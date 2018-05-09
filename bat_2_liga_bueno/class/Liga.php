<?php

/**
 * Description of Liga
 *
 * @author jaboo
 */
class Liga {

    private $nombre;
    private $jornadas;
    private $equipos;
    private $id;

    function __construct($nombre = null, $arrayEquipos = null) {
        $this->nombre = $nombre;
        $this->jornadas = new Collection();
        $this->equipos = new Collection();
        if(count($arrayEquipos) > 0){
            //Ahora relleno la coleccion con sus equipos y sus jorndas
            foreach ($arrayEquipos as $x => $equipo) {
                $this->equipos->add(new Equipo($equipo,null,$x));
            }
            //Genera el equipo descanso en caso de ser impares.
            if ($this->equipos->getNumObjects() % 2 != 0) {
                $this->equipos->add(new Equipo("Descanso"));
            }
            //Genero las jornadas
            $this->creaJornadas($this->equipos);
        }
    }

    function getId() {
        return $this->id;
    }

    /*
     * Funcion statica que retorna la liga de la BD, en caso de no haber retorna null.
     */

    public static function isLiga($bd) {
        $query = "SELECT * FROM liga";
        $stmt = $bd->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Liga");
        $stmt->execute();
        $liga = $stmt->fetch();
        if ($liga) {
            //Empieza el proceso de construccion de la liga
            $_SESSION['liga'] = $liga;
            $liga->setEquipos(Equipo::recuperaEquipos($bd));
            $liga->setJornadas(Jornada::recuperaJornadas($bd));
        }
        return $liga;
    }
    /*
     * Proceso de persistenia de la BD
     */
     public function persist($bd) {
        $query = "INSERT INTO liga (nombre) VALUES( :nombre)";
        $stmt = $bd->prepare($query);
        $insert = $stmt->execute(array(":nombre" => $this->nombre));
        if ($insert) {
            $this->setId($bd->lastInsertId());
            while ($equipo = $this->equipos->iterate()) {
                $equipo->persist($bd, $this->id);
            }
            while ($jornada = $this->jornadas->iterate()) {
                $jornada->persist($bd, $this->id);
                while ($partido = $jornada->getPartidos()->iterate()){
                    $partido->persist($bd);
                }
            }
            
            //Equipo::creaEquipos($this->arrayEquipos, $this->id, $bd);
            //Jornada::creaJornadas($bd, $this->getEquipos());
        }
    }

    /*
     * Funcion que añade los equipos y los persiste en la BD con el id de laliga.
     */

    public function setEquipos($equipo) {
        if($equipo != null){
            $this->equipos->add($equipo);
        }
    }

    /*
     * Funcion para setear las jornada.
     */

    public function setJornadas($jornadas) {
        if($jornadas != null){
            $this->jornadas->add($jornadas);
        }    
    }

    /*
     * Genera la clasificacion de la liga a partir de la clasificacion de cada jornada. 
     */

    public function generaClasificacion($dbh) {

        $clasificacion = [];
        while ($equipo = $this->equipos->iterate()) {
            $clasificacion [$equipo->getNombre()] = ['GF' => 0, 'GC' => 0, 'GA' => 0, 'Puntos' => 0];
        }
        while ($jornada = $this->jornadas->iterate()) {
            //Cada Jornada tiene su clasificacion con goles favor goles contra id jornada
            $clasificacionJornada = $jornada->clasificacionJornada();
            if (!is_null($clasificacionJornada)) {
                foreach ($clasificacionJornada as $nombre => $valor) {
                    $equipo = $_SESSION['liga']->getEquipos()->getByProperty('nombre', $nombre)->getNombre();
                    $clasificacion[$equipo]['GF'] += $valor['GF'];
                    $clasificacion[$equipo]['GC'] += $valor['GC'];
                    $clasificacion[$equipo]['Puntos'] += $valor['Puntos'];
                    $clasificacion[$equipo]['GA'] = (int) $clasificacion[$equipo]['GF'] - (int) $clasificacion[$equipo]['GC'];
                }
            }
            unset($clasificacionJornada);
        }
        $puntos = array_column($clasificacion, 'Puntos');
        $golAverage = array_column($clasificacion, 'GA');
        //Ordenacion x puntos,golaverage.
        array_multisort($puntos, SORT_DESC, $golAverage, SORT_DESC, $clasificacion);
        unset($clasificacion['Descanso']);
        return $clasificacion;
    }
    
    function getNombre() {
        return $this->nombre;
    }

    function getJornadas() {
        return $this->jornadas;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setId($id) {
        $this->id = $id;
    }

    function getEquipos() {
        return $this->equipos;
    }
    
     public function creaJornadas($equiposLiga) {
        $equipos = [];
        while ($actual = $equiposLiga->iterate()) {
            array_push($equipos, $actual->getNombre());
        }
        //Seteo la fecha de inicio de liga.
        $fecha = date_create('2018-03-05');
        for ($i = 0; $i < count($equipos) - 1; $i++) {
            $locales = array_slice($equipos, 0, (count($equipos) / 2));
            $visitantes = array_reverse(array_slice($equipos, (count($equipos) / 2)));
            //Se añade un intervalo de 7 dias entre jornada y jornada.
            date_add($fecha, date_interval_create_from_date_string('7 days'));
            //Formateo la fecha con el formato de esp.
            $fechaFormateada = date_format($fecha, 'd-m-Y');
            $miJornada = new Jornada($fechaFormateada);
            $miJornada->setId($i);
            //$miJornada->persist($dbh);
            //Genero el equipo local, visitante para poder tratarlos en la vista.
            for ($j = 0; $j < count($visitantes); $j++) {
                $liga[$i][$j]['local'] = $locales[$j];
                $liga[$i][$j]['visitante'] = $visitantes[$j];
                $partidosIda[] = ["id" => $i, "local" => $locales[$j], "gl" => "", "visitante" => $visitantes[$j], "gv" => ""];
            }
            //Se generar los cruces.
            $miJornada->cruces($partidosIda, $equiposLiga);
            if($miJornada !== null)
                $this->jornadas->add($miJornada);
            $equipoBase = array_shift($equipos);
            array_unshift($equipos, array_pop($equipos));
            array_unshift($equipos, $equipoBase);
            unset($partidosIda);
        }
    }
    public function crearCruces($partidos,$equipos) {
        foreach ($partidos as $x => $partido) {
            //Parametros constructor partido
            //idJornada, (int)equipoL,golL,(int)equipoV,golV
            $partido_actual = new Partido($partido['id'], $equipos->getByProperty("nombre", $partido['local'])->getNombre(),$partido['gl'],$equipos->getByProperty("nombre",$partido['visitante'])->getNombre(),$partido['gv']);       
            
        }
        
    }
}
