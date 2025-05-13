<?php
require_once '../database/db.php';
include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';

// Fetch materials for the dropdown
$materials = $pdo->query("SELECT id, name FROM materials ORDER BY name ASC")->fetchAll();

// Add new thickness entry
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_thickness'])) {
    $material_id = $_POST['material_id'];
    $thickness = $_POST['thickness_mm'];
    $cold_cost = $_POST['cold_rolling_cost'];

    $stmt = $pdo->prepare("INSERT INTO thickness_costs (material_id, thickness_mm, cold_rolling_cost) VALUES (?, ?, ?)");
    $stmt->execute([$material_id, $thickness, $cold_cost]);
    header("Location: thickness.php?success=added");
    exit;
}
            // Handle Edit Submit
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_thickness'])) {
                $id = $_POST['id'];
                $material_id = $_POST['material_id'];
                $thickness = $_POST['thickness_mm'];
                $cold_cost = $_POST['cold_rolling_cost'];

                $stmt = $pdo->prepare("UPDATE thickness_costs SET material_id = ?, thickness_mm = ?, cold_rolling_cost = ? WHERE id = ?");
                $stmt->execute([$material_id, $thickness, $cold_cost, $id]);
                header("Location: thickness.php?success=updated");
                exit;
            }

            // Fetch single record for editing
            $editData = null;
            if (isset($_GET['edit_id'])) {
                $edit_id = (int)$_GET['edit_id'];
                $stmt = $pdo->prepare("SELECT * FROM thickness_costs WHERE id = ?");
                $stmt->execute([$edit_id]);
                $editData = $stmt->fetch();
            }


// Delete entry
if (isset($_GET['delete_id'])) {
    $id = (int) $_GET['delete_id'];
    $pdo->prepare("DELETE FROM thickness_costs WHERE id = ?")->execute([$id]);
    header("Location: thickness.php?success=deleted");
    exit;
}

// Fetch all thickness records with material name
$thicknessList = $pdo->query("
    SELECT t.id, t.thickness_mm, t.cold_rolling_cost, m.name AS material_name
    FROM thickness_costs t
    JOIN materials m ON t.material_id = m.id
    ORDER BY m.name ASC, t.thickness_mm ASC
")->fetchAll();
?>

<div class="content-wrapper p-3">
  <section class="content">
    <div class="container-fluid">
      <h3 class="mb-4">Manage Thickness & Cold Rolling Cost</h3>

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
    <input type="number" step="0.01" name="thickness_mm" class="form-control mr-2" value="<?= $editData['thickness_mm'] ?>" required>
    <input type="number" step="0.01" name="cold_rolling_cost" class="form-control mr-2" value="<?= $editData['cold_rolling_cost'] ?>" required>
    <button type="submit" name="update_thickness" class="btn btn-primary">Update</button>
    <a href="thickness.php" class="btn btn-secondary ml-2">Cancel</a>
  </form>
<?php else: ?>
  <form method="POST" class="form-inline mb-4">
    <select name="material_id" class="form-control mr-2" required>
      <option value="">Select Material</option>
      <?php foreach ($materials as $mat): ?>
        <option value="<?= $mat['id'] ?>"><?= htmlspecialchars($mat['name']) ?></option>
      <?php endforeach; ?>
    </select>
    <input type="number" step="0.01" name="thickness_mm" placeholder="Thickness (mm)" class="form-control mr-2" required>
    <input type="number" step="0.01" name="cold_rolling_cost" placeholder="Rolling Cost (RMB)" class="form-control mr-2" required>
    <button type="submit" name="add_thickness" class="btn btn-success">Add</button>
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
              <td><?= $row['cold_rolling_cost'] ?></td>
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

<?php include '../includes/footer.php'; ?>
