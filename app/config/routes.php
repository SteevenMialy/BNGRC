<?php

use app\controllers\BesoinController;
use app\controllers\VilleController;
use app\controllers\DonController;
use app\middlewares\SecurityHeadersMiddleware;
use flight\Engine;
use flight\net\Router;

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

/** 
 * @var Router $router 
 * @var Engine $app
 */

// This wraps all routes in the group with the SecurityHeadersMiddleware
$router->group('', function (Router $router) use ($app) {
	$router->get('/form/ajoutBesoin', function () use ($app) {
		$app->render('insertBesoin', [
			'villes' => VilleController::getAll(),
			'dons' => DonController::getAll()
		]);
	});

	Flight::route('/besoin/insert', [BesoinController::class, 'ajouterBesoin']);

	$router->get('/listBesoin/satisfaits', function () use ($app) {
		$app->render('listBesoin', [
			'besoins' => BesoinController::getBesoinsSatisfaits(),
			'counts' => BesoinController::allCounts()
		]);
	});
	
	Flight::route('/besoin/delivrer', [BesoinController::class, 'livrerDons']);

	$router->get('/', function () use ($app) {
		$app->render('listBesoin', [
			'besoins' => BesoinController::getAllBesoins(),
			'counts' => BesoinController::allCounts()
		]);
	});

	$router->get('/listBesoin/nonSatisfaits', function () use ($app) {
		$app->render('listBesoin', [
			'besoins' => BesoinController::getBesoinsNonSatisfaits(),
			'counts' => BesoinController::allCounts()
		]);
	});

	$router->get('/achat', function () use ($app) {
		$app->render('Achat');
	});

	
}, [SecurityHeadersMiddleware::class]);
