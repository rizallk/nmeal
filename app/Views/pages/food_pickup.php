<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('styles') ?>
<style>
  .food-pickup .table .status {
    position: relative;
    width: 40px;
  }

  .food-pickup .table .form-check-input {
    width: 1.2em;
    height: 1.2em;
  }

  .food-pickup .table tr td {
    white-space: nowrap;
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="food-pickup">
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
  <div class="header mb-1">
    <form id="search-form" action="<?= site_url('food-pickup') ?>" method="get">
      <div class="row">
        <div class="col-md">
          <div class="row mb-3">
            <label for="kelas" class="col-sm-auto col-form-label">Kelas</label>
            <div class="col-sm">
              <select class="form-select" id="kelas" name="kelas" aria-label="Pilih Kelas" onchange="this.form.submit()">
                <option value="" <?= empty($kelasFilter) ? 'selected' : '' ?>>Pilih Kelas</option>
                <option value="1" <?= $kelasFilter == '1' ? 'selected' : '' ?>>Kelas 1</option>
                <option value="2" <?= $kelasFilter == '2' ? 'selected' : '' ?>>Kelas 2</option>
                <option value="3" <?= $kelasFilter == '3' ? 'selected' : '' ?>>Kelas 3</option>
                <option value="4" <?= $kelasFilter == '4' ? 'selected' : '' ?>>Kelas 4</option>
                <option value="5" <?= $kelasFilter == '5' ? 'selected' : '' ?>>Kelas 5</option>
              </select>
            </div>
          </div>
        </div>
        <div class="col-md">
          <div class="row mb-3">
            <label for="tanggal" class="col-sm-auto col-form-label">Tanggal</label>
            <div class="col-sm">
              <input type="date" class="form-control" id="tanggal" name="tanggal" <?= empty($kelasFilter) ? 'disabled' : '' ?>>
            </div>
          </div>
        </div>
        <div class="col-md">
          <div class="input-group input-group mb-3">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="search" class="form-control" name="search" value="<?= esc($search) ?>" placeholder="Cari nama atau yang lain..." <?= empty($kelasFilter) ? 'disabled' : '' ?>>
            <button class="btn btn-success" type="submit" <?= empty($kelasFilter) ? 'disabled' : '' ?>>Cari</button>
            <?php if (!empty($search)): ?>
              <a href="<?= site_url('daftar-user') ?>" class="btn btn-outline-secondary">Reset</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </form>
    <hr class="mt-0">
    <?php if (!empty($kelasFilter)): ?>
      <button class="btn btn-sm btn-warning mb-3"><b>INFO :</b> Pengambilan makanan untuk hari ini (<b><?= formatTanggalIndo(date("d-m-Y")) ?></b>)</button>
    <?php endif; ?>
  </div>
  <?php if (empty($kelasFilter)): ?>
    <div class="alert alert-info text-center" role="alert">
      <i class="bi bi-info-circle-fill me-1"></i>
      Silakan pilih <strong>Kelas</strong> terlebih dahulu untuk menampilkan data aktivitas.
    </div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th style="width: 40px;">No</th>
            <th class="text-center status">
              <div class="form-check">
                <div class="d-flex justify-content-center">
                  <div class="bg-info" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Tooltip on top">
                    <input class="form-check-input" type="checkbox" value="" id="checkedAll">
                  </div>
                </div>
              </div>
              </td>
            </th>
            <th>
              <a href="<?= buildSortLink('daftar-user', 'nama', $sortColumn, $sortOrder, $search) ?>" class="text-dark text-decoration-none">
                Nama <?= getSortIcon('nama', $sortColumn, $sortOrder) ?>
              </a>
            </th>
            <th>
              Status
            </th>
            <th>
              Laporan
            </th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($data)): ?>
            <?php $no = 0; ?>
            <?php foreach ($data as $d): ?>
              <tr>
                <td><?= ++$no ?></td>
                <td class="position-relative">
                  <div class="form-check">
                    <div class="d-flex justify-content-center">
                      <input class="form-check-input" type="checkbox" value="" id="checkDefault">
                    </div>
                  </div>
                </td>
                <td><?= esc($d['nama_siswa']) ?></td>
                <td><?= esc($d['status']) ?></td>
                <td><?= esc($d['laporan']) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" class="text-center">Tidak ada data yang ditemukan.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
<?= $this->endSection() ?>