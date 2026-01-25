<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Informasi Pelanggan & Kendaraan</h5>
            </div>
            <div class="card-body">
                <h6 class="text-muted mb-3">Informasi Pemilik</h6>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Nama Lengkap</label>
                        <input type="text" name="name" value="<?= escapeChars($data['customer']['name']); ?>" class="form-control" disabled />
                    </div>
                    <div class="col-md-6">
                        <label>Nomor HP (WhatsApp)</label>
                        <input type="text" name="phone" value="<?= escapeChars($data['customer']['phone']); ?>"  class="form-control" disabled />
                    </div>
                </div>
                <hr />
                <h6 class="text-muted mb-3">Informasi Kendaraan</h6>
                <?php foreach ($data['vehicles'] as $vehicle):?>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Merk</label>
                            <input type="text" name="brand" value="<?= escapeChars($vehicle['brand']); ?>" class="form-control" disabled />
                        </div>
                        <div class="col-md-6">
                            <label>Model / Tipe</label>
                            <input type="text" name="model" value="<?= escapeChars($vehicle['model']); ?>" class="form-control" disabled />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label>Warna</label>
                            <input type="text" name="color" value="<?= escapeChars($vehicle['color']); ?>" class="form-control" disabled />
                        </div>
                        <div class="col-md-6">
                            <label>Tahun Produksi</label>
                            <input type="number" name="year" value="<?= escapeChars($vehicle['production_year']); ?>" class="form-control" disabled />
                        </div>
                    </div>
                    <hr />
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>