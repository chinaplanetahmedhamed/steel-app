<?php
ob_start();
require_once '../database/db.php';
include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';

// Fetch all materials
$materials = $pdo->query("SELECT id, name FROM materials ORDER BY name ASC")->fetchAll();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_thickness_entry'])) {
    $material_id = $_POST['material_id'];
    $thickness = $_POST['thickness_mm'];
    $stmt = $pdo->prepare("INSERT INTO thicknesses (material_id, thickness_mm) VALUES (?, ?)");
    $stmt->execute([$material_id, $thickness]);
    header("Location: thicknesses.php?success=1");
    exit;
}

// Handle delete
if (isset($_GET['delete_id'])) {
    $id = (int) $_GET['delete_id'];
    $pdo->prepare("DELETE FROM thicknesses WHERE id = ?")->execute([$id]);
    header("Location: thicknesses.php?deleted=1");
    exit;
}

// Fetch all thicknesses
$rows = $pdo->query("
    SELECT t.id, t.thickness_mm, m.name AS material_name
    FROM thicknesses t
    JOIN materials m ON t.material_id = m.id
    ORDER BY m.name ASC, t.thickness_mm ASC
")->fetchAll();
?>

<div class="container mt-4">
  <h2>Material Thicknesses</h2>

  <form method="POST" class="form-inline mb-4">
    <select name="material_id" class="form-control mr-2" required>
      <option value="">Select Material</option>
      <?php foreach ($materials as $mat): ?>
        <option value="<?= $mat['id'] ?>"><?= htmlspecialchars($mat['name']) ?></option>
      <?php endforeach; ?>
    </select>
    <input type="number" step="0.01" name="thickness_mm" class="form-control mr-2" placeholder="Thickness (mm)" required>
    <button type="submit" name="add_thickness_entry" class="btn btn-primary">Add Thickness</button>
  </form>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Material</th>
        <th>Thickness (mm)</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($rows as $row): ?>
        <tr>
          <td><?= htmlspecialchars($row['material_name']) ?></td>
          <td><?= htmlspecialchars($row['thickness_mm']) ?></td>
          <td><a href="?delete_id=<?= $row['id'] ?>" onclick="return confirm('Delete this thickness?')" class="btn btn-danger btn-sm">Delete</a></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
ob_end_flush();
<?php include '../includes/footer.php'; ?>