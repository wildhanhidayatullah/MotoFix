<?php

use App\Helpers\FormatHelper;
$format = new FormatHelper();

require __DIR__ . '/../layouts/header.php';

?>

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
                        <td class="align-middle"><?= $format->escapeChars($customer['name']) ?></td>
                        <td class="align-middle"><?= $format->escapeChars($customer['phone']) ?></td>
                        <td class="align-middle text-center fw-bold">
                            <?= htmlspecialchars($customer['vehicles_list'] ?? '-'); ?>
                        </td>
                        <td class="align-middle text-center">
                            <a href="#" class="btn btn-sm btn-info">Detail</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
