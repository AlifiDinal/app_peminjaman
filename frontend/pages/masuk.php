<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Masuk - Inventaris App</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="icon" href="../templates_users/assets/img/logo.png" type="image/x-icon" />
  <style>
    :root {
      --primary: #4e73df;
      --secondary: #1cc88a;
      --dark: #2e384d;
      --light: #f8f9fc;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      overflow: hidden;
    }

    .login-container {
      position: relative;
      width: 100%;
      max-width: 380px;
      padding: 15px;
    }

    .login-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      border-radius: 16px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      border: 1px solid rgba(255, 255, 255, 0.2);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .login-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    }

    .card-header {
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      padding: 2rem 1.5rem 1.5rem;
      text-align: center;
      position: relative;
    }

    .logo-wrapper {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 12px;
    }

    .logo-circle {
      width: 80px;
      height: 80px;
      background: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      transition: transform 0.3s ease;
    }

    .logo-circle:hover {
      transform: scale(1.05);
    }

    .logo-circle img {
      width: 90px;
      height: 90px;
      object-fit: contain;
      border-radius: 90%;
    }

    .app-title {
      color: white;
      font-size: 1.3rem;
      font-weight: 700;
      margin: 0;
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .card-body {
      padding: 2rem 1.5rem;
    }

    .login-title {
      color: var(--dark);
      font-size: 1.3rem;
      font-weight: 600;
      margin-bottom: 0.4rem;
      text-align: center;
    }

    .login-subtitle {
      color: #6c757d;
      text-align: center;
      margin-bottom: 1.5rem;
      font-size: 0.85rem;
    }

    .form-group {
      margin-bottom: 1.2rem;
      position: relative;
    }

    .form-label {
      font-weight: 500;
      color: var(--dark);
      margin-bottom: 0.4rem;
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 0.9rem;
    }

    .form-label i {
      color: var(--primary);
      width: 14px;
      font-size: 0.9rem;
    }

    .form-control {
      border: 2px solid #e3e6f0;
      border-radius: 10px;
      padding: 0.65rem 0.9rem;
      font-size: 0.9rem;
      transition: all 0.3s ease;
      background: #fff;
      height: 45px;
    }

    .form-control:focus {
      border-color: var(--primary);
      box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.15);
      background: #fff;
    }

    .btn-login {
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      border: none;
      border-radius: 10px;
      padding: 0.65rem;
      font-weight: 600;
      font-size: 0.95rem;
      color: white;
      transition: all 0.3s ease;
      box-shadow: 0 3px 12px rgba(78, 115, 223, 0.3);
      margin-top: 0.3rem;
      height: 45px;
      cursor: pointer;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(78, 115, 223, 0.4);
      color: white;
    }

    .btn-login:active {
      transform: translateY(0);
    }

    .floating-bubbles {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
      overflow: hidden;
    }

    .bubble {
      position: absolute;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.1);
      animation: float 15s infinite linear;
    }

    @keyframes float {
      0% {
        transform: translateY(100vh) rotate(0deg);
        opacity: 0;
      }
      10% {
        opacity: 0.5;
      }
      90% {
        opacity: 0.5;
      }
      100% {
        transform: translateY(-100px) rotate(360deg);
        opacity: 0;
      }
    }

    /* Responsive */
    @media (max-width: 480px) {
      .login-container {
        padding: 10px;
        max-width: 340px;
      }
      .card-body {
        padding: 1.5rem 1.2rem;
      }
      .card-header {
        padding: 1.5rem 1.2rem 1.2rem;
      }
      .logo-circle {
        width: 70px;
        height: 70px;
      }
      .logo-circle img {
        width: 45px;
        height: 45px;
      }
      .app-title {
        font-size: 1.2rem;
      }
    }

    @media (max-width: 360px) {
      .login-container {
        max-width: 320px;
      }
      .card-body {
        padding: 1.2rem 1rem;
      }
      .logo-circle {
        width: 65px;
        height: 65px;
      }
      .logo-circle img {
        width: 40px;
        height: 40px;
      }
    }
  </style>
</head>

<body>
  <!-- Floating Background Bubbles -->
  <div class="floating-bubbles">
    <?php for($i = 0; $i < 12; $i++): ?>
      <div class="bubble" style="
        width: <?= rand(30, 80) ?>px;
        height: <?= rand(30, 80) ?>px;
        left: <?= rand(0, 100) ?>%;
        animation-delay: <?= rand(0, 15) ?>s;
        animation-duration: <?= rand(10, 25) ?>s;
      "></div>
    <?php endfor; ?>
  </div>

  <div class="login-container">
    <div class="card login-card">
      <!-- Header dengan Logo -->
      <div class="card-header">
        <div class="logo-wrapper">
          <div class="logo-circle">
            <img src="../templates_users/assets/img/logo.png" alt="Logo Inventaris">
          </div>
          <h1 class="app-title">Inventaris App</h1>
        </div>
      </div>

      <!-- Form Login -->
      <div class="card-body">
        <h2 class="login-title">Masuk ke Akun</h2>
        <p class="login-subtitle">Silakan masuk dengan kredensial Anda</p>

        <form action="../action/masuk.php" method="POST" autocomplete="off">
          <div class="form-group">
            <label for="nama_pegawai" class="form-label">
              <i class="fas fa-user"></i> Nama
            </label>
            <input id="nama_pegawai" type="text" name="nama_pegawai" class="form-control" placeholder="Masukkan nama..." required>
          </div>

          <div class="form-group">
            <label for="nip" class="form-label">
              <i class="fas fa-id-card"></i> NIP
            </label>
            <input id="nip" type="text" name="nip" class="form-control" placeholder="Masukkan NIP..." required autocomplete="off">
          </div>

          <button type="submit" name="login" class="btn btn-login w-100">
            <i class="fas fa-sign-in-alt me-2"></i> Masuk
          </button>
        </form>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const inputs = document.querySelectorAll('.form-control');
      inputs.forEach(input => {
        input.addEventListener('focus', function() {
          this.parentElement.classList.add('focused');
        });
        input.addEventListener('blur', function() {
          if (this.value === '') {
            this.parentElement.classList.remove('focused');
          }
        });
        if (input.value !== '') {
          input.parentElement.classList.add('focused');
        }
      });
    });
  </script>
</body>
</html>
