<?php
require 'inc/db.php';

// Fetch dashboard stats
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

$stmt = $pdo->query('SELECT * FROM produk ORDER BY created_at DESC LIMIT 12');
$products = $stmt->fetchAll();

require 'inc/header.php';
?>

<!-- Dashboard Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-xl shadow-lg">
        <div class="flex items-center">
            <div class="p-3 rounded-lg bg-blue-400 bg-opacity-30 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-medium opacity-80">Total Produk</h3>
                <div class="text-2xl font-bold mt-1"><?= $totalProduk ?></div>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-xl shadow-lg">
        <div class="flex items-center">
            <div class="p-3 rounded-lg bg-green-400 bg-opacity-30 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-medium opacity-80">Transaksi Hari Ini</h3>
                <div class="text-2xl font-bold mt-1"><?= $totalTransToday ?></div>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-xl shadow-lg">
        <div class="flex items-center">
            <div class="p-3 rounded-lg bg-purple-400 bg-opacity-30 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-medium opacity-80">Omzet Hari Ini</h3>
                <div class="text-2xl font-bold mt-1">Rp <?= number_format($omzetToday, 0, ',', '.') ?></div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Low Stock Warning -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-4">
            <h3 class="font-semibold text-white flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
                Stok Menipis (< 10)
                    </h3>
        </div>
        <div class="p-4">
            <?php if (count($lowStock) > 0): ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left text-sm text-gray-500 border-b">
                                <th class="pb-2 font-medium">Nama Produk</th>
                                <th class="pb-2 font-medium">Stok Tersisa</th>
                                <th class="pb-2 font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            <?php foreach ($lowStock as $p):
                                $stockLevel = '';
                                $stockColor = '';
                                if ($p['stok'] <= 2) {
                                    $stockLevel = 'Sangat Rendah';
                                    $stockColor = 'bg-red-100 text-red-800';
                                } elseif ($p['stok'] <= 5) {
                                    $stockLevel = 'Rendah';
                                    $stockColor = 'bg-orange-100 text-orange-800';
                                } else {
                                    $stockLevel = 'Menipis';
                                    $stockColor = 'bg-yellow-100 text-yellow-800';
                                }
                            ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 text-sm"><?= htmlspecialchars($p['nama']) ?></td>
                                    <td class="py-3 text-sm font-medium"><?= $p['stok'] ?></td>
                                    <td class="py-3">
                                        <span class="px-2 py-1 text-xs rounded-full <?= $stockColor ?>"><?= $stockLevel ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-8 text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="mt-2">Semua stok dalam kondisi baik</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Recent Transactions (Placeholder) -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 px-6 py-4">
            <h3 class="font-semibold text-white flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Transaksi Terbaru
            </h3>
        </div>
        <div class="p-4">
            <div class="text-center py-8 text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="mt-2">Fitur transaksi terbaru akan ditampilkan di sini</p>
            </div>
        </div>
    </div>
</div>

<!-- Latest Products -->
<div class="mt-8 bg-white rounded-xl shadow-md overflow-hidden">
    <div class="bg-gradient-to-r from-amber-500 to-amber-600 px-6 py-4">
        <h2 class="font-semibold text-white flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
            </svg>
            Produk Terbaru
        </h2>
    </div>
    <div class="p-4">
        <?php if (count($products) > 0): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <?php foreach ($products as $p):
                    $img = $p['gambar'] && file_exists($p['gambar']) ? $p['gambar'] : 'https://via.placeholder.com/400x300?text=No+Image';
                    $stockStatus = $p['stok'] > 10 ? 'Tersedia' : ($p['stok'] > 0 ? 'Terbatas' : 'Habis');
                    $stockColor = $p['stok'] > 10 ? 'bg-green-100 text-green-800' : ($p['stok'] > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                ?>
                    <div class="bg-white rounded-lg shadow border border-gray-100 overflow-hidden transition-transform duration-200 hover:shadow-md hover:-translate-y-1">
                        <div class="w-full h-44 bg-gray-100 overflow-hidden">
                            <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($p['nama']) ?>" class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                        </div>
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-semibold text-sm line-clamp-2 flex-1 mr-2"><?= htmlspecialchars($p['nama']) ?></h3>
                                <span class="px-2 py-1 text-xs rounded-full <?= $stockColor ?> whitespace-nowrap"><?= $stockStatus ?></span>
                            </div>
                            <div class="text-amber-600 font-bold text-lg mb-2">Rp <?= number_format($p['harga'], 0, ',', '.') ?></div>
                            <?php if (!empty($p['deskripsi'])): ?>
                                <p class="text-sm text-gray-600 line-clamp-2"><?= htmlspecialchars($p['deskripsi']) ?></p>
                            <?php endif; ?>
                            <div class="mt-3 flex justify-between items-center text-xs text-gray-500">
                                <span>Stok: <?= $p['stok'] ?></span>
                                <button class="text-blue-600 hover:text-blue-800 font-medium">Lihat Detail</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-8 text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m8-8V4a1 1 0 00-1-1h-2a1 1 0 00-1 1v1M9 7h6" />
                </svg>
                <p class="mt-2">Belum ada produk yang ditambahkan</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require 'inc/footer.php'; ?>