<?php
require_once __DIR__ . "/../../middleware/auth.php";
requireLogin();
require_once __DIR__ . "/../../config/database.php";

$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM employees WHERE id = ?");
$stmt->execute([$id]);
$employee = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$employee) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => "Data pegawai tidak ditemukan."];
    header("Location: index.php");
    exit;
}

$form = $_SESSION['form'] ?? [];
unset($_SESSION['form']);
$old = $form['input'] ?? $employee;
$errors = $form['errors'] ?? [];

$base = "../../";
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Pegawai</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>

<body>
    <?php require __DIR__ . "/../../includes/header.php"; ?>

    <main class="app-content">
        <h1>Edit Pegawai</h1>

        <?php if (isset($errors['_general'])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($errors['_general']) ?></div>
        <?php endif; ?>

        <form action="../../actions/employees/update.php" method="POST" class="form">
            <input type="hidden" name="id" value="<?= (int) $employee['id'] ?>">

            <div class="form-group">
                <label>Nomor Pegawai</label>
                <input type="text" name="number" maxlength="10" value="<?= htmlspecialchars($old['number'] ?? '') ?>">
                <?php if (isset($errors['number'])): ?>
                    <div class="field-error"><?= htmlspecialchars($errors['number']) ?></div><?php endif; ?>
            </div>

            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="full_name" maxlength="100"
                    value="<?= htmlspecialchars($old['full_name'] ?? '') ?>">
                <?php if (isset($errors['full_name'])): ?>
                    <div class="field-error"><?= htmlspecialchars($errors['full_name']) ?></div><?php endif; ?>
            </div>

            <div class="form-group">
                <label>Jenis Kelamin</label>
                <label><input type="radio" name="gender" value="M" <?= ($old['gender'] ?? '') === 'M' ? 'checked' : '' ?>>
                    Laki-laki</label>
                <label><input type="radio" name="gender" value="F" <?= ($old['gender'] ?? '') === 'F' ? 'checked' : '' ?>>
                    Perempuan</label>
                <?php if (isset($errors['gender'])): ?>
                    <div class="field-error"><?= htmlspecialchars($errors['gender']) ?></div><?php endif; ?>
            </div>

            <div class="form-group">
                <label>Tanggal Lahir</label>
                <input type="date" name="birth_date" value="<?= htmlspecialchars($old['birth_date'] ?? '') ?>">
                <?php if (isset($errors['birth_date'])): ?>
                    <div class="field-error"><?= htmlspecialchars($errors['birth_date']) ?></div><?php endif; ?>
            </div>

            <div class="form-group">
                <label>Jabatan</label>
                <select name="position">
                    <option value="">-- Pilih --</option>
                    <?php foreach (['Cashier', 'Warehouse Staff', 'Admin'] as $p): ?>
                        <option value="<?= htmlspecialchars($p) ?>" <?= ($old['position'] ?? '') === $p ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['position'])): ?>
                    <div class="field-error"><?= htmlspecialchars($errors['position']) ?></div><?php endif; ?>
            </div>

            <div class="form-group">
                <label>Gaji</label>
                <input type="number" name="salary" min="0" step="0.01"
                    value="<?= htmlspecialchars($old['salary'] ?? '') ?>">
                <?php if (isset($errors['salary'])): ?>
                    <div class="field-error"><?= htmlspecialchars($errors['salary']) ?></div><?php endif; ?>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="index.php" class="btn">Batal</a>
            </div>
        </form>
    </main>

    <?php require __DIR__ . "/../../includes/footer.php"; ?>
</body>

</html>