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

// PROSES HAPUS BUKU
if(isset($_GET['hapus'])){
    $id_buku = (int)$_GET['hapus'];
    
    $q = mysqli_query($koneksi, "SELECT judul FROM buku WHERE id=$id_buku");
    $data = mysqli_fetch_assoc($q);
    
    if($data){
        mysqli_query($koneksi, "DELETE FROM buku WHERE id=$id_buku");
        setNotif('Buku "'.$data['judul'].'" berhasil dihapus dari katalog!', 'success');
    } else {
        setNotif('Buku tidak ditemukan!', 'error');
    }
    echo "<script>window.location='kelola_buku.php';</script>";
    exit;
}

$buku = mysqli_query($koneksi, "SELECT * FROM buku ORDER BY id DESC");
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
    <title>Kelola Buku - Perpustakaan Bintang</title>
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

    <!-- Modal Logout (DaisyUI Native Dialog) -->
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

    <!-- Modal Hapus Buku (DaisyUI Native Dialog with Premium Design) -->
    <dialog id="modalHapus" class="modal z-50">
        <div class="modal-box p-6 border border-[var(--glass-border)] shadow-2xl relative overflow-hidden">
            <!-- Glow background decor -->
            <div class="absolute -top-10 -right-10 w-24 h-24 rounded-full bg-[var(--accent-danger)]/10 blur-xl"></div>
            
            <h3 class="text-lg font-bold text-[var(--accent-danger)] flex items-center gap-2">
                <div class="p-2 rounded-xl bg-[var(--accent-danger)]/10 flex items-center justify-center">
                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                </div>
                Konfirmasi Hapus
            </h3>
            <p class="py-4 text-secondary-theme text-sm" id="modalText">Apakah Anda yakin ingin menghapus buku ini dari katalog?</p>
            <div class="modal-action flex gap-2 pt-2">
                <button class="btn btn-premium-ghost rounded-xl px-5" onclick="tutupModal()">Batal</button>
                <button class="btn btn-premium-danger px-6 rounded-xl flex items-center justify-center gap-2" id="btnYes">
                    <i data-lucide="trash-2" class="w-4 h-4"></i> Hapus
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
                    <i data-lucide="settings" class="icon-indigo w-8 h-8"></i>
                    <span>Kelola Katalog Buku</span>
                </h2>
                <p class="text-[10px] text-secondary-theme opacity-80 mt-1 uppercase tracking-widest font-semibold ml-11">Panel Administrator Data Buku</p>
            </div>
            <div class="flex flex-wrap justify-center gap-3">
                <a href="tambah_buku.php" class="btn btn-premium rounded-2xl text-xs px-5 py-2 text-white gap-1.5 flex items-center justify-center">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i> Tambah Buku
                </a>
                <a href="dashboard.php" class="btn btn-premium-ghost rounded-2xl text-secondary-theme text-xs px-5 py-2 gap-1.5 flex items-center justify-center">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Dashboard
                </a>
            </div>
        </div>

        <!-- Kelola Table Container -->
        <div class="glass-card p-6 shadow-xl">
            <div class="overflow-x-auto w-full">
                <table class="custom-table w-full">
                    <thead>
                        <tr>
                            <th class="w-12 text-center">No</th>
                            <th>Judul Buku</th>
                            <th>Penulis</th>
                            <th class="text-center">Tahun</th>
                            <th class="text-center">Stok</th>
                            <th class="text-center w-40">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($buku) > 0): ?>
                            <?php $no = 1; while($b = mysqli_fetch_assoc($buku)): ?>
                            <tr>
                                <td class="text-center text-secondary-theme opacity-70 font-bold text-sm"><?= $no++ ?></td>
                                <td class="py-4 font-bold text-primary-theme"><?= htmlspecialchars($b['judul']) ?></td>
                                <td class="text-secondary-theme font-medium"><?= htmlspecialchars($b['penulis']) ?></td>
                                <td class="text-center"><span class="badge badge-premium-ghost py-2 px-3 text-xs"><?= $b['tahun'] ?></span></td>
                                <td class="text-center font-bold text-primary-theme"><?= $b['stok'] ?></td>
                                <td class="text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="edit_buku.php?id=<?= $b['id'] ?>" class="btn btn-premium btn-xs rounded-xl px-3 py-1 text-xs text-white gap-1 flex items-center justify-center">
                                            <i data-lucide="edit-3" class="w-3 h-3"></i> Edit
                                        </a>
                                        <button onclick="bukaModal(<?= $b['id'] ?>, '<?= htmlspecialchars($b['judul'], ENT_QUOTES) ?>')" class="btn btn-premium-danger btn-xs rounded-xl px-3 py-1 text-xs gap-1 flex items-center justify-center">
                                            <i data-lucide="trash-2" class="w-3 h-3"></i> Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-10 text-secondary-theme opacity-65 text-sm">
                                    Belum ada data katalog buku terdaftar.
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

        let idBuku = 0;
        function bukaModal(id, judul){
            idBuku = id;
            document.getElementById('modalText').innerHTML = 'Apakah Anda yakin ingin menghapus buku <span class="text-red-400 font-extrabold">"' + judul + '"</span>? Data yang telah dihapus tidak dapat dipulihkan!';
            document.getElementById('modalHapus').showModal();
        }
        function tutupModal(){
            document.getElementById('modalHapus').close();
        }
        document.getElementById('btnYes').onclick = function(){
            window.location = 'kelola_buku.php?hapus=' + idBuku;
        }
    </script>
    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>