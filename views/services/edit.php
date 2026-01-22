<?php require __DIR__ . '/../layouts/header.php';?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Tambah Layanan Baru</h5>
            </div>
            <div class="card-body">
                <form action="/services/update" method="POST">
                    <?php csrfToken(); ?>
                    <input type="hidden" name='id' value="<?= $data['service']['id']; ?>">
                    <div class="mb-3">
                        <label>Nama Layanan</label>
                        <input required type="text" name="name" class="form-control" value="<?= $data['service']['name']?>" />
                    </div>
                    <div class="mb-3">
                        <label>Harga</label>
                        <input required type="number" name="price" class="form-control" value="<?= (int)$data['service']['price']?>" />
                    </div>
                    <div class="d-flex justify-content-end">
                        <a href="/services" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php';?>
