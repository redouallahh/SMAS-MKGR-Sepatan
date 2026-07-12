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

if (isset($_POST['ajax'])) {
    header('Content-Type: application/json');
    $hari = mysqli_real_escape_string($db, $_POST['hari']);
    $id_guru = mysqli_real_escape_string($db, $_POST['id_guru']);
    $id_kelas = mysqli_real_escape_string($db, $_POST['id_kelas']);
    $id_mapel = mysqli_real_escape_string($db, $_POST['id_mapel']);
    $jam_ke = mysqli_real_escape_string($db, $_POST['jam_ke']);

    // Validasi Bentrok
    $cek = mysqli_query($db, "SELECT id FROM jadwal WHERE hari = '$hari' AND jam_ke = '$jam_ke' AND (id_guru = '$id_guru' OR id_kelas = '$id_kelas')");
    
    if (mysqli_num_rows($cek) > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Jadwal bentrok! Guru atau Kelas sudah memiliki jadwal di hari dan jam yang sama.']);
    } else {
        $insert = mysqli_query($db, "INSERT INTO jadwal (hari, jam_ke, id_guru, id_kelas, id_mapel) 
                           VALUES ('$hari', '$jam_ke', '$id_guru', '$id_kelas', '$id_mapel')");
        if ($insert) {
            echo json_encode(['status' => 'success', 'message' => 'Jadwal berhasil ditambahkan!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan saat menyimpan jadwal.']);
        }
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏫</text></svg>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jadwal - SMAS MKGR Sepatan</title>
    <link href="css/output.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="h-full antialiased text-slate-800 bg-slate-50/50">
    <div class="flex min-h-screen">
        <?php include 'includes/sidebar.php'; ?>

        <div class="flex-1 min-w-0 h-screen overflow-y-auto bg-slate-50/30 relative">
            <?php include 'includes/header.php'; ?>
            <div class="p-6 md:p-10 max-w-[600px] mx-auto space-y-6">
                
                <div class="flex items-center gap-3">
                    <a href="jadwal.php" class="p-2.5 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 text-slate-500 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-900">Plot Jadwal </h2>
                        <p class="text-xs text-slate-400 mt-0.5">Tambahkan sesi pengajaran  ke sistem.</p>
                    </div>
                </div>

                <?php if (!empty($error_msg)) : ?>
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-xs font-bold text-rose-700"><?= $error_msg; ?></div>
                <?php endif; ?>

                <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-100 shadow-sm">
                    <form action="" method="POST" class="space-y-5">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Hari Operasional</label>
                            <select name="hari" required class="block w-full text-sm rounded-xl border border-slate-200 p-3 bg-slate-50 outline-none focus:bg-white focus:border-slate-900 transition-all font-medium">
                                <option value="Senin">Senin</option>
                                <option value="Selasa">Selasa</option>
                                <option value="Rabu">Rabu</option>
                                <option value="Kamis">Kamis</option>
                                <option value="Jumat">Jumat</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Jam Ke-</label>
                            <input type="number" name="jam_ke" min="1" max="15" oninput="this.value = this.value.replace(/[^0-9]/g, '')" title="Jam ke harus antara 1 sampai 15" required placeholder="Contoh: 1" class="block w-full text-sm rounded-xl border border-slate-200 p-3 bg-slate-50 outline-none focus:bg-white focus:border-slate-900 transition-all font-medium">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Guru Pengampu</label>
                            <select name="id_guru" required class="block w-full text-sm rounded-xl border border-slate-200 p-3 bg-slate-50 outline-none focus:bg-white focus:border-slate-900 transition-all font-medium">
                                <?php 
                                $q_guru = mysqli_query($db, "SELECT * FROM guru WHERE status_tugas != 'Cuti' ORDER BY nama_guru ASC");
                                while($g = mysqli_fetch_assoc($q_guru)) echo "<option value='".$g['id']."'>".$g['nama_guru']."</option>";
                                ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Ruang Kelas</label>
                            <select name="id_kelas" required class="block w-full text-sm rounded-xl border border-slate-200 p-3 bg-slate-50 outline-none focus:bg-white focus:border-slate-900 transition-all font-medium">
                                <?php 
                                $q_kelas = mysqli_query($db, "SELECT * FROM kelas ORDER BY nama_kelas ASC");
                                while($k = mysqli_fetch_assoc($q_kelas)) echo "<option value='".$k['id']."'>".$k['nama_kelas']."</option>";
                                ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Mata Pelajaran</label>
                            <select name="id_mapel" required class="block w-full text-sm rounded-xl border border-slate-200 p-3 bg-slate-50 outline-none focus:bg-white focus:border-slate-900 transition-all font-medium">
                                <?php 
                                $q_mapel = mysqli_query($db, "SELECT * FROM mapel ORDER BY nama_mapel ASC");
                                while($m = mysqli_fetch_assoc($q_mapel)) echo "<option value='".$m['id']."'>".$m['nama_mapel']."</option>";
                                ?>
                            </select>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                            <a href="jadwal.php" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-xs rounded-xl transition-all">Batal</a>
                            <button type="submit" name="submit_jadwal" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-sm transition-all">Simpan Jadwal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
                }).then(() => window.location = 'jadwal.php');
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