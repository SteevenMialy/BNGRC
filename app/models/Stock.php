<?php

namespace app\models;

use PDO;

class Stock
{
    public $id_stock;
    public ?Dons $dons; // Objet Dons
    public $quantite;
    public $date_reception;

    public function __construct($id_stock = null, ?Dons $dons = null, $quantite = null, $date_reception = null)
    {
        $this->id_stock = $id_stock;
        $this->dons = $dons;
        $this->quantite = $quantite;
        $this->date_reception = $date_reception;
    }


    public static function Donsargent($db)
    {
        $sql = "SELECT sum(s.quantite) as total_argent FROM gd_dons d JOIN gd_stock s ON d.id_don=s.id_don WHERE d.idTypes=3 AND s.quantite>0 ORDER BY s.date_reception ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }


    public function insert($db): bool
    {
        $sql = "INSERT INTO gd_stock (id_don, quantite, date_reception)
                VALUES (:id_don, :quantite, :date_reception)";

        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':id_don' => $this->dons?->id_dons,
            ':quantite' => $this->quantite,
            ':date_reception' => $this->date_reception
        ]);
    }

    public static function getAll($db): array
    {
        $sql = "SELECT * FROM gd_stock ORDER BY date_reception DESC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($db, $id): ?Stock
    {
        $sql = "SELECT * FROM gd_stock WHERE id_stock = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Stock(
                $row['id_stock'],
                Dons::getById($db, $row['id_don']), // On hydrate l'objet Dons
                $row['quantite'],
                $row['date_reception']
            );
        }
        return null;
    }

    public function update($db): bool
    {
        $sql = "UPDATE gd_stock 
                SET id_don = :id_don,
                    quantite = :quantite,
                    date_reception = :date_reception
                WHERE id_stock = :id_stock";

        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':id_don' => $this->dons?->id_dons,
            ':quantite' => $this->quantite,
            ':date_reception' => $this->date_reception,
            ':id_stock' => $this->id_stock
        ]);
    }

    public static function delete($db, $id): bool
    {
        $sql = "DELETE FROM gd_stock WHERE id_stock = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public static function dons_ayant_stock($db)
    {
        $sql = "SELECT * FROM gd_dons d JOIN gd_stock s ON d.id_don=s.id_don WHERE s.quantite>0 ORDER BY s.date_reception ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stocks = [];
        foreach ($rows as $row) {
            $stocks[] = new Stock(
                $row['id_stock'],
                Dons::getById($db, $row['id_don']), // On hydrate l'objet Dons
                $row['quantite'],
                $row['date_reception']
            );
        }
        return $stocks;
    }
}
