<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <title>Konfirmasi Order</title>
  <style>
    /* (Style identik dengan halaman sebelumnya) */
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
    .store-name, .store-address {
      font-size: 14px;
    }
    .store-rating img {
      width: 24px;
      height: 24px;
    }
    .order-details h4 {
      font-size: 14px;
      margin-bottom: 8px;
    }
    .buttons {
      display: flex;
      justify-content: space-between;
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      padding: 20px;
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
    <a href="keranjang.php"><i class="bi bi-arrow-left fs-4" style="color:white;"></i></a>
  </div>

  <div class="container total d-flex justify-content-between align-items-center">
    <div class="d-flex gap-2 align-items-center">
      <img src="images/shopping-cart.png" style="width: 25px; height: 25px;" />
      <span class="fw-bold">Total Bayar</span>
    </div>
    <div><span class="fw-bold" id="totalHarga">Rp 0</span></div>
  </div>

  <div class="content">
    <div class="store-info mb-2">
      <div class="d-flex justify-content-between align-items-center gap-2">
        <div>
          <div class="store-name fw-bold">Boemi Coffee</div>
          <div class="store-address">Jalan Nglangen Sari, Ngropoh, Condongcatur, Sleman, DIY</div>
        </div>
        <div class="store-rating">
          <img src="images/five.png" alt="Rating" style="width: 49px; height: 49px;" />
        </div>
      </div>
      <hr>
      <div class="mb-2 fw-bold">Nama Pemesan: <span id="namaPemesan"></span></div>
    </div>

    <div id="orderContainer" style="margin-bottom: 80px;"></div>

    <div class="buttons">
      <button class="cancel-btn" onclick="window.location.href='keranjang.php'">Cancel</button>
      <button class="continue-btn" onclick="simpanKeDatabase()">Bayar</button>
    </div>
  </div>

  <script>
    function formatRupiah(angka) {
      return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(angka);
    }

    function loadRekap() {
      const cart = JSON.parse(localStorage.getItem('cart') || '[]');
      const nama = localStorage.getItem('namaPemesan') || '-';
      const orderContainer = document.getElementById('orderContainer');
      const totalHargaEl = document.getElementById('totalHarga');
      const namaEl = document.getElementById('namaPemesan');
      orderContainer.innerHTML = '';
      namaEl.innerText = nama;

      let total = 0;

      const grouped = {};
      cart.forEach(item => {
        const tipe = item.tipe_pesanan || 'Dine In';
        if (!grouped[tipe]) grouped[tipe] = [];
        grouped[tipe].push(item);
      });

      for (const tipe in grouped) {
        orderContainer.innerHTML += `
          <hr>
          <div class="dine-in d-flex align-items-center gap-2">
            <img src="images/restaurant-cutlery-circular-symbol-of-a-spoon-and-a-fork-in-a-circle.png" alt="Icon" style="width: 24px; height: 24px;" />
            <span>${tipe}</span>
          </div>
          <hr>
          <h6 class="fw-bold mt-2">Rincian Order</h6>
        `;

        grouped[tipe].forEach(item => {
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
          `;
        });
      }

      totalHargaEl.innerText = formatRupiah(total);
    }

    function simpanKeDatabase() {
      const nama = localStorage.getItem('namaPemesan') || '';
      const cart = JSON.parse(localStorage.getItem('cart') || '[]');

      fetch('controller/simpanorder.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ nama, cart })
      }).then(res => res.json())
        .then(data => {
          if (data.success) {
            alert("Order berhasil disimpan!");
            localStorage.removeItem('cart');
            localStorage.removeItem('namaPemesan');
            window.location.href = 'customer-dashboard.php'; // BUKAN ke simpanorder.php
          } else {
            alert("Gagal menyimpan order: " + (data.error || 'Unknown error'));
          }
        })
        .catch(err => {
          alert("Terjadi kesalahan saat mengirim order");
          console.error(err);
        });
    }


    loadRekap();
  </script>
</body>
</html>
