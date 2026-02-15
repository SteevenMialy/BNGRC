<?php

namespace app\models;

use PDO;

class ExchObject
{
    public $id;
    private $id_user;
    private $id_category;
    private $descript;
    private $prix;
    private $obj_name;
    private $userName;
    private $categoryName;
    public $pictures = [];

    public function __construct($id = null, $id_user = null, $id_category = null, $descript = null, $prix = null, $obj_name = null, $userName = null, $categoryName = null)
    {
        $this->id = $id;
        $this->id_user = $id_user;
        $this->id_category = $id_category;
        $this->descript = $descript;
        $this->prix = $prix;
        $this->obj_name = $obj_name;
        $this->userName = $userName;
        $this->categoryName = $categoryName;
    }

    public function findObject($db, $conditions = [])
    {
        $sql = "SELECT * FROM v_exch_object_details WHERE 1=1";
        $params = [];
        if ($this->id_user !== null) {
            $sql .= " AND id_user = :id_user";
            $params[':id_user'] = $this->id_user;
        }
        if ($this->id_category !== null) {
            $sql .= " AND id_category = :id_category";
            $params[':id_category'] = $this->id_category;
        }

        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        $objects = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $obj = new ExchObject($row['id'], $row['id_user'], $row['id_category'], $row['descript'], $row['prix'], $row['obj_name'], $row['username'], $row['nomcategory']);
            $obj->pictures = Picture::findByObjectId($db, $obj->id);
            $objects[] = $obj;
        }
        return $objects;
    }

    public function resakapourcentage($db, $pourcentage, $id_object,$id_user)
    {
        $sql = "SELECT o2.*
        FROM exch_object o1
        JOIN exch_object o2
            ON o2.prix BETWEEN 
                o1.prix - (o1.prix * :pourcentage / 100)
            AND 
                o1.prix + (o1.prix * :pourcentage / 100)
        WHERE o1.id = :id_object
        AND o2.id <> :id_object
        AND o2.id_user <> :id_user
    ";


        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':id_object' => $id_object,
            ':pourcentage' => $pourcentage,
            ':id_user' => $id_user
        ]);

        $objects = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $obj = new ExchObject(
                $row['id'],
                $row['id_user'],
                $row['id_category'],
                $row['descript'],
                $row['prix'],
                $row['obj_name']
            );

            $obj->pictures = Picture::findByObjectId($db, $obj->id);

            $objects[] = $obj;
        }

        return $objects;
    }


    public function verification($db, $id_user, $id_object): bool
    {
        $sql = "SELECT 1 FROM exch_object WHERE id_user = :id_user AND id = :id_object LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':id_user' => $id_user,
            ':id_object' => $id_object,
        ]);

        return (bool) $stmt->fetchColumn();
    }

    public function findBelongedObject($db, $id_user): array
    {
        $sql = "SELECT * FROM v_exch_object_details WHERE id_user = :id_user";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':id_user' => $id_user
        ]);

        $objects = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $obj = new ExchObject($row['id'], $row['id_user'], $row['id_category'], $row['descript'], $row['prix'], $row['obj_name'], $row['username'], $row['nomcategory']);
            $obj->pictures = Picture::findByObjectId($db, $obj->id);
            $objects[] = $obj;
        }
        return $objects;
    }

    public function findNotBelongedObject($db, $id_user): array
    {
        $sql = "SELECT * FROM v_exch_object_details WHERE id_user != :id_user";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':id_user' => $id_user
        ]);

        $objects = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $obj = new ExchObject($row['id'], $row['id_user'], $row['id_category'], $row['descript'], $row['prix'], $row['obj_name'], $row['username'], $row['nomcategory']);
            $obj->pictures = Picture::findByObjectId($db, $obj->id);
            $objects[] = $obj;
        }
        return $objects;
    }

    public function insert($db, $data, $id_user): int
    {
        $sql = "INSERT INTO exch_object (id_user, id_category, descript, prix, obj_name) 
                VALUES (:id_user, :id_category, :descript, :prix, :obj_name)";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':id_user' => $id_user,
            ':id_category' => $data['id_category'],
            ':descript' => $data['descript'],
            ':prix' => $data['prix'],
            ':obj_name' => $data['obj_name']
        ]);

        $this->id = $db->lastInsertId();
        return $this->id;

    }

    public static function lastinsert($db): ?ExchObject
    {
        $id = $db->lastInsertId();
        if ($id) {
            return $id;
        }
        return null;
    }

    public static function findById($db, $id): ?ExchObject
    {
        $sql = "SELECT * FROM v_exch_object_details WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':id' => $id
        ]);

        $object = null;
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $obj = new ExchObject($row['id'], $row['id_user'], $row['id_category'], $row['descript'], $row['prix'], $row['obj_name'], $row['username'], $row['nomcategory']);
            $obj->pictures = Picture::findByObjectId($db, $obj->id);
            $object = $obj;
        }
        return $object;
    }

    public static function findAll($db): array
    {
        $sql = "SELECT * FROM v_exch_object_details";
        $stmt = $db->prepare($sql);
        $stmt->execute();

        $objects = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $obj = new ExchObject($row['id'], $row['id_user'], $row['id_category'], $row['descript'], $row['prix'], $row['obj_name'], $row['username'], $row['nomcategory']);
            $obj->pictures = Picture::findByObjectId($db, $obj->id);
            $objects[] = $obj;
        }
        return $objects;
    }

    public function update($db): bool
    {
        $sql = "UPDATE exch_object 
                SET id_user = :id_user, id_category = :id_category, descript = :descript, prix = :prix, obj_name = :obj_name
                WHERE id = :id";

        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':id_user' => $this->id_user,
            ':id_category' => $this->id_category,
            ':descript' => $this->descript,
            ':prix' => $this->prix,
            ':obj_name' => $this->obj_name,
            ':id' => $this->id
        ]);
    }

    public function delete($db): bool
    {
        $sql = "DELETE FROM exch_object WHERE id = :id";
        $stmt = $db->prepare($sql);

        return $stmt->execute([
            ':id' => $this->id
        ]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIdUser()
    {
        return $this->id_user;
    }

    public function setIdUser($id_user)
    {
        $this->id_user = $id_user;
    }

    public function getIdCategory()
    {
        return $this->id_category;
    }

    public function setIdCategory($id_category)
    {
        $this->id_category = $id_category;
    }

    public function getDescript()
    {
        return $this->descript;
    }

    public function setDescript($descript)
    {
        $this->descript = $descript;
    }

    public function getPrix()
    {
        return $this->prix;
    }

    public function setPrix($prix)
    {
        $this->prix = $prix;
    }

    public function getObjName()
    {
        return $this->obj_name;
    }

    public function setObjName($obj_name)
    {
        $this->obj_name = $obj_name;
    }

    public function getUserName()
    {
        return $this->userName;
    }

    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    public function getCategoryName()
    {
        return $this->categoryName;
    }

    public function setCategoryName($categoryName)
    {
        $this->categoryName = $categoryName;
    }

}