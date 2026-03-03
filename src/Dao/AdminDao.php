<?php
namespace App\Dao;

use PDO;

class AdminDao
{
    private $pdo; private $logger;
    public function __construct(PDO $pdo, Logger $logger)
    { $this->pdo = $pdo; $this->logger = $logger; }

    public function findByUsername(string $username): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id, username, password_hash FROM admin WHERE username = ?');
        $stmt->execute([$username]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
}