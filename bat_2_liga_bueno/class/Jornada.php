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
        $this->partidos->add($partidos);
    }
    /*
     * Genera los cruces.
     */
    public function cruces($partidos,$equipos) {
        foreach ($partidos as $x => $partido) {
            $this->partidos->add(new Partido($partido['id'], $equipos->getByProperty("nombre", $partido['local'])->getNombre(),$partido['gl'],$equipos->getByProperty("nombre",$partido['visitante'])->getNombre(),$partido['gv'])); 
          
        }  
    }
    /*
     * Genera la clasificacion por jornada asignando puntuacion.
     */
    public function clasificacionJornada() {
        $clasificacion = [];
        
        while ($partido = $this->getPartidos()->iterate()){
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
    public function persist($bd,$idLiga){
       
        $query = "INSERT INTO jornadas (fecha, idLiga, id) VALUES ".
            "( :fecha, :idLiga ,:id)";
        $stmt = $bd->prepare($query);
        $insert = $stmt->execute(array(":fecha" => $this->fecha, ":idLiga" => $idLiga, ":id" => $this->id));
        if($insert){
            $this->setId($bd->lastInsertId());
        }
    }
    public static function recuperaJornadas($dbh) {
        $query = "SELECT * FROM jornadas WHERE idLiga = :id ";
        $stmt = $dbh->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Jornada");
        $stmt->execute(array(":id" => $_SESSION['liga']->getId()));
        $jornadas = $stmt->fetchAll();
        foreach ($jornadas as $jornada) {
            Partido::recuperaPartidos($jornada,$jornada->getId(),$dbh);
            $_SESSION['liga']->setJornadas($jornada);
        }
    }
}
