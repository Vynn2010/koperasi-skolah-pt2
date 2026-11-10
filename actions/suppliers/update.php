<?php
require_once __DIR__ . "/../../middleware/auth.php";
requireLogin();
require_once __DIR__ . "/../../config/database.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../pages/suppliers/index.php");
    exit;
}

$name             = trim($_POST['name'] ?? '');
$email            = trim($_POST['email'] ?? '');
$phone            = trim($_POST['phone'] ?? '');
$address          = trim($_POST['address'] ?? '');
$product_types    = trim($_POST['product_types'] ?? '');
$partnership_date = trim($_POST['partnership_date'] ?? '');

$errors = [];

$id = (int)($_POST['id'] ?? 0);

$stmt = $pdo->prepare("SELECT 1 FROM suppliers WHERE id = ?");
$stmt->execute([$id]);
if ($id <= 0 || !$stmt->fetchColumn()) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => "Data supplier tidak ditemukan."];
    header("Location: ../../pages/suppliers/index.php");
    exit;
}

if ($name === '') {
    $errors['name'] = "Nama supplier wajib diisi.";
} elseif (strlen($name) > 100) {
    $errors['name'] = "Nama supplier maksimal 100 karakter.";
}

if ($email !== '') {
    if (strlen($email) > 100) {
        $errors['email'] = "Email maksimal 100 karakter.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Format email tidak valid.";
    }
}

if ($phone === '') {
    $errors['phone'] = "No. telepon wajib diisi.";
} elseif (!preg_match('/^[0-9+\-\s]{8,15}$/', $phone)) {
    $errors['phone'] = "No. telepon tidak valid (8-15 karakter, hanya angka, +, -).";
}

if ($address === '') {
    $errors['address'] = "Alamat wajib diisi.";
}

if ($product_types === '') {
    $errors['product_types'] = "Jenis produk wajib diisi.";
} elseif (strlen($product_types) > 100) {
    $errors['product_types'] = "Jenis produk maksimal 100 karakter.";
}

if ($partnership_date === '') {
    $errors['partnership_date'] = "Tanggal kerja sama wajib diisi.";
} else {
    $d = DateTime::createFromFormat('Y-m-d', $partnership_date);
    if (!$d || $d->format('Y-m-d') !== $partnership_date) {
        $errors['partnership_date'] = "Tanggal kerja sama tidak valid.";
    }
}

if (!empty($errors)) {
    $_SESSION['form'] = ['input' => $_POST, 'errors' => $errors];
    header("Location: ../../pages/suppliers/edit.php?id=" . $id);
    exit;
}

try {
    $pdo->beginTransaction();
    
    $stmt = $pdo->prepare("SELECT id FROM suppliers WHERE id = ? FOR UPDATE");
    $stmt->execute([$id]);
    if (!$stmt->fetchColumn()) {
        throw new RuntimeException("Data tidak ditemukan.");
    }

    $stmt = $pdo->prepare(
        "UPDATE suppliers SET name = ?, email = ?, phone = ?, address = ?,
        product_types = ?, partnership_date = ? WHERE id = ?"
    );
    $stmt->execute([
        $name, $email === '' ? null : $email, $phone, $address,
        $product_types, $partnership_date, $id
    ]);

    $pdo->commit();
} catch (Throwable $errors) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    $msg = ['_general' => "Gagal mengubah data. Perubahan dibatalkan (rollback)."];
    $_SESSION['form'] = ['input' => $_POST, 'errors' => $msg];
    header("Location: ../../pages/suppliers/edit.php?id=$id");
    exit;
}

$_SESSION['flash'] = ['type' => 'success', 'message' => "Supplier berhasil diubah."];
header("Location: ../../pages/suppliers/index.php");
exit;
?>