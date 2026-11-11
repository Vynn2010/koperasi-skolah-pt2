<?php
require_once __DIR__ . "/../config/database.php";

function getSupplierTypes(): array
{
    return ['Food', 'Drink', 'Stationery', 'Uniform'];
}

function getAllSuppliers(): array
{
    global $pdo;
    return $pdo->query("SELECT * FROM suppliers ORDER BY id DESC")->fetchAll();
}

function findSupplier(int $id): ?array
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM suppliers WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function lockSupplier(int $id): bool
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT id FROM suppliers WHERE id = ? FOR UPDATE");
    $stmt->execute([$id]);
    return (bool) $stmt->fetchColumn();
}

function insertSupplier(array $d): void
{
    global $pdo;
    $stmt = $pdo->prepare(
        "INSERT INTO suppliers (name, email, phone, address, product_types, partnership_date)
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->execute([
        $d['name'], $d['email'], $d['phone'], $d['address'],
        $d['product_types'], $d['partnership_date'],
    ]);
}

function updateSupplier(int $id, array $d): void
{
    global $pdo;
    $stmt = $pdo->prepare(
        "UPDATE suppliers SET name = ?, email = ?, phone = ?, address = ?,
                product_types = ?, partnership_date = ?
          WHERE id = ?"
    );
    $stmt->execute([
        $d['name'], $d['email'], $d['phone'], $d['address'],
        $d['product_types'], $d['partnership_date'], $id,
    ]);
}

function deleteSupplier(int $id): bool
{
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM suppliers WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->rowCount() > 0;
}