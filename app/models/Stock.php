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

    public function insertWithId($db): bool
    {
        if ($this->daty !== null) {
            $sql = "INSERT INTO gd_stock (id, idDon, qte, daty)
                VALUES (:id, :idDon, :qte, :daty)";
            $params = [
                ':id'    => $this->id,
                ':idDon' => $this->dons?->id,
                ':qte'   => $this->qte,
                ':daty'  => $this->daty
            ];
        } else {
            $sql = "INSERT INTO gd_stock (id, idDon, qte)
                VALUES (:id, :idDon, :qte)";
            $params = [
                ':id'    => $this->id,
                ':idDon' => $this->dons?->id,
                ':qte'   => $this->qte
            ];
        }

        $stmt = $db->prepare($sql);
        return $stmt->execute($params);
    }

    public static function cleanTable($db): bool
    {
        $sql = "TRUNCATE TABLE gd_stock";
        $stmt = $db->prepare($sql);
        return $stmt->execute();
    }

    public static function insertDataFile($db, string $path): bool
    {
        if (!file_exists($path)) {
            return false;
        }

        $handle = fopen($path, 'r');
        if (!$handle) {
            return false;
        }

        $db->beginTransaction();

        try {
            $lineNumber = 1;

            while (($line = fgetcsv($handle, 0, ',')) !== false) {

                // CSV attendu : idDon, qte [, daty]
                if (count($line) < 2) {
                    continue;
                }

                $idDon = (int)$line[0];
                $qte   = (float)$line[1];

                $daty = null;
                if (isset($line[2]) && trim($line[2]) !== '') {
                    $daty = trim($line[2]);
                }

                $don = Dons::getById($db, $idDon);
                if ($don === null) {
                    continue; // sécurité : don inexistant
                }

                $stock = new Stock(
                    $lineNumber,
                    $don,
                    $qte,
                    $daty
                );

                $stock->insertWithId($db);

                $lineNumber++;
            }

            fclose($handle);
            $db->commit();
            return true;
        } catch (\Exception $e) {
            $db->rollBack();
            fclose($handle);
            return false;
        }
    }

    public static function Donsargent($db)
    {
        $sql = "SELECT sum(s.qte) as total_argent FROM gd_dons d JOIN gd_stock s ON d.id=s.idDon WHERE d.idTypes=3 AND s.qte>0 ORDER BY s.daty ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
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

    public static function dons_ayant_stock($db)
    {
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
