<?php
require_once '../database/db.php';
include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_packing'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $unit_type = $_POST['unit_type'];
    $cost = $_POST['cost'];

    $stmt = $pdo->prepare("UPDATE packing_options SET name = ?, unit_type = ?, cost = ? WHERE id = ?");
    $stmt->execute([$name, $unit_type, $cost, $id]);
    header("Location: packing.php?success=updated");
    exit;
}

// Handle add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_packing'])) {
    $name = $_POST['name'];
    $unit_type = $_POST['unit_type'];
    $cost = $_POST['cost'];

    $stmt = $pdo->prepare("INSERT INTO packing_options (name, unit_type, cost) VALUES (?, ?, ?)");
    $stmt->execute([$name, $unit_type, $cost]);
    header("Location: packing.php?success=added");
    exit;
}

// Handle delete
if (isset($_GET['delete_id'])) {
    $id = (int) $_GET['delete_id'];
    $pdo->prepare("DELETE FROM packing_options WHERE id = ?")->execute([$id]);
    header("Location: packing.php?success=deleted");
    exit;
}

// Handle edit
$editData = null;
if (isset($_GET['edit_id'])) {
    $id = (int) $_GET['edit_id'];
    $stmt = $pdo->prepare("SELECT * FROM packing_options WHERE id = ?");
    $stmt->execute([$id]);
    $editData = $stmt->fetch();
}

// Fetch all
$packingList = $pdo->query("SELECT * FROM packing_options ORDER BY id DESC")->fetchAll();
?>

<div class="container mt-4">
  <h2>Packing Types & Costs</h2>

  <?php if ($editData): ?>
    <form method="POST" class="form-inline mb-4 bg-light p-3 rounded">
      <input type="hidden" name="id" value="<?= $editData['id'] ?>">
      <input type="text" name="name" class="form-control mr-2" value="<?= htmlspecialchars($editData['name']) ?>" required>
      <select name="unit_type" class="form-control mr-2" required>
        <option value="per_ton" <?= $editData['unit_type'] == 'per_ton' ? 'selected' : '' ?>>Per Ton</option>
        <option value="per_pack" <?= $editData['unit_type'] == 'per_pack' ? 'selected' : '' ?>>Per Pack</option>
        <option value="per_sheet" <?= $editData['unit_type'] == 'per_sheet' ? 'selected' : '' ?>>Per Sheet</option>
      </select>
      <input type="number" step="0.01" name="cost" class="form-control mr-2" value="<?= $editData['cost'] ?>" required>
      <button type="submit" name="update_packing" class="btn btn-primary">Update</button>
      <a href="packing.php" class="btn btn-secondary ml-2">Cancel</a>
    </form>
  <?php else: ?>
    <form method="POST" class="form-inline mb-4">
      <input type="text" name="name" class="form-control mr-2" placeholder="Packing Type (e.g. Coil Wrap)" required>
      <select name="unit_type" class="form-control mr-2" required>
        <option value="">Select Unit</option>
        <option value="per_ton">Per Ton</option>
        <option value="per_pack">Per Pack</option>
        <option value="per_sheet">Per Sheet</option>
      </select>
      <input type="number" step="0.01" name="cost" class="form-control mr-2" placeholder="Cost (RMB)" required>
      <button type="submit" name="add_packing" class="btn btn-success">Add</button>
    </form>
  <?php endif; ?>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Packing Type</th>
        <th>Unit</th>
        <th>Cost (RMB)</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($packingList as $p): ?>
        <tr>
          <td><?= htmlspecialchars($p['name']) ?></td>
          <td><?= strtoupper(str_replace('_', ' ', $p['unit_type'])) ?></td>
          <td><?= $p['cost'] ?></td>
          <td>
            <a href="?edit_id=<?= $p['id'] ?>" class="btn btn-warning btn-sm mr-1">Edit</a>
            <a href="?delete_id=<?= $p['id'] ?>" onclick="return confirm('Delete this packing option?')" class="btn btn-danger btn-sm">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include '../includes/footer.php'; ?>
