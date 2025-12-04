<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pages/login.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content-wrapper') ?>
<div class="login">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md">
        <div class="logo-wrapper d-flex justify-content-center align-items-center flex-column py-5">
          <img class="logo pb-5" src="<?= base_url('assets/images/logo.jpeg') ?>" alt="Logo" />
          <h3 style="font-weight: 700;"><?= getenv('SCHOOL_NAME') ?></h3>
          <a class="d-flex d-md-none align-items-center flex-column text-decoration-none text-body" href="#login">
            <div class="mt-3">Login</div>
            <i class="bi bi-chevron-compact-down"></i>
          </a>
        </div>
      </div>
      <div class="col-md content-column">
        <div class="content py-5 d-flex justify-content-center align-items-center">
          <div class="box" id="login">
            <h2 class="text-light text-center fw-bold">Selamat Datang</h2>
            <p class="text-secondary text-center mb-4">Masuk dengan akunmu</p>
            <?php if (session()->getFlashdata('error')): ?>
              <div class="alert alert-danger text-center" role="alert">
                <?= session()->getFlashdata('error') ?>
              </div>
            <?php endif; ?>
            <?php $errors = session()->getFlashdata('validation'); ?>
            <form action="<?= site_url('login/auth') ?>" method="post">
              <?= csrf_field() ?>
              <div class="mb-3">
                <label for="username" class="form-label text-light">Username</label>
                <input type="text" name="username" class="form-control <?= $errors && isset($errors['username']) ? 'is-invalid' : '' ?>" id="username" placeholder="Username" required>
                <div class="invalid-feedback">
                  <?= $errors && isset($errors['username']) ? $errors['username'] : '' ?>
                </div>
              </div>
              <div class="mb-3">
                <label for="password" class="form-label text-light">Password</label>
                <div class="input-group">
                  <input type="password" name="password" class="form-control <?= $errors && isset($errors['password']) ? 'is-invalid' : '' ?>" id="password" placeholder="Password" required>
                  <button class="btn btn-light rounded-end"
                    type="button"
                    id="togglePassword">
                    <i class="bi bi-eye-slash" id="toggleIcon"></i>
                  </button>
                </div>
                <small class="text-danger" style="margin-top: 0.25rem;">
                  <?= $errors && isset($errors['password']) ? $errors['password'] : '' ?>
                </small>
              </div>
              <div class="d-flex justify-content-center">
                <button type="submit" class="btn submit btn-primary-custom rounded-pill mt-3">Masuk</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

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