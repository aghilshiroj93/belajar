<?php
require 'inc/db.php';
$stmt = $pdo->query('SELECT id, kode, tanggal, subtotal, pajak, grand_total, jumlah_bayar, kembalian FROM transaksi ORDER BY tanggal DESC');
$rows = $stmt->fetchAll();
require 'inc/header.php';
?>

<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="text-center">
        <h1 class="text-2xl font-bold text-gray-800">Rekap Transaksi</h1>
        <p class="text-gray-600 mt-1">Daftar lengkap transaksi penjualan</p>
    </div>

    <!-- Transactions Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Card Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold text-gray-800">
                    <i class="fas fa-history mr-2 text-blue-500"></i>
                    Riwayat Transaksi
                </h3>
                <span class="text-sm text-gray-500">
                    Total: <?= count($rows) ?> transaksi
                </span>
            </div>
        </div>

        <?php if (empty($rows)): ?>
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-receipt text-gray-400 text-2xl"></i>
                </div>
                <p class="text-gray-500 text-lg font-medium">Belum ada transaksi</p>
                <p class="text-gray-400 text-sm mt-1">Transaksi yang dilakukan akan muncul di sini</p>
            </div>
        <?php else: ?>
            <!-- Transactions Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 text-left text-sm text-gray-600 font-medium">
                            <th class="py-3 px-4">Kode</th>
                            <th class="py-3 px-4">Tanggal</th>
                            <th class="py-3 px-4 text-right">Total</th>
                            <th class="py-3 px-4 text-right">Bayar</th>
                            <th class="py-3 px-4 text-right">Kembali</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php foreach ($rows as $r): ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-3 px-4">
                                    <div class="font-mono text-sm font-semibold text-blue-600">
                                        <?= htmlspecialchars($r['kode']) ?>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="text-sm text-gray-700">
                                        <?= date('d M Y', strtotime($r['tanggal'])) ?>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        <?= date('H:i', strtotime($r['tanggal'])) ?>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="font-semibold text-gray-800">
                                        Rp <?= number_format($r['grand_total'], 0, ',', '.') ?>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Sub: Rp <?= number_format($r['subtotal'], 0, ',', '.') ?>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="text-sm text-green-600 font-medium">
                                        Rp <?= number_format($r['jumlah_bayar'], 0, ',', '.') ?>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <div class="text-sm <?= $r['kembalian'] > 0 ? 'text-blue-600' : 'text-gray-600' ?> font-medium">
                                        Rp <?= number_format($r['kembalian'], 0, ',', '.') ?>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <a href="receipt.php?id=<?= $r['id'] ?>"
                                        class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 font-medium text-sm transition-colors">
                                        <i class="fas fa-eye"></i>
                                        Lihat
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Quick Stats -->
    <?php if (!empty($rows)): ?>
        <?php
        $totalPenjualan = array_sum(array_column($rows, 'grand_total'));
        $totalPajak = array_sum(array_column($rows, 'pajak'));
        $rataRata = $totalPenjualan / count($rows);
        ?>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-4 rounded-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm">Total Penjualan</p>
                        <p class="text-xl font-bold mt-1">Rp <?= number_format($totalPenjualan, 0, ',', '.') ?></p>
                    </div>
                    <i class="fas fa-chart-line text-xl opacity-80"></i>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-4 rounded-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm">Rata-rata/Transaksi</p>
                        <p class="text-xl font-bold mt-1">Rp <?= number_format($rataRata, 0, ',', '.') ?></p>
                    </div>
                    <i class="fas fa-calculator text-xl opacity-80"></i>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-4 rounded-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm">Total Pajak</p>
                        <p class="text-xl font-bold mt-1">Rp <?= number_format($totalPajak, 0, ',', '.') ?></p>
                    </div>
                    <i class="fas fa-percentage text-xl opacity-80"></i>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require 'inc/footer.php'; ?>