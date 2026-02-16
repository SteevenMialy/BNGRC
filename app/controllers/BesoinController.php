<?php

namespace app\controllers;

use app\models\Besoin;
use Flight;
use flight\Engine;

class BesoinController
{
	protected Engine $app;
	
	public function __construct($app)
	{
		$this->app = $app;
	}

    public static function getBesoinsNonSatisfaits()
    {
        $db = Flight::db();
        $besoins = Besoin::getNonSatisfaits($db);
        return $besoins;
    }

    public static function getBesoinsSatisfaits()
    {
        $db = Flight::db();
        $besoins = Besoin::getSatisfaits($db);
        return $besoins;
    }

}