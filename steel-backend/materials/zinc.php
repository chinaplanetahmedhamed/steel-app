<?php
ob_start();
require_once '../database/db.php';
include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';

// Fetch materials
$materials = $pdo->query("SELECT id, name FROM materials ORDER BY name ASC")->fetchAll();

// Fetch thicknesses per material
$thicknessMap = [];
$thicknessRows = $pdo->query("SELECT t.id, t.material_id, t.thickness_mm FROM thicknesses t")->fetchAll();
foreach ($thicknessRows as $row) {
    $thicknessMap[$row['material_id']][] = $row;
}

// Handle Add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_zinc'])) {
    $thickness_id = $_POST['thickness_id'];
    $base_zinc = $_POST['base_zinc_g'];
    $base_zinc_cost = $_POST['base_zinc_cost'];
    if (isset($_POST['edit_id'])) {
        // Update
        $id = (int) $_POST['edit_id'];
        $stmt = $pdo->prepare("UPDATE zinc_costs SET thickness_id=?, base_zinc_g=?, base_zinc_cost=? WHERE id=?");
        $stmt->execute([$thickness_id, $base_zinc, $base_zinc_cost, $id]);
    } else {
        // Insert
        $stmt = $pdo->prepare("INSERT INTO zinc_costs (thickness_id, base_zinc_g, base_zinc_cost) VALUES (?, ?, ?)");
        $stmt->execute([$thickness_id, $base_zinc, $base_zinc_cost]);
    }

    header("Location: zinc.php?success=1");
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
    SELECT z.id, z.base_zinc_g, z.base_zinc_cost, m.name AS material_name, t.thickness_mm
    FROM zinc_costs z
    JOIN thicknesses t ON z.thickness_id = t.id
    JOIN materials m ON t.material_id = m.id
    ORDER BY m.name ASC, t.thickness_mm ASC
")->fetchAll();
?>

<div class="container mt-4">
  <h2>Zinc  Settings</h2>

  <form method="POST" class="form-inline mb-4">
          <select name="material_id" id="materialSelect" class="form-control mr-2" required onchange="updateThicknessDropdown()">
        <option value="">Select Material</option>
        <?php foreach ($materials as $mat): ?>
          <option value="<?= $mat['id'] ?>"><?= htmlspecialchars($mat['name']) ?></option>
        <?php endforeach; ?>
      </select>

      <select name="thickness_id" id="thicknessSelect" class="form-control mr-2" required>
        <option value="">Select Thickness</option>
      </select>

        <input type="number" name="base_zinc_g" class="form-control mr-2" placeholder="Base Zinc (g/m²)" required>
      <input type="number" step="0.01" name="base_zinc_cost" class="form-control mr-2" placeholder="Base Zinc cost (RMB/Ton)" required>
      <button type="submit" name="add_zinc" class="btn btn-success">Add</button>
  </form>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Material</th>
        <th>Thickness (mm)</th>
        <th>Base Zinc (g/m²)</th>
        <th>Base zinc cost (RMB/ton)</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($zincList as $row): ?>
        <tr>
          <td><?= htmlspecialchars($row['material_name']) ?></td>
          <td><?= htmlspecialchars($row['thickness_mm']) ?></td>
          <td><?= $row['base_zinc_g'] ?></td>
          <td><?= $row['base_zinc_cost'] ?></td>
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
