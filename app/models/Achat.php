<?php

namespace app\models;

use PDO;

class Achat
{
    public $id_achat;
    public ?Ville $ville;
    public ?Dons $dons;
    public $taux;
    public $quantite;
    public $date_achat;

    public function __construct($id_achat = null, ?Ville $ville = null, ?Dons $dons = null, $taux = null, $quantite = null, $date_achat = null)
    {
        $this->id_achat = $id_achat;
        $this->ville = $ville;
        $this->dons = $dons;
        $this->taux = $taux;
        $this->quantite = $quantite;
        $this->date_achat = $date_achat;
    }



    public function insert($db): bool
    {
        $sql = "INSERT INTO gd_achat (idVille, idDons, taux, quantite, date_achat)
                VALUES (:idVille, :idDons, :taux, :quantite, :date_achat)";

        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':idVille' => $this->ville?->id,
            ':idDons' => $this->dons?->id,
            ':taux' => $this->taux,
            ':quantite' => $this->quantite,
            ':date_achat' => $this->date_achat
        ]);
    }

    public function valider($db, $id_achat): bool
    {
        try {
            $db->beginTransaction();

            $achat = self::getAchatRow($db, $id_achat);
            if (!$achat) {
                $db->rollBack();
                return false;
            }

            $sqlStock = "SELECT id_stock FROM gd_stock WHERE id_don = :id_don LIMIT 1";
            $stmtStock = $db->prepare($sqlStock);
            $stmtStock->execute([':id_don' => $achat['idDons']]);
            $stockRow = $stmtStock->fetch(PDO::FETCH_ASSOC);

            if ($stockRow) {
                $sqlUpdate = "UPDATE gd_stock SET quantite = quantite + :quantite WHERE id_stock = :id_stock";
                $stmtUpdate = $db->prepare($sqlUpdate);
                $stmtUpdate->execute([
                    ':quantite' => $achat['quantite'],
                    ':id_stock' => $stockRow['id_stock']
                ]);
            } else {
                $sqlInsert = "INSERT INTO gd_stock (id_don, quantite, date_reception)
                              VALUES (:id_don, :quantite, :date_reception)";
                $stmtInsert = $db->prepare($sqlInsert);
                $stmtInsert->execute([
                    ':id_don' => $achat['idDons'],
                    ':quantite' => $achat['quantite'],
                    ':date_reception' => date('Y-m-d H:i:s')
                ]);
            }

            $db->commit();
            return true;
        } catch (\Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            return false;
        }
    }

    public static function lastinsertId($db): int
    {
        return $db->lastInsertId();
    }

    public static function distribuer($db, $id_achat): bool
    {
        try {
            $db->beginTransaction();

            $achat = self::getAchatRow($db, $id_achat);
            if (!$achat) {
                $db->rollBack();
                return false;
            }

            $sqlStock = "SELECT id_stock, quantite FROM gd_stock WHERE id_don = :id_don LIMIT 1";
            $stmtStock = $db->prepare($sqlStock);
            $stmtStock->execute([':id_don' => $achat['idDons']]);
            $stock = $stmtStock->fetch(PDO::FETCH_ASSOC);

            if (!$stock || (float)$stock['quantite'] < (float)$achat['quantite']) {
                $db->rollBack();
                return false;
            }

            $sqlSortieStock = "UPDATE gd_stock SET quantite = quantite - :quantite WHERE id_stock = :id_stock";
            $stmtSortieStock = $db->prepare($sqlSortieStock);
            $stmtSortieStock->execute([
                ':quantite' => $achat['quantite'],
                ':id_stock' => $stock['id_stock']
            ]);

            $okBesoin = self::comblerBesoinsVille($db, $achat);
            if (!$okBesoin) {
                $db->rollBack();
                return false;
            }

            $db->commit();
            return true;
        } catch (\Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            return false;
        }
    }

    public function insertData($db, $data): bool
    {
        $sql = "INSERT INTO gd_achat (idVille, idDons, taux, quantite, date_achat)
                VALUES (:idVille, :idDons, :taux, :quantite, :date_achat)";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':idVille' => $data['idVille'],
            ':idDons' => $data['idDons'],
            ':taux' => $data['taux'],
            ':quantite' => $data['quantite'],
            ':date_achat' => $data['date_achat'] ?? date('Y-m-d H:i:s')
        ]);
        return $db->lastInsertId() > 0;
    }

    private static function getAchatRow($db, $id_achat): ?array
    {
        $sql = "SELECT * FROM gd_achat WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id_achat]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    private static function columnExists($db, $table, $column): bool
    {
        $sql = "SELECT COUNT(*) AS c
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_SCHEMA = DATABASE()
                  AND TABLE_NAME = :table
                  AND COLUMN_NAME = :column";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':table' => $table,
            ':column' => $column
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return isset($row['c']) && (int)$row['c'] > 0;
    }

    private static function comblerBesoinsVille($db, array $achat): bool
    {
        if (!self::columnExists($db, 'gd_besoins_ville', 'quantite_demandee')) {
            return true;
        }

        $idCol = self::columnExists($db, 'gd_besoins_ville', 'id_besoin') ? 'id_besoin' : (self::columnExists($db, 'gd_besoins_ville', 'id') ? 'id' : null);
        $villeCol = self::columnExists($db, 'gd_besoins_ville', 'id_ville') ? 'id_ville' : (self::columnExists($db, 'gd_besoins_ville', 'idVille') ? 'idVille' : null);
        $dateCol = self::columnExists($db, 'gd_besoins_ville', 'date_demande') ? 'date_demande' : (self::columnExists($db, 'gd_besoins_ville', 'daty') ? 'daty' : null);

        if (!$idCol || !$villeCol || !$dateCol) {
            return false;
        }

        $typeColInBesoin = null;
        $typeValue = null;

        if (self::columnExists($db, 'gd_besoins_ville', 'id_types')) {
            $typeColInBesoin = 'id_types';
            $typeValue = self::getTypeFromDons($db, $achat['idDons']);
            if ($typeValue === null) {
                return false;
            }
        } elseif (self::columnExists($db, 'gd_besoins_ville', 'idDon')) {
            $typeColInBesoin = 'idDon';
            $typeValue = $achat['idDons'];
        } else {
            return false;
        }

        $reste = (float)$achat['quantite'];

        while ($reste > 0) {
            $sqlBesoin = "SELECT {$idCol} AS besoin_id, quantite_demandee
                         FROM gd_besoins_ville
                         WHERE {$villeCol} = :idVille
                           AND {$typeColInBesoin} = :typeValue
                           AND quantite_demandee > 0
                         ORDER BY {$dateCol} ASC
                         LIMIT 1";

            $stmtBesoin = $db->prepare($sqlBesoin);
            $stmtBesoin->execute([
                ':idVille' => $achat['idVille'],
                ':typeValue' => $typeValue
            ]);
            $besoin = $stmtBesoin->fetch(PDO::FETCH_ASSOC);

            if (!$besoin) {
                break;
            }

            $qteBesoin = (float)$besoin['quantite_demandee'];
            $qteServie = min($qteBesoin, $reste);

            $sqlUpdateBesoin = "UPDATE gd_besoins_ville
                                SET quantite_demandee = quantite_demandee - :qte
                                WHERE {$idCol} = :id";
            $stmtUpdateBesoin = $db->prepare($sqlUpdateBesoin);
            $stmtUpdateBesoin->execute([
                ':qte' => $qteServie,
                ':id' => $besoin['besoin_id']
            ]);

            $reste -= $qteServie;
        }

        return true;
    }

    private static function getTypeFromDons($db, $idDons): ?int
    {
        if (self::columnExists($db, 'gd_dons', 'id_types')) {
            $sql = "SELECT id_types AS id_type FROM gd_dons WHERE id_don = :id LIMIT 1";
        } elseif (self::columnExists($db, 'gd_dons', 'idTypes')) {
            $sql = "SELECT idTypes AS id_type FROM gd_dons WHERE id = :id LIMIT 1";
        } else {
            return null;
        }

        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $idDons]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? (int)$row['id_type'] : null;
    }


    public static function getAll($db): array
    {
        $sql = "SELECT * FROM gd_achat ORDER BY date_achat DESC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($db, $id): ?Achat
    {
        $sql = "SELECT * FROM gd_achat WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Achat(
                $row['id'],
                Ville::getById($db, $row['idVille']),
                Dons::getById($db, $row['idDons']),
                $row['taux'],
                $row['quantite'],
                $row['date_achat']
            );
        }

        return null;
    }

    public function update($db): bool
    {
        $sql = "UPDATE gd_achat
                SET idVille = :idVille,
                    idDons = :idDons,
                    taux = :taux,
                    quantite = :quantite,
                    date_achat = :date_achat
                WHERE id = :id";

        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':idVille' => $this->ville?->id,
            ':idDons' => $this->dons?->id,
            ':taux' => $this->taux,
            ':quantite' => $this->quantite,
            ':date_achat' => $this->date_achat,
            ':id' => $this->id_achat
        ]);
    }

    public function updateData($db, $id, $data): bool
    {
        $sql = "UPDATE gd_achat
                SET idVille = :idVille,
                    idDons = :idDons,
                    taux = :taux,
                    quantite = :quantite,
                    date_achat = :date_achat
                WHERE id = :id";

        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':idVille' => $data['idVille'],
            ':idDons' => $data['idDons'],
            ':taux' => $data['taux'],
            ':quantite' => $data['quantite'],
            ':date_achat' => $data['date_achat'] ?? date('Y-m-d H:i:s'),
            ':id' => $id
        ]);
    }

    public static function delete($db, $id): bool
    {
        $sql = "DELETE FROM gd_achat WHERE id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
