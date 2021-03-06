<?php
/**
 * Description of Usuario
 *
 * @author jaboo
 */
class Usuario {
    private $nombre;
    private $pass;
    private $id;
    
    function __construct($nombre = null, $pass=null) {
        $this->nombre = $nombre;
        $this->pass = $pass;
    }

    
    function getNombre() {
        return $this->nombre;
    }

    function getPass() {
        return $this->pass;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setPass($pass) {
        $this->pass = $pass;
    }
    function getId() {
        return $this->id;
    }

    function setId($id) {
        $this->id = $id;
    }

        
    public function persist($dbh) {
        if ($this->id) {//Update
            $modificar = 'UPDATE usuarios set nombre = :nombre ,pass = :pass'
                    . '    WHERE id = :id';
            $persistir = $dbh->prepare($modificar);
            $persistido = $persistir->execute(array(':nombre' => $this->getNombre(), ':pass' => $this->getPass(), ':id' => $this->getId()));
        } else {//Insert
            $insert = 'INSERT INTO usuarios (nombre,pass) VALUES (:nombre,:pass)';
            $persistir = $dbh->prepare($insert);
            $persistido = $persistir->execute(array('nombre' => $this->getNombre(), 'pass' => $this->getPass()));
            if ($persistido) {
                $this->setId($dbh->lastInsertId());
            } else {
                throw new Exception;
            }
        }
        return $persistido;
    }
    
    public function getCredencial($dbh) {
        $select = "SELECT * FROM usuarios WHERE nombre= :user AND pass= :pass";
        $consulta = $dbh->prepare($select);
        $consulta->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Usuario");
        $consulta->execute(array(":user" => $this->nombre, ":pass" => $this->pass));
        $logueado = $consulta->fetch();
        return $logueado;
    }

}
