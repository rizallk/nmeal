<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
  .dashboard-separator {
    height: 75px;
  }

  @media (min-width: 768px) {
    .dashboard-separator {
      display: none;
    }
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content-wrapper') ?>
<div class="dashboard-layout container-fluid">
  <div class="row">
    <?= $this->include('layouts/sidebar_layout') ?>

    <!-- Main Content -->
    <div class="col-md-9 col-lg-10 mb-3">
      <?= $this->include('partials/topbar') ?>

      <div class="dashboard-separator"></div>

      <div class="page-title d-md-none d-block">
        <p class="mb-0 fw-bold"><?= $pageTitle ?></p>
        <hr>
      </div>

      <?= $this->renderSection('content') ?>
    </div>
  </div>
</div>
<?= $this->endSection() ?>