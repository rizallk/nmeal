<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('styles') ?>
<style>
  .food-pickup .table .status-checkbox {
    position: relative;
    width: 40px;
  }

  .food-pickup .table .nama {
    position: relative;
    width: 300px;
  }

  .food-pickup .table .status {
    position: relative;
    width: 80px;
  }

  .food-pickup .table .operator {
    position: relative;
    width: 200px;
  }

  .food-pickup .table .catatan-value {
    cursor: pointer;
    white-space: nowrap;
    position: relative;
  }

  .food-pickup .table .catatan-value .bi {
    position: absolute;
    right: 10px;
  }

  .food-pickup .table .form-check-input {
    width: 1.2em;
    height: 1.2em;
  }

  .food-pickup .table tr td {
    white-space: nowrap;
  }

  .food-pickup .info-is-editable {
    display: flex;
    justify-content: left;
  }

  .food-pickup .buttons {
    position: fixed;
    bottom: -8px;
    right: 20px;
    z-index: 1009;
  }

  @media (min-width: 768px) {
    .food-pickup .info-is-editable {
      justify-content: end;
    }
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
              <div class="input-group input-group">
                <select class="form-select" id="kelas" name="kelas" aria-label="Pilih Kelas" onchange="this.form.submit()">
                  <option value="" <?= empty($kelasFilter) ? 'selected' : '' ?>>Pilih Kelas</option>
                  <option value="1" <?= $kelasFilter == '1' ? 'selected' : '' ?>>Kelas 1</option>
                  <option value="2" <?= $kelasFilter == '2' ? 'selected' : '' ?>>Kelas 2</option>
                  <option value="3" <?= $kelasFilter == '3' ? 'selected' : '' ?>>Kelas 3</option>
                  <option value="4" <?= $kelasFilter == '4' ? 'selected' : '' ?>>Kelas 4</option>
                  <option value="5" <?= $kelasFilter == '5' ? 'selected' : '' ?>>Kelas 5</option>
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
    <div class="row">
      <div class="col-md">
        <?php if (!empty($kelasFilter) && !empty($data)): ?>
          <div class="alert alert-sm alert-<?= $tanggalFilter === date('Y-m-d') ? 'info' : 'secondary' ?>" role="alert">
            <?php if ($tanggalFilter === date("Y-m-d")): ?>
              <b>INFO :</b> Pengambilan makanan untuk hari ini (<b><?= formatTanggalIndo(date("d-m-Y")) ?></b>) di <b>Kelas <?= $kelasFilter ?></b>
            <?php else: ?>
              Riwayat Pengambilan makanan pada tanggal <b><?= formatTanggalIndo($tanggalFilter) ?></b> di <b>Kelas <?= $kelasFilter ?></b>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>
      <?php if (!$isEditable && !empty($data)): ?>
        <div class="col-md">
          <div class="info-is-editable">
            <div class="alert alert-sm alert-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i>Data ini tidak bisa diubah lagi</div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <?php if (empty($kelasFilter)): ?>
    <div class="alert alert-info text-center" role="alert">
      <i class="bi bi-info-circle-fill me-1"></i>
      Silakan pilih <strong>Kelas</strong> terlebih dahulu untuk menampilkan data pengambilan makanan.
    </div>
  <?php else: ?>
    <form action="<?= site_url('food-pickup/save') ?>" method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="tanggal" value="<?= $tanggalFilter ?>">
      <input type="hidden" name="kelas" value="<?= $kelasFilter ?>">

      <div class="table-responsive mb-3">
        <table class="table table-striped table-bordered table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th style="width: 40px;">No</th>
              <th class="text-center status-checkbox">
                <div class="form-check">
                  <div class="d-flex justify-content-center">
                    <input class="form-check-input" type="checkbox" value="" id="checkedAll" <?= $isEditable ? '' : 'disabled' ?>>
                  </div>
                </div>
                </td>
              </th>
              <th class="nama">
                <a href="<?= buildSortLink('food-pickup', 'nama_lengkap', $sortColumn, $sortOrder, $currentFilters) ?>" class="text-dark text-decoration-none">
                  Nama <?= getSortIcon('nama_lengkap', $sortColumn, $sortOrder) ?>
                </a>
              </th>
              <th class="status">
                Status
              </th>
              <th>
                Catatan
              </th>
              <th class="operator">
                Operator
              </th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($data)): ?>
              <?php $no = 0; ?>
              <?php foreach ($data as $d): ?>
                <tr class="<?= !$isEditable ? 'disabled' : '' ?>">
                  <td><?= ++$no ?></td>
                  <td class="position-relative">
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

                    <input
                      type="hidden"
                      name="catatan[<?= $d['student_id'] ?>]"
                      id="input_catatan_<?= $d['student_id'] ?>"
                      value="<?= esc($d['catatan']) ?>">
                  </td>
                  <td
                    data-bs-toggle="modal"
                    data-bs-target="#catatanModal"
                    data-student-id="<?= $d['student_id'] ?>" data-student-name="<?= $d['nama_siswa'] ?>" data-student-note="<?= $d['catatan'] ?>" style="cursor: pointer">
                    <?= esc($d['nama_siswa']) ?>
                  </td>
                  <td><span class="badge rounded-pill <?= esc($d['status']) == 1 ? "text-bg-success" : "text-bg-danger" ?>"><?= esc($d['status']) == 1 ? "Sudah" : "Belum" ?></span>
                  </td>
                  <td
                    class="catatan-value"
                    data-bs-toggle="modal" data-bs-target="#catatanModal"
                    data-student-id="<?= $d['student_id'] ?>" data-student-name="<?= $d['nama_siswa'] ?>" data-student-note="<?= $d['catatan'] ?>">
                    <div>
                      <span id="display_catatan_<?= $d['student_id'] ?>">
                        <?= esc($d['catatan']) ?>
                      </span>
                      <?php if ($isEditable): ?>
                        <i class="bi bi-pencil-square text-secondary" style="font-size: 0.8rem;"></i>
                      <?php endif; ?>
                    </div>
                  </td>
                  <td><?= esc($d['nama_operator']) ?></td>
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

      <?php if ($tanggalFilter <= date("Y-m-d")): ?>
        <button type="submit" class="btn btn-outline-primary">Cetak PDF</button>
      <?php endif; ?>

      <?php if ($isEditable): ?>
        <div class="buttons bg-white p-2 pb-3 shadow border rounded">
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success"></i>Simpan</button>
          </div>
        </div>
      <?php endif; ?>
    </form>
  <?php endif; ?>


  <!-- Modal Input Catatan -->
  <?php if ($isEditable): ?>
    <div class="modal fade" id="catatanModal" tabindex="-1" aria-labelledby="catatanModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Catatan - <b id="modalStudentName"></b></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="modalStudentId">
            <textarea class="form-control" id="modalStudentNote" rows="3" placeholder="Contoh: Alergi, Tidak makan sayur, dll..."></textarea>
            <div class="form-text text-muted">Klik "Set Catatan" untuk menyimpan sementara ke tabel. Klik tombol hijau "Simpan" di pojok kanan bawah untuk menyimpan ke database.</div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-primary" id="btnSetCatatan">Set Catatan</button>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <script>
    const checkedAll = document.querySelector('#checkedAll')

    if (checkedAll) {
      checkedAll.addEventListener('change', (e) => {
        const statusCheckbox = document.querySelectorAll('input.status-checkbox:not(:disabled)');

        if (e.target.checked) {
          statusCheckbox.forEach(element => {
            element.checked = true
          });
        } else {
          statusCheckbox.forEach(element => {
            element.checked = false
          });
        }
      })
    }

    document.addEventListener('DOMContentLoaded', function() {
      const catatanModal = document.getElementById('catatanModal');

      if (catatanModal) {
        catatanModal.addEventListener('show.bs.modal', function(event) {
          const button = event.relatedTarget;

          const id = button.getAttribute('data-student-id');
          const name = button.getAttribute('data-student-name');

          const currentVal = document.getElementById('input_catatan_' + id).value;

          document.getElementById('modalStudentId').value = id;
          document.getElementById('modalStudentName').textContent = name;
          document.getElementById('modalStudentNote').value = currentVal;
        });

        document.getElementById('btnSetCatatan').addEventListener('click', function() {
          const id = document.getElementById('modalStudentId').value;
          const val = document.getElementById('modalStudentNote').value;

          document.getElementById('input_catatan_' + id).value = val;

          const displaySpan = document.getElementById('display_catatan_' + id);
          if (val.trim() !== "") {
            displaySpan.innerHTML = val;
          }

          const modalInstance = bootstrap.Modal.getInstance(catatanModal);
          modalInstance.hide();
        });
      }
    });
  </script>
</div>
<?= $this->endSection() ?>