<?php
session_start();
require_once __DIR__ . "/../../config/database.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../../pages/auth/login.php");
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    $_SESSION['form'] = ['input' => ['username' => $username], 'error' => "Username dan password wajib diisi."];
    header("Location: ../../pages/auth/login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT user_id, username, password FROM users WHERE username =?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    session_regenerate_id(true);
    unset($_SESSION['flash']);
    $_SESSION['user'] =['id' => $user['user_id'], 'username' => $user['username']];
    header("Location: ../../pages/products/index.php");
    exit;
}

$_SESSION['form'] = ['input' => ['username' => $username], 'error' => "Username atau password salah."];
header("Location: ../../pages/auth/login.php");
exit;
?>