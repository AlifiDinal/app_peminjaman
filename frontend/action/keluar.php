<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../../config/conection.php';

// ===== 🔥 TAMBAHAN: LOG AKTIVITAS LOGOUT =====
if (isset($_SESSION['id_users'])) {

    $id_users   = $_SESSION['id_users'];
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];

    $aktivitas  = "Logout";
    $keterangan = "User keluar dari sistem";

    $log = "INSERT INTO aktivitas_users 
            (id_users, ip_address, users_agent, aktivitas, keterangan, created_at)
            VALUES 
            ('$id_users', '$ip_address', '$user_agent', '$aktivitas', '$keterangan', NOW())";

    mysqli_query($connect, $log) or die("Gagal simpan log logout: " . mysqli_error($connect));
}

// ===== HAPUS SESSION =====
unset($_SESSION['nama_users']);
unset($_SESSION['nip']);
unset($_SESSION['id_users']);

session_unset();
session_destroy();

// ===== HAPUS COOKIE =====
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// ===== REDIRECT =====
echo "
    <script>
        alert('Anda telah berhasil keluar dari sistem.');
        window.location.href = '../index.php';
    </script>
";
exit;
?>