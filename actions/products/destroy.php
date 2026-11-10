<?php
session_start();
require_once __DIR__ . "/../../config/database.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../pages/products/index.php");
    exit;
}

$id = (int)($_POST['id'] ?? 0);

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("SELECT product_id FROM products WHERE product_id = ? FOR UPDATE");
    $stmt->execute(['id']);
    if ($id <= 0 || $stmt->fetchColumn()){
        throw new RuntimeException("Data tidak ditemukan.");
    }

    $stmt = $pdo->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->execute([$id]);

    $pdo->commit();
    $_SESSION['flash'] = ['type' => 'success','message' => "Produk berhasil dihapus."];
} catch (Throwable $errors) {
    if ($pdo -> inTransaction()) {
        $pdo->rollBack();
    }
    $_SESSION['flash'] = ['type' => 'error' , 'message' => "Gagal menghapus data. Perubahan dibatalkan. "];
}

header("Location: ../../pages/products/index.php");
exit;
?>