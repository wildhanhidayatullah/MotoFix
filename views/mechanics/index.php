<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Mekanik</h3>
    <a href="/mechanics/create" class="btn btn-primary"><i class="fas fa-user-plus"></i> Mekanik Baru</a>
</div>
<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Nama</th>
                    <th>No. HP</th>
                    <th>Gaji Pokok</th>
                    <th class="text-center">Komisi</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['mechanics'] as $mechanic): ?>
                    <tr>
                        <td class="align-middle"><?= escapeChars($mechanic['name']) ?></td>
                        <td class="align-middle"><?= escapeChars($mechanic['phone']) ?></td>
                        <td class="align-middle">Rp<?= formatNumber((int)$mechanic['base_salary']) ?></td>
                        <td class="align-middle text-center"><?= escapeChars($mechanic['commission_rate']) ?>%</td>
                        <td class="align-middle text-center">
                            <?php if ($mechanic['is_active']): ?>
                                <span class="fw-bold text-success">Aktif</span>
                            <?php else: ?>
                                <span class="fw-bold text-danger">Non-Aktif</span>
                            <?php endif; ?>
                        </td>
                        <td class="align-middle text-center">
                            <a href="/mechanics/edit?id=<?= $mechanic['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
