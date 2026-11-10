<?php
require_once __DIR__ . "/../../middleware/auth.php";
redirectIfLoggedIn();

if(isset($_SESSION['user'])) {
    header("Location: ../products/index.php");
    exit;
}

$old = ['username' => '', 'email' => ''];
$errors= [];
if(isset($_SESSION['form'])) {
    $old = array_merge($old, $_SESSION['form']['input']);
    $errors = $_SESSION['fomr']['errors'];
    unset($_SESSION['form']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Koperasi</title>
    <link rel="stylesheet" href="../../styles/base.css">
</head>
<body>
    <div class="auth-box">
        <h2>Daftar Akun</h2>
        <form method="POST" action="../../actions/auth/register.php" novalidate>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($old['username']) ?>">
                <?php if(isset($errors['username'])): ?><div class="field-error"><?= htmlspecialchars($errors['username']) ?></div><?php endif; ?>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($old['email']) ?>">
                <?php if(isset($errors['email'])): ?><div class="field-error"><?= htmlspecialchars($errors['email']) ?></div><?php endif; ?>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
                <?php if(isset($errors['password'])): ?><div class="field-error"><?= htmlspecialchars($errors['password']) ?></div><?php endif; ?>
            </div>
            <div class="form-group">
                <label for="confirm_password">Konfirmasi Password</label>
                <input type="confirm_password" id="confirm_password" name="confirm_password">
                <?php if(isset($errors['confirm_password'])): ?><div class="field-error"><?= htmlspecialchars($errors['confirm_password']) ?></div><?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Daftar</button>
        </form>
        <p>Sudah punya akun? <a href="login.php">Masuk</a></p>           
    </div>
</body>
</html>