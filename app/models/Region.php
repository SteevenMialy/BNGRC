<?php

namespace app\models;

use PDO;

class Region
{
    public $id_region;
    public $nom_region;

    public function __construct($id_region = null, $nom_region = null)
    {
        $this->id_region = $id_region;
        $this->nom_region = $nom_region;
    }

    public function getIdregion()
    {
        return $this->id_region;
    }

    public function getNomRegion()
    {
        return $this->nom_region;
    }

    public function setNomRegion($nom_region)
    {
        $this->nom_region = $nom_region;
    }

    public function insert($db): bool
    {
        $sql = "INSERT INTO region (nom_region)
                VALUES (:nom_region)";
        
        $stmt = $db->prepare($sql);

        return $stmt->execute([
            ':nom_region' => $this->nom_region
        ]);
    }

    public static function getAll($db): array
    {
        $sql = "SELECT * FROM region ORDER BY id_region DESC";
        $stmt = $db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($db, $id): ?Region
    {
        $sql = "SELECT * FROM region WHERE id_region = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Region(
                $row['id_region'],
                $row['nom_region']
            );
        }

        return null;
    }

    public function update($db): bool
    {
        $sql = "UPDATE region 
                SET nom_region = :nom_region
                WHERE id_region = :id_region";

        $stmt = $db->prepare($sql);

        return $stmt->execute([
            ':nom_region' => $this->nom_region,
            ':id_region' => $this->id_region
        ]);
    }

    public static function delete($db, $id): bool
    {
        $sql = "DELETE FROM region WHERE id_region = :id";
        $stmt = $db->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }
}
