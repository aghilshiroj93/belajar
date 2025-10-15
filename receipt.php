<?php
require 'inc/db.php';
$id = $_GET['id'] ?? null;
if (!$id) die('ID transaksi diperlukan');
$stmt = $pdo->prepare('SELECT * FROM transaksi WHERE id=?');
$stmt->execute([$id]);
$trx = $stmt->fetch();
$stmt = $pdo->prepare('SELECT td.*, p.nama FROM transaksi_detail td JOIN produk p ON p.id=td.produk_id WHERE td.transaksi_id=?');
$stmt->execute([$id]);
$items = $stmt->fetchAll();
require 'inc/header.php';
?>
<div class="bg-white p-4 rounded shadow">
    <h2 class="text-lg font-semibold">Struk - <?= htmlspecialchars($trx['kode']) ?></h2>
    <div>Tanggal: <?= $trx['tanggal'] ?></div>
    <table class="w-full mt-2">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $it): ?>
                <tr>
                    <td><?= htmlspecialchars($it['nama']) ?></td>
                    <td>Rp <?= number_format($it['harga'], 2, ',', '.') ?></td>
                    <td><?= $it['qty'] ?></td>
                    <td>Rp <?= number_format($it['total'], 2, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="mt-4">
        <div>Subtotal: Rp <?= number_format($trx['subtotal'], 2, ',', '.') ?></div>
        <div>Pajak: Rp <?= number_format($trx['pajak'], 2, ',', '.') ?></div>
        <div class="font-bold">Grand Total: Rp <?= number_format($trx['grand_total'], 2, ',', '.') ?></div>
        <div>Bayar: Rp <?= number_format($trx['jumlah_bayar'], 2, ',', '.') ?></div>
        <div>Kembalian: Rp <?= number_format($trx['kembalian'], 2, ',', '.') ?></div>
    </div>
    <div class="mt-4"><button onclick="window.print()" class="bg-blue-600 text-white px-3 py-1 rounded">Cetak</button></div>
</div>

<?php require 'inc/footer.php'; ?>