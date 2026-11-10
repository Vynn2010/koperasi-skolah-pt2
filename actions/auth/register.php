<?php
session_start();
require_once __DIR__ . "/../../config/database.php";

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    header("Location: ../../pages/auth/register.php");
    exit;
}

$username = trim($_POST['username'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');
$confirm  = trim($_POST['confirm_password'] ?? '');
$errors   = [];

if ($username === '') {
    $errors['username'] = "username wajib diisi.";
} elseif (strlen($username) < 3 || strlen($username) > 50){
    $errors['username'] = "Username 3-50 karakter.";
} else {
    $stmt = $pdo->prepare("SELECT 1 FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetchColumn()){
        $errors['username'] = "Username sudab digunakan.";
    }
}

if ($email === '') {
    $errors['email'] = "Email wajib diisi.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = "Format email tidak valid.";
} else {
    $stmt = $pdo->prepare("SELECT 1 FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetchColumn()){
        $errors['email'] = "Email sudab digunakan.";
    }
}

if ($password === '') {
    $errors['password'] = "Password wajib diisi.";
} elseif (strlen($password) < 8){
    $errors['password'] = "Password minimal 8 karakter.";
} 

if ($confirm !== $password) {
    $errors['confirm_password'] = "Konfirmasi password tidak sama.";
}

if(!empty($errors)) {
    $_SESSION['form'] = ['input' => ['username' => $username, 'email' => $email], 'errors' => $errors];
    header("Location: ../../pages/auth/register.php");
    exit;
}

$stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?,?,?)");
$stmt->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT)]);

$_SESSION['flash'] = ['type' => 'success','message'=>"Registrasi berhasil. Silahkan login kembali."];
header("Location: ../../pages/auth/login.php");
exit;
?>