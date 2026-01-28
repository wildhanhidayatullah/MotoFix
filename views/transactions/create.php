<?php require __DIR__ . '/../layouts/header.php'; ?>

<script>
    const CSRF_TOKEN = "<?= $_SESSION['csrf_token'] ?? '' ?>";
    const ITEMS_DATA = <?= $data['items_json']; ?>;
    const SERVICES_DATA = <?= $data['services_json']; ?>;
    const MECHANICS_DATA = <?= $data['mechanics_json']; ?>;
</script>

<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-primary text-white">1. Pelanggan & Kendaraan</div>
            <div class="card-body">
                <div class="mb-3">
                    <label>Pilih Pelanggan</label>
                    <select id="customerSelect" class="form-select">
                        <option value="">-- Cari Pelanggan --</option>
                        <?php foreach ($data['customers'] as $c): ?>
                            <option value="<?= $c['id']; ?>"><?= $c['name']; ?> (<?= $c['phone']; ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Pilih Kendaraan</label>
                    <select id="vehicleSelect" class="form-select" disabled>
                        <option value="">-- Pilih Kendaraan --</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between">
                <span>2. Item Transaksi</span>
                <button type="button" class="btn btn-sm btn-light py-0" onclick="addItemRow()"><i class="fas fa-add"></i> Tambah Item</button>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0" id="cartTable">
                    <thead>
                        <tr>
                            <th width="35%">Item / Jasa</th>
                            <th width="20%">Mekanik</th>
                            <th width="10%">Qty</th>
                            <th width="20%">Harga</th>
                            <th width="10%">Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        </tbody>
                    <tfoot class="table-dark">
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total Bayar:</strong></td>
                            <td colspan="2"><strong id="grandTotal">Rp0</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="card-footer text-end">
                <button type="button" class="btn btn-md btn-success" onclick="processCheckout()">
                    <i class="fas fa-save"></i> SIMPAN & BAYAR
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('customerSelect').addEventListener('change', async function() {
        const customerId = this.value;
        const vehicleSelect = document.getElementById('vehicleSelect');

        vehicleSelect.innerHTML = '<option value="">-- Loading... --</option>';

        if (!customerId) {
            vehicleSelect.innerHTML = '<option value="">-- Pilih Kendaraan --</option>';
            vehicleSelect.disabled = true;
            
            return;
        }

        const response = await fetch(`/api/vehicles?customer_id=${customerId}`);
        console.log(response);
        const vehicles = await response.json();

        vehicleSelect.innerHTML = '<option value="">-- Pilih Kendaraan --</option>';
        
        vehicles.forEach(vehicle => {
            vehicleSelect.innerHTML += `<option value="${vehicle.id}">${vehicle.brand} ${vehicle.model} (${vehicle.color})</option>`;
        });
        
        vehicleSelect.disabled = false;
    });

    function addItemRow() {
        const tbody = document.querySelector('#cartTable tbody');
        const rowId = Date.now();

        let itemOptions = `<optgroup label="Sparepart">`;
        ITEMS_DATA.forEach(item => itemOptions += `<option value="part|${item.id}|${item.sell_price}">${item.name} (Stok: ${item.stock})</option>`);
        itemOptions += `</optgroup><optgroup label="Jasa">`;
        SERVICES_DATA.forEach(service => itemOptions += `<option value="service|${service.id}|${service.price}">${service.name}</option>`);
        itemOptions += `</optgroup>`;

        let mechanicOptions = '';
        MECHANICS_DATA.forEach(mechanic => mechanicOptions += `<option value="${mechanic.id}">${mechanic.name}</option>`);

        const html = `
            <tr id="row-${rowId}">
                <td>
                    <select class="form-select item-select" onchange="updatePrice(${rowId})">
                        <option value="">-- Pilih --</option>
                        ${itemOptions}
                    </select>
                </td>
                <td>
                    <select class="form-select mechanic-select">${mechanicOptions}</select>
                </td>
                <td>
                    <input type="number" class="form-control qty-input" value="1" min="1" onchange="calculateTotal()">
                </td>
                <td class="price-display">Rp0</td>
                <td>
                    <button class="btn btn-danger btn-sm" onclick="removeRow(${rowId})">X</button>
                </td>
            </tr>
        `;

        tbody.insertAdjacentHTML('beforeend', html);
    }

    function updatePrice(rowId) {
        const row = document.getElementById(`row-${rowId}`);
        const val = row.querySelector('.item-select').value;
        const display = row.querySelector('.price-display');

        if (val) {
            const [type, id, price] = val.split('|');
            display.innerText = 'Rp' + parseInt(price).toLocaleString();
            display.dataset.price = price;
            } else {
            display.innerText = 'Rp0';
            display.dataset.price = 0;
        }

        calculateTotal();
    }

    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('#cartTable tbody tr').forEach(row => {
            const price = parseInt(row.querySelector('.price-display').dataset.price || 0);
            const qty = parseInt(row.querySelector('.qty-input').value || 1);
            total += (price * qty);
        });
        document.getElementById('grandTotal').innerText = 'Rp' + total.toLocaleString();
        return total;
    }

    function removeRow(rowId) {
        document.getElementById(`row-${rowId}`).remove();
        calculateTotal();
    }

    async function processCheckout() {
        const customerId = document.getElementById('customerSelect').value;
        const vehicleId = document.getElementById('vehicleSelect').value;
        
        if (!customerId || !vehicleId) {
            alert("Harap pilih Pelanggan dan Kendaraan!");
            return;
        }

        const items = [];
        document.querySelectorAll('#cartTable tbody tr').forEach(row => {
            const val = row.querySelector('.item-select').value;
            if (val) {
                const [type, id, price] = val.split('|');
                const qty = row.querySelector('.qty-input').value;
                const mechId = row.querySelector('.mechanic-select').value;
                
                items.push({
                    type: type,
                    id: id,
                    qty: qty,
                    price: price,
                    subtotal: price * qty,
                    mechanic_id: mechId
                });
            }
        });

        if (items.length === 0) {
            alert("Keranjang belanja masih kosong!");
            return;
        }

        const payload = {
            csrf_token: CSRF_TOKEN,
            customer_id: customerId,
            vehicle_id: vehicleId,
            grand_total: calculateTotal(),
            items: items
        };

        if(confirm("Simpan Transaksi?")) {
            try {
                const res = await fetch('/transactions/store', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                
                const result = await res.json();
                
                if (result.status === 'success') {
                    alert("Transaksi Berhasil! No Invoice: " + result.invoice);
                    window.location.href = '/transactions';
                } else {
                    alert("Gagal: " + result.message);
                }
            } catch (err) {
                alert("Terjadi kesalahan sistem.");
                console.error(err);
            }
        }
    }
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
