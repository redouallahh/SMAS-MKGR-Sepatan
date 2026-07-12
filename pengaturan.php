<?php
session_start();
require_once 'config/database.php';

// Proteksi halaman
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$pesan_error = "";
$pesan_sukses = "";

// Ambil data user
$username_session = mysqli_real_escape_string($db, $_SESSION['username']);
$query_user = mysqli_query($db, "SELECT * FROM user WHERE username = '$username_session'");
$data_user = mysqli_fetch_assoc($query_user);

// Jika tabel kosong, cegah error lebih lanjut
if (!$data_user) {
    die("Data user tidak ditemukan di database.");
}

$user_id = $data_user['id'];

if (isset($_POST['ajax'])) {
    header('Content-Type: application/json');
    $action = $_POST['action'] ?? '';

    if ($action === 'update_profil') {
        $username_ = mysqli_real_escape_string($db, $_POST['username']);
        $cek_username = mysqli_query($db, "SELECT id FROM user WHERE username = '$username_' AND id != '$user_id'");
        if (mysqli_num_rows($cek_username) > 0) {
            echo json_encode(['status' => 'error', 'message' => "Username '$username_' sudah terdaftar!"]);
        } else {
            $update_profil = mysqli_query($db, "UPDATE user SET username = '$username_' WHERE id = '$user_id'");
            if ($update_profil) {
                $_SESSION['username'] = $username_;
                echo json_encode(['status' => 'success', 'message' => 'Username berhasil diperbarui!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui username.']);
            }
        }
        exit;
    }

    if ($action === 'update_password') {
        $pass_lama = mysqli_real_escape_string($db, $_POST['pass_lama']);
        $pass_ = mysqli_real_escape_string($db, $_POST['pass_']);
        $konfirmasi = mysqli_real_escape_string($db, $_POST['konfirmasi']);

        if (md5($pass_lama) !== $data_user['password']) {
            echo json_encode(['status' => 'error', 'message' => 'Password Lama salah!']);
        } elseif ($pass_ !== $konfirmasi) {
            echo json_encode(['status' => 'error', 'message' => 'Konfirmasi Password tidak cocok!']);
        } elseif (empty($pass_)) {
            echo json_encode(['status' => 'error', 'message' => 'Password tidak boleh kosong!']);
        } else {
            $pass__md5 = md5($pass_);
            $update_pass = mysqli_query($db, "UPDATE user SET password = '$pass__md5' WHERE id = '$user_id'");
            if ($update_pass) {
                echo json_encode(['status' => 'success', 'message' => 'Kata Sandi berhasil diperbarui!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui kata sandi.']);
            }
        }
        exit;
    }

    if ($action === 'reset_user_lain' && $_SESSION['role'] === 'admin') {
        $target_username = mysqli_real_escape_string($db, $_POST['target_username']);
        $cek_user = mysqli_query($db, "SELECT id FROM user WHERE username = '$target_username'");
        if (mysqli_num_rows($cek_user) > 0) {
            $default_pass = md5('sekolah123');
            $reset_query = mysqli_query($db, "UPDATE user SET password = '$default_pass' WHERE username = '$target_username'");
            if ($reset_query) {
                echo json_encode(['status' => 'success', 'message' => "Password untuk '$target_username' berhasil direset menjadi: sekolah123"]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal mereset password.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => "Username '$target_username' tidak ditemukan."]);
        }
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <link rel="icon" type="image/png" href="images/logosekolah.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Akun - SMAS MKGR</title>
    <link href="css/output.css" rel="stylesheet">
</head>
<body class="h-full antialiased text-slate-800 bg-slate-50/50">
    <div class="flex min-h-screen">
        <?php include 'includes/sidebar.php'; ?>
        <div class="flex-1 min-w-0 h-screen overflow-y-auto bg-slate-50/30 relative">
            <?php include 'includes/header.php'; ?>
            <div class="p-6 md:p-10 max-w-2xl mx-auto space-y-6">


                <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm">
                    <form action="" method="POST">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Username</label>
                        <input type="text" name="username" value="<?= htmlspecialchars($data_user['username']); ?>" pattern="[a-zA-Z0-9_]+" oninput="this.value = this.value.replace(/[^a-zA-Z0-9_]/g, '')" title="Username hanya boleh berisi huruf, angka, dan underscore (tanpa spasi)" required class="w-full text-sm rounded-xl border border-slate-200 p-3 mb-4">
                        <button type="submit" name="update_profil" class="w-full py-3 bg-slate-900 text-white font-bold text-xs rounded-xl">Simpan Username</button>
                    </form>
                </div>

                <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm">
                    <form action="" method="POST" class="space-y-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase">Ganti Password</label>
                        <input type="password" name="pass_lama" placeholder="Password Lama" required class="w-full text-sm rounded-xl border border-slate-200 p-3">
                        <input type="password" name="pass_" placeholder="Password " required class="w-full text-sm rounded-xl border border-slate-200 p-3">
                        <input type="password" name="konfirmasi" placeholder="Konfirmasi Password " required class="w-full text-sm rounded-xl border border-slate-200 p-3">
                        <button type="submit" name="update_password" class="w-full py-3 bg-indigo-600 text-white font-bold text-xs rounded-xl">Peri Kata Sandi</button>
                    </form>
                </div>

                <?php if ($_SESSION['role'] === 'admin'): ?>
                <div class="bg-white p-8 rounded-2xl border border-rose-100 shadow-sm">
                    <form action="" method="POST" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-rose-600 uppercase">Reset Password Akun Lain (Admin)</label>
                            <p class="text-[10px] text-slate-500 mt-1">Masukkan username. Password akan direset ke <span class="font-bold text-slate-800">sekolah123</span>.</p>
                        </div>
                        <input type="text" name="target_username" placeholder="Username akun (contoh: guru1)" required class="w-full text-sm rounded-xl border border-slate-200 p-3">
                        <button type="submit" name="reset_user_lain" onclick="return confirm('Yakin ingin mereset password akun ini?')" class="w-full py-3 bg-rose-600 hover:bg-rose-700 transition-colors text-white font-bold text-xs rounded-xl">Reset Password ke Default</button>
                    </form>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = e.submitter || this.querySelector('button[type="submit"]');
            const actionName = btn.getAttribute('name');
            
            // Bypass vanilla js confirm logic
            if (actionName === 'reset_user_lain') {
                e.submitter.removeAttribute('onclick'); // prevent native confirm
                Swal.fire({
                    title: 'Yakin mereset password?',
                    text: "Password user akan dikembalikan ke default 'sekolah123'",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48', // rose-600
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'Ya, Reset!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        processForm(form, btn, actionName);
                    }
                });
            } else {
                processForm(form, btn, actionName);
            }
        });
    });
    
    function processForm(form, btn, actionName) {
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2 inline" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...';
        
        const formData = new FormData(form);
        formData.append('ajax', '1');
        formData.append('action', actionName);
        
        fetch('', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = originalText;
            if(data.status === 'success') {
                Swal.fire('Berhasil!', data.message, 'success').then(() => {
                    if(actionName === 'update_password' || actionName === 'reset_user_lain') {
                        form.reset();
                    } else {
                        window.location.reload();
                    }
                });
            } else {
                Swal.fire('Gagal!', data.message, 'error');
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = originalText;
            Swal.fire('Gagal!', 'Terjadi kesalahan jaringan.', 'error');
        });
    }
    </script>
</body>
</html>