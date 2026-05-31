<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role']!='admin'){
    header("Location: index.php"); exit;
}

$id = $_GET['id'];
$buku_id = $_GET['buku_id'];

// Update status jadi dikembalikan + isi tgl_kembali
mysqli_query($koneksi,"UPDATE peminjaman SET status='dikembalikan', tanggal_kembali=NOW() WHERE id=$id");

// Tambah stok buku
mysqli_query($koneksi,"UPDATE buku SET stok = stok + 1 WHERE id=$buku_id");

echo "<script>alert('Buku berhasil dikembalikan!'); window.location='admin_pinjam.php';</script>";
?>