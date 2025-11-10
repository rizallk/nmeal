<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pages/landing.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content-wrapper') ?>
<div class="landing">
  <div class="container">
    <div
      class="content-wrapper d-flex justify-content-center align-items-center">
      <div
        class="content d-flex align-items-center flex-column">
        <img class="mb-5" src="<?= base_url('assets/images/logo.jpeg') ?>" alt="Logo" />
        <button class="btn btn-primary-custom rounded-pill mb-3">Masuk</button>
        <button class="btn btn-primary-custom rounded-pill">Daftar</button>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>