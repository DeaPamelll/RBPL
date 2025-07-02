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

// Ambil semua data pesanan
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
  GROUP BY p.ID_Pesanan
  ORDER BY p.Tanggal DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Order Produk</title>
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      background-color: #f5f5f5;
      padding-bottom: 70px;
    }

    .header {
      background-color: #252c3a;
      color: white;
      height: 110px;
      font-size: 1rem;
      display: flex;
      align-items: center;
    }

    .search-section {
      display: flex;
      align-items: center;
      padding: 16px;
      gap: 10px;
    }

    .search-section input {
      border-radius: 10px;
    }

    .btn-search{
      background-color: #252c3a;
      border-radius: 100px;
      font-size: 12px;
      color: white;
    }
    /* Styling Tabel Container */
    .table-container {
      background: white;
      border-radius: 12px;
      padding: 8px;
      margin: 0 16px;
      box-shadow: 0 0 5px rgba(0,0,0,0.05);
      overflow-x: auto; /* Aktifkan scroll horizontal hanya di dalam container tabel */
      white-space: nowrap;
      -webkit-overflow-scrolling: touch; /* Menambahkan dukungan scroll halus untuk perangkat sentuh */
    }

    /* Styling Tabel */
    .table {
      margin: 20px 0;
      border-collapse: collapse; /* Menghilangkan garis vertikal antara sel */
      font-family: Arial, sans-serif;
      background-color: #fff;
      border-radius: 10px; /* Lengkungkan sudut tabel */
      table-layout: auto; /* Menyesuaikan lebar kolom secara otomatis */
      width: 100%;
      overflow: hidden; /* Agar border-radius terlihat */
    }

    .table th, .table td {
      padding: 12px 16px; /* Memperlebar padding antar kolom */
      text-align: center;
      font-size: 12px; /* Ukuran font tetap kecil agar tabel tidak terlalu besar */
      background-color: #fff;
      vertical-align: middle; /* Menjaga teks sejajar secara vertikal */
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

    /* Styling untuk status */
    .status-pastel {
      width: 100%;
      padding: 4px 8px; /* Mengurangi padding pada select */
      font-weight: 600;
      font-size: 12px; /* Memperkecil font di select */
      cursor: pointer;
    }

    .status-menunggu-pembayaran {
      background: #FFE29A;
      color: #000;
      font-size: 12px;
    }

    .status-menunggu {
      background: #FFF8CC;
      color: #000;
      font-size: 12px;
    }

    .status-diproses {
      background: #CDE7FF;
      color: #000;
      font-size: 12px;
    }

    .status-selesai {
      background: #B9EED0;
      color: #000;
      font-size: 12px;
    }

    .status-dibatalkan {
      background: #FFC9C9;
      color: #000;
      font-size: 12px;
    }

    select.status-pastel option {
      background: #fff !important;
      color: #000;
      font-size: 12px;
    }

    table tr td:nth-child(1) { min-width: 120px; } /* Tanggal */
    table tr td:nth-child(2) { min-width: 100px; } /* Nama */
    table tr td:nth-child(3) { min-width: 200px; } /* Menu */
    table tr td:nth-child(4) { min-width: 80px; } /* Jumlah */
    table tr td:nth-child(5) { min-width: 100px; } /* Total Harga */
    table tr td:nth-child(6) { min-width: 220px; } /* Status */

    /* Efek hover pada baris */
    .table tbody tr:hover {
      background-color: #f1f1f1;
    }

    /* Media query untuk layar kecil */
    @media (max-width: 768px) {
      .table-container {
        overflow-x: scroll;
        -webkit-overflow-scrolling: touch;
      }

      .table th, .table td {
        white-space: nowrap;
      }
    }
  </style>
</head>
<body>

  <div class="header d-flex align-items-center px-3">
        <div class="container py-4">
            <a href="controller/logout.php" class="fs-5 me-2 text-white text-decoration-none">Tutup</a>
        </div>
     </div>

  <!-- Search and Add -->
  <div class="search-section" style="margin-top: 20px;">
    <div class="input-group">
      <input type="text" class="form-control border-start-0" placeholder="Search">
    </div>
    <button class="btn btn-search"><i class="bi bi-search"></i></button>
  </div>

  <!-- Table -->
  <div class="table-container">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th style="color: grey;">TANGGAL</th>
          <th style="color: grey;">NAMA</th>
          <th style="color: grey;">MENU</th>
          <th style="color: grey;">JUMLAH</th>
          <th style="color: grey;">TOTAL HARGA</th>
          <th style="color: grey;">STATUS</th>
          <?php if ($role == 'Owner' || $role == 'Admin' || $role == 'Barista') echo "<th>Aksi</th>"; ?>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($query)) : ?>
          <tr>
            <td><?= date('d-m-Y H:i', strtotime($row['Tanggal'])) ?></td>
            <td><?= $row['Nama'] ?></td>
            <td><?= $row['DaftarMenu'] ?></td>
            <td><?= $row['TotalJumlah'] ?></td>
            <td>Rp <?= number_format($row['TotalSubtotal'], 0, ',', '.') ?></td>

            <?php
              $status = strtolower($row['status_pesanan']);
              $class = 'status-pastel ';
              switch ($status) {
                  case 'menunggu pembayaran': $class .= 'status-menunggu-pembayaran'; break;
                  case 'menunggu':            $class .= 'status-menunggu'; break;
                  case 'diproses':            $class .= 'status-diproses'; break;
                  case 'selesai':             $class .= 'status-selesai'; break;
                  case 'dibatalkan':          $class .= 'status-dibatalkan'; break;
              }
            ?>

            <td>
              <form method="POST">
                <input type="hidden" name="id_pesanan" value="<?= $row['ID_Pesanan'] ?>">
                <select name="status_pesanan" class="<?= $class ?>" onchange="this.form.submit()">
                  <option <?= ($row['status_pesanan'] == 'Menunggu Pembayaran') ? 'selected' : '' ?>>Menunggu Pembayaran</option>
                  <option <?= ($row['status_pesanan'] == 'Menunggu') ? 'selected' : '' ?>>Menunggu</option>
                  <option <?= ($row['status_pesanan'] == 'Diproses') ? 'selected' : '' ?>>Diproses</option>
                  <option <?= ($row['status_pesanan'] == 'Selesai') ? 'selected' : '' ?>>Selesai</option>
                  <option <?= ($row['status_pesanan'] == 'Dibatalkan') ? 'selected' : '' ?>>Dibatalkan</option>
                </select>
                <input type="hidden" name="ubah_status" value="1">
              </form>
            </td>

            <?php if ($role == 'Petugas' || $role == 'Admin') : ?>
              <td>
                <!-- Tempat untuk aksi tambahan -->
                <em>-</em>
              </td>
            <?php endif; ?>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>


  <div class="bottom-nav">
        <a href="admin-dashboard.php">
          <i class="bi bi-house"></i>
          <span>Utama</span>
        </a>
        <a href="admin-kategori.php">
          <i class="bi bi-grid"></i>
          <span>Kategori</span>
        </a>
        <a href="admin-order.php" class="active">
          <i class="bi bi-arrow-repeat"></i>
          <span>Order</span>
        </a>
        <a href="admin-laporan.php">
          <i class="bi bi-file-earmark-text"></i>
          <span>Laporan</span>
        </a>
        <a href="admin-user.php">
          <i class="bi bi-people"></i>
          <span>User</span>
        </a>
  </div>

  

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
