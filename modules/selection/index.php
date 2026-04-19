<?php
require_once '../../config/db.php';
check_login();

$active_page = 'selection';
$message = '';

if (isset($_GET['msg']) && $_GET['msg'] == 'updated') {
    $message = '<div class="alert alert-success">Status pelamar berhasil diperbarui!</div>';
}

include '../../includes/header.php';
?>

<div class="mb-4">
    <h4 class="fw-bold">Seleksi Pelamar</h4>
    <p class="text-secondary">Kelola dan tinjau berkas lamaran yang masuk.</p>
</div>

<?= $message ?>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Pelamar</th>
                    <th>Lowongan</th>
                    <th>Tgl Melamar</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT a.id as app_id, a.status, a.applied_at, 
                                 p.name as applicant_name, p.email, p.resume,
                                 j.title as job_title
                          FROM applications a
                          JOIN applicants p ON a.applicant_id = p.id
                          JOIN jobs j ON a.job_id = j.id
                          ORDER BY a.applied_at DESC";
                $result = mysqli_query($conn, $query);
                while ($row = mysqli_fetch_assoc($result)) :
                    $status_class = [
                        'pending' => 'warning',
                        'reviewed' => 'info',
                        'accepted' => 'success',
                        'rejected' => 'danger'
                    ];
                ?>
                <tr>
                    <td>
                        <div class="fw-bold text-dark"><?= $row['applicant_name'] ?></div>
                        <small class="text-secondary"><?= $row['email'] ?></small>
                    </td>
                    <td><?= $row['job_title'] ?></td>
                    <td><?= date('d M Y', strtotime($row['applied_at'])) ?></td>
                    <td>
                        <span class="badge bg-<?= $status_class[$row['status']] ?> bg-opacity-10 text-<?= $status_class[$row['status']] ?>">
                            <?= ucfirst($row['status']) ?>
                        </span>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#updateModal<?= $row['app_id'] ?>">
                            Update Status
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="updateModal<?= $row['app_id'] ?>" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="update_status.php" method="POST">
                                        <input type="hidden" name="app_id" value="<?= $row['app_id'] ?>">
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold">Update Status: <?= $row['applicant_name'] ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body text-start">
                                            <p class="small text-secondary mb-3">Posisi: <strong><?= $row['job_title'] ?></strong></p>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Pilih Status Baru</label>
                                                <select name="status" class="form-select">
                                                    <option value="pending" <?= $row['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                                    <option value="reviewed" <?= $row['status'] == 'reviewed' ? 'selected' : '' ?>>Reviewed</option>
                                                    <option value="accepted" <?= $row['status'] == 'accepted' ? 'selected' : '' ?>>Accepted</option>
                                                    <option value="rejected" <?= $row['status'] == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php if (mysqli_num_rows($result) == 0) : ?>
                <tr>
                    <td colspan="5" class="text-center py-5 text-secondary">
                        <i class="bi bi-person-x fs-1 d-block mb-3 opacity-25"></i>
                        Belum ada pelamar yang masuk.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>
