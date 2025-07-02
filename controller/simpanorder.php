<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Gunakan metode POST']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['nama']) || !isset($data['cart'])) {
    echo json_encode(['error' => 'Data tidak lengkap']);
    exit;
}

$nama = $data['nama'];
$cart = $data['cart'];

if (empty($cart)) {
    echo json_encode(['error' => 'Keranjang kosong']);
    exit;
}

$conn = new mysqli("localhost", "root", "", "cafe");

if ($conn->connect_error) {
    echo json_encode(['error' => 'Koneksi gagal: ' . $conn->connect_error]);
    exit;
}

$conn->begin_transaction();

try {
    // 1. Simpan ke tabel pelanggan (cek dulu apakah sudah ada)
    $stmt = $conn->prepare("SELECT ID_Pelanggan FROM pelanggan WHERE Nama = ?");
    $stmt->bind_param("s", $nama);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $id_pelanggan = $row['ID_Pelanggan'];
    } else {
        $stmt = $conn->prepare("INSERT INTO pelanggan (Nama) VALUES (?)");
        $stmt->bind_param("s", $nama);
        $stmt->execute();
        $id_pelanggan = $stmt->insert_id;
    }

    // 2. Hitung total harga
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['harga'] * $item['jumlah'];
    }

    $tanggal = date("Y-m-d H:i:s");
    $status = "Menunggu Pembayaran";

    // 3. Simpan ke tabel pesanan
    $stmt = $conn->prepare("INSERT INTO pesanan (ID_Pelanggan, Total_Harga, Tanggal, status_pesanan) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("idss", $id_pelanggan, $total, $tanggal, $status);
    $stmt->execute();
    $id_pesanan = $stmt->insert_id;

    // 4. Simpan ke detail_pesanan
    $stmt = $conn->prepare("INSERT INTO detail_pesanan 
        (ID_Pesanan, ID_menu, Jumlah, Harga_Satuan, Subtotal, Varian, Tipe_Pesanan, Ice_Level, Sugar_Level)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    foreach ($cart as $item) {
        $subtotal = $item['harga'] * $item['jumlah'];
        $stmt->bind_param(
            "iiiddssss",
            $id_pesanan,
            $item['id_menu'],
            $item['jumlah'],
            $item['harga'],
            $subtotal,
            $item['varian'],
            $item['tipe_pesanan'],
            $item['ice_level'],
            $item['sugar_level']
        );
        $stmt->execute();
    }

    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Order berhasil disimpan']);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['error' => 'Gagal menyimpan order: ' . $e->getMessage()]);
}

$conn->close();
?>
