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
<?= $this->include('partials/greeting') ?>

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
  const tipsContentSaved = localStorage.getItem('tips') ? JSON.parse(localStorage.getItem('tips')) : null;
  const tipsHeader = document.getElementById('tipsHeader');
  const tipsContent = document.getElementById('tipsContent');
  const status = document.getElementById('status').value;
  const catatan = document.getElementById('catatan').value;

  if (tipsContentSaved?.status !== status || tipsContentSaved?.catatan !== catatan) {
    localStorage.removeItem('tips')
  }

  if (getFormattedDate(new Date()) === tipsContentSaved?.date) {
    tipsContent.innerHTML = `
      <p class="fw-bold color mt-3">Tips Hari Ini</p>
      ${tipsContentSaved?.content}
    `
    tipsHeader.innerHTML = `
      <div class="card bg-color-2 border-0" id="tipsHeader">
              <div class="card-body text-center">
                <h6 class="card-title mb-0 fw-bold">Ayo dukung si kecil dengan tips berikut!</h6>
              </div>
            </div>
    `
  } else {
    tipsHeader.innerHTML = `
      <div class="card bg-color-2 border-0" id="btnRekomendasi" onclick="getRecommendation()" style="cursor: pointer">
              <div class="card-body text-center">
                <div class="d-flex justify-content-center align-items-center">
                  <div class="spinner-border spinner-border-sm d-none" role="status" id="btnLoading">
                    <span class="visually-hidden">Loading...</span>
                  </div>
                  <h6 class="card-title mb-0 fw-bold ms-2" id="btnText">Minta Rekomendasi Makanan ke AI</h6>
                </div>
              </div>
            </div>
    `
  }

  async function getRecommendation() {
    const btn = document.getElementById('btnRekomendasi');
    const btnText = document.getElementById('btnText');
    const btnLoading = document.getElementById('btnLoading');

    btn.classList.add('disabled');
    btnText.textContent = "Sedang Menganalisis...";
    btnLoading.classList.remove('d-none');

    try {
      const response = await fetch('<?= site_url('dashboard/recommendation') ?>', {
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      });

      const data = await response.json();

      if (data.success) {
        tipsHeader.innerHTML = `
          <div class="card bg-color-2 border-0" id="tipsHeader">
                  <div class="card-body text-center">
                    <h6 class="card-title mb-0 fw-bold">Ayo dukung si kecil dengan tips berikut!</h6>
                  </div>
                </div>
        `
        tipsContent.innerHTML = `
          <p class="fw-bold color mt-3">Tips Hari Ini</p>
          ${data.message}
        `
        localStorage.setItem('tips', JSON.stringify({
          status,
          catatan,
          date: getFormattedDate(new Date()),
          content: data.message
        }))
      } else {
        tipsContent.innerHTML = `<div class="alert alert-danger">${data.message || 'Gagal memuat rekomendasi.'}</div>`;
      }
    } catch (error) {
      console.error(error);
      localStorage.removeItem('tips')
      alert('Terjadi kesalahan jaringan.');
    } finally {
      btn.classList.remove('disabled');
      btnText.textContent = "Lihat Rekomendasi";
      btnLoading.classList.add('d-none');
    }
  }
</script>

<?= $this->endSection() ?>