<?php

namespace app\controllers;

use app\models\ExchObject;
use Flight;
use flight\Engine;

class ObjectController
{

	protected Engine $app;
	
	public function __construct($app)
	{
		$this->app = $app;
	}

	public static function getAllBelongedObject()
	{
		$obj = new ExchObject();
		$objs = $obj->findBelongedObject(Flight::db(),$_SESSION['user']->id);
		return $objs;
	}

	public static function getResakaPourcentage($pourcentage, $id_object) {
		$obj = new ExchObject();
		$id_user= $_SESSION['user']->id;
		return $obj->resakapourcentage(Flight::db(), $pourcentage, $id_object,$id_user);
	}

	public static function getAllNotBelongedObject()
	{
		$obj = new ExchObject();
		$objs = $obj->findNotBelongedObject(Flight::db(),$_SESSION['user']->id);
		return $objs;
	}

	public static function getObject($id)
	{
		$obj = new ExchObject();
		$objs = $obj->findById(Flight::db(), $id);
		return $objs;
	}

	public static function verification($id_object, $id_user): bool
	{
		$obj = new ExchObject();
		return $obj->verification(Flight::db(), $id_user, $id_object);
	}

	public static function insertionobject(){

		$request = Flight::request();
		$db = Flight::db();
		$data=$request->data->getData();
		$object = new ExchObject();
		$id_user= $_SESSION['user']->id;
		$object->insert($db, $data, $id_user);
		return $object->getId();
	}
	
}