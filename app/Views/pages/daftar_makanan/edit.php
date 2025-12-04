<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('styles') ?>
<style>

</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="edit-makanan">
  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= session()->getFlashdata('success') ?> <a href="/daftar-makanan">Lihat</a>
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
  <form id="form-register" action="<?= site_url('update-makanan/' . $makanan['id']) ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="row">
      <div class="col-md-6">
        <input type="hidden" value="<?= $makanan['nis'] ?>" name="old_nis">
        <div class="mb-3">
          <label for="Makanan" class="form-label">bahan Makanan<span class="text-danger">*</span></label>
          <input type="text" name="Bahan Makanan" class="form-control <?= $errors && isset($errors['Bahan Makanan']) ? 'is-invalid' : '' ?>" id="bahan_makanan" placeholder="Bahan Makanan" value="<?= old('menu_makanan', $makanan['makanan']) ?>" required>
          <div class="invalid-feedback">
            <?= $errors && isset($errors['nis']) ? $errors['nis'] : '' ?>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3">
          <label for="bahan_makanan" class="form-label">Bahan Makanan<span class="text-danger">*</span></label>
          <select class="form-select <?= $errors && isset($errors['food_id']) ? 'is-invalid' : '' ?>" name="bahan_makanan" id="bahan_makanan" required>
            <option value="">Pilih Bahan Makanan</option>
            <?php foreach ($daftarBahanMakanan as $bahanMakanan): ?>
              <option value="<?= $bahanMakanan['id'] ?>"
                <?php
                $selectedVal = old('bahan_makanan', $bahanMakananSelected['food_id'] ?? '');
                ?>
                <?= $selectedVal == $bahanMakanan['id'] ? 'selected' : '' ?>>
                <?= $bahanMakanan['name'] ?>
              </option>
            <?php endforeach; ?>
          </select>
          <div class="invalid-feedback">
            <?= $errors && isset($errors['food_id']) ? $errors['food_id'] : '' ?>
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

        alert("KONEKSI INTERNET TERPUTUS!\n\nAnda sedang offline. Data tidak dapat dikirim sekarang. Silakan periksa koneksi internet Anda.");
      }
    });
  </script>
</div>
<?= $this->endSection() ?>