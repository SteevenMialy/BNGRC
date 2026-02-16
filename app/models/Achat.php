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
        $sql = "INSERT INTO gd_achat (idVille, idDon, taux, quantite, date_achat)
            VALUES (:idVille, :idDon, :taux, :quantite, :date_achat)";

        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':idVille' => $this->ville?->id,
            ':idDon' => $this->dons?->id,
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

            $sqlStock = "SELECT id FROM gd_stock WHERE idDon = :idDon LIMIT 1";
            $stmtStock = $db->prepare($sqlStock);
            $stmtStock->execute([':idDon' => $achat['idDons']]);
            $stockRow = $stmtStock->fetch(PDO::FETCH_ASSOC);

            if ($stockRow) {
                $sqlUpdate = "UPDATE gd_stock SET qte = qte + :quantite WHERE id = :id";
                $stmtUpdate = $db->prepare($sqlUpdate);
                $stmtUpdate->execute([
                    ':quantite' => $achat['quantite'],
                    ':id' => $stockRow['id']
                ]);
            } else {
                $sqlInsert = "INSERT INTO gd_stock (idDon, qte, daty)
                              VALUES (:idDon, :quantite, :daty)";
                $stmtInsert = $db->prepare($sqlInsert);
                $stmtInsert->execute([
                    ':idDon' => $achat['idDon'],
                    ':quantite' => $achat['quantite'],
                    ':daty' => date('Y-m-d H:i:s')
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

            $sqlStock = "SELECT id, qte FROM gd_stock WHERE idDon = :idDon LIMIT 1";
            $stmtStock = $db->prepare($sqlStock);
            $stmtStock->execute([':idDon' => $achat['idDons']]);
            $stock = $stmtStock->fetch(PDO::FETCH_ASSOC);

            if (!$stock || (float)$stock['qte'] < (float)$achat['quantite']) {
                $db->rollBack();
                return false;
            }

            $sqlSortieStock = "UPDATE gd_stock SET qte = qte - :quantite WHERE id = :id";
            $stmtSortieStock = $db->prepare($sqlSortieStock);
            $stmtSortieStock->execute([
                ':quantite' => $achat['quantite'],
                ':id' => $stock['id']
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
            VALUES (:idVille, :idDon, :taux, :quantite, :date_achat)";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':idVille' => $data['idVille'],
            ':idDon' => $data['idDons'],
            ':taux' => $data['taux'],
            ':quantite' => $data['quantite'],
            ':date_achat' => $data['date_achat'] ?? date('Y-m-d H:i:s')
        ]);
        return $db->lastInsertId() > 0;
    }

    private static function getAchatRow($db, $id_achat): ?array
    {
        $sql = "SELECT a.* FROM gd_achat a WHERE a.id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id_achat]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    private static function comblerBesoinsVille($db, array $achat): bool
    {
        $reste = (float)$achat['quantite'];
        $besoins = Besoin::getNonSatisfaitsByType($db, (int)$achat['idDons']);

        foreach ($besoins as $besoin) {
            if ($reste <= 0) {
                break;
            }

            if ((int)$besoin->ville?->id !== (int)$achat['idVille']) {
                continue;
            }

            $qteBesoin = (float)$besoin->qte;
            if ($qteBesoin <= 0) {
                continue;
            }

            $qteServie = min($qteBesoin, $reste);
            $besoin->qte = $qteBesoin - $qteServie;

            if (!$besoin->update($db)) {
                return false;
            }

            $reste -= $qteServie;
        }

        return true;
    }

    private static function getTypeFromDons($db, $idDon): ?int
    {
        $sql = "SELECT idTypes AS id_type FROM gd_dons WHERE id = :id LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $idDon]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['id_type'] : null;
    }


    public static function getAll($db): array
    {
        $sql = "SELECT a.* FROM gd_achat a ORDER BY a.date_achat DESC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllByVille($db, $idVille): array
    {
        $sql = "SELECT a.*, v.nomVille, d.libelle
                FROM gd_achat a
                JOIN gd_villes v ON v.id = a.idVille
                JOIN gd_dons d ON d.id = a.idDons
                WHERE a.idVille = :idVille
                ORDER BY a.date_achat DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute([':idVille' => $idVille]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAllWithVille($db): array
    {
        $sql = "SELECT a.*, v.nomVille, d.libelle
                FROM gd_achat a
                JOIN gd_villes v ON v.id = a.idVille
                JOIN gd_dons d ON d.id = a.idDons
                ORDER BY a.date_achat DESC";
        $stmt = $db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($db, $id): ?Achat
    {
        $sql = "SELECT a.* FROM gd_achat a WHERE a.id = :id";
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
