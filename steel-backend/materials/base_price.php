<?php
require_once '../database/db.php';
include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';

// Handle add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_base_price'])) {
    $label = $_POST['label'];
    $price = $_POST['price_per_ton'];
    $stmt = $pdo->prepare("INSERT INTO base_price (label, price_per_ton, updated_at) VALUES (?, ?, NOW())");
    $stmt->execute([$label, $price]);
    header("Location: base_price.php?success=added");
    exit;
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
    $id = $_POST['update_id'];
    $price = $_POST['price_per_ton'];
    $stmt = $pdo->prepare("UPDATE base_price SET price_per_ton = ?, updated_at = NOW() WHERE id = ?");
    $stmt->execute([$price, $id]);
    header("Location: base_price.php?success=updated");
    exit;
}

$edit_id = $_GET['edit_id'] ?? null;
$edit_row = null;
if ($edit_id) {
    $stmt = $pdo->prepare("SELECT * FROM base_price WHERE id = ?");
    $stmt->execute([$edit_id]);
    $edit_row = $stmt->fetch();
}

$prices = $pdo->query("SELECT * FROM base_price ORDER BY label ASC")->fetchAll();
?>

<div class="container mt-4">
  <h2>Material Base Prices (RMB / Ton)</h2>

  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">Action completed successfully.</div>
  <?php endif; ?>

  <!-- Add new base price -->
  <form method="POST" class="form-inline bg-light p-3 rounded mb-4">
    <input type="text" name="label" class="form-control mr-2" placeholder="Material Label (e.g. Galvanized)" required>
    <input type="number" step="0.01" name="price_per_ton" class="form-control mr-2" placeholder="Price per Ton" required>
    <button type="submit" name="add_base_price" class="btn btn-success">Add New Base Price</button>
  </form>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Material</th>
        <th>Price (RMB/Ton)</th>
        <th>Last Updated</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($prices as $row): ?>
      <tr>
        <?php if ($edit_row && $edit_row['id'] == $row['id']): ?>
          <!-- Edit Mode -->
          <form method="POST">
            <td><?= htmlspecialchars($row['label']) ?></td>
            <td>
              <input type="hidden" name="update_id" value="<?= $row['id'] ?>">
              <input type="number" step="0.01" name="price_per_ton" value="<?= $row['price_per_ton'] ?>" class="form-control" required>
            </td>
            <td><?= $row['updated_at'] ?></td>
            <td>
              <button type="submit" class="btn btn-primary btn-sm">Save</button>
              <a href="base_price.php" class="btn btn-secondary btn-sm">Cancel</a>
            </td>
          </form>
        <?php else: ?>
          <!-- View Mode -->
          <td><?= htmlspecialchars($row['label']) ?></td>
          <td>¥<?= number_format($row['price_per_ton'], 2) ?></td>
          <td><?= $row['updated_at'] ?></td>
          <td>
            <a href="?edit_id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
          </td>
        <?php endif; ?>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include '../includes/footer.php'; ?>
