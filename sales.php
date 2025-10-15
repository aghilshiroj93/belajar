<?php
require 'inc/db.php';
// fetch products for dropdown
$products = $pdo->query('SELECT id,nama,harga,stok FROM produk ORDER BY nama')->fetchAll();
require 'inc/header.php';
?>
<h2 class="text-xl mb-4">Transaksi Penjualan</h2>
<form id="salesForm" method="post" action="save_sale.php" class="bg-white p-4 rounded shadow">
    <table id="cart" class="w-full">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Total</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr class="cart-row">
                <td>
                    <select name="produk_id[]" class="product-select border p-2">
                        <option value="">-- pilih --</option>
                        <?php foreach ($products as $p): ?>
                            <option value="<?= $p['id'] ?>" data-harga="<?= $p['harga'] ?>" data-stok="<?= $p['stok'] ?>"><?= htmlspecialchars($p['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td><input name="harga[]" class="harga border p-2" readonly></td>
                <td><input name="qty[]" type="number" class="qty border p-2" value="1" min="1"></td>
                <td><input name="total_item[]" class="total_item border p-2" readonly></td>
                <td><button type="button" class="remove-row text-red-600">Hapus</button></td>
            </tr>
        </tbody>
    </table>
    <div class="mt-3">
        <button id="addRow" type="button" class="bg-green-500 text-white px-3 py-1 rounded">Tambah Baris</button>
    </div>

    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-2">
        <div>
            <label>Subtotal <input id="subtotal" name="subtotal" readonly class="border p-2 w-full"></label>
            <label class="block mt-2">Pajak (%) <input id="pajakPercent" name="pajak_percent" value="0" type="number" class="border p-2 w-full"></label>
            <label class="block mt-2">Grand Total <input id="grand_total" name="grand_total" readonly class="border p-2 w-full"></label>
        </div>
        <div>
            <label>Jumlah Bayar <input id="jumlah_bayar" name="jumlah_bayar" type="number" step="0.01" class="border p-2 w-full"></label>
            <label class="block mt-2">Kembalian <input id="kembalian" name="kembalian" readonly class="border p-2 w-full"></label>
        </div>
    </div>

    <div class="mt-4"><button class="bg-blue-600 text-white px-3 py-1 rounded">Simpan Transaksi</button></div>
</form>
<?php require 'inc/footer.php'; ?>