<?php

namespace app\controllers;

use app\models\Dons;
use Flight;
use flight\Engine;

class DonController
{
    protected Engine $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public static function getAll()
    {
        $db = Flight::db();
        return Dons::getAll($db);
    }

}