<?php
require_once '../database/db.php';
include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';

// ==== Handle FOB Loading Ports ====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_fob'])) {
    $stmt = $pdo->prepare("INSERT INTO shipping_ports (port_name, shipping_method, fob_cost_per_ton, notes) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_POST['port_name'], $_POST['shipping_method'], $_POST['fob_cost_per_ton'], $_POST['notes']]);
    header("Location: shipping.php?tab=fob");
    exit;
}

if (isset($_GET['delete_fob'])) {
    $stmt = $pdo->prepare("DELETE FROM shipping_ports WHERE id = ?");
    $stmt->execute([$_GET['delete_fob']]);
    header("Location: shipping.php?tab=fob");
    exit;
}

$fob_ports = $pdo->query("SELECT * FROM shipping_ports ORDER BY port_name ASC")->fetchAll();

// ==== Handle CIF Destination Ports ====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_cif'])) {
    $stmt = $pdo->prepare("INSERT INTO destination_ports (port_name, shipping_method, cif_cost_per_ton, notes) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_POST['port_name'], $_POST['shipping_method'], $_POST['cif_cost_per_ton'], $_POST['notes']]);
    header("Location: shipping.php?tab=cif");
    exit;
}

if (isset($_GET['delete_cif'])) {
    $stmt = $pdo->prepare("DELETE FROM destination_ports WHERE id = ?");
    $stmt->execute([$_GET['delete_cif']]);
    header("Location: shipping.php?tab=cif");
    exit;
}

$cif_ports = $pdo->query("SELECT * FROM destination_ports ORDER BY port_name ASC")->fetchAll();

$activeTab = $_GET['tab'] ?? 'fob';
?>

<div class="container mt-4">
  <h2>Shipping Settings (FOB & CIF)</h2>

  <ul class="nav nav-tabs mb-3">
    <li class="nav-item">
      <a class="nav-link <?= $activeTab == 'fob' ? 'active' : '' ?>" href="?tab=fob">FOB – Loading Ports (China)</a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?= $activeTab == 'cif' ? 'active' : '' ?>" href="?tab=cif">CIF – Destination Ports</a>
    </li>
  </ul>

  <?php if ($activeTab == 'fob'): ?>
    <!-- =================== FOB FORM =================== -->
    <form method="POST" class="form-inline mb-4">
      <input type="text" name="port_name" class="form-control mr-2" placeholder="Port Name (e.g. Tianjin)" required>
      <select name="shipping_method" class="form-control mr-2" required>
        <option value="bulk">Bulk</option>
        <option value="container">Container</option>
      </select>
      <input type="number" step="0.01" name="fob_cost_per_ton" class="form-control mr-2" placeholder="FOB Cost / Ton" required>
      <input type="text" name="notes" class="form-control mr-2" placeholder="Notes (optional)">
      <button type="submit" name="add_fob" class="btn btn-success">Add FOB</button>
    </form>

    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Port</th>
          <th>Method</th>
          <th>FOB Cost (RMB/Ton)</th>
          <th>Notes</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($fob_ports as $port): ?>
          <tr>
            <td><?= htmlspecialchars($port['port_name']) ?></td>
            <td><?= ucfirst($port['shipping_method']) ?></td>
            <td><?= $port['fob_cost_per_ton'] ?></td>
            <td><?= htmlspecialchars($port['notes']) ?></td>
            <td>
              <a href="?delete_fob=<?= $port['id'] ?>&tab=fob" onclick="return confirm('Delete this FOB port?')" class="btn btn-danger btn-sm">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

  <?php else: ?>
    <!-- =================== CIF FORM =================== -->
    <form method="POST" class="form-inline mb-4">
      <input type="text" name="port_name" class="form-control mr-2" placeholder="Destination Port (e.g. Alexandria)" required>
      <select name="shipping_method" class="form-control mr-2" required>
        <option value="bulk">Bulk</option>
        <option value="container">Container</option>
      </select>
      <input type="number" step="0.01" name="cif_cost_per_ton" class="form-control mr-2" placeholder="CIF Cost / Ton" required>
      <input type="text" name="notes" class="form-control mr-2" placeholder="Notes (optional)">
      <button type="submit" name="add_cif" class="btn btn-success">Add CIF</button>
    </form>

    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Port</th>
          <th>Method</th>
          <th>CIF Cost (RMB/Ton)</th>
          <th>Notes</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($cif_ports as $port): ?>
          <tr>
            <td><?= htmlspecialchars($port['port_name']) ?></td>
            <td><?= ucfirst($port['shipping_method']) ?></td>
            <td><?= $port['cif_cost_per_ton'] ?></td>
            <td><?= htmlspecialchars($port['notes']) ?></td>
            <td>
              <a href="?delete_cif=<?= $port['id'] ?>&tab=cif" onclick="return confirm('Delete this CIF port?')" class="btn btn-danger btn-sm">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
