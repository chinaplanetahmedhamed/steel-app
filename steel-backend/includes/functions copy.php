
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

// Get base price from base_materials via materials
$stmt = $pdo->prepare("
  SELECT bm.price_per_ton
  FROM materials m
  JOIN base_materials bm ON m.base_material_id = bm.id
  WHERE m.id = ?
");
$stmt->execute([$inputs['material_id']]);
$base_price = (float)$stmt->fetchColumn();

// Get thickness info (thickness in mm + cold rolling)
$stmt = $pdo->prepare("SELECT cold_rolling_cost, thickness_mm FROM thickness_costs WHERE id = ?");
$stmt->execute([$inputs['thickness_id']]);
$thickness = $stmt->fetch();

$cold_rolling = (float)$thickness['cold_rolling_cost'];
$thickness_mm = (float)$thickness['thickness_mm'];
$width_mm = (float)$inputs['width'];  // passed from frontend (via dropdown or input)

// Calculate area per ton based on width + thickness
$area_per_ton = 1000000 / ($width_mm * $thickness_mm * 7.85);

// Zinc cost logic
$stmt = $pdo->prepare("SELECT base_zinc_g, extra_10g_cost_per_m2 FROM zinc_costs WHERE material_id = ?");
$stmt->execute([$inputs['material_id']]);
$zinc = $stmt->fetch();

if (!$zinc) {
    $zinc_cost = 0;
} else {
    $extra = max(0, $inputs['zinc'] - $zinc['base_zinc_g']);
    $zinc_cost = ($extra / 10) * $zinc['extra_10g_cost_per_m2'] * $area_per_ton;
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
    $final_usd = $final_rmb / 7.25; // static rate for now

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
    ];
}
