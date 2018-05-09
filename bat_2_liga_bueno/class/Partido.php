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
    private $golL;
    private $equipoV;
    private $golV;
    
    public function __construct($idJornada = null, $equipoL = null ,$golL = null ,$equipoV = null,$golV = null, $id = null) {
        
        $this->id = $id;
        $this->idJornada = $idJornada;
        $this->equipoL = $equipoL;
        $this->golL = $golL;
        $this->equipoV = $equipoV;
        $this->golV = $golV;
        
    }
    function getEquipoL() {
        return $this->equipoL;
    }
    function getGL() {
        return $this->golL;
    }
    function getEquipoV() {
        return $this->equipoV;
    }
    function getGV() {
        return $this->golV;
    }
    function setEquipoL($equipoL) {
        $this->equipoL = $equipoL;
    }
    function setGL($golL) {
        $this->golL = $golL;
    }
    function setEquipoV($equipoV) {
        $this->equipoV = $equipoV;
    }
    function setGV($golV) {
        $this->golV = $golV;
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
            "( :idJornada, :equipoL, :golL, :equipoV, :golV)";
        $stmt = $bd->prepare($query);
        $insert = $stmt->execute(array(":idJornada" => $this->idJornada, ":equipoL" => $this->equipoL, ":golL" => $this->golL, ":equipoV" => $this->equipoV, ":golV" => $this->golV));
        if($insert){
            $this->setId($bd->lastInsertId());
        }
    }
    public function actualizaPartidos($resultados,$dbh) {
        foreach ($resultados as $x => $partido) {
           $query = "UPDATE partidos SET golL = :golL , golV = :golV WHERE id = :id";
           $guardar = $dbh->prepare($query);
           $guardar->execute(array(":golL" => $partido['gL'],":golV" => $partido['gV'], ":id" => $partido['id']));           
        }
    }
    /*
     * Recupera los partidos de cada jornada.
     */
    public function recuperaPartidos($jornada,$id, $dbh) {
        
        $query = "SELECT * FROM partidos where idJornada = :idJornada";
        $consulta = $dbh->prepare($query);
        $consulta->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,"Partido");
        $consulta->execute(array(":idJornada" => $id));
        $partidos = $consulta->fetchAll();
        $jornada->getPartidos()->removeAll();
        foreach ($partidos as $key => $partido) {
            $jornada->getPartidos()->add($partido);
        }              
    }
}
