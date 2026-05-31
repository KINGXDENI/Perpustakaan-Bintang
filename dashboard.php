<?php
include 'koneksi.php';

if(!isset($_SESSION['user_id'])){
    setNotif('Silakan login terlebih dahulu!', 'error');
    echo "<script>window.location='index.php';</script>";
    exit;
}

$role = $_SESSION['role'];
$nama = $_SESSION['nama'];
$email = $_SESSION['email'] ?? 'admin@mail.com';
$user_id = $_SESSION['user_id'];

$buku = mysqli_query($koneksi, "SELECT * FROM buku ORDER BY id DESC LIMIT 5");
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
    <title>Dashboard - Perpustakaan Bintang</title>
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
<body class="min-h-screen pb-12">

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

    <!-- Modal Logout (DaisyUI Native Dialog with Premium Design) -->
    <dialog id="modalLogout" class="modal z-50">
        <div class="modal-box p-6 border border-[var(--glass-border)] shadow-2xl relative overflow-hidden">
            <!-- Glow background decor -->
            <div class="absolute -top-10 -right-10 w-24 h-24 rounded-full bg-[var(--accent-primary)]/10 blur-xl"></div>
            
            <h3 class="text-lg font-bold text-[var(--accent-danger)] flex items-center gap-2">
                <div class="p-2 rounded-xl bg-[var(--accent-danger)]/10 flex items-center justify-center">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                </div>
                Konfirmasi Keluar
            </h3>
            <p class="py-4 text-secondary-theme text-sm">Apakah Anda yakin ingin keluar dari Perpustakaan Bintang, <span class="font-bold text-primary-theme"><?= htmlspecialchars($nama) ?></span>?</p>
            <div class="modal-action flex gap-2 pt-2">
                <button class="btn btn-premium-ghost rounded-xl px-5" onclick="tutupModalLogout()">Batal</button>
                <a href="logout.php" class="btn btn-premium-danger px-6 rounded-xl flex items-center justify-center gap-2">
                    <i data-lucide="log-out" class="w-4 h-4"></i> Keluar
                </a>
            </div>
        </div>
    </dialog>

    <!-- Navigation Header (Floating Glass Nav) -->
    <?php include 'navbar.php'; ?>

    <!-- Main Container -->
    <main class="container mx-auto px-4 mt-8 max-w-6xl">
        
        <!-- Hero Section & Typing Quote Widget -->
        <div class="glass-card p-6 sm:p-8 shadow-xl mb-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="space-y-2 text-center md:text-left w-full md:w-auto">
                <h1 class="text-3xl font-extrabold tracking-tight flex items-center justify-center md:justify-start gap-2.5 text-primary-theme">
                    Selamat Datang, <span class="text-indigo-gradient font-black"><?= htmlspecialchars(ucfirst($nama)) ?></span>!
                </h1>
                <p class="text-secondary-theme text-sm">
                    Email: <span class="text-primary-theme font-semibold"><?= htmlspecialchars($email) ?></span> <span class="text-secondary-theme opacity-50 mx-2">|</span> Anda login sebagai <span class="badge <?= $role === 'admin' ? 'badge-glass-admin' : 'badge-glass-user' ?> uppercase px-2 py-1 ml-1"><?= strtoupper($role) ?></span>
                </p>
            </div>
            
            <div class="bg-black/10 border border-white/5 rounded-2xl px-6 py-4 max-w-md w-full shadow-inner flex items-center gap-4">
                <i data-lucide="quote" class="icon-gold filter drop-shadow-[0_0_8px_rgba(251,191,36,0.5)] w-6 h-6"></i>
                <div>
                    <p class="text-[9px] text-secondary-theme opacity-60 font-bold uppercase tracking-widest mb-0.5">Inspirasi Bintang</p>
                    <p class="text-xs font-medium italic text-secondary-theme min-h-[40px] flex items-center leading-relaxed" id="quoteText"></p>
                </div>
            </div>
        </div>

        <!-- Menu Action Cards -->
        <div class="mb-8">
            <h2 class="text-lg font-bold text-secondary-theme mb-4 flex items-center gap-2">
                <i data-lucide="layers" class="icon-indigo"></i>
                Akses Fitur <?= $role == 'admin' ? 'Administrator' : 'Anggota' ?>
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php if($role == 'admin'): ?>
                    <!-- Kelola Buku Card -->
                    <a href="kelola_buku.php" class="glass-card glass-card-hover p-5 flex items-center gap-4 shadow-lg group">
                        <div class="w-12 h-12 rounded-2xl bg-[var(--accent-primary)]/10 text-[var(--accent-primary)] border border-[var(--glass-border)] flex items-center justify-center group-hover:scale-110 duration-300 shadow-lg">
                            <i data-lucide="settings" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-primary-theme group-hover:text-[var(--accent-primary)] transition-colors text-base">Kelola Buku</h3>
                            <p class="text-xs text-secondary-theme opacity-80 mt-0.5">Edit, tambah, dan hapus katalog buku perpustakaan.</p>
                        </div>
                    </a>

                    <!-- Data Peminjaman Card -->
                    <a href="admin_pinjam.php" class="glass-card glass-card-hover p-5 flex items-center gap-4 shadow-lg group">
                        <div class="w-12 h-12 rounded-2xl bg-[var(--accent-primary)]/10 text-[var(--accent-primary)] border border-[var(--glass-border)] flex items-center justify-center group-hover:scale-110 duration-300 shadow-lg">
                            <i data-lucide="clipboard-list" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-primary-theme group-hover:text-[var(--accent-primary)] transition-colors text-base">Data Peminjaman</h3>
                            <p class="text-xs text-secondary-theme opacity-80 mt-0.5">Pantau buku yang dipinjam & kelola pengembalian.</p>
                        </div>
                    </a>

                    <!-- Tambah Buku Card -->
                    <a href="tambah_buku.php" class="glass-card glass-card-hover p-5 flex items-center gap-4 shadow-lg group">
                        <div class="w-12 h-12 rounded-2xl bg-[var(--accent-primary)]/10 text-[var(--accent-primary)] border border-[var(--glass-border)] flex items-center justify-center group-hover:scale-110 duration-300 shadow-lg">
                            <i data-lucide="plus-circle" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-primary-theme group-hover:text-[var(--accent-primary)] transition-colors text-base">Tambah Buku Baru</h3>
                            <p class="text-xs text-secondary-theme opacity-80 mt-0.5">Input langsung judul buku baru ke dalam database.</p>
                        </div>
                    </a>
                <?php else: ?>
                    <!-- Pinjam Buku Card -->
                    <a href="pinjam.php" class="glass-card glass-card-hover p-6 flex items-center gap-5 shadow-lg group md:col-span-2">
                        <div class="w-14 h-14 rounded-2xl bg-[var(--accent-primary)]/10 text-[var(--accent-primary)] border border-[var(--glass-border)] flex items-center justify-center group-hover:scale-110 duration-300 shadow-lg">
                            <i data-lucide="book-open" class="w-7 h-7"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-primary-theme group-hover:text-[var(--accent-primary)] transition-colors text-lg">Pinjam Buku Anggota</h3>
                            <p class="text-sm text-secondary-theme opacity-80 mt-0.5">Jelajahi dan pinjam ribuan buku berkualitas yang tersedia.</p>
                        </div>
                    </a>

                    <!-- Riwayat Pinjaman Card -->
                    <a href="riwayat.php" class="glass-card glass-card-hover p-6 flex items-center gap-5 shadow-lg group">
                        <div class="w-14 h-14 rounded-2xl bg-[var(--accent-primary)]/10 text-[var(--accent-primary)] border border-[var(--glass-border)] flex items-center justify-center group-hover:scale-110 duration-300 shadow-lg">
                            <i data-lucide="history" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-primary-theme group-hover:text-[var(--accent-primary)] transition-colors text-base">Riwayat Pinjaman</h3>
                            <p class="text-xs text-secondary-theme opacity-80 mt-0.5">Lihat riwayat transaksi dan buku yang sedang dipinjam.</p>
                        </div>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Latest Books Table & Sidebar Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Books Table -->
            <div class="lg:col-span-2 glass-card p-6 shadow-xl">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-bold flex items-center gap-2 text-primary-theme">
                        <i data-lucide="book-open" class="icon-gold"></i>
                        Buku Terbaru
                    </h2>
                    <a href="daftar_buku.php" class="text-xs font-semibold text-[var(--accent-primary)] hover:text-[var(--accent-gold-glow)] transition-colors">Lihat Semua →</a>
                </div>
                
                <div class="overflow-x-auto w-full">
                    <table class="custom-table w-full">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Penulis</th>
                                <th class="text-center">Tahun</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($buku) > 0): ?>
                                <?php while($b = mysqli_fetch_assoc($buku)):
                                    $udah_pinjam = 0;
                                    if($role == 'user'){
                                        $udah_pinjam = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM peminjaman WHERE user_id=$user_id AND buku_id={$b['id']} AND status='dipinjam'"));
                                    }
                                ?>
                                <tr>
                                    <td class="font-bold text-primary-theme"><?= htmlspecialchars($b['judul']) ?></td>
                                    <td class="text-secondary-theme font-medium"><?= htmlspecialchars($b['penulis']) ?></td>
                                    <td class="text-center"><span class="badge badge-premium-ghost py-2 px-3 text-xs"><?= $b['tahun'] ?></span></td>
                                    <td class="text-center">
                                        <?php if($udah_pinjam > 0): ?>
                                            <span class="badge badge-glass-info py-2.5 px-3">Dipinjam</span>
                                        <?php elseif($b['stok'] > 0): ?>
                                            <span class="badge badge-glass-success py-2.5 px-3"><?= $b['stok'] ?> Tersedia</span>
                                        <?php else: ?>
                                            <span class="badge badge-glass-error py-2.5 px-3">Habis</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-6 text-secondary-theme opacity-60 text-sm">
                                        Belum ada buku di Perpustakaan Bintang.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Information Sidebar -->
            <div class="glass-card p-6 shadow-xl space-y-4">
                <h3 class="text-lg font-bold flex items-center gap-2.5 text-primary-theme">
                    <i data-lucide="help-circle" class="icon-indigo"></i>
                    Informasi Layanan
                </h3>
                <div class="border-t border-[var(--glass-border)] my-2"></div>
                <div class="space-y-4 text-xs text-secondary-theme leading-relaxed">
                    <p class="font-semibold text-primary-theme">Berikut petunjuk penggunaan sistem Perpustakaan Bintang:</p>
                    
                    <?php if($role == 'admin'): ?>
                        <div class="flex gap-3 items-start">
                            <span class="text-[var(--accent-primary)] font-extrabold">01.</span>
                            <p>Gunakan menu <span class="font-semibold text-primary-theme">Kelola Buku</span> untuk mengedit detail buku atau menghapusnya jika diperlukan.</p>
                        </div>
                        <div class="flex gap-3 items-start">
                            <span class="text-[var(--accent-primary)] font-extrabold">02.</span>
                            <p>Menu <span class="font-semibold text-primary-theme">Data Peminjaman</span> berguna untuk memverifikasi dan mengonfirmasi pengembalian buku anggota.</p>
                        </div>
                        <div class="flex gap-3 items-start">
                            <span class="text-[var(--accent-primary)] font-extrabold">03.</span>
                            <p>Gunakan menu <span class="font-semibold text-primary-theme">Tambah Buku Baru</span> untuk menginput buku baru secara instan.</p>
                        </div>
                    <?php else: ?>
                        <div class="flex gap-3 items-start">
                            <span class="text-[var(--accent-primary)] font-extrabold">01.</span>
                            <p>Pilih menu <span class="font-semibold text-primary-theme">Pinjam Buku</span> untuk mencari buku dan menentukan jangka waktu peminjaman Anda.</p>
                        </div>
                        <div class="flex gap-3 items-start">
                            <span class="text-[var(--accent-primary)] font-extrabold">02.</span>
                            <p>Kunjungi menu <span class="font-semibold text-primary-theme">Riwayat Pinjaman</span> untuk memantau jatuh tempo pengembalian buku.</p>
                        </div>
                        <div class="flex gap-3 items-start">
                            <span class="text-[var(--accent-primary)] font-extrabold">03.</span>
                            <p>Maksimal durasi peminjaman adalah 7 hari dan batas pinjam akan diverifikasi otomatis.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </main>

    <!-- JS Scripts -->
    <script>
        function bukaModalLogout(){
            document.getElementById('modalLogout').showModal();
        }
        function tutupModalLogout(){
            document.getElementById('modalLogout').close();
        }
        
        const quotes = [
            "Buku adalah jendela menuju semesta ilmu pengetahuan.",
            "Membaca satu buku berarti menjelajahi satu semesta baru.",
            "Perpustakaan adalah portal menuju dimensi pengetahuan tanpa batas.",
            "Ilmu pengetahuan tak terbatas layaknya gugusan bintang di angkasa."
        ];
        let quoteIndex = 0;
        let charIndex = 0;
        
        function typeQuote() {
            const el = document.getElementById('quoteText');
            if(!el) return;
            if (charIndex < quotes[quoteIndex].length) {
                el.textContent += quotes[quoteIndex].charAt(charIndex);
                charIndex++;
                setTimeout(typeQuote, 40);
            } else {
                setTimeout(() => {
                    el.textContent = '';
                    charIndex = 0;
                    quoteIndex = (quoteIndex + 1) % quotes.length;
                    typeQuote();
                }, 4000);
            }
        }
        window.addEventListener('DOMContentLoaded', typeQuote);
    </script>
    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>