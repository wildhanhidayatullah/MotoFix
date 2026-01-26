<?php require __DIR__ . '/../layouts/header.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-left-primary h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Omzet Hari Ini</div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800">
                            Rp<?= formatNumber($data['omzet_today']); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-left-success h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Transaksi Hari Ini</div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800">
                            <?= $data['trx_count']; ?> <span class="text-muted text-sm" style="font-size:14px">Struk</span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-receipt fa-2x text-gray-300 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-left-danger h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Stok Menipis</div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800">
                            <?= $data['low_stock_total']; ?> <span class="text-muted text-sm" style="font-size:14px">Item</span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Grafik Pendapatan (7 Hari Terakhir)</h6>
            </div>
            <div class="card-body">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-danger text-white">
                <h6 class="m-0 font-weight-bold">Perlu Re-Stock</h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Barang</th>
                            <th class="text-center">Sisa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['low_stock_items'] as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']); ?></td>
                            <td class="text-center fw-bold text-danger"><?= $item['stock']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if (empty($data['low_stock_items'])): ?>
                            <tr><td colspan="2" class="text-center py-3">Stok Aman</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer text-center">
                <a href="/inventory" class="btn btn-sm btn-light">Lihat Semua Inventory</a>
            </div>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= $data['chart_labels']; ?>,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: <?= $data['chart_values']; ?>,
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 2,
                pointRadius: 4,
                pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointBorderColor: '#fff',
                pointHoverRadius: 6,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { borderDash: [2, 2] }
                },
                x: {
                    grid: { display: false }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>