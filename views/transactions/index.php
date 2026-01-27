<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Riwayat Transaksi</h3>
    <a href="/transactions/create" class="btn btn-primary"><i class="fas fa-cash-register"></i> Transaksi Baru</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Tanggal</th>
                    <th>No. Invoice</th>
                    <th>Pelanggan</th>
                    <th>Kendaraan</th>
                    <th>Total</th>
                    <th>Kasir</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['transactions'] as $transaction): ?>
                <tr>
                    <td class="align-middle"><?= date('d/m/Y H:i', strtotime($transaction['transaction_date'])); ?></td>
                    <td class="align-middle"><?= $transaction['invoice_number']; ?></td>
                    <td class="align-middle"><?= htmlspecialchars($transaction['customer_name']); ?></td>
                    <td class="align-middle"><?= htmlspecialchars($transaction['brand'] . ' ' . $transaction['vehicle_model']); ?> </td>
                    <td class="align-middle fw-bold">Rp <?= number_format($transaction['total_amount'], 0, ',', '.'); ?></td>
                    <td class="align-middle"><?= htmlspecialchars($transaction['cashier_name']); ?></td>
                    <td class="align-middle">
                        <a href="/transactions/show?inv=<?= urlencode($transaction['invoice_number']); ?>" class="btn btn-sm btn-info text-white">
                            <i class="fas fa-print"></i> Lihat
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($data['transactions'])): ?>
                    <tr><td colspan="7" class="text-center">Belum ada transaksi.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
