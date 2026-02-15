<?php

use app\controllers\CategoryController;
use app\controllers\UserController;
use app\controllers\ObjectController;
use app\controllers\AdminController;
use app\controllers\PictureController;
use app\controllers\PropositionController;

use app\middlewares\SecurityHeadersMiddleware;
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

	$router->get('/', function () use ($app) {
		$app->render('login');
	});

	$router->get('/search', function () use ($app) {
		//$controller = new ObjectController($app);

		$categories = new CategoryController($app);
		$catArray = [];
		$catArray = $categories->allcategory();
		$count = [];
		foreach ($catArray as $category) {
			$count[$category->id] = CategoryController::countObjectsInCategory($category->id);
		}

		$objects = ObjectController::search();
		$app->render('home', [
			'objects' => $objects,
			'categories' => $catArray,
			'counts' => $count
		]);
	});

	$router->get('/about', function () use ($app) {
		$app->render('about');
	});


	$router->get('/newcategory', function () use ($app) {
		$app->render('InsertCategory');
	});

	$router->post('/insertioncategory', function () use ($app) {
		$controller = new CategoryController($app);
		$controller->insertcategory();
		Flight::redirect('/adminpage');
	});

	$router->get('/editcategory/@id', function ($id) use ($app) {
		$controller = new CategoryController($app);
		$app->render('modifycategory', [
			'category' => CategoryController::find($id)
		]);
	});

	$router->post('/modificationcategory', function () use ($app) {
		$controller = new CategoryController($app);
		$controller->modifycategory();
		Flight::redirect('/adminpage');
	});


	$router->get('/signin', function () use ($app) {
		$app->render('signin');
	});

	$router->get('/disconnect', function () use ($app) {
		if (session_status() === PHP_SESSION_ACTIVE) {
			if (ini_get("session.use_cookies")) {
				$params = session_get_cookie_params();
				setcookie(
					session_name(),
					'',
					time() - 42000,
					$params["path"],
					$params["domain"],
					$params["secure"],
					$params["httponly"]
				);
			}

			session_destroy();
			Flight::redirect('/');
		}
	});

	Flight::route('/api/validate/signin', [UserController::class, 'validateSignin']);
	Flight::route('/signIn', [UserController::class, 'save']);

	Flight::route('/api/validate/login', [UserController::class, 'validateLogin']);
	Flight::route('/login', [UserController::class, 'check']);

	$router->get('/home', function () use ($app) {
		//$controller = new ObjectController($app);
		$categories = new CategoryController($app);
		$catArray = [];
		$catArray = $categories->allcategory();
		$count = [];
		foreach ($catArray as $category) {
			$count[$category->id] = CategoryController::countObjectsInCategory($category->id);
		}
		$objects = ObjectController::getAllNotBelongedObject();
		$app->render('home', [
			'objects' => $objects,
			'categories' => $catArray,
			'counts' => $count
		]);
	});

	$router->get('/adminpage', function () use ($app) {
		$categories = new CategoryController($app);
		$catArray = [];
		$catArray = $categories->allcategory();
		$count = [];
		foreach ($catArray as $category) {
			$count[$category->id] = CategoryController::countObjectsInCategory($category->id);
		}
		$toutexchanges = PropositionController::allexchanges();
		$count = count($toutexchanges);
		$alluser=UserController::getAllUsers();
		$countUser=$alluser ? count($alluser) : 0;
		$app->render('AdminPage', [
			'categories' => $catArray,
			'counts' => $count,
			'countUser' => $countUser,
			'toutexchanges' => $count
		]);
	});

	$router->get('/shop', function () use ($app) {
		$objects = ObjectController::getAllNotBelongedObject();
		$app->render('shop', [
			'objects' => $objects
		]);
	});

	$router->get('/shopCart', function () use ($app) {
		$app->render('cart',[
			'propositions' => PropositionController::getPropositionsReceived(),
			'counts' => PropositionController::allconts($_SESSION['user']->id),
			'statusUser' => 1 // 1 pour les propositions reçues, 0 pour les propositions envoyées
		]);
	});

	$router->get('/shopCart/sent', function () use ($app) {
		$app->render('cart',[
			'propositions' => PropositionController::getPropositionsSent(),
			'counts' => PropositionController::allconts($_SESSION['user']->id),
			'statusUser' => 0 // 1 pour les propositions reçues, 0 pour les propositions envoyées
		]);
	});

	$router->get('/api/proposal/accept/@id', function ($id) use ($app) {
		PropositionController::acceptProposal($id);
		$app->render('about');
	});

	$router->get('/checkout', function () use ($app) {
		$app->render('checkout');
	});

	$router->get('/contact', function () use ($app) {
		$app->render('contact');
	});

	$router->post('/connect/admin', function () use ($app) {
		$controller = new AdminController($app);
		$controller->authenticateAdmin();
	});

	$router->get('/Accueil', function () use ($app) {
		$objects = ObjectController::getAllBelongedObject();
		$app->render('Accueil', [
			'objects'  => $objects
		]);
	});

	$router->get('/ajoutobject', function () use ($app) {
		$db = Flight::db();
		$categories = new CategoryController($app);
		$catArray = [];
		$catArray = $categories->allcategory();
		$app->render('ajoutobject', [
			'categories' => $catArray
		]);
	});

	$router->post('/ajoutobjectfonc', function () use ($app) {
		$controller = new ObjectController($app);
		$idobject = $controller->insertionobject();

		if (!empty($idobject)) {
			// Insérer les images associées à l'objet
			PictureController::insertionpicture($idobject);

			$objects = ObjectController::getAllBelongedObject();
			$app->render('Accueil', [
				'objects'  => $objects
			]);
		} else {
			$app->render('ajoutobject', [
				'categories' => CategoryController::allcategory()
			]);
		}
	});

	$router->get('/object/@id', function ($id) use ($app) {
		$object = ObjectController::getObject($id);
		$app->render('DetailsObject', [
			'object' => $object
		]);
	});

	$router->get('/appartenance', function () use ($app) {
		$echange = PropositionController::getinfoexchange(Flight::db());
		$app->render('Appartenance', [
			'echange' => $echange
		]);
	});

	$router->get('/exchange/@id', function ($id) use ($app) {
		$object = ObjectController::getObject($id);
		$userId = $_SESSION['user']->id ?? null;
		$verification = $userId ? ObjectController::verification($id, $userId) : false;
		$objectsList = $verification
			? ObjectController::getAllNotBelongedObject()
			: ObjectController::getAllBelongedObject();
		$app->render('Exchange', [
			'object' => $object,
			'objectsNotBelonged' => $objectsList
		]);
	});

	Flight::route('/propose', [PropositionController::class, 'proposeExchange']);
	Flight::route('/propose/accept/@id', [PropositionController::class, 'acceptProposal']);
	Flight::route('/propose/refuse/@id', [PropositionController::class, 'refuseProposal']);


	$router->get('/exchange/chossen/@id', function ($id) {
		$object = ObjectController::getObject($id);
		if (!$object) {
			Flight::json(null);
			return;
		}
		$firstPicture = $object->pictures[0] ?? null;
		Flight::json([
			"id" => $object->id,
			"id_owner" => $object->getIdUser(),
			"obj_name" => $object->getObjName(),
			"prix" => $object->getPrix(),
			"image" => $firstPicture ? $firstPicture->getPathImg() : null
		]);
	});

	$router->get('/api/object/@id', function ($id) {
		$object = ObjectController::getObject($id);
		if (!$object) {
			Flight::json(null);
			return;
		}

		$pictures = [];
		foreach (($object->pictures ?? []) as $picture) {
			$pictures[] = $picture->getPathImg();
		}

		Flight::json([
			"id" => $object->id,
			"obj_name" => $object->getObjName(),
			"prix" => $object->getPrix(),
			"descript" => $object->getDescript(),
			"category" => $object->getCategoryName(),
			"owner" => $object->getUserName(),
			"pictures" => $pictures
		]);
	});
}, [SecurityHeadersMiddleware::class]);
