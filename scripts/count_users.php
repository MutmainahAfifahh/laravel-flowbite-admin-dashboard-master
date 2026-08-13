<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=laravel;charset=utf8', 'root', '');
    $stmt = $pdo->query('SELECT COUNT(*) FROM users');
    $count = $stmt->fetchColumn();
    echo $count . PHP_EOL;
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
}
