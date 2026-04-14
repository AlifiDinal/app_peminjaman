<?php
include '../../../config/conection.php';

// Pastikan ada ID di URL
if (!isset($_GET['id_pengembalian']) || empty($_GET['id_pengembalian'])) {
    echo "
        <script>
            alert('ID pengembalian tidak ditemukan!');
            window.location.href = '../../pages/pengembalian/index.php';
        </script>
    ";
    exit;
}

$id_pengembalian = intval($_GET['id_pengembalian']);

// Mulai transaksi
mysqli_begin_transaction($connect);

try {
    // Ambil data peminjaman terkait sebelum menghapus
    $qGetPeminjaman = "SELECT id_peminjaman FROM pengembalian WHERE id_pengembalian = ?";
    $stmtGet = mysqli_prepare($connect, $qGetPeminjaman);
    mysqli_stmt_bind_param($stmtGet, "i", $id_pengembalian);
    mysqli_stmt_execute($stmtGet);
    $resultGet = mysqli_stmt_get_result($stmtGet);
    $data = mysqli_fetch_assoc($resultGet);
    $id_peminjaman = $data['id_peminjaman'] ?? null;
    mysqli_stmt_close($stmtGet);
    
    // Hapus data pengembalian
    $qDelete = "DELETE FROM pengembalian WHERE id_pengembalian = ?";
    $stmtDelete = mysqli_prepare($connect, $qDelete);
    mysqli_stmt_bind_param($stmtDelete, "i", $id_pengembalian);
    $result = mysqli_stmt_execute($stmtDelete);
    
    if (!$result) {
        throw new Exception(mysqli_error($connect));
    }
    
    // Jika berhasil menghapus, update status peminjaman kembali menjadi 'Dipinjam'
    if ($id_peminjaman) {
        $qUpdatePeminjaman = "UPDATE peminjaman SET status_peminjaman = 'Dipinjam' WHERE id_peminjaman = ?";
        $stmtUpdate = mysqli_prepare($connect, $qUpdatePeminjaman);
        mysqli_stmt_bind_param($stmtUpdate, "i", $id_peminjaman);
        $updateResult = mysqli_stmt_execute($stmtUpdate);
        
        if (!$updateResult) {
            throw new Exception(mysqli_error($connect));
        }
        mysqli_stmt_close($stmtUpdate);
    }
    
    // Commit transaksi
    mysqli_commit($connect);
    
    echo "
        <script>
            alert('Data Pengembalian Berhasil Dihapus!');
            window.location.href='../../pages/pengembalian/index.php';
        </script>    
    ";
    
} catch (Exception $e) {
    // Rollback jika ada error
    mysqli_rollback($connect);
    
    echo "
        <script>
            alert('Data Gagal Dihapus. Error: " . addslashes($e->getMessage()) . "');
            window.history.back();
        </script> 
    ";
}

mysqli_close($connect);
?>