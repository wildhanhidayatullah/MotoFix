<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <?php if (isset($data['customer'])): ?>
                    <h5 class="mb-0">Tambah Kendaraan Kendaraan</h5>
                <?php else: ?>
                    <h5 class="mb-0">Registrasi Pelanggan & Kendaraan</h5>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <form action="/customers/store" method="POST">
                    <?php csrfToken(); ?>
                    <h6 class="text-muted mb-3">Informasi Pemilik</h6>
                    <div class="row mb-3">
                        <input type="hidden" name="id" value="<?= $data['customer']['id'] ?? null; ?>" />
                        <div class="col-md-6">
                            <label>Nama Lengkap</label>
                            <input type="text" name="name" value="<?= $data['customer']['name'] ?? null; ?>" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Nomor HP (WhatsApp)</label>
                            <input type="text" name="phone" value="<?= $data['customer']['phone'] ?? null; ?>" class="form-control" required placeholder="628...">
                        </div>
                    </div>
                    <hr />
                    <h6 class="text-muted mb-3">Informasi Kendaraan</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Merk</label>
                            <select name="brand" class="form-select" required>
                                <option value="Honda">Honda</option>
                                <option value="Yamaha">Yamaha</option>
                                <option value="Suzuki">Suzuki</option>
                                <option value="Kawasaki">Kawasaki</option>
                                <option value="Vespa">Vespa</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Model / Tipe</label>
                            <input type="text" name="model" class="form-control" required placeholder="Contoh: Vario 160 ABS">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label>Warna</label>
                            <input type="text" name="color" class="form-control" placeholder="Contoh: Hitam Doff">
                        </div>
                        <div class="col-md-6">
                            <label>Tahun Produksi</label>
                            <input type="number" name="year" class="form-control" placeholder="2023">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <a href="/customers" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>