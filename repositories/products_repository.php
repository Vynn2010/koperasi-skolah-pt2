<?php
require_once __DIR__ . "/../config/database.php";

function getProductCategories(): array
{
    return ['Food', 'Drink', 'Stationery', 'Uniform'];
}

function getProductUnits(): array
{
    return ['Pcs', 'Pack', 'Bottle'];
}

function getAllProducts(): array
{
    global $pdo;
    return $pdo->query("SELECT * FROM products ORDER BY product_id DESC")->fetchAll();
}

function findProduct(int $id): ?array
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function productCodeExists(string $code, int $exceptId = 0): bool
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT 1 FROM products WHERE product_code = ? AND product_id <> ?");
    $stmt->execute([$code, $exceptId]);
    return (bool) $stmt->fetchColumn();
}

// Kunci baris di dalam transaksi (dipakai update & destroy)
function lockProduct(int $id): bool
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT product_id FROM products WHERE product_id = ? FOR UPDATE");
    $stmt->execute([$id]);
    return (bool) $stmt->fetchColumn();
}

function insertProduct(array $d): void
{
    global $pdo;
    $stmt = $pdo->prepare(
        "INSERT INTO products
            (product_code, product_name, category, unit, price, stock, entry_date, description)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->execute([
        $d['product_code'], $d['product_name'], $d['category'], $d['unit'],
        $d['price'], $d['stock'], $d['entry_date'], $d['description'],
    ]);
}

function updateProduct(int $id, array $d): void
{
    global $pdo;
    $stmt = $pdo->prepare(
        "UPDATE products SET product_code = ?, product_name = ?, category = ?, unit = ?,
                price = ?, stock = ?, entry_date = ?, description = ?
          WHERE product_id = ?"
    );
    $stmt->execute([
        $d['product_code'], $d['product_name'], $d['category'], $d['unit'],
        $d['price'], $d['stock'], $d['entry_date'], $d['description'], $id,
    ]);
}

function deleteProduct(int $id): void
{
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->execute([$id]);
}