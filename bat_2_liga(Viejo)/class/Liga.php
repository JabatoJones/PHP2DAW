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

    function __construct($nombre = null, $jornadas = null, $equipos = null) {
        $this->nombre = $nombre;
        $this->jornadas = new Collection();
        $this->equipos = new Collection();
    }
    function getId() {
        return $this->id;
    }

        
    public static function isLiga($bd){
        
        $query = "SELECT * FROM liga";
        $stmt = $bd->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,"Liga");
        $stmt->execute();
        $liga = $stmt->fetch();    
        return $liga; 
    }

    public function setEquipos($equipos, $dbh) {
        foreach ($equipos as $x => $equipo) {
            $equipoActual = new Equipo($equipo, $this->id);
            $equipoActual->persist($dbh);
            $this->equipos->add($equipoActual);
        }
    }
    
    public function setJornadas($dbh) {
        $valor = $this->equipos->getNumObjects();
        if ($this->equipos->getNumObjects() % 2 != 0) {
            $equipo = new Equipo("Descanso", $this->id);
            $equipo->persist($dbh);
            $this->equipos->add($equipo);
        }
        $equipos = [];
        while ($actual = $this->equipos->iterate()) {
            array_push($equipos, $actual->getNombre());
        }
        $fecha = date_create('2014-11-02');
        for ($i = 0; $i < count($equipos) - 1; $i++) {
            $locales = array_slice($equipos, 0, (count($equipos) / 2));
            $visitantes = array_reverse(array_slice($equipos, (count($equipos) / 2)));
            
            date_add($fecha, date_interval_create_from_date_string('7 days'));
            $fechaFormateada = date_format($fecha, 'd-m-Y');
            $miJornada = new Jornada($fechaFormateada, $this->id);
            $miJornada->setId($i);
            $miJornada->persist($dbh);
            for ($j = 0; $j < count($visitantes); $j++) {
                $liga[$i][$j]['local'] = $locales[$j];
                $liga[$i][$j]['visitante'] = $visitantes[$j];
                $partidosIda[] = ["id" => $i, "local" => $locales[$j], "gl" => "", "visitante" => $visitantes[$j], "gv" => ""];
            }
            $miJornada->creaPartidos($partidosIda, $this->equipos, $dbh);
            $miJornada->setId($i);
            $this->jornadas->add($miJornada);
            $equipoBase = array_shift($equipos);
            array_unshift($equipos, array_pop($equipos));
            array_unshift($equipos, $equipoBase);
            unset($partidosIda);
        }
    }

    public function generaClasificacion($dbh) {

        $clasificacion = [];
        while ($equipo = $this->equipos->iterate()) {
            $clasificacion [$equipo->getNombre()] = ['GF' => 0, 'GC' => 0, 'GA' => 0, 'Puntos' => 0];
        }
        while ($jornada = $this->jornadas->iterate()) {
            $jornada->getPartidosById($jornada->getId(), $dbh);
            $clasificacionJornada = $jornada->clasificacionJornada();
            if (!is_null($clasificacionJornada)) {
                foreach ($clasificacionJornada as $id => $valor) {
                    $equipo = $_SESSION['liga']->getEquipos()->getByProperty('id', $id)->getNombre();
                    $clasificacion[$equipo]['GF'] += $valor['GF'];
                    $clasificacion[$equipo]['GC'] += $valor['GC'];
                    $clasificacion[$equipo]['Puntos'] += $valor['Puntos'];
                    $clasificacion[$equipo]['GA'] = (int) $clasificacion[$equipo]['GF'] - (int) $clasificacion[$equipo]['GC'];
                }
            }
            unset($clasificacionJornada);
        }

        $columna_puntos = array_column($clasificacion, 'Puntos');
        $columna_average = array_column($clasificacion, 'GA');

        array_multisort($columna_puntos, SORT_DESC, $columna_average, SORT_DESC, $clasificacion);
        unset($clasificacion['Descanso']);
        return $clasificacion;
    }

    public function persist($bd) {
        $query =  "INSERT INTO liga (nombre) VALUES( :nombre)";
        $stmt = $bd->prepare($query);
        $insert = $stmt->execute(array(":nombre" => $this->nombre));
        if ($insert) {
            $this->setId($bd->lastInsertId());
        }
    }
    
    public static function recuperaLiga($bd){
        
        $query = "SELECT * FROM liga";
        $stmt = $bd->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,"Liga");
        $stmt->execute();
        $liga = $stmt->fetch(); 
        $liga->setJornadas($liga->recuperaJornadas($bd));
        $liga->recuperaEquipos($bd);
        $liga->setEquipos($liga->getEquipos(),$bd);
        return $liga; 
    }

    public function recuperaJornadas($dbh) {
        $query = "SELECT * FROM jornadas WHERE idLiga = :id ";
        $stmt = $dbh->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Jornada");
        $stmt->execute(array(":id" => $this->id));
        $jornadas = $stmt->fetchAll();
        foreach ($jornadas as $jornada) {
            $this->jornadas->add($jornada);
        }
    }

    public function recuperaEquipos($dbh) {
        $query = "SELECT * FROM equipos WHERE idLiga = :id";
        $stmt = $dbh->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Equipo");
        $stmt->execute(array(":id" => $this->id));
        $equipos = $stmt->fetchAll();
        foreach ($equipos as $equipo) {
            $this->equipos->add($equipo);
        }
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

}
