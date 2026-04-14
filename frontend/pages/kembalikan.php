<?php
session_start();
include '../../config/conection.php';

// Cek login
if (!isset($_SESSION['id_users'])) {
    echo "<script>
        alert('Anda harus login terlebih dahulu!');
        window.location.href='../pages/masuk.php';
    </script>";
    exit;
}

$id_users = $_SESSION['id_users'];
$nama_users = $_SESSION['nama_users'] ?? 'User';

// Ambil daftar peminjaman AKTIF dari user yang login
$query = "SELECT pm.id_peminjaman, pm.tanggal_pinjam, pm.tanggal_kembali, pm.kode_jenis,
                 j.nama_jenis 
          FROM peminjaman pm
          LEFT JOIN jenis j ON pm.kode_jenis = j.kode_jenis
          WHERE pm.id_users = ? 
          AND pm.status_peminjaman = 'Dipinjam'
          ORDER BY pm.tanggal_pinjam DESC";

$stmt = mysqli_prepare($connect, $query);
mysqli_stmt_bind_param($stmt, "i", $id_users);
mysqli_stmt_execute($stmt);
$resultPeminjaman = mysqli_stmt_get_result($stmt);
$daftarPeminjaman = mysqli_fetch_all($resultPeminjaman, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

// Ambil daftar petugas
$queryPetugas = "SELECT id_petugas, nama_petugas FROM petugas ORDER BY nama_petugas";
$resultPetugas = mysqli_query($connect, $queryPetugas);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengembalian Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .card {
            border-radius: 15px;
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(135deg, #28a745, #20c997);
            padding: 20px;
        }
        .btn-success {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            transition: transform 0.2s;
        }
        .btn-success:hover {
            transform: translateY(-2px);
        }
        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #28a745;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .barang-item {
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .barang-item:hover {
            background-color: #f8f9fa;
            border-color: #28a745;
        }
        .barang-item.selected {
            background-color: #d4edda;
            border-color: #28a745;
            border-width: 2px;
        }
        .warning-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .form-label {
            font-weight: 600;
            margin-bottom: 8px;
        }
        .btn-kembali {
            background-color: #6c757d;
            color: white;
        }
        .btn-kembali:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">
                            <i class="bi bi-arrow-return-left me-2"></i> Form Pengembalian Barang
                        </h4>
                        <p class="mb-0 mt-2 small">Pilih barang yang akan dikembalikan</p>
                    </div>
                    <div class="card-body p-4">
                        <!-- Informasi Peminjam -->
                        <div class="info-box">
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">Nama Peminjam</small>
                                    <p class="fw-bold mb-0"><?= htmlspecialchars($nama_users) ?></p>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">ID User</small>
                                    <p class="fw-bold mb-0">#<?= htmlspecialchars($id_users) ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Daftar Barang yang Dipinjam -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="bi bi-box-seam me-1"></i> Barang yang Sedang Dipinjam 
                                <span class="text-danger">*</span>
                            </label>
                            
                            <?php if (count($daftarPeminjaman) > 0): ?>
                                <div id="daftarBarang">
                                    <?php foreach($daftarPeminjaman as $index => $item): 
                                        $tgl_kembali = $item['tanggal_kembali'];
                                        $hari_ini = date('Y-m-d');
                                        $terlambat = $hari_ini > $tgl_kembali;
                                        $hari_terlambat = 0;
                                        $denda = 0;
                                        
                                        if ($terlambat) {
                                            $tgl1 = new DateTime($tgl_kembali);
                                            $tgl2 = new DateTime($hari_ini);
                                            $hari_terlambat = $tgl1->diff($tgl2)->days;
                                            $denda = $hari_terlambat * 5000;
                                        }
                                    ?>
                                        <div class="barang-item" data-id="<?= $item['id_peminjaman'] ?>" 
                                             data-denda="<?= $denda ?>" 
                                             data-nama="<?= htmlspecialchars($item['nama_jenis']) ?>"
                                             data-tgl_kembali="<?= date('d F Y', strtotime($item['tanggal_kembali'])) ?>"
                                             data-terlambat="<?= $terlambat ? 'true' : 'false' ?>"
                                             data-hari_terlambat="<?= $hari_terlambat ?>">
                                            <div class="row align-items-center">
                                                <div class="col-md-7">
                                                    <h6 class="mb-1">
                                                        <i class="bi bi-book me-2"></i>
                                                        <strong><?= htmlspecialchars($item['nama_jenis']) ?></strong>
                                                    </h6>
                                                    <small class="text-muted">
                                                        <i class="bi bi-calendar me-1"></i> 
                                                        Pinjam: <?= date('d M Y', strtotime($item['tanggal_pinjam'])) ?>
                                                        <br>
                                                        <i class="bi bi-calendar-event me-1"></i>
                                                        Batas Kembali: <?= date('d M Y', strtotime($item['tanggal_kembali'])) ?>
                                                    </small>
                                                </div>
                                                <div class="col-md-5 text-end">
                                                    <?php if ($terlambat): ?>
                                                        <span class="badge bg-danger mb-2">
                                                            <i class="bi bi-exclamation-triangle"></i> Terlambat <?= $hari_terlambat ?> hari
                                                        </span>
                                                        <br>
                                                        <span class="badge bg-warning text-dark">
                                                            Denda: Rp <?= number_format($denda, 0, ',', '.') ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success">
                                                            <i class="bi bi-check-circle"></i> Tepat Waktu
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Tidak ada barang yang sedang dipinjam. 
                                    <a href="pinjam_jenis.php" class="alert-link">Pinjam barang sekarang</a>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php if (count($daftarPeminjaman) > 0): ?>
                        <form action="../action/kembalikan.php" method="POST" id="formPengembalian">
                            <input type="hidden" name="id_peminjaman" id="selected_id_peminjaman" required>
                            <input type="hidden" name="denda" id="selected_denda">
                            
                            <!-- Kondisi Barang -->
                            <div class="mb-3">
                                <label for="kondisi_barang" class="form-label">
                                    <i class="bi bi-clipboard-check me-1"></i> Kondisi Barang 
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="kondisi_barang" id="kondisi_barang" class="form-select" disabled required>
                                    <option value="">-- Pilih Kondisi --</option>
                                    <option value="Baik">Baik</option>
                                    <option value="Rusak Ringan">Rusak Ringan</option>
                                    <option value="Rusak Berat">Rusak Berat</option>
                                </select>
                            </div>
                            
                            <!-- Keterangan -->
                            <div class="mb-3">
                                <label for="keterangan" class="form-label">
                                    <i class="bi bi-chat-text me-1"></i> Keterangan
                                </label>
                                <textarea class="form-control" name="keterangan" id="keterangan" rows="3" 
                                    placeholder="Isikan keterangan jika ada kerusakan atau catatan lainnya..." disabled></textarea>
                                <small class="text-muted">* Optional, bisa dikosongkan</small>
                            </div>
                            
                            <!-- Petugas -->
                            <div class="mb-3">
                                <label for="id_petugas" class="form-label">
                                    <i class="bi bi-person-badge me-1"></i> Petugas yang Menerima 
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" name="id_petugas" id="id_petugas" required disabled>
                                    <option value="" disabled selected>-- Pilih barang terlebih dahulu --</option>
                                    <?php while($petugas = mysqli_fetch_assoc($resultPetugas)): ?>
                                    <option value="<?= $petugas['id_petugas'] ?>">
                                        <?= htmlspecialchars($petugas['nama_petugas']) ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            
                            <!-- Info Denda (dynamic) -->
                            <div id="infoDenda" class="warning-box d-none">
                                <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                                <span id="infoDendaText"></span>
                            </div>
                            
                            <hr class="my-4">
                            
                            <!-- Tombol Aksi -->
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="../index.php" class="btn btn-outline-secondary px-4">
                                    <i class="bi bi-x-circle me-1"></i> Batal
                                </a>
                                <button type="submit" name="submit" class="btn btn-success px-4" id="btnSubmit" disabled>
                                    <i class="bi bi-check-circle me-1"></i> Konfirmasi Pengembalian
                                </button>
                            </div>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        let selectedItem = null;
        
        // Pilih barang
        document.querySelectorAll('.barang-item').forEach(item => {
            item.addEventListener('click', function() {
                // Hapus selected dari semua
                document.querySelectorAll('.barang-item').forEach(i => i.classList.remove('selected'));
                
                // Tambah selected ke yang diklik
                this.classList.add('selected');
                selectedItem = this;
                
                // Isi hidden fields
                const idPeminjaman = this.dataset.id;
                const denda = this.dataset.denda;
                const namaBarang = this.dataset.nama;
                const terlambat = this.dataset.terlambat === 'true';
                const hariTerlambat = this.dataset.hari_terlambat;
                const tglKembali = this.dataset.tgl_kembali;
                
                document.getElementById('selected_id_peminjaman').value = idPeminjaman;
                document.getElementById('selected_denda').value = denda;
                
                // Enable form elements
                document.getElementById('kondisi_barang').disabled = false;
                document.getElementById('keterangan').disabled = false;
                document.getElementById('id_petugas').disabled = false;
                document.getElementById('btnSubmit').disabled = false;
                
                // Reset kondisi barang
                document.getElementById('kondisi_barang').value = '';
                
                // Tampilkan info denda jika terlambat
                const infoDendaDiv = document.getElementById('infoDenda');
                if (terlambat && parseInt(denda) > 0) {
                    infoDendaDiv.classList.remove('d-none');
                    document.getElementById('infoDendaText').innerHTML = `
                        <strong>Perhatian!</strong> Anda terlambat mengembalikan barang 
                        <strong>${namaBarang}</strong> selama <strong>${hariTerlambat} hari</strong>.<br>
                        Denda: <strong>Rp ${parseInt(denda).toLocaleString('id-ID')}</strong>
                    `;
                } else {
                    infoDendaDiv.classList.add('d-none');
                }
            });
        });
        
        // Validasi submit
        document.getElementById('formPengembalian')?.addEventListener('submit', function(e) {
            const kondisi = document.getElementById('kondisi_barang').value;
            const petugas = document.getElementById('id_petugas').value;
            
            if (!kondisi) {
                e.preventDefault();
                alert('Silakan pilih kondisi barang terlebih dahulu!');
                document.getElementById('kondisi_barang').focus();
                return false;
            }
            
            if (!petugas) { 
                e.preventDefault();
                alert('Silakan pilih petugas yang menerima!');
                document.getElementById('id_petugas').focus();
                return false;
            }
            
            return confirm('Apakah Anda yakin ingin mengembalikan barang ini?');
        });
    </script>
</body>
</html>