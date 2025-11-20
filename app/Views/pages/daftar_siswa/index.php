<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('styles') ?>
<style>
    .daftar-siswa .table .action {
        width: 100px;
    }

    .daftar-siswa .table tr td {
        white-space: nowrap;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="daftar-siswa">
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
        <form id="search-form" action="<?= site_url('daftar-siswa') ?>" method="get">
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
                                    <a href="<?= clear_spesific_filter_helper('daftar-siswa', $currentFilters, 'kelas') ?>" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="input-group input-group mb-3">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="search" class="form-control" name="search" value="<?= esc($search) ?>" placeholder="Cari nama atau yang lain...">
                        <button class="btn btn-success" type="submit">Cari</button>
                        <?php if (!empty($search)): ?>
                            <a href="<?= clear_spesific_filter_helper('daftar-siswa', $currentFilters, 'search') ?>" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
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
                        <a href="<?= buildSortLink('daftar-siswa', 'nis', $sortColumn, $sortOrder, $currentFilters) ?>" class="text-dark text-decoration-none">
                            NIS <?= getSortIcon('nis', $sortColumn, $sortOrder) ?>
                        </a>
                    </th>
                    <th>
                        <a href="<?= buildSortLink('daftar-siswa', 'nama_lengkap', $sortColumn, $sortOrder, $currentFilters) ?>" class="text-dark text-decoration-none">
                            Nama <?= getSortIcon('nama_lengkap', $sortColumn, $sortOrder) ?>
                        </a>
                    </th>
                    <th>
                        <a href="<?= buildSortLink('daftar-siswa', 'Kelas', $sortColumn, $sortOrder, $currentFilters) ?>" class="text-dark text-decoration-none">
                            Kelas <?= getSortIcon('Kelas', $sortColumn, $sortOrder) ?>
                        </a>
                    </th>
                    <th>
                        <a href="<?= buildSortLink('daftar-siswa', 'created_at', $sortColumn, $sortOrder, $currentFilters) ?>" class="text-dark text-decoration-none">
                            Tanggal Dibuat <?= getSortIcon('created_at', $sortColumn, $sortOrder) ?>
                        </a>
                    </th>
                    <th class="text-center action">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($daftarSiswa)): ?>
                    <?php $no = $startNumber; ?>
                    <?php foreach ($daftarSiswa as $siswa): ?>
                        <tr>
                            <td><?= ++$no ?></td>
                            <td><?= esc($siswa['nis']) ?></td>
                            <td><?= esc($siswa['nama_lengkap']) ?></td>
                            <td><?= esc($siswa['kelas']) ?></td>
                            <td><?= formatTanggalIndo(esc($siswa['created_at'])) ?></td>
                            <td class="text-center">
                                <a href="<?= site_url('edit-siswa/' . $siswa['id']) ?>" class="text-warning me-2">Edit</a>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#deleteModal" data-siswa-id="<?= $siswa['id'] ?>" data-siswa-name="<?= esc($siswa['nama_lengkap']) ?>" title="Hapus Siswa" class="text-danger">Hapus</a>
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
                    <h5 class="modal-title" id="deleteModalLabel"><i class="bi bi-exclamation-triangle me-2"></i> Konfirmasi Hapus Siswa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="deleteForm" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus siswa <strong id="studentName"></strong>?</p>
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

                alert("KONEKSI INTERNET TERPUTUS!\n\nAnda sedang offline. Tidak dapat melakukan operasi hapus sekarang. Silakan periksa koneksi internet Anda.");
            }
        });
        document.getElementById('search-form').addEventListener('submit', function(event) {
            if (!navigator.onLine) {
                event.preventDefault();

                alert("KONEKSI INTERNET TERPUTUS!\n\nAnda sedang offline. Tidak dapat melakukan pencarian sekarang. Silakan periksa koneksi internet Anda.");
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const deleteModal = document.getElementById('deleteModal');
            const deleteForm = document.getElementById('deleteForm');
            const studentNameElement = document.getElementById('studentName');

            if (deleteModal) {
                deleteModal.addEventListener('show.bs.modal', function(event) {
                    // Tombol yang memicu modal
                    const button = event.relatedTarget;

                    // Ambil data ID dan Nama dari tombol
                    const studentId = button.getAttribute('data-siswa-id');
                    const studentName = button.getAttribute('data-siswa-name');

                    // Perbarui konten modal
                    studentNameElement.textContent = studentName;

                    // Set URL action untuk form delete
                    deleteForm.setAttribute('action', '<?= site_url('delete-siswa') ?>/' + studentId);
                });
            }
        });
    </script>
</div>
<?= $this->endSection() ?>