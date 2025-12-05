<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <?= csrf_meta() ?>
  <title><?= !empty($pageTitle) ? "$pageTitle - NMeal - " : 'NMeal - ' ?><?= getenv('SCHOOL_NAME') ?></title>

  <!-- Untuk kebutuhan PWA -->
  <link rel="manifest" href="/manifest.json">
  <meta name="theme-color" content="#000000">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <meta name="apple-mobile-web-app-title" content="NMeal">
  <link rel="apple-touch-icon" href="/icons/android-icon-192x192.png">

  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
    crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="<?= base_url('assets/css/main.css') ?>">
  <?= $this->renderSection('styles') ?> <!-- untuk CSS khusus halaman tertentu -->

  <!-- Javascript -->
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    // Script untuk mendaftarkan Service Worker (PWA)
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', () => {
        navigator.serviceWorker.register('/service-worker.js')
          .then(registration => {
            console.log('ServiceWorker registered successfully');
          })
          .catch(error => {
            console.log('ServiceWorker registration failed: ', error);
          });
      });
    }
  </script>
</head>

<body>
  <!-- Konten halaman -->
  <main>
    <?= $this->renderSection('content-wrapper') ?>
  </main>

  <!-- Javascript -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>

</html>