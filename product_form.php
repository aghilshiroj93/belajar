<?php
require 'inc/db.php';
$id = $_GET['id'] ?? null;
$product = null;
if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM produk WHERE id=?');
    $stmt->execute([$id]);
    $product = $stmt->fetch();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $deskripsi = $_POST['deskripsi'];
    $gambarPath = $product['gambar'] ?? null;
    if (!empty($_FILES['gambar']['name'])) {
        $f = $_FILES['gambar'];
        $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array(strtolower($ext), $allowed)) {
            $err = 'Tipe file tidak diizinkan';
        } elseif ($f['size'] > 1024 * 1024) {
            $err = 'Ukuran file maksimal 1MB';
        } else {
            if (!is_dir('uploads')) mkdir('uploads', 0777, true);
            $target = 'uploads/' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
            move_uploaded_file($f['tmp_name'], $target);
            $gambarPath = $target;
        }
    }
    if (!isset($err)) {
        if ($id) {
            $stmt = $pdo->prepare('UPDATE produk SET nama=?, harga=?, stok=?, gambar=?, deskripsi=?, updated_at=NOW() WHERE id=?');
            $stmt->execute([$nama, $harga, $stok, $gambarPath, $deskripsi, $id]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO produk(nama,harga,stok,gambar,deskripsi) VALUES(?,?,?,?,?)');
            $stmt->execute([$nama, $harga, $stok, $gambarPath, $deskripsi]);
        }
        header('Location: products.php');
        exit;
    }
}
require 'inc/header.php';
?>
<h2 class="text-xl mb-4"><?= $product ? 'Edit Produk' : 'Tambah Produk' ?></h2>
<?php if (!empty($err)): ?><div class="bg-red-100 p-2 text-red-700"><?= $err ?></div><?php endif; ?>
<form method="post" enctype="multipart/form-data" class="bg-white p-4 rounded shadow">
    <label class="block">Nama <input name="nama" value="<?= htmlspecialchars($product['nama'] ?? '') ?>" class="border p-2 w-full" required></label>
    <label class="block mt-2">Harga <input name="harga" type="number" step="0.01" value="<?= htmlspecialchars($product['harga'] ?? '') ?>" class="border p-2 w-full" required></label>
    <label class="block mt-2">Stok <input name="stok" type="number" value="<?= htmlspecialchars($product['stok'] ?? '') ?>" class="border p-2 w-full" required></label>
    <label class="block mt-2">Gambar <input name="gambar" type="file" accept="image/*" class="border p-2 w-full"></label>
    <?php if (!empty($product['gambar'])): ?><img src="<?= htmlspecialchars($product['gambar']) ?>" class="w-32 mt-2" /><?php endif; ?>
    <label class="block mt-2">Deskripsi <textarea name="deskripsi" class="border p-2 w-full"><?= htmlspecialchars($product['deskripsi'] ?? '') ?></textarea></label>
    <div class="mt-3"><button class="bg-blue-600 text-white px-3 py-1 rounded">Simpan</button></div>
</form>
<?php require 'inc/footer.php'; ?>