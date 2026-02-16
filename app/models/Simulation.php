<?php

namespace app\models;

use PDO;

class Simulation
{
    public ?Ville $ville;          // Objet Ville
    public ?Dons $don;             // Objet Dons
    public $prix;

    public $qtestock;
    public $argentreste;

    public function __construct(?Ville $ville = null, ?Dons $don = null, $prix = null, $qtestock = null, $argentreste = null)
    {
        $this->ville = $ville;
        $this->don = $don;
        $this->prix = $prix;
        $this->qtestock = $qtestock;
        $this->argentreste = $argentreste;
    }

    //qte nle don hananana raha mbola > 0 le izy de tsy afaka mividy
    public static function getqteDon($db,$idDon) {
        $sql = "SELECT COALESCE(SUM(qte), 0) AS qte FROM gd_stock WHERE idDon = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$idDon]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //prix unitaire dons choisi
    public static function prixunitaire ($db,$idDon) {
        $sql = "SELECT pu FROM gd_dons WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$idDon]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    //raisina
    public static function calculprix ($db,$idDon,$qte,$taux) {
        $prix = self::prixunitaire($db,$idDon);
        $val = ($prix['pu'] * $qte) + (1+ ($taux/100));
        return $val;
    }

    //calcul qte restant si on simule
    //raisina
    public static function stock_restant ($db,$idDon,$qte) {
        $qtebase = self::getqteDon($db,$idDon);
        if ($qte > $qtebase['qte']) {
            return false;
        }
        return $qtebase['qte'] - $qte;
    }

    public static function Donsargent($db)
    {
        $sql = "SELECT COALESCE(SUM(s.qte), 0) as total_argent
                FROM gd_dons d
                JOIN gd_stock s ON d.id = s.idDon
                WHERE d.idTypes = 3 AND s.qte > 0";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    //argent restant
    //raisina
    public static function getArgentReste($db, $idDon, $qte,$taux) {
        $argentjiab = self::Donsargent($db);
        $prixsimuler = self::calculprix($db, $idDon, $qte, $taux);
        if ($prixsimuler > $argentjiab[0]['total_argent']) {
            return false;
        }
        return $argentjiab[0]['total_argent'] - $prixsimuler;
    }


}