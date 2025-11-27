<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('styles') ?>
<style>

</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="edit-siswa">
  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= session()->getFlashdata('success') ?> <a href="/daftar-siswa">Lihat</a>
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
  <form id="form-register" action="<?= site_url('update-siswa/' . $siswa['id']) ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="row">
      <div class="col-md-6">
        <input type="hidden" value="<?= $siswa['nis'] ?>" name="old_nis">
        <div class="mb-3">
          <label for="nis" class="form-label">NIS<span class="text-danger">*</span></label>
          <input type="text" name="nis" class="form-control <?= $errors && isset($errors['nis']) ? 'is-invalid' : '' ?>" id="nis" placeholder="NIS" value="<?= old('nis', $siswa['nis']) ?>" required>
          <div class="invalid-feedback">
            <?= $errors && isset($errors['nis']) ? $errors['nis'] : '' ?>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3">
          <label for="kelas" class="form-label">Kelas<span class="text-danger">*</span></label>
          <select class="form-select <?= $errors && isset($errors['role']) ? 'is-invalid' : '' ?>" name="kelas" id="kelas">
            <option value="">Pilih Kelas</option>
            <option value="1"
              <?= old('kelas', $siswa['kelas']) == '1' ? 'selected' : '' ?>>
              Kelas 1
            </option>
            <option value="2"
              <?= old('kelas', $siswa['kelas']) == '2' ? 'selected' : '' ?>>
              Kelas 2
            </option>
            <option value="3"
              <?= old('kelas', $siswa['kelas']) == '3' ? 'selected' : '' ?>>
              Kelas 3
            </option>
            <option value="4"
              <?= old('kelas', $siswa['kelas']) == '4' ? 'selected' : '' ?>>
              Kelas 4
            </option>
            <option value="5"
              <?= old('kelas', $siswa['kelas']) == '5' ? 'selected' : '' ?>>
              Kelas 5
            </option>
            <option value="6"
              <?= old('kelas', $siswa['kelas']) == '6' ? 'selected' : '' ?>>
              Kelas 6
            </option>
          </select>
          <div class="invalid-feedback">
            <?= $errors && isset($errors['kelas']) ? $errors['kelas'] : '' ?>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="mb-3">
          <label for="nama_lengkap" class="form-label">Nama Lengkap<span class="text-danger">*</span></label>
          <input type="text" name="nama_lengkap" class="form-control <?= $errors && isset($errors['nama_lengkap']) ? 'is-invalid' : '' ?>" id="nama_lengkap" placeholder="Nama Lengkap" value="<?= old('nama_lengkap',  $siswa['nama_lengkap']) ?>">
          <div class="invalid-feedback">
            <?= $errors && isset($errors['nama_lengkap']) ? $errors['nama_lengkap'] : '' ?>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3">
          <label for="menu_makanan" class="form-label">Menu Makanan<span class="text-danger">*</span></label>
          <select class="form-select <?= $errors && isset($errors['food_id']) ? 'is-invalid' : '' ?>" name="menu_makanan" id="menu_makanan" required>
            <option value="">Pilih Menu Makanan</option>
            <?php foreach ($daftarMenuMakanan as $menuMakanan): ?>
              <option value="<?= $menuMakanan['id'] ?>"
                <?php
                $selectedVal = old('menu_makanan', $menuMakananSelected['food_id'] ?? '');
                ?>
                <?= $selectedVal == $menuMakanan['id'] ? 'selected' : '' ?>>
                <?= $menuMakanan['name'] ?>
              </option>
            <?php endforeach; ?>
          </select>
          <small class="form-text text-muted">
            Menu Makanan yang akan dikonsumsi siswa.
          </small>
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