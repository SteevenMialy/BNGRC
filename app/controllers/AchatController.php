<?php

namespace app\controllers;

use app\models\Achat;
use app\models\Ville;
use Flight;
use flight\Engine;

class AchatController
{
    protected Engine $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public static function create()
    {
        $request = Flight::request();
        $data = $request->data->getData();
        $db = Flight::db();
        $achat = new Achat();
        return $achat->insertData($db, $data);
    }

    public static function getAll()
    {
        $db = Flight::db();
        return Achat::getAll($db);
    }

    public static function getById($id)
    {
        $db = Flight::db();
        return Achat::getById($db, $id);
    }

    public static function update($id)
    {
        $request = Flight::request();
        $data = $request->data->getData();
        $db = Flight::db();
        $achat = new Achat();
        return $achat->updateData($db, $id, $data);
    }

    public static function delete($id)
    {
        $db = Flight::db();
        return Achat::delete($db, $id);
    }

    public static function validerSimulation($idville, $iddon, $qte, $taux)
    {
        $db = Flight::db();
        $achat = new Achat();
        $data = [
            'idVille' => $idville,
            'idDons' => $iddon,
            'taux' => $taux,
            'quantite' => $qte,
            'date_achat' => date('Y-m-d H:i:s')
        ];

        $okInsert = $achat->insertData($db, $data);
        $idAchat = (int)$db->lastInsertId();
        $okValider = $okInsert && $idAchat > 0 ? $achat->valider($db, $idAchat) : false;
        $okDistribuer = $okValider ? Achat::distribuer($db, $idAchat) : false;

        $achats = Achat::getAllByVille($db, $idville);
        $villes = Ville::getAll($db);
        $ville = Ville::getById($db, $idville);

        Flight::render('LIsteFiltrableAchat', [
            'achats' => $achats,
            'villes' => $villes,
            'selectedVille' => $idville,
            'ville' => $ville,
            'validationOk' => $okValider,
            'distributionOk' => $okDistribuer
        ]);
    }

    public static function distribuer($id)
    {
        $db = Flight::db();
        return Achat::distribuer($db, $id);
    }

    public static function listeFiltrable($idville = null)
    {
        $db = Flight::db();
        $villes = Ville::getAll($db);

        if ($idville !== null) {
            $achats = Achat::getAllByVille($db, $idville);
            $ville = Ville::getById($db, $idville);
        } else {
            $achats = Achat::getAllWithVille($db);
            $ville = null;
        }

        Flight::render('LIsteFiltrableAchat', [
            'achats' => $achats,
            'villes' => $villes,
            'selectedVille' => $idville,
            'ville' => $ville,
            'validationOk' => null,
            'distributionOk' => null
        ]);
    }
}
