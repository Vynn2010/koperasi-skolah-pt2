<?php
require_once __DIR__ . "/../../repositories/employees_repository.php";
require_once __DIR__ . "/../../middleware/auth.php";
requireLogin();
require_once __DIR__ . "/../../config/database.php";

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$employees = getAllEmployees();
$genderLabel = ['M' => 'Laki-laki', 'F' => 'Perempuan'];

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Pegawai</title>
    <link rel="stylesheet" href="../../styles/base.css">
</head>

<body>
    <div class="app-shell">
        <?php require_once __DIR__ . "/../../components/admin/sidebar.php" ?>

        <main class="app-main">
            <?php require_once __DIR__ . "/../../components/admin/topbar.php" ?>
            <main class="app-content">
                <h1>Data Pegawai</h1>

                <?php if ($flash): ?>
                    <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>">
                        <?= htmlspecialchars($flash['message']) ?>
                    </div>
                <?php endif; ?>

                <div class="toolbar">
                    <a href="create.php" class="btn btn-primary">+ Tambah Pegawai</a>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nomor</th>
                                <th>Nama Lengkap</th>
                                <th>Jenis Kelamin</th>
                                <th>Tanggal Lahir</th>
                                <th>Jabatan</th>
                                <th>Gaji</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!$employees): ?>
                                <tr>
                                    <td colspan="8" class="empty">Belum ada data pegawai.</td>
                                </tr>
                            <?php endif; ?>

                            <?php foreach ($employees as $i => $row): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= htmlspecialchars($row['number'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['full_name'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($genderLabel[$row['gender']] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($row['birth_date'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($row['position'] ?? '') ?></td>
                                    <td class="num">Rp <?= number_format((float) $row['salary'], 0, ',', '.') ?></td>
                                    <td>
                                        <a href="edit.php?id=<?= (int) $row['id'] ?>" class="btn btn-sm">Edit</a>
                                        <form action="../../actions/employees/destroy.php" method="POST"
                                            style="display:inline"
                                            onsubmit="return confirm('Yakin ingin menghapus pegawai ini?')">
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