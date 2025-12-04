<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('styles') ?>
<style>
  .home-ortu .bg-color-1 {
    background-color: #7ed957;
  }

  .home-ortu .bg-color-2 {
    background-color: #93ee6cff;
  }

  .home-ortu .color {
    color: #65c43cff;
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-md-8">
    <?= $this->include('partials/greeting') ?>
  </div>
  <div class="col-md-4">
    <div class="mb-4">
      <button id="btnNotif" class="btn btn-outline-primary w-100 rounded d-flex align-items-center justify-content-center gap-2" onclick="handleNotificationClick()">
        <div id="spinnerNotif" class="spinner-border spinner-border-sm d-none" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
        <i class="bi bi-bell-slash" id="iconNotif"></i>
        <span id="textNotif">Aktifkan Notifikasi</span>
      </button>
    </div>
  </div>
</div>

<!-- Header -->
<a href="/food-activity" class="btn btn-primary-custom text-center rounded mb-4 w-100">
  <h5 class="fw-bold">Lihat Aktivitas Makan si Kecil</h5>
  Tekan disini untuk melihatnya
</a>

<!-- Contents -->
<div class="home-ortu">
  <h5 class="fw-bold mb-3">Rekomendasi untuk Anak</h5>
  <input type="hidden" value="<?= $status ?>" id="status">
  <input type="hidden" value="<?= $catatan ?>" id="catatan">
  <div class="row">
    <div class="col-md-8">
      <div class="card bg-color-1 p-3 border-0 mb-3">
        <div class="card px-4 py-3 border-0">
          <div id="tipsHeader"></div>
          <div id="tipsContent"></div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card card-right mb-3">
        <div class="card-body text-center">
          <?php if ((int)$status == 0): ?>
            <h5 class="card-title fw-bold text-danger">Perlu diperhatikan!</h5>
            <p class="card-text">Hari ini si kecil belum mengonsumsi makanannya</p>
            <div class="btn btn-danger">Ingatkan si kecil ya!</div>
          <?php elseif (!empty($catatan)): ?>
            <h5 class="card-title fw-bold text-warning">Perlu diperhatikan!</h5>
            <p class="card-text">Hari ini si kecil sudah mengonsumsi makanannya namun terdapat catatan dari guru :</p>
            <p class="card-text fw-bold"><?= $catatan ?></p>
            <div class="btn btn-warning">Ingatkan si kecil ya!</div>
          <?php else: ?>
            <h5 class="card-title fw-bold text-success">Si kecil hebat!</h5>
            <p class="card-text">Hari ini si kecil sudah memenuhi gizi harian</p>
            <div class="btn btn-success">Beri apresiasi si kecil!</div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="<?= base_url('assets/js/getFormattedDate.js'); ?>"></script>

<script>
  const dashboardOrtuConfig = {
    publicKeyUrl: '<?= site_url('notification/get-public-key') ?>',
    subscribeUrl: '<?= site_url('notification/subscribe') ?>',
    recommendationUrl: '<?= site_url('dashboard/recommendation') ?>'
  };
</script>

<script src="<?= base_url('assets/js/pages/dashboard-ortu.js') ?>"></script>

<?= $this->endSection() ?>