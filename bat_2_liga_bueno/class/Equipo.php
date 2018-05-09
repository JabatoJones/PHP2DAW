<?php
/**
 * Description of Equipo
 *
 * @author jaboo
 */
class Equipo {
    private $id;
    private $idLiga;
    private $nombre;
      
    public function __construct($nombre = null,$idLiga = null, $id = null) {
        
        $this->id = $id;
        $this->idLiga = $idLiga;
        $this->nombre = $nombre;
               
    }
    function getId() {
        return $this->id;
    }
    function getNombre() {
        return $this->nombre;
    }
    
    function setId($id) {
        $this->id = $id;
    }
    function setNombre($nombre) {
        $this->nombre = $nombre;
    }
    function getIdLiga() {
        return $this->idLiga;
    }
    function setIdLiga($idLiga) {
        $this->idLiga = $idLiga;
    }
    
    public function persist($bd,$id){       
        $query = "INSERT INTO equipos (nombre, idLiga) VALUES ".
            "( :nombre, :idLiga)";
        $stmt = $bd->prepare($query);
        $insert = $stmt->execute(array(":nombre" => $this->nombre, ":idLiga" => $id));
        if($insert){
            $this->setId($bd->lastInsertId());
        }   
    }
    public static function creaEquipos($equipos) {
        foreach ($equipos as $x => $equipo) {
            return $equipo_actual = new Equipo($equipo,'',$x);           
            //$equipo_actual->persist($dbh);                  
            //$_SESSION['liga']->setEquipos($equipo_actual);
        }
    }
    public static function recuperaEquipos($dbh) {
        $query = "SELECT * FROM equipos WHERE idLiga = :id";
        $stmt = $dbh->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Equipo");
        $stmt->execute(array(":id" => $_SESSION['liga']->getId()));
        $equipos = $stmt->fetchAll();
        foreach ($equipos as $equipo) {
            $_SESSION['liga']->setEquipos($equipo);
        }
    }
}
