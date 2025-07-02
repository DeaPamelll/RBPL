<?php
include 'controller/koneksi.php';

// Ambil semua kategori unik dari menu
$kategoriQuery = "SELECT DISTINCT Kategori_menu FROM menu";
$kategoriResult = $koneksi->query($kategoriQuery);
$kategoriList = [];

while ($row = $kategoriResult->fetch_assoc()) {
    $kategoriList[] = $row['Kategori_menu'];
}

// Format harga
function format_rupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Food Menu</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #f5f5f5;
      max-width: 390px;
      margin: 0 auto;
      font-family: 'Segoe UI', sans-serif;
      overflow-x: hidden;
    }
    .header {
      background-color: #252c3a;
      color: white;
      padding: 1rem;
      font-size: 1rem;
      display: flex;
      align-items: center;
    }
    .section-title {
      padding: 10px 16px 0;
      margin-top: 10px;
      margin-bottom: 0;
    }
    .brand-section {
      padding: 0 16px;
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 10px;
    }
    .brand-section img {
      width: 48px;
      height: 48px;
      border-radius: 8px;
    }
    .brand-info {
      display: flex;
      flex-direction: column;
    }
    .brand-name {
      font-weight: bold;
      font-size: 16px;
    }
    .location-btn {
      background-color: #282E3B;
      border-radius: 12px;
      color: white;
      border: none;
      padding: 5px 15px;
      font-size: 12px;
      width: fit-content;
    }
    .search-box {
      padding: 0 16px;
      margin-bottom: 10px;
    }
    .search-box input {
      border-radius: 20px;
      padding: 8px 16px;
      font-size: 14px;
    }
    .category-title {
      font-weight: bold;
      font-size: 16px;
      padding: 0 16px;
      margin-top: 20px;
      margin-bottom: 10px;
    }
    .product-card {
      background-color: white;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0px 2px 6px rgba(0,0,0,0.08);
      margin-bottom: 16px;
      transition: transform 0.2s ease;
    }

    .product-card:hover {
      transform: translateY(-2px);
    }

    .product-card img {
      width: 100%;
      height: 160px;
      object-fit: contain;
      border-bottom: 1px solid #eee;
    }

    .product-info {
      padding: 12px;
      text-align: left;
    }

    .product-name {
      font-weight: 700;
      font-size: 15px;
      margin-bottom: 4px;
      color: black;
      line-height: 0.7;
    }

    .product-price {
      font-size: 13px;
      color: #888;
      margin-bottom: 4px;
    }

    .product-status {
      font-size: 11px;
      color: #666;
      margin-bottom: 12px;
    }

    .btn-beli {
      border: 1.5px solid #252c3a;
      color: #252c3a;
      border-radius: 30px;
      padding: 6px 24px;
      font-weight: bold;
      font-size: 13px;
      background-color: transparent;
    }

  </style>
</head>
<body>

<div class="header d-flex align-items-center px-3">
  <div class="px-1 py-4">
    <span class="fs-5 me-2">Tutup</span>
  </div>
</div>

<div class="section-title">
  <p style="font-size: 22px; font-weight: bold; line-height: 0.5;">Food</p>
  <p style="font-weight: normal; font-size: 12px; color: #706c6c; line-height: 0.5;">Order</p>
</div>

<hr>

<div class="brand-section">
  <img src="images/logo boemi.png" alt="logo">
  <div class="brand-info">
    <div class="brand-name">Boemi Coffee</div>
    <button class="location-btn"><i class="bi bi-geo-alt"></i> Sleman</button>
  </div>
</div>

<hr>

<div class="search-box">
  <div class="input-group">
    <span class="input-group-text border-end-0" style="background-color: #e3e3e3;">
      <i class="bi bi-search" style="color: #706c6c;"></i>
    </span>
    <input type="text" class="form-control border-start-0" style="border-radius: 5px; color: #706c6c; background-color: #e3e3e3;" placeholder="Search">
  </div>
</div>

<?php foreach ($kategoriList as $kategori): ?>
  <div class="category-title"><?php echo htmlspecialchars($kategori); ?></div>
  <div class="row gx-2 px-3">
    <?php
    $query = "SELECT * FROM menu WHERE Kategori_menu = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("s", $kategori);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()):
    ?>
    <div class="col-6">
      <a href="customer-detailmenu.php?id=<?php echo $row['ID_menu']; ?>" class="text-decoration-none">
        <div class="product-card">
          <img src="data:<?php echo $row['tipe_mime']; ?>;base64,<?php echo base64_encode($row['foto_menu']); ?>" class="product-image" alt="<?php echo htmlspecialchars($row['Nama_menu']); ?>">
          <div class="product-info">
            <div class="product-name"><?php echo htmlspecialchars($row['Nama_menu']); ?></div>
            <div class="product-price"><?php echo format_rupiah($row['harga']); ?></div>
            <div class="product-status"><?php echo $row['Jumlah_Stok'] > 0 ? 'Stok Tersedia' : 'Stok Habis'; ?></div>
            
            <a href="customer-detailmenu.php?id=<?php echo $row['ID_menu']; ?>" class="text-decoration-none">
              <div class="text-center">
                <button class="btn btn-beli">Beli</button>
              </div>
            </a>
          </div>
        </div>
      </a>
    </div>

    <?php endwhile; ?>
  </div>
<?php endforeach; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>