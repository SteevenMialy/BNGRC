<?php

namespace app\models;

use PDO;

class Ville
{
    public $id_ville;
    public ?Region $region;      // = id_region
    public $nom_ville;

    public function __construct($id_ville = null, ?Region $region = null, $nom_ville = null)
    {
        $this->id_ville = $id_ville;
        $this->region = $region;
        $this->nom_ville = $nom_ville;
    }

    public function insert($db): bool
    {
        $sql = "INSERT INTO gd_villes (id_region, nom_ville)
                VALUES (:region, :nom_ville)";
        
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':region' => $this->region->getIdregion(),
            ':nom_ville' => $this->nom_ville
        ]);
    }

    public static function getAll($db): array
    {
        $sql = "SELECT * FROM gd_villes ORDER BY id_ville DESC";
        $stmt = $db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($db, $id): ?Ville
    {
        $sql = "SELECT * FROM gd_villes WHERE id_ville = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Ville(
                $row['id_ville'],
                Region::getById($db, $row['id_region']),
                $row['nom_ville']
            );
        }

        return null;
    }

    public function update($db): bool
    {
        $sql = "UPDATE gd_villes
                SET id_region = :region,
                    nom_ville = :nom_ville
                WHERE id_ville = :id_ville";

        $stmt = $db->prepare($sql);

        return $stmt->execute([
            ':region' => $this->region->getIdregion(),
            ':nom_ville' => $this->nom_ville,
            ':id_ville' => $this->id_ville
        ]);
    }

    public static function delete($db, $id): bool
    {
        $sql = "DELETE FROM gd_villes WHERE id_ville = :id";
        $stmt = $db->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }
}
