<?php
require_once __DIR__ . "/../../middleware/auth.php";
requireLogin();
require_once __DIR__ . "/../../config/database.php";

$type_list = ['Food','Drink','Stationery','Uniform'];

$old = [
    'name'    => '',
    'email'            => '',
    'phone'            => '',
    'address'          => '',
    'product_types'    => [],
    'partnership_date' => date('Y-m-d')
];
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
    <title>Tambah Supplier</title>
    <link rel="stylesheet" href="../../styles/base.css">
</head>

<body>
    <div class="app-shell">
        <?php require_once __DIR__ . "/../../components/admin/sidebar.php" ?>

        <main class="app-main">
            <?php require_once __DIR__ . "/../../components/admin/topbar.php" ?>
            <main class="app-content">
                <h1>Tambah Supplier</h1>

                <?php if (isset($errors['_general'])): ?>
                    <div class="alert alert-error"><?= htmlspecialchars($errors['_general']) ?></div>
                <?php endif; ?>

                <form action="../../actions/suppliers/store.php" method="POST" class="form">
                    <div class="form-group">
                        <label>Nama Supplier</label>
                        <input type="text" name="name" maxlength="100"
                            value="<?= htmlspecialchars($old['name'] ?? '') ?>">
                        <?php if (isset($errors['name'])): ?>
                            <div class="field-error"><?= htmlspecialchars($errors['name']) ?></div><?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label>Email (opsional)</label>
                        <input type="email" name="email" maxlength="100"
                            value="<?= htmlspecialchars($old['email'] ?? '') ?>">
                        <?php if (isset($errors['email'])): ?>
                            <div class="field-error"><?= htmlspecialchars($errors['email']) ?></div><?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label>No. Telepon</label>
                        <input type="tel" name="phone" maxlength="15"
                            value="<?= htmlspecialchars($old['phone'] ?? '') ?>">
                        <?php if (isset($errors['phone'])): ?>
                            <div class="field-error"><?= htmlspecialchars($errors['phone']) ?></div><?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea name="address" rows="3"><?= htmlspecialchars($old['address'] ?? '') ?></textarea>
                        <?php if (isset($errors['address'])): ?>
                            <div class="field-error"><?= htmlspecialchars($errors['address']) ?></div><?php endif; ?>
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
                    </div>

                    <div class="form-group">
                        <label>Tanggal Kerja Sama</label>
                        <input type="date" name="partnership_date"
                            value="<?= htmlspecialchars($old['partnership_date'] ?? '') ?>">
                        <?php if (isset($errors['partnership_date'])): ?>
                            <div class="field-error"><?= htmlspecialchars($errors['partnership_date']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="index.php" class="btn">Batal</a>
                    </div>
                </form>
            </main>
        </main>
    </div>
    <?php require __DIR__ . "/../../components/landing/footer.php"; ?>
</body>

</html>