<?php
require_once 'config/db.php';
check_login();

$active_page = 'dashboard';

// Fetch stats
$job_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM jobs"))['count'];
$applicant_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM applicants"))['count'];
$pending_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM applications WHERE status = 'pending'"))['count'];

include 'includes/header.php';
?>

<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 bg-primary bg-opacity-10 p-3 rounded-3 text-primary">
                    <i class="bi bi-briefcase fs-3"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="text-secondary mb-1">Total Lowongan</h6>
                    <h3 class="mb-0 fw-bold"><?= $job_count ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 bg-success bg-opacity-10 p-3 rounded-3 text-success">
                    <i class="bi bi-people fs-3"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="text-secondary mb-1">Total Pelamar</h6>
                    <h3 class="mb-0 fw-bold"><?= $applicant_count ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0 bg-warning bg-opacity-10 p-3 rounded-3 text-warning">
                    <i class="bi bi-clock-history fs-3"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="text-secondary mb-1">Butuh Review</h6>
                    <h3 class="mb-0 fw-bold"><?= $pending_count ?></h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">Lowongan Terbaru</h5>
                <a href="<?= base_url('modules/jobs/index.php') ?>" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Posisi</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th>Tanggal Post</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM jobs ORDER BY created_at DESC LIMIT 5";
                        $result = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_assoc($result)) :
                        ?>
                        <tr>
                            <td><span class="fw-semibold text-dark"><?= $row['title'] ?></span></td>
                            <td><?= $row['location'] ?></td>
                            <td>
                                <span class="badge bg-<?= $row['status'] == 'open' ? 'success' : 'danger' ?> bg-opacity-10 text-<?= $row['status'] == 'open' ? 'success' : 'danger' ?>">
                                    <?= ucfirst($row['status']) ?>
                                </span>
                            </td>
                            <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                        </tr>
                        <?php endwhile; ?>
                        <?php if (mysqli_num_rows($result) == 0) : ?>
                        <tr>
                            <td colspan="4" class="text-center py-4 text-secondary italic">Belum ada data lowongan.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
