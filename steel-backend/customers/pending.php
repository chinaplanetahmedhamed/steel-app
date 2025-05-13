<?php
require_once '../database/db.php';
include '../includes/header.php';
include '../includes/navbar.php';
include '../includes/sidebar.php';
function safe($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

// ✅ Handle Approve
if (isset($_GET['approve_id'])) {
  $id = (int) $_GET['approve_id'];
  $stmt = $pdo->prepare("SELECT * FROM pending_customers WHERE id = ?");
  $stmt->execute([$id]);
  $customer = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($customer) {
    $inviteCode = bin2hex(random_bytes(4)); // 8-character code
    $stmt = $pdo->prepare("INSERT INTO users (name, email, company, invite_code) VALUES (?, ?, ?, ?)");
    $stmt->execute([
      $customer['name'],
      $customer['email'],
      $customer['company'],
      $inviteCode
    ]);
    $pdo->prepare("DELETE FROM pending_customers WHERE id = ?")->execute([$id]);
    header("Location: pending.php?success=approved&code=$inviteCode");    exit;
  }
}

// ❌ Handle Delete
if (isset($_GET['delete_id'])) {
  $id = (int) $_GET['delete_id'];
  $pdo->prepare("DELETE FROM pending_customers WHERE id = ?")->execute([$id]);
  header("Location: pending.php?success=deleted");
  exit;
}

// 📋 Fetch all pending customers
$stmt = $pdo->query("SELECT * FROM pending_customers ORDER BY created_at DESC");
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="content-wrapper p-3">
  <section class="content">
    <div class="container-fluid">
      <h3 class="mb-4">Pending Customer Approvals</h3>

      <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
          ✅ <?= safe($_GET['success']) ?>
          <?php if (isset($_GET['code'])): ?>
            <br>
            🧾 Invite Code: <strong><?= safe($_GET['code']) ?></strong>
            <button onclick="navigator.clipboard.writeText('<?= safe($_GET['code']) ?>')" class="btn btn-sm btn-outline-secondary ml-2">Copy</button>
          <?php endif; ?>
        </div>
      <?php endif; ?>


      <table class="table table-bordered table-striped">
        <thead>
          <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Company</th>
          <th>Phone</th>
          <th>Notes</th>
          <th>Registered</th>
          <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($customers as $row): ?>
            <?php /** @var array $row */ ?>
            <tr>
              <td><?= safe($row['name']) ?></td>
              <td><?= safe($row['email']) ?></td>
              <td><?= safe($row['company']) ?></td>
              <td><?= safe($row['phone']) ?></td>
              <td><?= !empty($row['notes']) ? nl2br(safe($row['notes'])) : 'No Notes' ?></td>
              <td><?= safe($row['created_at']) ?></td>
              <td>
                <a href="pending.php?approve_id=<?= $row['id'] ?>" class="btn btn-success btn-sm">Approve</a>
                <a href="pending.php?delete_id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this request?')">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </section>
</div>

<?php include '../includes/footer.php'; ?>
