<?php

namespace app\controllers;

use app\models\Mouvement;
use app\models\Ville;
use Flight;
use flight\Engine;

class VilleController
{

    protected Engine $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public static function allville () {
        return Ville::getAll(Flight::db());
    }

    public static function getVilleById($id) {
        return Ville::getById(Flight::db(), $id);
    }

    public static function getAll()
    {
        $db = Flight::db();
        return Ville::getAll($db);
    }


}