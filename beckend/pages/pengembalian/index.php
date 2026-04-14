<?php
include '../../partials/header.php';
include '../../partials/sidebar.php';
include '../../partials/navbar.php';
include '../../../config/conection.php';

// Ambil keyword dari GET
$keyword = $_GET['keyword'] ?? '';
$safeKeyword = mysqli_real_escape_string($connect, $keyword);

// Query Pengembalian + filter pencarian
$qPengembalian = "SELECT 
    pg.*, 
    p.id_peminjaman,
    p.tanggal_pinjam,
    p.tanggal_kembali,
    p.status_peminjaman,
    u.nama_users,
    j.nama_jenis,
    pt.nama_petugas
FROM pengembalian pg
LEFT JOIN peminjaman p ON pg.id_peminjaman = p.id_peminjaman
LEFT JOIN users u ON p.id_users = u.id_users
LEFT JOIN jenis j ON p.kode_jenis = j.kode_jenis
LEFT JOIN petugas pt ON pg.id_petugas = pt.id_petugas
";

if (!empty($keyword)) {
    $qPengembalian .= " WHERE 
        pg.id_pengembalian LIKE '%$safeKeyword%' 
        OR pg.tanggal_kembali_real LIKE '%$safeKeyword%' 
        OR pg.kondisi_barang LIKE '%$safeKeyword%'
        OR pg.denda LIKE '%$safeKeyword%'
        OR u.nama_users LIKE '%$safeKeyword%'
        OR j.nama_jenis LIKE '%$safeKeyword%'
        OR pt.nama_petugas LIKE '%$safeKeyword%'
    ";
}


$resultPengembalian = mysqli_query($connect, $qPengembalian) or die(mysqli_error($connect));

?>

<div class="container-fluid">
  <div class="page-inner py-5">

    <!-- Judul Halaman -->
    <div class="text-center py-5">
      <h2 class="fw-bold mb-2 mt-4 text-dark display-5">
        <i class="bi bi-tags-fill text-primary me-2"></i> Halaman Pengembalian
      </h2>
      <h5 class="text-muted">Daftar Pengembalian inventaris / kategori Pengembalian</h5>
    </div>

    <!-- Card -->
    <div class="card shadow-lg border-0 rounded-4">
      <div class="card-body">
        <div class="table-responsive">
          <table id="DataTable" class="table table-hover align-middle text-center">
            <thead class="table-light">
              <tr>
                <th>No</th>
                <th>Kode Pengembalian</th>
                <th>Id Peminjaman</th>
                <th>Kondisi Barang</th>
                <th>Denda</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              if (mysqli_num_rows($resultPengembalian) > 0):
                while ($item = $resultPengembalian->fetch_object()):
              ?>
                <tr>
                  <td><?= $no ?></td>
                  <td><?= htmlspecialchars($item->id_pengembalian) ?></td>
                  <td><?= htmlspecialchars($item->id_peminjaman) ?></td>
                  <td><?= htmlspecialchars($item->kondisi_barang) ?></td>
                  <td><?= htmlspecialchars($item->denda) ?></td>
                  <td>
                    <a href="./detail.php?id_pengembalian=<?= $item->id_pengembalian ?>" 
                      class="btn btn-sm btn-outline-info me-1 shadow-sm">
                      <i class="bi bi-info-circle"></i>
                    </a>
                    <a href="../../action/pengembalian/destroy.php?id_pengembalian=<?= $item->id_pengembalian ?>"
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
    $('#DataTable').DataTable({
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
