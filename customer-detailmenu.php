<?php
include 'controller/koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
  die("ID menu tidak valid.");
}

$query = "SELECT * FROM menu WHERE ID_menu = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$menu = $result->fetch_assoc();

if (!$menu) {
  die("Menu tidak ditemukan.");
}

function format_rupiah($angka) {
  return "Rp " . number_format($angka, 0, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Detail Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    body {
      background-color: #fff;
      max-width: 390px;
      margin: 0 auto;
      font-family: 'Segoe UI', sans-serif;
    }
    .header {
      background-color: #282e3b;
      color: white;
      padding: 1rem;
      height: 110px;
      display: flex;
      align-items: center;
    }
    .product-image img {
      width: 100%;
      height: auto;
      object-fit: contain;
      padding: 5px;
    }
    .variant-btn {
      border-radius: 20px;
      padding: 5px 20px;
    }
    .note-pill {
      border-radius: 20px;
      padding: 4px 12px;
      font-size: 13px;
    }
    .continue-btn {
      background-color: #252c3a;
      color: white;
      border: none;
      border-radius: 8px;
      width: 100%;
      padding: 12px;
      font-weight: 600;
    }
    .form-section-title {
      font-weight: bold;
      margin-bottom: 4px;
    }
    .form-section-subtitle {
      font-size: 12px;
      margin-bottom: 8px;
    }
    .dotted-hr {
      border: none;
      border-top: 3px dotted #000000;
      margin-bottom: 10px;
      margin-top: 10px;
    }
    .modal-content {
      margin: 0 auto;
      border-radius: 50px;
      padding: 24px;
      width: 300px;
    }
    .modal-content h5 {
      font-size: 18px;
      font-weight: bold;
    }
    .modal-content p {
      font-size: 14px;
      color: #555;
    }
  </style>
</head>
<body>

<div class="header px-3">
  <a href="customer-dashboard.php" class="text-white text-decoration-none">
    <i class="bi bi-arrow-left fs-4"></i>
  </a>
</div>

<div class="product-image">
  <img src="data:<?php echo $menu['tipe_mime']; ?>;base64,<?php echo base64_encode($menu['foto_menu']); ?>" alt="<?php echo htmlspecialchars($menu['Nama_menu']); ?>" style="height: 280px;">
</div>

<div class="p-3">
  <h5 class="fw-bold mb-1" style="font-size: 24px;"><?php echo htmlspecialchars($menu['Nama_menu']); ?></h5>
  <div class="mb-2">
    <span class="fw-bold"><?php echo format_rupiah($menu['harga']); ?></span>
    &middot;
    <span class="text-success fw-bold">
      <?php echo $menu['Jumlah_Stok'] > 0 ? 'Stok tersedia' : 'Stok habis'; ?>
    </span>
  </div>
  <hr>
  <?php if (!empty($menu['deskripsi'])): ?>
    <p class="text-muted small" style="text-align: justify;">
      <?php echo htmlspecialchars($menu['deskripsi']); ?>
    </p>
  <?php else: ?>
    <p class="text-muted small fst-italic" style="text-align: justify; color: #888;">
      Belum ada deskripsi untuk menu ini.
    </p>
  <?php endif; ?>

  <hr>

  <div id="opsiMenu"></div>

  <div>
    <div class="form-section-title" style="margin-top: 20px;">Notes</div>
    <div class="fw-bold text-black mb-3" style="font-size: 12px;">Required</div>
      <button class="btn btn-outline-dark note-pill fw-bold" onclick="setTipePesanan('Dine in')" style="width: 85px; height: 30px; font-size: 13px; padding: 5px;">Dine in</button>
      <button class="btn btn-outline-dark note-pill fw-bold" onclick="setTipePesanan('Take Away')" style="width: 85px; height: 30px; font-size: 13px; padding: 5px;">Take Away</button>
    </div>

  <div style="margin-top: 60px;">
    <button class="continue-btn" id="btnLanjut">Lanjut</button>
  </div>
</div>

<!-- Modal Pop-up -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center p-4 rounded-4">
      <h5 class="fw-bold mb-2">Berhasil Ditambah</h5>
      <p class="text-muted">Produk telah ditambah ke keranjang</p>
      <div class="d-flex justify-content-center gap-3 mt-3">
        <a href="customer-dashboard.php" class="btn btn-outline-dark" style="font-size: 13px; border-radius:23px;">Belanja lagi</a>
        <a href="customer-rincianorder.php" class="btn btn-dark" style="font-size: 13px; border-radius:23px;">Lihat keranjang</a>
      </div>
    </div>
  </div>
</div>

<script>
  const kategori = <?php echo json_encode($menu['Kategori_menu']); ?>;
  const opsiMenu = document.getElementById("opsiMenu");

  if (kategori === 'Espresso Based' || kategori === 'Signature') {
    opsiMenu.innerHTML = `
      <div>
        <div class="form-section-title">Pilih Varian</div>
        <div class="form-section-subtitle"><span style="font-weight: bold;">Harus dipilih</span> <span class="fw-bold" style="color: grey;">| Pilih 1</span></div>
        <div class="d-flex mb-2 gap-2 mt-4 mb-4" role="group" aria-label="Varian">
            <input type="radio" class="btn-check" name="varian" id="ice" value="Ice" autocomplete="off">
            <label class="btn btn-outline-dark variant-btn fw-bold" for="ice">Ice</label>
            <input type="radio" class="btn-check" name="varian" id="hot" value="Hot" autocomplete="off">
            <label class="btn btn-outline-dark variant-btn fw-bold" for="hot">Hot</label>
        </div>
      </div>

      <div id="iceLevelContainer">
        <span class="form-section-title">Ice Level</span>
        <span class="form-section-subtitle text-muted" style="font-size: 12px; font-weight:bold;">| Pilih 1</span>
        <hr class="dotted-hr">
        <div class="form-check d-flex justify-content-between align-items-center">
          <label class="form-check-label" for="iceLess">Less Ice <small class="text-muted ms-2">Gratis</small></label>
          <input class="form-check-input" type="radio" name="ice_level" id="iceLess" value="Less Ice">
        </div>
        <hr class="dotted-hr">
        <div class="form-check d-flex justify-content-between align-items-center">
          <label class="form-check-label" for="iceNormal">Normal Ice<small class="text-muted ms-2">Gratis</small></label>
          <input class="form-check-input" type="radio" name="ice_level" id="iceNormal" value="Normal Ice">
        </div>
        <hr class="dotted-hr">
        <div class="form-check d-flex justify-content-between align-items-center">
          <label class="form-check-label" for="iceExtra">Extra Ice <small class="text-muted ms-2">Gratis</small></label>
          <input class="form-check-input" type="radio" name="ice_level" id="iceExtra" value="Extra Ice">
        </div>
      </div>

      <div class="mt-4">
        <span class="form-section-title">Sugar Level</span>
        <span class="form-section-subtitle text-muted" style="font-size: 12px; font-weight:bold;">| Pilih 1</span>
        <hr class="dotted-hr">
        <div class="form-check d-flex justify-content-between align-items-center">
          <label class="form-check-label" for="sugarLess">Less Sugar <small class="text-muted ms-2">Gratis</small></label>
          <input class="form-check-input" type="radio" name="sugar_level" id="sugarLess" value="Less Sugar">
        </div>
        <hr class="dotted-hr">
        <div class="form-check d-flex justify-content-between align-items-center">
          <label class="form-check-label" for="sugarNormal">Normal Sugar<small class="text-muted ms-2">Gratis</small></label>
          <input class="form-check-input" type="radio" name="sugar_level" id="sugarNormal" value="Normal Sugar">
        </div>
        <hr class="dotted-hr">
        <div class="form-check d-flex justify-content-between align-items-center">
          <label class="form-check-label" for="sugarExtra">Extra Sugar <small class="text-muted ms-2">Gratis</small></label>
          <input class="form-check-input" type="radio" name="sugar_level" id="sugarExtra" value="Extra Sugar">
        </div>
      </div>
    `;
  }

  let tipePesanan = "";
  function setTipePesanan(val) {
    tipePesanan = val;
  }

  document.getElementById('btnLanjut').addEventListener('click', function () {
    const selectedVarian = document.querySelector('input[name="varian"]:checked')?.value || null;
    const selectedIce = document.querySelector('input[name="ice_level"]:checked')?.value || null;
    const selectedSugar = document.querySelector('input[name="sugar_level"]:checked')?.value || null;

    const item = {
      id_menu: <?php echo $menu['ID_menu']; ?>,
      nama_menu: <?php echo json_encode($menu['Nama_menu']); ?>,
      harga: <?php echo $menu['harga']; ?>,
      varian: selectedVarian,
      ice_level: selectedIce,
      sugar_level: selectedSugar,
      tipe_pesanan: tipePesanan,
      jumlah: 1
    };

    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    cart.push(item);
    localStorage.setItem('cart', JSON.stringify(cart));

    const modal = new bootstrap.Modal(document.getElementById('successModal'));
    modal.show();
  });

  document.querySelectorAll('input[name="varian"]').forEach(function(el) {
    el.addEventListener('change', function () {
      const selected = this.value;
      const iceContainer = document.getElementById('iceLevelContainer');
      if (selected === 'Hot') {
        iceContainer.style.display = 'none';
        document.querySelectorAll('input[name="ice_level"]').forEach(e => e.checked = false);
      } else {
        iceContainer.style.display = 'block';
      }
    });
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
