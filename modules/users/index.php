<?php
require_once '../../config/db.php';
check_login();

$active_page = 'users';
$message = '';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM users WHERE id = $id");
    header('Location: index.php?msg=deleted');
    exit();
}

// Handle Create
if (isset($_POST['create'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO users (username, fullname, email, password, status) 
              VALUES ('$username', '$fullname', '$email', '$password', '$status')";
    
    if (mysqli_query($conn, $query)) {
        header('Location: index.php?msg=created');
        exit();
    } else {
        $message = '<div class="alert alert-danger">Gagal menambah pengguna! Username mungkin sudah ada.</div>';
    }
}

// Handle Update
if (isset($_POST['update'])) {
    $id = (int)$_POST['id'];
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $password = $_POST['password'];

    $query = "UPDATE users SET username = '$username', fullname = '$fullname', email = '$email', status = '$status'";
    
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query .= ", password = '$hashed_password'";
    }
    
    $query .= " WHERE id = $id";
    
    if (mysqli_query($conn, $query)) {
        header('Location: index.php?msg=updated');
        exit();
    } else {
        $message = '<div class="alert alert-danger">Gagal memperbarui pengguna!</div>';
    }
}

if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'deleted') $message = '<div class="alert alert-success alert-dismissible fade show">Pengguna Pelamar berhasil dihapus!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    if ($_GET['msg'] == 'created') $message = '<div class="alert alert-success alert-dismissible fade show">Akun Pelamar baru berhasil ditambahkan!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    if ($_GET['msg'] == 'updated') $message = '<div class="alert alert-success alert-dismissible fade show">Data Pelamar berhasil diperbarui!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
}

include '../../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Kelola Akun Pelamar</h4>
        <p class="text-secondary small mb-0">Manajemen akun untuk pencari kerja.</p>
    </div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalUser">
        <i class="bi bi-person-plus me-2"></i> Tambah Pelamar
    </button>
</div>

<?= $message ?>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nama Pelamar</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Terdaftar</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM users ORDER BY id DESC";
                $result = mysqli_query($conn, $query);
                while ($row = mysqli_fetch_assoc($result)) :
                ?>
                <tr>
                    <td><div class="fw-bold text-dark"><?= $row['fullname'] ?></div></td>
                    <td><span class="badge bg-light text-dark border"><?= $row['username'] ?></span></td>
                    <td><?= $row['email'] ?></td>
                    <td>
                        <span class="badge bg-<?= $row['status'] == 'active' ? 'success' : 'danger' ?> bg-opacity-10 text-<?= $row['status'] == 'active' ? 'success' : 'danger' ?>">
                            <?= ucfirst($row['status']) ?>
                        </span>
                    </td>
                    <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                    <td class="text-center">
                        <div class="btn-group shadow-sm">
                            <button type="button" class="btn btn-sm btn-white text-primary border edit-btn" 
                                    title="Edit"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalUser"
                                    data-id="<?= $row['id'] ?>"
                                    data-username="<?= htmlspecialchars($row['username']) ?>"
                                    data-fullname="<?= htmlspecialchars($row['fullname']) ?>"
                                    data-email="<?= htmlspecialchars($row['email']) ?>"
                                    data-status="<?= $row['status'] ?>">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <a href="index.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-white text-danger border" title="Hapus" onclick="return confirm('Yakin ingin menghapus akun pelamar ini?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php if (mysqli_num_rows($result) == 0) : ?>
                <tr>
                    <td colspan="6" class="text-center py-4 text-secondary">Belum ada akun pelamar.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Create/Edit User -->
<div class="modal fade" id="modalUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="" method="POST">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold" id="modalTitle">Tambah Pelamar Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="id" id="user_id">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" name="fullname" id="user_fullname" class="form-control" placeholder="Nama Lengkap" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" id="user_email" class="form-control" placeholder="email@example.com" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Username</label>
                            <input type="text" name="username" id="user_username" class="form-control" placeholder="Username" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Status Akun</label>
                            <select name="status" id="user_status" class="form-select">
                                <option value="active">Aktif</option>
                                <option value="inactive">Non-Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold" id="passLabel">Password</label>
                        <input type="password" name="password" id="user_password" class="form-control" placeholder="Password">
                        <small class="text-secondary d-none" id="passHelp">Kosongkan jika tidak ingin ganti password.</small>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="create" id="btnSubmit" class="btn btn-primary px-4">Simpan Pelamar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalUser = document.getElementById('modalUser');
    const modalTitle = document.getElementById('modalTitle');
    const btnSubmit = document.getElementById('btnSubmit');
    const passHelp = document.getElementById('passHelp');
    const userPass = document.getElementById('user_password');
    const form = modalUser.querySelector('form');

    modalUser.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        if (!button.classList.contains('edit-btn')) {
            modalTitle.innerText = 'Tambah Pelamar Baru';
            btnSubmit.innerText = 'Simpan Pelamar';
            btnSubmit.name = 'create';
            form.reset();
            document.getElementById('user_id').value = '';
            userPass.required = true;
            passHelp.classList.add('d-none');
        } else {
            modalTitle.innerText = 'Edit Akun Pelamar';
            btnSubmit.innerText = 'Perbarui Pelamar';
            btnSubmit.name = 'update';
            userPass.required = false;
            passHelp.classList.remove('d-none');
            
            document.getElementById('user_id').value = button.getAttribute('data-id');
            document.getElementById('user_username').value = button.getAttribute('data-username');
            document.getElementById('user_fullname').value = button.getAttribute('data-fullname');
            document.getElementById('user_email').value = button.getAttribute('data-email');
            document.getElementById('user_status').value = button.getAttribute('data-status');
        }
    });
});
</script>

<?php include '../../includes/footer.php'; ?>
