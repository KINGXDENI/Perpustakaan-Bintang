<?php session_start(); include 'koneksi.php';
if(isset($_POST['email'])){
    $email = mysqli_real_escape_string($koneksi,$_POST['email']);
    $password = $_POST['password'];
    $query = mysqli_query($koneksi,"SELECT * FROM users WHERE email='$email' LIMIT 1");
    $data = mysqli_fetch_assoc($query);
    if($data && password_verify($password,$data['password'])){
        $_SESSION['user_id'] = $data['id']; // WAJIB buat tabel peminjaman
        $_SESSION['user'] = $data['email'];
        $_SESSION['role'] = $data['role'];
        $_SESSION['nama'] = $data['nama'];
        header("Location: dashboard.php"); exit;
    } else { echo "<script>alert('Email/Password salah!'); window.location='index.php';</script>"; }
} else { header("Location: index.php"); } ?>