<?php 
session_start();
include __DIR__ . '/koneksi.php';

if(!isset($_SESSION['user_id'])){
    setNotif('Silakan login terlebih dahulu!', 'error');
    echo "<script>window.location='index.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$nama = $_SESSION['nama'];
$role = $_SESSION['role'];
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
    <title>Peminjaman Master - Perpustakaan Bintang</title>
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

    <!-- Modal Logout (DaisyUI Native Dialog) -->
    <dialog id="modalLogout" class="modal z-50">
        <div class="modal-box p-6">
            <h3 class="text-lg font-bold text-red-400 flex items-center gap-2">
                <i data-lucide="log-out" class="icon-error"></i>
                Konfirmasi Keluar
            </h3>
            <p class="py-4 text-slate-300 text-sm">Apakah Anda yakin ingin keluar dari Perpustakaan Bintang, <span class="font-bold text-white"><?= htmlspecialchars($nama) ?></span>?</p>
            <div class="modal-action flex gap-2">
                <button class="btn btn-premium-ghost" onclick="tutupModalLogout()">Batal</button>
                <a href="logout.php" class="btn btn-premium-danger px-6 flex items-center justify-center">Keluar</a>
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
                <h2 class="text-3xl font-black tracking-tight flex items-center justify-center sm:justify-start gap-3 text-white">
                    <i data-lucide="library" class="icon-indigo w-8 h-8"></i>
                    <span>Riwayat Peminjaman Master</span>
                </h2>
                <p class="text-[10px] text-slate-400 opacity-70 mt-1 uppercase tracking-widest font-semibold ml-11">Tampilan Transaksi Dan Pengembalian Anggota</p>
            </div>
            <a href="dashboard.php" class="btn btn-premium-ghost border border-white/5 rounded-2xl hover:bg-white/5 text-slate-300 text-sm gap-2">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
            </a>
        </div>

        <!-- Master Loans Table Container -->
        <div class="glass-card p-6 shadow-xl">
            <div class="overflow-x-auto w-full">
                <table class="custom-table w-full">
                    <thead>
                        <tr>
                            <th class="w-12 text-center">No</th>
                            <th>Judul Buku</th>
                            <th class="text-center">Tanggal Pinjam</th>
                            <th class="text-center">Jatuh Tempo</th>
                            <th class="text-center">Status</th>
                            <th class="text-center w-36">Aksi Mandiri</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        $query = mysqli_query($koneksi, "SELECT p.*, b.judul FROM peminjaman p JOIN buku b ON p.buku_id=b.id WHERE p.user_id='$user_id' ORDER BY p.id DESC");
                        while($p = mysqli_fetch_assoc($query)):
                        ?>
                        <tr>
                            <td class="text-center text-slate-500 font-bold text-sm"><?= $no++ ?></td>
                            <td class="py-4 font-bold text-white"><?= htmlspecialchars($p['judul']) ?></td>
                            <td class="text-center"><span class="badge badge-premium-ghost py-2 px-3 text-slate-300 text-xs"><?= date('d-m-Y', strtotime($p['tanggal_pinjam'])) ?></span></td>
                            <td class="text-center"><span class="badge badge-premium-ghost py-2 px-3 text-slate-300 text-xs"><?= $p['tanggal_kembali'] ? date('d-m-Y', strtotime($p['tanggal_kembali'])) : '-' ?></span></td>
                            <td class="text-center">
                                <?php if($p['status'] == 'dipinjam'): ?>
                                    <span class="badge badge-glass-info py-2.5 px-3.5 text-xs">Sedang Dipinjam</span>
                                <?php else: ?>
                                    <span class="badge badge-glass-success py-2.5 px-3.5 text-xs">Sudah Kembali</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if($p['status'] == 'dipinjam'): ?>
                                    <a href="kembalikan_user.php?id=<?= $p['id'] ?>&buku_id=<?= $p['buku_id'] ?>" 
                                       onclick="return confirm('Apakah Anda yakin ingin mengembalikan buku ini?')" 
                                       class="btn btn-premium btn-xs rounded-xl px-4 py-1 text-xs text-white gap-1 flex items-center justify-center">
                                        <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Kembalikan
                                    </a>
                                <?php else: ?>
                                    <span class="text-xs text-slate-500 font-semibold">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php if(mysqli_num_rows($query) == 0): ?>
                            <tr>
                                <td colspan="6" class="text-center py-10 text-slate-500 text-sm">
                                    Belum ada transaksi peminjaman master terdaftar.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
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
    </script>
    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>