<?php
require_once __DIR__ . "/../../middleware/auth.php";
requireLogin();
require_once __DIR__ . "/../../config/database.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../pages/employees/index.php");
    exit;
}

$id = (int)($_POST['id'] ?? 0);

try {
    $stmt = $pdo->prepare("DELETE FROM employees WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() === 0) {
        $_SESSION['flash'] = ['type' => 'error', 'message' => "Data pegawai tidak ditemukan."];
    } else {
        $_SESSION['flash'] = ['type' => 'success', 'message' => "Pegawai berhasil dihapus."];
    }
} catch (Throwable $errors) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => "Pegawai tidak dapat dihapus karena masih digunakan atau terjadi kesalahan."];
}

header("Location: ../../pages/employees/index.php");
exit;
?>