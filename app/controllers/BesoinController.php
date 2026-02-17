<?php

namespace app\controllers;

use app\models\Besoin;
use app\models\Stock;
use app\models\Ville;
use app\models\Dons;
use Flight;
use flight\Engine;

class BesoinController
{
    protected Engine $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public static function ajouterBesoin()
    {
        $db = Flight::db();
        $req = Flight::request();
        $data = $req->data;

        $idVille = $data->idVille ?? null;
        $idDon = $data->idDon ?? null;
        $qte = $data->qte ?? null;
        $daty = $data->daty ?? null;

        $ville = $idVille ? Ville::getById($db, $idVille) : null;
        $don = $idDon ? Dons::getById($db, $idDon) : null;

        $besoin = new Besoin(
            null,
            $ville,
            $don,
            $qte,
            $daty
        );

        if ($besoin->insert($db)) {
            Flight::json([
                'success' => true,
                'redirection' => BASE_URL . '/listBesoin/nonSatisfaits'
            ]);
        } else {
            Flight::json([
                'success' => false,
                'error' => 'Erreur lors de l\'ajout du besoin'
            ]);
        }
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

    public static function allCounts()
    {
        $db = Flight::db();
        return [
            'total' => Besoin::countAll($db),
            'satisfaits' => Besoin::countSatisfaits($db),
            'non_satisfaits' => Besoin::countNonSatisfaits($db)
        ];
    }

    public static function getAllBesoins()
    {
        $db = Flight::db();
        $besoins = Besoin::getAll($db);
        return $besoins;
    }

    public static function livrerDons()
    {
        $db = Flight::db();
        $dons = Stock::dons_ayant_stock($db);

        $satisfaction = [];
        $count = 0;

        foreach ($dons as $stock) {
            if (!$stock->dons) {
                continue;
            }

            $idDon = $stock->dons->id;

            if (!$idDon) {
                continue;
            }

            $quantiteRestante = $stock->qte;

            // Besoins non satisfaits du même don, triés par date la plus ancienne
            $besoins = Besoin::getNonSatisfaitsByType($db, $idDon);

            foreach ($besoins as $besoin) {
                if ($quantiteRestante <= 0) {
                    break;
                }

                if ($quantiteRestante >= $besoin->qte) {
                    // Satisfaction totale du besoin
                    $quantiteLivree = $besoin->qte;
                    $quantiteRestante -= $besoin->qte;
                    $besoin->qte = 0;
                    $count++;
                } else {
                    // Satisfaction partielle du besoin
                    $quantiteLivree = $quantiteRestante;
                    $besoin->qte -= $quantiteRestante;
                    $quantiteRestante = 0;
                    $count++;
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
            $stock->qte = $quantiteRestante;
            $stock->update($db);
        }

        Flight::json([
            'success' => true,
            'message' => 'Dons livrés et besoins mis à jour (' . $count . ' besoins satisfaits)'
        ]);
    }

    public static function livrerDonsmin()
    {
        $db = Flight::db();
        $dons = Stock::dons_ayant_stock($db);

        $satisfaction = [];
        $count = 0;

        foreach ($dons as $stock) {
            if (!$stock->dons) {
                continue;
            }

            $idDon = $stock->dons->id;

            if (!$idDon) {
                continue;
            }

            $quantiteRestante = $stock->qte;

            // Besoins non satisfaits du même don, triés par date la plus ancienne
            $besoins = Besoin::getBesoinByTypesmin($db, $idDon);

            foreach ($besoins as $besoin) {
                if ($quantiteRestante <= 0) {
                    break;
                }
                if ($besoin->qte > 0) {

                    if ($quantiteRestante >= $besoin->qte) {
                        // Satisfaction totale du besoin
                        $quantiteLivree = $besoin->qte;
                        $quantiteRestante -= $besoin->qte;
                        $besoin->qte = 0;
                        $count++;
                    } else {
                        // Satisfaction partielle du besoin
                        $quantiteLivree = $quantiteRestante;
                        $besoin->qte -= $quantiteRestante;
                        $quantiteRestante = 0;
                        $count++;
                    }

                    // Mise à jour du besoin en base
                    $besoin->update($db);

                    $satisfaction[] = [
                        'besoin' => $besoin,
                        'quantite_livree' => $quantiteLivree,
                        'stock' => $stock
                    ];
                }
            }

            // Mise à jour du stock restant
            $stock->qte = $quantiteRestante;
            $stock->update($db);
        }

        Flight::json([
            'success' => true,
            'message' => 'Dons livrés et besoins mis à jour (' . $count . ' besoins satisfaits)'
        ]);
    }

    public static function livrerDonsproportion()
    {
        $db = Flight::db();
        $dons = Stock::dons_ayant_stock($db);

        $satisfaction = [];
        $count = 0;

        foreach ($dons as $stock) {
            if (!$stock->dons) {
                continue;
            }

            $idDon = $stock->dons->id;

            if (!$idDon) {
                continue;
            }

            $quantiteRestante = (int)$stock->qte;
            $stockInitial = $quantiteRestante;

            // Besoins non satisfaits du même don, triés par date la plus ancienne
            $besoins = Besoin::getBesoinByproportion($db, $idDon);
            $sommebesoin = 0;
            foreach ($besoins as $besoin) {
                if ($besoin->qte > 0) {
                    $sommebesoin += (int)$besoin->qte;
                }
            }

            if ($quantiteRestante <= 0 || $sommebesoin <= 0) {
                continue;
            }

            foreach ($besoins as $besoin) {
                if ($quantiteRestante <= 0) {
                    break;
                }

                if ($besoin->qte > 0) {
                    $partTheorique = ($besoin->qte / $sommebesoin) * $stockInitial;
                    $quantiteLivree = (int) floor($partTheorique);
                    $quantiteLivree = min($quantiteLivree, (int)$besoin->qte, $quantiteRestante);
                    if ($quantiteLivree <= 0) {
                        continue;
                    }
                    $besoin->qte -= $quantiteLivree;
                    $quantiteRestante -= $quantiteLivree;
                    $count++;

                    // Mise à jour du besoin en base
                    $besoin->update($db);

                    $satisfaction[] = [
                        'besoin' => $besoin,
                        'quantite_livree' => $quantiteLivree,
                        'stock' => $stock
                    ];
                }
            }

            // Mise à jour du stock restant
            $stock->qte = $quantiteRestante;
            $stock->update($db);
        }

        Flight::json([
            'success' => true,
            'message' => 'Dons livrés et besoins mis à jour (' . $count . ' besoins satisfaits)'
        ]);
    }
}
