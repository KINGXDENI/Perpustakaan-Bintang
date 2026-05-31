<?php
include 'koneksi.php';

if(isset($_SESSION['user_id'])){
    echo "<script>window.location='dashboard.php';</script>";
    exit;
}

if(isset($_POST['login'])){
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];
    
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email'");
    $user = mysqli_fetch_assoc($query);
    
    if($user && password_verify($password, $user['password'])){
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        setNotif('Selamat datang di Perpustakaan Bintang, '.$user['nama'].'!', 'success');
        echo "<script>window.location='dashboard.php';</script>";
        exit;
    } else {
        setNotif('Email atau password salah!', 'error');
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
    <title>Login - Perpustakaan Bintang</title>
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

    <!-- Notifikasi Sistem (Toast Alert) -->
    <?php if(isset($_SESSION['notif'])): 
        $icon = $_SESSION['notif']['tipe'] === 'success' ? 'check-circle' : 'alert-triangle';
        $iconColor = $_SESSION['notif']['tipe'] === 'success' ? 'icon-success' : 'icon-error';
    ?>
    <div class="toast toast-top toast-end z-50 transition-all duration-300">
        <div class="alert toast-glass shadow-2xl flex items-center gap-3 py-3.5 px-5">
            <i data-lucide="<?= $icon ?>" class="<?= $iconColor ?>"></i>
            <span class="font-semibold text-sm text-primary-theme"><?= htmlspecialchars($_SESSION['notif']['pesan']) ?></span>
        </div>
    </div>
    <script>
        setTimeout(() => {
            const toast = document.querySelector('.toast');
            if (toast) {
                toast.classList.add('opacity-0', 'scale-95');
                setTimeout(() => toast.remove(), 300);
            }
        }, 4000);
    </script>
    <?php unset($_SESSION['notif']); endif; ?>

    <!-- Login Card Container -->
    <div class="card w-full max-w-md glass-card overflow-hidden shadow-2xl transition-all duration-500 hover:shadow-[rgba(177,116,87,0.15)]">
        <!-- Glow top line -->
        <div class="h-1 w-full bg-gradient-to-r from-[var(--accent-primary)] via-[var(--accent-secondary)] to-[var(--accent-primary)] opacity-60"></div>
        
        <div class="card-body p-8 sm:p-10">
            <!-- Header/Logo -->
            <div class="flex flex-col items-center mb-6">
                <a href="index.php" class="w-16 h-16 rounded-2xl bg-[var(--accent-primary)]/10 border border-[var(--accent-primary)]/20 flex items-center justify-center shadow-lg shadow-[var(--accent-primary)]/10 mb-4 transition-transform hover:scale-110 duration-300">
                    <i data-lucide="sparkles" class="icon-indigo w-7 h-7 filter drop-shadow-[0_0_8px_rgba(177,116,87,0.5)]"></i>
                </a>
                <h2 class="text-3xl font-extrabold text-center tracking-tight text-primary-theme">
                    Perpustakaan <span class="text-gold-gradient">Bintang</span>
                </h2>
                <p class="text-[10px] text-secondary-theme opacity-70 mt-1 tracking-widest uppercase font-semibold">Pintu Gerbang Semesta Ilmu</p>
            </div>

            <!-- Login Form -->
            <form method="POST" action="login.php" class="space-y-4">
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
                    <button type="submit" name="login" class="btn btn-premium w-full text-white font-bold tracking-wide transition-all duration-300">
                        Masuk
                    </button>
                </div>
            </form>

            <!-- Divider -->
            <div class="divider my-6 text-[10px] text-secondary-theme uppercase tracking-widest opacity-60">Atau</div>

            <!-- Registration Link -->
            <div class="text-center">
                <p class="text-sm text-secondary-theme">
                    Belum memiliki akun? 
                    <a href="register.php" class="text-[var(--accent-primary)] hover:text-[var(--accent-gold-glow)] transition-colors font-bold ml-1">Daftar Sekarang</a>
                </p>
                <div class="mt-4">
                    <a href="index.php" class="text-xs text-secondary-theme opacity-60 hover:opacity-100 flex items-center justify-center gap-1">
                        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
