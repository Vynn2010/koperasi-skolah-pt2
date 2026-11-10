<?php
session_start();
require_once __DIR__ . "/config/database.php";

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$products = $pdo->query("SELECT * FROM products ORDER BY product_id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Produk - Koperasi</title>
    <link rel="stylesheet" href="../../styles/base.css">
</head>
<body>
    <div class="app-shell">
        <?php require_once __DIR__ . "/../../components/admin/sidebar.php" ?>

        <main class="app-main">
            <?php require_once __DIR__ . "../../components/admin/topbar.php" ?>
            
            <div class="app-content">

                <?php if ($flash): ?>
                    <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>">
                        <?= htmlspecialchars($flash['message']) ?>
                    </div>
                <?php endif; ?>

                <div class="toolbar">
                    <a href="create.php" class="btn btn-primary">Tambah Produk</a>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Satuan</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Tgl Masuk</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($products)): ?>
                                <tr><td colspan="10" class="empty">Belum ada data produk</td></tr>
                            <?php endif; ?>

                            <?php foreach ($products as $i => $p): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= htmlspecialchars($p['product_code']) ?></td>
                                    <td><?= htmlspecialchars($p['product_name']) ?></td>
                                    <td><?= htmlspecialchars($p['category']) ?></td>
                                    <td><?= htmlspecialchars($p['unit']) ?></td>
                                    <td class="num">Rp <?=  number_format((float)$p['price'], 2, ',', '.') ?></td>
                                    <td class="num"><?= (int)$p['stock'] ?></td>
                                    <td><?= htmlspecialchars($p['entry_date']) ?></td>
                                    <td><?= htmlspecialchars($p['description']) ?></td>
                                    <td class="actions">
                                        <a href="edit.php?id=<?= (int)$p['product_id'] ?>"
                                            class="btn btn-outline btn-sm">Ubah</a>

                                        <form method="POST" action="../../actions/products/destroy.php"
                                            style="display:inline" onsubmit="return confirm('Yakin ingin menghapus produk <?= htmlspecialchars(addslashes($p['product_name']), ENT_QUOTES) ?>? Data tidak dapat dikembalikan.');">
                                            <input type="hidden" name="id" value="<?= (int)$p['product_id'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>