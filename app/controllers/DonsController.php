<?php

namespace app\controllers;

use app\models\Mouvement;
use app\models\Ville;
use app\models\Dons;
use Flight;
use flight\Engine;

class DonsController
{

    protected Engine $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public static function alldons () {
        return Dons::getAll(Flight::db());
    }

     public static function alldonssansargent () {
        return Dons::getAllSansArgent(Flight::db());
    }

    public static function getDonById($id) {
        return Dons::getById(Flight::db(), $id);
    }




}
