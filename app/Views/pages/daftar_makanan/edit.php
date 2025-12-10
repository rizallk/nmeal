<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('styles') ?>
<style>

</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="edit-makanan">
  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= session()->getFlashdata('success') ?> <a href="/daftar-makanan">Lihat</a>
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
  <form id="form-register" action="<?= site_url('update-makanan/' . $makanan['id']) ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="row">
      <div class="col-md-6">
        <input type="hidden" value="<?= $makanan['name'] ?>" name="old_name">
        <div class="mb-3">
          <label for="name" class="form-label">Nama Makanan<span class="text-danger">*</span></label>
          <input type="text" name="name" class="form-control <?= $errors && isset($errors['name']) ? 'is-invalid' : '' ?>" id="name" placeholder="Nama Makanan" value="<?= old('name', $makanan['name']) ?>" required>
          <div class="invalid-feedback">
            <?= $errors && isset($errors['name']) ? $errors['name'] : '' ?>
          </div>
        </div>
        <div class="card-legend">
          <div class="legend ms-3 px-2 py-1">Bahan Makanan</div>
          <div class="content-wrapper border rounded-3 p-3">
            <ul class="list-group list-group-flush mt-1" id="ingredient-list">
              <li class="list-group-item text-muted ingredient-empty-msg text-center">
                Tidak ada bahan makanan yang dipilih
              </li>
            </ul>
            <div id="ingredient-inputs"></div>
            <div class="input-group input-group-sm mt-3">
              <select class="form-select" id="select-ingredient">
                <option value="">Pilih Bahan Makanan</option>
                <?php if (isset($ingredients) && !empty($ingredients)): ?>
                  <?php foreach ($ingredients as $ingredient): ?>
                    <option value="<?= $ingredient['id'] ?>"><?= $ingredient['name'] ?></option>
                  <?php endforeach; ?>
                <?php else: ?>
                  <option value="" disabled>Data bahan makanan kosong</option>
                <?php endif; ?>
              </select>
              <button class="btn btn-outline-secondary" type="button" id="btn-add-ingredient">Tambah</button>
            </div>
            <small class="text-danger d-none" id="ingredient-error">Bahan Makanan sudah dipilih!</small>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card-legend">
          <div class="legend ms-3 px-2 py-1">Alergen</div>
          <div class="content-wrapper border rounded-3 p-3">
            <ul class="list-group list-group-flush mt-1" id="allergen-list">
              <li class="list-group-item text-muted allergen-empty-msg text-center">
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
    <?php
    $oldInputAllergen = old('allergens');

    $dbDataAllergen = [];
    if (isset($foodAllergens)) {
      foreach ($foodAllergens as $fa) {
        $dbDataAllergen[] = $fa['allergen_id'];
      }
    }

    $finalDataAllergen = $oldInputAllergen ? $oldInputAllergen : $dbDataAllergen;

    $oldInputIngredient = old('ingredients');

    $dbDataIngredient = [];
    if (isset($foodIngredients)) {
      foreach ($foodIngredients as $fi) {
        $dbDataIngredient[] = $fi['ingredient_id'];
      }
    }

    $finalDataIngredient = $oldInputIngredient ? $oldInputIngredient : $dbDataIngredient;
    ?>

    const initialAllergenIds = <?= json_encode($finalDataAllergen ?? []) ?>;
    const initialIngredientIds = <?= json_encode($finalDataIngredient ?? []) ?>;

    // Menampilkan alert ketika submit form dalam keadaan offline
    document.getElementById('form-register').addEventListener('submit', function(event) {
      if (!navigator.onLine) {
        event.preventDefault();

        Swal.fire({
          title: 'Koneksi Internet Terputus!',
          text: 'Anda sedang offline. Data tidak dapat dikirim sekarang. Silakan periksa koneksi internet Anda.',
          icon: 'info',
        })
      }
    });

    document.addEventListener('DOMContentLoaded', function() {
      const btnAdd = document.getElementById('btn-add-allergen');
      const selectAllergen = document.getElementById('select-allergen');
      const allergenList = document.getElementById('allergen-list');
      const allergenInputs = document.getElementById('allergen-inputs');
      const allergenErrorMsg = document.getElementById('allergen-error');
      const allergenEmptyMsg = document.querySelector('.allergen-empty-msg');

      let selectedAllergens = [];

      function addAllergenToUi(id, text) {
        if (selectedAllergens.includes(id)) return;
        if (allergenEmptyMsg) allergenEmptyMsg.style.display = 'none';

        selectedAllergens.push(id);

        const li = document.createElement('li');
        li.className = 'list-group-item allergen-item d-flex justify-content-between align-items-center';
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

      if (initialAllergenIds.length > 0) {
        initialAllergenIds.forEach(id => {
          const option = selectAllergen.querySelector(`option[value="${id}"]`);
          if (option) {
            addAllergenToUi(id.toString(), option.text);
          }
        });
      }

      btnAdd.addEventListener('click', function() {
        const id = selectAllergen.value;
        if (!id) return;

        if (selectedAllergens.includes(id)) {
          allergenErrorMsg.classList.remove('d-none');
          setTimeout(() => allergenErrorMsg.classList.add('d-none'), 2000);
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

          if (selectedAllergens.length === 0 && allergenEmptyMsg) {
            allergenEmptyMsg.style.display = 'block';
          }
        }
      });
    });

    document.addEventListener('DOMContentLoaded', function() {
      const btnAdd = document.getElementById('btn-add-ingredient');
      const selectIngredient = document.getElementById('select-ingredient');
      const ingredientList = document.getElementById('ingredient-list');
      const ingredientInputs = document.getElementById('ingredient-inputs');
      const ingredientErrorMsg = document.getElementById('ingredient-error');
      const ingredientEmptyMsg = document.querySelector('.ingredient-empty-msg');

      let selectedIngredients = [];

      function addIngredientToUi(id, text) {
        if (selectedIngredients.includes(id)) return;
        if (ingredientEmptyMsg) ingredientEmptyMsg.style.display = 'none';

        selectedIngredients.push(id);

        const li = document.createElement('li');
        li.className = 'list-group-item ingredient-item d-flex justify-content-between align-items-center';
        li.dataset.id = id;
        li.innerHTML = `
            <span>${text}</span>
            <button type="button" class="btn btn-sm text-danger btn-delete-ingredient">Hapus</button>
        `;
        ingredientList.appendChild(li);

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ingredients[]';
        input.value = id;
        input.id = `input-ingredient-${id}`;
        ingredientInputs.appendChild(input);
      }

      if (initialIngredientIds.length > 0) {
        initialIngredientIds.forEach(id => {
          const option = selectIngredient.querySelector(`option[value="${id}"]`);
          if (option) {
            addIngredientToUi(id.toString(), option.text);
          }
        });
      }

      btnAdd.addEventListener('click', function() {
        const id = selectIngredient.value;
        if (!id) return;

        if (selectedIngredients.includes(id)) {
          ingredientErrorMsg.classList.remove('d-none');
          setTimeout(() => ingredientErrorMsg.classList.add('d-none'), 2000);
          return;
        }

        const text = selectIngredient.options[selectIngredient.selectedIndex].text;
        addIngredientToUi(id, text);
        selectIngredient.value = "";
      });

      ingredientList.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-delete-ingredient')) {
          const li = e.target.closest('li');
          const id = li.dataset.id;

          selectedIngredients = selectedIngredients.filter(item => item !== id);

          const inputToRemove = document.getElementById(`input-ingredient-${id}`);
          if (inputToRemove) inputToRemove.remove();

          li.remove();

          if (selectedIngredients.length === 0 && ingredientEmptyMsg) {
            ingredientEmptyMsg.style.display = 'block';
          }
        }
      });
    });
  </script>
</div>
<?= $this->endSection() ?>