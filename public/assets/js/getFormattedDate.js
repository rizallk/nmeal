function getFormattedDate(date = new Date(), separator = '-') {
  const tahun = date.getFullYear();
  // Ingat: getMonth() mulai dari 0, jadi harus +1
  const bulan = String(date.getMonth() + 1).padStart(2, '0');
  const tanggal = String(date.getDate()).padStart(2, '0');

  return `${tahun}${separator}${bulan}${separator}${tanggal}`;
}
