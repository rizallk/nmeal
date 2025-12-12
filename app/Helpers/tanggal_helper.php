<?php

if (!function_exists('formatTanggalIndo')) {
  function formatTanggalIndo($tanggal = null, $singkat = false)
  {

    $timestamp = ($tanggal === null) ? time() : strtotime($tanggal);

    $nama_bulan = [
      1 => 'Januari',
      2 => 'Februari',
      3 => 'Maret',
      4 => 'April',
      5 => 'Mei',
      6 => 'Juni',
      7 => 'Juli',
      8 => 'Agustus',
      9 => 'September',
      10 => 'Oktober',
      11 => 'November',
      12 => 'Desember'
    ];

    $nama_bulan_singkat = [
      1 => 'Jan',
      2 => 'Feb',
      3 => 'Mar',
      4 => 'Apr',
      5 => 'Mei',
      6 => 'Jun',
      7 => 'Jul',
      8 => 'Agu',
      9 => 'Sep',
      10 => 'Okt',
      11 => 'Nov',
      12 => 'Des'
    ];

    $hari = date('j', $timestamp);
    $bulanIndex = (int)date('n', $timestamp);
    $tahun = date('Y', $timestamp);

    $bulan = ($singkat) ? $nama_bulan_singkat[$bulanIndex] : $nama_bulan[$bulanIndex];

    return $hari . ' ' . $bulan . ' ' . $tahun;
  }
}
