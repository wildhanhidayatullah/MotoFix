<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Pengguna</h3>
    <a href="/users/create" class="btn btn-primary"><i class="fas fa-user-plus"></i> Pengguna Baru</a>
</div>
<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Nama Pengguna</th>
                    <th class="text-center">Role</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Created At</th>
                    <th class="text-center" width="150">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['users'] as $user): ?>
                    <tr>
                        <td class="align-middle"><?= escapeChars($user['username']); ?></td>
                        <td class="align-middle text-center"><?= ucfirst(escapeChars($user['role'])); ?></td>
                        <td class="align-middle text-center">
                            <?php if ($user['is_active']): ?>
                                <span class="fw-bold text-success">Aktif</span>
                            <?php else: ?>
                                <span class="fw-bold text-danger">Non-Aktif</span>
                            <?php endif; ?>
                        </td>
                        <td class="align-middle text-center"><?php date_default_timezone_set('Asia/Jakarta'); echo date('d M Y', strtotime($user['created_at'])); ?></td>
                        <td class="align-middle text-center">
                            <a href="/users/edit?id=<?= $user['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
