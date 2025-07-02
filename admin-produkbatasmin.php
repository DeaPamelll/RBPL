<?php
include 'controller/koneksi.php';

$id = $_GET['id'] ?? null;
$tipe = $_GET['tipe'] ?? 'produk';

if (!$id || !in_array($tipe, ['produk', 'menu'])) {
  die('Data tidak valid.');
}

if ($tipe === 'produk') {
  $query = "SELECT * FROM barang WHERE ID_Barang = ?";
} else {
  $query = "SELECT * FROM menu WHERE ID_menu = ?";
}

$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
  die('Data tidak ditemukan.');
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

    .custom-input:focus {
        background-color: #e0f7fa;
        border-color: #007bff; 
    }
        input[type="date"]::-webkit-datetime-edit {
        color: transparent;
    }
        input[type="date"]:focus::-webkit-datetime-edit {
        color: black;
    }


  </style>
</head>
<body>

  <!-- Header -->
  <div class="header px-3">
    <a href="index.php"><i class="bi bi-arrow-left fs-4" style="color: white;"></i></a>
  </div>

  <!-- Konten Utama -->
  <div class="content-container">
    <div class="product-page">
      <div class="product-image-wrapper">
        <div class="product-image">
          <img src="data:<?php echo $tipe === 'produk' ? $data['tipe_mime'] : $data['tipe_mime']; ?>;base64,<?php echo base64_encode($tipe === 'produk' ? $data['foto_barang'] : $data['foto_menu']); ?>" alt="gambar">
        </div>
      </div>

      <div class="product-info">
        <div class="category"><?php echo strtoupper($tipe === 'produk' ? $data['Kategori_barang'] : $data['Kategori_menu']); ?></div>
        <div class="product-title"><?php echo $tipe === 'produk' ? $data['Nama_barang'] : $data['Nama_menu']; ?></div>
        <div class="description">
          <?php echo !empty($data['deskripsi']) ? $data['deskripsi'] : 'Belum ada deskripsi.'; ?>
        </div>
        <div class="mt-4 fw-bold" id="stockStatus">
          Stok <?php echo $data['Status_Stok']; ?>
        </div>

        <div class="d-flex flex-column align-items-center" style="margin-top: 140px; position:fixed; bottom:30px; left:40px;">
          <div class="d-flex align-items-center">
            <div class="mx-2 py-2 rounded-3 d-flex justify-content-center align-items-center" style="width: 220px; background-color: #282e3b; width: 300px;">
              <span class="fw-bold" style="color: white;"><?php echo $data['Jumlah_Stok']; ?></span>
            </div>
          </div>
           <small class="text-muted mt-2" id="stockText">Jumlah Stok</small>
        </div>
      </div>



  

<script>

</script>


</body>
</html>
