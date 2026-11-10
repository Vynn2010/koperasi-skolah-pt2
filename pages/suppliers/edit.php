<?php
require_once __DIR__ . "/../../middleware/auth.php";
requireLogin();
require_once __DIR__ . "/../../config/database.php";

$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM suppliers WHERE supplier_id = ?");
$stmt->execute([$id]);
$supplier = $stmt->fetch(PDO::FETCH_ASSOC);

if ($id <= 0 || !$supplier) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => "Data supplier tidak ditemukan."];
    header("Location: index.php");
    exit;
}

$type_list = ['Food', 'Drink', 'Stationery', 'Uniform'];

$old = $supplier;
$old['product_types'] = json_decode($supplier['product_types'], true) ?: [];
$errors = [];

if (isset($_SESSION['form'])) {
    $old = array_merge($old, $_SESSION['form']['input']);
    $old['product_types'] = is_array($_SESSION['form']['input']['product_types'] ?? null)
        ? $_SESSION['form']['input']['product_types'] : [];
    $errors = $_SESSION['form']['errors'];
    unset($_SESSION['form']);
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Supplier</title>
    <link rel="stylesheet" href="../../styles/base.css">
</head>

<body>
    <div class="app-shell">
        <?php require_once __DIR__ . "/../../components/admin/sidebar.php" ?>

        <main class="app-main">
            <?php require_once __DIR__ . "/../../components/admin/topbar.php" ?>

            <div class="app-content">
                <h1>Edit Supplier</h1>

                <?php if (isset($errors['_general'])): ?>
                    <div class="alert alert-error"><?= htmlspecialchars($errors['_general']) ?></div>
                <?php endif; ?>

                <form action="../../actions/suppliers/update.php" method="POST" class="form" novalidate>
                    <input type="hidden" name="id" value="<?= (int) $id ?>">

                    <div class="form-group">
                        <label for="supplier_name">Nama Supplier</label>
                        <input type="text" id="supplier_name" name="supplier_name" maxlength="100"
                            value="<?= htmlspecialchars($old['supplier_name']) ?>">
                        <?php if (isset($errors['supplier_name'])): ?>
                            <div class="field-error"><?= htmlspecialchars($errors['supplier_name']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" maxlength="100"
                            value="<?= htmlspecialchars($old['email']) ?>">
                        <?php if (isset($errors['email'])): ?>
                            <div class="field-error"><?= htmlspecialchars($errors['email']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="phone">No. Telepon</label>
                        <input type="tel" id="phone" name="phone" maxlength="15"
                            value="<?= htmlspecialchars($old['phone']) ?>">
                        <?php if (isset($errors['phone'])): ?>
                            <div class="field-error"><?= htmlspecialchars($errors['phone']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="address">Alamat</label>
                        <textarea id="address" name="address" rows="3"><?= htmlspecialchars($old['address']) ?></textarea>
                        <?php if (isset($errors['address'])): ?>
                            <div class="field-error"><?= htmlspecialchars($errors['address']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label>Jenis Produk</label>
                        <?php foreach ($type_list as $t): ?>
                            <label>
                                <input type="checkbox" name="product_types[]" value="<?= $t ?>"
                                    <?= in_array($t, $old['product_types'], true) ? 'checked' : '' ?>>
                                <?= $t ?>
                            </label>
                        <?php endforeach; ?>
                        <?php if (isset($errors['product_types'])): ?>
                            <div class="field-error"><?= htmlspecialchars($errors['product_types']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="partnership_date">Tanggal Kerja Sama</label>
                        <input type="date" id="partnership_date" name="partnership_date"
                            value="<?= htmlspecialchars($old['partnership_date']) ?>">
                        <?php if (isset($errors['partnership_date'])): ?>
                            <div class="field-error"><?= htmlspecialchars($errors['partnership_date']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="index.php" class="btn">Batal</a>
                    </div>
                </form>