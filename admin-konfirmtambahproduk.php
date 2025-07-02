<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tambah Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f5f5f5;
      margin: 0;
      padding: 0;
      height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .header {
      background-color: #282e3b;
      height: 110px;
      flex-shrink: 0;
    }

    .main {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        transform: translateY(-40px); /* naik 40px dari tengah */
    }

    .check-icon {
      width: 80px;
      height: 80px;
      background-color: #4CAF50;
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 20px;
    }

    .check-icon i {
      color: white;
      font-size: 48px;
    }

    .success-title {
      font-weight: bold;
      font-size: 18px;
    }

    .success-text {
      font-size: 14px;
      color: #555;
    }

    .footer-button {
      position: fixed;
      bottom: 50px;
      left: 20px;
      right: 20px;
      background-color: #282e3b;
      color: white;
      text-align: center;
      padding: 5px 0;
      border-radius: 12px;
      font-size: 16px;
      z-index: 1000;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .footer-button .btn {
      background: transparent;
      border: none;
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="header px-3"></div>

  <div class="main">
    <div class="check-icon">
      <i class="bi bi-check-lg"></i>
    </div>
    <div class="success-title">Berhasil Ditambahkan</div>
    <div class="success-text mt-2">
      Produk berhasil ditambahkan.<br>
      Kembali ke halaman utama untuk melihat produk
    </div>
  </div>

  <div class="footer-button text-center">
    <a href="admin-kategori.php"><button class="btn w-100 text-white">Selesai</button></a>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
