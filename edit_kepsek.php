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

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: kepsek.php");
    exit;
}
$id = mysqli_real_escape_string($db, $_GET['id']);

$query = mysqli_query($db, "SELECT kepalasekolah.*, user.username FROM kepalasekolah LEFT JOIN user ON kepalasekolah.id_user = user.id WHERE kepalasekolah.id = '$id'");
if (mysqli_num_rows($query) === 0) {
    header("Location: kepsek.php");
    exit;
}
$row = mysqli_fetch_assoc($query);
$pesan_error = "";

if (isset($_POST['ajax'])) {
    header('Content-Type: application/json');
    $nip = mysqli_real_escape_string($db, $_POST['nip']);
    $nama = mysqli_real_escape_string($db, $_POST['nama_kepsek']);
    $kontak = mysqli_real_escape_string($db, $_POST['kontak']);
    $username = mysqli_real_escape_string($db, $_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Password wajib diisi untuk keamanan!']);
        exit;
    }
    
    // Validasi NIP unik
    $cek_nip_kepsek = mysqli_query($db, "SELECT id FROM kepalasekolah WHERE nip='$nip' AND id != '$id'");
    $cek_nip_guru = mysqli_query($db, "SELECT id FROM guru WHERE nip='$nip'");
    if(mysqli_num_rows($cek_nip_kepsek) > 0 || mysqli_num_rows($cek_nip_guru) > 0) {
        echo json_encode(['status' => 'error', 'message' => 'NIP sudah terpakai!']);
        exit;
    }

    // Validasi Kontak unik
    $cek_kontak_kepsek = mysqli_query($db, "SELECT id FROM kepalasekolah WHERE kontak='$kontak' AND id != '$id'");
    $cek_kontak_guru = mysqli_query($db, "SELECT id FROM guru WHERE kontak='$kontak'");
    if(mysqli_num_rows($cek_kontak_kepsek) > 0 || mysqli_num_rows($cek_kontak_guru) > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Nomor HP/Telepon sudah terpakai!']);
        exit;
    }

    // Validasi username unik
    if (!empty($username)) {
        $id_user = $row['id_user'];
        $cek_query = "SELECT id FROM user WHERE username='$username'";
        if ($id_user) {
            $cek_query .= " AND id != '$id_user'";
        }
        $cek = mysqli_query($db, $cek_query);
        if(mysqli_num_rows($cek) > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Username sudah terpakai oleh akun lain!']);
            exit;
        }
    }

    // Update Kepala Sekolah
    $update = mysqli_query($db, "UPDATE kepalasekolah SET 
                             nip='$nip', 
                             nama_kepsek='$nama', 
                             kontak='$kontak'
                             WHERE id='$id'");
                             
    // Update atau Insert User
    if ($update && !empty($username)) {
        if ($row['id_user']) {
            $id_user = $row['id_user'];
            $sql_user = "UPDATE user SET username='$username'";
            if (!empty($password)) {
                $pass_md5 = md5($password);
                $sql_user .= ", password='$pass_md5'";
            }
            $sql_user .= " WHERE id='$id_user'";
            mysqli_query($db, $sql_user);
        } else {
            $pass_md5 = md5(empty($password) ? 'sekolah123' : $password);
            mysqli_query($db, "INSERT INTO user (username, password, role) VALUES ('$username', '$pass_md5', 'kepala_sekolah')");
            $new_id_user = mysqli_insert_id($db);
            mysqli_query($db, "UPDATE kepalasekolah SET id_user='$new_id_user' WHERE id='$id'");
        }
    }
    if ($update) {
        echo json_encode(['status' => 'success', 'message' => 'Data Kepala Sekolah berhasil diperbarui!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui data: ' . mysqli_error($db)]);
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
    <title>Edit Data Kepala Sekolah - SMAS MKGR Sepatan</title>
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
            <div class="p-6 md:p-10 max-w-[800px] mx-auto space-y-6">

                <div class="flex items-center gap-3">
                    <a href="kepsek.php" class="p-2.5 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 text-slate-500 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-900">Update Data Kepala Sekolah</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Modifikasi informasi fungsional kepala sekolah yang dipilih.</p>
                    </div>
                </div>

                <?php if ($pesan_error != ""): ?>
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-xs font-bold text-rose-700 transition-all"><?= $pesan_error; ?></div>
                <?php endif; ?>

                <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-100 shadow-sm">
                    <form action="" method="POST" class="space-y-5">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Nomor Induk Pegawai (NIP)</label>
                            <input type="text" name="nip" value="<?= $row['nip']; ?>" pattern="\d{18}" maxlength="18" oninput="this.value = this.value.replace(/[^0-9]/g, '')" title="NIP harus 18 digit angka" required class="block w-full text-sm rounded-xl border border-slate-200 p-3 bg-slate-50 outline-none focus:bg-white focus:border-slate-900 transition-all font-medium">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Nama Lengkap & Gelar</label>
                            <input type="text" name="nama_kepsek" value="<?= $row['nama_kepsek']; ?>" pattern="[a-zA-Z\s\.,\-']+" oninput="this.value = this.value.replace(/[^a-zA-Z\s\.,\-']/g, '')" title="Nama hanya boleh berisi huruf, spasi, titik, koma, tanda hubung, dan petik" required class="block w-full text-sm rounded-xl border border-slate-200 p-3 bg-slate-50 outline-none focus:bg-white focus:border-slate-900 transition-all font-medium">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">No. Handphone / WhatsApp</label>
                            <input type="tel" name="kontak" value="<?= $row['kontak']; ?>" pattern="\d{10,13}" maxlength="13" oninput="this.value = this.value.replace(/[^0-9]/g, '')" title="Nomor HP/WA harus 10-13 digit angka" required class="block w-full text-sm rounded-xl border border-slate-200 p-3 bg-slate-50 outline-none focus:bg-white focus:border-slate-900 transition-all font-medium">
                        </div>

                        <div class="pt-4 border-t border-slate-100 mt-4">
                            <h3 class="text-sm font-extrabold text-slate-800 mb-4">Akun Login Kepala Sekolah</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Username Login</label>
                                    <input type="text" name="username" value="<?= htmlspecialchars($row['username'] ?? ''); ?>" pattern="[a-zA-Z0-9_]+" title="Hanya huruf, angka, dan underscore" required placeholder="Cth: kepsek_1" class="w-full p-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-500 uppercase mb-2">Password Login</label>
                                    <input type="password" name="password" required placeholder="Masukkan kata sandi" class="w-full p-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all">
                                </div>
                            </div>
                        </div>
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                            <a href="kepsek.php" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-xs rounded-xl transition-all">Batal</a>
                            <button type="submit" name="update" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-sm transition-all">Simpan Perubahan</button>
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
                }).then(() => window.location = 'kepsek.php');
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