<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="assets/css/pages/food_pickup.css">
<?= $this->endSection() ?>

<?php
// Hari dan tanggal sekarang
$date = new IntlDateFormatter(
  'id_ID',
  IntlDateFormatter::FULL,
  IntlDateFormatter::NONE
);

$now = $date->format(time());
$dayFilter = date('l', strtotime($tanggalFilter)); // mengambil nama hari dari tanggal filter
$isDay = $dayFilter === 'Monday' || $dayFilter === 'Tuesday' || $dayFilter === 'Wednesday' || $dayFilter === 'Thursday' || $dayFilter === 'Friday' || $dayFilter === 'Saturday'; // filter hari, apakah termasuk hari masuk sekolah atau libur
?>

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
              <div class="input-group input-group">
                <select class="form-select" id="kelas" name="kelas" aria-label="Pilih Kelas" onchange="this.form.submit()">
                  <option value="" <?= empty($kelasFilter) ? 'selected' : '' ?>>Pilih Kelas</option>
                  <option value="1" <?= $kelasFilter == '1' ? 'selected' : '' ?>>Kelas 1</option>
                  <option value="2" <?= $kelasFilter == '2' ? 'selected' : '' ?>>Kelas 2</option>
                  <option value="3" <?= $kelasFilter == '3' ? 'selected' : '' ?>>Kelas 3</option>
                  <option value="4" <?= $kelasFilter == '4' ? 'selected' : '' ?>>Kelas 4</option>
                  <option value="5" <?= $kelasFilter == '5' ? 'selected' : '' ?>>Kelas 5</option>
                  <option value="6" <?= $kelasFilter == '6' ? 'selected' : '' ?>>Kelas 6</option>
                </select>
                <?php if (!empty($kelasFilter)): ?>
                  <a href="<?= clear_spesific_filter_helper('food-pickup', $currentFilters, 'kelas') ?>" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md">
          <div class="row mb-3">
            <label for="tanggal" class="col-sm-auto col-form-label">Tanggal</label>
            <div class="col-sm">
              <div class="input-group input-group">
                <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= $tanggalFilter ?>" onchange="this.form.submit()" <?= empty($kelasFilter) ? 'disabled' : '' ?>>
                <?php if (!empty($tanggalFilter)): ?>
                  <a href="<?= clear_spesific_filter_helper('food-pickup', $currentFilters, 'tanggal') ?>" class="btn btn-outline-secondary <?= empty($kelasFilter) ? 'disabled' : '' ?>"><i class="bi bi-x-lg"></i></a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md">
          <div class="input-group input-group mb-3">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="search" class="form-control" name="search" value="<?= esc($search) ?>" placeholder="Cari nama atau yang lain..." <?= empty($kelasFilter) ? 'disabled' : '' ?>>
            <button class="btn btn-success" type="submit" <?= empty($kelasFilter) ? 'disabled' : '' ?>>Cari</button>
            <?php if (!empty($search)): ?>
              <a href="<?= clear_spesific_filter_helper('food-pickup', $currentFilters, 'search') ?>" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </form>
    <hr class="mt-0">
    <?php if ($isDay): ?>
      <div class="row">
        <div class="col-md">
          <?php if (!empty($kelasFilter) && !empty($data)): ?>
            <div class="alert alert-sm alert-<?= $tanggalFilter === date('Y-m-d') ? 'info' : 'secondary' ?>" role="alert">
              <?php if ($tanggalFilter === date("Y-m-d")): ?>
                <b>INFO :</b> Pengambilan makanan untuk hari ini (<b><?= $now ?></b>) di <b>Kelas <?= $kelasFilter ?></b>
              <?php else: ?>
                Riwayat Pengambilan makanan pada tanggal <b><?= formatTanggalIndo($tanggalFilter) ?></b> di <b>Kelas <?= $kelasFilter ?></b>
              <?php endif; ?>
            </div>
          <?php endif; ?>
        </div>
        <?php if (!$isEditable && !empty($data)): ?>
          <div class="col-md">
            <div class="info-is-editable">
              <div class="alert alert-sm alert-warning"><i class="bi bi-exclamation-triangle-fill me-2"></i>Data ini tidak bisa diubah lagi</div>
            </div>
          </div>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
  <?php if (empty($kelasFilter)): ?>
    <div class="alert alert-info text-center" role="alert">
      <i class="bi bi-info-circle-fill me-1"></i>
      Silakan pilih <strong>Kelas</strong> terlebih dahulu untuk menampilkan data pengambilan makanan.
    </div>
  <?php else: ?>
    <form id="formFoodPickup" action="<?= site_url('food-pickup/save') ?>" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="tanggal" value="<?= $tanggalFilter ?>">
      <input type="hidden" name="kelas" value="<?= $kelasFilter ?>">

      <div class="table-responsive mb-3">
        <table class="table table-striped table-bordered table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th style="width: 40px;">No</th>
              <th class="text-center status-checkbox">
              </th>
              <th class="nama">
                <a href="<?= buildSortLink('food-pickup', 'nama_lengkap', $sortColumn, $sortOrder, $currentFilters) ?>" class="text-dark text-decoration-none">
                  Nama <?= getSortIcon('nama_lengkap', $sortColumn, $sortOrder) ?>
                </a>
              </th>
              <th class="status">
                Status
              </th>
              <th class="menu-makanan">Menu Makanan</th>
              <th class="catatan">
                Catatan
              </th>
              <th class="operator">
                Operator
              </th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($data) || !$isDay): ?>
              <tr>
                <td colspan="7" class="text-center">Tidak ada data yang ditemukan.</td>
              </tr>
            <?php else: ?>
              <?php $no = 0; ?>
              <?php foreach ($data as $d): ?>
                <tr
                  data-student-id="<?= $d['student_id'] ?>"
                  data-student-name="<?= $d['nama_siswa'] ?>"
                  data-student-note="<?= $d['catatan'] ?>"
                  data-student-food-id="<?= $d['food_id'] ?? '' ?>">
                  <td><?= ++$no ?></td>
                  <td class="position-relative">
                    <input
                      type="hidden"
                      name="catatan[<?= $d['student_id'] ?>]"
                      id="input_catatan_<?= $d['student_id'] ?>"
                      value="<?= esc($d['catatan']) ?>">
                    <input
                      type="hidden"
                      name="food_ids[<?= $d['student_id'] ?>]"
                      id="input_food_id_<?= $d['student_id'] ?>"
                      value="<?= $d['food_id'] ?? '' ?>">
                    <div class="form-check">
                      <div class="d-flex justify-content-center">
                        <input
                          class="form-check-input status-checkbox" type="checkbox"
                          name="student_ids[]"
                          value="<?= $d['student_id'] ?>"
                          <?= !empty($d['status']) ? 'checked' : '' ?>
                          <?= !$isEditable ? 'disabled' : '' ?>>
                      </div>
                    </div>
                  </td>
                  <td
                    class="nama"
                    data-bs-toggle="modal"
                    data-bs-target="#detailModal">
                    <span><?= esc($d['nama_siswa']) ?></span>
                    <?php if ($isEditable): ?>
                      <i class="bi bi-pencil-square text-secondary"></i>
                    <?php endif; ?>
                  </td>
                  <td class="text-center">
                    <span
                      id="status_badge_<?= $d['student_id'] ?>"
                      class="badge rounded-pill <?= esc($d['status']) == 1 ? "text-bg-success" : "text-bg-danger" ?>">
                      <?= esc($d['status']) == 1 ? "Sudah" : "Belum" ?>
                    </span>
                  </td>
                  <td
                    class="menu-makanan"
                    data-bs-toggle="modal"
                    data-bs-target="#detailModal">
                    <span id="display_food_name_<?= $d['student_id'] ?>">
                      <?= esc($d['menu_makanan']) ?>
                    </span>
                  </td>
                  <td
                    class="catatan"
                    data-bs-toggle="modal"
                    data-bs-target="#detailModal">
                    <span id="display_catatan_<?= $d['student_id'] ?>">
                      <?= esc($d['catatan']) ?>
                    </span>
                  </td>
                  <td class="operator">
                    <span id="display_operator_<?= $d['student_id'] ?>">
                      <?= esc($d['nama_operator']) ?>
                    </span>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <?php if ($tanggalFilter <= date("Y-m-d") && $isDay): ?>
        <a href="<?= site_url('food-pickup/export-pdf?' . http_build_query(array_filter($currentFilters))) ?>" target="_blank" class="btn btn-outline-primary">
          <i class="bi bi-printer me-2"></i>Cetak PDF
        </a>
      <?php endif; ?>

      <?php if ($isEditable): ?>
        <div class="buttons bg-white p-2 pb-3 shadow border rounded">
          <div class="d-flex gap-2">
            <button id="btnFinalSubmit" type="submit" class="btn btn-success"></i>Simpan</button>
          </div>
        </div>
      <?php endif; ?>
    </form>
  <?php endif; ?>

  <!-- Modal / Popup -->
  <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><b id="modalStudentName"></b></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="modalStudentId">
          <div class="mb-3">
            <div class="mb-2">Alergi Terhadap :</div>
            <ul class="border rounded alergen-list"></ul>
          </div>
          <div class="mb-3">
            <label for="modalFoodId" class="form-label">Menu Makanan<span class="text-danger">*</span> :</label>
            <select class="form-select" name="modal_menu_makanan" id="modalFoodId" <?= !$isEditable ? 'disabled' : '' ?>>
              <option value="">Pilih Menu Makanan</option>
              <?php foreach ($daftarMenuMakanan as $menuMakanan): ?>
                <option value="<?= $menuMakanan['id'] ?>"
                  data-allergens="<?= strtolower($menuMakanan['food_allergens'] ?? '') ?>"
                  <?= old('menu_makanan') == $menuMakanan['id'] ? 'selected' : '' ?>>
                  <?= $menuMakanan['name'] ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div id="allergenWarning" class="alert alert-sm alert-warning d-none" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-1"></i>
            <strong>PERINGATAN : </strong> Makanan ini mengandung alergen <span id="conflictAllergens" class="fw-bold"></span> yang memicu alergi siswa!
          </div>
          <label for="modalStudentNote" class="form-label">Catatan <small class="text-muted">(jika ada)</small> :</label>
          <textarea class="form-control mb-3" id="modalStudentNote" rows="3" placeholder="Contoh: Alergi, Tidak makan sayur, dll..." <?= !$isEditable ? 'disabled' : '' ?>></textarea>
          <?php if ($isEditable): ?>
            <div class="form-text text-muted">Klik tombol "Set" untuk menyimpan sementara ke tabel. Klik tombol hijau "Simpan" di pojok kanan bawah untuk menyimpan ke database.</div>
          <?php endif; ?>
        </div>
        <?php if ($isEditable): ?>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-primary" id="btnSet">Set</button>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- =================================================== -->
  <script src="<?= base_url('assets/js/offlineSync.js') ?>"></script>

  <script src="<?= base_url('assets/js/pages/food-pickup.js') ?>"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const pageConfig = {
        csrfTokenName: '<?= csrf_token() ?>',
        operatorName: '<?= session()->get('nama') ?>',
        getAllergensUrlPath: '<?= site_url('food-pickup/get-allergens') ?>'
      };

      initFoodPickupPage(pageConfig);
    });
  </script>
</div>
<?= $this->endSection() ?>