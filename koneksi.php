<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$koneksi = mysqli_connect("localhost", "root", "", "uts_perpustakaan");

if(!$koneksi){
    die("Koneksi gagal: ".mysqli_connect_error());
}

function setNotif($pesan, $tipe='success'){
    $_SESSION['notif'] = ['pesan'=>$pesan, 'tipe'=>$tipe];
}
?>