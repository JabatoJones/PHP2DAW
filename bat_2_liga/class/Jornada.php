<?php
/**
 * Description of Jornada
 *
 * @author jaboo
 */
class Jornada {
    private $id;
    private $idLiga;
    private $fecha;
    private $partidos;
    
    public function __construct($fecha = null,$idLiga = null, $id = null) {
        
        $this->id = $id;
        $this->idLiga = $idLiga;
        $this->fecha = $fecha;
        $this->partidos = new Collection();
    }
    function getId() {
        return $this->id;
    }
    function getFecha() {
        return $this->fecha;
    }
    function getPartidos() {
        return $this->partidos;
    }
    function setId($id) {
        $this->id = $id;
    }
    function setFecha($fecha) {
        $this->fecha = $fecha;
    }
    function setPartidos($partidos) {
        $this->partidos = $partidos;
    }
    public function creaPartidos($partidos,$equipos,$dbh) {
        foreach ($partidos as $x => $partido) {
            $partido_actual = new Partido($partido['id'], (int)$equipos->getByProperty("nombre", $partido['local'])->getId(),$partido['gl'],(int)$equipos->getByProperty("nombre",$partido['visitante'])->getId(),$partido['gv']); 
            $partido_actual->persist($dbh);          
        }
        
    }
    public function clasificacionJornada() {
        $clasificacion = [];
        
        while ($partido = $this->partidos->iterate()){
            if(is_numeric($partido->getGL())){              
                if($partido->getGL() > $partido->getGV()){
                    $clasificacion [$partido->getEquipoL()]=['GF'=> (int)$partido->getGL() ,'GC'=> (int)$partido->getGV() ,'Puntos'=> 3 ];
                    $clasificacion [$partido->getEquipoV()]=['GF'=> (int)$partido->getGV() ,'GC'=> (int)$partido->getGL() ,'Puntos'=> 0 ];      
                }elseif ($partido->getGL() < $partido->getGV()) {
                    $clasificacion [$partido->getEquipoL()]=['GF'=> (int)$partido->getGL() ,'GC'=> (int)$partido->getGV() ,'Puntos'=> 0 ];
                    $clasificacion [$partido->getEquipoV()]=['GF'=> (int)$partido->getGV() ,'GC'=> (int)$partido->getGL() ,'Puntos'=> 3 ];
                }else{
                   $clasificacion [$partido->getEquipoL()]=['GF'=> (int)$partido->getGL() ,'GC'=> (int)$partido->getGV() ,'Puntos'=> 1 ];
                   $clasificacion [$partido->getEquipoV()]=['GF'=> (int)$partido->getGV() ,'GC'=> (int)$partido->getGL() ,'Puntos'=> 1 ];
                }
            }
        }
         return $clasificacion;   
    }
    public function persist($bd){
       
        $query = "INSERT INTO jornadas (fecha, idLiga, id) VALUES ".
            "( :fecha, :idLiga ,:id)";
        $stmt = $bd->prepare($query);
        $insert = $stmt->execute(array(":fecha" => $this->fecha, ":idLiga" => $this->idLiga, ":id" => $this->id));
        if($insert){
            $this->setId($bd->lastInsertId());
        }
        
    }
    public function getPartidosById($id, $dbh) {
        
        $query = "SELECT * FROM partidos where idJornada = :idJornada";
        $consulta = $dbh->prepare($query);
        $consulta->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,"Partido");
        $consulta->execute(array("idJornada" => $id));
        $partidos = $consulta->fetchAll();
        $this->partidos->removeAll();
        foreach ($partidos as $key => $partido) {
            $this->partidos->add($partido);
        }              
    }
}
