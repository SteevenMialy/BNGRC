<?php

namespace app\controllers;

use app\models\Achat;
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

    public static function valider()
    {
        $db = Flight::db();
        $achat = new Achat();
          $request = Flight::request();
        $data = $request->data->getData();
       $id= $achat->insertData($db, $data);
        return $achat->valider($db, $id);
    }

    public static function distribuer($id)
    {
        $db = Flight::db();
        return Achat::distribuer($db, $id);
    }
}
