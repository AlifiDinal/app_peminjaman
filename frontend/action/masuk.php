<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../../config/conection.php';

// Escape function
if (!function_exists('escapeString')) {
    function escapeString($string) {
        global $connect;
        return mysqli_real_escape_string($connect, trim($string));
    }
}

if (isset($_POST['login'])) {

    $nama_pegawai = escapeString($_POST['nama_pegawai']);
    $nip = escapeString($_POST['nip']);

    $q = "SELECT * FROM users WHERE nama_users = '$nama_pegawai' AND nip = '$nip'";
    $result = mysqli_query($connect, $q);

    if (!$result) {
        die("Error query: " . mysqli_error($connect));
    }

    if (mysqli_num_rows($result) > 0) {

        $pegawai = mysqli_fetch_assoc($result);

        // ===== SET SESSION =====
        $_SESSION['id_users']   = $pegawai['id_users'];
        $_SESSION['nama_users'] = $pegawai['nama_users'];
        $_SESSION['nip']        = $pegawai['nip'];
        $_SESSION['alamat']     = $pegawai['alamat'];
        $_SESSION['login_time'] = time();

        // ===== 🔥 TAMBAHAN: LOG AKTIVITAS LOGIN =====
        $id_users   = $pegawai['id_users'];
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $aktivitas  = "Login";
        $keterangan = "User login ke sistem";

        $log = "INSERT INTO aktivitas_users 
                (id_users, ip_address, users_agent, aktivitas, keterangan, created_at)
                VALUES 
                ('$id_users', '$ip_address', '$user_agent', '$aktivitas', '$keterangan', NOW())";

        mysqli_query($connect, $log) or die("Gagal simpan log: " . mysqli_error($connect));

        // ===== REDIRECT =====
        echo "
            <script>
                alert('Berhasil masuk sebagai {$pegawai['nama_users']}');
                window.location.href = '../index.php';
            </script>
        ";
        exit;

    } else {

        echo "
            <script>
                alert('Nama atau NIP salah!');
                window.location.href = '../index.html';
            </script>
        ";
        exit;
    }

} else {
    header("Location: ../index.html");
    exit;
}
?>