<?php
require_once __DIR__ . "/../../middleware/auth.php";
requireLogin();
require_once __DIR__ . "/../../config/database.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../pages/products/index.php");
    exit;
}

$id = (int)($_POST['id'] ?? 0);

try {
    $pdo->beginTransaction();

    if ($id <= 0 || !lockProduct($id)) {
    throw new RuntimeException("Data tidak ditemukan.");
    }

    deleteProduct($id); 

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