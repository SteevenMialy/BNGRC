<?php

namespace app\models;

use PDO;

class TypeBesoin
{
   public $id_types;
   public $nom_types;
   public $libele;
    public function __construct($id_types = null, $nom_types = null, $libele = null)
    {
        $this->id_types = $id_types;
        $this->nom_types = $nom_types;
        $this->libele = $libele;
    }

    public function insert($db): bool
    {
        $sql = "INSERT INTO gd_types_besoin (id_types, nom_types, libele)
                VALUES (:id_types, :nom_types, :libele)";
        
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':id_types' => $this->id_types,
            ':nom_types' => $this->nom_types,
            ':libele' => $this->libele
        ]);
    }

    public static function getAll($db): array
    {
        $sql = "SELECT * FROM gd_types_besoin ORDER BY id_types DESC";
        $stmt = $db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($db, $id): ?TypeBesoin
    {
        $sql = "SELECT * FROM gd_types_besoin WHERE id_types = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new TypeBesoin(
                $row['id_types'],
                $row['nom_types'],
                $row['libele']
            );
        }

        return null;
    }

    public function update($db): bool
    {
        $sql = "UPDATE gd_types_besoin
                SET id_types = :id_types,
                    nom_types = :nom_types,
                    libele = :libele
                WHERE id_types = :id_types";

        $stmt = $db->prepare($sql);

        return $stmt->execute([
            ':id_types' => $this->id_types,
            ':nom_types' => $this->nom_types,
            ':libele' => $this->libele
        ]);
    }

    public static function delete($db, $id): bool
    {
        $sql = "DELETE FROM gd_types_besoin WHERE id_types = :id";
        $stmt = $db->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }
}
