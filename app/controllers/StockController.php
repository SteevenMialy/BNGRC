<?php

namespace app\controllers;

use app\models\Stock;

use app\models\Dons;
use Flight;
use flight\Engine;

class StockController
{

    protected Engine $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public static function alldons () {
        return Dons::getAllSansArgent(Flight::db());
    }

    public static function getDonById($id) {
        return Dons::getById(Flight::db(), $id);
    }

    public static function insert()
    {
        $request = Flight::request();
        $data = $request->data->getData();
        
        $db = Flight::db();
        $stock = new Stock();
        $stock->dons = new Dons($data['idDon']);
        $stock->qte = $data['qte'];
        $stock->daty = $data['daty'];
        return $stock->insert($db);
    }




}
