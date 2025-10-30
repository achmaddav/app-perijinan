<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Web Perizinan & Cuti BPTU-Sembawa</title>

  <link rel="icon" type="image/png" href="assets/image/logo.png">
  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <!-- FontAwesome -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
  <style>
    body {
      background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding-left: 30px;
      padding-right: 30px;
    }

    .login-container {
      background: #ffffffee;
      padding: 35px 30px;
      border-radius: 12px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
      width: 100%;
      max-width: 400px;
      text-align: center;
      position: relative;
      animation: fadeIn 0.8s ease;
    }

    .login-container img {
      width: 70px;
      height: 70px;
      margin-bottom: 15px;
    }

    .login-container h3 {
      font-weight: 700;
      color: #1e3c72;
      margin-bottom: 25px;
    }

    .form-control {
      border-radius: 50px;
      padding-left: 15px;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      border-color: #1e3c72;
      box-shadow: 0 0 8px rgba(30, 60, 114, 0.3);
    }

    .input-group-text {
      background: transparent;
      border: none;
      cursor: pointer;
    }

    .btn-primary {
      border-radius: 50px;
      background: #1e3c72;
      border: none;
      transition: all 0.3s ease;
      font-weight: 600;
    }

    .btn-primary:hover {
      background: #16325c;
    }

    /* Animasi fadeIn */
    @keyframes fadeIn {
      0% {
        opacity: 0;
        transform: translateY(-15px);
      }

      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>
</head>

<body>
  <div class="login-container">
    <!-- Logo -->
    <img src="assets/image/logo.png" alt="Logo">
    <h3>Login</h3>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm auto-dismiss" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> <?= $_SESSION['error']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    <form action="/app-perijinan/authenticate" method="post">
      <div class="mb-3 input-group">
        <span class="input-group-text"><i class="fas fa-id-badge text-primary"></i></span>
        <input type="text" name="nip" class="form-control" placeholder="Masukkan NIP" required>
      </div>
      <div class="mb-3 input-group">
        <span class="input-group-text"><i class="fas fa-lock text-primary"></i></span>
        <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan Password" required>
        <button type="button" class="input-group-text" id="togglePassword">
          <i class="fas fa-eye-slash"></i>
        </button>
      </div>
      <button type="submit" class="btn btn-primary w-100 mt-3">Login</button>
    </form>
    <small class="text-muted d-block mt-3">&copy; <span id="currentYear"></span> BPTU Sembawa</small>
  </div>
  <script src="/app-perijinan/assets/js/site.js?v=<?= time(); ?>"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');

    togglePassword.addEventListener('click', function () {
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);

      const icon = this.querySelector('i');
      icon.classList.toggle('fa-eye');
      icon.classList.toggle('fa-eye-slash');
    });

    document.getElementById('currentYear').textContent = new Date().getFullYear();
  </script>
</body>

</html>