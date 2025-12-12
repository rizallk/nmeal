// Nama cache unik
const CACHE_NAME = 'ci4-pwa-cache-v1';

const appShellFiles = [
  '/', // Halaman utama (hasil render CI4)
  '/manifest.json',
  '/offline.html', // Halaman fallback jika offline

  // JS Files
  '/assets/js/pages/food-pickup.js',
  '/assets/js/getFormattedDate.js',
  '/assets/js/offlineSync.js',
  // CSS Files
  '/assets/css/components/sidebar_menu.css',
  '/assets/css/components/topbar.css',
  '/assets/css/pages/landing.css',
  '/assets/css/pages/food_pickup.css',
  '/assets/css/pages/login.css',
  '/assets/css/main.css',
  // Key Images (Logo & gambar default)
  '/assets/images/logo-horizontal.png',
  '/assets/images/person-default.png',
  '/assets/images/logo.jpeg',
  // App icon
  '/icons/android-icon-192x192.png',
  '/icons/android-icon-72x72.png',
  // CDN
  'https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css',
  'https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js',
  'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css',
  'https://cdn.jsdelivr.net/npm/sweetalert2@11',
  'https://cdn.jsdelivr.net/npm/chart.js',
  'https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0',
];

// Event: Install
// Dipanggil saat Service Worker pertama kali diinstal
self.addEventListener('install', (event) => {
  console.log('[ServiceWorker] Install');
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      console.log('[ServiceWorker] Caching App Shell');
      return cache.addAll(appShellFiles);
    })
  );
});

// Event: Activate
// Dipanggil setelah instalasi berhasil. Berguna untuk membersihkan cache lama.
self.addEventListener('activate', (event) => {
  console.log('[ServiceWorker] Activate');
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
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

// Event: Fetch
// Dipanggil setiap kali ada permintaan jaringan (request) dari aplikasi
self.addEventListener('fetch', (event) => {
  const url = new URL(event.request.url);

  if (
    url.pathname.includes('browser-sync') ||
    url.pathname.includes('socket.io')
  ) {
    if (!navigator.onLine) {
      event.respondWith(new Response('', { status: 200, statusText: 'OK' }));
    }
    return;
  }

  if (event.request.method !== 'GET') {
    return;
  }

  if (!event.request.url.startsWith('http')) {
    return;
  }

  event.respondWith(
    fetch(event.request)
      .then(async (networkResponse) => {
        return caches.open(CACHE_NAME).then((cache) => {
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
      .catch(async () => {
        return caches.match(event.request).then((cachedResponse) => {
          if (cachedResponse) return cachedResponse;
          return caches.match('/offline.html');
        });
      })
  );
});

// Event: Push Notification
self.addEventListener('push', function (event) {
  if (!(self.Notification && self.Notification.permission === 'granted')) {
    return;
  }

  let title = 'Info Baru';
  let body = 'Ada notifikasi masuk';
  let targetUrl = '/';

  try {
    const data = event.data ? event.data.json() : {};
    title = data.title;
    body = data.body;
    targetUrl = data.url;
  } catch (e) {
    body = event.data.text();
  }

  const options = {
    body: body,
    icon: '/icons/android-icon-192x192.png',
    data: {
      url: targetUrl,
    },
  };

  event.waitUntil(self.registration.showNotification(title, options));
});

// Event listener saat notifikasi di-klik
self.addEventListener('notificationclick', function (event) {
  event.notification.close();
  event.waitUntil(
    clients.matchAll({ type: 'window' }).then((windowClients) => {
      // Jika tab sudah terbuka, fokuskan
      for (let i = 0; i < windowClients.length; i++) {
        let client = windowClients[i];
        if (client.url === event.notification.data.url && 'focus' in client) {
          return client.focus();
        }
      }
      // Jika belum, buka tab baru
      if (clients.openWindow) {
        return clients.openWindow(event.notification.data.url);
      }
    })
  );
});
