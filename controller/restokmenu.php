<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_barang   = intval($_POST['id_menu']);
    $tanggal     = $_POST['tanggal'] ?? '';
    $jumlah      = intval($_POST['jumlah']);
    $deskripsi   = $_POST['deskripsi'] ?? '';

    // Validasi dasar
    if ($id_barang <= 0 || $jumlah <= 0 || empty($tanggal)) {
        die("Data tidak valid.");
    }

    // Ambil stok lama
    $queryGet = "SELECT Jumlah_Stok FROM menu WHERE ID_menu = ?";
    $stmtGet = $koneksi->prepare($queryGet);
    $stmtGet->bind_param("i", $id_barang);
    $stmtGet->execute();
    $result = $stmtGet->get_result();

    if ($result->num_rows === 0) {
        die("Menu tidak ditemukan.");
    }

    $data = $result->fetch_assoc();
    $stok_lama = intval($data['Jumlah_Stok']);
    $stok_baru = $stok_lama + $jumlah;

    // Perbarui stok & deskripsi
    $queryUpdate = "UPDATE menu SET Jumlah_Stok = ?, deskripsi = ?, Status_Stok = ? WHERE ID_menu = ?";
    $status_stok = $stok_baru <= 2 ? 'Hampir habis' : 'Tersedia';

    $stmtUpdate = $koneksi->prepare($queryUpdate);
    $stmtUpdate->bind_param("issi", $stok_baru, $deskripsi, $status_stok, $id_barang);

    if ($stmtUpdate->execute()) {
        // Kembali ke halaman detail
        header("Location: ../admin-konfirmrestok.php?id=$id_menu&tipe=produk&pesan=sukses");
        exit;
    } else {
        echo "Gagal melakukan restok: " . $koneksi->error;
    }
} else {
    echo "Akses tidak diizinkan.";
}
?>