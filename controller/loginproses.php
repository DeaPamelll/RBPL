<?php
session_start();
include 'koneksi.php'; // Ganti dengan path ke file koneksi Anda

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Cek input kosong
if (empty($username) || empty($password)) {
    $_SESSION['error'] = "Username dan password wajib diisi.";
    header("Location: admin-login.php");
    exit;
}

// Query ke database
$query = "SELECT * FROM login WHERE username = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Verifikasi password (pastikan Anda menyimpan password hash!)
    if ($password === $user['password']) {
        $_SESSION['user'] = $user;
        header("Location: ../admin-dashboard.php"); // redirect ke halaman dashboard
        exit;
    } else {
        $_SESSION['error'] = "Password salah.";
    }
} else {
    $_SESSION['error'] = "Username atau password tidak ditemukan.";
}

header("Location: ../index.php");
exit;
?>
