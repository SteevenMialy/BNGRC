<?php

namespace app\controllers;

use app\models\Achat;
use app\models\Besoin;
use app\models\Stock;
use app\models\Ville;
use app\models\Dons;
use app\models\Mouvement;
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

    public static function reinitializeData()
    {
        try {
            $db = Flight::db();
            $path = __DIR__ . '/../../original-data/';

            $db->exec('SET FOREIGN_KEY_CHECKS = 0');

            Mouvement::cleanTable($db);
            Achat::cleanTable($db);
            Besoin::cleanTable($db);
            Stock::cleanTable($db);
            Dons::cleanTalble($db);

            $db->exec('SET FOREIGN_KEY_CHECKS = 1');

            Dons::insertDataFile($db, $path . 'don.csv');
            Stock::insertDataFile($db, $path . 'stock.csv');
            Besoin::insertDataFile($db, $path . 'besoin.csv');

            Flight::json([
                'success' => true,
                'message' => 'Données réinitialisées avec succès'
            ]);
        } catch (\Exception $e) {
            try {
                $db->exec('SET FOREIGN_KEY_CHECKS = 1');
            } catch (\Exception $ignored) {
            }

            Flight::json([
                'success' => false,
                'error' => 'Erreur lors de la réinitialisation des données: ' . $e->getMessage()
            ]);
            return;
        }
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
                
                $mvt = new Mouvement();
                $mvt->besoin = $besoin;
                $mvt->stock = $stock;
                $mvt->entree = 0;
                $mvt->sortie = $quantiteLivree;
                $mvt->designation = "Livraison de " . $quantiteLivree . " unités du don " . $stock->dons->libelle . " pour le besoin #" . $besoin->id;
                $mvt->insert($db);

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
            'message' => 'Dons livrés et besoins mis à jour'
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

                    $mvt = new Mouvement();
                    $mvt->besoin = $besoin;
                    $mvt->stock = $stock;
                    $mvt->entree = 0;
                    $mvt->sortie = $quantiteLivree;
                    $mvt->designation = "Livraison de " . $quantiteLivree . " unités du don " . $stock->dons->libelle . " pour le besoin #" . $besoin->id;
                    $mvt->insert($db);


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
            'message' => 'Dons livrés et besoins mis à jour'
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
                    $partidec = $partTheorique - $quantiteLivree;
                    $arondi = $partidec >= 0.5 ? 1 : 0;
                    $quantiteLivree += $arondi;
                    $quantiteLivree = min($quantiteLivree, (int)$besoin->qte, $quantiteRestante);
                    if ($quantiteLivree <= 0) {
                        continue;
                    }
                    $besoin->qte -= $quantiteLivree;
                    $quantiteRestante -= $quantiteLivree;
                    $count++;

                    $mvt = new Mouvement();
                    $mvt->besoin = $besoin;
                    $mvt->stock = $stock;
                    $mvt->entree = 0;
                    $mvt->sortie = $quantiteLivree;
                    $mvt->designation = "Livraison de " . $quantiteLivree . " unités du don " . $stock->dons->libelle . " pour le besoin #" . $besoin->id;
                    $mvt->insert($db);

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
            'message' => 'Dons livrés et besoins mis à jour'
        ]);
    }

    public static function valeurmotant($motantsatisafait)
    {
        $db = Flight::db();
        $non_satisfaits = Besoin::valeurNonSatisfaits($db);
        return [
            'total' => $non_satisfaits + $motantsatisafait,
            'satisfaits' => $motantsatisafait,
            'non_satisfaits' => $non_satisfaits
        ];
    }
}
