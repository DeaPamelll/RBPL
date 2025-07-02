<?php
session_start();
include 'controller/koneksi.php';

if (empty($_SESSION['user'])) {
    header("location:admin-login.php?pesan=belum_login");
    exit;
}

$user = $_SESSION['user'];

// Jika session berupa array
if (is_array($user) && isset($user['username'])) {
    $username = $user['username'];
}
// Jika session berupa string (username langsung)
elseif (is_string($user)) {
    $username = $user;
} else {
    die("Session user tidak valid.");
}

// Query data user dari tabel login
$queryUser = "SELECT * FROM login WHERE username = ?";
$stmt = $koneksi->prepare($queryUser);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();
?>



<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <title>User Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="style.css">
  <style>
    html, body {
      background-color: #f5f5f5;
      font-family: 'Segoe UI', sans-serif;
      max-width: 390px;
      height: 100%;
      margin: 0;
      padding: 0;
      overflow: hidden;
    }

    .header {
      background-color: #252c3a;
      color: white;
      padding: 1rem;
      font-size: 1rem;
      display: flex;
      align-items: center;
    }

    .card-container {
      background-color: white;
      margin: 50px 16px;
      border-radius: 12px;
      padding: 20px;
    }

    .card-container h5 {
      font-weight: bold;
      margin-bottom: 10px;
    }

    .card-container hr {
      margin-top: 0;
      margin-bottom: 16px;
    }

    .profile-box {
      background-color: #e2e2e2;
      border-radius: 12px;
      padding: 40px 20px;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .profile-icon {
      width: 66px;
      height: 66px;
      border: 2px solid #333;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 40px;
      color: #333;
    }

    .profile-info {
      font-size: 13px;
      line-height: 1.4;
    }

    .profile-info .name {
      font-weight: bold;
      font-size: 14px;
    }

    .logout-btn {
      margin: 250px 16px;
      border-radius: 10px;
      font-weight: bold;
      border: 1px solid #ccc;
      padding: 10px;
      background-color: white;
      width: calc(100% - 32px);
    }


    .bottom-nav {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      background-color: white;
      border-top: 1px solid #ccc;
      display: flex;
      justify-content: space-around;
      padding: 6px 0;
      max-width: 390px;
      margin: 0 auto;
    }

    .bottom-nav .nav-item {
      text-align: center;
      font-size: 11px;
      color: #888;
    }

    .bottom-nav .nav-item.active {
      color: #000;
      font-weight: bold;
    }

    .bottom-nav .nav-item i {
      display: block;
      font-size: 18px;
      margin-bottom: 2px;
    }
  </style>
</head>
<body>

    <div class="header d-flex align-items-center px-3">
        <div class="container py-4">
            <span class="fs-5 me-2">Tutup</span>
        </div>
    </div>

    <div class="card-container">
      <h5>User</h5>
      <hr>
      <div class="profile-box">
        <div class="profile-icon"><i class="bi bi-person"></i></div>
        <div class="profile-info">
          <div class="name"><?php echo htmlspecialchars($userData['username']); ?></div>
          <div style="color: #706c6c;"><?php echo htmlspecialchars($userData['role']); ?></div>
          <div style="color: #706c6c;"><?php echo htmlspecialchars($userData['email']); ?></div>
        </div>
      </div>
    </div>
  
    <a href="controller/logout.php"><button class="btn logout-btn">Keluar</button></a>

    <div class="bottom-nav">
        <a href="admin-dashboard.php">
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
        <a href="admin-user.php" class="active">
          <i class="bi bi-people"></i>
          <span>User</span>
        </a>
  </div>

</body>
</html>
