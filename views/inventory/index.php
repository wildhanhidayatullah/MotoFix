<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Inventaris Sparepart</h3>
    <a href="/inventory/create" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Barang</a>
</div>
<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th class="text-center">Stok</th>
                    <th class="text-center">Harga Beli</th>
                    <th class="text-center">Harga Jual</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['items'] as $item): ?>
                <tr>
                    <td class="align-middle"><?= escapeChars($item['code']); ?></td>
                    <td class="align-middle"><?= escapeChars($item['name']); ?></td>
                    <td class="align-middle text-center fw-bold <?= $item['stock'] <= $item['min_stock_alert'] ? 'text-danger' : 'text-success'; ?>">
                        <?= $item['stock']; ?>
                    </td>
                    <td class="align-middle text-center">Rp<?= formatNumber($item['buy_price']); ?></td>
                    <td class="align-middle text-center">Rp<?= formatNumber($item['sell_price']); ?></td>
                    <td class="align-middle text-center">
                        <a href="/inventory/edit?id=<?= $item['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="/inventory/delete?id=<?= $item['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus layanan <?= $item['name']; ?>?')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                
                <?php if (empty($data['items'])): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted">Belum ada data barang.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php';?>
