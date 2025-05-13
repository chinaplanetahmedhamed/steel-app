<?php
require_once 'database/db.php'; // database connection

$stmt = $pdo->query("SELECT COUNT(*) FROM pending_customers");
$pendingCount = $stmt->fetchColumn();
$stmt = $pdo->query("SELECT name, email, company FROM pending_customers ORDER BY created_at DESC LIMIT 2");
$latestPending = $stmt->fetchAll(PDO::FETCH_ASSOC);
include 'includes/header.php';
include 'includes/navbar.php';
include 'includes/sidebar.php';
?>
<div class="content-wrapper p-3">
  <section class="content">
    <div class="container-fluid">
      <div class="row" id="dashboard-widgets">
        <div class="col-md-6 col-lg-4 mb-4" id="widget-users">
          <a href="customers.php">
          <div class="small-box bg-info">
            <div class="inner">
              <h3>120</h3>
              <p>Total Users</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
            <a href="customers.html" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4" id="widget-pending">
          <a href="pending.php"> 
          <div class="small-box bg-warning">
          <div class="inner">
            <h3><?= $pendingCount ?></h3>
             <p>Pending Approvals</p>
            </div>
            <div class="icon"><i class="fas fa-user-clock"></i></div>
            <a href="pending.html" class="small-box-footer">Review now <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4" id="widget-quotes">
          <div class="small-box bg-success">
            <a href="quotes.php">
            <div class="inner">
              <h3>58</h3>
              <p>Quotes Today</p>
            </div>
            <div class="icon"><i class="fas fa-file-invoice"></i></div>
            <a href="quotes.html" class="small-box-footer">View all <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4" id="widget-revenue">
          <div class="small-box bg-danger">
            <div class="inner">
              <h3>$8.6k</h3>
              <p>Revenue</p>
            </div>
            <div class="icon"><i class="fas fa-chart-line"></i></div>
            <a href="quotes.html" class="small-box-footer">Details <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-md-6 col-lg-6 mb-4" id="widget-activity">
          <div class="card">
            <div class="card-header"><h3 class="card-title">Recent Activity</h3></div>
            <div class="card-body">
              <ul>
                <li>Ahmed submitted a quote request</li>
                <li>Mohamed approved by Admin</li>
                <li>Steel pricing updated by Admin</li>
              </ul>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-6 mb-4" id="widget-approvals">
          <div class="card">
            <div class="card-header"><h3 class="card-title">Latest Pending Approvals</h3></div>
            <div class="card-body">

              <table class="table table-bordered">
                <thead><tr><th>Name</th><th>Email</th><th>Company</th></tr></thead>
                <tbody>
                <?php foreach ($latestPending as $row): ?>
                  <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['company']) ?></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-6 mb-4" id="widget-chart-pie">
          <div class="card">
            <div class="card-header"><h3 class="card-title">Customer Countries (Pie Chart)</h3></div>
            <div class="card-body" style="height: 300px;">
              <canvas id="customerPie"></canvas>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-6 mb-4" id="widget-chart-bar">
          <div class="card">
            <div class="card-header"><h3 class="card-title">Top 10 Customer Orders</h3></div>
            <div class="card-body" style="height: 250px;">
              <canvas id="customerOrders"></canvas>
            </div>
          </div>
        </div>

        <div class="col-md-12 mb-4" id="widget-map">
          <div class="card">
            <div class="card-header"><h3 class="card-title">Global Customer Distribution</h3></div>
            <div class="card-body">
              <div id="world-map" style="width: 100%; height: 300px;"></div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>
</div>
<?php include 'includes/footer.php'; ?>