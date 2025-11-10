// Nama cache Anda (buat unik)
const CACHE_NAME = 'ci4-pwa-cache-v1';
const SYNC_DB_NAME = 'pending-requests-db';
const SYNC_STORE_NAME = 'pending-requests-store';

// Aset "App Shell" yang ingin Anda cache saat instalasi
// Ini adalah file statis dari folder public/ Anda
const appShellFiles = [
  '/', // Halaman utama (hasil render CI4)
  '/manifest.json',
  '/offline.html', // Halaman fallback jika offline

  // CSS Files
  '/assets/css/components/sidebar_menu.css',
  '/assets/css/components/topbar.css',
  '/assets/css/pages/landing.css',
  '/assets/css/pages/login.css',
  '/assets/css/main.css',
  // Key Images (Logo & gambar default)
  '/assets/images/logo-horizontal.png',
  '/assets/images/person-default.png',
  '/assets/images/logo.jpeg',
  // App icon
  '/icons/android-icon-192x192.png',
  // CDN
  'https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css',
  'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css',
  'https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js',
];

// 1. Event: Install
// Dipanggil saat Service Worker pertama kali diinstal
self.addEventListener('install', (event) => {
  console.log('[ServiceWorker] Install');
  // Tunggu sampai semua aset app shell di-cache
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      console.log('[ServiceWorker] Caching App Shell');
      return cache.addAll(appShellFiles);
    })
  );
});

// 2. Event: Activate
// Dipanggil setelah instalasi berhasil. Berguna untuk membersihkan cache lama.
self.addEventListener('activate', (event) => {
  console.log('[ServiceWorker] Activate');
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          // Hapus cache lama jika namanya tidak sama dengan CACHE_NAME
          if (cacheName !== CACHE_NAME) {
            console.log('[ServiceWorker] Removing old cache', cacheName);
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
  return self.clients.claim();
});

// 3. Event: Fetch
// Dipanggil setiap kali ada permintaan jaringan (request) dari aplikasi Anda
// Ini adalah tempat kita mengimplementasikan strategi caching
self.addEventListener('fetch', (event) => {
  // Kita hanya ingin memproses request GET.
  // Request POST, PUT, DELETE, dll. biarkan langsung ke server.
  if (event.request.method !== 'GET') {
    return fetch(event.request);
  }

  // Hanya proses request http/https. Abaikan semua skema lain (spt chrome-extension://)
  // Ini akan memperbaiki error "Request scheme 'chrome-extension' is unsupported".
  if (!event.request.url.startsWith('http')) {
    // Biarkan browser menangani request ini secara normal tanpa caching.
    return fetch(event.request);
  }

  event.respondWith(
    // Strategi: Network falling back to Cache
    // 1. Coba ambil dari jaringan (Network) dulu
    fetch(event.request)
      .then((networkResponse) => {
        // Jika berhasil, simpan di cache dan kembalikan
        return caches.open(CACHE_NAME).then((cache) => {
          // Pastikan kita hanya meng-cache respons yang valid (status 200)
          // dan (meskipun sudah kita filter) kita pastikan lagi ini GET.
          // Ini juga mencegah caching respons error (seperti 404, 500).
          if (
            networkResponse &&
            networkResponse.status === 200 &&
            event.request.method === 'GET'
          ) {
            cache.put(event.request, networkResponse.clone());
          }
          return networkResponse;
        });
      })
      .catch(() => {
        // 2. Jika Jaringan Gagal (offline), coba ambil dari Cache
        return caches.match(event.request).then((cachedResponse) => {
          // Jika ada di cache, kembalikan dari cache
          if (cachedResponse) {
            return cachedResponse;
          }
          // Jika tidak ada di cache sama sekali, kembalikan halaman offline
          return caches.match('/offline.html');
        });
      })
  );
});

// Event listener untuk saat notifikasi di-klik
self.addEventListener('notificationclick', function (event) {
  console.log('[Service Worker] Notifikasi di-klik.');

  // 1. Tutup notifikasi yang di-klik
  event.notification.close();

  // 2. Ambil URL dari data yang kita kirim
  const targetUrl = event.notification.data.url || '/';

  // 3. Buka tab/window baru ke URL tersebut
  event.waitUntil(clients.openWindow(targetUrl));
});
