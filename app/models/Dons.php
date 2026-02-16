<?php

namespace app\models;

use PDO;

class Dons
{
    public $id_dons;
    public ?Ville $ville; //id_ville
    public ?TypeBesoin $types_besoin; //id_types
    public $quantite;
    public $date_reception;

    public function __construct($id_dons = null, ?Ville $ville = null, ?TypeBesoin $types_besoin = null, $quantite = null, $date_reception = null)
    {
        $this->id_dons = $id_dons;
        $this->ville = $ville;
        $this->types_besoin = $types_besoin;
        $this->quantite = $quantite;
        $this->date_reception = $date_reception;
    }

    public function insert($db): bool
    {
        $sql = "INSERT INTO gd_dons (id_ville, id_types, quantite, date_reception)
            VALUES (:ville, :types_besoin, :quantite, :date_reception)";

        $stmt = $db->prepare($sql);

        return $stmt->execute([
            ':ville' => $this->ville?->id_ville,
            ':types_besoin' => $this->types_besoin?->id_types,
            ':quantite' => $this->quantite,
            ':date_reception' => $this->date_reception
        ]);
    }

    public static function getAll($db): array
    {
        $sql = "SELECT * FROM gd_dons ORDER BY date_reception ASC";
        $stmt = $db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($db, $id): ?Dons
    {
        $sql = "SELECT * FROM gd_dons WHERE id_dons = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Dons(
                $row['id_dons'],
                Ville::getById($db, $row['id_ville']),
                TypeBesoin::getById($db, $row['id_types']),
                $row['quantite'],
                $row['date_reception']
            );
        }

        return null;
    }

    public function update($db): bool
    {
        $sql = "UPDATE gd_dons 
            SET id_ville = :ville,
                id_types = :types_besoin,
                quantite = :quantite,
                date_reception = :date_reception
            WHERE id_dons = :id_dons";

        $stmt = $db->prepare($sql);

        return $stmt->execute([
            ':ville' => $this->ville?->id_ville,
            ':types_besoin' => $this->types_besoin?->id_types,
            ':quantite' => $this->quantite,
            ':date_reception' => $this->date_reception,
            ':id_dons' => $this->id_dons
        ]);
    }

    public static function delete($db, $id): bool
    {
        $sql = "DELETE FROM gd_dons WHERE id_dons = :id";
        $stmt = $db->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }



}
