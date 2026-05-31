<?php
include 'koneksi.php';

// Simpen nama + role dulu sebelum session ancur
$nama = $_SESSION['nama'] ?? 'User';
$role = $_SESSION['role'] ?? 'user';

session_unset();
session_destroy();

// Start lagi cuma buat kirim notif
session_start();
if($role == 'admin'){
    setNotif('Sampai jumpa lagi, Admin '.$nama.'!', 'success');
} else {
    setNotif('Logout berhasil. Sampai jumpa lagi, '.$nama.'!', 'success');
}

echo "<script>window.location='index.php';</script>";
exit;
?>