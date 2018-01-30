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
    private $correo;
    private $pintor;

    public function __construct($nombre = null, $pass = null, $correo = null, $pintor = null) {
        $this->nombre = $nombre;
        $this->pass = $pass;
        $this->pintor = $pintor;
        $this->correo = $correo;
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

    function getCorreo() {
        return $this->correo;
    }

    function getPintor() {
        return $this->pintor;
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

    function setCorreo($correo) {
        $this->correo = $correo;
    }

    function setPintor($pintor) {
        $this->pintor = $pintor;
    }

    public function getCredencial($dbh, $user, $pass) {
        $select = "SELECT * FROM usuario WHERE nombre= :user AND pass= :pass";
        $consulta = $dbh->prepare($select);
        $consulta->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Usuario");
        $consulta->execute(array(":user" => $user, ":pass" => $pass));
        $logueado = $consulta->fetch();
        return $logueado;
    }

    /*public function persist($dbh) {
        if($this->id){ //UPDATE
            $modificar = "UPDATE usuario SET nombre= :nombre ,pass= :pass,correo= :correo ,idpintor= :pintor WHERE id= :id";
            $persistir = $dbh->prepare($modificar);
            $persistido = $persistir->execute(array(":usuario" => $this->nombre,":pass" => $this->pass,":correo" => $this->correo,":idpintor" => $this->pintor, ":id" => $this->id)); 
        }else{   ////INSERT         
            $query = "INSERT INTO usuario (nombre, idpintor ,pass ,correo) values (:nombre, :idpintor, :pass, :correo)";
            $persistir = $dbh->prepare($query);
         
            //$dbh->prepare($query);
            $persistido = $persistir->execute([":nombre"=> $this->getNombre(),
                                                ":idpintor"=> $this->getPintor(),
                                                ":pass"=> $this->getPass(),
                                                ":correo"=> $this->getCorreo()]);   
            if($persistido){
                //El id del usuario dinamico recogido del registro de la base de datos.
                $this->setId($dbh->lastInsertId());
            }
        }
        return $persistido;
    }*/
    public function persist($dbh) {     

       $query = "INSERT INTO usuarios (nombre, idpintor ,pass ,correo) values (:nombre, :idpintor, :pass, :correo)";

       $insert = $dbh->prepare($query);

       $persistido = $insert->execute([":nombre"=> $this->getNombre(),
                                                ":idpintor"=> $this->getPintor(),
                                                ":pass"=> $this->getPass(),
                                                ":correo"=> $this->getCorreo()]);

       if($persistido){

           $this->setId($dbh->lastInsertId());

       }else{

           throw new Exception;

       }

       

   }

}
