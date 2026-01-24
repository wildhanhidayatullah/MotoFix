<?php require __DIR__ . '/../layouts/header.php';?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Edit Data Mekanik</h5>
            </div>
            <div class="card-body">
                <form action="/mechanics/update" method="POST">
                    <?php csrfToken(); ?>
                    <input type="hidden" name="id" value="<?= $data['mechanic']['id']; ?>" />
                    <div class="mb-3">
                        <label>Nama Mekanik</label>
                        <input required type="text" name="name" value="<?= $data['mechanic']['name']; ?>" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label>No. HP</label>
                        <input required type="text" name="phone" value="<?= $data['mechanic']['phone']; ?>" class="form-control" placeholder="628..." />
                    </div>
                    <div class="mb-3">
                        <label>Gaji Pokok</label>
                        <input required type="number" name="base_salary" value="<?= $data['mechanic']['base_salary']; ?>" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label>Komisi (%)</label>
                        <input required type="number" name="commission_rate" value="<?= $data['mechanic']['commission_rate']; ?>" class="form-control" step="0.05" />
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select required name="is_active" type="number" class="form-control">
                            <option value="<?= ($data['mechanic']['is_active']) ? 1 : 0; ?>">
                                <?= ($data['mechanic']['is_active']) ? 'Aktif' : 'Non-Aktif'; ?>
                            </option>
                            <option value="<?= ($data['mechanic']['is_active']) ? 0 : 1; ?>">
                                <?= ($data['mechanic']['is_active']) ? 'Non-Aktif' : 'Aktif'; ?>
                            </option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-end">
                        <a href="/mechanics" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php';?>
