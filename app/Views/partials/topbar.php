<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/components/topbar.css') ?>">
<?= $this->endSection() ?>

<div class="topbar bg-white fixed-top">
  <!-- Navbar untuk layar kecil: tombol toggle untuk offcanvas -->
  <nav class="navbar">
    <div class="page-title d-md-block d-none">
      <p class="mb-0 fw-bold"><?= $pageTitle ?></p>
    </div>
    <div class="d-md-none">
      <button class="btn btn-outline-secondary me-2" type="button"
        data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas"
        aria-label="Buka menu">
        <i class="bi bi-list"></i>
      </button>
      <img class="logo" src="<?= base_url('assets/images/logo-horizontal.png') ?>" alt="Logo" />
    </div>
    <div class="d-flex align-items-center ms-auto">
      <span class="me-2 fw-bold name"><?= session()->get('username') ?></span>
      <div class="dropdown">
        <div class="button dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
          <?php if (session()->get('userFoto')): ?>
            <img class="foto" src="<?= base_url('uploads/foto_user/' . session()->get('userFoto')) ?>" alt="Foto">
          <?php else: ?>
            <img class="foto" src="<?= base_url('assets/images/person-default.png') ?>" alt="Foto">
          <?php endif; ?>
        </div>
        <ul class="dropdown-menu shadow dropdown-menu-end">
          <li><a class="dropdown-item" href="/profile">Lihat Profil</a></li>
          <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">Keluar</a></li>
        </ul>
      </div>
    </div>
  </nav>
</div>

<!-- Modal Konfirmasi Logout -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <h4>Apakah Anda ingin keluar?</h4>
      </div>
      <div class="modal-footer w-100">
        <div class="d-flex justify-content-center w-100">
          <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Batal</button>
          <a href="/logout" class="btn btn-danger">Ya</a>
        </div>
      </div>
    </div>
  </div>
</div>