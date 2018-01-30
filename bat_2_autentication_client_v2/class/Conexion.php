<?php

/**
 * Description of Conexion
 *
 * @author jaboo
 */
include 'config.php';
class Conexion {

    private $basedatos = BD_NOMBRE;
    private $usuario = BD_USUARIO;
    private $contrasenya = BD_PASSWORD;
    private $equipo = BD_SERVIDOR;
    protected static $bd = null;

    private function __construct() {
        try {
            self::$bd = new PDO("mysql:host=$this->equipo;dbname=$this->basedatos", $this->usuario, $this->contrasenya);
            //  self::$bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
        }
    }

    public static function getConexion() {
        if (!self::$bd) {
            new Conexion();
        }
        return self::$bd;
    }
    
    
    
    
}
