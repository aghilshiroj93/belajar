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
<div class="flex justify-between items-center">
    <h2 class="text-xl font-semibold">Produk</h2>
    <a href="product_form.php" class="bg-blue-500 text-white px-3 py-1 rounded">Tambah Produk</a>
</div>

<form method="get" class="mt-3 flex gap-2">
    <input name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Cari nama..." class="border p-2 rounded" />
    <select name="sort" class="border p-2 rounded">
        <option value="">Sort</option>
        <option value="harga" <?= $sort == 'harga' ? 'selected' : '' ?>>Harga</option>
        <option value="stok" <?= $sort == 'stok' ? 'selected' : '' ?>>Stok</option>
    </select>
    <button class="bg-gray-800 text-white px-3 py-1 rounded">Filter</button>
</form>

<table class="w-full mt-4 bg-white rounded shadow">
    <thead class="border-b">
        <tr>
            <th class="p-2">Gambar</th>
            <th class="p-2">Nama</th>
            <th class="p-2">Harga</th>
            <th class="p-2">Stok</th>
            <th class="p-2">Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $p): ?>
            <tr class="border-b">
                <td class="p-2"><img src="<?= $p['gambar'] ? htmlspecialchars($p['gambar']) : 'https://via.placeholder.com/80' ?>" class="w-20 h-20 object-cover" /></td>
                <td class="p-2"><?= htmlspecialchars($p['nama']) ?></td>
                <td class="p-2">Rp <?= number_format($p['harga'], 2, ',', '.') ?></td>
                <td class="p-2"><?= $p['stok'] ?></td>
                <td class="p-2">
                    <a href="product_form.php?id=<?= $p['id'] ?>" class="text-blue-600 mr-2">Edit</a>
                    <a href="delete_product.php?id=<?= $p['id'] ?>" class="text-red-600" onclick="return confirm('Hapus produk?')">Hapus</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="mt-4">
    <?php $pages = ceil($totalRows / $perPage);
    for ($i = 1; $i <= $pages; $i++): ?>
        <a href="?page=<?= $i ?>&q=<?= urlencode($search) ?>&sort=<?= urlencode($sort) ?>" class="px-2 <?= $i == $page ? 'font-bold' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>
</div>

<?php require 'inc/footer.php'; ?>