<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Daftar Layanan</h3>
    <?php if (isAdmin() || isOwner()): ?>
        <a href="/services/create" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Layanan</a>
    <?php endif; ?>
</div>
<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Nama Layanan</th>
                    <th>Harga</th>
                    <?php if (isAdmin() || isOwner()): ?>
                        <th class="text-center" width="150">Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['services'] as $service): ?>
                <tr>
                    <td class="align-middle"><?= escapeChars($service['name']); ?></td>
                    <td class="align-middle">Rp<?= formatNumber($service['price'], 0, ',', '.'); ?></td>
                    <?php if (isAdmin() || isOwner()): ?>
                        <td class="align-middle text-center">
                            <a href="/services/edit?id=<?= $service['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="/services/delete?id=<?= $service['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus layanan <?= $service['name']; ?>?')">Hapus</a>
                        </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>

                <?php if (empty($data['services'])): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted">Belum ada data layanan.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>