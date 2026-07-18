<?php
session_start();
require_once 'config/database.php';

// Proteksi halaman
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Silakan un-comment baris di bawah ini jika ingin langsung menghubungkan ke database asli kamu:
$search = isset($_GET['q']) ? mysqli_real_escape_string($db, trim($_GET['q'])) : '';
$whereClause = "";
if ($search !== '') {
    $whereClause = "WHERE kepalasekolah.nama_kepsek LIKE '%$search%' OR kepalasekolah.nip LIKE '%$search%'";
}

$query = mysqli_query($db, "SELECT kepalasekolah.*, user.username FROM kepalasekolah LEFT JOIN user ON kepalasekolah.id_user = user.id $whereClause ORDER BY kepalasekolah.id DESC");
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">

<head>
    <link rel="icon" type="image/png" href="images/logosekolah.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kepala Sekolah - SMAS MKGR Sepatan</title>
    <!-- Tailwind CSS CDN -->
    <link href="css/output.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="h-full antialiased text-slate-800 bg-slate-50/50">

    <!-- WRAPPER UTAMA -->
    <div class="flex min-h-screen">

        <!-- Panggil Sidebar -->
        <?php include 'includes/sidebar.php'; ?>

        <!-- AREA KONTEN KANAN -->
        <div class="flex-1 min-w-0 h-screen overflow-y-auto bg-slate-50/30 relative">
            <?php include 'includes/header.php'; ?>
            <div class="p-6 md:p-10 space-y-6 max-w-[1600px] mx-auto">

                <!-- HEADER HALAMAN & TOMBOL TAMBAH -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Manajemen Data Kepala Sekolah</h2>
                        <p class="text-xs text-slate-400 mt-1">Kelola data tenaga pengajar, nama lengkap, NIP, beserta status fungsionalnya.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <form method="GET" action="" style="position:relative;">
                            <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Cari nama/NIP..." style="padding: 10px 36px 10px 36px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; font-size:12px; outline:none; width:240px; font-family:inherit; color:#1e293b; transition: border-color .2s, background .2s;" onfocus="this.style.background='#fff';this.style.borderColor='#1e293b'" onblur="this.style.background='#f8fafc';this.style.borderColor='#e2e8f0'">
                            <svg width="16" height="16" fill="none" stroke="#94a3b8" stroke-width="2" viewBox="0 0 24 24" style="position:absolute;left:11px;top:50%;transform:translateY(-50%);pointer-events:none;"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <?php if($search): ?>
                                <a href="?" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);color:#94a3b8;line-height:0;">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>

                <!-- CONTAINER DATA TABEL -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto w-full">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/70 border-b border-slate-100">
                                    <th class="p-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider w-16 text-center">No</th>
                                    <th class="p-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">NIP</th>
                                    <th class="p-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Nama Lengkap Kepala Sekolah</th>
                                    <th class="p-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Kontak / No. HP</th>
                                    <th class="p-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider w-32 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm text-slate-700 font-medium">

                                <?php
                                $no = 1;
                                while ($row = mysqli_fetch_assoc($query)) :
                                ?>
                                    <tr class="hover:bg-slate-50/40 transition-colors">
                                        <td class="p-4 text-center text-slate-400 font-normal"><?= $no++; ?></td>
                                        <td class="p-4 text-slate-900 font-semibold tracking-tight"><?= $row['nip']; ?></td>
                                        <td class="p-4">
                                            <div class="text-slate-900 font-semibold"><?= htmlspecialchars($row['nama_kepsek'] ?? '-'); ?></div>
                                        </td>
                                        <td class="p-4 text-slate-500 font-normal"><?= $row['kontak']; ?></td>
                                        <td class="p-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="edit_kepsek.php?id=<?= $row['id']; ?>" class="p-2 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </a>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>

                                <?php if (mysqli_num_rows($query) === 0) : ?>
                                    <tr>
                                        <td colspan="5" class="p-8 text-center text-slate-400 font-normal">Belum ada data kepala sekolah di dalam database.</td>
                                    </tr>
                                <?php endif; ?>

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

    </div>
</body>

</html>