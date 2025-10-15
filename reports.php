<?php
require 'inc/db.php';
// simple reports page - list transaksi
$stmt = $pdo->query('SELECT id, kode, tanggal, subtotal, pajak, grand_total, jumlah_bayar, kembalian FROM transaksi ORDER BY tanggal DESC');
$rows = $stmt->fetchAll();
require 'inc/header.php';
?>

<div class="bg-white p-4 rounded shadow">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold">Rekapitulasi Transaksi</h2>
        <!-- <a href="sales.php" class="text-sm text-amber-600 hover:underline">+ Buat Transaksi Baru</a> -->
    </div>

    <?php if (empty($rows)): ?>
        <div class="text-center text-gray-500">Belum ada transaksi.</div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="bg-gray-50 text-left">
                        <th class="p-2">Kode</th>
                        <th class="p-2">Tanggal</th>
                        <th class="p-2">Subtotal</th>
                        <th class="p-2">Pajak</th>
                        <th class="p-2">Grand Total</th>
                        <th class="p-2">Bayar</th>
                        <th class="p-2">Kembalian</th>
                        <th class="p-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $r): ?>
                        <tr class="border-t">
                            <td class="p-2 font-mono text-sm"><?= htmlspecialchars($r['kode']) ?></td>
                            <td class="p-2 text-sm"><?= htmlspecialchars($r['tanggal']) ?></td>
                            <td class="p-2 text-sm">Rp <?= number_format($r['subtotal'], 2, ',', '.') ?></td>
                            <td class="p-2 text-sm">Rp <?= number_format($r['pajak'], 2, ',', '.') ?></td>
                            <td class="p-2 text-sm font-bold">Rp <?= number_format($r['grand_total'], 2, ',', '.') ?></td>
                            <td class="p-2 text-sm">Rp <?= number_format($r['jumlah_bayar'], 2, ',', '.') ?></td>
                            <td class="p-2 text-sm">Rp <?= number_format($r['kembalian'], 2, ',', '.') ?></td>
                            <td class="p-2 text-sm">
                                <a href="receipt.php?id=<?= $r['id'] ?>" class="text-blue-600 hover:underline">Lihat</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require 'inc/footer.php'; ?>