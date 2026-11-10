<?php
if(isset($_SESSION['user'])) {
    header("Location: ../products/index.php");
    exit;
}

$username = '';
$error = '';
if(isset($_SESSION['form'])) {
    $username = $_SESSION['form']['input']['username'];
    $error = $_SESSION['form']['error'];
    unset($_SESSION['form']);
}
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Koperasi</title>
    <link rel="stylesheet" href="../../styles/base.css">
</head>
<body>
    <div class="auth-box">
        <h2>Masuk</h2>

        <?php if ($flash): ?>
            <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>"><?= htmlspecialchars($flash['message']) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="../../actions/auth/login.php" novalidate>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($username) ?>">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
            </div>
            <button type="submit" class="btn btn-primary">Masuk</button>
        </form>
        <p>Belum punya akun? <a href="register.php">Daftar</a></p>
    </div>
</body>
</html>