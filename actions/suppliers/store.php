<?php
require_once __DIR__ . "/../../middleware/auth.php";
requireLogin();
require_once __DIR__ . "/../../config/database.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../pages/suppliers/index.php");
    exit;
}

$type_list = ['Food', 'Drink', 'Stationery', 'Uniform'];

$name    = trim($_POST['name'] ?? '');
$email            = trim($_POST['email'] ?? '');
$phone            = trim($_POST['phone'] ?? '');
$address          = trim($_POST['address'] ?? '');
$partnership_date = trim($_POST['partnership_date'] ?? '');
$types            = $_POST['product_types'] ?? [];
if (!is_array($types)) {
    $types = [];
}

$errors = [];

if ($name === '') {
    $errors['name'] = "Nama supplier wajib diisi.";
} elseif (strlen($name) > 100) {
    $errors['name'] = "Nama supplier maksimal 100 karakter.";
}

if ($email === '') {
    $errors['email'] = "Email wajib diisi.";
} elseif (strlen($email) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = "Format email tidak valid.";
}

if ($phone === '') {
    $errors['phone'] = "No. telepon wajib diisi.";
} elseif (!preg_match('/^[0-9+\-\s]{8,15}$/', $phone)) {
    $errors['phone'] = "No. telepon tidak valid (8-15 karakter, hanya angka, +, -).";
}

if ($address === '') {
    $errors['address'] = "Alamat wajib diisi.";
}

if (count($types) === 0) {
    $errors['product_types'] = "Pilih minimal satu jenis produk.";
} else {
    foreach ($types as $t) {
        if (!is_string($t) || !in_array($t, $type_list, true)) {
            $errors['product_types'] = "Jenis produk tidak valid.";
            break;
        }
    }
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
    header("Location: ../../pages/suppliers/create.php");
    exit;
}

// array → JSON, contoh: ["Food","Drink"]
$types = array_values(array_intersect($type_list, $types));
$product_types = json_encode($types);

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare(
        "INSERT INTO suppliers (name, email, phone, address, product_types, partnership_date)
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->execute([$name, $email, $phone, $address, $product_types, $partnership_date]);

    $pdo->commit();
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $_SESSION['form'] = ['input' => $_POST, 'errors' => ['_general' => "Gagal menyimpan data. Perubahan dibatalkan (rollback)."]];
    header("Location: ../../pages/suppliers/create.php");
    exit;
}

$_SESSION['flash'] = ['type' => 'success', 'message' => "Supplier berhasil ditambahkan."];
header("Location: ../../pages/suppliers/index.php");
exit;