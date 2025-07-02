<?php
session_start();
if (empty($_SESSION['user'])) {
    header("Location: admin-login.php?pesan=belum_login");
    exit;
}
include 'controller/koneksi.php';

// Ambil ID dan tipe dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$tipe = $_GET['tipe'] ?? '';

if ($tipe === 'produk') {
    $stmt = $koneksi->prepare("SELECT * FROM barang WHERE ID_Barang = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if (!$data) {
        die("Produk tidak ditemukan.");
    }
} else {
    die("Tipe tidak valid.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Detail Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <style>
    html, body {
      height: 100vh;
      margin: 0;
      padding: 0;
      overflow: hidden;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4fafa;
    }

    .header {
      background-color: #282e3b;
      color: white;
      height: 110px;
      display: flex;
      align-items: center;
    }

    .header i {
      font-size: 20px;
      cursor: pointer;
    }

    .content-container {
      max-width: 480px;
      margin: auto;
      background-color: white;
      height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      overflow: hidden;
      position: relative;
    }

    .product-page {
      display: flex;
      flex-direction: column;
    }

    .product-image-wrapper {
      background-color: #d9d9d9;
    }

    .product-image img {
      display: block;
      width: 140px;
      height: auto;
      margin: 16px auto;
    }

    .product-info {
      padding: 20px;
    }

    .category {
      font-size: 14px;
      font-weight: bold;
      color: #6c757d;
      margin-bottom: 4px;
      margin-top: 10px;
    }

    .product-title {
      font-size: 20px;
      font-weight: 700;
      margin-bottom: 10px;
    }

    .description {
      font-size: 14px;
      color: #555;
      text-align: justify;
      margin-top: 30px;
    }

    .restock-btn {
      position: fixed;
      bottom: 50px;
      left: 20px;
      right: 20px;
      background-color: #282e3b;
      color: white;
      text-align: center;
      padding: 10px 0;
      border-radius: 12px;
      font-weight: 600;
      font-size: 16px;
      cursor: pointer;
      z-index: 1000;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .restock-btn:hover {
      background-color: #1f2733;
    }

    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      height: 100%;
      width: 100%;
      background-color: rgba(0, 0, 0, 0.4);
      display: none;
      justify-content: center;
      align-items: flex-end;
      z-index: 2000;
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .overlay.active {
      display: flex;
      opacity: 1;
    }

    .overlay.fade-out {
      opacity: 0;
    }

    .restock-form {
      background-color: white;
      padding: 20px 20px 30px;
      border-radius: 35px 35px 0 0;
      width: 100%;
      max-width: 480px;
      height: 78%;
      overflow-y: auto;
      position: relative;
      animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
      from {
        transform: translateY(100%);
      }
      to {
        transform: translateY(0);
      }
    }

    .form-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .b-close {
      background-color: transparent;
      border: none;
      color: #706c6c;
    }

    .btn-submit {
      background-color: #282e3b;
      color: white;
    }

    .btn-submit:hover {
      background-color: #1f2733;
    }
    .custom-input {
        background-color: #f1f1f1;
        border: none; 
    }

  </style>
</head>
<body>

  <!-- Header -->
  <div class="header px-3">
    <a href="admin-kategori.php"><i class="bi bi-arrow-left fs-4" style="color:white;"></i></a>
  </div>

  <!-- Konten Utama -->
  <div class="content-container">
    <div class="product-page">
      <div class="product-image-wrapper">
        <div class="product-image">
          <img src="controller/showimageproduk.php?id=<?= $data['ID_Barang'] ?>" alt="<?= htmlspecialchars($data['Nama_barang']) ?>">
        </div>
      </div>

      <div class="product-info">
        <div class="category"><?= htmlspecialchars(strtoupper($data['Kategori_barang'])) ?></div>
        <div class="product-title"><?= htmlspecialchars($data['Nama_barang']) ?></div>
        <div class="description"><?= nl2br(htmlspecialchars($data['deskripsi'] ?? 'Tidak ada deskripsi.')) ?></div>
      </div>
    </div>
  </div>

   <!-- Tombol Fixed -->
  <div class="restock-btn" onclick="showOverlay()">Restok</div>

  <!-- Overlay Restok -->
  <div class="overlay" id="restockOverlay">
    <div class="restock-form">
      <div class="form-header d-flex justify-content-between align-items-center">
        <span class="mb-0 mx-auto" style="padding-left: 20px; font-size: 20px; font-weight: bold;">Restok Produk</span>
        <button type="button" class="b-close" onclick="hideOverlay()">
          <i class="bi bi-x-circle"></i>
        </button>
      </div>
      <hr>
      <form action="controller/restokproduk.php" method="POST">
        <input type="hidden" name="id_barang" value="<?= $data['ID_Barang'] ?>">
        <div class="mb-3">
          <label class="form-label">Waktu</label>
          <?php $tanggalHariIni = date('Y-m-d'); ?>
          <input type="date" name="tanggal" class="form-control custom-input" required value="<?= $tanggalHariIni ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Nama</label>
          <input type="text" name="nama" value="<?= htmlspecialchars($data['Nama_barang']) ?>" class="form-control custom-input" readonly>
        </div>
        <div class="mb-3">
          <label class="form-label">Jumlah Tambahan</label>
          <input type="number" name="jumlah" class="form-control custom-input" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Kategori</label>
          <input type="text" name="kategori" value="<?= htmlspecialchars($data['Kategori_barang']) ?>" class="form-control custom-input" readonly>
        </div>
        <div class="mb-3">
          <label class="form-label">Deskripsi</label>
          <textarea name="deskripsi" class="form-control custom-input" rows="3"><?= htmlspecialchars($data['deskripsi'] ?? '') ?></textarea>
        </div>
        <div class="d-flex justify-content-between mt-4">
          <button type="button" class="btn btn-secondary" onclick="hideOverlay()" style="width: 170px;">Cancel</button>
          <button type="submit" class="btn btn-submit" style="width: 170px;">Submit</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    const overlay = document.getElementById("restockOverlay");
    function showOverlay() {
      overlay.style.display = "flex";
      setTimeout(() => {
        overlay.classList.add("active");
      }, 10);
      document.body.style.overflow = "hidden";
    }

    function hideOverlay() {
      overlay.classList.remove("active");
      overlay.classList.add("fade-out");
      setTimeout(() => {
        overlay.style.display = "none";
        overlay.classList.remove("fade-out");
      }, 300);
      document.body.style.overflow = "";
    }
  </script>
</body>
</html>