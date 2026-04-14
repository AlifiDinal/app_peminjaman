<?php
include '../../../config/conection.php';

// Pastikan ada ID di URL
if (!isset($_GET['id_pegawai']) || empty($_GET['id_pegawai'])) {
    echo "
        <script>
            alert('ID pegawai tidak ditemukan!');
            window.location.href = '../../pages/pegawai/index.php';
        </script>
    ";
    exit;
}

$id_pegawai = intval($_GET['id_pegawai']);

// Cek apakah data exist sebelum menghapus
$checkQuery = "SELECT * FROM users WHERE id_pegawai = $id_pegawai";
$checkResult = mysqli_query($connect, $checkQuery);

if (!$checkResult || mysqli_num_rows($checkResult) === 0) {
    echo "
        <script>
            alert('Data pegawai tidak ditemukan!');
            window.location.href = '../../pages/pegawai/index.php';
        </script>
    ";
    exit;
}

// Hapus data - GUNAKAN PREFIX
$qDelete = "DELETE FROM users WHERE id_pegawai = $id_pegawai";
$result = mysqli_query($connect, $qDelete);

if ($result) {
    echo "
        <script>
            alert('Data pegawai berhasil dihapus!');
            window.location.href='../../pages/pegawai/index.php';
        </script>    
    ";
} else {
    $error = mysqli_error($connect);
    echo "
        <script>
            alert('Data gagal dihapus. Error: " . addslashes($error) . "');
            window.history.back();
        </script> 
    ";
}
?>