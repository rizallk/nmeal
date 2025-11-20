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
          <div class="card bg-color-2 border-0 mb-3" id="btnRekomendasi" onclick="getRecommendation()" style="cursor: pointer">
            <div class="card-body text-center">
              <div class="d-flex justify-content-center align-items-center">
                <div class="spinner-border spinner-border-sm d-none" role="status" id="btnLoading">
                  <span class="visually-hidden">Loading...</span>
                </div>
                <h6 class="card-title mb-0 fw-bold ms-2" id="btnText">Lihat Rekomendasi</h6>
              </div>
            </div>
          </div>
          <div class="card bg-color-2 border-0 mb-3" id="tipsHeader">
            <div class="card-body text-center">
              <h6 class="card-title mb-0 fw-bold">Ayo dukung si kecil dengan tips makanan berikut!</h6>
            </div>
          </div>
          <p class="fw-bold color">Tips Hari Ini</p>
          <p id="tipsContent"></p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card card-right mb-3">
        <div class="card-body text-center">
          <h5 class="card-title fw-bold text-danger">Perlu diperhatikan!</h5>
          <p class="card-text">Hari ini si kecil belum mengonsumsi makanannya</p>
          <div class="btn btn-danger">Ingatkan si kecil ya!</div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="<?= base_url('assets/js/getFormattedDate.js'); ?>"></script>

<script>
  const btn = document.getElementById('btnRekomendasi');
  const tipsContentSaved = localStorage.getItem('tips') ? JSON.parse(localStorage.getItem('tips')) : null;
  const tipsHeader = document.getElementById('tipsHeader');
  const tipsContent = document.getElementById('tipsContent');

  if (getFormattedDate(new Date()) === tipsContentSaved?.date) {
    tipsContent.textContent = tipsContentSaved?.content
    btn.classList.add('d-none')
  } else {
    tipsHeader.classList.add('d-none')
    localStorage.setItem('tips', JSON.stringify({
      content: 'test',
      date: getFormattedDate(new Date())
    }))
  }

  async function getRecommendation() {
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
        tipsContent.innerHTML = data.message;
        localStorage.setItem('tips', JSON.stringify({
          content: data.message,
          date: getFormattedDate(new Date())
        }))
      } else {
        tipsContent.innerHTML = `<div class="alert alert-warning">${data.message || 'Gagal memuat rekomendasi.'}</div>`;
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