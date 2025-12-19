<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('styles') ?>
<style>
  .daftar-bahan-makanan .table .action {
    width: 100px;
  }

  .daftar-bahan-makanan .table tr td {
    white-space: nowrap;
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="daftar-bahan-makanan">
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
    <form id="search-form" action="<?= site_url('daftar-bahan-makanan') ?>" method="get">
      <div class="row">
        <div class="col-md">
          <div class="input-group input-group mb-3">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="search" class="form-control" name="search" value="<?= esc($search) ?>" placeholder="Cari nama atau yang lain...">
            <button class="btn btn-success" type="submit">Cari</button>
            <?php if (!empty($search)): ?>
              <a href="<?= clear_spesific_filter_helper('daftar-bahan-makanan', $currentFilters, 'search') ?>" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </form>
  </div>
  <div class="table-responsive">
    <table class="table table-striped table-bordered table-hover mb-0">
      <thead class="table-light">
        <tr>
          <th style="width: 40px;">No</th>
          <th>
            <a href="<?= buildSortLink('daftar-bahan-makanan', 'name', $sortColumn, $sortOrder, $currentFilters) ?>" class="text-dark text-decoration-none">
              Nama Bahan Makanan <?= getSortIcon('name', $sortColumn, $sortOrder) ?>
            </a>
          </th>
          <th class="text-center action">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($daftarBahanMakanan)): ?>
          <?php $no = $startNumber; ?>
          <?php foreach ($daftarBahanMakanan as $bahanMakanan): ?>
            <tr>
              <td><?= ++$no ?></td>
              <td><?= esc($bahanMakanan['name']) ?></td>
              <td class="text-center">
                <a href="<?= site_url('edit-bahan-makanan/' . $bahanMakanan['id']) ?>" class="text-warning me-2">Edit</a>
                <a href="#" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bahan-makanan-id="<?= $bahanMakanan['id'] ?>" data-bahan-makanan-name="<?= esc($bahanMakanan['name']) ?>" title="Hapus Bahan Makanan" class="text-danger">Hapus</a>
              </td>
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

  <div class="d-flex justify-content-center mt-3">
    <?= $pager->links('default', 'pagination') ?>
  </div>

  <!-- Modal Konfirmasi Hapus -->
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="deleteModalLabel"><i class="bi bi-exclamation-triangle me-2"></i> Konfirmasi Hapus Bahan Makanan</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="deleteForm" method="post">
          <?= csrf_field() ?>
          <div class="modal-body">
            <p>Apakah Anda yakin ingin menghapus bahan makanan <strong id="ingredientName"></strong>?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    // Menampilkan alert ketika submit form dalam keadaan offline
    document.getElementById('deleteForm').addEventListener('submit', function(event) {
      if (!navigator.onLine) {
        event.preventDefault();

        Swal.fire({
          title: 'Koneksi Internet Terputus!',
          text: 'Anda sedang offline. Tidak dapat melakukan operasi hapus sekarang. Silakan periksa koneksi internet Anda.',
          icon: 'info',
        })
      }
    });
    document.getElementById('search-form').addEventListener('submit', function(event) {
      if (!navigator.onLine) {
        event.preventDefault();

        Swal.fire({
          title: 'Koneksi Internet Terputus!',
          text: 'Anda sedang offline. Tidak dapat melakukan pencarian sekarang. Silakan periksa koneksi internet Anda.',
          icon: 'info',
        })
      }
    });

    document.addEventListener('DOMContentLoaded', function() {
      const deleteModal = document.getElementById('deleteModal');
      const deleteForm = document.getElementById('deleteForm');
      const ingredientNameElement = document.getElementById('ingredientName');

      if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function(event) {
          // Tombol yang memicu modal
          const button = event.relatedTarget;

          // Ambil data ID dan Nama dari tombol
          const ingredientId = button.getAttribute('data-bahan-makanan-id');
          const ingredientName = button.getAttribute('data-bahan-makanan-name');

          // Perbarui konten modal
          ingredientNameElement.textContent = ingredientName;
          // Set URL action untuk form delete
          deleteForm.setAttribute('action', '<?= site_url('delete-bahan-makanan') ?>/' + ingredientId);
        });
      }
    });

    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
  </script>
</div>
<?= $this->endSection() ?>