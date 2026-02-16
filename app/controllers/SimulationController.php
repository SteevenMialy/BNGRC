<?php

namespace app\controllers;

use app\models\Mouvement;
use app\models\Ville;
use app\models\Dons;
use app\models\Simulation;
use Flight;
use flight\Engine;

class SimulationController
{

    protected Engine $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public static function calculprix($idDon, $qte, $taux) {
        $db = Flight::db();
        return Simulation::calculprix($db, $idDon, $qte, $taux);
    }

    public static function stockreste ($idDon, $qte) {
        $db = Flight::db();
        return Simulation::stock_restant($db, $idDon, $qte);
    }

    public static function argentreste ($idDon, $qte,$taux) {
        $db = Flight::db();
        return Simulation::getArgentReste($db, $idDon, $qte,$taux);
    }

    
    




}
