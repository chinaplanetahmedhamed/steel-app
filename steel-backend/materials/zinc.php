<?php
ob_start();
require_once '../database/db.php';
include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';

// Fetch materials for dropdown
$materials = $pdo->query("SELECT id, name FROM materials ORDER BY name ASC")->fetchAll();

// Handle Add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_zinc'])) {
    $material_id = $_POST['material_id'];
    $base_zinc = $_POST['base_zinc_g'];
    $extra_cost = $_POST['extra_10g_cost'];

    $stmt = $pdo->prepare("INSERT INTO zinc_costs (material_id, base_zinc_g, extra_10g_cost_per_m2) VALUES (?, ?, ?)");
    $stmt->execute([$material_id, $base_zinc, $extra_cost]);
    header("Location: zinc.php?success=added");
    exit;
}

// Handle Delete
if (isset($_GET['delete_id'])) {
    $id = (int) $_GET['delete_id'];
    $pdo->prepare("DELETE FROM zinc_costs WHERE id = ?")->execute([$id]);
    header("Location: zinc.php?success=deleted");
    exit;
}

// Fetch Zinc Settings
$zincList = $pdo->query("
    SELECT z.id, z.base_zinc_g, z.extra_10g_cost_per_m2, m.name AS material_name
    FROM zinc_costs z
    JOIN materials m ON z.material_id = m.id
    ORDER BY m.name ASC
")->fetchAll();
?>

<div class="container mt-4">
  <h2>Zinc Coating Settings</h2>

  <form method="POST" class="form-inline mb-4">
    <select name="material_id" class="form-control mr-2" required>
      <option value="">Select Material</option>
      <?php foreach ($materials as $mat): ?>
        <option value="<?= $mat['id'] ?>"><?= htmlspecialchars($mat['name']) ?></option>
      <?php endforeach; ?>
    </select>
   <input type="number" name="base_zinc_g" class="form-control mr-2" placeholder="Base Zinc (g/m²)" required
  title="This is the default zinc coating included in the base cost. Typically 30g/m².">
<input type="number" step="0.01" name="extra_10g_cost" class="form-control mr-2" placeholder="Extra 10g Cost (RMB/m²)" required
  title="Cost per extra 10g/m² zinc coating. Only charged for zinc above the base level.">
    <button type="submit" name="add_zinc" class="btn btn-success">Add</button>
  </form>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Material</th>
        <th>Base Zinc (g/m²)</th>
        <th>Extra 10g Cost (RMB/m²)</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($zincList as $row): ?>
        <tr>
          <td><?= htmlspecialchars($row['material_name']) ?></td>
          <td><?= $row['base_zinc_g'] ?></td>
          <td><?= $row['extra_10g_cost_per_m2'] ?></td>
          <td>
            <a href="?delete_id=<?= $row['id'] ?>" onclick="return confirm('Delete this zinc setting?')" class="btn btn-danger btn-sm">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
ob_end_flush();
<?php include '../includes/footer.php'; ?>
