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
    
    public function persist($bd){       
        $query = "INSERT INTO equipos (nombre, idLiga) VALUES ".
            "( :nombre, :idLiga)";
        $stmt = $bd->prepare($query);
        $insert = $stmt->execute(array(":nombre" => $this->nombre, ":idLiga" => $this->idLiga));
        if($insert){
            $this->setId($bd->lastInsertId());
        }
        
    }
}
