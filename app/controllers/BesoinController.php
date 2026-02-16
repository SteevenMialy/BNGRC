<?php

namespace app\controllers;

use app\models\Besoin;
use app\models\Stock;
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

    public static function livrerDons()
    {
        $db = Flight::db();
        $dons = Stock::dons_ayant_stock($db);

        $satisfaction = [];

        foreach ($dons as $stock) {
            if (!$stock->dons) {
                continue;
            }

            $idTypes = $stock->dons->types_besoin?->id_types;

            if (!$idTypes) {
                continue;
            }

            $quantiteRestante = $stock->quantite;

            // Besoins non satisfaits du même type, triés par date la plus ancienne
            $besoins = Besoin::getNonSatisfaitsByType($db, $idTypes);

            foreach ($besoins as $besoin) {
                if ($quantiteRestante <= 0) {
                    break;
                }

                if ($quantiteRestante >= $besoin->quantite_demandee) {
                    // Satisfaction totale du besoin
                    $quantiteLivree = $besoin->quantite_demandee;
                    $quantiteRestante -= $besoin->quantite_demandee;
                    $besoin->quantite_demandee = 0;
                } else {
                    // Satisfaction partielle du besoin
                    $quantiteLivree = $quantiteRestante;
                    $besoin->quantite_demandee -= $quantiteRestante;
                    $quantiteRestante = 0;
                }

                // Mise à jour du besoin en base
                $besoin->update($db);

                $satisfaction[] = [
                    'besoin' => $besoin,
                    'quantite_livree' => $quantiteLivree,
                    'stock' => $stock
                ];
            }

            // Mise à jour du stock restant
            $stock->quantite = $quantiteRestante;
            $stock->update($db);
        }

        Flight::json([
            'success' => true,
            'message' => 'Dons livrés et besoins mis à jour'
        ]);
    }

}