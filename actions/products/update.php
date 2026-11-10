<?php
require_once __DIR__ . "/../../middleware/auth.php";
requireLogin();
require_once __DIR__ . "/../../config/database.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST'){
    header("Location: ../../pages/products/index.php");
    exit;
}

$id = (int)($_POST['id'] ?? 0);

$stmt = $pdo->prepare("SELECT 1 FROM products WHERE product_id = ?");
$stmt->execute([$id]);
if ($id <= 0 || !$stmt->fetchColumn()) {
    $_SESSION['flash'] = ['type' => 'error','message' => "Data produk tidak ditemukan."];
    header("Location: ../../pages/product/index.php");
    exit;
}

$categories = ['Food','Drink','Stationery','Uniform'];
$units = ['Pcs','Pack','Bottle'];

$product_code     = trim($_POST['product_code'] ?? '');
$product_name     = trim($_POST['product_name'] ?? '');
$category         = trim($_POST['category'] ?? '');
$unit             = trim($_POST['unit'] ?? '');
$price            = trim($_POST['price'] ?? '');
$stock            = trim($_POST['stock'] ?? '');
$entry_date       = trim($_POST['entry_date'] ?? '');
$description      = trim($_POST['description'] ?? '');

$errors = [];

if ($product_code === '') {
    $errors['product_code'] = "Kode produk wajib diisi.";
} elseif (strlen($product_code) > 10) {
    $errors['product_code'] = "Kode produk maksimal 10 karakter.";
} else {
    $stmt=$pdo->prepare("SELECT 1 FROM products WHERE product_code = ? AND product_id <>?");
    $stmt->execute([$product_code, $id]);
    if($stmt->fetchColumn()){
        $errors['product_code'] = "Kode produk sudah digunakan produk lain.";
    }
}

if ($product_name === '') {
    $errors['product_name'] = "Nama produk wajib diisi.";
} elseif (strlen($product_name) > 100) {
    $errors['product_name'] = "Nama produk maksimal 100 karakter.";
}

if ($category === '') {
    $errors['category'] ="Kategori wajib dipilih.";
} elseif (!in_array($category, $categories, true)) {
    $errors['category'] = "Kategori tidak valid.";
}

if ($unit === '') {
    $errors['unit'] = "Satuan wajib dipilih.";
} elseif (!in_array($unit, $units, true)) {
    $errors['unit'] = "Satuan tidak valid.";
}

if ($price === '') {
    $errors['price'] = "Harga wajib diiisi.";
} elseif (!is_numeric($price)) {
    $errors['price'] = "Harga harus berupa angka.";
} elseif ((float)$price < 0) {
    $errors['price'] = "Harga tidak boleh negatif.";
} elseif ((float)$price > 99999999.99) {
    $errors['price'] = "Harga terlalu besar.";
}

if ($stock === '') {
    $errors['stock'] = "Stok wajib diisi.";
} elseif (!preg_match('/^-?\d+$/', $stock)) {
    $errors['stock'] = "Stok harus berupa bilangan bulat.";
} elseif ((int)$stock < 0) {
    $errors['stock'] = "Stok tidak boleh negatif.";
}

if ($entry_date === '') {
    $errors['entry_date'] = "Tanggal masuk wajib diisi.";
} else {
    $d = DateTime::createFromFormat('Y-m-d', $entry_date);
    if (!$d || $d->format('Y-m-d') !== $entry_date) {
        $errors['entry_date'] = "Tanggal masuk tidak valid.";
    }
}

if ($description === '') {
    $errors['description'] = "Deskripsi wajib diisi.";
}

if (!empty($errors)) {
    $_SESSION['form'] = ['input' => $_POST, 'errors' => $errors];
    header("Location: ../../pages/products/edit.php?id=" . $id);
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("SELECT product_id FROM products WHERE product_id = ? FOR UPDATE");
    $stmt->execute([$id]);
    if (!$stmt->fetchColumn()) {
        throw new RuntimeException("Data tidak ditemukan.");
    }

    $stmt = $pdo->prepare(
        "UPDATE products SET product_code = ?, product_name = ?, category = ?, unit  = ?,
        price = ?, stock = ?, entry_date = ?, description = ? WHERE product_id = ?"
    );

    $stmt->execute([
        $product_code,$product_name,$category,$unit,
        $price, $stock, $entry_date, $description, $id
    ]);

    $pdo->commit();
} catch (Throwable $errors) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    if ($errors instanceof PDOException && ($errors->errorInfo[1] ?? 0) == 1062) {
        $msg = ['product_code' => "Kode produk sudah digunakan produk lain."];
    } else {
        $msg = ['_general' => "Gagal memngubah data. Perubahan dibatalkan (rollback)."];
    }

    $_SESSION['form'] = ['input' => $_POST, 'errors' => $msg];
    header("Location: ../../pages/products/edit.php?id=" . $id);
    exit;
}

$_SESSION['flash'] = ['type' => 'success', 'message' => "Produk berhasil diubah."];
header("Location: ../../pages/products/index.php");
exit;
   



?>