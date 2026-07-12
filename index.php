<?php
session_start();
require_once 'config/database.php';

// Proteksi halaman: Jika belum login, tendang balik ke login.php
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// =========================================================
// QUERY UNTUK MENGAMBIL TOTAL DATA DARI DATABASE
// =========================================================

// 1. Hitung Total Guru
$query_guru = mysqli_query($db, "SELECT COUNT(id) as total FROM guru");
$data_guru = mysqli_fetch_assoc($query_guru);
$total_guru = $data_guru['total'] ?? 0;

// 2. Hitung Total Kelas (Kelas)
$query_kelas = mysqli_query($db, "SELECT COUNT(id) as total FROM kelas");
$data_kelas = mysqli_fetch_assoc($query_kelas);
$total_kelas = $data_kelas['total'] ?? 0;

// 3. Hitung Total Mapel
$query_mapel = mysqli_query($db, "SELECT COUNT(id) as total FROM mapel");
$data_mapel = mysqli_fetch_assoc($query_mapel);
$total_mapel = $data_mapel['total'] ?? 0;

// 4. Hitung Total Jadwal Aktif
$query_jadwal = mysqli_query($db, "SELECT COUNT(id) as total FROM jadwal");
$data_jadwal = mysqli_fetch_assoc($query_jadwal);
$total_jadwal = $data_jadwal['total'] ?? 0;

$role = $_SESSION['role'] ?? 'admin';
$username = $_SESSION['username'] ?? 'User';
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SMAS MKGR Sepatan</title>
    <link href="css/output.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="h-full antialiased text-slate-800 bg-slate-50/50">

    <div class="flex min-h-screen">
        
        <?php include 'includes/sidebar.php'; ?>

        <div class="flex-1 min-w-0 h-screen overflow-y-auto bg-slate-50/30 relative">
            <?php include 'includes/header.php'; ?>
            
            <div class="p-6 md:p-10 space-y-8 max-w-[1600px] mx-auto">
                
                <!-- Header Section -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-slate-800">Dashboard</h2>
                    <p class="mt-1 text-sm text-slate-500">Ringkasan data dan performa jadwal akademik.</p>
                </div>
                
                <!-- Top Stats Row -->
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
                    
                    <div class="p-5 bg-white rounded-lg border border-slate-200">
                        <span class="text-sm font-semibold text-slate-600 block mb-2">Total Guru</span>
                        <div class="text-3xl font-bold text-slate-800 mb-1"><?= $total_guru; ?></div>
                        <span class="text-xs text-slate-500">Guru aktif mengajar</span>
                    </div>

                    <div class="p-5 bg-white rounded-lg border border-slate-200">
                        <span class="text-sm font-semibold text-slate-600 block mb-2">Total Kelas</span>
                        <div class="text-3xl font-bold text-slate-800 mb-1"><?= $total_kelas; ?></div>
                        <span class="text-xs text-slate-500">Rombongan belajar terdaftar</span>
                    </div>

                    <div class="p-5 bg-white rounded-lg border border-slate-200">
                        <span class="text-sm font-semibold text-slate-600 block mb-2">Mata Pelajaran</span>
                        <div class="text-3xl font-bold text-slate-800 mb-1"><?= $total_mapel; ?></div>
                        <span class="text-xs text-slate-500">Mapel dalam kurikulum</span>
                    </div>

                    <div class="p-5 bg-white rounded-lg border border-slate-200">
                        <span class="text-sm font-semibold text-slate-600 block mb-2">Jadwal Sesi</span>
                        <div class="text-3xl font-bold text-slate-800 mb-1"><?= $total_jadwal; ?></div>
                        <span class="text-xs text-slate-500">Sesi terplot di sistem</span>
                    </div>

                </div>

                <!-- Main Content Row -->
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                    
                    <!-- Left/Main Area -->
                    <div class="xl:col-span-2 bg-white rounded-lg border border-slate-200 p-6">
                        <div class="border-b border-slate-100 pb-4 mb-4">
                            <h3 class="text-base font-semibold text-slate-800">Alur Kerja Penjadwalan</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Step 1 -->
                            <div class="space-y-2">
                                <div class="w-8 h-8 rounded bg-slate-100 flex items-center justify-center text-slate-600 text-sm font-bold mb-3">1</div>
                                <h4 class="text-sm font-semibold text-slate-800">Data Master</h4>
                                <p class="text-xs text-slate-500 leading-relaxed">
                                    Lengkapi data Guru, Kelas, dan Mata Pelajaran sebelum membuat jadwal.
                                </p>
                            </div>
                            
                            <!-- Step 2 -->
                            <div class="space-y-2">
                                <div class="w-8 h-8 rounded bg-slate-100 flex items-center justify-center text-slate-600 text-sm font-bold mb-3">2</div>
                                <h4 class="text-sm font-semibold text-slate-800">Plotting Waktu</h4>
                                <p class="text-xs text-slate-500 leading-relaxed">
                                    Tentukan hari dan jam pelajaran untuk masing-masing guru dan kelas.
                                </p>
                            </div>

                            <!-- Step 3 -->
                            <div class="space-y-2">
                                <div class="w-8 h-8 rounded bg-slate-100 flex items-center justify-center text-slate-600 text-sm font-bold mb-3">3</div>
                                <h4 class="text-sm font-semibold text-slate-800">Validasi Otomatis</h4>
                                <p class="text-xs text-slate-500 leading-relaxed">
                                    Sistem akan menolak penyimpanan jika terdeteksi bentrok pada guru atau ruangan yang sama.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Sidebar Area -->
                    <div class="xl:col-span-1 space-y-6">
                        
                        <div class="p-6 bg-white rounded-lg border border-slate-200">
                            <h3 class="text-sm font-semibold text-slate-800 mb-4">Status Sistem</h3>
                            <div class="space-y-3">
                                <div>
                                    <div class="flex justify-between text-xs mb-1">
                                        <span class="text-slate-600">Integritas Jadwal</span>
                                        <span class="text-emerald-600 font-medium">Aman</span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-1.5">
                                      <div class="bg-emerald-500 h-1.5 rounded-full" style="width: 100%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 bg-white rounded-lg border border-slate-200">
                            <h3 class="text-sm font-semibold text-slate-800 mb-2">Akses Cepat</h3>
                            <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                                Lakukan pengelolaan dan input jadwal mengajar terbaru di menu jadwal.
                            </p>
                            <a href="jadwal.php" class="block text-center w-full bg-indigo-600 text-white font-medium text-xs py-2 rounded hover:bg-indigo-700 transition-colors">
                                Ke Halaman Jadwal
                            </a>
                        </div>

                    </div>

                </div>

                <div class="pt-6 border-t border-slate-200/50 text-center">
                    <p class="text-[11px] text-slate-400 font-medium">SMAS MKGR Sepatan - Hak Cipta Dilindungi Undang-Undang.</p>
                </div>

            </div>
        </div>

    </div>

</body>
</html>