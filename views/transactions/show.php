<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk <?= $data['header']['invoice_number']; ?></title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 14px;
            margin: 0;
            padding: 20px;
            background: #eee;
        }
        .invoice-box {
            max-width: 300px;
            margin: auto;
            padding: 15px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .line { border-bottom: 1px dashed #000; margin: 10px 0; }
        .bold { font-weight: bold; }
        
        table { width: 100%; line-height: inherit; text-align: left; }
        table td { padding: 2px 0; vertical-align: top; }
        
        @media print {
            body { background: #fff; }
            .invoice-box { box-shadow: none; border: 0; width: 100%; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
<div class="invoice-box">
    <div class="text-center">
        <h2 style="margin:0;">MotoFix</h2>
        <p style="margin:5px 0;">Jl. Teknologi No. 123<br>Telp: +62 812 3456 7890</p>
    </div>
    <div class="line"></div>
    <table>
        <tr>
            <td>No. Invoice</td>
            <td class="text-right"><?= $data['header']['invoice_number']; ?></td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td class="text-right"><?= date('d/m/y H:i', strtotime($data['header']['transaction_date'])); ?></td>
        </tr>
        <tr>
            <td>Kasir</td>
            <td class="text-right"><?= $data['header']['cashier_name']; ?></td>
        </tr>
        <tr>
            <td>Pelanggan</td>
            <td class="text-right"><?= $data['header']['customer_name']; ?></td>
        </tr>
        <tr>
            <td>Kendaraan</td>
            <td class="text-right"><?= $data['header']['brand']; ?> <?= $data['header']['model']; ?></td>
        </tr>
    </table>
    <div class="line"></div>
    <table>
        <?php foreach ($data['items'] as $item): ?>
        <tr>
            <td colspan="2"><?= $item['item_name']; ?> (<?= $item['mechanic_name']; ?>)</td>
        </tr>
        <tr>
            <td><?= $item['qty']; ?> x <?= formatNumber($item['price_at_transaction']); ?></td>
            <td class="text-right"><?= formatNumber($item['subtotal']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <div class="line"></div>
    <table>
        <tr class="bold" style="font-size: 16px;">
            <td>TOTAL</td>
            <td class="text-right">Rp<?= formatNumber($data['header']['total_amount']); ?></td>
        </tr>
    </table>
    <div class="text-center" style="margin-top: 20px;">
        <p>Terima kasih atas kunjungan Anda!</p>
        <p style="font-size: 10px;">Barang yang dibeli tidak dapat ditukar.</p>
    </div>

    <div class="text-center no-print" style="margin-top: 20px; border-top: 1px solid #ccc; padding-top: 10px;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">Cetak</button>
        <br />
        <a href="/transactions">Kembali</a>
    </div>
</div>
</body>
</html>