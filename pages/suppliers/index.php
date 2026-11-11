<?php
require_once __DIR__ . "/../../repositories/suppliers_repository.php";
require_once __DIR__ . "/../../middleware/auth.php";
requireLogin();
require_once __DIR__ . "/../../config/database.php";

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$suppliers = getAllSuppliers();

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Supplier</title>
    <link rel="stylesheet" href="../../styles/base.css">
</head>

<body>
    <div class="app-shell">
        <?php require_once __DIR__ . "/../../components/admin/sidebar.php" ?>

        <main class="app-main">
            <?php require_once __DIR__ . "/../../components/admin/topbar.php" ?>
            <main class="app-content">
                <h1>Data Supplier</h1>

                <?php if ($flash): ?>
                    <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>">
                        <?= htmlspecialchars($flash['message']) ?>
                    </div>
                <?php endif; ?>

                <div class="toolbar">
                    <a href="create.php" class="btn btn-primary">+ Tambah Supplier</a>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>No. Telepon</th>
                                <th>Alamat</th>
                                <th>Jenis Produk</th>
                                <th>Tanggal Kerja Sama</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!$suppliers): ?>
                                <tr>
                                    <td colspan="8" class="empty">Belum ada data supplier.</td>
                                </tr>
                            <?php endif; ?>

                            <?php foreach ($suppliers as $i => $row): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= htmlspecialchars($row['name'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['email'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($row['phone'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['address'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['product_types'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['partnership_date'] ?? '') ?></td>
                                    <td>
                                        <a href="edit.php?id=<?= (int) $row['id'] ?>" class="btn btn-sm">Edit</a>
                                        <form action="../../actions/suppliers/destroy.php" method="POST"
                                            style="display:inline"
                                            onsubmit="return confirm('Yakin ingin menghapus supplier ini?')">
                                            <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </main>
    </div>
    <?php require __DIR__ . "/../../components/landing/footer.php"; ?>
</body>

</html>