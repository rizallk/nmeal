document.addEventListener('DOMContentLoaded', () => {
  checkSubscriptionState();
  initRecommendationUI();
});

// NOTIFIKASI (PUSH NOTIFICATION)
const btnNotif = document.getElementById('btnNotif');
const textNotif = document.getElementById('textNotif');
const iconNotif = document.getElementById('iconNotif');
const spinnerNotif = document.getElementById('spinnerNotif');

// Fungsi Helper: Konversi VAPID Key
function urlBase64ToUint8Array(base64String) {
  const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
  const base64 = (base64String + padding)
    .replace(/\-/g, '+')
    .replace(/_/g, '/');
  const rawData = window.atob(base64);
  const outputArray = new Uint8Array(rawData.length);
  for (let i = 0; i < rawData.length; ++i) {
    outputArray[i] = rawData.charCodeAt(i);
  }
  return outputArray;
}

// Cek status saat load page
async function checkSubscriptionState() {
  if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
    if (btnNotif) btnNotif.style.display = 'none';
    return;
  }

  try {
    const registration = await navigator.serviceWorker.ready;
    const subscription = await registration.pushManager.getSubscription();

    if (subscription) {
      // Jika sudah subscribe di browser
      if (Notification.permission === 'granted') {
        updateBtnUI('active');
      }
    } else {
      // Belum subscribe
      if (Notification.permission === 'denied') {
        updateBtnUI('denied');
      } else {
        updateBtnUI('inactive');
      }
    }
  } catch (e) {
    console.error('Service Worker belum siap:', e);
  }
}

// Update Tampilan Tombol
function updateBtnUI(state) {
  if (!btnNotif) return;

  spinnerNotif.classList.add('d-none');
  iconNotif.classList.remove('d-none');

  if (state === 'active') {
    btnNotif.className =
      'btn btn-success w-100 rounded d-flex align-items-center justify-content-center gap-2';
    textNotif.textContent = 'Notifikasi Aktif';
    iconNotif.className = 'bi bi-bell-fill';
    btnNotif.disabled = true;
  } else if (state === 'denied') {
    btnNotif.className =
      'btn btn-danger w-100 rounded d-flex align-items-center justify-content-center gap-2';
    textNotif.textContent = 'Izin Ditolak';
    iconNotif.className = 'bi bi-bell-slash-fill';
    btnNotif.disabled = false;
  } else if (state === 'loading') {
    btnNotif.className =
      'btn btn-outline-primary w-100 rounded d-flex align-items-center justify-content-center gap-2';
    textNotif.textContent = 'Memproses...';
    spinnerNotif.classList.remove('d-none');
    iconNotif.classList.add('d-none');
    btnNotif.disabled = true;
  } else {
    btnNotif.className =
      'btn btn-outline-primary w-100 rounded d-flex align-items-center justify-content-center gap-2';
    textNotif.textContent = 'Aktifkan Notifikasi';
    iconNotif.className = 'bi bi-bell';
    btnNotif.disabled = false;
  }
}

// Handler Klik Tombol
async function handleNotificationClick() {
  if (!('Notification' in window)) return;

  if (Notification.permission === 'denied') {
    Swal.fire({
      icon: 'warning',
      title: 'Izin Ditolak',
      text: 'Anda memblokir notifikasi. Silakan ubah izin secara manual melalui pengaturan browser (ikon gembok di URL bar).',
    });
    return;
  }

  if (Notification.permission === 'granted') {
    checkSubscriptionState();
    return;
  }

  // Mulai Proses Subscribe
  updateBtnUI('loading');

  try {
    const permission = await Notification.requestPermission();

    if (permission === 'granted') {
      await subscribeUserToPush();
    } else {
      updateBtnUI('denied');
    }
  } catch (error) {
    console.error(error);
    Swal.fire('Error', 'Gagal mengaktifkan notifikasi.', 'error');
    updateBtnUI('inactive');
  }
}

// Logika Inti Subscribe ke Server
async function subscribeUserToPush() {
  try {
    const keyResponse = await fetch(dashboardOrtuConfig.publicKeyUrl);
    const keyData = await keyResponse.json();
    const publicKey = keyData.publicKey;

    if (!publicKey) throw new Error('VAPID Public Key tidak ditemukan.');

    const registration = await navigator.serviceWorker.ready;
    const subscription = await registration.pushManager.subscribe({
      userVisibleOnly: true,
      applicationServerKey: urlBase64ToUint8Array(publicKey),
    });

    const saveResponse = await fetch(dashboardOrtuConfig.subscribeUrl, {
      method: 'POST',
      body: JSON.stringify(subscription),
      headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      },
    });

    const saveData = await saveResponse.json();

    if (saveData.status === 'success') {
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Notifikasi berhasil diaktifkan. Anda akan menerima update ketika si kecil menerima makanan.',
        timer: 5000,
      });
      updateBtnUI('active');
    } else {
      throw new Error('Gagal menyimpan ke database.');
    }
  } catch (err) {
    console.error('Failed to subscribe:', err);
    Swal.fire(
      'Gagal',
      'Terjadi kesalahan saat koneksi ke server notifikasi.',
      'error'
    );
    updateBtnUI('inactive');
  }
}

// REKOMENDASI AI
const tipsHeader = document.getElementById('tipsHeader');
const tipsContent = document.getElementById('tipsContent');
const statusVal = document.getElementById('status')
  ? document.getElementById('status').value
  : '';
const catatanVal = document.getElementById('catatan')
  ? document.getElementById('catatan').value
  : '';

function initRecommendationUI() {
  const tipsContentSaved = localStorage.getItem('tips')
    ? JSON.parse(localStorage.getItem('tips'))
    : null;

  // Reset jika status/catatan berubah dari hari sebelumnya
  if (
    tipsContentSaved?.status !== statusVal ||
    tipsContentSaved?.catatan !== catatanVal
  ) {
    localStorage.removeItem('tips');
  }

  // Cek apakah data di localStorage masih relevan hari ini
  if (
    typeof getFormattedDate === 'function' &&
    getFormattedDate(new Date()) === tipsContentSaved?.date
  ) {
    renderTips(tipsContentSaved.content);
  } else {
    renderButton();
  }
}

function renderTips(content) {
  if (tipsHeader) {
    tipsHeader.innerHTML = `
        <div class="card bg-color-2 border-0" id="tipsHeader">
            <div class="card-body text-center">
                <h6 class="card-title mb-0 fw-bold">Ayo dukung si kecil dengan tips berikut!</h6>
            </div>
        </div>`;
  }
  if (tipsContent) {
    tipsContent.innerHTML = `
        <p class="fw-bold color mt-3">Tips Hari Ini</p>
        ${content}`;
  }
}

function renderButton() {
  if (tipsHeader) {
    tipsHeader.innerHTML = `
        <div class="card bg-color-2 border-0" id="btnRekomendasi" onclick="getRecommendation()" style="cursor: pointer">
            <div class="card-body text-center">
                <div class="d-flex justify-content-center align-items-center">
                    <div class="spinner-border spinner-border-sm d-none" role="status" id="btnLoading">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <h6 class="card-title mb-0 fw-bold ms-2" id="btnText">Minta Rekomendasi Makanan ke AI</h6>
                </div>
            </div>
        </div>`;
  }
}

async function getRecommendation() {
  const btn = document.getElementById('btnRekomendasi');
  const btnText = document.getElementById('btnText');
  const btnLoading = document.getElementById('btnLoading');

  if (btn) btn.classList.add('disabled');
  if (btnText) btnText.textContent = 'Sedang Menganalisis...';
  if (btnLoading) btnLoading.classList.remove('d-none');

  try {
    const response = await fetch(dashboardOrtuConfig.recommendationUrl, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
      },
    });

    const data = await response.json();

    if (data.success) {
      renderTips(data.message);

      // Simpan ke LocalStorage
      if (typeof getFormattedDate === 'function') {
        localStorage.setItem(
          'tips',
          JSON.stringify({
            status: statusVal,
            catatan: catatanVal,
            date: getFormattedDate(new Date()),
            content: data.message,
          })
        );
      }
    } else {
      if (tipsContent)
        tipsContent.innerHTML = `<div class="alert alert-danger">${
          data.message || 'Gagal memuat rekomendasi.'
        }</div>`;
    }
  } catch (error) {
    console.error(error);
    localStorage.removeItem('tips');
    alert('Terjadi kesalahan jaringan.');
  } finally {
    if (btn) btn.classList.remove('disabled');
    if (btnText) btnText.textContent = 'Lihat Rekomendasi';
    if (btnLoading) btnLoading.classList.add('d-none');
  }
}
