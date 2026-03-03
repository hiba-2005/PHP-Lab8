<?php
declare(strict_types=1);

namespace App\Dao;

use PDO;
use PDOException;

class FiliereDao
{
    private PDO $pdo;
    private Logger $logger;

    public function __construct(PDO $pdo, Logger $logger)
    {
        $this->pdo = $pdo;
        $this->logger = $logger;
    }

    public function insert(array $f): int
    {
        $sql = 'INSERT INTO filiere(code, libelle) VALUES(:code, :libelle)';
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':code', (string)($f['code'] ?? ''), PDO::PARAM_STR);
            $stmt->bindValue(':libelle', (string)($f['libelle'] ?? ''), PDO::PARAM_STR);
            $stmt->execute();
            return (int)$this->pdo->lastInsertId();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), ['method' => __METHOD__, 'sql' => $sql]);
            throw $e;
        }
    }

    public function update(int $id, array $f): bool
    {
        $sql = 'UPDATE filiere SET code = :code, libelle = :libelle WHERE id = :id';
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':code', (string)($f['code'] ?? ''), PDO::PARAM_STR);
            $stmt->bindValue(':libelle', (string)($f['libelle'] ?? ''), PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), ['method' => __METHOD__, 'sql' => $sql, 'id' => $id]);
            throw $e;
        }
    }

    public function delete(int $id): bool
    {
        $sql = 'DELETE FROM filiere WHERE id = :id';
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), ['method' => __METHOD__, 'sql' => $sql, 'id' => $id]);
            throw $e;
        }
    }

    public function findById(int $id): ?array
    {
        $sql = 'SELECT id, code, libelle FROM filiere WHERE id = :id';
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch();
            return $row ?: null;
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), ['method' => __METHOD__, 'sql' => $sql, 'id' => $id]);
            throw $e;
        }
    }

    public function findAll(): array
    {
        $sql = 'SELECT id, code, libelle FROM filiere ORDER BY id ASC';
        try {
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            $this->logger->error($e->getMessage(), ['method' => __METHOD__, 'sql' => $sql]);
            throw $e;
        }
    }
}