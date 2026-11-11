<?php
require_once __DIR__ . "/../config/database.php";

function getAllEmployees(): array
{
    global $pdo;
    return $pdo->query("SELECT * FROM employees ORDER BY id DESC")->fetchAll();
}

function findEmployee(int $id): ?array
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM employees WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function employeeNumberExists(string $number, int $exceptId = 0): bool
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT 1 FROM employees WHERE number = ? AND id <> ?");
    $stmt->execute([$number, $exceptId]);
    return (bool) $stmt->fetchColumn();
}

function insertEmployee(array $d): void
{
    global $pdo;
    $stmt = $pdo->prepare(
        "INSERT INTO employees (number, full_name, gender, birth_date, position, salary)
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->execute([$d['number'], $d['full_name'], $d['gender'], $d['birth_date'], $d['position'], $d['salary']]);
}

function updateEmployee(int $id, array $d): void
{
    global $pdo;
    $stmt = $pdo->prepare(
        "UPDATE employees SET number = ?, full_name = ?, gender = ?,
                birth_date = ?, position = ?, salary = ?
          WHERE id = ?"
    );
    $stmt->execute([$d['number'], $d['full_name'], $d['gender'], $d['birth_date'], $d['position'], $d['salary'], $id]);
}

function deleteEmployee(int $id): bool
{
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM employees WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->rowCount() > 0;
}