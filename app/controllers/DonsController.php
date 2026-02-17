<?php

namespace app\controllers;

use app\models\Mouvement;
use app\models\Ville;
use app\models\Dons;
use app\models\TypeBesoin;
use Flight;
use flight\Engine;

class DonsController
{

    protected Engine $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public static function alldons(){
        return Dons::getAll(Flight::db());
    }

    public static function reinitializedon()
    {
        $db= Flight::db();
        Dons::cleanTalble($db);
        $path = BASE_URL . '/original-data/don.csv';
        Dons::insertDataFile($db, $path);
    }

    public static function alldonssansargent () {
        return Dons::getAllSansArgent(Flight::db()); //tsy nalako
    }

    public static function getDonById($id)
    {
        return Dons::getById(Flight::db(), $id);
    }

    public static function checkInsertDon()
    {
        $data = Flight::request()->data;

        $type = TypeBesoin::getById(Flight::db(), $data->type);
        if (!$type) {
            Flight::json([
                'succes' => false,
                'error' => 'Type de besoin inexistant'
            ]);
            return;
        }

        $don = new Dons(
            null,
            $data->libelle,
            $data->pu,
            $type,
            $data->daty
        );

        $search = Dons::findByLibelle(Flight::db(), $data->libelle);
        if (count($search) > 0 && !empty($search)) {
            Flight::json([
                'succes' => false,
                'error' => 'Un don avec ce libelle existe déjà'
            ]);
            return;
        } else {
            if ($don->insert(Flight::db())) {
                Flight::json([
                    'succes' => true,
                    'redirection' => BASE_URL . '/'
                ]);
            } else {
                Flight::json([
                    'succes' => false,
                    'error' => 'Erreur lors de l\'ajout du don'
                ]);
            }
        }
    }
}
