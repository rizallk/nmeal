const notificationButton = document.getElementById('enable-notifications-btn');

if (notificationButton) {
  notificationButton.addEventListener('click', () => {
    Notification.requestPermission().then((permission) => {
      if (permission === 'granted') {
        console.log('Izin notifikasi diberikan!');
        // Sembunyikan tombol setelah izin diberikan
        notificationButton.style.display = 'none';
      } else {
        console.log('Izin notifikasi ditolak.');
      }
    });
  });

  // Sembunyikan tombol jika izin sudah diberikan
  if (Notification.permission === 'granted') {
    notificationButton.style.display = 'none';
  }
}

function showLocalNotification(title, body, icon, url = '/') {
  // Cek apakah browser mendukung Service Worker
  if (!('serviceWorker' in navigator)) {
    console.warn('Service Worker tidak didukung browser ini.');
    return;
  }

  // Cek apakah izin sudah diberikan
  if (Notification.permission === 'granted') {
    // Dapatkan registrasi Service Worker yang aktif
    navigator.serviceWorker.ready.then(function (registration) {
      // Tampilkan notifikasi!
      registration.showNotification(title, {
        body: body,
        icon: icon || '/images/icons/icon-192x192.png',
        badge: '/images/icons/android-icon-72x72.png', // Ganti path badge Anda
        vibrate: [100, 50, 100], // Pola getar [getar, diam, getar]
        data: {
          url: url, // Data kustom (URL) yang akan dipakai di service-worker.js
        },
      });
    });
  } else {
    // Izin belum diberikan atau ditolak
    console.warn('Izin notifikasi belum diberikan atau ditolak.');
    // Anda bisa meminta izin lagi di sini, tapi sebaiknya
    // arahkan user ke tombol "Aktifkan Notifikasi"
  }
}
