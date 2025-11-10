<?php

if (!function_exists('formatTanggalIndo')) {
  function formatTanggalIndo($tanggal = null)
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

    $hari = date('j', $timestamp);
    $bulan = $nama_bulan[(int)date('n', $timestamp)];
    $tahun = date('Y', $timestamp);

    return $hari . ' ' . $bulan . ' ' . $tahun;
  }
}
