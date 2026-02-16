<?php

namespace app\controllers;

use app\models\Mouvement;
use Flight;
use flight\Engine;

class MouvementController
{

    protected Engine $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public static function mvtentree()
    {

        $request = Flight::request();
        $obj = new Mouvement();
        $data = $request->data->getData();
        $db = Flight::db();
        $objs = $obj->insertentre($db, $data);
        return $objs;
    }

     public static function mvtSortie()
    {
        $request = Flight::request();
        $obj = new Mouvement();
        $data = $request->data->getData();
        $db = Flight::db();
        $objs = $obj->insertsortie($db, $data);
        return $objs;
    }




}
