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
      <a href="/dashboard" class="nav-link rounded <?= ($currentPage == 'dashboard') ? 'active' : '' ?>">
        <i class="bi bi-house-door-fill me-2"></i> Dashboard
      </a>
    </li>

    <!-- Aktivitas Terkini -->
    <li class="nav-item mb-2">
      <a href="/aktivitas-terkini" class="nav-link rounded <?= ($currentPage == 'aktivitas-terkini') ? 'active' : '' ?>">
        <i class="bi bi-file-text-fill me-2"></i> Aktivitas Terkini
      </a>
    </li>

    <!-- Biodata -->
    <?php if (session()->get('userRole') == 'ortu'): ?>
      <li class="nav-item mb-2">
        <a href="/biodata" class="nav-link rounded <?= ($currentPage == 'biodata') ? 'active' : '' ?>">
          <i class="bi bi-person me-2"></i> Biodata
        </a>
      </li>
    <?php endif; ?>

    <!-- Daftar Makanan | Tambah Makanan -->
    <div id="sidebarAccordion">
      <?php if (session()->get('userRole') == 'admin' || session()->get('userRole') == 'guru'): ?>
        <li class="nav-item mb-2">
          <a class="nav-link rounded dropdown-toggle mb-2 <?= isDropdownActive(['daftar-makanan', 'tambah-makanan'], $currentPage) ? 'outline-active' : '' ?>"
            href="#" id="foodManagementDropdown" role="button" data-bs-toggle="collapse" data-bs-target="#foodManagementCollapse"
            aria-expanded="<?= isDropdownActive(['daftar-makanan', 'tambah-makanan'], $currentPage) ? 'true' : 'false' ?>" aria-controls="foodManagementCollapse">
            <i class="bi bi-fork-knife me-2"></i> Daftar Makanan
          </a>
          <div class="collapse <?= isDropdownActive(['daftar-makanan', 'tambah-makanan'], $currentPage) ? 'show' : '' ?>" id="foodManagementCollapse" data-bs-parent="#sidebarAccordion">
            <ul class="nav flex-column ms-3">
              <li class="nav-item mb-2">
                <a href="/daftar-makanan" class="nav-link rounded <?= ($currentPage == 'daftar-makanan') ? 'active' : '' ?>">
                  Daftar Makanan
                </a>
              </li>
              <li class="nav-item">
                <a href="/tambah-makanan" class="nav-link rounded <?= ($currentPage == 'tambah-makanan') ? 'active' : '' ?>">
                  Tambah Makanan
                </a>
              </li>
            </ul>
          </div>
        </li>
      <?php endif; ?>

      <!-- Daftar User | Tambah User -->
      <div id="sidebarAccordion">
        <?php if (session()->get('userRole') == 'admin' || session()->get('userRole') == 'guru'): ?>
          <li class="nav-item mb-2">
            <a class="nav-link rounded dropdown-toggle mb-2 <?= isDropdownActive(['daftar-user', 'tambah-user'], $currentPage) ? 'outline-active' : '' ?>"
              href="#" id="userManagementDropdown" role="button" data-bs-toggle="collapse" data-bs-target="#userManagementCollapse"
              aria-expanded="<?= isDropdownActive(['daftar-user', 'tambah-user'], $currentPage) ? 'true' : 'false' ?>" aria-controls="userManagementCollapse">
              <i class="bi bi-person-lines-fill me-2"></i> Daftar User
            </a>
            <div class="collapse <?= isDropdownActive(['daftar-user', 'tambah-user'], $currentPage) ? 'show' : '' ?>" id="userManagementCollapse" data-bs-parent="#sidebarAccordion">
              <ul class="nav flex-column ms-3">
                <li class="nav-item mb-2">
                  <a href="/daftar-user" class="nav-link rounded <?= ($currentPage == 'daftar-user') ? 'active' : '' ?>">
                    Daftar User
                  </a>
                </li>
                <li class="nav-item">
                  <a href="/tambah-user" class="nav-link rounded <?= ($currentPage == 'tambah-user') ? 'active' : '' ?>">
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
            <a class="nav-link rounded dropdown-toggle mb-2 <?= isDropdownActive(['daftar-siswa', 'tambah-siswa'], $currentPage) ? 'outline-active' : '' ?>"
              href="#" id="studentManagementDropdown" role="button" data-bs-toggle="collapse" data-bs-target="#studentManagementCollapse"
              aria-expanded="<?= isDropdownActive(['daftar-siswa', 'tambah-siswa'], $currentPage) ? 'true' : 'false' ?>" aria-controls="studentManagementCollapse">
              <i class="bi bi-people-fill me-2"></i> Daftar Siswa
            </a>
            <div class="collapse <?= isDropdownActive(['daftar-siswa', 'tambah-siswa'], $currentPage) ? 'show' : '' ?>" id="studentManagementCollapse" data-bs-parent="#sidebarAccordion">
              <ul class="nav flex-column ms-3">
                <li class="nav-item mb-2">
                  <a href="/daftar-siswa" class="nav-link rounded <?= ($currentPage == 'daftar-siswa') ? 'active' : '' ?>">
                    Daftar Siswa
                  </a>
                </li>
                <li class="nav-item">
                  <a href="/tambah-siswa" class="nav-link rounded <?= ($currentPage == 'tambah-siswa') ? 'active' : '' ?>">
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