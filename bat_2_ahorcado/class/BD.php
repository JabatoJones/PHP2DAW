<?php


class BD
{

    const BD_SERVIDOR = "localhost:3306";
    const BD_NOMBRE = "ahorcado";
    const BD_USUARIO = "root";
    const BD_PASSWORD = "";

    protected static $bd = null;

    private function __construct()
    {
        try {
            self::$bd = new PDO("mysql:host=" . self::BD_SERVIDOR . ";dbname=" . self::BD_NOMBRE, self::BD_USUARIO, self::BD_PASSWORD);
            self::$bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public static function getConexion()
    {
        if (!self::$bd) {
            new BD();
        }
        return self::$bd;
    }

}
