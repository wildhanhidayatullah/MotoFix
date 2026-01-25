<?php require __DIR__ . '/../layouts/header.php';?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Tambah Pengguna Baru</h5>
            </div>
            <div class="card-body">
                <form action="/users/store" method="POST">
                    <?php csrfToken(); ?>
                    <div class="mb-3">
                        <label>Nama Pengguna</label>
                        <input required type="text" name="username" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label>Role</label>
                        <select required name="role" class="form-select">
                            <option value="admin">Admin</option>
                            <option value="cashier">Cashier</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input required type="text" name="password" class="form-control" />
                    </div>
                    <div class="d-flex justify-content-end">
                        <a href="/users" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php';?>
