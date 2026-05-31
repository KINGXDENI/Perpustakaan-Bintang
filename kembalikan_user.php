<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['user_id'])){
    setNotif('Silakan login terlebih dahulu!', 'error');
    echo "<script>window.location='index.php';</script>";
    exit;
}

$id = (int)$_GET['id'];
$buku_id = (int)$_GET['buku_id'];
$id_user = $_SESSION['user_id'];

// Validasi: pastikan peminjaman ini milik user yang login dan memang sedang dipinjam
$cek = mysqli_query($koneksi, "SELECT p.*, b.judul FROM peminjaman p JOIN buku b ON p.buku_id=b.id WHERE p.id='$id' AND p.user_id='$id_user' AND p.status='dipinjam'");
$data = mysqli_fetch_assoc($cek);

if(!$data){
    setNotif('Transaksi peminjaman tidak valid!', 'error');
    echo "<script>window.location='peminjaman.php';</script>";
    exit;
}

// 1. Update status peminjaman & isi tanggal kembali
mysqli_query($koneksi, "UPDATE peminjaman SET status='dikembalikan', tanggal_kembali=NOW() WHERE id='$id'");

// 2. Update stok buku (ditambah 1) konsisten dengan kelola_buku
mysqli_query($koneksi, "UPDATE buku SET stok = stok + 1 WHERE id='$buku_id'");

setNotif('Sukses! Buku "' . $data['judul'] . '" berhasil dikembalikan.', 'success');
echo "<script>window.location='peminjaman.php';</script>";
exit;
?>