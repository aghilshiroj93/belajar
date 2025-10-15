<?php
require 'inc/db.php';
// basic validation and save
$produk_ids = $_POST['produk_id'] ?? [];
$hargas = $_POST['harga'] ?? [];
$qtys = $_POST['qty'] ?? [];
$totals = $_POST['total_item'] ?? [];
// Prefer raw numeric fields sent from the form
$subtotal = isset($_POST['subtotal_raw']) ? floatval($_POST['subtotal_raw']) : (isset($_POST['subtotal']) ? floatval($_POST['subtotal']) : 0);
$pajak_percent = isset($_POST['pajak_percent_raw']) ? floatval($_POST['pajak_percent_raw']) : (isset($_POST['pajak_percent']) ? floatval($_POST['pajak_percent']) : 0);
$grand_total = isset($_POST['grand_total_raw']) ? floatval($_POST['grand_total_raw']) : (isset($_POST['grand_total']) ? floatval($_POST['grand_total']) : 0);
$jumlah_bayar = isset($_POST['jumlah_bayar_raw']) ? floatval($_POST['jumlah_bayar_raw']) : (isset($_POST['jumlah_bayar']) ? floatval($_POST['jumlah_bayar']) : 0);
$kembalian = isset($_POST['kembalian_raw']) ? floatval($_POST['kembalian_raw']) : (isset($_POST['kembalian']) ? floatval($_POST['kembalian']) : 0);

// Fallback parsing from formatted strings if needed
if ($subtotal == 0 && isset($_POST['subtotal'])) {
    $subtotal = floatval(preg_replace('/[^0-9\.\-]/', '', $_POST['subtotal']));
}
if ($grand_total == 0 && isset($_POST['grand_total'])) {
    $grand_total = floatval(preg_replace('/[^0-9\.\-]/', '', $_POST['grand_total']));
}
if ($jumlah_bayar == 0 && isset($_POST['jumlah_bayar'])) {
    $jumlah_bayar = floatval(preg_replace('/[^0-9\.\-]/', '', $_POST['jumlah_bayar']));
}
if ($kembalian == 0 && isset($_POST['kembalian'])) {
    $kembalian = floatval(preg_replace('/[^0-9\.\-]/', '', $_POST['kembalian']));
}

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
    // use raw per-item totals if provided
    $totals_raw = $_POST['total_item_raw'] ?? [];
    for ($i = 0; $i < count($produk_ids); $i++) {
        $pid = $produk_ids[$i];
        $harga = isset($hargas[$i]) ? floatval(preg_replace('/[^0-9\.\-]/', '', $hargas[$i])) : 0;
        $qty = isset($qtys[$i]) ? intval($qtys[$i]) : 0;
        $total = isset($totals_raw[$i]) ? floatval($totals_raw[$i]) : (isset($totals[$i]) ? floatval(preg_replace('/[^0-9\.\-]/', '', $totals[$i])) : 0);
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
