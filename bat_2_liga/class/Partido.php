<?php
/**
 * Description of Partido
 *
 * @author jaboo
 */
class Partido {
    private $id;
    private $idJornada;
    private $equipoL;
    private $gL;
    private $equipoV;
    private $gV;
    
    public function __construct($idJornada = null, $equipoL = null ,$gL = null ,$equipoV = null,$gV = null, $id = null) {
        
        $this->id = $id;
        $this->idJornada = $idJornada;
        $this->equipoL = $equipoL;
        $this->gL = $gL;
        $this->equipoV = $equipoV;
        $this->gV = $gV;
        
    }
    function getEquipoL() {
        return $this->equipoL;
    }
    function getGL() {
        return $this->gL;
    }
    function getEquipoV() {
        return $this->equipoV;
    }
    function getGV() {
        return $this->gV;
    }
    function setEquipoL($equipoL) {
        $this->equipoL = $equipoL;
    }
    function setGL($gL) {
        $this->gL = $gL;
    }
    function setEquipoV($equipoV) {
        $this->equipoV = $equipoV;
    }
    function setGV($gV) {
        $this->gV = $gV;
    }
    function getId() {
        return $this->id;
    }
    function setId($id) {
        $this->id = $id;
    }
    function getIdJornada() {
        return $this->idJornada;
    }
    function setIdJornada($idJornada) {
        $this->idJornada = $idJornada;
    }
    public function persist($bd){
       
        $query = "INSERT INTO partidos (idJornada, equipoL, golL, equipoV, golV) VALUES ".
            "( :idJornada, :equipoL, :gL, :equipoV, :gV)";
        $stmt = $bd->prepare($query);
        $insert = $stmt->execute(array(":idJornada" => $this->idJornada, ":equipoL" => $this->equipoL, ":gL" => $this->gL, ":equipoV" => $this->equipoV, ":gV" => $this->gV));
        if($insert){
            $this->setId($bd->lastInsertId());
        }
        
    }
    public function actualizaPartidos($resultados,$dbh) {
        foreach ($resultados as $x => $partido) {
           $query = "UPDATE partidos SET gL = :gL , gV = :gV WHERE id = :id";
           $guardar = $dbh->prepare($query);
           $guardar->execute(array(":gL" => $partido['gL'],":gV" => $partido['gV'], ":id" => $partido['id']));
            
        }
    }
}
