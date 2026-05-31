<?php
session_start();
include 'koneksi.php';
$error = '';

if(isset($_POST['daftar'])){
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email'");
    if(mysqli_num_rows($cek) > 0){
        $error = "Email sudah terdaftar!";
    } else {
        mysqli_query($koneksi, "INSERT INTO users (nama, email, password, role) VALUES ('$nama', '$email', '$password', 'user')");
        setNotif('Registrasi berhasil! Silakan masuk.', 'success');
        header("Location: login.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id" data-theme="night">
<head>
    <script>
        const savedTheme = localStorage.getItem('theme') || 'dark';
        document.documentElement.setAttribute('data-theme', savedTheme);
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Perpustakaan Bintang</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS & DaisyUI CDN -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Custom Style Sheet -->
    <link rel="stylesheet" href="style.css">
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <!-- Register Card Container -->
    <div class="card w-full max-w-md glass-card overflow-hidden shadow-2xl transition-all duration-500 hover:shadow-[rgba(177,116,87,0.15)]">
        <!-- Glow top line -->
        <div class="h-1 w-full bg-gradient-to-r from-[var(--accent-primary)] via-[var(--accent-secondary)] to-[var(--accent-primary)] opacity-60"></div>
        
        <div class="card-body p-8 sm:p-10">
            <!-- Header/Logo -->
            <div class="flex flex-col items-center mb-6">
                <div class="w-16 h-16 rounded-2xl bg-[var(--accent-primary)]/10 border border-[var(--accent-primary)]/20 flex items-center justify-center shadow-lg shadow-[var(--accent-primary)]/10 mb-4 transition-transform hover:scale-110 duration-300">
                    <i data-lucide="user-plus" class="icon-indigo w-7 h-7 filter drop-shadow-[0_0_8px_rgba(177,116,87,0.5)]"></i>
                </div>
                <h2 class="text-3xl font-extrabold text-center tracking-tight text-primary-theme">
                    Daftar <span class="text-gold-gradient">Akun</span>
                </h2>
                <p class="text-[10px] text-secondary-theme opacity-80 mt-1 tracking-widest uppercase font-semibold">Mulai Menjelajahi Cakrawala Baru</p>
            </div>

            <!-- Error Notification -->
            <?php if($error): ?>
                <div class="alert alert-error shadow-lg mb-4 border border-[var(--glass-border)] flex items-center gap-3 py-3 rounded-xl">
                    <i data-lucide="alert-triangle" class="icon-error w-5 h-5"></i>
                    <span class="text-xs font-semibold text-primary-theme"><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <!-- Registration Form -->
            <form method="POST" class="space-y-4">
                <div class="form-control w-full">
                    <label class="label pb-1">
                        <span class="label-text font-semibold text-secondary-theme text-sm">Nama Lengkap</span>
                    </label>
                    <input type="text" name="nama" placeholder="Nama Lengkap" class="input input-glass w-full" required>
                </div>

                <div class="form-control w-full">
                    <label class="label pb-1">
                        <span class="label-text font-semibold text-secondary-theme text-sm">Email</span>
                    </label>
                    <input type="email" name="email" placeholder="nama@email.com" class="input input-glass w-full" required>
                </div>

                <div class="form-control w-full">
                    <label class="label pb-1">
                        <span class="label-text font-semibold text-secondary-theme text-sm">Password</span>
                    </label>
                    <input type="password" name="password" placeholder="••••••••" class="input input-glass w-full" required>
                </div>

                <div class="pt-4">
                    <button type="submit" name="daftar" class="btn btn-premium w-full text-white font-bold tracking-wide transition-all duration-300">
                        Daftar Akun
                    </button>
                </div>
            </form>

            <!-- Divider -->
            <div class="divider my-6 text-[10px] text-secondary-theme uppercase tracking-widest opacity-60">Atau</div>

            <!-- Login Link -->
            <div class="text-center">
                <p class="text-sm text-secondary-theme">
                    Sudah memiliki akun? 
                    <a href="login.php" class="text-[var(--accent-primary)] hover:text-[var(--accent-gold-glow)] transition-colors font-bold ml-1">Masuk Sekarang</a>
                </p>
            </div>
        </div>
    </div>

    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>