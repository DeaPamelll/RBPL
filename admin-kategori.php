<?php
session_start();
if(empty($_SESSION['user'])){
    header("location:admin-login.php?pesan=belum_login");
    exit;
}
include 'controller/koneksi.php';

// Ambil tab aktif & kata kunci pencarian
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'produk';
$cari = isset($_GET['cari']) ? $_GET['cari'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Produk & Menu</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      max-width: 390px;
      margin: 0 auto;
      background-color: #f5f5f5;
      font-family: sans-serif;
    }
    .header {
      background-color: #252c3a;
      color: white;
      padding: 1rem;
      font-size: 1rem;
      display: flex;
      align-items: center;
    }
    .search-section {
      background: #f5f5f5;
      padding: 16px;
    }
    .search-input {
      position: relative;
      flex-grow: 1;
    }
    .search-input input {
      padding-left: 36px;
      background-color: #e3e3e3;
      border: none;
      border-radius: 7px;
      height: 32px;
    }
    .search-icon {
      position: absolute;
      left: 10px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 16px;
      color: #666;
      pointer-events: none;
    }
    .add-btn {
      background: #202a36;
      color: #fff;
      border: none;
      border-radius: 50%;
      width: 32px;
      height: 32px;
      font-size: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0;
    }
    .category-title {
      margin: 20px 16px 8px;
      font-weight: bold;
    }
    .product-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 12px;
      padding: 0 16px;
      margin-bottom: 12px;
    }
    .product-card {
      background: #fff;
      border-radius: 10px;
      padding: 6px;
      text-align: center;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .product-card img {
      width: 100%;
      height: 60px;
      object-fit: contain;
      margin-bottom: 4px;
    }
    .product-name {
      font-size: 12px;
      font-weight: 600;
    }
    .product-unit {
      font-size: 10px;
      color: #555;
      margin-top: 2px;
    }
    .tab-btn {
      border: none;
      background: none;
      font-weight: bold;
      padding: 0 10px;
      cursor: pointer;
      font-size: 18px;
    }
    .tab-btn.active {
      color: #007bff;
      text-decoration: underline;
    }
    .tab-toggle {
      font-size: 20px;
      font-weight: bold;
      color: lightgray;
      text-decoration: none;
      padding-bottom: 4px;
      border-bottom: 2px solid transparent;
    }
    .tab-toggle.active {
      color: #252c3a;
      border-color: #252c3a;
    }

  </style>
</head>
<body>

<div class="header d-flex align-items-center px-3">
        <div class="container py-4">
            <a href="controller/logout.php" class="fs-5 me-2 text-white text-decoration-none">Tutup</a>
        </div>
     </div>

<div class="container">
  <div class="search-section">
    <div class="d-flex gap-3 mb-2">
      <a href="?tab=produk" class="tab-toggle <?php echo $tab == 'produk' ? 'active' : ''; ?>">Produk</a>
      <a href="?tab=menu" class="tab-toggle <?php echo $tab == 'menu' ? 'active' : ''; ?>">Menu</a>
    </div>

    <form method="GET" class="d-flex align-items-center gap-2">
      <div class="search-input">
        <i class="bi bi-search search-icon"></i>
        <input type="text" class="form-control form-control-sm" name="cari" placeholder="Search" value="<?php echo htmlspecialchars($cari); ?>" />
        <input type="hidden" name="tab" value="<?php echo $tab; ?>">
      </div>
      <button type="submit" class="add-btn" title="Cari">
        <i class="bi bi-search"></i>
      </button>
      <a href="admin-formtambah<?php echo $tab; ?>.php" class="add-btn" title="Tambah <?php echo ucfirst($tab); ?>">
        <i class="bi bi-plus"></i>
      </a>
    </form>
  </div>

  <div class="kategori" style="margin-bottom: 90px;">

  <?php if ($tab === 'produk'): ?>
    <?php
      $kategoriList = ['Sirup', 'Powder', 'Susu', 'Teh'];
      foreach ($kategoriList as $kategori) {
        $query = "SELECT ID_Barang, Nama_barang, Jumlah_Stok, foto_barang, tipe_mime 
                  FROM barang 
                  WHERE Kategori_barang = '$kategori'";
        if (!empty($cari)) {
          $query .= " AND Nama_barang LIKE '%" . $koneksi->real_escape_string($cari) . "%'";
        }
        $result = $koneksi->query($query);
        if ($result->num_rows > 0):
    ?>
    <div class="category-title"><?php echo $kategori; ?></div>
    <div class="product-grid">
      <?php while($row = $result->fetch_assoc()): ?>
        <a href="admin-detail.php?id=<?php echo $row['ID_Barang']; ?>&tipe=produk" class="text-decoration-none text-dark">
          <div class="product-card">
            <img src="data:<?php echo $row['tipe_mime']; ?>;base64,<?php echo base64_encode($row['foto_barang']); ?>" alt="<?php echo htmlspecialchars($row['Nama_barang']); ?>" />
            <div class="product-name"><?php echo htmlspecialchars($row['Nama_barang']); ?></div>
            <div class="product-unit"><?php echo $row['Jumlah_Stok']; ?> unit</div>
          </div>
        </a>
      <?php endwhile; ?>
    </div>
    <?php endif; } ?>

  <?php elseif ($tab === 'menu'): ?>
    <?php
    $kategoriList = ['Espresso Based', 'Signature', 'Snack'];
    foreach ($kategoriList as $kategori) {
      $query = "SELECT ID_menu, Nama_menu, Jumlah_Stok, foto_menu, tipe_mime 
                FROM menu
                WHERE Kategori_menu = '" . $koneksi->real_escape_string($kategori) . "'";

      if (!empty($cari)) {
        $query .= " AND Nama_menu LIKE '%" . $koneksi->real_escape_string($cari) . "%'";
      }

      $result = $koneksi->query($query);
      if ($result && $result->num_rows > 0):
    ?>

      <div class="category-title"><?php echo $kategori; ?></div>
      <div class="product-grid">
        <?php while($row = $result->fetch_assoc()): ?>
          <a href="admin-detailmenu.php?id=<?php echo $row['ID_menu']; ?>&tipe=menu" class="text-decoration-none text-dark">
            <div class="product-card">
              <img src="data:<?php echo $row['tipe_mime']; ?>;base64,<?php echo base64_encode($row['foto_menu']); ?>" alt="<?php echo htmlspecialchars($row['Nama_menu']); ?>" />
              <div class="product-name"><?php echo htmlspecialchars($row['Nama_menu']); ?></div>
              <div class="product-unit"><?php echo $row['Jumlah_Stok']; ?> unit</div>
            </div>
          </a>
        <?php endwhile; ?>
      </div>
      <?php endif; } ?>
    <?php else: ?>
      <div class="text-center mt-4">Menu tidak ditemukan.</div>
    <?php endif; ?>

  </div>
</div>

    <div class="bottom-nav">
        <a href="index.php">
          <i class="bi bi-house"></i>
          <span>Utama</span>
        </a>
        <a href="admin-kategori.php" class="active">
          <i class="bi bi-grid"></i>
          <span>Kategori</span>
        </a>
        <a href="admin-order.php">
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
</body>
</html>
