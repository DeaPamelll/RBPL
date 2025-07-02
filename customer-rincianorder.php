<!-- keranjang.php (versi dinamis dari localStorage) -->
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <title>Order Summary</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: "Arial", sans-serif;
    }
    body {
      background-color: #ffffff;
      width: 390px;
      height: 844px;
      margin: 0 auto;
    }
    .header {
      background-color: #252c3a;
      color: white;
      height: 110px;
      font-size: 1rem;
      display: flex;
      align-items: center;
    }
    .total {
      background-color: #E3E3E3;
      height: 65px;
      font-size: 1rem;
      display: flex;
      align-items: center;
    }
    .content {
      background: white;
      padding: 16px;
    }
    .store-info {
      margin-bottom: 16px;
    }
    .store-name {
      font-weight: bold;
      font-size: 16px;
      margin-bottom: 4px;
    }
    .store-address {
      font-size: 12px;
      color: #555;
      margin-bottom: 8px;
    }
    .store-rating img {
      width: 24px;
      height: 24px;
    }
    .dine-in {
      display: flex;
      align-items: center;
      gap: 8px;
      margin: 16px 0;
      font-size: 14px;
      font-weight: 500;
    }
    .dine-in img {
      width: 24px;
      height: 24px;
    }
    .order-details {
      padding-top: 16px;
    }
    .order-details h4 {
      font-size: 14px;
      margin-bottom: 8px;
    }
    .order-item {
      display: flex;
      justify-content: space-between;
      font-size: 14px;
      margin-bottom: 4px;
    }
    .order-modifier {
      font-size: 12px;
      color: #555;
      margin-left: 16px;
    }
    .quantity-control {
      display: flex;
      justify-content: end;
      align-items: center;
      gap: 12px;
      margin: 12px 0;
    }
    .quantity-control button {
      width: 28px;
      height: 28px;
      border-radius: 10px;
      border: 1px solid #999;
      background: white;
      font-size: 18px;
      cursor: pointer;
    }
    .buttons {
      display: flex;
      justify-content: space-between;
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      padding: 20px;
      margin-top: 16px;
      background-color: white;
    }
    .buttons button {
      width: 48%;
      height: 44px;
      border-radius: 10px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
    }
    .cancel-btn {
      background: white;
      border: 1px solid #333;
      color: #333;
    }
    .continue-btn {
      background: #2d3241;
      color: white;
    }
  </style>
</head>
<body>
  <div class="header px-3">
    <a href="customer-dashboard.php"><i class="bi bi-arrow-left fs-4" style="color:white;"></i></a>
  </div>

  <div class="container total d-flex justify-content-between align-items-center">
    <div class="d-flex gap-2 align-items-center">
      <div class="keranjang">
        <img src="images/shopping-cart.png" alt="keranjang" style="width: 25px; height: 25px;" />
      </div>
      <span class="fw-bold">Total Bayar</span>
    </div>
    <div>
      <span class="fw-bold" id="totalHarga">Rp 0</span>
    </div>
  </div>

  <div class="content">
    <div class="store-info">
      <div class="d-flex justify-content-between align-items-center gap-2">
        <div>
          <div class="store-name">Boemi Coffee</div>
          <div class="store-address">
            Jalan Nglangen Sari, Ngropoh, Condongcatur, Kec. Depok, Sleman, DIY
          </div>
        </div>
        <div class="store-rating">
          <img src="images/five.png" alt="Rating" style="width: 49px; height: 49px;"/>
        </div>
      </div>
      <hr>
      <div class="customer-name mt-2">
            <label for="namaPemesan" class="form-label" style="font-size: 14px; font-weight: bold;">Nama Pemesan:</label>
            <input type="text" id="namaPemesan" class="form-control" placeholder="Masukkan nama Anda" required>
          </div>
    </div>

    <div id="orderContainer" style="margin-bottom: 80px;"></div>

    <div class="buttons">
      <button class="cancel-btn" onclick="clearCart()">Cancel</button>
      <button class="continue-btn" onclick="lanjutkanPesanan()">Lanjut</button>
    </div>
  </div>

  <script>
    function formatRupiah(angka) {
      return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(angka);
    }

    function renderCart() {
      const cart = JSON.parse(localStorage.getItem('cart') || '[]');
      const orderContainer = document.getElementById('orderContainer');
      const totalHargaEl = document.getElementById('totalHarga');
      orderContainer.innerHTML = '';
      let total = 0;

      // Kelompokkan item berdasarkan tipe_pesanan
      const grouped = {};
      cart.forEach(item => {
        const tipe = item.tipe_pesanan || 'Dine In';
        if (!grouped[tipe]) grouped[tipe] = [];
        grouped[tipe].push(item);
      });

      // Tampilkan tiap grup
      for (const tipe in grouped) {
        orderContainer.innerHTML += `
          <hr>
          <div class="dine-in">
            <img src="images/restaurant-cutlery-circular-symbol-of-a-spoon-and-a-fork-in-a-circle.png" alt="Icon" style="width: 40px; height: 40px;" />
            <span>${tipe}</span>
          </div>
          <hr>
          <h6 class="fw-bold">Rincian Order</h6>
        `;

        grouped[tipe].forEach((item, index) => {
          total += item.harga * item.jumlah;
          orderContainer.innerHTML += `
            <div class="order-details">
              <div class="row">
                <div class="col-2 fw-bold">${item.jumlah}x</div>
                <div class="col-6">${item.nama_menu}</div>
                <div class="col-4 text-end">${formatRupiah(item.harga * item.jumlah)}</div>
              </div>
              ${item.varian ? `<div class="row"><div class="col-2"></div><div class="col-6">${item.varian}</div><div class="col-4 text-end">Rp 0</div></div>` : ''}
              ${item.ice_level ? `<div class="row"><div class="col-2"></div><div class="col-6">${item.ice_level}</div><div class="col-4 text-end">Rp 0</div></div>` : ''}
              ${item.sugar_level ? `<div class="row"><div class="col-2"></div><div class="col-6">${item.sugar_level}</div><div class="col-4 text-end">Rp 0</div></div>` : ''}
            </div>
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <span onclick="removeItem(${index})" style="color: #dc3545; cursor: pointer; font-size: 13px;">Hapus</span>
              </div>
              <div class="quantity-control">
                <button onclick="updateQty(${index}, -1)">-</button>
                <span>${item.jumlah}</span>
                <button onclick="updateQty(${index}, 1)">+</button>
              </div>
            </div>
          `;
        });
      }

      totalHargaEl.innerText = formatRupiah(total);
    }



    function updateQty(index, change) {
      const cart = JSON.parse(localStorage.getItem('cart') || '[]');
      if (!cart[index]) return;
      cart[index].jumlah += change;
      if (cart[index].jumlah <= 0) cart.splice(index, 1);
      localStorage.setItem('cart', JSON.stringify(cart));
      renderCart();
    }

    function removeItem(index) {
      const cart = JSON.parse(localStorage.getItem('cart') || '[]');
      cart.splice(index, 1);
      localStorage.setItem('cart', JSON.stringify(cart));
      renderCart();
    }

    function lanjutkanPesanan() {
      const nama = document.getElementById('namaPemesan').value.trim();
      if (nama === '') {
        alert('Nama pemesan wajib diisi!');
        document.getElementById('namaPemesan').focus();
        return;
      }

      // Simpan nama ke localStorage (jika perlu diakses di halaman berikutnya)
      localStorage.setItem('namaPemesan', nama);

      // Redirect ke halaman konfirmasi
      window.location.href = 'customer-konfirmasiorder.php';
    }

    function clearCart() {
      if (confirm("Yakin ingin menghapus semua item di keranjang?")) {
        localStorage.removeItem('cart');
        renderCart();
        window.location.href = 'customer-dashboard.php';
      }
    }

    renderCart();
  </script>
</body>
</html>
