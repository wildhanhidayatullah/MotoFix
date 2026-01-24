<?php require __DIR__ . '/../layouts/header.php';?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Tambah Barang Baru</h5>
            </div>
            <div class="card-body">
                <form action="/inventory/update" method="POST">
                    <?php csrfToken(); ?>
                    <input type="hidden" name="id" value="<?= $data['item']['id']; ?>" />
                    <div class="mb-3">
                        <label>Kode Barang</label>
                        <input required type="text" name="code" value="<?= $data['item']['code']; ?>" class="form-control" placeholder="Contoh: OLI-YAMALUBE-01">
                    </div>
                    <div class="mb-3">
                        <label>Nama Barang</label>
                        <input required type="text" name="name" value="<?= $data['item']['name']; ?>" class="form-control" placeholder="Contoh: Oli Yamalube Matic 800ml" />
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Stok</label>
                            <input required type="number" name="stock" value="<?= $data['item']['stock']; ?>" class="form-control" min="0" />
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Harga Beli</label>
                            <input required type="number" name="buy_price" value="<?= (int)$data['item']['buy_price']; ?>" class="form-control" />
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Harga Jual</label>
                            <input required type="number" name="sell_price" value="<?= (int)$data['item']['sell_price']; ?>" class="form-control" />
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <a href="/inventory" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php';?>
