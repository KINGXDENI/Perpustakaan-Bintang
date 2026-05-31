<?php
include 'koneksi.php';

if(!isset($_SESSION['user_id'])){
    setNotif('Silakan login terlebih dahulu!', 'error');
    echo "<script>window.location='index.php';</script>";
    exit;
}
if($_SESSION['role'] != 'admin'){
    echo "<script>window.location='dashboard.php';</script>";
    exit;
}

// PROSES KEMBALIKAN BUKU
if(isset($_GET['kembali'])){
    $id_pinjam = (int)$_GET['kembali'];
    
    // Ambil data buat notif + update stok
    $q = mysqli_query($koneksi, "SELECT p.buku_id, b.judul, u.nama FROM peminjaman p JOIN buku b ON p.buku_id=b.id JOIN users u ON p.user_id=u.id WHERE p.id=$id_pinjam");
    $data = mysqli_fetch_assoc($q);
    
    if($data){
        mysqli_query($koneksi, "UPDATE peminjaman SET status='dikembalikan', tanggal_kembali=NOW() WHERE id=$id_pinjam");
        mysqli_query($koneksi, "UPDATE buku SET stok=stok+1 WHERE id=".$data['buku_id']);
        setNotif('Sukses! Buku "'.$data['judul'].'" dari '.$data['nama'].' berhasil dikembalikan.', 'success');
    }
    echo "<script>window.location='admin_pinjam.php';</script>";
    exit;
}

$pinjam = mysqli_query($koneksi, "SELECT p.*, b.judul, u.nama FROM peminjaman p JOIN buku b ON p.buku_id=b.id JOIN users u ON p.user_id=u.id ORDER BY p.id DESC");
$role = $_SESSION['role'];
$nama = $_SESSION['nama'];
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
    <title>Admin Peminjaman - Perpustakaan Bintang</title>
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

    <!-- Modal Konfirmasi Pengembalian (DaisyUI Native Dialog with Premium Design) -->
    <dialog id="modalKembali" class="modal z-50">
        <div class="modal-box p-6 border border-[var(--glass-border)] shadow-2xl relative overflow-hidden">
            <!-- Glow background decor -->
            <div class="absolute -top-10 -right-10 w-28 h-28 rounded-full bg-[var(--accent-primary)]/10 blur-xl"></div>
            
            <h3 class="text-lg font-bold text-[var(--accent-primary)] flex items-center gap-2">
                <div class="p-2 rounded-xl bg-[var(--accent-primary)]/10 flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                </div>
                <span>Konfirmasi Pengembalian</span>
            </h3>
            <p class="py-4 text-secondary-theme text-sm" id="modalText">Apakah Anda yakin ingin menyelesaikan peminjaman buku ini?</p>
            <div class="modal-action flex gap-2 pt-2">
                <button class="btn btn-premium-ghost rounded-xl px-5" onclick="tutupModal()">Batal</button>
                <button class="btn btn-premium px-6 rounded-xl text-white flex items-center justify-center gap-2" id="btnYes">
                    <i data-lucide="check" class="w-4 h-4"></i> Kembalikan Buku
                </button>
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
                    <i data-lucide="clipboard-list" class="icon-indigo w-8 h-8"></i>
                    <span>Data Transaksi Peminjaman</span>
                </h2>
                <p class="text-[10px] text-secondary-theme opacity-80 mt-1 uppercase tracking-widest font-semibold ml-11">Pantau Dan Konfirmasi Sirkulasi Buku Anggota</p>
            </div>
            <a href="dashboard.php" class="btn btn-premium-ghost rounded-2xl text-secondary-theme text-sm gap-2">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
            </a>
        </div>

        <!-- Loans Table Container -->
        <div class="glass-card p-6 shadow-xl">
            <div class="overflow-x-auto w-full">
                <table class="custom-table w-full">
                    <thead>
                        <tr>
                            <th class="w-12 text-center">No</th>
                            <th>Peminjam</th>
                            <th>Judul Buku</th>
                            <th class="text-center">Tgl Pinjam</th>
                            <th class="text-center">Jatuh Tempo</th>
                            <th class="text-center">Status</th>
                            <th class="text-center w-36">Aksi Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while($p = mysqli_fetch_assoc($pinjam)): ?>
                        <tr>
                            <td class="text-center text-secondary-theme opacity-70 font-bold text-sm"><?= $no++ ?></td>
                            <td class="font-bold text-primary-theme"><?= htmlspecialchars($p['nama']) ?></td>
                            <td class="font-medium text-secondary-theme"><?= htmlspecialchars($p['judul']) ?></td>
                            <td class="text-center"><span class="badge badge-premium-ghost py-2 px-3 text-xs"><?= date('d-m-Y', strtotime($p['tanggal_pinjam'])) ?></span></td>
                            <td class="text-center"><span class="badge badge-premium-ghost py-2 px-3 text-xs"><?= $p['tanggal_kembali'] ? date('d-m-Y', strtotime($p['tanggal_kembali'])) : '-' ?></span></td>
                            <td class="text-center">
                                <?php if($p['status'] == 'dipinjam'): ?>
                                    <span class="badge badge-glass-info py-2.5 px-3.5 text-xs">Sedang Dipinjam</span>
                                <?php else: ?>
                                    <span class="badge badge-glass-success py-2.5 px-3.5 text-xs">Dikembalikan</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if($p['status'] == 'dipinjam'): ?>
                                    <button onclick="bukaModal(<?= $p['id'] ?>, '<?= htmlspecialchars($p['judul'], ENT_QUOTES) ?>', '<?= htmlspecialchars($p['nama'], ENT_QUOTES) ?>')" class="btn btn-premium btn-xs rounded-xl px-4 py-1 text-xs text-white gap-1.5 flex items-center justify-center">
                                        <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Kembalikan
                                    </button>
                                <?php else: ?>
                                    <span class="text-xs text-secondary-theme opacity-50 font-semibold">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
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

        let idPinjam = 0;
        function bukaModal(id, judul, nama){
            idPinjam = id;
            document.getElementById('modalText').innerHTML = 'Apakah Anda yakin ingin memverifikasi pengembalian buku <span class="text-[var(--accent-primary)] font-extrabold">"' + judul + '"</span> dari peminjaman anggota <span class="font-bold text-primary-theme">' + nama + '</span>?';
            document.getElementById('modalKembali').showModal();
        }
        function tutupModal(){
            document.getElementById('modalKembali').close();
        }
        document.getElementById('btnYes').onclick = function(){
            window.location = 'admin_pinjam.php?kembali=' + idPinjam;
        }
    </script>
    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>