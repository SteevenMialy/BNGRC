<?php

use app\controllers\BesoinController;

use app\middlewares\SecurityHeadersMiddleware;
use app\models\Besoin;
use app\models\Category;
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

	$router->get('/liste/satisfait', function () use ($app) {
		$app->render('listBesoin', [
			'besoins' => BesoinController::getBesoinsSatisfaits()
		]);
	});
	
	Flight::route('/besoin/delivrer', [BesoinController::class, 'livrerDons']);

	$router->get('/', function () use ($app) {
		$app->render('listBesoin', [
			'besoins' => BesoinController::getBesoinsNonSatisfaits()
		]);
	});

	$router->get('/liste/nonsatisfait', function () use ($app) {
		$app->render('listBesoin', [
			'besoins' => BesoinController::getBesoinsNonSatisfaits()
		]);
	});

	$router->get('/achat', function () use ($app) {
		$app->render('Achat');
	});

	
}, [SecurityHeadersMiddleware::class]);
