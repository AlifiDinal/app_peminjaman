<?php
include __DIR__ . '/../../config/conection.php';

// Ambil keyword pencarian dari input
$keyword = $_GET['keyword'] ?? '';
$safeKeyword = mysqli_real_escape_string($connect, $keyword);

// Cek apakah user sudah login
$isLoggedIn = isset($_SESSION['id_users']);
$idUsers = $isLoggedIn ? $_SESSION['id_users'] : null;

// Query data barang (jenis)
$qJenis = "SELECT * FROM jenis";
if (!empty($keyword)) {
    $qJenis .= " WHERE nama_jenis LIKE '%$safeKeyword%' OR keterangan LIKE '%$safeKeyword%'";
}
$qJenis .= " LIMIT 20";
$resultJenis = mysqli_query($connect, $qJenis);

if (!$resultJenis) {
    die("Query error: " . mysqli_error($connect));
}

// Jika user login, cek peminjaman aktif untuk setiap jenis barang
$activeLoans = [];
if ($isLoggedIn) {
    $qActiveLoans = "SELECT kode_jenis FROM peminjaman 
                     WHERE id_users = $idUsers 
                     AND status_peminjaman = 'Dipinjam'";
    $resultActive = mysqli_query($connect, $qActiveLoans);
    while ($loan = mysqli_fetch_object($resultActive)) {
        $activeLoans[] = $loan->kode_jenis;
    }
}
?>

<!-- Barang Section -->
<section id="barang" class="team section section-bg py-5" style="background-color: #f8f9fa;">
  <div class="container" data-aos="fade-up">

    <!-- Section Title -->
    <div class="text-center mb-4">
      <h2 class="fw-bold text-dark mb-2">
        <i class="bi bi-box-seam me-2"></i>DAFTAR BARANG
      </h2>
      <div class="line mx-auto mb-3"></div>
      <p class="text-muted">Temukan berbagai jenis barang yang tersedia untuk dipinjam</p>
    </div>

    <!-- Form Pencarian -->
    <div class="text-center mb-5">
      <form method="get" action="" class="d-flex justify-content-center align-items-center">
        <input type="text" name="keyword" 
               placeholder="Cari nama atau keterangan barang..." 
               class="form-control w-50 me-2 shadow-sm"
               value="<?= htmlspecialchars($keyword) ?>">
        <button type="submit" class="btn btn-primary px-3">
          <i class="bi bi-search"></i> Cari
        </button>
      </form>
    </div>

    <!-- Daftar Barang -->
    <div class="row gy-4 justify-content-center">

      <?php if (mysqli_num_rows($resultJenis) > 0): ?>
        <?php while ($item = mysqli_fetch_object($resultJenis)): 
          // Cek apakah user sedang meminjam barang ini
          $isCurrentlyBorrowed = in_array($item->kode_jenis, $activeLoans);
        ?>
          <div class="col-xl-3 col-lg-4 col-md-6 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="100">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden w-100 hover-card">
              
              <!-- Gambar Barang -->
              <div class="position-relative">
                <img 
                  src="../storages/jenis/<?= htmlspecialchars($item->image ?: 'no-image.png') ?>" 
                  class="card-img-top" 
                  alt="<?= htmlspecialchars($item->nama_jenis) ?>" 
                  style="height: 220px; object-fit: cover;"
                >
                <div class="position-absolute top-0 end-0 m-2">
                  <span class="badge bg-primary shadow-sm px-3 py-2">
                    #<?= htmlspecialchars($item->kode_jenis ?? '-') ?>
                  </span>
                </div>
                <!-- Badge status jika sedang dipinjam -->
                <?php if ($isLoggedIn && $isCurrentlyBorrowed): ?>
                  <div class="position-absolute bottom-0 start-0 m-2">
                    <span class="badge bg-warning text-dark shadow-sm px-3 py-2">
                      <i class="bi bi-clock-history me-1"></i> Sedang Dipinjam
                    </span>
                  </div>
                <?php endif; ?>
              </div>

              <!-- Info Barang -->
              <div class="card-body text-center p-4">
                <h5 class="fw-bold text-dark mb-2">
                  <?= htmlspecialchars($item->nama_jenis) ?>
                </h5>
                <p class="text-muted small mb-3">
                  <?= htmlspecialchars($item->keterangan ?: 'Tidak ada keterangan') ?>
                </p>

                <!-- Tombol Aksi Dinamis -->
                <div class="d-flex justify-content-center gap-2">

                  <?php if (!$isLoggedIn): ?>
                    <!-- Belum login -->
                    <a href="./pages/masuk.php" 
                      class="btn btn-outline-secondary rounded-pill px-4 py-2 shadow-sm">
                      <i class="bi bi-lock me-1"></i> Masuk untuk Meminjam
                    </a>

                  <?php else: ?>
                    
                    <?php if ($isCurrentlyBorrowed): ?>
                      <!-- Sudah meminjam → hanya bisa kembalikan -->
                      <a href="./pages/kembalikan.php?kode_jenis=<?= $item->kode_jenis ?>" 
                        class="btn btn-success rounded-pill px-4 py-2 shadow-sm"
                        onclick="return confirm('Apakah Anda yakin ingin mengembalikan barang <?= htmlspecialchars($item->nama_jenis) ?>?')">
                        <i class="bi bi-arrow-return-left me-1"></i> Kembalikan
                      </a>

                    <?php else: ?>
                      <!-- Sudah login & belum meminjam → bisa pinjam -->
                      <a href="./pages/pinjam_jenis.php?kode_jenis=<?= $item->kode_jenis ?>" 
                        class="btn btn-primary rounded-pill px-4 py-2 shadow-sm">
                        <i class="bi bi-box-arrow-in-down me-1"></i> Pinjam
                      </a>

                    <?php endif; ?>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="col-12 text-center text-muted mt-4">
          <i class="bi bi-exclamation-circle me-1"></i> Barang tidak ditemukan.
        </div>
      <?php endif; ?>

    </div>
  </div>
</section>

<!-- Custom Styles -->
<style>
  /* Garis bawah merah di bawah judul */
  .line {
    width: 60px;
    height: 3px;
    background: #0004ebff;
  }

  /* Hover kartu barang */
  .hover-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  .hover-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
  }

  /* Judul */
  #barang h2 {
    font-size: 2rem;
    letter-spacing: 0.5px;
  }

  /* Badge kode barang */
  .badge.bg-primary {
    background: linear-gradient(135deg, #0059ff, #001aff);
    font-size: 0.8rem;
  }

  /* Tombol utama */
  .btn-primary {
    background: linear-gradient(135deg, #0059ff, #001aff);
    border: none;
    transition: 0.3s;
  }
  .btn-primary:hover {
    background: linear-gradient(135deg, #001aff, #0059ff);
    transform: scale(1.05);
  }
  
  /* Tombol kembali */
  .btn-success {
    background: linear-gradient(135deg, #28a745, #20c997);
    border: none;
    transition: 0.3s;
  }
  .btn-success:hover {
    background: linear-gradient(135deg, #218838, #1ba87e);
    transform: scale(1.05);
  }
</style>