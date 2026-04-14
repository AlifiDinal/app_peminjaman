<?php
include '../../partials/header.php';
include '../../partials/sidebar.php';
include '../../partials/navbar.php';
include '../../../config/conection.php';

// Ambil data aktivitas dengan join user (HAPUS role jika tidak ada)
$qaktivitas = "SELECT 
            a.*, 
            u.nama_users
            -- u.role  -- HAPUS karena kolom role tidak ada
        FROM aktivitas_users a
        LEFT JOIN users u ON a.id_users = u.id_users
        ORDER BY a.created_at DESC
    ";
$resultAktivitas = mysqli_query($connect, $qaktivitas) or die(mysqli_error($connect));

?>

<div class="container-fluid">
  <div class="page-inner">

    <!-- Judul Halaman -->
    <div class="text-center py-5">
      <h2 class="fw-bold mb-2 mt-4 text-dark display-5">
        <i class="bi bi-people-fill me-2 text-primary"></i> Data Aktivitas
      </h2>
      <h5 class="text-muted">Riwayat login/aktivitas user</h5>
    </div>

    <!-- Card -->
    <div class="card shadow-lg border-0 rounded-4">
      <div class="card-header bg-gradient d-flex justify-content-between align-items-center p-3">
        <h5 class="mb-0 fw-bold text-white">Log Aktivitas User</h5>
      </div>

      <div class="card-body">
        <div class="table-responsive">
          <table id="dataTable" class="table table-hover align-middle text-center">
            <thead class="table-light">
              <tr>
                <th>No</th>
                <th>User</th>
                <!-- <th>Role</th> HAPUS atau komen jika tidak ada role -->
                <th>IP Address</th>
                <th>User Agent</th>
                <th>Aktivitas</th>
                <th>Keterangan</th>
                <th>Waktu</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              // PERBAIKAN: Ganti $resultaktivitas menjadi $resultAktivitas
              while ($item = $resultAktivitas->fetch_object()):
              ?>
                <tr>
                  <td><?= $no ?></td>
                  <td class="fw-semibold"><?= htmlspecialchars($item->nama_users ?? '-') ?></td>
                  <!-- <td><?= htmlspecialchars($item->role ?? '-') ?> </td> HAPUS -->
                  <td><?= htmlspecialchars($item->ip_address ?? '-') ?></td>
                  <td class="text-truncate" style="max-width: 200px;"><?= htmlspecialchars($item->users_agent ?? '-') ?></td>
                  <td><?= htmlspecialchars($item->aktivitas ?? '-') ?></td>
                  <td><?= htmlspecialchars($item->keterangan ?? '-') ?></td>
                  <td><?= date('d-m-Y H:i:s', strtotime($item->created_at)) ?></td>
                </tr>
              <?php
              $no++;
              endwhile;
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>

<?php
include '../../partials/footer.php';
include '../../partials/script.php';
?>

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