<?php

namespace app\models;

use PDO;

class Besoin
{
    public $id;
    public ?Ville $ville;          // Objet Ville
    public ?Dons $don;             // Objet Dons
    public $qte;
    public $daty;

    public function __construct($id = null, ?Ville $ville = null, ?Dons $don = null, $qte = null, $daty = null)
    {
        $this->id = $id;
        $this->ville = $ville;
        $this->don = $don;
        $this->qte = $qte;
        $this->daty = $daty;
    }

    public static function getNonSatisfaitsByType($db, int $idDon): array
    {
        $sql = "SELECT * FROM gd_besoinVille
                WHERE qte > 0 AND idDon = :idDon
                ORDER BY daty ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute([':idDon' => $idDon]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $besoins = [];
        foreach ($rows as $row) {
            $besoins[] = new Besoin(
                $row['id'],
                Ville::getById($db, $row['idVille']),
                Dons::getById($db, $row['idDon']),
                $row['qte'],
                $row['daty']
            );
        }
        return $besoins;
    }

    public function insert($db): bool
    {
        $sql = "INSERT INTO gd_besoinVille (idVille, idDon, qte, daty)
                VALUES (:idVille, :idDon, :qte, :daty)";
        
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':idVille'  => $this->ville?->id,
            ':idDon'    => $this->don?->id,
            ':qte'      => $this->qte,
            ':daty'     => $this->daty
        ]);
    }

    public static function getNonSatisfaits($db): array
    {
        $sql = "SELECT * FROM gd_besoinVille WHERE qte > 0 ORDER BY daty ASC";
        $stmt = $db->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $besoins = [];
        foreach ($rows as $row) {
            $besoins[] = new Besoin(
                $row['id'],
                Ville::getById($db, $row['idVille']),
                Dons::getById($db, $row['idDon']),
                $row['qte'],
                $row['daty']
            );
        }
        return $besoins;
    }

    public static function getSatisfaits($db): array
    {
        $sql = "SELECT * FROM gd_besoinVille WHERE qte <= 0 ORDER BY daty ASC";
        $stmt = $db->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $besoins = [];
        foreach ($rows as $row) {
            $besoins[] = new Besoin(
                $row['id'],
                Ville::getById($db, $row['idVille']),
                Dons::getById($db, $row['idDon']),
                $row['qte'],
                $row['daty']
            );
        }
        return $besoins;
    }

    public static function getAll($db): array
    {
        $sql = "SELECT * FROM gd_besoinVille ORDER BY daty DESC";
        $stmt = $db->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $besoins = [];
        foreach ($rows as $row) {
            $besoins[] = new Besoin(
                $row['id'],
                Ville::getById($db, $row['idVille']),
                Dons::getById($db, $row['idDon']),
                $row['qte'],
                $row['daty']
            );
        }
        return $besoins;
    }

    public static function getById($db, $id): ?Besoin
    {
        $sql = "SELECT * FROM gd_besoinVille WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Besoin(
                $row['id'],
                Ville::getById($db, $row['idVille']),
                Dons::getById($db, $row['idDon']),
                $row['qte'],
                $row['daty']
            );
        }
        return null;
    }

    public function update($db): bool
    {
        $sql = "UPDATE gd_besoinVille 
                SET idVille = :idVille,
                    idDon = :idDon,
                    qte = :qte,
                    daty = :daty
                WHERE id = :id";

        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':idVille'  => $this->ville?->id,
            ':idDon'    => $this->don?->id,
            ':qte'      => $this->qte,
            ':daty'     => $this->daty,
            ':id'       => $this->id
        ]);
    }

    public static function delete($db, $id): bool
    {
        $sql = "DELETE FROM gd_besoinVille WHERE id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}