<?php
include '../../partials/header.php';
include '../../partials/sidebar.php';
include '../../partials/navbar.php';
include '../../../config/conection.php';

// Pastikan ada parameter id_pengembalian
if (!isset($_GET['id_pengembalian'])) {
    echo "<script>alert('ID Pengembalian tidak ditemukan!');window.location.href='index.php';</script>";
    exit;
}

$id_pengembalian = intval($_GET['id_pengembalian']);

// Ambil data pengembalian dengan join ke tabel related
$query = "SELECT 
            pg.id_pengembalian,
            pg.id_peminjaman,
            pg.tanggal_kembali_real,
            pg.kondisi_barang,
            pg.denda,
            pg.keterangan,
            pg.id_petugas,
            -- Data dari tabel peminjaman
            pm.tanggal_pinjam,
            pm.tanggal_kembali as tanggal_kembali_rencana,
            pm.status_peminjaman,
            pm.kode_jenis,
            -- Data users (peminjam)
            pw.nama_users,
            pw.nip,
            pw.alamat,
            -- Data jenis barang
            j.nama_jenis,
            j.keterangan as jenis_keterangan,
            -- Data petugas
            pt.nama_petugas,
            pt.username
          FROM pengembalian pg
          LEFT JOIN peminjaman pm ON pg.id_peminjaman = pm.id_peminjaman
          LEFT JOIN users pw ON pm.id_users = pw.id_users
          LEFT JOIN jenis j ON pm.kode_jenis = j.kode_jenis
          LEFT JOIN petugas pt ON pg.id_petugas = pt.id_petugas
          WHERE pg.id_pengembalian = $id_pengembalian
";

$result = mysqli_query($connect, $query);
$pengembalian = mysqli_fetch_assoc($result);

if (!$pengembalian) {
    echo "<script>alert('Data pengembalian tidak ditemukan!');window.location.href='index.php';</script>";
    exit;
}

// Hitung keterlambatan (jika ada)
$tgl_kembali_rencana = new DateTime($pengembalian['tanggal_kembali_rencana']);
$tgl_kembali_real = new DateTime($pengembalian['tanggal_kembali_real']);
$terlambat = $tgl_kembali_real > $tgl_kembali_rencana;
$hari_terlambat = $terlambat ? $tgl_kembali_rencana->diff($tgl_kembali_real)->days : 0;
?>

<div class="container-fluid">
  <div class="page-inner">

    <!-- Judul Halaman -->
    <div class="text-center py-5">
      <h2 class="fw-bold mb-2 mt-4 text-dark display-5">
        <i class="bi bi-arrow-return-left text-primary me-2"></i> Detail Pengembalian
      </h2>
      <h5 class="text-muted">Informasi lengkap data pengembalian barang</h5>
    </div>

    <!-- Card Detail -->
    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="card shadow-sm border-0 rounded-4">
          <div class="card-header bg-white border-0 rounded-top-4 py-3">
            <h5 class="mb-0 fw-bold text-dark">
              <i class="bi bi-info-circle-fill text-primary me-2"></i>Informasi Pengembalian
            </h5>
          </div>
          <div class="card-body px-4 py-4">

            <!-- Informasi Pengembalian -->
            <div class="mb-4">
              <h6 class="fw-bold text-primary mb-3">
                <i class="bi bi-tag me-2"></i>Data Pengembalian
              </h6>
              <table class="table table-bordered">
                <tr>
                  <th width="30%" class="bg-light">ID Pengembalian</th>
                  <td>
                    <span class="badge bg-info fs-6 px-3 py-2">#<?= htmlspecialchars($pengembalian['id_pengembalian']) ?></span>
                  </td>
                </tr>
                <tr>
                  <th class="bg-light">ID Peminjaman</th>
                  <td>
                    <a href="../peminjaman/detail.php?id_peminjaman=<?= $pengembalian['id_peminjaman'] ?>" class="text-decoration-none">
                      #<?= htmlspecialchars($pengembalian['id_peminjaman']) ?>
                    </a>
                  </td>
                </tr>
                <tr>
                  <th class="bg-light">Tanggal Kembali (Rencana)</th>
                  <td>
                    <i class="bi bi-calendar-date me-2 text-warning"></i>
                    <?= date('d F Y', strtotime($pengembalian['tanggal_kembali_rencana'])) ?>
                  </td>
                </tr>
                <tr>
                  <th class="bg-light">Tanggal Kembali (Aktual)</th>
                  <td>
                    <i class="bi bi-calendar-check me-2 text-success"></i>
                    <?= date('d F Y', strtotime($pengembalian['tanggal_kembali_real'])) ?>
                    <?php if ($terlambat): ?>
                      <span class="badge bg-danger ms-2">Terlambat <?= $hari_terlambat ?> hari</span>
                    <?php elseif ($hari_terlambat == 0): ?>
                      <span class="badge bg-success ms-2">Tepat waktu</span>
                    <?php endif; ?>
                  </td>
                </tr>
                <tr>
                  <th class="bg-light">Kondisi Barang</th>
                  <td>
                    <?php 
                    $kondisiBadge = '';
                    switch($pengembalian['kondisi_barang']) {
                      case 'Baik':
                        $kondisiBadge = 'bg-success';
                        break;
                      case 'Rusak Ringan':
                        $kondisiBadge = 'bg-warning text-dark';
                        break;
                      case 'Rusak Berat':
                        $kondisiBadge = 'bg-danger';
                        break;
                      default:
                        $kondisiBadge = 'bg-secondary';
                    }
                    ?>
                    <span class="badge <?= $kondisiBadge ?> fs-6 px-3 py-2">
                      <?= htmlspecialchars($pengembalian['kondisi_barang']) ?>
                    </span>
                  </td>
                </tr>
                <tr>
                  <th class="bg-light">Denda</th>
                  <td>
                    <span class="fw-bold <?= ($pengembalian['denda'] > 0) ? 'text-danger' : 'text-success' ?> fs-5">
                      <?= $pengembalian['denda'] > 0 ? 'Rp ' . number_format($pengembalian['denda'], 0, ',', '.') : 'Rp 0 (Tidak ada denda)' ?>
                    </span>
                    <?php if ($terlambat && $pengembalian['denda'] == 0): ?>
                      <small class="text-muted d-block">* Periksa kembali, seharusnya ada denda keterlambatan</small>
                    <?php endif; ?>
                  </td>
                </tr>
                <tr>
                  <th class="bg-light">Keterangan</th>
                  <td>
                    <?php if (!empty($pengembalian['keterangan'])): ?>
                      <?= nl2br(htmlspecialchars($pengembalian['keterangan'])) ?>
                    <?php else: ?>
                      <span class="text-muted">- Tidak ada keterangan -</span>
                    <?php endif; ?>
                  </td>
                </tr>
              </table>
            </div>

            <!-- Tombol Aksi -->
            <div class="d-flex justify-content-between mt-4 pt-3 border-top">
              <a href="index.php" class="btn btn-outline-secondary px-4">
                <i class="bi bi-arrow-left me-2"></i>Kembali ke Daftar
              </a>
            </div>

          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<style>
  .table th {
    font-weight: 600;
    background-color: #f8f9fa;
  }
  .table td {
    vertical-align: middle;
  }
  .badge {
    font-size: 0.9rem;
    font-weight: 500;
  }
  .btn-warning {
    color: #fff;
    background-color: #ffc107;
    border-color: #ffc107;
  }
  .btn-warning:hover {
    background-color: #e0a800;
    border-color: #d39e00;
    color: #fff;
  }
</style>

<?php
include '../../partials/footer.php';
include '../../partials/script.php';
?>