<?php
ob_start();
require_once '../database/db.php';
include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';

$editItem = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM extra_zinc_costs WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editItem = $stmt->fetch();
}

// Handle deletion
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM extra_zinc_costs WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header('Location: extra_zinc_costs.php');
    exit;
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $material_id = $_POST['material_id'];
    $cost = $_POST['extra_cost'];
    $id = $_POST['id'] ?? null;
        if ($id) {
        $stmt = $pdo->prepare("UPDATE extra_zinc_costs SET extra_10g_cost_per_sqm = ?, material_id = ? WHERE id = ?");
        $stmt->execute([$cost, $material_id, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO extra_zinc_costs (material_id, extra_10g_cost_per_sqm) VALUES (?, ?)");
        $stmt->execute([$material_id, $cost]);
    }

    header('Location: extra_zinc_costs.php');
    exit;
}
// Fetch data
$extraZinc = $pdo->query("
    SELECT ez.id, ez.extra_10g_cost_per_sqm, m.name AS material_name
    FROM extra_zinc_costs ez
    JOIN materials m ON ez.material_id = m.id
")->fetchAll();
$materials = $pdo->query("SELECT id, name FROM materials")->fetchAll();
?>
<div class="content-wrapper">
  <section class="content-header">
    <h1>Extra Zinc Costs</h1>
  </section>
  <section class="content">
    <form method="POST">
      <?php if ($editItem): ?>
        <input type="hidden" name="id" value="<?= $editItem['id'] ?>">
      <?php endif; ?>
      <div class="form-group">
        <label>Material</label>
        <select name="material_id" class="form-control" required>
          <?php foreach ($materials as $mat): ?>
            <option value="<?= $mat['id'] ?>" <?= $editItem && $editItem['material_id'] == $mat['id'] ? 'selected' : '' ?>>
                <?= $mat['name'] ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label>Extra Cost per 10g/m² (RMB)</label>
        <input type="number" step="0.01" name="extra_cost" class="form-control" required
               value="<?= $editItem ? $editItem['extra_10g_cost_per_sqm'] : '' ?>">
      </div>
      <button type="submit" class="btn btn-primary">Save</button>
    </form>
        <hr>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>Material</th>
          <th>Extra Cost (10g/m²)</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($extraZinc as $item): ?>
          <tr>
            <td><?= $item['id'] ?></td>
            <td><?= $item['material_name'] ?></td>
            <td><?= $item['extra_10g_cost_per_sqm'] ?></td>
            <td>
              <a href="?edit=<?= $item['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
              <a href="?delete=<?= $item['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </section>
</div>
ob_end_flush();
<?php include '../includes/footer.php'; ?>