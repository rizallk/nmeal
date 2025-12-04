const DB_NAME = 'ci4-pwa-db';
const STORE_NAME = 'pending-requests';

// Buka Database
function openDB() {
  return new Promise((resolve, reject) => {
    const request = indexedDB.open(DB_NAME, 1);
    request.onupgradeneeded = (event) => {
      const db = event.target.result;
      if (!db.objectStoreNames.contains(STORE_NAME)) {
        db.createObjectStore(STORE_NAME, {
          keyPath: 'id',
          autoIncrement: true,
        });
      }
    };
    request.onsuccess = (event) => resolve(event.target.result);
    request.onerror = (event) => reject(event.target.error);
  });
}

// Simpan ke IndexedDB
async function saveToIndexedDB(url, payload) {
  const db = await openDB();
  const tx = db.transaction(STORE_NAME, 'readwrite');
  const store = tx.objectStore(STORE_NAME);

  store.add({
    url: url,
    body: payload,
    timestamp: new Date().getTime(),
  });
  return tx.oncomplete;
}

// Fungsi Kirim Data (Fetch Wrapper)
async function sendData(url, payload, csrfTokenName) {
  const csrfElement = document.querySelector(`input[name="${csrfTokenName}"]`);
  const currentCsrfHash = csrfElement ? csrfElement.value : '';

  const headers = {
    'Content-Type': 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
  };

  if (currentCsrfHash) {
    headers['X-CSRF-TOKEN'] = currentCsrfHash;
  }

  if (payload && typeof payload === 'object') {
    payload[csrfTokenName] = currentCsrfHash;
  }

  const response = await fetch(url, {
    method: 'POST',
    headers: headers,
    body: JSON.stringify(payload),
  });

  if (!response.ok) {
    const text = await response.text();
    throw new Error(
      `Server Error: ${response.status} ${
        response.statusText
      } - ${text.substring(0, 50)}...`
    );
  }

  return await response.json();
}

// Logic Sync saat Online
async function syncOfflineData(csrfTokenName) {
  const db = await openDB();
  const tx = db.transaction(STORE_NAME, 'readonly');
  const store = tx.objectStore(STORE_NAME);
  const request = store.getAll();

  request.onsuccess = async function () {
    const items = request.result;
    if (items.length === 0) return;

    // Toast Notification awal
    const Toast = Swal.mixin({
      toast: true,
      position: 'bottom-end',
      showConfirmButton: false,
      timer: 3000,
    });

    // Flag untuk mengecek apakah perlu reload
    let hasSuccess = false;
    let hasError = false;

    Toast.fire({ icon: 'info', title: 'Sinkronisasi data...' });

    for (const item of items) {
      try {
        // Kirim data
        const result = await sendData(item.url, item.body, csrfTokenName);

        if (result.status === 'success') {
          // Hapus jika sukses
          const deleteTx = db.transaction(STORE_NAME, 'readwrite');
          deleteTx.objectStore(STORE_NAME).delete(item.id);
          hasSuccess = true; // Tandai ada yang berhasil
          console.log(`Item ${item.id} berhasil disinkronkan.`);
        } else {
          console.error(
            `Gagal sync item ${item.id}: Server menolak (Status: ${result.status})`,
            result.message
          );
          hasError = true;
        }
      } catch (error) {
        console.error(`Gagal sync item ${item.id}: Network/Code Error`, error);
        hasError = true;
      }
    }

    if (hasSuccess) {
      Toast.fire({ icon: 'success', title: 'Sinkronisasi selesai!' });
      setTimeout(() => location.reload(), 1500);
    } else if (hasError) {
      Toast.fire({ icon: 'error', title: 'Gagal sinkronisasi sebagian data.' });
    }
  };
}
