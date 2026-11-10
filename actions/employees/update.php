<?php
require_once __DIR__ . "/../../middleware/auth.php";
requireLogin();
require_once __DIR__ . "/../../config/database.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../pages/employees/index.php");
    exit;
}

$id         = (int)($_POST['id'] ?? 0);
$number     = trim($_POST['number'] ?? '');
$full_name  = trim($_POST['full_name'] ?? '');
$gender     = trim($_POST['gender'] ?? '');
$birth_date = trim($_POST['birth_date'] ?? '');
$position   = trim($_POST['position'] ?? '');
$salary     = trim($_POST['salary'] ?? '');

$stmt = $pdo->prepare("SELECT 1 FROM employees WHERE id = ?");
$stmt->execute([$id]);
if ($id <= 0 || !$stmt->fetchColumn()) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => "Data pegawai tidak ditemukan."];
    header("Location: ../../pages/employees/index.php");
    exit;
}

$errors = [];

if ($number === '') {
    $errors['number'] = "Nomor pegawai wajib diisi.";
} elseif (strlen($number) > 10) {
    $errors['number'] = "Nomor pegawai maksimal 10 karakter.";
} else {
    // cek unik, kecuali milik pegawai ini sendiri
    $stmt = $pdo->prepare("SELECT 1 FROM employees WHERE number = ? AND id <> ?");
    $stmt->execute([$number, $id]);
    if ($stmt->fetchColumn()) {
        $errors['number'] = "Nomor pegawai sudah digunakan.";
    }
}

if ($full_name === '') {
    $errors['full_name'] = "Nama lengkap wajib diisi.";
} elseif (strlen($full_name) > 100) {
    $errors['full_name'] = "Nama lengkap maksimal 100 karakter.";
}

if (!in_array($gender, ['M', 'F'], true)) {
    $errors['gender'] = "Jenis kelamin tidak valid.";
}

if ($birth_date === '') {
    $errors['birth_date'] = "Tanggal lahir wajib diisi.";
} else {
    $d = DateTime::createFromFormat('Y-m-d', $birth_date);
    if (!$d || $d->format('Y-m-d') !== $birth_date) {
        $errors['birth_date'] = "Tanggal lahir tidak valid.";
    }
}

if (!in_array($position, ['Cashier', 'Warehouse Staff', 'Admin'], true)) {
    $errors['position'] = "Jabatan tidak valid.";
}

if ($salary === '') {
    $errors['salary'] = "Gaji wajib diisi.";
} elseif (!is_numeric($salary) || $salary < 0 || $salary > 9999999999.99) {
    $errors['salary'] = "Gaji tidak valid.";
}

if (!empty($errors)) {
    $_SESSION['form'] = ['input' => $_POST, 'errors' => $errors];
    header("Location: ../../pages/employees/edit.php?id=" . $id);
    exit;
}

try {
    $stmt = $pdo->prepare(
        "UPDATE employees SET number = ?, full_name = ?, gender = ?,
         birth_date = ?, position = ?, salary = ? WHERE id = ?"
    );
    $stmt->execute([$number, $full_name, $gender, $birth_date, $position, $salary, $id]);
} catch (Throwable $errors) {
    $_SESSION['form'] = ['input' => $_POST, 'errors' => ['_general' => "Gagal mengubah data."]];
    header("Location: ../../pages/employees/edit.php?id=" . $id);
    exit;
}

$_SESSION['flash'] = ['type' => 'success', 'message' => "Pegawai berhasil diubah."];
header("Location: ../../pages/employees/index.php");
exit;
?>