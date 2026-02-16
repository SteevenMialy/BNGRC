<?php

namespace app\models;

use PDO;

class Ville
{
    public $id;
    public ?Region $region;      // = idRegion
    public $nomVille;

    public function __construct($id = null, ?Region $region = null, $nomVille = null)
    {
        $this->id = $id;
        $this->region = $region;
        $this->nomVille = $nomVille;
    }

    public function insert($db): bool
    {
        $sql = "INSERT INTO gd_villes (idRegion, nomVille)
                VALUES (:region, :nomVille)";
        
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':region' => $this->region->getIdregion(),
            ':nomVille' => $this->nomVille
        ]);
    }

    public static function getAll($db): array
    {
        $sql = "SELECT * FROM gd_villes ORDER BY id DESC";
        $stmt = $db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($db, $id): ?Ville
    {
        $sql = "SELECT * FROM gd_villes WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Ville(
                $row['id'],
                Region::getById($db, $row['idRegion']),
                $row['nomVille']
            );
        }

        return null;
    }

    public function update($db): bool
    {
        $sql = "UPDATE gd_villes
                SET idRegion = :region,
                    nomVille = :nomVille
                WHERE id = :id";

        $stmt = $db->prepare($sql);

        return $stmt->execute([
            ':region' => $this->region->getIdregion(),
            ':nomVille' => $this->nomVille,
            ':id' => $this->id
        ]);
    }

    public static function delete($db, $id): bool
    {
        $sql = "DELETE FROM gd_villes WHERE id = :id";
        $stmt = $db->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }
}
