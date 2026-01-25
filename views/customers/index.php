<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Data Pelanggan</h3>
    <a href="/customers/create" class="btn btn-primary"><i class="fas fa-user-plus"></i> Pelanggan Baru</a>
</div>
<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Nama</th>
                    <th>No. HP</th>
                    <th class="text-center">Kendaraan</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['customers'] as $customer): ?>
                    <tr>
                        <td class="align-middle"><?= escapeChars($customer['name']) ?></td>
                        <td class="align-middle"><?= escapeChars($customer['phone']) ?></td>
                        <td class="align-middle text-center fw-bold">
                            <?php 
                                if (!$customer['vehicles_list']) {
                                    echo '-';
                                } else {
                                    foreach (explode(',', $customer['vehicles_list']) as $vehicle) {
                                        echo escapeChars($vehicle) . '<br />';
                                    }
                                }
                            ?>
                        </td>
                        <td class="align-middle text-center">
                            <a href="/customers/create?id=<?= $customer['id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-plus"></i> Kendaraan</a>
                            <a href="/customers/detail?id=<?= $customer['id']; ?>" class="btn btn-sm btn-info">Detail</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
