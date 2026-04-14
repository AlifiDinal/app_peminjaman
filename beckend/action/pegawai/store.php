<?php
// store.php
include '../../../config/conection.php';
include '../../../config/escapeString.php'; // optional, kalau ada

// Jika kamu mengaktifkan mysqli exceptions (MYSQLI_REPORT_ERROR|MYSQLI_REPORT_STRICT),
// kamu bisa menangkapnya dengan try/catch. Jika tidak, kode ini tetap aman karena kita cek duplicate dulu.

if (!isset($_POST['tombol'])) {
    echo "<script>
            alert('Akses tidak sah!');
            window.location.href='../../pages/pegawai/index.php';
          </script>";
    exit;
}

// Ambil dan sanitasi input
$nama_pegawai = trim($_POST['nama_pegawai'] ?? '');
$nip          = trim($_POST['nip'] ?? '');
$alamat       = trim($_POST['alamat'] ?? '');

// Validasi sederhana
if ($nama_pegawai === '' || $nip === '' || $alamat === '') {
    echo "<script>
            alert('Semua field wajib diisi!');
            window.history.back();
          </script>";
    exit;
}

// Cek apakah NIP sudah ada
$cekSql = "SELECT id_pegawai FROM users WHERE nip = ? LIMIT 1";
if ($cekStmt = mysqli_prepare($connect, $cekSql)) {
    mysqli_stmt_bind_param($cekStmt, "s", $nip);
    mysqli_stmt_execute($cekStmt);
    mysqli_stmt_store_result($cekStmt);
    $count = mysqli_stmt_num_rows($cekStmt);
    mysqli_stmt_close($cekStmt);

    if ($count > 0) {
        // Jika sudah ada, beri pesan (atau arahkan ke halaman edit)
        echo "<script>
                alert('NIP sudah terdaftar. Gunakan NIP lain atau periksa daftar pegawai.');
                window.history.back();
              </script>";
        exit;
    }
} else {
    $err = mysqli_error($connect);
    echo "<script>
            alert('Gagal memeriksa NIP: " . addslashes($err) . "');
            window.history.back();
          </script>";
    exit;
}

// Jika belum ada, lakukan insert
$insertSql = "INSERT INTO users (nama_pegawai, nip, alamat) VALUES (?, ?, ?)";
if ($insertStmt = mysqli_prepare($connect, $insertSql)) {
    // jika kamu punya escapeString dan ingin menggunakannya untuk output html, bisa:
    // $nama_pegawai = escapeString($nama_pegawai);
    // $nip = escapeString($nip);
    // $alamat = escapeString($alamat);
    mysqli_stmt_bind_param($insertStmt, "sss", $nama_pegawai, $nip, $alamat);

    try {
        $exec = mysqli_stmt_execute($insertStmt);
        mysqli_stmt_close($insertStmt);

        if ($exec) {
            echo "<script>
                    alert('Data berhasil ditambahkan!');
                    window.location.href='../../pages/pegawai/index.php';
                  </script>";
            exit;
        } else {
            $err = mysqli_error($connect);
            echo "<script>
                    alert('Gagal menyimpan data: " . addslashes($err) . "');
                    window.history.back();
                  </script>";
            exit;
        }
    } catch (mysqli_sql_exception $e) {
        // Menangkap exception bila mysqli di-set untuk melempar exceptions
        $msg = $e->getMessage();
        echo "<script>
                alert('Terjadi kesalahan saat menyimpan: " . addslashes($msg) . "');
                window.history.back();
              </script>";
        exit;
    }
} else {
    $err = mysqli_error($connect);
    echo "<script>
            alert('Gagal menyiapkan query: " . addslashes($err) . "');
            window.history.back();
          </script>";
    exit;
}
?>
