<?php
session_start();
include '../../config/conection.php';

// Debug koneksi
if (!$connect) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Validasi request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Akses tidak valid!");
}

// Ambil data dari form
$id_peminjaman = $_POST['id_peminjaman'] ?? null;
$kondisi_barang = $_POST['kondisi_barang'] ?? null;
$keterangan = $_POST['keterangan'] ?? '';
$id_petugas = $_POST['id_petugas'] ?? null;
$denda = $_POST['denda'] ?? 0;
$tanggal_kembali_real = date('Y-m-d');

// Validasi
if (!$id_peminjaman || !$kondisi_barang || !$id_petugas) {
    die("Data tidak lengkap!");
}

// Pastikan tipe data benar
$id_peminjaman = (int)$id_peminjaman;
$id_petugas = (int)$id_petugas;
$denda = (int)$denda;

// Mulai transaksi
mysqli_begin_transaction($connect);

try {

    // =====================
    // INSERT PENGEMBALIAN
    // =====================
    $query1 = "INSERT INTO pengembalian 
        (id_peminjaman, tanggal_kembali_real, kondisi_barang, denda, keterangan, id_petugas) 
        VALUES (?, ?, ?, ?, ?, ?)";

    $stmt1 = mysqli_prepare($connect, $query1);

    if (!$stmt1) {
        throw new Exception("Prepare gagal: " . mysqli_error($connect));
    }

    mysqli_stmt_bind_param($stmt1, "issisi", 
        $id_peminjaman,
        $tanggal_kembali_real,
        $kondisi_barang,
        $denda,
        $keterangan,
        $id_petugas
    );

    if (!mysqli_stmt_execute($stmt1)) {
        throw new Exception("Execute gagal: " . mysqli_stmt_error($stmt1));
    }

    mysqli_stmt_close($stmt1);


    // =====================
    // UPDATE STATUS
    // =====================
    $query2 = "UPDATE peminjaman 
               SET status_peminjaman = 'Dikembalikan' 
               WHERE id_peminjaman = ?";

    $stmt2 = mysqli_prepare($connect, $query2);

    if (!$stmt2) {
        throw new Exception("Prepare update gagal: " . mysqli_error($connect));
    }

    mysqli_stmt_bind_param($stmt2, "i", $id_peminjaman);

    if (!mysqli_stmt_execute($stmt2)) {
        throw new Exception("Update gagal: " . mysqli_stmt_error($stmt2));
    }

    mysqli_stmt_close($stmt2);


    // =====================
    // COMMIT
    // =====================
    mysqli_commit($connect);

    echo "<script>
        alert('Pengembalian berhasil!');
        window.location.href='../index.php';
    </script>";

} catch (Exception $e) {

    mysqli_rollback($connect);

    echo "<script>
        alert('ERROR: " . $e->getMessage() . "');
        window.history.back();
    </script>";
}
?>