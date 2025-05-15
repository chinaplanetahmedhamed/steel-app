<?php $currentPage = basename($_SERVER['PHP_SELF']); ?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="<?= BASE_URL ?>index.php" class="brand-link">
    <span class="brand-text font-weight-light">Steel Dashboard</span>
  </a>
  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview">

        <li class="nav-item">
          <a href="<?= BASE_URL ?>index.php" class="nav-link <?= $currentPage == 'index.php' ? 'active' : '' ?>">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= BASE_URL ?>customers/pending.php" class="nav-link <?= $currentPage == 'pending.php' ? 'active' : '' ?>">
            <i class="nav-icon fas fa-user-clock"></i>
            <p>Pending Approvals</p>
          </a>
        </li>
<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$materialsPages = [
  'base_price.php', 'manage.php', 'widths.php', 'rolling_costs.php',
  'thicknesses.php', 'zinc.php', 'extra_zinc_costs.php',
  'coatings.php', 'processing.php', 'packing.php', 'shipping.php', 'ports.php'
];
$isMaterialsOpen = in_array($currentPage, $materialsPages);
?>

<li class="nav-item has-treeview <?= $isMaterialsOpen ? 'menu-open' : '' ?>">
  <a href="#" class="nav-link <?= $isMaterialsOpen ? 'active' : '' ?>">
    <i class="nav-icon fas fa-cubes"></i>
    <p>
      Materials
      <i class="right fas fa-angle-left"></i>
    </p>
  </a>
  <ul class="nav nav-treeview ml-2">
    <li class="nav-item">
      <a href="<?= BASE_URL ?>materials/base_price.php" class="nav-link <?= $currentPage == 'base_price.php' ? 'active' : '' ?>">
        <i class="fas fa-dollar-sign nav-icon text-success"></i>
        <p>Base Prices</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?= BASE_URL ?>materials/manage.php" class="nav-link <?= $currentPage == 'manage.php' ? 'active' : '' ?>">
        <i class="fas fa-cube nav-icon text-primary"></i>
        <p>Material Types</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?= BASE_URL ?>materials/widths.php" class="nav-link <?= $currentPage == 'widths.php' ? 'active' : '' ?>">
        <i class="fas fa-arrows-alt-h nav-icon text-info"></i>
        <p>Widths</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?= BASE_URL ?>materials/rolling_costs.php" class="nav-link <?= $currentPage == 'rolling_costs.php' ? 'active' : '' ?>">
        <i class="fas fa-industry nav-icon text-warning"></i>
        <p>Rolling Costs</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?= BASE_URL ?>materials/thicknesses.php" class="nav-link <?= $currentPage == 'thicknesses.php' ? 'active' : '' ?>">
        <i class="fas fa-ruler-combined nav-icon text-secondary"></i>
        <p>Thicknesses</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?= BASE_URL ?>materials/zinc.php" class="nav-link <?= $currentPage == 'zinc.php' ? 'active' : '' ?>">
        <i class="fas fa-tint nav-icon text-primary"></i>
        <p>Zinc Coating</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?= BASE_URL ?>materials/extra_zinc_costs.php" class="nav-link <?= $currentPage == 'extra_zinc_costs.php' ? 'active' : '' ?>">
        <i class="fas fa-percentage nav-icon text-danger"></i>
        <p>Extra Zinc Costs</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?= BASE_URL ?>materials/coatings.php" class="nav-link <?= $currentPage == 'coatings.php' ? 'active' : '' ?>">
        <i class="fas fa-brush nav-icon text-purple"></i>
        <p>Coatings</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?= BASE_URL ?>materials/processing.php" class="nav-link <?= $currentPage == 'processing.php' ? 'active' : '' ?>">
        <i class="fas fa-tools nav-icon text-teal"></i>
        <p>Processing</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?= BASE_URL ?>materials/packing.php" class="nav-link <?= $currentPage == 'packing.php' ? 'active' : '' ?>">
        <i class="fas fa-box-open nav-icon text-orange"></i>
        <p>Packing</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="<?= BASE_URL ?>materials/shipping.php" class="nav-link <?= $currentPage == 'ports.php' ? 'active' : '' ?>">
        <i class="fas fa-ship nav-icon text-navy"></i>
        <p>Shipping / Ports</p>
      </a>
    </li>
      <li class="nav-item">
      <a href="<?= BASE_URL ?>exchange_rate.php" class="nav-link <?= $currentPage == 'exchange_rate.php' ? 'active' : '' ?>">
        <i class="fas fa-USD nav-icon text-navy"></i>
        <p>exchange rate</p>
      </a>
    </li>
  </ul>
</li>
        <li class="nav-item">
          <a href="<?= BASE_URL ?>quotes/quotes.php" class="nav-link <?= $currentPage == 'quotes.php' ? 'active' : '' ?>">
            <i class="fas fa-file-invoice nav-icon"></i>
            <p>Quotes</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= BASE_URL ?>customers/customers.php" class="nav-link <?= $currentPage == 'customers.php' ? 'active' : '' ?>">
            <i class="nav-icon fas fa-users"></i>
            <p>Customers</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= BASE_URL ?>sale_price.php" class="nav-link <?= $currentPage == 'sale_price.php' ? 'active' : '' ?>">
            <i class="fas fa-calculator nav-icon"></i>
            <p>Sale Price</p>
          </a>
        </li>
    </nav>
  </div>
</aside>