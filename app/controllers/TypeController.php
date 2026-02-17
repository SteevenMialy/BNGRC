<?php

namespace app\controllers;

use app\models\TypeBesoin;
use Flight;
use flight\Engine;

class TypeController
{
    protected Engine $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public static function getAll(){
        return TypeBesoin::getall(Flight::db());
    }

}