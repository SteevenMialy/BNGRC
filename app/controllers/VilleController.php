<?php

namespace app\controllers;

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

    public static function getAll()
    {
        $db = Flight::db();
        return Ville::getAll($db);
    }

}