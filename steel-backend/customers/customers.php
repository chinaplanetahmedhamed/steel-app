<?php
ob_start();
require_once '../database/db.php';
include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';

function safe($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

// Toggle status
if (isset($_GET['toggle_id'])) {
  $id = (int) $_GET['toggle_id'];
  $stmt = $pdo->prepare("SELECT status FROM users WHERE id = ?");
  $stmt->execute([$id]);
  $current = $stmt->fetchColumn();
  $newStatus = ($current === 'active') ? 'held' : 'active';
  $pdo->prepare("UPDATE users SET status = ? WHERE id = ?")->execute([$newStatus, $id]);
  header("Location: customers.php?success=status-updated");
  exit;
}

// Delete user
if (isset($_GET['delete_id'])) {
  $id = (int) $_GET['delete_id'];
  $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$id]);
  header("Location: customers.php?success=deleted");
  exit;
}

// Save edits
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_id'])) {
  $id = (int) $_POST['save_id'];
  $name = $_POST['name'];
  $email = $_POST['email'];
  $company = $_POST['company'];
  $country = $_POST['country'];
  $phone = $_POST['phone'];
  $notes = $_POST['notes'];
  $pdo->prepare("UPDATE users SET name = ?, email = ?, company = ?, country = ?, phone = ?, notes = ? WHERE id = ?")
      ->execute([$name, $email, $company, $country, $phone, $notes, $id]);
  header("Location: customers.php?success=updated");
  exit;
}

// Load users
$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Identify row being edited
$editId = isset($_GET['edit_id']) ? (int) $_GET['edit_id'] : null;

?>

<div class="content-wrapper p-3">
  <section class="content">
    <div class="container-fluid">
      <h3 class="mb-4">Manage Approved Customers</h3>

      <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">✅ <?= htmlspecialchars($_GET['success']) ?></div>
      <?php endif; ?>

      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Company</th>
            <th>Country</th>
            <th>Phone</th>
            <th>Notes</th>
            <th>Invite Code</th>
            <th>Role</th>
            <th>Status</th>
            <th>Created</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          
        <?php foreach ($users as $row): ?>
          <tr>
            <?php if ($editId === (int)$row['id']): ?>
              <form method="post">
                <td><input type="text" name="name" value="<?= safe($row['name']) ?>" class="form-control"></td>
                <td><input type="email" name="email" value="<?= safe($row['email']) ?>" class="form-control"></td>
                <td><input type="text" name="company" value="<?= safe($row['company']) ?>" class="form-control"></td>
                <td><input type="text" name="country" value="<?= safe($row['country']) ?>" class="form-control"></td>
                <td><input type="text" name="phone" value="<?= safe($row['phone']) ?>" class="form-control"></td>
                <td><input type="text" name="notes" value="<?= safe($row['notes']) ?>" class="form-control"></td>
                <td><?= safe($row['invite_code']) ?></td>
                <td><?= safe($row['role']) ?></td>
                <td><?= safe($row['status']) ?></td>
                <td><?= safe($row['created_at']) ?></td>
                <td>
                  <input type="hidden" name="save_id" value="<?= $row['id'] ?>">
                  <button type="submit" class="btn btn-success btn-sm">Save</button>
                  <a href="customers.php" class="btn btn-secondary btn-sm">Cancel</a>
                </td>
              </form>
            <?php else: ?>
              <td><?= safe($row['name']) ?></td>
              <td><?= safe($row['email']) ?></td>
              <td><?= safe($row['company'] ) ?></td>
              <td><?= safe($row['country'] ) ?></td>
              <td><?= safe($row['phone'] ) ?></td>
              <td><?= safe($row['notes'] ) ?></td>
              <td><?= safe($row['invite_code']) ?></td>
              <td><?= safe($row['role']) ?></td>
              <td><?= $row['status'] === 'active' ? '✅ Active' : '⛔ Held' ?></td>
              <td><?= safe($row['created_at']) ?></td>
              <td>
                <a href="customers.php?edit_id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">✏️</a>
                <a href="customers.php?toggle_id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Toggle</a>
                <a href="customers.php?delete_id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this customer?')">🗑</a>
              </td>
            <?php endif; ?>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </section>
</div>

<?php include '../includes/footer.php'; ?>