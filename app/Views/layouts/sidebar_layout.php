<?= $this->section('styles') ?>
<!-- (Opsional) gaya kecil supaya offcanvas full tinggi -->
<style>
  .offcanvas-start {
    width: 200px !important;
  }

  @media (min-width: 576px) {
    .offcanvas-start {
      width: 280px !important;
    }
  }
</style>
<?= $this->endSection() ?>

<!-- Sidebar: tampil permanen di md ke atas -->
<aside class="col-md-3 col-lg-2 bg-white shadow min-vh-100 d-none d-md-block bg-primary">
  <?= $this->include('partials/sidebar_menu') ?>
</aside>

<!-- Offcanvas Sidebar: tampil hanya di mobile (d-md-none), slide dari kiri -->
<div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel">
  <?= $this->include('partials/sidebar_menu') ?>
</div>