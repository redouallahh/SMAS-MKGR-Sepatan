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

$error_msg = "";

if (isset($_POST['ajax'])) {
    header('Content-Type: application/json');
    $nama_mapel = mysqli_real_escape_string($db, trim($_POST['nama_mapel']));
    $kode = mysqli_real_escape_string($db, trim($_POST['kode_mapel']));

    // Cek duplikasi kode mapel
    $cek_ganda = mysqli_query($db, "SELECT id FROM mapel WHERE kode_mapel = '$kode'");
    if (mysqli_num_rows($cek_ganda) > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Gagal! Kode mapel sudah terpakai.']);
    } else {
        $insert = mysqli_query($db, "INSERT INTO mapel (kode_mapel, nama_mapel) VALUES ('$kode', '$nama_mapel')");
        if ($insert) {
            echo json_encode(['status' => 'success', 'message' => 'Mata pelajaran berhasil ditambahkan.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data ke database.']);
        }
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">

<head>
    <link rel="icon" type="image/png" href="images/logosekolah.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mapel - SMAS MKGR Sepatan</title>
    <link href="css/output.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="h-full antialiased text-slate-800 bg-slate-50/50">
    <div class="flex min-h-screen">
        <?php include 'includes/sidebar.php'; ?>

        <div class="flex-1 min-w-0 h-screen overflow-y-auto bg-slate-50/30 relative">
            <?php include 'includes/header.php'; ?>
            <div class="p-6 md:p-10 max-w-[600px] mx-auto space-y-6">

                <div class="flex items-center gap-3">
                    <a href="mapel.php" class="p-2.5 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 text-slate-500 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-900">Tambah Mata Pelajaran</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Daftarkan mata pelajaran kurikulum.</p>
                    </div>
                </div>

                <?php if (!empty($error_msg)) : ?>
                    <div class="p-4 text-sm text-rose-700 bg-rose-50 border border-rose-100 rounded-xl flex items-start gap-3">
                        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div><?= $error_msg; ?></div>
                    </div>
                <?php endif; ?>

                <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-100 shadow-sm">
                    <form action="" method="POST" class="space-y-5">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Nama Mata Pelajaran</label>
                            <input type="text" name="nama_mapel" pattern="[a-zA-Z0-9\s\-]+" oninput="this.value = this.value.replace(/[^a-zA-Z0-9\s\-]/g, '')" title="Nama mata pelajaran hanya boleh berisi huruf, angka, spasi, dan tanda hubung" placeholder="Contoh: Matematika Wajib" required class="block w-full text-sm rounded-xl border border-slate-200 p-3 bg-slate-50 outline-none focus:bg-white focus:border-slate-900 transition-all font-medium">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Kode Mata Pelajaran</label>
                            <input type="text" name="kode_mapel" placeholder="Contoh: A" required class="block w-full text-sm rounded-xl border border-slate-200 p-3 bg-slate-50 outline-none focus:bg-white focus:border-slate-900 transition-all font-medium">
                        </div>
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                            <a href="mapel.php" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-xs rounded-xl transition-all">Batal</a>
                            <button type="submit" name="submit" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-sm transition-all">Simpan Mapel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = this.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2 inline" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menyimpan...';
        
        const formData = new FormData(this);
        formData.append('ajax', '1');
        
        fetch('', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                Swal.fire({
                    title: 'Berhasil!',
                    text: data.message,
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => window.location = 'mapel.php');
            } else {
                Swal.fire('Gagal!', data.message, 'error');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        })
        .catch(err => {
            Swal.fire('Gagal!', 'Terjadi kesalahan jaringan.', 'error');
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
    });
    </script>
</body>
</html>