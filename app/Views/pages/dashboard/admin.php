<?= $this->extend('layouts/dashboard') ?>


<?= $this->section('styles') ?>
<style>
  .admin .foto {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 50%;
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('partials/greeting') ?>

<?php date_default_timezone_set('Asia/Makassar'); ?>

<div class="admin">
  <!-- Header -->
  <div class="secondary-color-bg text-white p-3 rounded mb-3">
    <div class="row">
      <div class="col-md-6">
        <div class="d-flex align-items-center mb-3 mb-md-0">
          <?php if (session()->get('userFoto')): ?>
            <img class="foto" src="<?= base_url('uploads/foto_user/' . session()->get('userFoto')) ?>" alt="Foto">
          <?php else: ?>
            <img class="foto" src="<?= base_url('assets/images/person-default.png') ?>" alt="Foto">
          <?php endif; ?>
          <div class="ms-3">
            <p class="mb-0"><?= session()->get('nama') ?></p>
            <p class="mb-0 text-secondary"><?= ucfirst(session()->get('userRole')) ?></p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="d-flex justify-content-end opacity-75">
          <i class="bi bi-calendar me-2"></i> <?= formatTanggalIndo(date("d-m-Y")) ?>
          <i class="bi bi-clock me-2 ms-4"></i> <?= date("H:i") ?> WITA
        </div>
      </div>
    </div>
  </div>

  <!-- Contents -->
  <div class="row">
    <div class="col-md-6">
      <div class="card primary-color-bg text-white mb-3">
        <div class="card-body text-center">
          <h1 class="card-title fw-bold">110</h1>
          <p class="card-text">Jumlah Siswa Sudah Terima Makanan Hari Ini</p>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card accent-color-bg text-white mb-3">
        <div class="card-body text-center">
          <h1 class="card-title fw-bold">34</h1>
          <p class="card-text">Jumlah Siswa Belum Terima Makanan Hari Ini</p>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>