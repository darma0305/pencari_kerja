<?php
require_once '../../config/db.php';
check_login();

$active_page = 'profile';
$error = '';
$success = '';
$admin_id = $_SESSION['admin_id'];

// Handle Update Profile
if (isset($_POST['update_profile'])) {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    
    $update_query = "UPDATE admins SET fullname = '$fullname', email = '$email', username = '$username'";
    
    // Handle Photo Upload
    if (!empty($_FILES['photo']['name'])) {
        $filename = time() . '_' . $_FILES['photo']['name'];
        $target = "../../uploads/profile/" . $filename;
        
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
            $update_query .= ", photo = '$filename'";
            
            // Delete old photo if exists and not default
            $old_photo_query = mysqli_query($conn, "SELECT photo FROM admins WHERE id = $admin_id");
            $old_photo = mysqli_fetch_assoc($old_photo_query)['photo'];
            if ($old_photo && $old_photo != 'default.png' && file_exists("../../uploads/profile/" . $old_photo)) {
                unlink("../../uploads/profile/" . $old_photo);
            }
        }
    }

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $update_query .= ", password = '$hashed_password'";
    }
    
    $update_query .= " WHERE id = $admin_id";

    if (mysqli_query($conn, $update_query)) {
        $_SESSION['admin_name'] = $fullname;
        $success = 'Profil berhasil diperbarui!';
    } else {
        $error = 'Gagal memperbarui profil!';
    }
}

// Fetch current data
$result = mysqli_query($conn, "SELECT * FROM admins WHERE id = $admin_id");
$admin = mysqli_fetch_assoc($result);
$photo_url = ($admin['photo'] && $admin['photo'] != 'default.png') 
            ? base_url('uploads/profile/' . $admin['photo']) 
            : base_url('assets/img/admin.png');

include '../../includes/header.php';
?>

<div class="mb-4">
    <h4 class="fw-bold">Pengaturan Profil</h4>
    <p class="text-secondary">Kelola informasi akun dan identitas Anda.</p>
</div>

<?php if ($success) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $success ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php if ($error) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= $error ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-5">
        <div class="card text-center p-4">
            <div class="mb-4">
                <img src="<?= $photo_url ?>" class="rounded-circle border p-1 mb-3" width="120" height="120" style="object-fit: cover;">
                <h5 class="fw-bold mb-0"><?= $admin['fullname'] ?></h5>
                <p class="text-secondary mb-3"><?= $admin['email'] ?></p>
                <span class="badge bg-primary bg-opacity-10 text-primary px-3">Administrator</span>
            </div>
            <button class="btn btn-outline-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#modalProfile">
                <i class="bi bi-pencil-square me-2"></i> Edit Profil
            </button>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card p-4">
            <h6 class="fw-bold mb-4">Informasi Detail</h6>
            <div class="row mb-3">
                <div class="col-sm-4 text-secondary">Username</div>
                <div class="col-sm-8 fw-semibold"><?= $admin['username'] ?></div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-4 text-secondary">Email</div>
                <div class="col-sm-8 fw-semibold"><?= $admin['email'] ?></div>
            </div>
            <div class="row mb-0">
                <div class="col-sm-4 text-secondary">Dibuat Pada</div>
                <div class="col-sm-8 fw-semibold"><?= date('d F Y', strtotime($admin['created_at'])) ?></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Profile -->
<div class="modal fade" id="modalProfile" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold">Edit Profil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 pb-4">
                    <div class="mb-4 text-center">
                        <label for="photoUpload" style="cursor: pointer;">
                            <img src="<?= $photo_url ?>" id="previewPhoto" class="rounded-circle border p-1 mb-2" width="100" height="100" style="object-fit: cover;">
                            <div class="small text-primary"><i class="bi bi-camera me-1"></i> Ganti Foto</div>
                        </label>
                        <input type="file" name="photo" id="photoUpload" class="d-none" accept="image/*" onchange="previewImage(this)">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" name="fullname" class="form-control" value="<?= $admin['fullname'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= $admin['email'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username</label>
                        <input type="text" name="username" class="form-control" value="<?= $admin['username'] ?>" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Password Baru (Opsional)</label>
                        <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin ganti">
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="update_profile" class="btn btn-primary px-4">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewPhoto').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php include '../../includes/footer.php'; ?>
