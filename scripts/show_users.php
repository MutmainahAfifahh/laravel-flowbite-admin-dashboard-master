<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=laravel;charset=utf8', 'root', '');
    $stmt = $pdo->query('SELECT id, email, password, role, email_verified_at FROM users');
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($rows, JSON_PRETTY_PRINT) . PHP_EOL;
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
}
