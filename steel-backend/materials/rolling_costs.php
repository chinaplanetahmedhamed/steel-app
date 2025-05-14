<?php
ob_start();
require_once '../database/db.php';
include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';

// Fetch materials for the dropdown
$materials = $pdo->query("SELECT id, name FROM materials ORDER BY name ASC")->fetchAll();
$thicknesses = $pdo->query("SELECT id, thickness_mm FROM thicknesses ORDER BY thickness_mm ASC")->fetchAll();

// Add new rolling cost entry
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_rolling_costs'])) {
    $material_id = $_POST['material_id'];
    $thickness_id = $_POST['thickness_id'];
    $cold_cost = $_POST['cold_rolling_cost'];

    $stmt = $pdo->prepare("INSERT INTO rolling_costs (material_id, thickness_id, rolling_cost) VALUES (?, ?, ?)");
    $stmt->execute([$material_id, $thickness_id, $cold_cost]);
    header("Location: rolling_costs.php?success=added");
    exit;
}
// Handle Edit Submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_rolling_costs'])) {
    $id = $_POST['id'];
    $material_id = $_POST['material_id'];
    $thickness_id = $_POST['thickness_id'];
    $cold_cost = $_POST['cold_rolling_cost'];

    $stmt = $pdo->prepare("UPDATE rolling_costs SET material_id = ?, thickness_id = ?, rolling_cost = ? WHERE id = ?");
    $stmt->execute([$material_id, $thickness_id, $cold_cost, $id]);
    header("Location: rolling_costs.php?success=updated");
    exit;
}

// Fetch single record for editing
$editData = null;
if (isset($_GET['edit_id'])) {
    $edit_id = (int)$_GET['edit_id'];
    $stmt = $pdo->prepare("SELECT * FROM rolling_costs WHERE id = ?");
    $stmt->execute([$edit_id]);
    $editData = $stmt->fetch();
}

// Delete entry
if (isset($_GET['delete_id'])) {
    $id = (int) $_GET['delete_id'];
    $pdo->prepare("DELETE FROM rolling_costs WHERE id = ?")->execute([$id]);
    header("Location: rolling_costs.php?success=deleted");
    exit;
}

// Fetch all thickness records with material name
$thicknessList = $pdo->query("
    SELECT r.id, r.rolling_cost, m.name AS material_name, t.thickness_mm
    FROM rolling_costs r
    JOIN materials m ON r.material_id = m.id
    JOIN thicknesses t ON r.thickness_id = t.id
    ORDER BY m.name ASC, t.thickness_mm ASC
")->fetchAll();
?>

<div class="content-wrapper p-3">
  <section class="content">
    <div class="container-fluid">
      <h3 class="mb-4">Manage Cold Rolling Cost</h3>

     <?php if ($editData): ?>
  <form method="POST" class="form-inline mb-4 bg-light p-3 rounded">
    <input type="hidden" name="id" value="<?= $editData['id'] ?>">
    <select name="material_id" class="form-control mr-2" required>
      <?php foreach ($materials as $mat): ?>
        <option value="<?= $mat['id'] ?>" <?= $editData['material_id'] == $mat['id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($mat['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
    <select name="thickness_id" class="form-control mr-2" required>
      <option value="">Select Thickness</option>
      <?php foreach ($thicknesses as $th): ?>
        <option value="<?= $th['id'] ?>" <?= $editData['thickness_id'] == $th['id'] ? 'selected' : '' ?>>
          <?= $th['thickness_mm'] ?> mm
        </option>
      <?php endforeach; ?>
    </select>
    <input type="number" step="0.01" name="cold_rolling_cost" class="form-control mr-2" value="<?= $editData['rolling_cost'] ?>" required>
    <button type="submit" name="update_rolling_costs" class="btn btn-primary">Update</button>
    <a href="rolling_costs.php" class="btn btn-secondary ml-2">Cancel</a>
  </form>
<?php else: ?>
  <form method="POST" class="form-inline mb-4">
    <select name="material_id" class="form-control mr-2" required>
      <option value="">Select Material</option>
      <?php foreach ($materials as $mat): ?>
        <option value="<?= $mat['id'] ?>"><?= htmlspecialchars($mat['name']) ?></option>
      <?php endforeach; ?>
    </select>
    <select name="thickness_id" class="form-control mr-2" required>
      <option value="">Select Thickness</option>
      <?php foreach ($thicknesses as $th): ?>
        <option value="<?= $th['id'] ?>"><?= $th['thickness_mm'] ?> mm</option>
      <?php endforeach; ?>
    </select>
    <input type="number" step="0.01" name="cold_rolling_cost" placeholder="Rolling Cost (RMB)" class="form-control mr-2" required>
    <button type="submit" name="add_rolling_costs" class="btn btn-success">Add</button>
  </form>
<?php endif; ?>

                

      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Material</th>
            <th>Thickness (mm)</th>
            <th>Cold Rolling Cost (RMB)</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($thicknessList as $row): ?>
            <tr>
              <td><?= htmlspecialchars($row['material_name']) ?></td>
              <td><?= $row['thickness_mm'] ?></td>
              <td><?= $row['rolling_cost'] ?></td>
              <td>
                <a href="?edit_id=<?= $row['id'] ?>" class="btn btn-warning btn-sm mr-1">Edit</a>
                <a href="?delete_id=<?= $row['id'] ?>" onclick="return confirm('Delete this entry?')" class="btn btn-danger btn-sm">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

    </div>
  </section>
</div>
ob_end_flush();
<?php include '../includes/footer.php'; ?>
