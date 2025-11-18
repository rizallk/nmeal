<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('styles') ?>
<style>

</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="tambah-user">
  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= session()->getFlashdata('success') ?> <a href="/daftar-user">Lihat</a>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>
  <?php $errors = session()->getFlashdata('validation'); ?>
  <form id="form-register" action="<?= site_url('tambah-user/register') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="row">
      <div class="col-md-6">
        <div class="mb-3">
          <label for="nama_lengkap" class="form-label">Nama Lengkap<span class="text-danger">*</span></label>
          <input type="text" name="nama_lengkap" class="form-control <?= $errors && isset($errors['nama_lengkap']) ? 'is-invalid' : '' ?>" id="nama_lengkap" placeholder="Nama Lengkap" value="<?= old('nama_lengkap') ?>">
          <div class="invalid-feedback">
            <?= $errors && isset($errors['nama_lengkap']) ? $errors['nama_lengkap'] : '' ?>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3">
          <label for="role" class="form-label">Role<span class="text-danger">*</span></label>
          <select class="form-select <?= $errors && isset($errors['role']) ? 'is-invalid' : '' ?>" name="role" id="role">
            <option value="">Pilih Role</option>
            <option value="admin"
              <?= old('role') == 'admin' ? 'selected' : '' ?>>
              Admin
            </option>
            <option value="guru"
              <?= old('role') == 'guru' ? 'selected' : '' ?>>
              Guru
            </option>
            <option value="ortu"
              <?= old('role') == 'ortu' ? 'selected' : '' ?>>
              Orang Tua Siswa
            </option>
          </select>
          <div class="invalid-feedback">
            <?= $errors && isset($errors['role']) ? $errors['role'] : '' ?>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="mb-3">
          <label for="username" class="form-label">Username<span class="text-danger">*</span></label>
          <input type="text" name="username" class="form-control <?= $errors && isset($errors['username']) ? 'is-invalid' : '' ?>" id="username" placeholder="Username" value="<?= old('username') ?>">
          <div class="invalid-feedback">
            <?= $errors && isset($errors['username']) ? $errors['username'] : '' ?>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3">
          <label for="password" class="form-label">Password<span class="text-danger">*</span></label>
          <div class="input-group">
            <input type="password" name="password" class="form-control <?= $errors && isset($errors['password']) ? 'is-invalid' : '' ?>" id="password" placeholder="Password" value="<?= old('password') ?>">
            <button class="btn btn-light border rounded-end"
              type="button"
              id="togglePassword">
              <i class="bi bi-eye-slash" id="toggleIcon"></i>
            </button>
          </div>
          <small class="text-danger" style="margin-top: 0.25rem;">
            <?= $errors && isset($errors['password']) ? $errors['password'] : '' ?>
          </small>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="mb-3">
          <label for="formFile" class="form-label <?= $errors && isset($errors['foto']) ? 'is-invalid' : '' ?>">Foto</label>
          <input class="form-control" type="file" id="formFile" name="foto">
          <div class="invalid-feedback">
            <?= $errors && isset($errors['foto']) ? $errors['foto'] : '' ?>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="d-flex justify-content-end align-items-end h-100 mt-1">
          <button type="submit" class="btn btn-success">Submit</button>
        </div>
      </div>
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

    // Logika JavaScript untuk Toggle Password
    document.addEventListener('DOMContentLoaded', function() {
      const passwordInput = document.getElementById('password');
      const toggleButton = document.getElementById('togglePassword');
      const toggleIcon = document.getElementById('toggleIcon');

      toggleButton.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';

        passwordInput.setAttribute('type', type);

        if (type === 'password') {
          toggleIcon.classList.remove('bi-eye');
          toggleIcon.classList.add('bi-eye-slash');
        } else {
          toggleIcon.classList.remove('bi-eye-slash');
          toggleIcon.classList.add('bi-eye');
        }
      });
    });
  </script>
</div>
<?= $this->endSection() ?>