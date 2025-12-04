<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('styles') ?>
<style>

</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="tambah-siswa">
  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= session()->getFlashdata('success') ?> <a href="/daftar-siswa">Lihat</a>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= session()->getFlashdata('error') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>
  <?php $errors = session()->getFlashdata('validation'); ?>
  <form id="form-register" action="<?= site_url('tambah-siswa/register') ?>" method="post">
    <?= csrf_field() ?>
    <div class="row">
      <div class="col-md-6">
        <div class="mb-3">
          <label for="nis" class="form-label">NIS<span class="text-danger">*</span></label>
          <input type="text" name="nis" class="form-control <?= $errors && isset($errors['nis']) ? 'is-invalid' : '' ?>" id="nis" placeholder="NIS" value="<?= old('nis') ?>" required>
          <div class="invalid-feedback">
            <?= $errors && isset($errors['nis']) ? $errors['nis'] : '' ?>
          </div>
        </div>
        <div class="mb-3">
          <label for="nama_lengkap" class="form-label">Nama Lengkap<span class="text-danger">*</span></label>
          <input type="text" name="nama_lengkap" class="form-control <?= $errors && isset($errors['nama_lengkap']) ? 'is-invalid' : '' ?>" id="nama_lengkap" placeholder="Nama Lengkap" value="<?= old('nama_lengkap') ?>" required>
          <div class="invalid-feedback">
            <?= $errors && isset($errors['nama_lengkap']) ? $errors['nama_lengkap'] : '' ?>
          </div>
        </div>
        <div class="mb-3">
          <label for="kelas" class="form-label">Kelas<span class="text-danger">*</span></label>
          <select class="form-select <?= $errors && isset($errors['kelas']) ? 'is-invalid' : '' ?>" name="kelas" id="kelas" required>
            <option value="">Pilih Kelas</option>
            <option value="1"
              <?= old('kelas') == '1' ? 'selected' : '' ?>>
              Kelas 1
            </option>
            <option value="2"
              <?= old('kelas') == '2' ? 'selected' : '' ?>>
              Kelas 2
            </option>
            <option value="3"
              <?= old('kelas') == '3' ? 'selected' : '' ?>>
              Kelas 3
            </option>
            <option value="4"
              <?= old('kelas') == '4' ? 'selected' : '' ?>>
              Kelas 4
            </option>
            <option value="5"
              <?= old('kelas') == '5' ? 'selected' : '' ?>>
              Kelas 5
            </option>
            <option value="6"
              <?= old('kelas') == '6' ? 'selected' : '' ?>>
              Kelas 6
            </option>
          </select>
          <div class="invalid-feedback">
            <?= $errors && isset($errors['kelas']) ? $errors['kelas'] : '' ?>
          </div>
        </div>
        <div class="form-check mb-3">
          <input class="form-check-input <?= $errors && isset($errors['username']) ? 'is-invalid' : '' ?>" type="checkbox" value="true" id="create_parent_account" name="create_parent_account" checked>
          <label class="form-check-label" for="create_parent_account">
            Buatkan akun orang tua untuk siswa ini
          </label>
          <br>
          <small class="form-text text-muted">
            Username adalah NIS siswa.
            <br>
            Default password : NIS siswa + @ + ortu.
            <br>
            Contoh password : 1234581@ortu
          </small>
          <div class="invalid-feedback">
            <?= $errors && isset($errors['username']) ? $errors['username'] : '' ?>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card-legend">
          <div class="legend ms-3 px-2 py-1">Alergen</div>
          <div class="content-wrapper border rounded-3 p-3">
            <ul class="list-group list-group-flush mt-1" id="allergen-list">
              <li class="list-group-item text-muted empty-msg text-center">
                Tidak ada alergen yang dipilih
              </li>
            </ul>
            <div id="allergen-inputs"></div>
            <div class="input-group input-group-sm mt-3">
              <select class="form-select" id="select-allergen">
                <option value="">Pilih Alergen</option>
                <?php if (isset($allergens) && !empty($allergens)): ?>
                  <?php foreach ($allergens as $allergen): ?>
                    <option value="<?= $allergen['id'] ?>"><?= $allergen['name'] ?></option>
                  <?php endforeach; ?>
                <?php else: ?>
                  <option value="" disabled>Data alergen kosong</option>
                <?php endif; ?>
              </select>
              <button class="btn btn-outline-secondary" type="button" id="btn-add-allergen">Tambah</button>
            </div>
            <small class="text-danger d-none" id="allergen-error">Alergen sudah dipilih!</small>
          </div>
        </div>
      </div>
    </div>

    <div class="d-flex justify-content-end align-items-end h-100 mt-1">
      <button type="submit" class="btn btn-success">Submit</button>
    </div>
  </form>

  <script>
    const oldAllergenIds = <?= json_encode(old('allergens') ?? []) ?>;

    // Menampilkan alert ketika submit form dalam keadaan offline
    document.getElementById('form-register').addEventListener('submit', function(event) {
      if (!navigator.onLine) {
        event.preventDefault();

        Swal.fire({
          title: 'Koneksi Internet Terputus!',
          text: 'Anda sedang offline. Data tidak dapat dikirim sekarang.',
          icon: 'info',
        })
      }
    });

    document.addEventListener('DOMContentLoaded', function() {
      const btnAdd = document.getElementById('btn-add-allergen');
      const selectAllergen = document.getElementById('select-allergen');
      const allergenList = document.getElementById('allergen-list');
      const allergenInputs = document.getElementById('allergen-inputs');
      const errorMsg = document.getElementById('allergen-error');
      const emptyMsg = document.querySelector('.empty-msg');

      let selectedAllergens = [];

      function addAllergenToUi(id, text) {
        if (selectedAllergens.includes(id)) return;

        if (emptyMsg) emptyMsg.style.display = 'none';

        selectedAllergens.push(id);

        const li = document.createElement('li');
        li.className = 'list-group-item allergen-item';
        li.dataset.id = id;
        li.innerHTML = `
            <span>${text}</span>
            <button type="button" class="btn btn-sm text-danger btn-delete-allergen">Hapus</button>
        `;
        allergenList.appendChild(li);

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'allergens[]';
        input.value = id;
        input.id = `input-allergen-${id}`;
        allergenInputs.appendChild(input);
      }

      if (oldAllergenIds.length > 0) {
        oldAllergenIds.forEach(id => {
          const option = selectAllergen.querySelector(`option[value="${id}"]`);

          if (option) {
            const text = option.text;
            addAllergenToUi(id.toString(), text);
          }
        });
      }

      btnAdd.addEventListener('click', function() {
        const id = selectAllergen.value;

        if (!id) return;

        if (selectedAllergens.includes(id)) {
          errorMsg.classList.remove('d-none');
          setTimeout(() => errorMsg.classList.add('d-none'), 2000);
          return;
        }

        const text = selectAllergen.options[selectAllergen.selectedIndex].text;

        addAllergenToUi(id, text);

        selectAllergen.value = "";
      });

      allergenList.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-delete-allergen')) {
          const li = e.target.closest('li');
          const id = li.dataset.id;

          selectedAllergens = selectedAllergens.filter(item => item !== id);

          const inputToRemove = document.getElementById(`input-allergen-${id}`);
          if (inputToRemove) inputToRemove.remove();

          li.remove();

          if (selectedAllergens.length === 0 && emptyMsg) {
            emptyMsg.style.display = 'block';
          }
        }
      });
    });
  </script>
</div>
<?= $this->endSection() ?>