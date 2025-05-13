<?php
require_once '../database/db.php';
include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_processing'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $type = $_POST['cost_type'];
    $value = $_POST['cost_value'];

    $stmt = $pdo->prepare("UPDATE processing_costs SET name = ?, cost_type = ?, cost_value = ? WHERE id = ?");
    $stmt->execute([$name, $type, $value, $id]);
    header("Location: processing.php?success=updated");
    exit;
}

// Handle add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_processing'])) {
    $name = $_POST['name'];
    $type = $_POST['cost_type'];
    $value = $_POST['cost_value'];

    $stmt = $pdo->prepare("INSERT INTO processing_costs (name, cost_type, cost_value) VALUES (?, ?, ?)");
    $stmt->execute([$name, $type, $value]);
    header("Location: processing.php?success=added");
    exit;
}

// Handle delete
if (isset($_GET['delete_id'])) {
    $id = (int) $_GET['delete_id'];
    $pdo->prepare("DELETE FROM processing_costs WHERE id = ?")->execute([$id]);
    header("Location: processing.php?success=deleted");
    exit;
}

// Edit data
$editData = null;
if (isset($_GET['edit_id'])) {
    $id = (int) $_GET['edit_id'];
    $stmt = $pdo->prepare("SELECT * FROM processing_costs WHERE id = ?");
    $stmt->execute([$id]);
    $editData = $stmt->fetch();
}

// Get all records
$processings = $pdo->query("SELECT * FROM processing_costs ORDER BY id DESC")->fetchAll();
?>

<div class="container mt-4">
  <h2>Processing Add-ons</h2>

  <?php if ($editData): ?>
    <form method="POST" class="form-inline mb-4 bg-light p-3 rounded">
      <input type="hidden" name="id" value="<?= $editData['id'] ?>">
      <input type="text" name="name" class="form-control mr-2" value="<?= htmlspecialchars($editData['name']) ?>" required>
      <select name="cost_type" class="form-control mr-2" required>
        <option value="per_ton" <?= $editData['cost_type'] == 'per_ton' ? 'selected' : '' ?>>Per Ton</option>
        <option value="per_m2" <?= $editData['cost_type'] == 'per_m2' ? 'selected' : '' ?>>Per m²</option>
      </select>
      <input type="number" step="0.01" name="cost_value" class="form-control mr-2" value="<?= $editData['cost_value'] ?>" required>
      <button type="submit" name="update_processing" class="btn btn-primary">Update</button>
      <a href="processing.php" class="btn btn-secondary ml-2">Cancel</a>
    </form>
  <?php else: ?>
    <form method="POST" class="form-inline mb-4">
      <input type="text" name="name" class="form-control mr-2" placeholder="Process Name (e.g. Cutting)" required>
      <select name="cost_type" class="form-control mr-2" required>
        <option value="">Select Cost Type</option>
        <option value="per_ton">Per Ton</option>
        <option value="per_m2">Per m²</option>
      </select>
      <input type="number" step="0.01" name="cost_value" class="form-control mr-2" placeholder="Cost Value (RMB)" required>
      <button type="submit" name="add_processing" class="btn btn-success">Add</button>
    </form>
  <?php endif; ?>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Process</th>
        <th>Cost Type</th>
        <th>Value (RMB)</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($processings as $p): ?>
        <tr>
          <td><?= htmlspecialchars($p['name']) ?></td>
          <td><?= strtoupper($p['cost_type']) ?></td>
          <td><?= $p['cost_value'] ?></td>
          <td>
            <a href="?edit_id=<?= $p['id'] ?>" class="btn btn-warning btn-sm mr-1">Edit</a>
            <a href="?delete_id=<?= $p['id'] ?>" onclick="return confirm('Delete this process?')" class="btn btn-danger btn-sm">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include '../includes/footer.php'; ?>
