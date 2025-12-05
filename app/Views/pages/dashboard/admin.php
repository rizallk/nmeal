<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('styles') ?>
<style>
  .admin .foto {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 50%;
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('partials/greeting') ?>

<?php date_default_timezone_set('Asia/Makassar'); ?>

<div class="admin">
  <!-- Header -->
  <div class="secondary-color-bg text-white p-3 rounded mb-3">
    <div class="row">
      <div class="col-md-6">
        <div class="d-flex align-items-center mb-3 mb-md-0">
          <?php if (session()->get('userFoto')): ?>
            <img class="foto" src="<?= base_url('uploads/foto_user/' . session()->get('userFoto')) ?>" alt="Foto">
          <?php else: ?>
            <img class="foto" src="<?= base_url('assets/images/person-default.png') ?>" alt="Foto">
          <?php endif; ?>
          <div class="ms-3">
            <p class="mb-0"><?= session()->get('nama') ?></p>
            <p class="mb-0 text-secondary"><?= ucfirst(session()->get('userRole')) ?></p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="d-flex justify-content-end opacity-75">
          <i class="bi bi-calendar me-2"></i> <?= formatTanggalIndo(date("d-m-Y")) ?>
          <i class="bi bi-clock me-2 ms-4"></i> <?= date("H:i") ?> WITA
        </div>
      </div>
    </div>
  </div>

  <!-- Contents -->
  <div class="row">
    <div class="col-md-6">
      <div class="card primary-color-bg text-white mb-3">
        <div class="card-body text-center">
          <h1 class="card-title fw-bold">110</h1>
          <p class="card-text">Jumlah Siswa Sudah Terima Makanan Hari Ini</p>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card accent-color-bg text-white mb-3">
        <div class="card-body text-center">
          <h1 class="card-title fw-bold">34</h1>
          <p class="card-text">Jumlah Siswa Belum Terima Makanan Hari Ini</p>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-4">
      <canvas id="studentsChart"></canvas>
    </div>
    <div class="col-md-8">
      <canvas id="studentAllergensChart"></canvas>
    </div>
  </div>

  <script>
    const studentAllergensChart = document.getElementById('studentAllergensChart');
    const studentsChart = document.getElementById('studentsChart');

    new Chart(studentsChart, {
      type: 'pie',
      data: {
        labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
        datasets: [{
          data: [12, 19, 3, 5, 2, 3],
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'top',
          },
          title: {
            display: true,
            text: 'Jumlah Siswa yang memiliki Alergi'
          }
        }
      },
    });

    new Chart(studentAllergensChart, {
      type: 'bar',
      data: {
        labels: ['Kelas 1', 'Kelas 2', 'Kelas 3', 'Kelas 4', 'Kelas 5', 'Kelas 6'],
        datasets: [{
          label: '# of Votes',
          data: [12, 19, 3, 5, 2, 3],
          backgroundColor: [
            'rgba(255, 99, 132, 0.5)', // Merah
            'rgba(54, 162, 235, 0.5)', // Biru
            'rgba(255, 206, 86, 0.5)', // Kuning
            'rgba(75, 192, 192, 0.5)', // Hijau
            'rgba(153, 102, 255, 0.5)', // Ungu
            'rgba(255, 159, 64, 0.5)' // Jingga
          ],
          borderColor: [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)'
          ],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'top',
          },
          title: {
            display: true,
            text: 'Jumlah Siswa Per-Kelas'
          }
        },
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  </script>
</div>
<?= $this->endSection() ?>