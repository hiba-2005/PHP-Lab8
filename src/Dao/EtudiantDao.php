<?php
declare(strict_types=1);

namespace App\Dao;

use PDO;
use PDOException;

class EtudiantDao
{
    private PDO $pdo;
    private Logger $logger;

    public function __construct(PDO $pdo, Logger $logger)
    {
        $this->pdo = $pdo;
        $this->logger = $logger;
    }

    public function countSearch(?string $q, ?int $filiereId): int
    {
        $where = [];
        $params = [];

        if ($q !== null && $q !== '') {
            $where[] = '(e.cne LIKE ? OR e.nom LIKE ? OR e.prenom LIKE ? OR e.email LIKE ?)';
            $like = '%' . $q . '%';
            array_push($params, $like, $like, $like, $like);
        }

        if ($filiereId !== null && $filiereId > 0) {
            $where[] = 'e.filiere_id = ?';
            $params[] = $filiereId;
        }

        $sql = 'SELECT COUNT(*) c FROM etudiant e' . (count($where) ? ' WHERE ' . implode(' AND ', $where) : '');

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch();

        return (int)($row['c'] ?? 0);
    }

    public function searchPaginated(?string $q, ?int $filiereId, int $page, int $size): array
    {
        $page = max(1, $page);
        $size = max(1, min(100, $size));
        $offset = ($page - 1) * $size;

        $where = [];
        $params = [];

        if ($q !== null && $q !== '') {
            $where[] = '(e.cne LIKE ? OR e.nom LIKE ? OR e.prenom LIKE ? OR e.email LIKE ?)';
            $like = '%' . $q . '%';
            array_push($params, $like, $like, $like, $like);
        }

        if ($filiereId !== null && $filiereId > 0) {
            $where[] = 'e.filiere_id = ?';
            $params[] = $filiereId;
        }

        $sql = 'SELECT e.id, e.cne, e.nom, e.prenom, e.email, e.filiere_id,
                       f.code AS filiere_code, f.libelle AS filiere_libelle
                FROM etudiant e
                JOIN filiere f ON e.filiere_id = f.id'
                . (count($where) ? ' WHERE ' . implode(' AND ', $where) : '')
                . ' ORDER BY e.id DESC LIMIT ? OFFSET ?';

        $stmt = $this->pdo->prepare($sql);

        // bind filtres
        foreach ($params as $i => $val) {
            $stmt->bindValue($i + 1, $val, is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }

        // bind limit/offset en INT (obligatoire)
        $stmt->bindValue(count($params) + 1, $size, PDO::PARAM_INT);
        $stmt->bindValue(count($params) + 2, $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
{
    $sql = 'SELECT e.id, e.cne, e.nom, e.prenom, e.email, e.filiere_id,
                   f.code AS filiere_code, f.libelle AS filiere_libelle
            FROM etudiant e
            JOIN filiere f ON e.filiere_id = f.id
            WHERE e.id = ?';

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$id]);
    $row = $stmt->fetch();

    return $row ?: null;
}
    
}