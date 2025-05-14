<?php
ob_start();
require_once '../database/db.php';
include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';

// Handle Add Width
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_width'])) {
  $width = (int) $_POST['width_mm'];
  $stmt = $pdo->prepare("INSERT INTO widths (width_mm) VALUES (?)");
  $stmt->execute([$width]);
  header("Location: widths.php?success=added");
  exit;
}

// Handle Toggle Status
if (isset($_GET['toggle_id'])) {
  $id = (int) $_GET['toggle_id'];
  $stmt = $pdo->prepare("SELECT status FROM widths WHERE id = ?");
  $stmt->execute([$id]);
  $currentStatus = $stmt->fetchColumn();
  $newStatus = ($currentStatus === 'active') ? 'inactive' : 'active';
  $pdo->prepare("UPDATE widths SET status = ? WHERE id = ?")->execute([$newStatus, $id]);
  header("Location: widths.php?success=status-updated");
  exit;
}

// Handle Delete
if (isset($_GET['delete_id'])) {
  $id = (int) $_GET['delete_id'];
  $pdo->prepare("DELETE FROM widths WHERE id = ?")->execute([$id]);
  header("Location: widths.php?success=deleted");
  exit;
}

// Fetch All
$widths = $pdo->query("SELECT * FROM widths ORDER BY width_mm ASC")->fetchAll();
?>

<div class="container mt-4">
  <h2>Available Coil Widths (mm)</h2>

  <form method="POST" class="form-inline mb-4">
    <input type="number" name="width_mm" class="form-control mr-2" placeholder="e.g. 1000" required>
    <button type="submit" name="add_width" class="btn btn-success">Add Width</button>
  </form>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>ID</th>
        <th>Width (mm)</th>
        <th>Status</th>
        <th>Created At</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($widths as $row): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= $row['width_mm'] ?></td>
          <td>
            <span class="badge badge-<?= $row['status'] === 'active' ? 'success' : 'secondary' ?>">
              <?= ucfirst($row['status']) ?>
            </span>
          </td>
          <td><?= $row['created_at'] ?></td>
          <td>
            <a href="?toggle_id=<?= $row['id'] ?>" class="btn btn-sm btn-info">Toggle</a>
            <a href="?delete_id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this width?')">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
ob_end_flush();
<?php include '../includes/footer.php'; ?>
