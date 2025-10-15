<?php
require 'inc/db.php';
// basic validation and save
$produk_ids = $_POST['produk_id'] ?? [];
$hargas = $_POST['harga'] ?? [];
$qtys = $_POST['qty'] ?? [];
$totals = $_POST['total_item'] ?? [];
$subtotal = $_POST['subtotal'] ?? 0;
$pajak_percent = $_POST['pajak_percent'] ?? 0;
$grand_total = $_POST['grand_total'] ?? 0;
$jumlah_bayar = $_POST['jumlah_bayar'] ?? 0;
$kembalian = $_POST['kembalian'] ?? 0;

if (empty($produk_ids)) {
    die('Tidak ada item');
}

$pdo->beginTransaction();
try {
    // generate kode TRX-YYYYMMDD-####
    $date = date('Ymd');
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM transaksi WHERE DATE(tanggal)=CURDATE()");
    $stmt->execute();
    $count = $stmt->fetchColumn();
    $seq = str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    $kode = "TRX-{$date}-{$seq}";
    $pajak = ($pajak_percent / 100) * $subtotal;
    $stmt = $pdo->prepare('INSERT INTO transaksi(kode,tanggal,subtotal,pajak,grand_total,jumlah_bayar,kembalian) VALUES(?,NOW(),?,?,?,?,?)');
    $stmt->execute([$kode, $subtotal, $pajak, $grand_total, $jumlah_bayar, $kembalian]);
    $trans_id = $pdo->lastInsertId();
    // insert details and update stok
    for ($i = 0; $i < count($produk_ids); $i++) {
        $pid = $produk_ids[$i];
        $harga = $hargas[$i];
        $qty = $qtys[$i];
        $total = $totals[$i];
        $stmt = $pdo->prepare('INSERT INTO transaksi_detail(transaksi_id,produk_id,harga,qty,total) VALUES(?,?,?,?,?)');
        $stmt->execute([$trans_id, $pid, $harga, $qty, $total]);
        // decrement stok
        $stmt = $pdo->prepare('UPDATE produk SET stok=stok-? WHERE id=?');
        $stmt->execute([$qty, $pid]);
    }
    $pdo->commit();
    header('Location: receipt.php?id=' . $trans_id);
} catch (Exception $e) {
    $pdo->rollBack();
    die('Error: ' . $e->getMessage());
}
