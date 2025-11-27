<!-- Greeting -->
<h4 class="fw-bold">Hi, <?= session()->get('userRole') == 'ortu' ? 'OrTu ' . session()->get('nama') . '' : session()->get('nama') ?></h4>
<h5 class="text-muted mb-4">Selamat Datang di Website NMeal - <?= getenv('SCHOOL_NAME') ?></h5>