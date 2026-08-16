<?php
declare(strict_types=1);
require __DIR__ . '/config/database.php';
use Config\Database;
print_r(PDO::getAvailableDrivers());

try {
    $db = new Database();
    $pdo = $db->getPdo();
    echo 'PDO OK. Version: ' . $pdo->getAttribute(\PDO::ATTR_SERVER_VERSION);
} catch (\Throwable $e) {
    echo 'Error: ' . $e->getMessage();
}
?>