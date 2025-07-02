<?php
include "koneksi.php";

$tanggal = $_GET['tanggal'] ?? date('Y-m-d');

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="laporan_penjualan_'.$tanggal.'.csv"');

$output = fopen('php://output', 'w');

// Header kolom
fputcsv($output, ['Tanggal', 'Nama Pelanggan', 'Menu', 'Jumlah', 'Subtotal']);

// Ambil data dari database
$query = mysqli_query($koneksi, "
  SELECT 
    p.Tanggal,
    pel.Nama,
    GROUP_CONCAT(m.Nama_menu SEPARATOR ', ') AS DaftarMenu,
    SUM(dp.Jumlah) AS TotalJumlah,
    SUM(dp.Subtotal) AS TotalSubtotal
  FROM pesanan p
  JOIN pelanggan pel ON p.ID_Pelanggan = pel.ID_Pelanggan
  JOIN detail_pesanan dp ON p.ID_Pesanan = dp.ID_Pesanan
  JOIN menu m ON dp.ID_Menu = m.ID_Menu
  WHERE DATE(p.Tanggal) = '$tanggal'
  GROUP BY p.ID_Pesanan
  ORDER BY p.Tanggal DESC
");

while ($row = mysqli_fetch_assoc($query)) {
    fputcsv($output, [
        $row['Tanggal'],
        $row['Nama'],
        $row['DaftarMenu'],
        $row['TotalJumlah'],
        $row['TotalSubtotal']
    ]);
}

fclose($output);
exit;
?>
