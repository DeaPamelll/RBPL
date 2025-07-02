<!-- Simpan ini sebagai login.php -->
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    .header {
      background-color: #252c3a;
      color: white;
      padding: 1rem;
      display: flex;
      align-items: center;
    }
    .login-container {
      max-width: 400px;
      margin: auto;
      background-color: white;
      padding: 2rem;
      border-radius: 10px;
      margin-top: 2rem;
    }
    .btn-dark {
      background-color: #252c3a;
      border: none;
    }
    .btn-dark:hover {
      background-color: #1b202c;
    }
    .forgot-password {
      text-align: center;
      font-size: 14px;
      margin-top: 10px;
      text-decoration: underline;
      font-weight: bold;
    }
    .alert {
      margin: 1rem auto 0;
      max-width: 400px;
    }
  </style>
</head>
<body>
  <div class="header d-flex align-items-center px-3">
    <div class="container py-4">
      <i class="bi bi-arrow-left fs-4 me-2"></i>
    </div>
  </div>

  <!-- Tampilkan pesan error jika ada -->
  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger text-center mt-0">
      <?= htmlspecialchars($_SESSION['error']) ?>
    </div>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  <div class="login-container">
    <h3 class="mb-4 fw-bold">Log in</h3>
    <form action="controller/loginproses.php" method="post">
      <div class="mb-3">
        <label for="username" class="form-label fw-bold">Username</label>
        <input type="text" class="form-control" id="username" name="username" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label fw-bold">Password</label>
        <div class="input-group">
          <input type="password" name="password" id="myInput" class="form-control" required>
          <span class="input-group-text" onclick="pwlook()">
            <i class="bi bi-eye-fill"></i>
          </span>
        </div>
      </div>
      <button type="submit" class="btn btn-dark w-100 mt-3">Log in</button>
    </form>
    <div class="forgot-password">
      <a href="#" style="color: black;">Lupa password?</a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function pwlook() {
      var x = document.getElementById("myInput");
      x.type = x.type === "password" ? "text" : "password";
    }
  </script>
</body>
</html>
