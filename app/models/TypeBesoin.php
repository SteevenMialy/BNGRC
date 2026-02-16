<?php

namespace app\models;

use PDO;

class TypeBesoin
{
   public $id;
   public $nom;
    public function __construct($id = null, $nom = null)
    {
        $this->id = $id;
        $this->nom = $nom;
    }

    public function insert($db): bool
    {
        $sql = "INSERT INTO gd_typesDons (nom)
                VALUES (:nom)";
        
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':nom' => $this->nom
        ]);
    }

    public static function getAll($db): array
    {
        $sql = "SELECT * FROM gd_typesDons ORDER BY id DESC";
        $stmt = $db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($db, $id): ?TypeBesoin
    {
        $sql = "SELECT * FROM gd_typesDons WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new TypeBesoin(
                $row['id'],
                $row['nom']
            );
        }

        return null;
    }

    public function update($db): bool
    {
        $sql = "UPDATE gd_typesDons
                SET nom = :nom
                WHERE id = :id";

        $stmt = $db->prepare($sql);

        return $stmt->execute([
            ':nom' => $this->nom,
            ':id' => $this->id
        ]);
    }

    public static function delete($db, $id): bool
    {
        $sql = "DELETE FROM gd_typesDons WHERE id = :id";
        $stmt = $db->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }
}
