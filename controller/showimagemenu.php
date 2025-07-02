<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $koneksi->prepare("SELECT foto_menu, tipe_mime FROM menu WHERE ID_Menu = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Penting! Bersihkan output buffer
        if (ob_get_length()) ob_end_clean();

        // Set header agar browser tahu ini gambar
        header("Content-Type: " . $row['tipe_mime']);
        header("Content-Length: " . strlen($row['foto_menu']));

        // Tampilkan binari gambar
        echo $row['foto_menu'];
        exit;
    }
}

http_response_code(404);
exit("Gambar tidak ditemukan.");
