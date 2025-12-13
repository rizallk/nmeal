<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('styles') ?>
<style>

</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="tambah-allergen">
  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= session()->getFlashdata('success') ?> <a href="/daftar-allergen">Lihat</a>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= session()->getFlashdata('error') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>
  <?php $errors = session()->getFlashdata('validation'); ?>
  <form id="form-register" action="<?= site_url('tambah-allergen/register') ?>" method="post">
    <?= csrf_field() ?>
    <div class="row">
      <div class="col-md-6">
        <div class="mb-3">
          <label for="name" class="form-label">Nama allergen<span class="text-danger">*</span></label>
          <input type="text" name="name" class="form-control <?= $errors && isset($errors['name']) ? 'is-invalid' : '' ?>" id="name" placeholder="Nama Allergen" value="<?= old('name') ?>" required>
          <div class="invalid-feedback">
            <?= $errors && isset($errors['name']) ? $errors['name'] : '' ?>
          </div>
        </div>
      </div>
    </div>

    <div class="d-flex justify-content-end align-items-end h-100 mt-1">
      <button type="submit" class="btn btn-success">Submit</button>
    </div>
  </form>

  <script>
    // Menampilkan alert ketika submit form dalam keadaan offline
    document.getElementById('form-register').addEventListener('submit', function(event) {
      if (!navigator.onLine) {
        event.preventDefault();

        Swal.fire({
          title: 'Koneksi Internet Terputus!',
          text: 'Anda sedang offline. Data tidak dapat dikirim sekarang.',
          icon: 'info',
        })
      }
    });
  </script>
</div>
<?= $this->endSection() ?>