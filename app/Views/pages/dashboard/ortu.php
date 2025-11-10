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
    color: #6ecc46ff;
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('partials/greeting') ?>

<!-- Header -->
<a href="/aktivitas-terkini" class="btn btn-primary-custom text-center rounded mb-4 w-100">
  <h5 class="fw-bold">Lihat Aktivitas Makan si Kecil</h5>
  Tekan disini untuk melihatnya
</a>

<!-- Contents -->
<div class="home-ortu">
  <h5 class="fw-bold mb-3">Rekomendasi untuk Anak</h5>
  <div class="row">
    <div class="col-md-8">
      <div class="card bg-color-1 p-3 border-0 mb-3">
        <div class="card px-4 py-3 border-0">
          <div class="card bg-color-2 border-0 mb-3">
            <div class="card-body text-center">
              <h6 class="card-title mb-0 fw-bold">Ayo dukung si kecil makan sayur!</h6>
            </div>
          </div>
          <p class="fw-bold color">Tips Hari Ini</p>
          <p>Tambahkan Sayur ke dalam menu favorit anak, misalnya mie goreng dengan wortel dan brokoli</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card card-right mb-3">
        <div class="card-body text-center">
          <h5 class="card-title fw-bold text-danger">Perlu diperhatikan!</h5>
          <p class="card-text">Hari ini si kecil sama sekali belum mengonsumsi sayur</p>
          <div class="btn btn-danger">Ingatkan si kecil ya!</div>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>