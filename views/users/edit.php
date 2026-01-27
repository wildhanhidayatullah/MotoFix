<?php require __DIR__ . '/../layouts/header.php';?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Edit Data Pengguna</h5>
            </div>
            <div class="card-body">
                <form action="/users/update" method="POST">
                    <?php csrfToken(); ?>
                    <input type="hidden" name="id" value="<?= $data['user']['id']; ?>" />
                    <div class="mb-3">
                        <label>Nama Pengguna</label>
                        <input required type="text" name="username" value="<?= escapeChars($data['user']['username']); ?>" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label>Role</label>
                        <select name="role" class="form-select">
                            <option value="<?= $data['user']['role']; ?>"><?= ucfirst(escapeChars($data['user']['role'])); ?></option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label description="Jangan diganti">Password</label><br />
                        <span class="text-danger" style="font-size: 15px">Kosongkan jika tidak ingin mengganti password</span>
                        <input type="text" name="password" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select required name="is_active" type="number" class="form-control">
                            <option value="<?= ($data['user']['is_active']) ? 1 : 0; ?>">
                                <?= ($data['user']['is_active']) ? 'Aktif' : 'Non-Aktif'; ?>
                            </option>
                            <option value="<?= ($data['user']['is_active']) ? 0 : 1; ?>">
                                <?= ($data['user']['is_active']) ? 'Non-Aktif' : 'Aktif'; ?>
                            </option>
                        </select>
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
