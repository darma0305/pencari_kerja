<?php
require_once '../../config/db.php';
check_login();

$active_page = 'jobs';
$message = '';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM jobs WHERE id = $id");
    header('Location: index.php?msg=deleted');
    exit();
}

// Handle Create
if (isset($_POST['create'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $salary = mysqli_real_escape_string($conn, $_POST['salary']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $requirements = mysqli_real_escape_string($conn, $_POST['requirements']);

    $query = "INSERT INTO jobs (title, location, salary, status, description, requirements) 
              VALUES ('$title', '$location', '$salary', '$status', '$description', '$requirements')";
    
    if (mysqli_query($conn, $query)) {
        header('Location: index.php?msg=created');
        exit();
    }
}

// Handle Update
if (isset($_POST['update'])) {
    $id = (int)$_POST['id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $salary = mysqli_real_escape_string($conn, $_POST['salary']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $requirements = mysqli_real_escape_string($conn, $_POST['requirements']);

    $query = "UPDATE jobs SET 
              title = '$title', 
              location = '$location', 
              salary = '$salary', 
              status = '$status', 
              description = '$description', 
              requirements = '$requirements'
              WHERE id = $id";
    
    if (mysqli_query($conn, $query)) {
        header('Location: index.php?msg=updated');
        exit();
    }
}

if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'deleted') $message = '<div class="alert alert-success alert-dismissible fade show" role="alert">Lowongan berhasil dihapus!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    if ($_GET['msg'] == 'created') $message = '<div class="alert alert-success alert-dismissible fade show" role="alert">Lowongan baru berhasil ditambahkan!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    if ($_GET['msg'] == 'updated') $message = '<div class="alert alert-success alert-dismissible fade show" role="alert">Lowongan berhasil diperbarui!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
}

include '../../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Kelola Lowongan</h4>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalJob">
        <i class="bi bi-plus-lg me-2"></i> Tambah Lowongan
    </button>
</div>

<?= $message ?>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Judul Lowongan</th>
                    <th>Lokasi</th>
                    <th>Gaji</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT * FROM jobs ORDER BY created_at DESC";
                $result = mysqli_query($conn, $query);
                while ($row = mysqli_fetch_assoc($result)) :
                ?>
                <tr>
                    <td>
                        <div class="fw-bold text-dark"><?= $row['title'] ?></div>
                        <small class="text-secondary"><?= date('d M Y', strtotime($row['created_at'])) ?></small>
                    </td>
                    <td><?= $row['location'] ?></td>
                    <td><?= $row['salary'] ?></td>
                    <td>
                        <span class="badge bg-<?= $row['status'] == 'open' ? 'success' : 'danger' ?> bg-opacity-10 text-<?= $row['status'] == 'open' ? 'success' : 'danger' ?>">
                            <?= ucfirst($row['status']) ?>
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="btn-group shadow-sm">
                            <button type="button" class="btn btn-sm btn-white text-primary border edit-btn" 
                                    title="Edit"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalJob"
                                    data-id="<?= $row['id'] ?>"
                                    data-title="<?= htmlspecialchars($row['title']) ?>"
                                    data-location="<?= htmlspecialchars($row['location']) ?>"
                                    data-salary="<?= htmlspecialchars($row['salary']) ?>"
                                    data-status="<?= $row['status'] ?>"
                                    data-description="<?= htmlspecialchars($row['description']) ?>"
                                    data-requirements="<?= htmlspecialchars($row['requirements']) ?>">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            <a href="index.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-white text-danger border" title="Hapus" onclick="return confirm('Yakin ingin menghapus lowongan ini?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php if (mysqli_num_rows($result) == 0) : ?>
                <tr>
                    <td colspan="5" class="text-center py-5 text-secondary">
                        <i class="bi bi-briefcase fs-1 d-block mb-3 opacity-25"></i>
                        Belum ada lowongan yang diposting.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Create/Edit Job -->
<div class="modal fade" id="modalJob" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="" method="POST">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold" id="modalTitle">Tambah Lowongan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="id" id="job_id">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Judul Lowongan</label>
                                <input type="text" name="title" id="job_title" class="form-control" placeholder="Contoh: Web Developer" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Deskripsi Pekerjaan</label>
                                <textarea name="description" id="job_description" class="form-control" rows="4" placeholder="Jelaskan detail pekerjaan..."></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Persyaratan</label>
                                <textarea name="requirements" id="job_requirements" class="form-control" rows="4" placeholder="Jelaskan syarat pelamar..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Lokasi</label>
                                <input type="text" name="location" id="job_location" class="form-control" placeholder="Contoh: Jakarta" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Gaji (Opsional)</label>
                                <input type="text" name="salary" id="job_salary" class="form-control" placeholder="Contoh: 5jt - 7jt">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Status</label>
                                <select name="status" id="job_status" class="form-select">
                                    <option value="open">Buka (Open)</option>
                                    <option value="closed">Tutup (Closed)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="create" id="btnSubmit" class="btn btn-primary px-4">Simpan Lowongan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalJob = document.getElementById('modalJob');
    const modalTitle = document.getElementById('modalTitle');
    const btnSubmit = document.getElementById('btnSubmit');
    const form = modalJob.querySelector('form');

    // Reset modal when opened for Create
    modalJob.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        if (!button.classList.contains('edit-btn')) {
            modalTitle.innerText = 'Tambah Lowongan Baru';
            btnSubmit.innerText = 'Simpan Lowongan';
            btnSubmit.name = 'create';
            form.reset();
            document.getElementById('job_id').value = '';
        } else {
            // Fill data for Edit
            modalTitle.innerText = 'Edit Lowongan';
            btnSubmit.innerText = 'Perbarui Lowongan';
            btnSubmit.name = 'update';
            
            document.getElementById('job_id').value = button.getAttribute('data-id');
            document.getElementById('job_title').value = button.getAttribute('data-title');
            document.getElementById('job_location').value = button.getAttribute('data-location');
            document.getElementById('job_salary').value = button.getAttribute('data-salary');
            document.getElementById('job_status').value = button.getAttribute('data-status');
            document.getElementById('job_description').value = button.getAttribute('data-description');
            document.getElementById('job_requirements').value = button.getAttribute('data-requirements');
        }
    });
});
</script>

<?php include '../../includes/footer.php'; ?>
