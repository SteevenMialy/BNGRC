<?php

namespace app\models;

use PDO;

class Besoin
{
    public $id_besoin;
    public ?Ville $ville;          // Objet Ville
    public ?TypeBesoin $types_besoin; // Objet TypeBesoin
    public $quantite_demandee;
    public $date_demande;

    public function __construct($id_besoin = null, ?Ville $ville = null, ?TypeBesoin $types_besoin = null, $quantite_demandee = null, $date_demande = null)
    {
        $this->id_besoin = $id_besoin;
        $this->ville = $ville;
        $this->types_besoin = $types_besoin;
        $this->quantite_demandee = $quantite_demandee;
        $this->date_demande = $date_demande;
    }

    public static function getNonSatisfaitsByType($db, int $idTypes): array
    {
        $sql = "SELECT * FROM gd_besoins_ville
                WHERE quantite_demandee > 0 AND id_types = :idTypes
                ORDER BY date_demande ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute([':idTypes' => $idTypes]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $besoins = [];
        foreach ($rows as $row) {
            $besoins[] = new Besoin(
                $row['id_besoin'],
                Ville::getById($db, $row['id_ville']),
                TypeBesoin::getById($db, $row['id_types']),
                $row['quantite_demandee'],
                $row['date_demande']
            );
        }
        return $besoins;
    }

    public function insert($db): bool
    {
        $sql = "INSERT INTO gd_besoins_ville (id_ville, id_types, quantite_demandee, date_demande)
                VALUES (:id_ville, :id_types, :quantite, :date_demande)";
        
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':id_ville'         => $this->ville?->id_ville,
            ':id_types'        => $this->types_besoin?->id_types,
            ':quantite'         => $this->quantite_demandee,
            ':date_demande'     => $this->date_demande
        ]);
    }

    public static function getNonSatisfaits($db): array
    {
        $sql = "SELECT * FROM gd_besoins_ville WHERE quantite_demandee > 0 ORDER BY date_demande ASC";
        $stmt = $db->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $besoins = [];
        foreach ($rows as $row) {
            $besoins[] = new Besoin(
                $row['id_besoin'],
                Ville::getById($db, $row['id_ville']),
                TypeBesoin::getById($db, $row['id_types']),
                $row['quantite_demandee'],
                $row['date_demande']
            );
        }
        return $besoins;
    }

    public static function getSatisfaits($db): array
    {
        $sql = "SELECT * FROM gd_besoins_ville WHERE quantite_demandee <= 0 ORDER BY date_demande ASC";
        $stmt = $db->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $besoins = [];
        foreach ($rows as $row) {
            $besoins[] = new Besoin(
                $row['id_besoin'],
                Ville::getById($db, $row['id_ville']),
                TypeBesoin::getById($db, $row['id_types']),
                $row['quantite_demandee'],
                $row['date_demande']
            );
        }
        return $besoins;
    }

    public static function getAll($db): array
    {
        $sql = "SELECT * FROM gd_besoins_ville ORDER BY date_demande DESC";
        $stmt = $db->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $besoins = [];
        foreach ($rows as $row) {
            $besoins[] = new Besoin(
                $row['id_besoin'],
                Ville::getById($db, $row['id_ville']),
                TypeBesoin::getById($db, $row['id_types']),
                $row['quantite_demandee'],
                $row['date_demande']
            );
        }
        return $besoins;
    }

    public static function getById($db, $id): ?Besoin
    {
        $sql = "SELECT * FROM gd_besoins_ville WHERE id_besoin = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Besoin(
                $row['id_besoin'],
                Ville::getById($db, $row['id_ville']),
                TypeBesoin::getById($db, $row['id_types']),
                $row['quantite_demandee'],
                $row['date_demande']
            );
        }
        return null;
    }

    public function update($db): bool
    {
        $sql = "UPDATE gd_besoins_ville 
                SET id_ville = :id_ville,
                    id_types = :id_types,
                    quantite_demandee = :quantite,
                    date_demande = :date_demande
                WHERE id_besoin = :id_besoin";

        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':id_ville'         => $this->ville?->id_ville,
            ':id_types'        => $this->types_besoin?->id_types,
            ':quantite'         => $this->quantite_demandee,
            ':date_demande'     => $this->date_demande,
            ':id_besoin'        => $this->id_besoin
        ]);
    }

    public static function delete($db, $id): bool
    {
        $sql = "DELETE FROM gd_besoins_ville WHERE id_besoin = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}