<?php
session_start();
include __DIR__ . '/../../config/conection.php';

// Cek apakah users sudah login
if (!isset($_SESSION['id_users'])) {
  echo "
  <script>
    alert('Anda harus login terlebih dahulu untuk meminjam barang!');
    window.location.href='../pages/masuk.php';
  </script>";
  exit;
}

$qJenis = "SELECT * FROM jenis";
$resultJenis = mysqli_query($connect, $qJenis);

// Ambil data users dari session
$namausers = $_SESSION['nama_users'] ?? 'users';
$idusers = $_SESSION['id_users'];
?>
<!DOCTYPE html>
<html lang="en">  

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Tambah Data Peminjaman</title>

  <!-- Favicons -->
  <link href="../templates_users/assets/img/logo_smk.png" rel="icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="../templates_users/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../templates_users/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="../templates_users/assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="../templates_users/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="../templates_users/assets/css/main.css" rel="stylesheet">
  
 <style>
  .form-container {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    padding: 30px;
    margin-top: 20px;
  }

  .form-header {
    border-bottom: 1px solid #eeeeeeff;
    padding-bottom: 15px;
    margin-bottom: 25px;
  }

  .form-label {
    font-weight: 500;
    margin-bottom: 8px;
  }

  /* Tombol simpan */
  .btn-submit {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: #fff !important;
    padding: 10px 25px;
    font-weight: 500;
    transition: all 0.2s ease;
  }

  .btn-submit:hover {
    background-color: #0b5ed7;
    border-color: #0b5ed7;
    color: #fff !important;
  }

  .btn-submit:active,
  .btn-submit:focus {
    background-color: #0a58ca !important;
    border-color: #0a58ca !important;
    color: #fff !important;
    box-shadow: none !important;
  }

  /* Tombol batal */
  .btn-cancel {
    background-color: #6c757d;
    border-color: #6c757d;
    color: #fff !important;
    padding: 10px 25px;
    font-weight: 500;
    transition: all 0.2s ease;
  }

  .btn-cancel:hover {
    background-color: #5c636a;
    border-color: #565e64;
  }

  .btn-cancel:active,
  .btn-cancel:focus {
    background-color: #4e555b !important;
    border-color: #4e555b !important;
    color: #fff !important;
    box-shadow: none !important;
  }
  
  /* Validasi style */
  .was-validated .form-control:invalid,
  .form-control.is-invalid {
    border-color: #dc3545;
  }
  
  .was-validated .form-control:valid,
  .form-control.is-valid {
    border-color: #198754;
  }
</style>

</head>

<body class="d-flex flex-column min-vh-100">

  <main class="flex-grow-1">
    <section class="py-5">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="form-container" data-aos="fade-up">
                <div class="form-header">
                  <h2 class="fw-bold text-dark mb-2">Tambah Data Peminjaman</h2>
                  <p class="text-muted">Isi form berikut untuk menambahkan data peminjaman baru</p>
                </div>
                
                <form action="../action/pinjam_jenis.php" method="POST" id="formPeminjaman">
                    <div class="row mb-3">
                      <div class="col-md-6">
                        <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="tanggal_pinjam" name="tanggal_pinjam" required>
                        <div class="invalid-feedback">
                          Tanggal pinjam harus diisi
                        </div>
                      </div>
                      <div class="col-md-6">
                        <label for="tanggal_kembali" class="form-label">Tanggal Kembali <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="tanggal_kembali" name="tanggal_kembali" required>
                        <div class="invalid-feedback">
                          Tanggal kembali harus diisi dan tidak boleh sebelum tanggal pinjam
                        </div>
                      </div>
                    </div>

                    <div class="mb-3">
                      <label for="kode_jenis" class="form-label">Jenis Barang <span class="text-danger">*</span></label>
                      <select class="form-select" name="kode_jenis" required>
                        <option value="" disabled selected>Pilih Jenis</option>
                        <?php while($j = mysqli_fetch_assoc($resultJenis)): ?>
                          <option value="<?= htmlspecialchars($j['kode_jenis']) ?>">
                            <?= htmlspecialchars($j['nama_jenis']) ?>
                          </option>
                        <?php endwhile; ?>
                      </select>
                      <div class="invalid-feedback">
                        Silakan pilih jenis barang
                      </div>
                    </div>

                    <div class="mb-3">
                      <label for="status_peminjaman" class="form-label">Status Peminjaman <span class="text-danger">*</span></label>
                      <input type="hidden" name="status_peminjaman" value="Dipinjam">
                      <div class="invalid-feedback">
                        Silakan pilih status peminjaman
                      </div>
                    </div>

                    <!-- Nama users tampil, tapi tidak bisa diubah -->
                    <div class="mb-3">
                      <label class="form-label">Nama users</label>
                      <input type="text" class="form-control" value="<?= htmlspecialchars($namausers) ?>" readonly disabled>
                      <small class="text-muted">Nama diambil dari session login</small>
                    </div>

                    <!-- ID users otomatis dikirim -->
                    <input type="hidden" name="id_users" value="<?= htmlspecialchars($idusers) ?>">

                    <div class="d-flex justify-content-end gap-2 mt-4">
                      <a href="../index.php" class="btn btn-cancel">Batal</a>
                      <button type="submit" name="tombol" class="btn btn-submit">Simpan Data</button>
                    </div>
                </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <footer class="bg-dark text-white text-center py-4 mt-auto">
    <div class="container">
      <p class="mb-0">&copy; <?= date('Y') ?> App Peminjaman Barang Sekolah.</p>
    </div>
  </footer>

  <!-- Vendor JS Files -->
  <script src="../templates_users/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../templates_users/assets/vendor/aos/aos.js"></script>

  <script>
    AOS.init();
    
    // Client-side validation untuk tanggal
    document.getElementById('formPeminjaman').addEventListener('submit', function(e) {
        const tglPinjam = document.getElementById('tanggal_pinjam').value;
        const tglKembali = document.getElementById('tanggal_kembali').value;
        
        if (tglPinjam && tglKembali) {
            if (new Date(tglKembali) < new Date(tglPinjam)) {
                e.preventDefault();
                alert('Tanggal kembali tidak boleh sebelum tanggal pinjam!');
                document.getElementById('tanggal_kembali').classList.add('is-invalid');
                return false;
            }
        }
        
        // Reset invalid state jika valid
        document.getElementById('tanggal_kembali').classList.remove('is-invalid');
    });
    
    // Real-time validation untuk tanggal kembali
    document.getElementById('tanggal_kembali').addEventListener('change', function() {
        const tglPinjam = document.getElementById('tanggal_pinjam').value;
        const tglKembali = this.value;
        
        if (tglPinjam && tglKembali && new Date(tglKembali) < new Date(tglPinjam)) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });
    
    // Set minimal date untuk tanggal pinjam (tidak boleh kurang dari hari ini)
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('tanggal_pinjam').setAttribute('min', today);
    
    // Update minimal tanggal kembali ketika tanggal pinjam berubah
    document.getElementById('tanggal_pinjam').addEventListener('change', function() {
        const tglKembali = document.getElementById('tanggal_kembali');
        tglKembali.setAttribute('min', this.value);
        
        // Reset nilai jika tanggal kembali lebih kecil
        if (tglKembali.value && new Date(tglKembali.value) < new Date(this.value)) {
            tglKembali.value = '';
        }
    });
  </script>
</body>
</html> 