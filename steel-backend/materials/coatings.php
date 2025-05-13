<?php
require_once '../database/db.php';
include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';

// Update coating
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_coating'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $type = $_POST['type'];
    $price = $_POST['price_per_m2'];

    $stmt = $pdo->prepare("UPDATE coatings SET name = ?, type = ?, price_per_m2 = ? WHERE id = ?");
    $stmt->execute([$name, $type, $price, $id]);
    header("Location: coatings.php?success=updated");
    exit;
}

// Fetch coating to edit
$editData = null;
if (isset($_GET['edit_id'])) {
    $edit_id = (int) $_GET['edit_id'];
    $stmt = $pdo->prepare("SELECT * FROM coatings WHERE id = ?");
    $stmt->execute([$edit_id]);
    $editData = $stmt->fetch();
}

// Add coating
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_coating'])) {
    $name = $_POST['name'];
    $type = $_POST['type'];
    $price = $_POST['price_per_m2'];

    $stmt = $pdo->prepare("INSERT INTO coatings (name, type, price_per_m2) VALUES (?, ?, ?)");
    $stmt->execute([$name, $type, $price]);
    header("Location: coatings.php?success=added");
    exit;
}

// Delete
if (isset($_GET['delete_id'])) {
    $id = (int) $_GET['delete_id'];
    $pdo->prepare("DELETE FROM coatings WHERE id = ?")->execute([$id]);
    header("Location: coatings.php?success=deleted");
    exit;
}

// Fetch all
$coatings = $pdo->query("SELECT * FROM coatings ORDER BY type ASC, name ASC")->fetchAll();
?>

<div class="container mt-4">
  <h2>Coating / Paint Options</h2>

<?php if ($editData): ?>
  <form method="POST" class="form-inline mb-4 bg-light p-3 rounded">
    <input type="hidden" name="id" value="<?= $editData['id'] ?>">
    <input type="text" name="name" class="form-control mr-2" value="<?= htmlspecialchars($editData['name']) ?>" required>
    <select name="type" class="form-control mr-2" required>
      <option value="standard" <?= $editData['type'] == 'standard' ? 'selected' : '' ?>>Standard</option>
      <option value="fingerprint" <?= $editData['type'] == 'fingerprint' ? 'selected' : '' ?>>Fingerprint</option>
      <option value="wrinkled" <?= $editData['type'] == 'wrinkled' ? 'selected' : '' ?>>Wrinkled</option>
      <option value="PVDF" <?= $editData['type'] == 'PVDF' ? 'selected' : '' ?>>PVDF</option>
    </select>
    <input type="number" step="0.01" name="price_per_m2" class="form-control mr-2" value="<?= $editData['price_per_m2'] ?>" required>
    <button type="submit" name="update_coating" class="btn btn-primary">Update</button>
    <a href="coatings.php" class="btn btn-secondary ml-2">Cancel</a>
  </form>
<?php else: ?>
  <form method="POST" class="form-inline mb-4">
    <input type="text" name="name" class="form-control mr-2" placeholder="Name (e.g., Red Paint)" required>
    <select name="type" class="form-control mr-2" required>
      <option value="">Type</option>
      <option value="standard">Standard Paint</option>
      <option value="fingerprint">Fingerprint</option>
      <option value="wrinkled">Wrinkled</option>
      <option value="PVDF">PVDF</option>
    </select>
    <input type="number" step="0.01" name="price_per_m2" class="form-control mr-2" placeholder="Price per m²" required>
    <button type="submit" name="add_coating" class="btn btn-success">Add</button>
  </form>
<?php endif; ?>

  <table class="table table-bordered">
    <thead>
        
      <tr>
        <th>Name</th>
        <th>Type</th>
        <th>Price (RMB/m²)</th>
        <th>Actions</th>
<td>
  <a href="?edit_id=<?= $c['id'] ?>" class="btn btn-warning btn-sm mr-1">Edit</a>
  <a href="?delete_id=<?= $c['id'] ?>" onclick="return confirm('Delete this coating option?')" class="btn btn-danger btn-sm">Delete</a>
</td>

      </tr>
    </thead>
    
    <tbody>
        
      <?php foreach ($coatings as $c): ?>
        <tr>
          <td><?= htmlspecialchars($c['name']) ?></td>
          <td><?= ucfirst($c['type']) ?></td>
          <td><?= $c['price_per_m2'] ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
    
  </table>
  
</div>

<?php include '../includes/footer.php'; ?>
