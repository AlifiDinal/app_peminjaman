<?php
include '../../partials/header.php';
include '../../partials/sidebar.php';
include '../../partials/navbar.php';
include '../../../config/conection.php';

// Ambil keyword dari GET
$keyword = $_GET['keyword'] ?? '';
$safeKeyword = mysqli_real_escape_string($connect, $keyword);

// Query Peminjaman + filter pencarian
$qPeminjaman = "SELECT 
    p.*, 
    pg.nama_users,
    j.nama_jenis
FROM peminjaman p
LEFT JOIN users pg ON p.id_users = pg.id_users
LEFT JOIN jenis j ON p.kode_jenis = j.kode_jenis
";

if (!empty($keyword)) {
    $qPeminjaman .= " WHERE 
        p.id_peminjaman LIKE '%$safeKeyword%' 
        OR p.tanggal_pinjam LIKE '%$safeKeyword%' 
        OR p.tanggal_kembali LIKE '%$safeKeyword%'
        OR p.status_peminjaman LIKE '%$safeKeyword%'
        OR pg.nama_users LIKE '%$safeKeyword%'
        OR j.nama_jenis LIKE '%$safeKeyword%'";
}


$resultPeminjaman = mysqli_query($connect, $qPeminjaman) or die(mysqli_error($connect));

?>

<div class="container-fluid">
  <div class="page-inner py-5">

    <!-- Judul Halaman -->
    <div class="text-center py-5">
      <h2 class="fw-bold mb-2 mt-4 text-dark display-5">
        <i class="bi bi-tags-fill text-primary me-2"></i> Halaman Peminjaman
      </h2>
      <h5 class="text-muted">Daftar Peminjaman inventaris / kategori Peminjaman</h5>
    </div>

    <!-- Card -->
    <div class="card shadow-lg border-0 rounded-4">
      <div class="card-body">
        <div class="table-responsive">
          <table id="dataTable" class="table table-hover align-middle text-center">
            <thead class="table-light">
              <tr>
                <th>No</th>
                <th>users</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Kode Barang</th>
                <th>Setatus</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              if (mysqli_num_rows($resultPeminjaman) > 0):
                while ($item = $resultPeminjaman->fetch_object()):
              ?>
                <tr>
                  <td><?= $no ?></td>
                  <td><?= htmlspecialchars($item->nama_users) ?></td>
                  <td><?= htmlspecialchars($item->tanggal_pinjam) ?></td>
                  <td><?= htmlspecialchars($item->tanggal_kembali) ?></td>
                  <td><?= htmlspecialchars($item->nama_jenis) ?></td>
                  <td class="fw-semibold"><?= htmlspecialchars($item->status_peminjaman) ?></td>
                  <td>
                    <a href="../../action/peminjaman/destroy.php?id_peminjaman=<?= $item->id_peminjaman ?>"
                       class="btn btn-sm btn-outline-danger shadow-sm"
                       onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                      <i class="bi bi-trash"></i>
                    </a>
                  </td>
                </tr>
              <?php
                $no++;
                endwhile;
              else:
              ?>
                <tr>
                  <td colspan="5" class="text-center text-muted">Tidak ada data ditemukan</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- DataTables Style & Script -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
  $(document).ready(function () {
    $('#dataTable').DataTable({
      language: {
        lengthMenu: "Tampilkan _MENU_ data per halaman",
        zeroRecords: "Tidak ada data ditemukan",
        info: "Menampilkan _START_ sampai _END_ dari total _TOTAL_ data",
        infoEmpty: "Tidak ada data tersedia",
        infoFiltered: "(difilter dari total _MAX_ data)",
        search: "Cari:",
        paginate: {
          first: "Awal",
          last: "Akhir",
          next: "→",
          previous: "←"
        }
      }
    });
  });
</script>

<!-- Custom Style -->
<style>
  .btn-purple {
    background: linear-gradient(135deg, #6f42c1, #9d6bff);
    color: #fff;
    border-radius: 10px;
    transition: all 0.3s ease;
  }
  .btn-purple:hover {
    background: linear-gradient(135deg, #5a32a3, #854dff);
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.2);
  }
  .text-purple {
    color: #6f42c1;
  }
  .table-hover tbody tr:hover {
    background-color: #f9f3ff !important;
    transition: 0.3s;
  }
</style>

<?php
include '../../partials/footer.php';
include '../../partials/script.php';
?>
