<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/components/sidebar_menu.css') ?>">
<?= $this->endSection() ?>

<?php
if (!function_exists('isDropdownActive')) {
  function isDropdownActive(array $pages, string $currentPage): string
  {
    return in_array($currentPage, $pages) ? 'active' : '';
  }
}

$currentPage = service('uri')->getSegment(1);
?>

<div class="sidebar-menu">
  <div class="header-wrapper">
    <div class="header d-flex justify-content-between align-items-center">
      <img class="logo" src="<?= base_url('assets/images/logo-horizontal.png') ?>" alt="Logo" />
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
  </div>
  <ul class="nav flex-column mt-3">
    <!-- Dashboard -->
    <li class="nav-item mb-2">
      <a href="/dashboard" class="nav-link rounded d-flex <?= ($currentPage == 'dashboard') ? 'active' : '' ?>">
        <i class="bi bi-speedometer me-2"></i>
        <span>Dashboard</span>
      </a>
    </li>

    <!-- Aktivitas Makan | Pengambilan Makanan -->
    <?php if (session()->get('userRole') === 'ortu'): ?>
      <li class="nav-item mb-2">
        <a href="/food-activity" class="nav-link rounded d-flex <?= ($currentPage == 'food-activity') ? 'active' : '' ?>">
          <i class="bi bi-file-text-fill me-2"></i>
          <span>Aktivitas Makan</span>
        </a>
      </li>
    <?php else: ?>
      <li class="nav-item mb-2">
        <a href="/food-pickup" class="nav-link rounded d-flex <?= ($currentPage == 'food-pickup') ? 'active' : '' ?>">
          <i class="bi bi-file-text-fill me-2"></i>
          <span>Pengambilan Makanan</span>
        </a>
      </li>
    <?php endif; ?>

    <!-- Profil -->
    <?php if (session()->get('userRole') == 'ortu'): ?>
      <li class="nav-item mb-2">
        <a href="/profil" class="nav-link rounded d-flex <?= ($currentPage == 'profil') ? 'active' : '' ?>">
          <i class="bi bi-person-fill me-2"></i>
          <span>Profil</span>
        </a>
      </li>
    <?php endif; ?>

    <div id="sidebarAccordion">
      <!-- Daftar Makanan | Tambah Makanan -->
      <?php if (session()->get('userRole') == 'admin' || session()->get('userRole') == 'guru'): ?>
        <li class="nav-item mb-2">
          <a class="nav-link rounded d-flex dropdown-toggle mb-2 <?= isDropdownActive(['daftar-makanan', 'tambah-makanan'], $currentPage) ? 'outline-active' : '' ?>"
            href="#" id="foodManagementDropdown" role="button" data-bs-toggle="collapse" data-bs-target="#foodManagementCollapse"
            aria-expanded="<?= isDropdownActive(['daftar-makanan', 'tambah-makanan'], $currentPage) ? 'true' : 'false' ?>" aria-controls="foodManagementCollapse">
            <i class="bi bi-fork-knife me-2"></i>
            <span>Daftar Makanan</span>
          </a>
          <div class="collapse <?= isDropdownActive(['daftar-makanan', 'tambah-makanan'], $currentPage) ? 'show' : '' ?>" id="foodManagementCollapse" data-bs-parent="#sidebarAccordion">
            <ul class="nav flex-column ms-3">
              <li class="nav-item mb-2">
                <a href="/daftar-makanan" class="nav-link rounded d-flex <?= ($currentPage == 'daftar-makanan') ? 'active' : '' ?>">
                  Daftar Makanan
                </a>
              </li>
              <li class="nav-item">
                <a href="/tambah-makanan" class="nav-link rounded d-flex <?= ($currentPage == 'tambah-makanan') ? 'active' : '' ?>">
                  Tambah Makanan
                </a>
              </li>
            </ul>
          </div>
        </li>
      <?php endif; ?>

      <!-- Daftar Alergen | Tambah Alergen -->
      <?php if (session()->get('userRole') == 'admin' || session()->get('userRole') == 'guru'): ?>
        <li class="nav-item mb-2">
          <a class="nav-link rounded d-flex dropdown-toggle mb-2 <?= isDropdownActive(['daftar-alergen', 'tambah-alergen'], $currentPage) ? 'outline-active' : '' ?>"
            href="#" id="allergenManagementDropdown" role="button" data-bs-toggle="collapse" data-bs-target="#allergenManagementCollapse"
            aria-expanded="<?= isDropdownActive(['daftar-alergen', 'tambah-alergen'], $currentPage) ? 'true' : 'false' ?>" aria-controls="allergenManagementCollapse">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <span>Daftar Alergen</span>
          </a>
          <div class="collapse <?= isDropdownActive(['daftar-alergen', 'tambah-alergen'], $currentPage) ? 'show' : '' ?>" id="allergenManagementCollapse" data-bs-parent="#sidebarAccordion">
            <ul class="nav flex-column ms-3">
              <li class="nav-item mb-2">
                <a href="/daftar-alergen" class="nav-link rounded d-flex <?= ($currentPage == 'daftar-alergen') ? 'active' : '' ?>">
                  Daftar Alergen
                </a>
              </li>
              <li class="nav-item">
                <a href="/tambah-alergen" class="nav-link rounded d-flex <?= ($currentPage == 'tambah-alergen') ? 'active' : '' ?>">
                  Tambah Alergen
                </a>
              </li>
            </ul>
          </div>
        </li>
      <?php endif; ?>

      <!-- Daftar User | Tambah User -->
      <?php if (session()->get('userRole') == 'admin' || session()->get('userRole') == 'guru'): ?>
        <li class="nav-item mb-2">
          <a class="nav-link rounded d-flex dropdown-toggle mb-2 <?= isDropdownActive(['daftar-user', 'tambah-user'], $currentPage) ? 'outline-active' : '' ?>"
            href="#" id="userManagementDropdown" role="button" data-bs-toggle="collapse" data-bs-target="#userManagementCollapse"
            aria-expanded="<?= isDropdownActive(['daftar-user', 'tambah-user'], $currentPage) ? 'true' : 'false' ?>" aria-controls="userManagementCollapse">
            <i class="bi bi-person-lines-fill me-2"></i>
            <span>Daftar User</span>
          </a>
          <div class="collapse <?= isDropdownActive(['daftar-user', 'tambah-user'], $currentPage) ? 'show' : '' ?>" id="userManagementCollapse" data-bs-parent="#sidebarAccordion">
            <ul class="nav flex-column ms-3">
              <li class="nav-item mb-2">
                <a href="/daftar-user" class="nav-link rounded d-flex <?= ($currentPage == 'daftar-user') ? 'active' : '' ?>">
                  Daftar User
                </a>
              </li>
              <li class="nav-item">
                <a href="/tambah-user" class="nav-link rounded d-flex <?= ($currentPage == 'tambah-user') ? 'active' : '' ?>">
                  Tambah User
                </a>
              </li>
            </ul>
          </div>
        </li>
      <?php endif; ?>

      <!-- Daftar Siswa | Tambah Siswa -->
      <?php if (session()->get('userRole') == 'admin' || session()->get('userRole') == 'guru'): ?>
        <li class="nav-item mb-2">
          <a class="nav-link rounded d-flex dropdown-toggle mb-2 <?= isDropdownActive(['daftar-siswa', 'tambah-siswa'], $currentPage) ? 'outline-active' : '' ?>"
            href="#" id="studentManagementDropdown" role="button" data-bs-toggle="collapse" data-bs-target="#studentManagementCollapse"
            aria-expanded="<?= isDropdownActive(['daftar-siswa', 'tambah-siswa'], $currentPage) ? 'true' : 'false' ?>" aria-controls="studentManagementCollapse">
            <i class="bi bi-people-fill me-2"></i>
            <span>Daftar Siswa</span>
          </a>
          <div class="collapse <?= isDropdownActive(['daftar-siswa', 'tambah-siswa'], $currentPage) ? 'show' : '' ?>" id="studentManagementCollapse" data-bs-parent="#sidebarAccordion">
            <ul class="nav flex-column ms-3">
              <li class="nav-item mb-2">
                <a href="/daftar-siswa" class="nav-link rounded d-flex <?= ($currentPage == 'daftar-siswa') ? 'active' : '' ?>">
                  Daftar Siswa
                </a>
              </li>
              <li class="nav-item">
                <a href="/tambah-siswa" class="nav-link rounded d-flex <?= ($currentPage == 'tambah-siswa') ? 'active' : '' ?>">
                  Tambah Siswa
                </a>
              </li>
            </ul>
          </div>
        </li>
      <?php endif; ?>
    </div>
  </ul>
</div>