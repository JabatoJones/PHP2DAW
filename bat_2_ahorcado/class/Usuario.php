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
    private $partidas;

    function __construct($nombre = null, $pass = null) {
        $this->nombre = $nombre;
        $this->pass = $pass;
        $this->partidas = new Collection();
    }
    function getPartidas() {
        return $this->partidas;
    }

    function setPartidas($partidas) {
        $this->partidas = $partidas;
    }

    
    function getNombre() {
        return $this->nombre;
    }

    function getPass() {
        return $this->pass;
    }

    function getId() {
        return $this->id;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setPass($pass) {
        $this->pass = $pass;
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

    public function getUsuarioByCredentials($dbh, $user, $pass) {
        $query = 'SELECT * from usuarios where nombre = :nombre AND pass = :pass';
        $consulta = $dbh->prepare($query);
        $consulta->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Usuario");
        $consulta->execute(array(":nombre" => $user, ":pass" => $pass));
        $logueado = $consulta->fetch();
        //Recupera todas las partidas de la base de datos mediante el ID
            $partidas = Partida::getPartidasByIdUser($dbh, $logueado->getId());
            foreach ($partidas as $miPartida){
                //Añade partidas a la coleccion del usuario.
                $logueado->partidas->add($miPartida);
            }
        
        return $logueado;
    }

}
