<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tambah Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <style>
    html, body {
      background-color: #f5f5f5;
      height: 100%;
    margin: 0;
    padding: 0;
    overflow: hidden;
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

    .container {
        height: calc(100% - 110px); /* 110px tinggi header */
        overflow-y: auto;
        padding-bottom: 120px;
    }

    .form-container {
      background-color: white;
      border-radius: 10px;
      padding: 25px;
      margin-top: 30px;
    }

    .form-title {
      text-align: center;
      font-weight: bold;
      font-size: 18px;
      margin-bottom: 20px;
    }

    .bottom-buttons {
      position: fixed;
      bottom: 0;
      right: 0;
      left: 0;
      background-color: white;
      padding: 30px 20px;
      display: flex;
      justify-content: center;
      gap: 10px;
    }

    .btn-cancel {
      border: 1px solid #ccc;
      width: 170px;
    }

    .btn-submit{
      background-color: #282e3b;
      opacity: 79%;
      color: white;
      width: 170px;
    }

    .dropdown-menu {
      min-width: 100%;
    }
  </style>
</head>
<body>
  <div class="header px-3">
    <a href="admin-kategori.php?tab=menu"><i class="bi bi-arrow-left fs-4" style="color: white;"></i></a>
  </div>

  <div class="container">
    <div class="form-container shadow-sm">
        <div class="form-title">Tambah Menu</div>
        <form action="controller/tambahmenu.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Menu</label>
            <input type="text" name="nama" class="form-control" id="nama" required>
        </div>

        <div class="mb-3">
            <label for="harga" class="form-label">Harga</label>
            <input type="number" name="harga" class="form-control" id="harga" required>
        </div>

        <div class="mb-3">
            <label for="jumlah" class="form-label">Jumlah Stok</label>
            <input type="number" name="jumlah" class="form-control" id="jumlah" required>
        </div>

        <div class="mb-3">
            <label for="kategori" class="form-label">Kategori</label>
            <select class="form-select" name="kategori" id="kategori" required>
            <option selected disabled value="">-- Pilih Kategori --</option>
            <option value="Espresso Based">Espresso Based</option>
            <option value="Signature">Signature</option>
            <option value="Snack">Snack</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" id="deskripsi" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label for="foto" class="form-label">Foto Menu</label>
            <input type="file" name="foto" class="form-control" id="foto" accept="image/*" required>
        </div>

        <div class="bottom-buttons">
            <a href="admin-kategori.php?tab=menu" class="btn btn-outline-secondary btn-cancel fw-bold text-black">Cancel</a>
            <button type="submit" class="btn btn-submit">Submit</button>
        </div>
        </form>
    </div>
  </div>



  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>