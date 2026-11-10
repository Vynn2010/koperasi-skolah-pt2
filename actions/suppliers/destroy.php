<?php
require_once __DIR__ . "/../../middleware/auth.php";
requireLogin();
require_once __DIR__ . "/../../config/database.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../pages/suppliers/index.php");
    exit;
}

$id = (int)($_POST['id'] ?? 0);

try {
    $stmt = $pdo->prepare("DELETE FROM suppliers WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() === 0) {
        $_SESSION['flash'] = ['type' => 'error', 'message' => "Data supplier tidak ditemukan."];
    } else {
        $_SESSION['flash'] = ['type' => 'success', 'message' => "Supplier berhasil dihapus."];
    }
} catch (Throwable $errors) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => "Supplier tidak dapat dihapus karena masih digunakan atau terjadi kesalahan."];
}

header("Location: ../../pages/suppliers/index.php");
exit;
?>