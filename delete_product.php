<?php
require 'inc/db.php';
$id = $_GET['id'] ?? null;
if ($id) {
    // delete gambar if exists
    $stmt = $pdo->prepare('SELECT gambar FROM produk WHERE id=?');
    $stmt->execute([$id]);
    $g = $stmt->fetchColumn();
    if ($g && file_exists($g)) unlink($g);
    $stmt = $pdo->prepare('DELETE FROM produk WHERE id=?');
    $stmt->execute([$id]);
}
header('Location: products.php');
