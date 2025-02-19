<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Perizinan Kantor</title>
  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <!-- FontAwesome (Untuk ikon mata) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
  <style>
    body {
      background-color: #f4f4f4;
      background-image: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
      color: #333;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .login-container {
      max-width: 400px;
      width: 100%;
      padding: 30px;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
    }
    .login-container h3 {
      margin-bottom: 20px;
      font-weight: bold;
      color: #1e3c72;
    }
    .input-group-text {
      background: transparent;
      border: none;
    }
    .form-control:focus {
      box-shadow: none;
      border-color: #1e3c72;
    }
    .btn-primary {
      background: #1e3c72;
      border: none;
    }
    .btn-primary:hover {
      background: #16325c;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h3 class="text-center">Login</h3>
    <form action="/app-perijinan/authenticate" method="post">
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" class="form-control" placeholder="Masukkan email" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <!-- Menggunakan input group untuk tombol show/hide password -->
        <div class="input-group">
          <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
          <span class="input-group-text">
            <i class="fas fa-eye" id="togglePassword" style="cursor: pointer;"></i>
          </span>
        </div>
      </div>
      <button type="submit" class="btn btn-primary w-100 mt-3">Login</button>
    </form>
  </div>

  <!-- JavaScript Bootstrap & Show Password -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');

    togglePassword.addEventListener('click', function () {
      // Toggle the type attribute using getAttribute() method
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);

      // Toggle the eye slash icon
      this.classList.toggle('fa-eye-slash');
    });
  </script>
</body>
</html>
