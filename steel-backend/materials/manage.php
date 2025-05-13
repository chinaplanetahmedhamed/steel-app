<?php
ob_start();
require_once '../database/db.php';
include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
$base_materials = $pdo->query("SELECT id, name FROM base_materials ORDER BY name")->fetchAll();
// Update material
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_material'])) {
  $id = $_POST['id'];
  $name = $_POST['name'];
  $unit = $_POST['unit'];
  $base_id = $_POST['base_material_id'];
  $stmt = $pdo->prepare("UPDATE materials SET name = ?, unit = ?, base_material_id = ? WHERE id = ?");
  $stmt->execute([$name, $unit, $base_id, $id]);
  header("Location: manage.php?success=updated");
  exit;
}
// Toggle status (active/inactive)
if (isset($_GET['toggle_id'])) {
  $id = (int) $_GET['toggle_id'];
  $stmt = $pdo->prepare("SELECT status FROM materials WHERE id = ?");
  $stmt->execute([$id]);
  $currentStatus = $stmt->fetchColumn();
  $newStatus = ($currentStatus === 'active') ? 'inactive' : 'active';
  $pdo->prepare("UPDATE materials SET status = ? WHERE id = ?")->execute([$newStatus, $id]);
  header("Location: manage.php?success=status-updated");
  exit;
}
// Fetch for editing
$editData = null;
if (isset($_GET['edit_id'])) {
  $edit_id = (int) $_GET['edit_id'];
  $stmt = $pdo->prepare("SELECT * FROM materials WHERE id = ?");
  $stmt->execute([$edit_id]);
  $editData = $stmt->fetch();
}
// Add new material
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_material'])) {
  $name = $_POST['name'];
  $unit = $_POST['unit'] ?? 'ton';
  $base_id = $_POST['base_material_id'];
  $stmt = $pdo->prepare("INSERT INTO materials (name, unit, base_material_id) VALUES (?, ?, ?)");
  $stmt->execute([$name, $unit, $base_id]);
  header("Location: manage.php?success=added");
  exit;
}
// Delete material
if (isset($_GET['delete_id'])) {
  $id = (int) $_GET['delete_id'];
  $pdo->prepare("DELETE FROM materials WHERE id = ?")->execute([$id]);
  header("Location: manage.php?success=deleted");
  exit;
}
// Fetch all materials
$materials = $pdo->query(

  "SELECT m.*, bm.name AS base_material_name FROM materials m
  LEFT JOIN base_materials bm ON m.base_material_id = bm.id
  ORDER BY m.id DESC "
)->fetchAll();
?>
<div class="container mt-4">
  <h2>Manage Steel Materials</h2>
  <!-- Add/Edit Form -->
  <?php if ($editData): ?>
    <form method="POST" class="mb-4 bg-light p-3 rounded">
      <input type="hidden" name="id" value="<?= $editData['id'] ?>">
      <div class="form-row">
        <div class="col">
          <select name="base_material_id" class="form-control" required>
            <option value="">Select Base Material</option>
            <?php foreach ($base_materials as $bm): ?>
              <option value="<?= $bm['id'] ?>"
                <?= ($editData && $editData['base_material_id'] == $bm['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($bm['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col">
          <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($editData['name']) ?>" required>
        </div>
        <div class="col">
          <select name="unit" class="form-control">
            <option value="ton" <?= $editData['unit'] == 'ton' ? 'selected' : '' ?>>Ton</option>
            <option value="kg" <?= $editData['unit'] == 'kg' ? 'selected' : '' ?>>Kg</option>
            <option value="sheet" <?= $editData['unit'] == 'sheet' ? 'selected' : '' ?>>Sheet</option>
          </select>
        </div>
        <div class="col">
          <button type="submit" name="update_material" class="btn btn-primary">Update</button>
          <a href="manage.php" class="btn btn-secondary ml-2">Cancel</a>
        </div>
      </div>
    </form>
  <?php else: ?>
    <form method="POST" class="mb-4">
      <div class="form-row">
        <div class="col">
          <input type="text" name="name" class="form-control" placeholder="Material Name" required>
        </div>
        <div class="col">
          <select name="base_material_id" class="form-control" required>
            <option value="">Select Base Material</option>
            <?php foreach ($base_materials as $bm): ?>
              <option value="<?= $bm['id'] ?>"><?= htmlspecialchars($bm['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col">
          <select name="unit" class="form-control">
            <option value="ton">Ton</option>
            <option value="kg">Kg</option>
            <option value="sheet">Sheet</option>
          </select>
        </div>
        <div class="col">
          <button type="submit" name="add_material" class="btn btn-success">Add</button>
        </div>
      </div>
    </form>
  <?php endif; ?>
  <!-- Material Table -->
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Base Material</th>
        <th>ID</th>
        <th>Material</th>
        <th>Unit</th>
        <th>Status</th>
        <th>Created</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($materials as $row): ?>
        <tr>
          <td><?= htmlspecialchars($row['base_material_name']) ?></td>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= $row['unit'] ?></td>
          <td>
            <span class="badge badge-<?= $row['status'] === 'active' ? 'success' : 'secondary' ?>">
              <?= ucfirst($row['status']) ?>
            </span>
          </td>
          <td><?= $row['created_at'] ?></td>
          <td>
            <a href="?toggle_id=<?= $row['id'] ?>" class="btn btn-info btn-sm mr-1">
              <?= $row['status'] === 'active' ? 'Deactivate' : 'Activate' ?>
            </a>
            <a href="?edit_id=<?= $row['id'] ?>" class="btn btn-warning btn-sm mr-1">Edit</a>
            <a href="?delete_id=<?= $row['id'] ?>" onclick="return confirm('Delete this material?')" class="btn btn-danger btn-sm">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
ob_end_flush();
<?php include '../includes/footer.php'; ?>