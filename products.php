<?php
require 'inc/db.php';
$search = $_GET['q'] ?? '';
$sort = $_GET['sort'] ?? '';
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;
$offset = ($page - 1) * $perPage;
$where = '';
$params = [];
if ($search) {
    $where = ' WHERE nama LIKE ? ';
    $params[] = "%$search%";
}
$allowedSort = ['harga', 'stok'];
$order = '';
if (in_array($sort, $allowedSort)) $order = " ORDER BY $sort ASC ";
$total = $pdo->prepare("SELECT COUNT(*) FROM produk $where");
$total->execute($params);
$totalRows = $total->fetchColumn();
$stmt = $pdo->prepare("SELECT * FROM produk $where $order LIMIT $perPage OFFSET $offset");
$stmt->execute($params);
$products = $stmt->fetchAll();
require 'inc/header.php';
?>

<!-- Header Section -->
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Produk</h1>
            <p class="text-gray-600 mt-1">Kelola produk dan inventori toko Anda</p>
        </div>
        <a href="product_form.php"
            class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-4 py-2.5 rounded-xl font-medium transition-all duration-200 shadow-lg hover:shadow-xl">
            <i class="fas fa-plus-circle"></i>
            Tambah Produk
        </a>
    </div>
</div>

<!-- Search and Filter Card -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
    <form method="get" class="flex flex-col sm:flex-row gap-4 items-end">
        <div class="flex-1 w-full">
            <label class="block text-sm font-medium text-gray-700 mb-2">Cari Produk</label>
            <div class="relative">
                <input name="q"
                    value="<?= htmlspecialchars($search) ?>"
                    placeholder="Ketik nama produk..."
                    class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all duration-200" />
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>

        <div class="w-full sm:w-48">
            <label class="block text-sm font-medium text-gray-700 mb-2">Urutkan</label>
            <select name="sort" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-all duration-200">
                <option value="">Pilih pengurutan</option>
                <option value="harga" <?= $sort == 'harga' ? 'selected' : '' ?>>Harga Terendah</option>
                <option value="stok" <?= $sort == 'stok' ? 'selected' : '' ?>>Stok Terendah</option>
            </select>
        </div>

        <div class="w-full sm:w-auto">
            <button type="submit"
                class="w-full sm:w-auto bg-gradient-to-r from-gray-700 to-gray-800 hover:from-gray-800 hover:to-gray-900 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 shadow-lg hover:shadow-xl flex items-center gap-2">
                <i class="fas fa-filter"></i>
                Terapkan
            </button>
        </div>
    </form>
</div>

<!-- Products Table Card -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <!-- Table Header -->
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
        <div class="flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Daftar Produk</h3>
            <span class="text-sm text-gray-500">
                Total: <?= number_format($totalRows) ?> produk
            </span>
        </div>
    </div>

    <!-- Products Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100">
                    <th class="py-4 px-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Produk</th>
                    <th class="py-4 px-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga</th>
                    <th class="py-4 px-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Stok</th>
                    <th class="py-4 px-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="py-4 px-6 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (count($products) > 0): ?>
                    <?php foreach ($products as $p):
                        $stockStatus = '';
                        $stockColor = '';
                        if ($p['stok'] == 0) {
                            $stockStatus = 'Habis';
                            $stockColor = 'bg-red-100 text-red-800';
                        } elseif ($p['stok'] < 5) {
                            $stockStatus = 'Menipis';
                            $stockColor = 'bg-orange-100 text-orange-800';
                        } else {
                            $stockStatus = 'Tersedia';
                            $stockColor = 'bg-green-100 text-green-800';
                        }
                    ?>
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <!-- Product Info -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-4">
                                    <div class="flex-shrink-0">
                                        <img src="<?= $p['gambar'] ? htmlspecialchars($p['gambar']) : 'https://via.placeholder.com/80x80?text=No+Image' ?>"
                                            alt="<?= htmlspecialchars($p['nama']) ?>"
                                            class="w-16 h-16 rounded-xl object-cover border border-gray-200 shadow-sm" />
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900"><?= htmlspecialchars($p['nama']) ?></div>
                                        <?php if (!empty($p['deskripsi'])): ?>
                                            <div class="text-sm text-gray-500 mt-1 line-clamp-2 max-w-xs">
                                                <?= htmlspecialchars($p['deskripsi']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>

                            <!-- Price -->
                            <td class="py-4 px-6">
                                <div class="text-lg font-bold text-blue-600">
                                    Rp <?= number_format($p['harga'], 0, ',', '.') ?>
                                </div>
                            </td>

                            <!-- Stock -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-gray-900"><?= $p['stok'] ?></span>
                                    <span class="text-sm text-gray-500">unit</span>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium <?= $stockColor ?>">
                                    <?= $stockStatus ?>
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <a href="product_form.php?id=<?= $p['id'] ?>"
                                        class="inline-flex items-center gap-1.5 text-blue-600 hover:text-blue-800 font-medium transition-colors duration-200 group"
                                        title="Edit Produk">
                                        <i class="fas fa-edit text-sm group-hover:scale-110 transition-transform"></i>
                                        <span class="hidden sm:inline">Edit</span>
                                    </a>

                                    <a href="delete_product.php?id=<?= $p['id'] ?>"
                                        class="inline-flex items-center gap-1.5 text-red-600 hover:text-red-800 font-medium transition-colors duration-200 group"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus produk <?= addslashes($p['nama']) ?>?')"
                                        title="Hapus Produk">
                                        <i class="fas fa-trash text-sm group-hover:scale-110 transition-transform"></i>
                                        <span class="hidden sm:inline">Hapus</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="py-12 px-6 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <i class="fas fa-box-open text-5xl mb-4"></i>
                                <p class="text-lg font-medium text-gray-500 mb-2">Tidak ada produk ditemukan</p>
                                <p class="text-sm text-gray-400 mb-4">
                                    <?= $search ? 'Coba ubah kata kunci pencarian' : 'Mulai dengan menambahkan produk pertama Anda' ?>
                                </p>
                                <?php if (!$search): ?>
                                    <a href="product_form.php"
                                        class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                                        <i class="fas fa-plus-circle"></i>
                                        Tambah Produk Pertama
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if (count($products) > 0): ?>
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="text-sm text-gray-500">
                    Menampilkan <?= min(($page - 1) * $perPage + 1, $totalRows) ?> -
                    <?= min($page * $perPage, $totalRows) ?> dari <?= number_format($totalRows) ?> produk
                </div>

                <div class="flex items-center gap-1">
                    <?php
                    $pages = ceil($totalRows / $perPage);
                    $maxVisiblePages = 5;
                    $startPage = max(1, $page - floor($maxVisiblePages / 2));
                    $endPage = min($pages, $startPage + $maxVisiblePages - 1);
                    $startPage = max(1, $endPage - $maxVisiblePages + 1);

                    // Previous button
                    if ($page > 1): ?>
                        <a href="?page=<?= $page - 1 ?>&q=<?= urlencode($search) ?>&sort=<?= urlencode($sort) ?>"
                            class="px-3 py-2 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50 transition-colors duration-200">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    <?php endif;

                    // Page numbers
                    for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <a href="?page=<?= $i ?>&q=<?= urlencode($search) ?>&sort=<?= urlencode($sort) ?>"
                            class="px-3 py-2 rounded-lg border transition-all duration-200 <?= $i == $page ? 'bg-blue-500 text-white border-blue-500 shadow-sm' : 'border-gray-300 text-gray-600 hover:bg-gray-50' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor;

                    // Next button
                    if ($page < $pages): ?>
                        <a href="?page=<?= $page + 1 ?>&q=<?= urlencode($search) ?>&sort=<?= urlencode($sort) ?>"
                            class="px-3 py-2 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50 transition-colors duration-200">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require 'inc/footer.php'; ?>