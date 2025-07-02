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
      padding: 1rem;
      height: 110px;
    }

    .header i {
      font-size: 20px;
      cursor: pointer;
    }

    .content-container {
      max-width: 480px;
      margin: auto;
      background-color: white;
      height: calc(100vh - 110px);
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      overflow: hidden;
      position: relative;
    }

    .footer {
      padding: 16px;
      margin-bottom: 40px;
      background-color: #ffffff;
      display: flex;
      justify-content: center;
    }

    .btn {
      width: 90%;
      padding: 14px;
      font-weight: bold;
      background-color: #212534;
      color: white;
      border: none;
      border-radius: 10px;  
      font-size: 16px;
      cursor: pointer;
    }
  </style>
</head>
<body>

    <!-- Header -->
    <div class="header px-3">
    </div>

    <!-- Konten Utama -->
    <div class="content-container">
        <div class="d-flex justify-content-center flex-column align-items-center text-center flex-grow-1">
            <img src="images/send.png" alt="Icon Terkirim" style="width: 110px; margin-bottom: 40px;"/>
            <p style="font-size: 20px; font-weight: bold;">Berhasil Dikirimkan</p>
            <p>Permintaan stok produk<br>berhasil dikirim. Kembali ke halaman<br>utama</p>
        </div>

        <div class="footer">
          <a href="admin-dashboard.php">
            <button class="btn" style="width: 280px;">Selesai</button>
          </a>
        </div>
    </div>

    <script>
    </script>
</body>
</html>