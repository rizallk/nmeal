function initFoodPickupPage(config) {
  const { csrfTokenName, operatorName, getAllergensUrlPath } = config;

  const detailModalEl = document.getElementById('detailModal');
  const detailModal = detailModalEl ? new bootstrap.Modal(detailModalEl) : null;
  const btnSet = document.getElementById('btnSet');
  const btnFinalSubmit = document.getElementById('btnFinalSubmit');

  let currentStudentAllergens = [];
  const allergenWarning = document.getElementById('allergenWarning');
  const conflictAllergensSpan = document.getElementById('conflictAllergens');
  const modalFoodSelect = document.getElementById('modalFoodId');

  function updateStatusUI(studentId, isChecked) {
    const badge = document.getElementById('status_badge_' + studentId);
    if (badge) {
      if (isChecked) {
        badge.classList.remove('text-bg-danger');
        badge.classList.add('text-bg-success');
        badge.textContent = 'Sudah';
      } else {
        badge.classList.remove('text-bg-success');
        badge.classList.add('text-bg-danger');
        badge.textContent = 'Belum';
      }
    }
  }

  function checkAllergyConflict() {
    if (!allergenWarning || !conflictAllergensSpan || !modalFoodSelect) return;

    allergenWarning.classList.add('d-none');
    conflictAllergensSpan.textContent = '';

    const selectedOption =
      modalFoodSelect.options[modalFoodSelect.selectedIndex];
    const foodAllergensRaw = selectedOption
      ? selectedOption.getAttribute('data-allergens')
      : '';

    if (!foodAllergensRaw || currentStudentAllergens.length === 0) return;

    const foodAllergens = foodAllergensRaw
      .split(',')
      .map((s) => s.trim().toLowerCase());

    const conflicts = foodAllergens.filter((allergen) =>
      currentStudentAllergens.includes(allergen)
    );

    if (conflicts.length > 0) {
      const formattedConflicts = conflicts
        .map((s) => s.charAt(0).toUpperCase() + s.slice(1))
        .join(', ');
      conflictAllergensSpan.textContent = formattedConflicts;
      allergenWarning.classList.remove('d-none');
    }
  }

  if (modalFoodSelect) {
    modalFoodSelect.addEventListener('change', checkAllergyConflict);
  }

  async function openModalFromRow(tr) {
    const id = tr.dataset.studentId;
    const name = tr.dataset.studentName;
    const currentFoodId = document.getElementById('input_food_id_' + id).value;
    const currentNote = document.getElementById('input_catatan_' + id).value;

    currentStudentAllergens = [];
    if (allergenWarning) allergenWarning.classList.add('d-none');

    document.getElementById('modalStudentId').value = id;
    document.getElementById('modalStudentName').textContent = name;
    document.getElementById('modalStudentNote').value = currentNote;

    const modalFoodSelect = document.getElementById('modalFoodId');
    if (modalFoodSelect) modalFoodSelect.value = currentFoodId;

    if (detailModal) {
      const alergenList = document.querySelector('.modal .alergen-list');

      if (alergenList) {
        alergenList.innerHTML = `
            <div class="d-flex justify-content-center align-items-center my-2">
               <div class="spinner-border spinner-border-sm me-2" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
                Loading
            </div>
        `;

        try {
          const response = await fetch(`${getAllergensUrlPath}/${id}`);

          if (!response.ok) throw new Error('Network response was not ok');

          const data = await response.json();

          alergenList.innerHTML = '';

          if (data && data.length > 0) {
            currentStudentAllergens = data.map((item) =>
              item.name.toLowerCase()
            );
            data.forEach((item) => {
              const li = document.createElement('li');
              li.innerHTML = item.name;
              alergenList.appendChild(li);
            });
          } else {
            alergenList.innerHTML = `
                <div class="d-flex justify-content-center align-items-center my-2">
                  Tidak ada data alergi.
                </div>
            `;
          }

          checkAllergyConflict();
        } catch (error) {
          console.error('Error fetching allergens:', error);
          alergenList.innerHTML = `
            <div class="d-flex justify-content-center align-items-center my-2">
              Gagal mengambil data alergi.
            </div>
          `;
        }
      } else {
        console.error('Element list alergen tidak ditemukan di dalam modal.');
      }

      detailModal.show();
    }
  }

  const checkboxes = document.querySelectorAll('.status-checkbox');
  checkboxes.forEach(function (checkbox) {
    checkbox.addEventListener('click', function (e) {
      const tr = this.closest('tr');
      if (this.checked) {
        e.preventDefault();
        openModalFromRow(tr);
      }
    });
  });

  if (detailModalEl) {
    detailModalEl.addEventListener('show.bs.modal', function (event) {
      const triggerEl = event.relatedTarget;
      if (triggerEl && !triggerEl.classList.contains('status-checkbox')) {
        const tr = triggerEl.closest('tr');
        if (tr) {
          openModalFromRow(tr);
        }
      }
    });
  }

  if (btnSet) {
    btnSet.addEventListener('click', function () {
      const foodSelect = document.getElementById('modalFoodId');
      const foodIdVal = foodSelect.value;
      const id = document.getElementById('modalStudentId').value;
      const noteVal = document.getElementById('modalStudentNote').value;
      const foodNameText = foodSelect.options[foodSelect.selectedIndex].text;

      if (!foodIdVal) {
        Swal.fire({
          title: 'Peringatan!',
          text: 'Harap pilih Menu Makanan!',
          icon: 'warning',
        });
        return;
      }

      document.getElementById('input_catatan_' + id).value = noteVal;
      document.getElementById('input_food_id_' + id).value = foodIdVal;
      document.getElementById('display_catatan_' + id).innerHTML = noteVal;
      document.getElementById('display_food_name_' + id).innerHTML =
        foodNameText;

      document.getElementById('display_operator_' + id).textContent =
        operatorName;

      const targetRow = document.querySelector(`tr[data-student-id="${id}"]`);
      if (targetRow) {
        const checkbox = targetRow.querySelector('.status-checkbox');
        if (checkbox) {
          checkbox.checked = true;
          updateStatusUI(id, true);
        }
      }
      detailModal.hide();
    });
  }

  // ==========================================
  // 2. LOGIKA PWA & OFFLINE SYNC
  // ==========================================
  async function applyOfflineChanges() {
    try {
      const db = await openDB();
      const tx = db.transaction(STORE_NAME, 'readonly');
      const store = tx.objectStore(STORE_NAME);
      const request = store.getAll();

      request.onsuccess = function () {
        const items = request.result;

        if (items.length === 0) return;

        items.forEach((item) => {
          if (item.url.includes('/save')) {
            const data = item.body;

            if (data.student_ids && Array.isArray(data.student_ids)) {
              data.student_ids.forEach((studentId) => {
                const row = document.querySelector(
                  `tr[data-student-id="${studentId}"]`
                );
                if (row) {
                  const checkbox = row.querySelector('.status-checkbox');
                  if (checkbox) checkbox.checked = true;

                  const badge = document.getElementById(
                    'status_badge_' + studentId
                  );
                  if (badge) {
                    const isAlreadyDone =
                      badge.classList.contains('text-bg-success');

                    if (!isAlreadyDone) {
                      badge.className = 'badge rounded-pill text-bg-warning';
                      badge.innerHTML = 'Sudah';
                    }

                    const operatorEl = document.getElementById(
                      'display_operator_' + studentId
                    );
                    if (operatorEl) {
                      operatorEl.textContent = operatorName;
                    }
                  }
                }
              });
            }

            if (data.catatan) {
              Object.keys(data.catatan).forEach((studentId) => {
                const noteVal = data.catatan[studentId];
                const displayNote = document.getElementById(
                  'display_catatan_' + studentId
                );
                const inputNote = document.getElementById(
                  'input_catatan_' + studentId
                );

                if (displayNote) displayNote.innerText = noteVal;
                if (inputNote) inputNote.value = noteVal;
              });
            }

            if (data.food_ids) {
              Object.keys(data.food_ids).forEach((studentId) => {
                const foodId = data.food_ids[studentId];
                const inputFood = document.getElementById(
                  'input_food_id_' + studentId
                );
                const displayFood = document.getElementById(
                  'display_food_name_' + studentId
                );

                if (inputFood) inputFood.value = foodId;

                const selectOption = document.querySelector(
                  `#modalFoodId option[value="${foodId}"]`
                );
                if (displayFood && selectOption) {
                  displayFood.innerText = selectOption.text;
                }
              });
            }
          }
        });

        const pendingCount = items.length;
        if (pendingCount > 0) {
          const infoAlert = document.querySelector('.alert-info');
          if (infoAlert) {
            infoAlert.classList.remove('alert-info');
            infoAlert.classList.add('alert-warning');
            infoAlert.innerHTML += `<br><b><i class="bi bi-wifi-off"></i> Mode Offline : </b> Menampilkan perubahan yang belum disinkronkan ke server.`;
          }
        }
      };
    } catch (e) {
      console.error('Gagal memuat data offline ke UI:', e);
    }
  }
  applyOfflineChanges();

  window.addEventListener('online', () => {
    console.log('Online detected');
    if (typeof syncOfflineData === 'function') {
      syncOfflineData(csrfTokenName);
    }
  });

  // Handle Submit Form
  const formElement = document.getElementById('formFoodPickup');
  if (formElement) {
    formElement.addEventListener('submit', async function (e) {
      e.preventDefault();
      if (btnFinalSubmit) btnFinalSubmit.disabled = true;

      const formData = new FormData(formElement);

      const checkedStudentIds = formData.getAll('student_ids[]');

      const objectData = {
        tanggal: formData.get('tanggal'),
        kelas: formData.get('kelas'),
        student_ids: checkedStudentIds,
        catatan: {},
        food_ids: {},
      };

      document.querySelectorAll('input[name^="catatan["]').forEach((input) => {
        const idMatch = input.name.match(/\[(.*?)\]/);
        if (idMatch) {
          const id = idMatch[1];
          if (checkedStudentIds.includes(id)) {
            objectData.catatan[id] = input.value;
          }
        }
      });
      document.querySelectorAll('input[name^="food_ids["]').forEach((input) => {
        const idMatch = input.name.match(/\[(.*?)\]/);
        if (idMatch) {
          const id = idMatch[1];
          if (checkedStudentIds.includes(id)) {
            objectData.food_ids[id] = input.value;
          }
        }
      });

      const actionUrl = formElement.action;

      // Logika Simpan (Online/Offline)
      if (navigator.onLine) {
        try {
          Swal.fire({
            title: 'Menyimpan...',
            didOpen: () => Swal.showLoading(),
          });

          const result = await sendData(actionUrl, objectData, csrfTokenName);

          if (result.status === 'success') {
            Swal.fire('Berhasil!', result.message, 'success').then(() => {
              location.reload();
            });
            setTimeout(() => location.reload(), 2000);
          } else {
            Swal.fire('Gagal!', result.message, 'error');
          }
        } catch (error) {
          console.log('Fetch error, saving offline...');
          handleOffline(actionUrl, objectData);
        } finally {
          btnFinalSubmit.disabled = false;
        }
      } else {
        handleOffline(actionUrl, objectData);
        btnFinalSubmit.disabled = false;
      }
    });
  }

  async function handleOffline(url, payloadData) {
    try {
      const db = await openDB();

      const tx = db.transaction(STORE_NAME, 'readwrite');
      const store = tx.objectStore(STORE_NAME);

      const request = store.getAll();

      request.onsuccess = async function () {
        const items = request.result;

        const deletePromises = items.map((item) => {
          if (item.url === url) {
            return new Promise((resolve, reject) => {
              const delReq = store.delete(item.id);
              delReq.onsuccess = resolve;
              delReq.onerror = reject;
            });
          }
          return Promise.resolve();
        });

        await Promise.all(deletePromises);

        await new Promise((resolve, reject) => {
          const addReq = store.add({
            url: url,
            body: payloadData,
            timestamp: new Date().getTime(),
          });
          addReq.onsuccess = resolve;
          addReq.onerror = reject;
        });

        Swal.fire({
          title: 'Offline Mode',
          text: 'Data disimpan di perangkat dan akan dikirim saat online.',
          icon: 'info',
        }).then(() => {
          location.reload();
        });
        setTimeout(() => location.reload(), 2000);

        resetBadgesToDefault();
        applyOfflineChanges();
      };
    } catch (err) {
      console.error(err);
      Swal.fire('Error', 'Gagal update data offline.', 'error');
    }
  }

  function resetBadgesToDefault() {
    const badges = document.querySelectorAll('[id^="status_badge_"]');
    badges.forEach((badge) => {
      if (badge.classList.contains('text-bg-warning')) {
        badge.classList.remove('text-bg-warning');

        badge.classList.add('text-bg-danger');
        badge.innerHTML = 'Belum';

        const studentId = badge.id.replace('status_badge_', '');
        const row = document.querySelector(
          `tr[data-student-id="${studentId}"]`
        );
        if (row) {
          const cb = row.querySelector('.status-checkbox');
          if (cb) cb.checked = false;
        }
      }
    });
  }

  if (navigator.onLine && typeof syncOfflineData === 'function') {
    syncOfflineData(csrfTokenName);
  }
}
