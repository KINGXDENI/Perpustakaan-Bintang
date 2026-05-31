<?php
include 'koneksi.php';

if(isset($_SESSION['user_id'])){
    echo "<script>window.location='dashboard.php';</script>";
    exit;
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
    <title>Perpustakaan Bintang - Pintu Gerbang Semesta Ilmu</title>
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
<body class="min-h-screen flex flex-col justify-between overflow-x-hidden">

    <!-- Landing Navigation Header (Floating Glass Nav) -->
    <header class="w-full max-w-6xl mx-auto px-4 mt-6">
        <div class="navbar floating-nav flex items-center justify-between shadow-xl">
            <!-- Brand Logo -->
            <div class="flex-1">
                <a href="index.php" class="btn btn-ghost text-lg font-bold tracking-tight gap-2 rounded-xl hover:bg-[rgba(177,116,87,0.08)] transition-all">
                    <i data-lucide="sparkles" class="icon-brand filter drop-shadow-[0_0_8px_var(--accent-primary)] w-5 h-5"></i>
                    <span class="text-primary-theme">Perpustakaan <span class="text-gold-gradient">Bintang</span></span>
                </a>
            </div>
            
            <!-- CTAs -->
            <div class="flex items-center gap-3">
                <a href="login.php" class="btn btn-premium-ghost rounded-xl text-xs px-4 py-2 hover:bg-[rgba(177,116,87,0.08)] text-secondary-theme">Masuk</a>
                <a href="register.php" class="btn btn-premium rounded-xl text-xs px-5 py-2 text-white font-bold transition-all shadow-md">Daftar</a>
            </div>
        </div>
    </header>

    <!-- Main Content (Hero & Features Showcase) -->
    <main class="flex-grow container mx-auto px-4 py-16 max-w-6xl flex flex-col items-center justify-center">
        <!-- Hero Section -->
        <div class="text-center max-w-3xl flex flex-col items-center">
            <!-- Subtle badge decoration -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[var(--accent-primary)]/10 border border-[var(--accent-primary)]/20 shadow-inner mb-6 animate-pulse">
                <i data-lucide="sparkles" class="w-3.5 h-3.5 icon-indigo"></i>
                <span class="text-[10px] tracking-wider font-extrabold uppercase text-[var(--accent-primary)]">Transformasi Perpustakaan Digital</span>
            </div>
            
            <!-- Hero Title -->
            <h1 class="text-4xl sm:text-6xl font-black tracking-tight leading-tight text-primary-theme">
                Pintu Gerbang <br class="hidden sm:inline">
                <span class="text-gold-gradient">Semesta Ilmu</span>
            </h1>
            
            <!-- Hero Subtitle -->
            <p class="text-sm sm:text-lg text-secondary-theme opacity-80 mt-6 max-w-2xl leading-relaxed">
                Jelajahi ribuan literatur berkualitas, kelola sirkulasi buku secara mandiri, dan nikmati pengalaman belajar premium dengan antarmuka SaaS modern.
            </p>
            
            <!-- Main CTA Buttons -->
            <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center w-full max-w-md">
                <a href="login.php" class="btn btn-premium btn-lg w-full sm:w-auto px-8 rounded-2xl flex items-center justify-center gap-2 py-4">
                    <span>Mulai Menjelajah</span>
                    <i data-lucide="arrow-right" class="w-5 h-5"></i>
                </a>
                <a href="register.php" class="btn btn-premium-ghost btn-lg w-full sm:w-auto px-8 rounded-2xl flex items-center justify-center gap-2 py-4 text-secondary-theme">
                    <i data-lucide="user-plus" class="w-5 h-5"></i>
                    <span>Daftar Akun</span>
                </a>
            </div>
        </div>

        <!-- Floating Stats Highlight Row -->
        <div class="w-full mt-20 grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="glass-card p-6 flex items-center gap-4 hover:border-[var(--accent-primary)]/30 transition-all duration-300">
                <div class="w-12 h-12 rounded-xl bg-[var(--accent-primary)]/10 flex items-center justify-center shadow-lg border border-[var(--accent-primary)]/20">
                    <i data-lucide="book-open" class="icon-brand w-5 h-5"></i>
                </div>
                <div>
                    <h4 class="text-2xl font-black text-primary-theme">1,200+</h4>
                    <p class="text-xs text-secondary-theme opacity-70">Buku Katalog Master</p>
                </div>
            </div>

            <div class="glass-card p-6 flex items-center gap-4 hover:border-[var(--accent-primary)]/30 transition-all duration-300">
                <div class="w-12 h-12 rounded-xl bg-[var(--accent-primary)]/10 flex items-center justify-center shadow-lg border border-[var(--accent-primary)]/20">
                    <i data-lucide="users" class="icon-brand w-5 h-5"></i>
                </div>
                <div>
                    <h4 class="text-2xl font-black text-primary-theme">500+</h4>
                    <p class="text-xs text-secondary-theme opacity-70">Anggota Aktif</p>
                </div>
            </div>

            <div class="glass-card p-6 flex items-center gap-4 hover:border-[var(--accent-primary)]/30 transition-all duration-300">
                <div class="w-12 h-12 rounded-xl bg-[var(--accent-primary)]/10 flex items-center justify-center shadow-lg border border-[var(--accent-primary)]/20">
                    <i data-lucide="clock" class="icon-brand w-5 h-5"></i>
                </div>
                <div>
                    <h4 class="text-2xl font-black text-primary-theme">24/7</h4>
                    <p class="text-xs text-secondary-theme opacity-70">Akses Sirkulasi Mandiri</p>
                </div>
            </div>
        </div>

        <!-- Sleek Feature Showcase Section -->
        <div class="w-full mt-24">
            <div class="text-center mb-12">
                <h3 class="text-2xl sm:text-3xl font-black text-primary-theme">Fungsionalitas Premium SaaS</h3>
                <p class="text-xs text-secondary-theme opacity-70 mt-2">Segala kemudahan sirkulasi perpustakaan dalam genggaman Anda</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Feature 1 -->
                <div class="glass-card p-6 flex flex-col justify-between hover:border-[var(--accent-primary)]/30 transition-all duration-300 h-full">
                    <div class="w-10 h-10 rounded-xl bg-[var(--accent-primary)]/10 flex items-center justify-center mb-4 border border-[var(--accent-primary)]/20">
                        <i data-lucide="search" class="icon-brand w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-sm text-primary-theme">Pencarian Cerdas</h4>
                        <p class="text-[11px] text-secondary-theme opacity-80 mt-2 leading-relaxed">
                            Telusuri daftar buku secara cepat dengan fitur pencarian real-time dan katalog terstruktur.
                        </p>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="glass-card p-6 flex flex-col justify-between hover:border-[var(--accent-primary)]/30 transition-all duration-300 h-full">
                    <div class="w-10 h-10 rounded-xl bg-[var(--accent-primary)]/10 flex items-center justify-center mb-4 border border-[var(--accent-primary)]/20">
                        <i data-lucide="refresh-cw" class="icon-brand w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-sm text-primary-theme">Sirkulasi Mandiri</h4>
                        <p class="text-[11px] text-secondary-theme opacity-80 mt-2 leading-relaxed">
                            Ajukan peminjaman buku favorit Anda kapan saja dan pantau langsung waktu pengembalian.
                        </p>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="glass-card p-6 flex flex-col justify-between hover:border-[var(--accent-primary)]/30 transition-all duration-300 h-full">
                    <div class="w-10 h-10 rounded-xl bg-[var(--accent-primary)]/10 flex items-center justify-center mb-4 border border-[var(--accent-primary)]/20">
                        <i data-lucide="history" class="icon-brand w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-sm text-primary-theme">Transparansi Riwayat</h4>
                        <p class="text-[11px] text-secondary-theme opacity-80 mt-2 leading-relaxed">
                            Semua catatan transaksi peminjaman terdokumentasi rapi demi kemudahan pemantauan.
                        </p>
                    </div>
                </div>

                <!-- Feature 4 -->
                <div class="glass-card p-6 flex flex-col justify-between hover:border-[var(--accent-primary)]/30 transition-all duration-300 h-full">
                    <div class="w-10 h-10 rounded-xl bg-[var(--accent-primary)]/10 flex items-center justify-center mb-4 border border-[var(--accent-primary)]/20">
                        <i data-lucide="sun-moon" class="icon-brand w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-sm text-primary-theme">Tema Earthy Dinamis</h4>
                        <p class="text-[11px] text-secondary-theme opacity-80 mt-2 leading-relaxed">
                            Dukungan tema gelap/terang alami yang ramah mata dengan penyimpanan otomatis.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Premium Footer -->
    <footer class="w-full py-8 mt-16 border-t border-[var(--glass-border)]">
        <div class="container mx-auto px-4 max-w-6xl flex flex-col sm:flex-row justify-between items-center gap-4 text-center sm:text-left">
            <p class="text-xs text-secondary-theme opacity-60">
                &copy; 2026 Perpustakaan Bintang. All rights reserved.
            </p>
            <div class="flex gap-6 text-xs text-secondary-theme opacity-60">
                <a href="#" class="hover:opacity-100 transition-opacity">Kebijakan Privasi</a>
                <a href="#" class="hover:opacity-100 transition-opacity">Syarat & Ketentuan</a>
                <a href="login.php" class="hover:opacity-100 transition-opacity">Akses Admin</a>
            </div>
        </div>
    </footer>

    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>