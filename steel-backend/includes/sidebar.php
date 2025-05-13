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
        <li class="nav-item has-treeview <?= in_array($currentPage, ['manage.php', 'thickness.php', 'zinc.php']) ? 'menu-open' : '' ?>">
          <a href="#" class="nav-link <?= in_array($currentPage, ['manage.php', 'thickness.php', 'zinc.php']) ? 'active' : '' ?>">
            <i class="nav-icon fas fa-cubes"></i>
            <p>
              Materials
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview ml-3">
            <li class="nav-item ml-3">
              <a href="<?= BASE_URL ?>materials/base_price.php" class="nav-link <?= $currentPage == 'base_price.php' ? 'active' : '' ?>">
                <i class="fas fa-money-bill-wave nav-icon"></i>
                <p>Base Prices</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= BASE_URL ?>materials/manage.php" class="nav-link <?= $currentPage == 'manage.php' ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Material Types</p>
              </a>
            </li>
            <li class="nav-item">
  <a href="<?= BASE_URL ?>materials/widths.php" class="nav-link <?= $currentPage == 'widths.php' ? 'active' : '' ?>">
    <i class="far fa-circle nav-icon"></i>
    <p>Widths</p>
  </a>
</li>

            <li class="nav-item">
              <a href="<?= BASE_URL ?>materials/thickness.php" class="nav-link <?= $currentPage == 'thickness.php' ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Thickness</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= BASE_URL ?>materials/zinc.php" class="nav-link <?= $currentPage == 'zinc.php' ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Zinc Coating</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= BASE_URL ?>materials/coatings.php" class="nav-link <?= $currentPage == 'coatings.php' ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Coatings</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= BASE_URL ?>materials/processing.php" class="nav-link <?= $currentPage == 'processing.php' ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Processing</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= BASE_URL ?>materials/packing.php" class="nav-link <?= $currentPage == 'packing.php' ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Packing</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= BASE_URL ?>materials/shipping.php" class="nav-link <?= $currentPage == 'ports.php' ? 'active' : '' ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Shipping / Ports</p>
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