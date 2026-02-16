<?php

namespace app\models;

use PDO;

class Dons
{
    public $id;
    public $libelle;
    public $pu;
    public ?TypeBesoin $types_besoin; //idTypes
    public $daty;

    public function __construct($id = null, $libelle = null, $pu = null, ?TypeBesoin $types_besoin = null, $daty = null)
    {
        $this->id = $id;
        $this->libelle = $libelle;
        $this->pu = $pu;
        $this->types_besoin = $types_besoin;
        $this->daty = $daty;
    }

    public function insert($db): bool
    {
        $sql = "INSERT INTO gd_dons (libelle, pu, idTypes, daty)
            VALUES (:libelle, :pu, :idTypes, :daty)";

        $stmt = $db->prepare($sql);

        return $stmt->execute([
            ':libelle' => $this->libelle,
            ':pu' => $this->pu,
            ':idTypes' => $this->types_besoin?->id,
            ':daty' => $this->daty
        ]);
    }

    public static function getAll($db): array
    {
        $sql = "SELECT * FROM gd_dons ORDER BY daty ASC";
        $stmt = $db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllSansArgent($db): array
    {
        $sql = "SELECT * FROM gd_dons d WHERE d.idTypes != 3 ORDER BY daty ASC";
        $stmt = $db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($db, $id): ?Dons
    {
        $sql = "SELECT * FROM gd_dons WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Dons(
                $row['id'],
                $row['libelle'],
                $row['pu'],
                TypeBesoin::getById($db, $row['idTypes']),
                $row['daty']
            );
        }   

        return null;
    }

    public function update($db): bool
    {
        $sql = "UPDATE gd_dons 
            SET libelle = :libelle,
                pu = :pu,
                idTypes = :idTypes,
                daty = :daty
            WHERE id = :id";

        $stmt = $db->prepare($sql);

        return $stmt->execute([
            ':libelle' => $this->libelle,
            ':pu' => $this->pu,
            ':idTypes' => $this->types_besoin?->id,
            ':daty' => $this->daty,
            ':id' => $this->id
        ]);
    }

    public static function delete($db, $id): bool
    {
        $sql = "DELETE FROM gd_dons WHERE id = :id";
        $stmt = $db->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }



}
