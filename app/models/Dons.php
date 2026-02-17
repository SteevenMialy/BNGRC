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

    public function insertWithId($db): bool
    {
        $sql = "";
        $params = [];
        if ($this->daty != null) {
            $sql = "INSERT INTO gd_dons (id, libelle, pu, idTypes, daty)
                VALUES (:id, :libelle, :pu, :idTypes, :daty)";
            $params = [
                ':id' => $this->id,
                ':libelle' => $this->libelle,
                ':pu' => $this->pu,
                ':idTypes' => $this->types_besoin?->id,
                ':daty' => $this->daty
            ];
        } else {
            $sql = "INSERT INTO gd_dons (id, libelle, pu, idTypes)
                VALUES (:id, :libelle, :pu, :idTypes)";
            $params = [
                ':id' => $this->id,
                ':libelle' => $this->libelle,
                ':pu' => $this->pu,
                ':idTypes' => $this->types_besoin?->id
            ];
        }

        $stmt = $db->prepare($sql);
        return $stmt->execute($params);
    }

    public static function cleanTalble($db): bool
    {
        $sql = "TRUNCATE TABLE gd_dons";
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
                // CSV attendu : libelle, pu, idTypes
                if (count($line) < 3) {
                    continue;
                }

                $libelle = trim($line[0]);
                $pu      = (float)$line[1];
                $idTypes = (int)$line[2];

                $daty = null;
                if (isset($line[3]) && trim($line[3]) !== '') {
                    $daty = trim($line[3]);
                }

                $don = new Dons(
                    $lineNumber,
                    $libelle,
                    $pu,
                    TypeBesoin::getById($db, $idTypes),
                    $daty
                );

                $don->insertWithId($db);

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

    public static function findByLibelle($db, $libelle): array
    {
        $sql = "SELECT * FROM gd_dons d WHERE d.libelle = :libelle ORDER BY daty ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute([':libelle' => $libelle]);

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
