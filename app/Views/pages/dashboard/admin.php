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
          <h1 class="card-title fw-bold"><?= $totalSudahMakan ?? 0 ?></h1>
          <p class="card-text">Jumlah Siswa Sudah Terima Makanan Hari Ini</p>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card accent-color-bg text-white mb-3">
        <div class="card-body text-center">
          <h1 class="card-title fw-bold"><?= $totalBelumMakan ?? 0 ?></h1>
          <p class="card-text">Jumlah Siswa Belum Terima Makanan Hari Ini</p>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-4">
      <canvas id="studentAllergensChart"></canvas>
    </div>
    <div class="col-md-8">
      <canvas id="studentsChart"></canvas>
    </div>
  </div>

  <script>
    const studentAllergensChart = document.getElementById('studentAllergensChart');
    const studentsChart = document.getElementById('studentsChart');

    new Chart(studentAllergensChart, {
      type: 'pie',
      data: {
        labels: <?= $studentAllergenChart['labels'] ?>,
        datasets: [{
          label: ' Jumlah Siswa',
          data: <?= $studentAllergenChart['data'] ?>,
        }]
      },
      plugins: [ChartDataLabels],
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'bottom',
          },
          title: {
            display: true,
            text: 'Distribusi Jenis Alergi Siswa',
            font: {
              size: 14,
            }
          },
          // Format tampilan data angka pada chart
          datalabels: {
            color: '#fff',
            font: {
              weight: 'bold',
              size: 12
            },
            formatter: (value, ctx) => {
              let sum = 0;
              let dataArr = ctx.chart.data.datasets[0].data;

              // PERBAIKAN DI SINI: Gunakan Number() atau parseFloat()
              dataArr.map(data => {
                sum += Number(data); // Memaksa string "5" menjadi angka 5
              });

              // Mencegah pembagian dengan nol
              if (sum === 0) return "0%";

              let percentage = (value * 100 / sum).toFixed(1) + "%";
              return percentage;
            }
          }
        }
      },
    });

    new Chart(studentsChart, {
      type: 'line',
      data: {
        labels: <?= $classChart['labels'] ?>,
        datasets: [{
          label: ' Jumlah Siswa',
          data: <?= $classChart['data'] ?>,
          backgroundColor: 'rgba(54, 162, 235, 0.2)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 2,
          pointBackgroundColor: '#fff',
          pointBorderColor: 'rgba(54, 162, 235, 1)',
          pointRadius: 3,
          pointHoverRadius: 5,
          fill: true,
          tension: 0.4
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            display: false
          },
          title: {
            display: true,
            text: 'Tren Pengambilan Makanan (30 Hari Terakhir)',
            font: {
              size: 14,
            }
          },
          datalabels: {
            display: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            title: {
              display: true,
              text: 'Jumlah Siswa'
            },
            ticks: {
              stepSize: 1
            },
            grid: {
              borderDash: [5, 5]
            }
          },
          x: {
            grid: {
              display: false
            },
            ticks: {
              maxTicksLimit: 10
            }
          }
        }
      }
    });
  </script>
</div>
<?= $this->endSection() ?>