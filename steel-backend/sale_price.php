<?php
require_once 'database/db.php';
require_once 'includes/functions.php';
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/sidebar.php';


// Load dropdown data
// Fetch materials and their base materials
$materials = $pdo->query("
  SELECT m.id, m.name, bm.name AS base_name, bm.price_per_ton
  FROM materials m
  JOIN base_materials bm ON m.base_material_id = bm.id
  ORDER BY m.name
")->fetchAll();
// Fetch thickness, coating, processing, packing, and shipping data
$widths = $pdo->query("SELECT width_mm FROM widths WHERE status = 'active' ORDER BY width_mm")->fetchAll();
$thicknesses = $pdo->query("SELECT id, thickness_mm FROM thickness_costs ORDER BY thickness_mm")->fetchAll();
$coatings = $pdo->query("SELECT id, name FROM coatings")->fetchAll();
$processing = $pdo->query("SELECT id, name FROM processing_costs")->fetchAll();
$packing = $pdo->query("SELECT id, name FROM packing_options")->fetchAll();
$shipping_ports = $pdo->query("SELECT id, port_name FROM shipping_ports")->fetchAll();
$destination_ports = $pdo->query("SELECT id, port_name FROM destination_ports")->fetchAll();

$breakdown = null;

// Handle calculation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputs = $_POST;
    $inputs['processing_ids'] = $_POST['processing_ids'] ?? [];
    $breakdown = calculate_price($pdo, $inputs);
}
?>



<div class="container mt-4">
  <h2><i class="fas fa-calculator mr-2"></i>Sale Price Calculator (Admin)</h2>

    <p class="text-muted">Use this form to calculate the sale price of steel materials based on various parameters.</p>
  <form method="POST" class="row g-3 mb-4">
    <div class="col-md-4">
      <label>Material Type</label>
      <select name="material_id" class="form-control" required>
  <option value="">Select Material</option>
  <?php foreach ($materials as $m): ?>
    <option value="<?= $m['id'] ?>">
      <?= htmlspecialchars($m['name']) ?> — Base: <?= htmlspecialchars($m['base_name']) ?> @ ¥<?= number_format($m['price_per_ton'], 2) ?>/ton
    </option>
  <?php endforeach; ?>
</select>
    </div>

    <div class="col-md-4">
      <label>Thickness (mm)</label>
      <select name="thickness_id" class="form-control" required>
        <?php foreach ($thicknesses as $t): ?>
          <option value="<?= $t['id'] ?>"><?= $t['thickness_mm'] ?> mm</option>
        <?php endforeach; ?>
      </select>
    </div>
  
    <div class="col-md-4">
  <label>Width (mm)</label>
  <select name="width" class="form-control" required>
    <option value="">Select Width</option>
    <?php foreach ($widths as $w): ?>
      <option value="<?= $w['width_mm'] ?>"><?= $w['width_mm'] ?> mm</option>
    <?php endforeach; ?>
  </select>
</div>


    <div class="col-md-4">
      <label>Zinc Weight (g/m²)</label>
      <input type="number" name="zinc" class="form-control" placeholder="e.g. 60" required>
    </div>

    <div class="col-md-4">
      <label>Coating</label>
      <select name="coating_id" class="form-control">
        <option value="">None</option>
        <?php foreach ($coatings as $c): ?>
          <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="col-md-8">
      <label>Processing Add-ons</label><br>
      <?php foreach ($processing as $p): ?>
        <label class="mr-3">
          <input type="checkbox" name="processing_ids[]" value="<?= $p['id'] ?>"> <?= $p['name'] ?>
        </label>
      <?php endforeach; ?>
    </div>

    <div class="col-md-4">
      <label>Packing Type</label>
      <select name="packing_id" class="form-control" required>
        <?php foreach ($packing as $p): ?>
          <option value="<?= $p['id'] ?>"><?= $p['name'] ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="col-md-4">
      <label>Shipping Type</label>
      <select name="shipping_type" class="form-control" required>
        <option value="EXW">EXW (No Shipping)</option>
        <option value="FOB">FOB (China Port)</option>
        <option value="CIF">CIF (Destination Port)</option>
      </select>
    </div>

    <div class="col-md-4">
      <label>Loading Port (FOB)</label>
      <select name="shipping_port_id" class="form-control">
        <option value="">Select Port</option>
        <?php foreach ($shipping_ports as $sp): ?>
          <option value="<?= $sp['id'] ?>"><?= $sp['port_name'] ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="col-md-4">
      <label>Destination Port (CIF)</label>
      <select name="destination_port_id" class="form-control">
        <option value="">Select Port</option>
        <?php foreach ($destination_ports as $dp): ?>
          <option value="<?= $dp['id'] ?>"><?= $dp['port_name'] ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="col-md-4">
      <label>Profit Type</label>
      <select name="profit_type" class="form-control" required>
        <option value="fixed">Fixed (RMB)</option>
        <option value="percent">Percentage (%)</option>
      </select>
    </div>

    <div class="col-md-4">
      <label>Profit Value</label>
      <input type="number" name="profit_value" class="form-control" placeholder="e.g. 8 or 200" required>
    </div>

    <div class="col-12">
      <button type="submit" class="btn btn-primary mt-3">Calculate</button>
    </div>
  </form>

  <?php if ($breakdown): ?>
    <div class="alert alert-info mt-4">
      <h4>Cost Breakdown (RMB / Ton)</h4>
      <ul>
        <li>Material + Rolling: <strong>¥<?= number_format($breakdown['base_cost'], 2) ?></strong></li>
        <li>Zinc: <strong>¥<?= number_format($breakdown['zinc_cost'], 2) ?></strong></li>
        <li>Coating: <strong>¥<?= number_format($breakdown['coating_cost'], 2) ?></strong></li>
        <li>Processing: <strong>¥<?= number_format($breakdown['processing'], 2) ?></strong></li>
        <li>Packing: <strong>¥<?= number_format($breakdown['packing'], 2) ?></strong></li>
        <li>Shipping: <strong>¥<?= number_format($breakdown['shipping'], 2) ?></strong></li>
        <li>Total Cost: <strong>¥<?= number_format($breakdown['total_cost'], 2) ?></strong></li>
        <li>Profit: <strong>¥<?= number_format($breakdown['profit'], 2) ?></strong></li>
      </ul>
      <h5 class="mt-3">Final Sale Price:</h5>
      <p>
        <strong>RMB:</strong> ¥<?= number_format($breakdown['final_rmb'], 2) ?><br>
        <strong>USD:</strong> $<?= number_format($breakdown['final_usd'], 2) ?> (Rate: 7.25)
      </p>
    </div>
  <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
