<main class="main">

  <!-- Hero Section -->
  <section id="hero" class="hero section position-relative overflow-hidden">

    <!-- Background Image -->
    <img src="./templates_users/assets/img/perpus.png" 
         alt="Background SMK" 
         class="hero-bg" 
         data-aos="fade-in">

    <div class="container position-relative z-2">
      <div class="row align-items-center">
        <div class="col-lg-7 text-white">
          <h1 class="display-5 fw-bold mb-3" data-aos="fade-up" data-aos-delay="100">
            Aplikasi Inventaris<br>Sarana dan Prasarana
          </h1>
          <p class="lead text-light mb-4" data-aos="fade-up" data-aos-delay="200">
            Sistem digital untuk mengelola data Pinjam/Peminjaman sekolah agar lebih cepat dan akurat.
          </p>
          <div class="d-flex gap-3" data-aos="fade-up" data-aos-delay="300">
            <a href="#barang" class="btn btn-warning text-white fw-semibold px-4 py-2 rounded-pill shadow-sm">
              <i class="bi bi-box-seam me-2"></i> Lihat Barang
            </a>
            <div class="profile-username mt-1">
              <span class="fw-semibold text-white-50 small">
                <?= isset($_SESSION['nama_users']) ? htmlspecialchars($_SESSION['nama_users']) : '' ?>
              </span>
            </div>
          </div>
        </div>

        <!-- Gambar ilustrasi di sisi kanan -->
        <div class="col-lg-5 d-none d-lg-block text-center" data-aos="zoom-in" data-aos-delay="200">
          <img src="./templates_users/assets/img/hero-illustration.png" 
               alt="Ilustrasi Inventaris Sekolah" 
               class="img-fluid hero-illustration">
        </div>
      </div>
    </div>

    <!-- Overlay untuk efek gelap -->
    <div class="hero-overlay"></div>

  </section>
  <!-- /Hero Section -->

</main>

<!-- Custom Styles -->
<style>
  .hero {
    position: relative;
    min-height: 100vh;
    display: flex;
    align-items: center;
    background: #00184d;
    color: white;
  }

  .hero-bg {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: brightness(0.4);
    z-index: 1;
  }

  .hero-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 40, 0.65);
    z-index: 1;
  }

  .hero .container {
    position: relative;
    z-index: 2;
  }

  .hero h1 {
    font-size: 2.8rem;
    line-height: 1.3;
  }

  .hero p {
    font-size: 1.1rem;
    max-width: 520px;
  }

  .hero .btn-warning {
    background: linear-gradient(135deg, #0059ff, #001aff);
    border: none;
    transition: 0.3s;
  }

  .hero .btn-outline-light:hover {
    background: white;
    color: #00184d;
  }

  .hero-illustration {
    max-width: 85%;
    animation: float 4s ease-in-out infinite;
  }

  @keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
  }

  @media (max-width: 991px) {
    .hero {
      text-align: center;
      padding-top: 120px;
    }
    .hero h1 {
      font-size: 2rem;
    }
    .hero p {
      margin: 0 auto;
    }
    .hero .btn {
      width: 100%;
    }
  }
</style>
