<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Mengambil data mapel asli dari database
$query = mysqli_query($db, "SELECT * FROM mapel ORDER BY nama_mapel ASC") or die(mysqli_error($db));
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mata Pelajaran - SMAS MKGR Sepatan</title>
    <link href="css/output.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="h-full antialiased text-slate-800 bg-slate-50/50">

    <div class="flex min-h-screen">
        
        <?php include 'includes/sidebar.php'; ?>

        <div class="flex-1 min-w-0 h-screen overflow-y-auto bg-slate-50/30 relative">
            <?php include 'includes/header.php'; ?>
            <div class="p-6 md:p-10 space-y-6 max-w-[1200px] mx-auto">
                
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Data Mata Pelajaran</h2>
                        <p class="text-xs text-slate-400 mt-1">Kelola daftar mata pelajaran kurikulum dan kelompok pengajarannya.</p>
                    </div>
                    <div>
                        <a href="tambah_mapel.php" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-xl transition-all shadow-sm active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah Mapel 
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto w-full">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/70 border-b border-slate-100">
                                    <th class="p-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider text-center w-12">No</th>
                                    <th class="p-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Kode Mapel</th>
                                    <th class="p-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Mata Pelajaran</th>
                                    <th class="p-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider">Kelompok Kurikulum</th>
                                    <th class="p-4 text-[11px] font-bold text-slate-500 uppercase tracking-wider w-32 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm text-slate-700 font-medium">
                                
                                <?php 
                                $no = 1;
                                while ($row = mysqli_fetch_assoc($query)) : 
                                ?>
                                <tr class="hover:bg-slate-50/40 transition-colors">
                                    <td class="p-4 text-center text-slate-400 font-medium"><?= $no++; ?></td>
                                    <td class="p-4 text-slate-500 font-mono text-xs font-bold"><?= $row['kode_mapel']; ?></td>
                                    <td class="p-4 text-slate-900 font-bold tracking-tight"><?= $row['nama_mapel']; ?></td>
                                    <td class="p-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-800">
                                            <?= $row['kelompok']; ?>
                                        </span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="edit_mapel.php?id=<?= $row['id']; ?>" class="p-2 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <a href="hapus_mapel.php?id=<?= $row['id']; ?>" onclick="return confirm('Hapus mata pelajaran ini?')" class="p-2 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>

                                <?php if (mysqli_num_rows($query) === 0) : ?>
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-slate-400 font-normal">Belum ada data mata pelajaran di database.</td>
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