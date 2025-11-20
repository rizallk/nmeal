<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Laporan Pengambilan Makanan</title>
  <style>
    body {
      font-family: sans-serif;
      font-size: 12px;
    }

    .header {
      text-align: center;
      margin-bottom: 20px;
    }

    .header h2 {
      margin: 0;
    }

    .header p {
      margin: 5px 0;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    table,
    th,
    td {
      border: 1px solid #333;
    }

    th,
    td {
      padding: 8px;
      text-align: left;
    }

    th {
      background-color: #f2f2f2;
      text-align: center;
    }

    .text-center {
      text-align: center;
    }

    .badge {
      padding: 3px 6px;
      border-radius: 4px;
      font-size: 10px;
      font-weight: bold;
    }

    .bg-success {
      color: green;
    }

    .bg-danger {
      color: red;
    }
  </style>
</head>

<body>

  <div class="header">
    <h2>Laporan Pengambilan Makanan</h2>
    <p>Kelas: <?= $kelas ?></p>
    <p>Tanggal: <?= date('d-m-Y', strtotime($tanggal)) ?></p>
  </div>

  <table>
    <thead>
      <tr>
        <th style="width: 5%;">No</th>
        <th style="width: 30%;">Nama Siswa</th>
        <th style="width: 10%;">Status</th>
        <th style="width: 35%;">Catatan</th>
        <th style="width: 20%;">Operator</th>
      </tr>
    </thead>
    <tbody>
      <?php $no = 1;
      foreach ($data as $row): ?>
        <tr>
          <td class="text-center"><?= $no++ ?></td>
          <td><?= esc($row['nama_siswa']) ?></td>
          <td class="text-center">
            <?php if ($row['status'] == 1): ?>
              <span class="badge bg-success">SUDAH</span>
            <?php else: ?>
              <span class="badge bg-danger">BELUM</span>
            <?php endif; ?>
          </td>
          <td><?= esc($row['catatan']) ?></td>
          <td><?= esc($row['nama_operator']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div style="margin-top: 30px; text-align: right;">
    <p>Dicetak pada: <?= date('d-m-Y H:i:s') ?></p>
  </div>

</body>

</html>