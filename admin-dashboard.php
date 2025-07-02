<?php
    session_start();
    if(empty($_SESSION['user'])){
        header("location:admin-login.php?pesan=belum_login");
        exit;
    }

    include 'controller/koneksi.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Stok Hampir Habis</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <style>
    body {
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

    .content-container {
      max-width: 400px;
      margin: 0 auto;
      background-color: white;
      border-radius: 10px;
      padding: 1.5rem;
    }

    .product-list {
      border: 1px solid #dee2e6;
      border-radius: 10px;
      overflow: hidden;
    }

    .product-item {
      display: flex;
      align-items: center;
      padding: 0.75rem 1rem;
      border-bottom: 1px solid #dee2e6;
    }

    .product-item:last-child {
      border-bottom: none;
    }

    .product-img {
      width: 40px;
      height: 40px;
      object-fit: cover;
      margin-right: 1rem;
    }

    .product-info {
      flex-grow: 1;
    }

    .product-info small {
      font-size: 10px;
      text-transform: uppercase;
      color: gray;
    }

    .product-info strong {
      font-size: 14px;
      display: block;
    }

    .product-units {
      font-size: 12px;
      color: #666;
    }

    .product-arrow {
      color: #555;
    }
    .product-card {
      border: 1px solid #eee;
      border-radius: 10px;
      padding: 0.75rem;
      text-align: center;
      background-color: #fff;
      box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .product-card img {
      width: 40px;
      height: 60px;
      object-fit: contain;
      margin-bottom: 0.5rem;
    }

    .category-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-weight: bold;
      margin-top: 1rem;
      margin-bottom: 0.5rem;
    }

    .product-name {
      font-weight: bold;
    }

    .product-unit {
      font-size: 13px;
      color: #666;
    }

    .laporan-wrapper {
      background-color: #ffffff;
      border: 1px solid #e0e0e0;
      border-radius: 16px;
      overflow: hidden;
    }

    .laporan-table {
      width: 100%;
      margin-bottom: 0;
    }

    .laporan-table thead tr {
      background-color: #f9f9f9;
    }

    .laporan-table th {
      font-size: 13px;
      color: #6c757d;
      font-weight: 600;
      padding: 12px 16px;
      border-bottom: 1px solid #f0f0f0;
    }

    .laporan-table td {
      font-size: 15px;
      font-weight: 500;
      padding: 14px 16px;
      border-bottom: 1px solid #f1f1f1;
    }

    .laporan-table tr:last-child td {
      border-bottom: none;
    }

    .ellipsis {
      text-align: right;
      font-weight: bold;
      font-size: 22px;
    }
  </style>
</head>
<body>

    <div class="header d-flex align-items-center px-3">
        <div class="container py-4">
            <a href="controller/logout.php" class="fs-5 me-2 text-white text-decoration-none">Tutup</a>
        </div>
     </div>

    <div class="content-container" style="margin-bottom: 70px;">
      <p class="fw-bold mb-3 fs-4">Menu hampir habis</p>
        <div class="product-list">
          <?php

          $query = "SELECT ID_menu, Nama_menu, Kategori_menu, Jumlah_Stok, Status_Stok, foto_menu FROM menu WHERE Status_Stok = 'Hampir habis' LIMIT 4";
          $result = $koneksi->query($query);

          if ($result && $result->num_rows > 0):
              while ($row = $result->fetch_assoc()):
          ?>
              <a href="admin-produkbatasmin.php?id=<?= $row['ID_menu'] ?>&tipe=produk" class="text-decoration-none text-dark">
                <div class="product-item">
                  <img src="controller/showimagemenu.php?id=<?= $row['ID_menu'] ?>" alt="<?= htmlspecialchars($row['Nama_menu']) ?>" class="product-img">
                  <div class="product-info">
                      <small class="fw-bold"><?= htmlspecialchars(strtoupper($row['Kategori_menu'])) ?></small>
                      <strong><?= htmlspecialchars($row['Nama_menu']) ?></strong>
                      <div class="product-units"><?= intval($row['Jumlah_Stok']) ?> unit</div>
                  </div>
                  <i class="bi bi-chevron-right product-arrow"></i>
                </div>
              </a>

          <?php
              endwhile;
            else:
          ?>
              <p class="text-muted">Tidak ada data stok hampir habis.</p>
          <?php endif; ?>
        </div>  
    
      <p class="fw-bold mb-3 fs-4 mt-4">Stok hampir habis</p>
        <div class="product-list">
          <?php

          $query = "SELECT ID_Barang, Nama_barang, Kategori_barang, Jumlah_Stok, Status_Stok, foto_barang FROM barang WHERE Status_Stok = 'Hampir habis' LIMIT 4";
          $result = $koneksi->query($query);

          if ($result && $result->num_rows > 0):
              while ($row = $result->fetch_assoc()):
          ?>
              <a href="admin-produkbatasmin.php?id=<?= $row['ID_Barang'] ?>&tipe=produk" class="text-decoration-none text-dark">
                <div class="product-item">
                  <img src="controller/showimageproduk.php?id=<?= $row['ID_Barang'] ?>" alt="<?= htmlspecialchars($row['Nama_barang']) ?>" class="product-img">
                  <div class="product-info">
                      <small class="fw-bold"><?= htmlspecialchars(strtoupper($row['Kategori_barang'])) ?></small>
                      <strong><?= htmlspecialchars($row['Nama_barang']) ?></strong>
                      <div class="product-units"><?= intval($row['Jumlah_Stok']) ?> unit</div>
                  </div>
                  <i class="bi bi-chevron-right product-arrow"></i>
                </div>
              </a>

          <?php
              endwhile;
            else:
          ?>
              <p class="text-muted">Tidak ada data stok hampir habis.</p>
          <?php endif; ?>
        </div>
        <div>
            <h6 class="fw-bold mb-2 fs-4 mt-4">Produk</h6>
        
            <!-- SIRUP -->
            <div class="category-header">
            <span class="fs-5">Sirup</span>
            <a href="#" class="text-decoration-none" style="font-size: 13px; color: black;">Lihat Lainnya ></a>
            </div>
            <div class="row g-2">
                <?php
                include 'controller/koneksi.php';

                // ambil 3 produk acak dari kategori Sirup
                $query = "SELECT Nama_barang, Jumlah_Stok, foto_barang, ID_Barang FROM barang WHERE Kategori_barang = 'Sirup' ORDER BY RAND() LIMIT 3";
                $result = $koneksi->query($query);

                if ($result && $result->num_rows > 0):
                    while ($row = $result->fetch_assoc()):
                ?>
                    <div class="col-4">
                        <a href="admin-detailproduk.php?id=<?= $row['ID_Barang'] ?>&tipe=produk" class="text-decoration-none text-dark">
                      <div class="product-card">
                        <img src="controller/showimageproduk.php?id=<?= $row['ID_Barang'] ?>" alt="<?= htmlspecialchars($row['Nama_barang']) ?>" />
                        <div class="product-name"><?= htmlspecialchars($row['Nama_barang']) ?></div>
                        <div class="product-unit"><?= intval($row['Jumlah_Stok']) ?> unit</div>
                      </div>
                    </a>
                    </div>
                <?php
                    endwhile;
                else:
                    echo "<p class='text-muted'>Tidak ada produk sirup.</p>";
                endif;
                ?>
            </div>
        
            <!-- POWDER -->
            <div class="category-header">
            <span class="fs-5">Powder</span>
            <a href="#" class="text-decoration-none" style="font-size: 13px; color: black;">Lihat Lainnya ></a>
            </div>
            <div class="row g-2">
                <?php
                include 'controller/koneksi.php';

                // ambil 3 produk acak dari kategori Sirup
                $query = "SELECT Nama_barang, Jumlah_Stok, foto_barang, ID_Barang FROM barang WHERE Kategori_barang = 'Powder' ORDER BY RAND() LIMIT 3";
                $result = $koneksi->query($query);

                if ($result && $result->num_rows > 0):
                    while ($row = $result->fetch_assoc()):
                ?>
                    <div class="col-4">
                        <a href="admin-produkbatasmin.php?id=<?= $row['ID_Barang'] ?>&tipe=produk" class="text-decoration-none text-dark">
                          <div class="product-card">
                            <img src="controller/showimageproduk.php?id=<?= $row['ID_Barang'] ?>" alt="<?= htmlspecialchars($row['Nama_barang']) ?>" />
                            <div class="product-name"><?= htmlspecialchars($row['Nama_barang']) ?></div>
                            <div class="product-unit"><?= intval($row['Jumlah_Stok']) ?> unit</div>
                          </div>
                        </a>
                    </div>
                <?php
                    endwhile;
                else:
                    echo "<p class='text-muted'>Tidak ada produk sirup.</p>";
                endif;
                ?>
            </div>
        
            <!-- SUSU -->
            <div class="category-header">
            <span class="fs-5">Susu</span>
            <a href="#" class="text-decoration-none" style="font-size: 13px; color: black;">Lihat Lainnya ></a>
            </div>
            <div class="row g-2 mb-4">
                <?php
                include 'controller/koneksi.php';

                // ambil 3 produk acak dari kategori Sirup
                $query = "SELECT Nama_barang, Jumlah_Stok, foto_barang, ID_Barang FROM barang WHERE Kategori_barang = 'Susu' ORDER BY RAND() LIMIT 3";
                $result = $koneksi->query($query);

                if ($result && $result->num_rows > 0):
                    while ($row = $result->fetch_assoc()):
                ?>
                    <div class="col-4">
                        <a href="admin-produkbatasmin.php?id=<?= $row['ID_Barang'] ?>&tipe=produk" class="text-decoration-none text-dark">
                          <div class="product-card">
                            <img src="controller/showimageproduk.php?id=<?= $row['ID_Barang'] ?>" alt="<?= htmlspecialchars($row['Nama_barang']) ?>" />
                            <div class="product-name"><?= htmlspecialchars($row['Nama_barang']) ?></div>
                            <div class="product-unit"><?= intval($row['Jumlah_Stok']) ?> unit</div>
                          </div>
                        </a>
                    </div>
                <?php
                    endwhile;
                else:
                    echo "<p class='text-muted'>Tidak ada produk sirup.</p>";
                endif;
                ?>
            </div>
            
            <div class="laopran mb-5 pb-3 px-3">
              <p class="fs-4 fw-bold">Laporan</p>
              <div class="laporan-wrapper">
                <div style="overflow-x: auto; width: 100%;">
                  <table class="laporan-table">
                    <thead>
                      <tr>
                        <th>TANGGAL</th>
                        <th>USER</th>
                        <th>MENU</th>
                        <th>JUMLAH</th>
                        <th>TOTAL</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      include 'controller/koneksi.php';
  
                      $query_laporan = mysqli_query($koneksi, "
                        SELECT 
                          p.Tanggal,
                          pel.Nama,
                          GROUP_CONCAT(m.Nama_menu SEPARATOR ', ') AS MenuDipesan,
                          SUM(dp.Jumlah) AS TotalJumlah,
                          SUM(dp.Subtotal) AS TotalHarga
                        FROM pesanan p
                        JOIN pelanggan pel ON p.ID_Pelanggan = pel.ID_Pelanggan
                        JOIN detail_pesanan dp ON p.ID_Pesanan = dp.ID_Pesanan
                        JOIN menu m ON dp.ID_Menu = m.ID_Menu
                        WHERE p.status_pesanan = 'Selesai'
                        GROUP BY p.ID_Pesanan
                        ORDER BY p.Tanggal DESC
                        LIMIT 3
                      ");
  
                      if (mysqli_num_rows($query_laporan) > 0):
                        while ($lap = mysqli_fetch_assoc($query_laporan)):
                      ?>
                        <tr>
                          <td><?= date('d/m/y H:i', strtotime($lap['Tanggal'])) ?></td>
                          <td><?= htmlspecialchars($lap['Nama']) ?></td>
                          <td><?= htmlspecialchars($lap['MenuDipesan']) ?></td>
                          <td><?= intval($lap['TotalJumlah']) ?></td>
                          <td>Rp <?= number_format($lap['TotalHarga'], 0, ',', '.') ?></td>
                        </tr>
                      <?php
                        endwhile;
                      else:
                      ?>
                        <tr>
                          <td colspan="5" class="text-center text-muted">Belum ada data penjualan.</td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            
        </div>
    </div>


    <div class="bottom-nav">
        <a href="admin-dashboard.php" class="active">
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
        <a href="admin-laporan.php">
          <i class="bi bi-file-earmark-text"></i>
          <span>Laporan</span>
        </a>
        <a href="admin-user.php">
          <i class="bi bi-people"></i>
          <span>User</span>
        </a>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>