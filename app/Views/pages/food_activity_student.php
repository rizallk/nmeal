<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('styles') ?>
<style>
  .food-activity .table .status {
    position: relative;
    width: 120px;
  }

  .food-activity .table .catatan-value {
    white-space: nowrap;
    position: relative;

  }

  .food-activity .table tr td {
    white-space: nowrap;
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="food-activity">
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
    <p class="mb-1">Nama Siswa : <?= session()->get('nama') ?></p>
    <p>Kelas : <?= $kelas ?></p>
    <form id="search-form" action="<?= site_url('food-activity') ?>" method="get">
      <div class="row">
        <div class="col-md">
          <div class="row mb-3">
            <label for="tanggal" class="col-sm-auto col-form-label">Tanggal</label>
            <div class="col-sm">
              <div class="input-group input-group">
                <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= $tanggalFilter ?>" onchange="this.form.submit()">
                <?php if (!empty($tanggalFilter)): ?>
                  <a href="<?= clear_spesific_filter_helper('food-activity', $currentFilters, 'tanggal') ?>" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md">
          <div class="input-group input-group mb-3">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="search" class="form-control" name="search" value="<?= esc($search) ?>" placeholder="Cari...">
            <button class="btn btn-success" type="submit">Cari</button>
            <?php if (!empty($search)): ?>
              <a href="<?= clear_spesific_filter_helper('food-activity', $currentFilters, 'search') ?>" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </form>
  </div>
  <div class="table-responsive mb-3">
    <table class="table table-striped table-bordered table-hover mb-0">
      <thead class="table-light">
        <tr>
          <th style="width: 40px;">No</th>
          <th>
            <a href="<?= buildSortLink('food-activity', 'tanggal', $sortColumn, $sortOrder, $currentFilters) ?>" class="text-dark text-decoration-none">
              Tanggal <?= getSortIcon('tanggal', $sortColumn, $sortOrder) ?>
            </a>
          </th>
          <th class="status">
            <a href="<?= buildSortLink('food-activity', 'status', $sortColumn, $sortOrder, $currentFilters) ?>" class="text-dark text-decoration-none">
              Status Makan <?= getSortIcon('status', $sortColumn, $sortOrder) ?>
            </a>
          </th>
          <th>
            Menu Makanan
          </th>
          <th>
            Catatan
          </th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($data)): ?>
          <?php $no = 0; ?>
          <?php foreach ($data as $d): ?>
            <tr>
              <td><?= ++$no ?></td>
              <td><?= formatTanggalIndo(esc($d['created_at'])) ?></td>
              <td class="text-center"><span class="badge rounded-pill <?= esc($d['status']) == 1 ? "text-bg-success" : "text-bg-danger" ?>"><?= esc($d['status']) == 1 ? "Sudah" : "Belum" ?></span>
              </td>
              <td><?= esc($d['menu_makanan']) ?></td>
              <td><?= esc($d['catatan']) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="5" class="text-center">Tidak ada data yang ditemukan.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="d-flex justify-content-center mt-3">
    <?= $pager->links('default', 'pagination') ?>
  </div>
</div>
<?= $this->endSection() ?>