<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('styles') ?>
<style>

</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="edit-user">
  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= session()->getFlashdata('success') ?>
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
  <form action="<?= site_url('update-user/' . $user['id']) ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <input type="hidden" name="username_lama" value="<?= esc($user['username']) ?>">
    <input type="hidden" name="foto_lama" value="<?= esc($user['foto']) ?>">
    <div class="row">
      <div class="col-md-6">
        <div class="mb-3">
          <label for="nama_lengkap" class="form-label">Nama Lengkap <?= old('role', $user['role']) == 'ortu' ? 'Siswa' : '' ?></label>
          <input type="text" name="nama_lengkap" class="form-control <?= $errors && isset($errors['nama_lengkap']) ? 'is-invalid' : '' ?>" id="nama_lengkap" placeholder="Nama Lengkap" value="<?= old('nama_lengkap', $user['nama_lengkap']) ?>" required>
          <div class="invalid-feedback">
            <?= $errors && isset($errors['nama_lengkap']) ? $errors['nama_lengkap'] : '' ?>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3">
          <label for="role" class="form-label">Role</label>
          <select class="form-select <?= $errors && isset($errors['role']) ? 'is-invalid' : '' ?>" name="role" id="role" required>
            <option value="">Pilih Role</option>
            <option value="admin"
              <?= old('role', $user['role']) == 'admin' ? 'selected' : '' ?>>
              Admin
            </option>
            <option value="guru"
              <?= old('role', $user['role']) == 'guru' ? 'selected' : '' ?>>
              Guru
            </option>
            <option value="ortu"
              <?= old('role', $user['role']) == 'ortu' ? 'selected' : '' ?>>
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
          <label for="username" class="form-label">Username</label>
          <input type="text" name="username" class="form-control <?= $errors && isset($errors['username']) ? 'is-invalid' : '' ?>" id="username" placeholder="Username" value="<?= old('username', $user['username']) ?>" required>
          <div class="invalid-feedback">
            <?= $errors && isset($errors['username']) ? $errors['username'] : '' ?>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3">
          <label for="password" class="form-label">Password baru</label>
          <div class="input-group">
            <input type="password" name="password" class="form-control <?= $errors && isset($errors['password']) ? 'is-invalid' : '' ?>" id="password" placeholder="Password baru">
            <button class="btn btn-light border rounded-end"
              type="button"
              id="togglePassword">
              <i class="bi bi-eye-slash" id="toggleIcon"></i>
            </button>
          </div>
          <small class="form-text text-muted">
            Kosongkan jika tidak ingin mengubah password.
          </small>
          <small class="text-danger" style="margin-top: 0.25rem;">
            <?= $errors && isset($errors['password']) ? $errors['password'] : '' ?>
          </small>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-2">
        <div class="mb-3">
          <label class="form-label">Foto Saat Ini</label><br>
          <?php if (!empty($user['foto'])): ?>
            <img class="img-fluid" style="height: auto; width: 200px;" src="<?= base_url('uploads/foto_user/' . esc($user['foto'])) ?>" alt="<?= esc($user['nama_lengkap']) ?>">
          <?php endif; ?>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3">
          <label for="formFile" class="form-label <?= $errors && isset($errors['foto']) ? 'is-invalid' : '' ?>">Ganti Foto</label>
          <input class="form-control" type="file" id="formFile" name="foto">
          <div class="invalid-feedback">
            <?= $errors && isset($errors['foto']) ? $errors['foto'] : '' ?>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="d-flex justify-content-end align-items-end h-100 mt-1">
          <a href="<?= site_url('daftar-user') ?>" class="btn btn-secondary me-2">Batal</a>
          <button type="submit" class="btn btn-success">Update</button>
        </div>
      </div>
    </div>
  </form>

  <script>
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