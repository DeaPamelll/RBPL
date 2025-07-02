<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $koneksi->prepare("SELECT foto_barang, tipe_mime FROM barang WHERE ID_Barang = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Penting! Bersihkan output buffer
        if (ob_get_length()) ob_end_clean();

        // Set header agar browser tahu ini gambar
        header("Content-Type: " . $row['tipe_mime']);
        header("Content-Length: " . strlen($row['foto_barang']));

        // Tampilkan binari gambar
        echo $row['foto_barang'];
        exit;
    }
}

http_response_code(404);
exit("Gambar tidak ditemukan.");
