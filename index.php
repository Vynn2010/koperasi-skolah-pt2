
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koperasi Sekolah</title>
    <link rel="stylesheet" href="styles/base.css">
</head>
<body>
    <?php require_once __DIR__ . "/components/landing/header.php" ?>

    <main class="hero">
        <h1>Sistem Informasi Koperasi Sekolah</h1>
        <p>SMK Kristen Immanuel Pontianak</p>
        <a href="pages/products/index.php" class="btn btn-primary">Mulai Kelola Data</a>
    </main>

    <section class="features">
        <a class="card" href="pages/products/index.php">
            <strong>Produk</strong>
            <span>Kelola data barang koperasi</span>
        </a>
        <a class="card" href="pages/suppliers/index.php">
            <strong>Supplier</strong>
            <span>Kelola data pemasok</span>
        </a>
        <a class="card" href="pages/employees/index.php">
            <strong>Pegawai</strong>
            <span>Kelola data pegawai</span>
        </a>
    </section>

    <?php require_once __DIR__ . "/components/landing/footer.php" ?>
</body>
</html>