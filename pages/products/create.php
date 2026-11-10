<?php
require_once __DIR__ . "/../../middleware/auth.php";
requireLogin();
require_once __DIR__ . "/../../config/database.php";

$old = [
    'product_code' => '',
    'product_name' => '',
    'category' => '',
    'unit' => '',
    'price' => '',
    'stock' => '',
    'entry_date' => date('Y-m-d'),
    'description' => ''
];
$errors = [];

if (isset($_SESSION['form'])) {
    $old = array_merge($old, $_SESSION['form']['input']);
    $errors = $_SESSION['form']['errors'];
    unset($_SESSION['form']);
}

$categories = ['Food', 'Drink', 'Stationery', 'Uniform'];
$units = ['Pcs', 'Pack', 'Bottle'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../styles/base.css">
    <title>Tambah Produk - Koperasi</title>
</head>

<body>
    <div class="app-shell">
        <?php require_once __DIR__ . "/../../components/admin/sidebar.php" ?>

        <main class="app-main">
            <?php require_once __DIR__ . "/../../components/admin/topbar.php" ?>

            <div class="app-content">

                <?php if (isset($errors['_general'])): ?>
                    <div class="alert alert-error"><?= htmlspecialchars($errors['_general']) ?></div>
                <?php endif; ?>

                <form method="POST" action="../../actions/products/store.php" class="form" novalidate>
                    <div class="form-section-title">Data Produk</div>

                    <div class="form-group">
                        <label for="product_code">Kode Produk</label>
                        <input type="text" id="product_code" name="product_code" maxlength="10"
                            value="<?= htmlspecialchars($old['product_code']) ?>">
                        <?php if (isset($errors['product_code'])): ?>
                            <div class="field-error"><?= htmlspecialchars($errors['product_code']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="product_name">Nama Produk</label>
                        <input type="text" id="product_name" name="product_name" maxlength="100"
                            value="<?= htmlspecialchars($old['product_name']) ?>">
                        <?php if (isset($errors['product_name'])): ?>
                            <div class="field-error"><?= htmlspecialchars($errors['product_name']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="category">Kategori</label>
                        <select id="category" name="category">
                            <option value="">=== Pilih Kategori ===</option>
                            <?php foreach ($categories as $c): ?>
                                <option value="<?= $c ?>" <?= $old['category'] === $c ? 'selected' : '' ?>><?= $c ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['category'])): ?>
                            <div class="field-error"><?= htmlspecialchars($errors['category']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label>Satuan</label>
                        <?php foreach ($units as $u): ?>
                            <label>
                                <input type="radio" name="unit" value="<?= $u ?>" <?= $old['unit'] === $u ? 'checked' : '' ?>>
                                <?= $u ?>
                            </label>
                        <?php endforeach ?>
                        <?php if (isset($errors['unit'])): ?>
                            <div class="field-error"><?= htmlspecialchars($errors['unit']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="price">Harga (Rp)</label>
                        <input type="number" id="price" name="price" min="0" step="0.01"
                            value="<?= htmlspecialchars($old['price']) ?>">
                        <?php if (isset($errors['price'])): ?>
                            <div class="field-error"><?= htmlspecialchars($errors['price']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="stock">Stok</label>
                        <input type="number" id="stock" name="stock" min="0" step="1"
                            value="<?= htmlspecialchars($old['stock']) ?>">
                        <?php if (isset($errors['stock'])): ?>
                            <div class="field-error"><?= htmlspecialchars($errors['stock']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="entry_date">Tanggal Masuk</label>
                        <input type="date" id="entry_date" name="entry_date"
                            value="<?= htmlspecialchars($old['entry_date']) ?>">
                        <?php if (isset($errors['entry_date'])): ?>
                            <div class="field-error"><?= htmlspecialchars($errors['entry_date']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea name="description" id="description"
                            rows="4"><?= htmlspecialchars($old['description']) ?></textarea>
                        <?php if (isset($errors['description'])): ?>
                            <div class="field-error"><?= htmlspecialchars($errors['description']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="index.php" class="btn btn-outline">Batal</a>
                    </div>
                </form>
            </div>
        </main>

    </div>
</body>

</html>