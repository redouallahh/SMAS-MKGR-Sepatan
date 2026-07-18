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

// Cek apakah ada ID yang dikirim
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: jadwal.php");
    exit;
}

$id = mysqli_real_escape_string($db, $_GET['id']);
$pesan_error = "";

// Ambil data jadwal saat ini
$query = mysqli_query($db, "SELECT * FROM jadwal WHERE id = '$id'");
if (mysqli_num_rows($query) === 0) {
    header("Location: jadwal.php");
    exit;
}
$row = mysqli_fetch_assoc($query);

// PROSES UPDATE DATA
if (isset($_POST['ajax'])) {
    header('Content-Type: application/json');
    $hari = mysqli_real_escape_string($db, $_POST['hari']);
    $id_guru = mysqli_real_escape_string($db, $_POST['id_guru']);
    $id_kelas = mysqli_real_escape_string($db, $_POST['id_kelas']);
    $id_mapel = mysqli_real_escape_string($db, $_POST['id_mapel']);
    $jam_ke = mysqli_real_escape_string($db, $_POST['jam_ke']);

    // Validasi Anti-Bentrok (Mengecualikan ID yang sedang diedit)
    $cek_bentrok = mysqli_query($db, "SELECT id FROM jadwal WHERE hari = '$hari' AND jam_ke = '$jam_ke' AND (id_guru = '$id_guru' OR id_kelas = '$id_kelas') AND id != '$id'");

    if (mysqli_num_rows($cek_bentrok) > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Guru atau Ruang Kelas tersebut sudah memiliki jadwal lain di hari dan jam yang sama.']);
    } else {
        $update = mysqli_query($db, "UPDATE jadwal SET hari='$hari', jam_ke='$jam_ke', id_guru='$id_guru', id_kelas='$id_kelas', id_mapel='$id_mapel' WHERE id='$id'");
        
        if ($update) {
            echo json_encode(['status' => 'success', 'message' => 'Jadwal berhasil diperbarui!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal mengupdate jadwal.']);
        }
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <link rel="icon" type="image/png" href="images/logosekolah.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Jadwal - SMAS MKGR Sepatan</title>
    <link href="css/output.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="h-full antialiased text-slate-800 bg-slate-50/50">
    <div class="flex min-h-screen">
        <?php include 'includes/sidebar.php'; ?>

        <div class="flex-1 min-w-0 h-screen overflow-y-auto bg-slate-50/30 relative">
            <?php include 'includes/header.php'; ?>
            <div class="p-6 md:p-10 max-w-[800px] mx-auto space-y-6">
                
                <div class="flex items-center gap-3">
                    <a href="jadwal.php" class="p-2.5 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 text-slate-500 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-900">Peri Sesi Jadwal</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Modifikasi alokasi waktu, guru, atau ruang kelas.</p>
                    </div>
                </div>

                <?php if ($pesan_error != ""): ?>
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-xs font-bold text-rose-700 transition-all"><?= $pesan_error; ?></div>
                <?php endif; ?>

                <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-100 shadow-sm">
                    <form action="" method="POST" class="space-y-5">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Hari Operasional</label>
                                <select name="hari" required class="block w-full text-sm rounded-xl border border-slate-200 p-3 bg-slate-50 outline-none focus:bg-white focus:border-slate-900 font-semibold text-slate-700 transition-all">
                                    <option value="Senin" <?= ($row['hari'] == 'Senin') ? 'selected' : ''; ?>>Senin</option>
                                    <option value="Selasa" <?= ($row['hari'] == 'Selasa') ? 'selected' : ''; ?>>Selasa</option>
                                    <option value="Rabu" <?= ($row['hari'] == 'Rabu') ? 'selected' : ''; ?>>Rabu</option>
                                    <option value="Kamis" <?= ($row['hari'] == 'Kamis') ? 'selected' : ''; ?>>Kamis</option>
                                    <option value="Jumat" <?= ($row['hari'] == 'Jumat') ? 'selected' : ''; ?>>Jumat</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Jam Ke-</label>
                                <input type="number" name="jam_ke" value="<?= $row['jam_ke']; ?>" min="1" max="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" title="Jam ke harus antara 1 sampai 10" required placeholder="Contoh: 1, 2, 8..." class="block w-full text-sm rounded-xl border border-slate-200 p-3 bg-slate-50 outline-none focus:bg-white focus:border-slate-900 transition-all font-medium">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Guru Pengampu</label>
                            <select name="id_guru" required class="block w-full text-sm rounded-xl border border-slate-200 p-3 bg-slate-50 outline-none focus:bg-white focus:border-slate-900 font-semibold text-slate-700 transition-all">
                                <?php 
                                $q_guru = mysqli_query($db, "SELECT * FROM guru WHERE status_tugas != 'Cuti' ORDER BY kode_guru ASC, nama_guru ASC");
                                while($g = mysqli_fetch_assoc($q_guru)) {
                                    $selected = ($row['id_guru'] == $g['id']) ? 'selected' : '';
                                    echo "<option value='".$g['id']."' $selected>[".$g['kode_guru']."] - ".$g['nama_guru']."</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Ruang Kelas</label>
                            <select name="id_kelas" required class="block w-full text-sm rounded-xl border border-slate-200 p-3 bg-slate-50 outline-none focus:bg-white focus:border-slate-900 font-semibold text-slate-700 transition-all">
                                <?php 
                                $q_kelas = mysqli_query($db, "SELECT * FROM kelas ORDER BY nama_kelas ASC");
                                while($k = mysqli_fetch_assoc($q_kelas)) {
                                    $selected = ($row['id_kelas'] == $k['id']) ? 'selected' : '';
                                    echo "<option value='".$k['id']."' $selected>".$k['nama_kelas']."</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Mata Pelajaran</label>
                            <select name="id_mapel" required class="block w-full text-sm rounded-xl border border-slate-200 p-3 bg-slate-50 outline-none focus:bg-white focus:border-slate-900 font-semibold text-slate-700 transition-all">
                                <?php 
                                $q_mapel = mysqli_query($db, "SELECT * FROM mapel ORDER BY kode_mapel ASC, nama_mapel ASC");
                                while($m = mysqli_fetch_assoc($q_mapel)) {
                                    $selected = ($row['id_mapel'] == $m['id']) ? 'selected' : '';
                                    echo "<option value='".$m['id']."' $selected>[".$m['kode_mapel']."] - ".$m['nama_mapel']."</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                            <a href="jadwal.php" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-xs rounded-xl transition-all">Batal</a>
                            <button type="submit" name="update_jadwal" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-sm transition-all">Simpan Perubahan</button>
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