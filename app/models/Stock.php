<?php

namespace app\models;

use PDO;

class Stock
{
    public $id;
    public ?Dons $dons; // Objet Dons
    public $qte;
    public $daty;

    public function __construct($id = null, ?Dons $dons = null, $qte = null, $daty = null)
    {
        $this->id = $id;
        $this->dons = $dons;
        $this->qte = $qte;
        $this->daty = $daty;
    }

    public function insert($db): bool
    {
        $sql = "INSERT INTO gd_stock (idDon, qte, daty)
                VALUES (:idDon, :qte, :daty)";

        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':idDon' => $this->dons?->id,
            ':qte' => $this->qte,
            ':daty' => $this->daty
        ]);
    }

    public static function getAll($db): array
    {
        $sql = "SELECT * FROM gd_stock ORDER BY daty DESC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($db, $id): ?Stock
    {
        $sql = "SELECT * FROM gd_stock WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Stock(
                $row['id'],
                Dons::getById($db, $row['idDon']),
                $row['qte'],
                $row['daty']
            );
        }
        return null;
    }

    public function update($db): bool
    {
        $sql = "UPDATE gd_stock 
                SET idDon = :idDon,
                    qte = :qte,
                    daty = :daty
                WHERE id = :id";

        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':idDon' => $this->dons?->id,
            ':qte' => $this->qte,
            ':daty' => $this->daty,
            ':id' => $this->id
        ]);
    }

    public static function delete($db, $id): bool
    {
        $sql = "DELETE FROM gd_stock WHERE id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public static function dons_ayant_stock ($db) {
        $sql = "SELECT * FROM gd_dons d JOIN gd_stock s ON d.id=s.idDon WHERE s.qte>0 ORDER BY s.daty ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stocks = [];
        foreach ($rows as $row) {
            $stocks[] = new Stock(
                $row['id'],
                Dons::getById($db, $row['idDon']),
                $row['qte'],
                $row['daty']
            );
        }
        return $stocks;
    }
}


