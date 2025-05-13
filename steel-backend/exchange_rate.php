<?php
require_once 'database/db.php';
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/sidebar.php';

// 1. Handle update if form submitted
$success = false;
$error = false;
$newRate = null; // Initialize $newRate to avoid undefined variable error

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newRate = isset($_POST['rate']) ? floatval($_POST['rate']) : null;

    if ($newRate !== null && $newRate > 0) {
        $stmt = $pdo->prepare("
            INSERT INTO exchange_rate (currency, rate, updated_at)
            VALUES ('USD', ?, NOW())
            ON DUPLICATE KEY UPDATE rate = VALUES(rate), updated_at = NOW()
        ");
        $stmt->execute([$newRate]);
        $success = true;
    } else {
        $error = true;
    }
}

// 2. Fetch current saved rate
$stmt = $pdo->prepare("
    SELECT rate, updated_at
    FROM exchange_rate
    WHERE currency = 'USD'
");
$stmt->execute();

$currentRate = '';
$lastUpdated = '';
$currentRateRow = $stmt->fetch();
if ($currentRateRow && is_array($currentRateRow)) {
    $currentRate = $currentRateRow['rate'];
    $lastUpdated = $currentRateRow['updated_at'];
}

// 3. Fetch live exchange rate for reference
$liveRate = 'Unavailable';
try {
    $ch = curl_init("https://open.er-api.com/v6/latest/USD");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Set a timeout for the request
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        throw new Exception('cURL Error: ' . curl_error($ch));
    }

    curl_close($ch);
    $live = json_decode($response, true);

    if (isset($live['rates']['CNY'])) {
        $liveRate = $live['rates']['CNY'];
    } else {
        $liveRate = 'Unavailable (API Error)';
    }
} catch (Exception $e) {
    $liveRate = 'Error: Unable to fetch live rate';
}
?>

<div class="content-wrapper">
  <section class="content-header">
    <h1>Exchange Rate Settings</h1>
  </section>

  <section class="content">

    <?php if (!empty($success)): ?>
      <div class="alert alert-success">✅ Exchange rate updated successfully.</div>
    <?php elseif (!empty($error)): ?>
      <div class="alert alert-danger">❌ Please enter a valid rate greater than 0.</div>
    <?php endif; ?>

    <form method="POST">
      <div class="form-group">
        <label>Manual Exchange Rate (USD to RMB)</label>
        <input type="number" step="0.0001" name="rate" class="form-control" value="<?= htmlspecialchars($currentRate) ?>" required>
      </div>

      <button type="submit" class="btn btn-primary">Update Rate</button>
    </form>

    <h4>📊 Current Rate</h4>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Currency</th>
          <th>Manual Rate</th>
          <th>Last Updated</th>
          <th>Live Market Rate</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>USD → RMB</td>
          <td><?= htmlspecialchars($currentRate ?? '') ?></td>
          <td><?= htmlspecialchars($lastUpdated ?? '') ?></td>
          <td><?= htmlspecialchars((string)($liveRate ?? '')) ?></td>
        </tr>
      </tbody>
    </table>
  </section>
</div>  
ob_end_flush();
<?php include '../includes/footer.php'; ?>

