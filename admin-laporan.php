<?php
session_start();
include "controller/koneksi.php";

if(empty($_SESSION['user'])){
    header("location:admin-login.php?pesan=belum_login");
    exit;
}

$user = $_SESSION['user'];
$username = $user['username'];
$role = $user['role'];

// Update status jika ada POST
if (isset($_POST['ubah_status'])) { 
  $id_pesanan = $_POST['id_pesanan'];
  $status_baru = $_POST['status_pesanan'];
  mysqli_query($koneksi, "UPDATE pesanan SET status_pesanan = '$status_baru' WHERE ID_Pesanan = $id_pesanan");
}

$tanggal_filter = $_GET['tanggal'] ?? date('Y-m-d');

// Query data pesanan sesuai tanggal
$query = mysqli_query($koneksi, "
  SELECT 
    p.ID_Pesanan,
    p.Tanggal,
    pel.Nama,
    p.status_pesanan,
    GROUP_CONCAT(m.Nama_menu SEPARATOR ', ') AS DaftarMenu,
    SUM(dp.Jumlah) AS TotalJumlah,
    SUM(dp.Subtotal) AS TotalSubtotal
  FROM pesanan p
  JOIN pelanggan pel ON p.ID_Pelanggan = pel.ID_Pelanggan
  JOIN detail_pesanan dp ON p.ID_Pesanan = dp.ID_Pesanan
  JOIN menu m ON dp.ID_Menu = m.ID_Menu
  WHERE DATE(p.Tanggal) = '$tanggal_filter' AND p.status_pesanan = 'Selesai'
  GROUP BY p.ID_Pesanan
  ORDER BY p.Tanggal DESC
");

// Total Pendapatan
$q_total = mysqli_query($koneksi, "SELECT SUM(dp.Subtotal) as total FROM pesanan p
JOIN detail_pesanan dp ON p.ID_Pesanan = dp.ID_Pesanan
WHERE DATE(p.Tanggal) = '$tanggal_filter' AND p.status_pesanan = 'Selesai'");
$total_pendapatan = mysqli_fetch_assoc($q_total)['total'] ?? 0;

// Total Transaksi
$q_transaksi = mysqli_query($koneksi, "SELECT COUNT(DISTINCT p.ID_Pesanan) as total FROM pesanan p
WHERE DATE(p.Tanggal) = '$tanggal_filter' AND p.status_pesanan = 'Selesai'");
$total_transaksi = mysqli_fetch_assoc($q_transaksi)['total'] ?? 0;

// Produk Terjual
$q_produk = mysqli_query($koneksi, "SELECT SUM(dp.Jumlah) as total FROM pesanan p
JOIN detail_pesanan dp ON p.ID_Pesanan = dp.ID_Pesanan
WHERE DATE(p.Tanggal) = '$tanggal_filter' AND p.status_pesanan = 'Selesai'");
$total_produk = mysqli_fetch_assoc($q_produk)['total'] ?? 0;

?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Laporan Penjualan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      background-color: #f5f5f5;
      font-family: system-ui, sans-serif;
    }
    .header {
      background-color: #252c3a;
      color: white;
      height: 110px;
      font-size: 1rem;
      display: flex;
      align-items: center;
    }
    .laporan-container {
      padding: 1.2rem;
    }
    .top-bar {
      margin-top: 10px;
      margin-bottom: 20px;
    }

    .date-control {
      background-color: #1e2a38;
      color: white;
      border-radius: 6px;
      padding: 0.4rem 0.6rem;
      display: flex;
      align-items: center;
      font-size: 0.85rem;
      gap: 0.4rem;
    }
    .date-control input[type="date"] {
      background: transparent;
      border: none;
      color: white;
      width: 120px;
      font-size: 0.85rem;
      -webkit-calendar-picker-indicator {
        filter: invert(1); /* membuat ikon kalender menjadi putih di Webkit (Chrome/Safari) */
      }
    }
    .date-control input:focus {
      outline: none;
    }
    .icon-button {
      background-color: #1e2a38;
      color: white;
      border: none;
      padding: 0.4rem 0.55rem;
      border-radius: 6px;
      font-size: 0.85rem;
      font-weight: bold;
    }
    .laporan-box {
      background-color: white;
      border-radius: 12px;
      text-align: center;
      padding: 1.2rem;
      box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }
    .laporan-box .text-muted {
      font-size: 0.9rem;
    }
    .laporan-box strong {
      font-size: 1.25rem;
      display: block;
      margin-top: 0.4rem;
    }
    input[type="date"]::-webkit-calendar-picker-indicator {
      filter: invert(1);         /* Membuat ikon jadi putih */
      cursor: pointer;    
    }

    @media (max-width: 430px) {
      .top-bar {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.6rem;
      }
    }

    .table-container {
      background: white;
      border-radius: 12px;
      padding: 8px;
      margin: 0 16px;
      box-shadow: 0 0 5px rgba(0,0,0,0.05);
      overflow: visible; /* Tampilkan semuanya */
    }

    .table {
      margin: 20px 0;
      border-collapse: collapse; /* Menghilangkan garis vertikal antara sel */
      font-family: Arial, sans-serif;
      background-color: #fff;
      border-radius: 10px; /* Lengkungkan sudut tabel */
      width: 100%;
      overflow: hidden; /* Agar border-radius terlihat */
      font-size: 12px;
    }

    .table th, .table td {
      padding: 12px 16px; /* Memperlebar padding antar kolom */
      text-align: center;
      font-size: 12px; /* Ukuran font tetap kecil agar tabel tidak terlalu besar */
      background-color: #ffffff;
      vertical-align: middle; 
      white-space: normal;      /* Izinkan teks turun ke baris berikutnya */
      word-wrap: break-word;    /* Paksa kata panjang agar pecah */
      max-width: 200px;  
    }

    /* Menghilangkan border vertikal dan border pada th */
    .table th, .table td {
      border: none;
    }

    .table th {
      font-weight: bold;
      color: #000;
    }

    /* Styling select di dalam tabel */
    .table td select {
      width: 100%;
      border: 1px solid #ced4da;
      border-radius: 10px;
      padding: 4px 8px; /* Mengurangi padding pada select */
      font-weight: 600;
      font-size: 12px; /* Memperkecil font di select */
      cursor: pointer;
      appearance: none;
      -webkit-appearance: none;
      -moz-appearance: none;
    }

    .table td select::-ms-expand {
      display: none;
    }

    table tr td {
      min-width: unset;
      word-break: break-word;
    }


    /* Efek hover pada baris */
    .table tbody tr:hover {
      background-color: #f1f1f1;
    }

  </style>
</head>
<body>

  <div class="header d-flex align-items-center px-3">
        <div class="container py-4">
            <a href="controller/logout.php" class="fs-5 me-2 text-white text-decoration-none">Tutup</a>
        </div>
     </div>

  <div class="container laporan-container">
    <div class="top-bar">
      <div class="d-flex align-items-center justify-content-between gap-2">
        <div class="date-control">
          <input type="date" id="tanggalInput" onchange="filterTanggal()" value="<?= $tanggal_filter ?>">
        </div>
        <div>
          <a class="icon-button" href="controller/laporanexport.php?= $tanggal_filter ?>" target="_blank">
            <i class="bi bi-download"></i>
          </a>
        </div>
      </div>
    </div>

    <div class="row g-3">
      <div class="col-12">
        <div class="laporan-box">
          <div class="text-muted">Total Pendapatan</div>
          <strong>Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></strong>
        </div>
      </div>
      <div class="col-6">
        <div class="laporan-box">
          <div class="text-muted">Total Transaksi</div>
          <strong><?= $total_transaksi ?></strong>
        </div>
      </div>
      <div class="col-6">
        <div class="laporan-box">
          <div class="text-muted">Produk Terjual</div>
          <strong><?= $total_produk ?></strong>
        </div>
      </div>
    </div>
  </div>

  <div class="table-container">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th style="color: grey;">NAMA</th>
          <th style="color: grey;">MENU</th>
          <th style="color: grey;">JUMLAH</th>
          <th style="color: grey;">TOTAL HARGA</th>
          <?php if ($role == 'Owner' || $role == 'Admin' || $role == 'Barista') echo "<th>Aksi</th>"; ?>
        </tr>
      </thead>
      <tbody>
        <?php if (mysqli_num_rows($query) > 0): ?>
          <?php while ($row = mysqli_fetch_assoc($query)) : ?>
            <tr>
              <td><?= $row['Nama'] ?></td>
              <td><?= $row['DaftarMenu'] ?></td>
              <td><?= $row['TotalJumlah'] ?></td>
              <td>Rp <?= number_format($row['TotalSubtotal'], 0, ',', '.') ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="5">Tidak ada laporan penjualan untuk tanggal ini.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="bottom-nav">
        <a href="index.php">
          <i class="bi bi-house"></i>
          <span>Utama</span>
        </a>
        <a href="admin-kategori.php">
          <i class="bi bi-grid"></i>
          <span>Kategori</span>
        </a>
        <a href="admin-order.php">
          <i class="bi bi-arrow-repeat"></i>
          <span>Order</span>
        </a>
        <a href="admin-laporan.php" class="active">
          <i class="bi bi-file-earmark-text"></i>
          <span>Laporan</span>
        </a>
        <a href="admin-user.php">
          <i class="bi bi-people"></i>
          <span>User</span>
        </a>
  </div>

  <script>
    // Jangan timpa jika sudah ada value dari PHP (artinya user memilih tanggal)
    const tanggalInput = document.getElementById('tanggalInput');
    if (!tanggalInput.value) {
      const today = new Date().toISOString().split('T')[0];
      tanggalInput.value = today;
    }


    function filterTanggal() {
      const tgl = document.getElementById('tanggalInput').value;
      window.location.href = '?tanggal=' + tgl;
    }
  </script>

</body>
</html>
