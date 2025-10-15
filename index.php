<?php
require 'inc/db.php';
// fetch dashboard stats
$totalProduk = $pdo->query('SELECT COUNT(*) FROM produk')->fetchColumn();
$today = date('Y-m-d');
$stmt = $pdo->prepare('SELECT COUNT(*) FROM transaksi WHERE DATE(tanggal)=?');
$stmt->execute([$today]);
$totalTransToday = $stmt->fetchColumn();
$stmt = $pdo->prepare('SELECT IFNULL(SUM(grand_total),0) FROM transaksi WHERE DATE(tanggal)=?');
$stmt->execute([$today]);
$omzetToday = $stmt->fetchColumn();
$stmt = $pdo->query('SELECT * FROM produk WHERE stok < 10 ORDER BY stok ASC LIMIT 5');
$lowStock = $stmt->fetchAll();
require 'inc/header.php';
?>
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="bg-white p-4 rounded shadow">
        <h3 class="text-sm text-gray-500">Total Produk</h3>
        <div class="text-2xl font-bold"><?= $totalProduk ?></div>
    </div>
    <div class="bg-white p-4 rounded shadow">
        <h3 class="text-sm text-gray-500">Transaksi Hari Ini</h3>
        <div class="text-2xl font-bold"><?= $totalTransToday ?></div>
    </div>
    <div class="bg-white p-4 rounded shadow">
        <h3 class="text-sm text-gray-500">Omzet Hari Ini</h3>
        <div class="text-2xl font-bold">Rp <?= number_format($omzetToday, 2, ',', '.') ?></div>
    </div>
</div>

<div class="mt-6 bg-white p-4 rounded shadow">
    <h3 class="font-semibold">Stok Menipis (&lt;10)</h3>
    <table class="w-full mt-2 table-auto">
        <thead>
            <tr class="text-left">
                <th>Nama</th>
                <th>Stok</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($lowStock as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['nama']) ?></td>
                    <td><?= $p['stok'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require 'inc/footer.php'; ?>