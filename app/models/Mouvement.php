<?php

namespace app\models;

use PDO;

class Mouvement
{
    public $id_mvstock;
    public ?Besoin $besoin;
    public ?Stock $stock;
    public $entree;
    public $sortie;
    public $date_attribution;
    public $designation;

    public function __construct($id_mvstock = null, ?Besoin $besoin = null, ?Stock $stock = null, $entree = null, $sortie = null, $date_attribution = null, $designation = null)
    {
        $this->id_mvstock = $id_mvstock;
        $this->besoin = $besoin;
        $this->stock = $stock;
        $this->entree = $entree;
        $this->sortie = $sortie;
        $this->date_attribution = $date_attribution;
        $this->designation = $designation;
    }

    public function insert($db): bool
    {
        $sql = "INSERT INTO gd_mvstock (id_besoin, id_stock, entree, sortie, date_attribution, designation)
                VALUES (:id_besoin, :id_stock, :entree, :sortie, :date_attribution, :designation)";

        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':id_besoin' => $this->besoin?->id_besoin,
            ':id_stock' => $this->stock?->id_stock,
            ':entree' => $this->entree,
            ':sortie' => $this->sortie,
            ':date_attribution' => $this->date_attribution,
            ':designation' => $this->designation
        ]);
    }

     public function insertentre($db, $data): bool
    {
        $sql = "INSERT INTO gd_mvstock (id_besoin, id_stock, entree, sortie, date_attribution, designation)
                VALUES (:id_besoin, :id_stock, :entree, :sortie, :date_attribution, :designation)";

        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':id_besoin' => $data['id_besoin'],
            ':id_stock' => $data['id_stock'],
            ':entree' => $data['entree'],
            ':sortie' => 0,
            ':date_attribution' => $data['date_attribution'],
            ':designation' => $data['designation']
        ]);
    }

     public function insertsortie($db, $data): bool
    {
        $sql = "INSERT INTO gd_mvstock (id_besoin, id_stock, entree, sortie, date_attribution, designation)
                VALUES (:id_besoin, :id_stock, :entree, :sortie, :date_attribution, :designation)";

        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':id_besoin' => $data['id_besoin'],
            ':id_stock' => $data['id_stock'],
            ':entree' => 0,
            ':sortie' => $data['sortie'],
            ':date_attribution' => $data['date_attribution'],
            ':designation' => $data['designation']
        ]);
    }

    public static function getAll($db): array
    {
        $sql = "SELECT * FROM gd_mvstock ORDER BY date_attribution DESC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($db, $id): ?Mouvement
    {
        $sql = "SELECT * FROM gd_mvstock WHERE id_mvstock = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Mouvement(
                $row['id_mvstock'],
                Besoin::getById($db, $row['id_besoin']),
                Stock::getById($db, $row['id_stock']),
                $row['entree'],
                $row['sortie'],
                $row['date_attribution'],
                $row['designation']
            );
        }
        return null;
    }

    public function update($db): bool
    {
        $sql = "UPDATE gd_mvstock 
                SET id_besoin = :id_besoin,
                    id_stock = :id_stock,
                    entree = :entree,
                    sortie = :sortie,
                    date_attribution = :date_attribution,
                    designation = :designation
                WHERE id_mvstock = :id_mvstock";

        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':id_besoin' => $this->besoin?->id_besoin,
            ':id_stock' => $this->stock?->id_stock,
            ':entree' => $this->entree,
            ':sortie' => $this->sortie,
            ':date_attribution' => $this->date_attribution,
            ':designation' => $this->designation,
            ':id_mvstock' => $this->id_mvstock
        ]);
    }

    public static function delete($db, $id): bool
    {
        $sql = "DELETE FROM gd_mvstock WHERE id_mvstock = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}