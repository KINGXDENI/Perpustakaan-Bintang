<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$koneksi = mysqli_connect("database-perpustakaan-q6gl4b", "perpustakaan", "123perpustakaan", "perpustakaan", 3306);

if(!$koneksi){
    die("Koneksi gagal: ".mysqli_connect_error());
}

function setNotif($pesan, $tipe='success'){
    $_SESSION['notif'] = ['pesan'=>$pesan, 'tipe'=>$tipe];
}
?>