<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $koneksi->real_escape_string($_POST['nama']);
    $harga = intval($_POST['harga']);
    $jumlah = intval($_POST['jumlah']);
    $kategori = $koneksi->real_escape_string($_POST['kategori']);
    $deskripsi = $koneksi->real_escape_string($_POST['deskripsi']);

    // Status stok berdasarkan jumlah
    if ($jumlah == 0) {
        $status = 'Habis';
    } elseif ($jumlah <= 2) {
        $status = 'Hampir habis';
    } else {
        $status = 'Tersedia';
    }

    // Gambar
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto_tmp = $_FILES['foto']['tmp_name'];
        $foto_data = file_get_contents($foto_tmp);
        $foto_data = $koneksi->real_escape_string($foto_data);
        $mime_type = $_FILES['foto']['type'];
    } else {
        echo "Upload foto gagal.";
        exit;
    }

    // Simpan ke tabel menu
    $query = "INSERT INTO menu (Nama_menu, Kategori_menu, harga, Jumlah_Stok, Status_Stok, foto_menu, tipe_mime, deskripsi)
              VALUES ('$nama', '$kategori', $harga, $jumlah, '$status', '$foto_data', '$mime_type', '$deskripsi')";

    if ($koneksi->query($query)) {
        header("Location: ../admin-konfirmtambahmenu.php?pesan=sukses");
        exit;
    } else {
        echo "Gagal menambahkan data: " . $koneksi->error;
    }
} else {
    echo "Akses tidak valid.";
}
?>
