<?php
function calculate_price($pdo, $inputs) {
    // Base material
    // Get material name by ID
$stmt = $pdo->prepare("SELECT name FROM materials WHERE id = ?");
$stmt->execute([$inputs['material_id']]);
$material_name = $stmt->fetchColumn();

// Get base price by joining materials and base_materials
$stmt = $pdo->prepare("
    SELECT bm.price_per_ton
    FROM materials m
    JOIN base_materials bm ON m.base_material_id = bm.id
    WHERE m.id = ?
");
$stmt->execute([$inputs['material_id']]);
$base_price = (float)$stmt->fetchColumn();

function getExtraZincCostPerSqm($pdo, $material_id) {
    $stmt = $pdo->prepare("SELECT extra_10g_cost_per_sqm FROM extra_zinc_costs WHERE material_id = ?");
    $stmt->execute([$material_id]);
    return $stmt->fetchColumn() ?: 0;
}


// Get thickness info and cold rolling cost
$stmt = $pdo->prepare("SELECT t.thickness_mm, rc.rolling_cost
    FROM thicknesses t
    LEFT JOIN rolling_costs rc ON t.id = rc.thickness_id
    WHERE t.id = ?");
$stmt->execute([$inputs['thickness_id']]);
$thickness = $stmt->fetch();

$cold_rolling = (float)$thickness['rolling_cost'];
$thickness_mm = (float)$thickness['thickness_mm'];
$width_mm = (float)$inputs['width'];  // passed from frontend (via dropdown or input)

// Calculate area per ton based on width + thickness
$area_per_ton = 1000000 / ($width_mm * $thickness_mm * 7.85);


// Get base zinc cost (per ton) from zinc_costs table, joined with thicknesses and filtered by material and thickness
$stmt = $pdo->prepare("
    SELECT z.base_zinc_g, z.base_zinc_cost
    FROM zinc_costs z
    JOIN thicknesses t ON z.thickness_id = t.id
    WHERE t.material_id = ? AND z.thickness_id = ?
");
$stmt->execute([$inputs['material_id'], $inputs['thickness_id']]);
$zinc = $stmt->fetch();

// Get extra 10g cost per sqm from extra_zinc_costs table (depends only on material_id)
$stmt = $pdo->prepare("SELECT extra_10g_cost_per_sqm FROM extra_zinc_costs WHERE material_id = ?");
$stmt->execute([$inputs['material_id']]);
$extra_cost_per_sqm = (float)$stmt->fetchColumn();

if (!$zinc) {
    $zinc_cost = 0;
} else {
$zinc_step = ceil(($inputs['zinc'] - $zinc['base_zinc_g']) / 10);
$zinc_step = max(0, $zinc_step);
$zinc_cost = $zinc['base_zinc_cost'] + ($zinc_step * $extra_cost_per_sqm * $area_per_ton);
}


    // Coating
    $stmt = $pdo->prepare("SELECT price_per_m2 FROM coatings WHERE id = ?");
    $stmt->execute([$inputs['coating_id']]);
    $coating_cost = (float)$stmt->fetchColumn();

    // Processing
    $processing_total = 0;
    if (!empty($inputs['processing_ids'])) {
        $in = implode(',', array_fill(0, count($inputs['processing_ids']), '?'));
        $stmt = $pdo->prepare("SELECT cost_type, cost_value FROM processing_costs WHERE id IN ($in)");
        $stmt->execute($inputs['processing_ids']);
        foreach ($stmt->fetchAll() as $row) {
            $processing_total += ($row['cost_type'] === 'per_ton') ? $row['cost_value'] : 0;
        }
    }

    // Packing
    $stmt = $pdo->prepare("SELECT cost, unit_type FROM packing_options WHERE id = ?");
    $stmt->execute([$inputs['packing_id']]);
    $packing = $stmt->fetch();
    $packing_cost = $packing['unit_type'] === 'per_ton' ? $packing['cost'] : 0;

    // Shipping
    $shipping_cost = 0;
    if ($inputs['shipping_type'] === 'FOB') {
        $stmt = $pdo->prepare("SELECT fob_cost_per_ton FROM shipping_ports WHERE id = ?");
        $stmt->execute([$inputs['shipping_port_id']]);
        $shipping_cost = (float)$stmt->fetchColumn();
    } elseif ($inputs['shipping_type'] === 'CIF') {
        $stmt = $pdo->prepare("SELECT cif_cost_per_ton FROM destination_ports WHERE id = ?");
        $stmt->execute([$inputs['destination_port_id']]);
        $shipping_cost = (float)$stmt->fetchColumn();
    }

    // Total Cost
    $total_cost = $base_price + $cold_rolling + $zinc_cost + $coating_cost + $processing_total + $packing_cost + $shipping_cost;

    // Profit
    $profit = $inputs['profit_type'] === 'fixed'
        ? (float)$inputs['profit_value']
        : $total_cost * ((float)$inputs['profit_value'] / 100);

    $final_rmb = $total_cost + $profit;
    // Get exchange rate from database
    $usd_rate = get_exchange_rate($pdo);

    $final_usd = $final_rmb / $usd_rate;

    return [
        'base_cost'    => $base_price + $cold_rolling,
        'zinc_cost'    => $zinc_cost,
        'coating_cost' => $coating_cost,
        'processing'   => $processing_total,
        'packing'      => $packing_cost,
        'shipping'     => $shipping_cost,
        'total_cost'   => $total_cost,
        'profit'       => $profit,
        'final_rmb'    => $final_rmb,
        'final_usd'    => $final_usd,
        'sqm_per_ton'  => $area_per_ton,
    ];
}

function get_exchange_rate($pdo, $currency = 'USD', $fallback = 7.25) {
    $stmt = $pdo->prepare("SELECT rate FROM exchange_rate WHERE currency = ? ORDER BY updated_at DESC LIMIT 1");
    $stmt->execute([$currency]);
    return (float)($stmt->fetchColumn() ?: $fallback);
}
