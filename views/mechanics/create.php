<?php require __DIR__ . '/../layouts/header.php';?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Tambah Mekanik Baru</h5>
            </div>
            <div class="card-body">
                <form action="/mechanics/store" method="POST">
                    <?php csrfToken(); ?>
                    <div class="mb-3">
                        <label>Nama Mekanik</label>
                        <input required type="text" name="name" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label>No. HP</label>
                        <input required type="text" name="phone" class="form-control" placeholder="628..." />
                    </div>
                    <div class="mb-3">
                        <label>Gaji Pokok</label>
                        <input required type="number" name="base_salary" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label>Komisi (%)</label>
                        <input required type="number" name="commission_rate" class="form-control" step="0.05" />
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
