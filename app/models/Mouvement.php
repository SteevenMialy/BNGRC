<?php

namespace app\models;

use PDO;

class Mouvement
{
    public $id;
    public ?Besoin $besoin;
    public ?Stock $stock;
    public $entree;
    public $sortie;
    public $daty;
    public $designation;

    public function __construct($id = null, ?Besoin $besoin = null, ?Stock $stock = null, $entree = null, $sortie = null, $daty = null, $designation = null)
    {
        $this->id = $id;
        $this->besoin = $besoin;
        $this->stock = $stock;
        $this->entree = $entree;
        $this->sortie = $sortie;
        $this->daty = $daty;
        $this->designation = $designation;
    }

    public function livraison($db, $data)
    {
        $stock = [];
        foreach ($data as $key => $value) {
            $sql = "UPDATE gd_stock SET qte = qte - :sortie WHERE id = :id";
            $stmt2 = $db->prepare($sql);
            $stmt2->execute([
                ':sortie' => $value['sortie'],
                ':id' => $value['id']
            ]);
            $stock[] = $stmt2->rowCount();
        }
        if (count($data) > 0 && count($stock) > 0) {
            return true;
        }
        return false;
    }

    public function getmontantsatisfait($db){
        $sql = "SELECT SUM(s.sortie * d.pu) as montant
                FROM gd_mvstock s
                JOIN gd_stock st ON s.idstock = st.id
                JOIN gd_dons d ON st.idDon = d.id
                WHERE s.sortie > 0";
        $stmt = $db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['montant'] ?? 0;
    }

    public function insertentre($db, $data): bool
    {
        $sql = "INSERT INTO gd_mvstock (idbesoin, idstock, entree, sortie, daty, designation)
                VALUES (:idbesoin, :idstock, :entree, :sortie, :daty, :designation)";

        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':idbesoin' => $data['id_besoin'] ?? $data['idbesoin'],
            ':idstock' => $data['id_stock'] ?? $data['idstock'],
            ':entree' => $data['entree'],
            ':sortie' => 0,
            ':daty' => $data['date_attribution'] ?? $data['daty'] ?? date('Y-m-d H:i:s'),
            ':designation' => $data['designation']
        ]);
    }

    public static function cleanTable($db): bool
    {
        $sql = "TRUNCATE TABLE gd_mvstock";
        $stmt = $db->prepare($sql);
        return $stmt->execute();
    }


    public function insertsortie($db, $data): bool
    {
        $sql = "INSERT INTO gd_mvstock (idbesoin, idstock, entree, sortie, daty, designation)
                VALUES (:idbesoin, :idstock, :entree, :sortie, :daty, :designation)";

        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':idbesoin' => $data['id_besoin'] ?? $data['idbesoin'],
            ':idstock' => $data['id_stock'] ?? $data['idstock'],
            ':entree' => 0,
            ':sortie' => $data['sortie'],
            ':daty' => $data['date_attribution'] ?? $data['daty'] ?? date('Y-m-d H:i:s'),
            ':designation' => $data['designation']
        ]);
    }

    public function insert($db): bool
    {
        $sql = "INSERT INTO gd_mvstock (idbesoin, idstock, entree, sortie, daty, designation)
                VALUES (:idbesoin, :idstock, :entree, :sortie, :daty, :designation)";

        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':idbesoin' => $this->besoin?->id,
            ':idstock' => $this->stock?->id,
            ':entree' => $this->entree,
            ':sortie' => $this->sortie,
            ':daty' => $this->daty,
            ':designation' => $this->designation
        ]);
    }



    public static function getAll($db): array
    {
        $sql = "SELECT * FROM gd_mvstock ORDER BY daty DESC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($db, $id): ?Mouvement
    {
        $sql = "SELECT * FROM gd_mvstock WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Mouvement(
                $row['id'],
                Besoin::getById($db, $row['idbesoin']),
                Stock::getById($db, $row['idstock']),
                $row['entree'],
                $row['sortie'],
                $row['daty'],
                $row['designation']
            );
        }
        return null;
    }

    public function update($db): bool
    {
        $sql = "UPDATE gd_mvstock 
                SET idbesoin = :idbesoin,
                    idstock = :idstock,
                    entree = :entree,
                    sortie = :sortie,
                    daty = :daty,
                    designation = :designation
                WHERE id = :id";

        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':idbesoin' => $this->besoin?->id,
            ':idstock' => $this->stock?->id,
            ':entree' => $this->entree,
            ':sortie' => $this->sortie,
            ':daty' => $this->daty,
            ':designation' => $this->designation,
            ':id' => $this->id
        ]);
    }

    public static function delete($db, $id): bool
    {
        $sql = "DELETE FROM gd_mvstock WHERE id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
