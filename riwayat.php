<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['user_id'])){
    setNotif('Silakan login terlebih dahulu!', 'error');
    echo "<script>window.location='index.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$nama = $_SESSION['nama'];
$role = $_SESSION['role'];

$data = mysqli_query($koneksi, "SELECT p.*, b.judul, b.penulis FROM peminjaman p 
JOIN buku b ON p.buku_id=b.id 
WHERE p.user_id=$user_id ORDER BY p.id DESC");
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
    <title>Riwayat Pinjaman - Perpustakaan Bintang</title>
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
        
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-8">
            <div class="text-center sm:text-left">
                <h2 class="text-3xl font-black tracking-tight flex items-center justify-center sm:justify-start gap-3 text-primary-theme">
                    <i data-lucide="history" class="icon-indigo w-8 h-8"></i> 
                    <span>Riwayat Peminjaman Buku</span>
                </h2>
                <p class="text-[10px] text-secondary-theme opacity-80 mt-1 uppercase tracking-widest font-semibold ml-11">Pantau Transaksi Peminjaman Dan Jatuh Tempo</p>
            </div>
            <a href="dashboard.php" class="btn btn-premium-ghost rounded-2xl text-secondary-theme text-sm gap-2">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
            </a>
        </div>

        <!-- History Table Container -->
        <div class="glass-card p-6 shadow-xl">
            <div class="overflow-x-auto w-full">
                <table class="custom-table w-full">
                    <thead>
                        <tr>
                            <th class="w-12 text-center">No</th>
                            <th>Judul Buku</th>
                            <th>Penulis</th>
                            <th class="text-center">Tanggal Pinjam</th>
                            <th class="text-center">Jatuh Tempo</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while($p = mysqli_fetch_assoc($data)): ?>
                        <tr>
                            <td class="text-center text-secondary-theme opacity-70 font-bold text-sm"><?= $no++ ?></td>
                            <td class="py-4 font-bold text-primary-theme"><?= htmlspecialchars($p['judul']) ?></td>
                            <td class="text-secondary-theme font-medium"><?= htmlspecialchars($p['penulis']) ?></td>
                            <td class="text-center"><span class="badge badge-premium-ghost py-2 px-3 text-xs"><?= $p['tanggal_pinjam'] ? date('d-m-Y', strtotime($p['tanggal_pinjam'])) : '-' ?></span></td>
                            <td class="text-center"><span class="badge badge-premium-ghost py-2 px-3 text-xs"><?= $p['tanggal_kembali'] ? date('d-m-Y', strtotime($p['tanggal_kembali'])) : '-' ?></span></td>
                            <td class="text-center">
                                <?php if($p['status'] == 'dipinjam'): ?>
                                    <span class="badge badge-glass-info py-2.5 px-3.5">Sedang Dipinjam</span>
                                <?php else: ?>
                                    <span class="badge badge-glass-success py-2.5 px-3.5">Dikembalikan</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php if(mysqli_num_rows($data) == 0): ?>
                            <tr>
                                <td colspan="6" class="text-center py-10 text-secondary-theme opacity-65 text-sm">
                                    Belum ada transaksi peminjaman buku yang tercatat.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <script>
        function bukaModalLogout(){
            document.getElementById('modalLogout').showModal();
        }
        function tutupModalLogout(){
            document.getElementById('modalLogout').close();
        }
    </script>
    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>