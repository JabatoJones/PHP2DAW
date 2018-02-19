<?php

require_once 'BD.php';
require_once 'Jugada.php';
require_once 'Collection.php';

/**
 * Description of Partida
 *
 * @author jaboo
 */
class Partida {

    private $idUsuario;
    private $estado;
    private $intentos;
    private $jugadas;
    private $palabra;
    private $solucionada;
    private $fallos;
    private $id;

    function __construct() {
        $this->estado = 'false';
        $this->intentos = 0;
        $this->fallos = 0;
        $this->jugadas = new Collection();
        $this->palabra = $this->randomWord();
        $this->solucionada = '';
    }

    function randomWord() {
        $palabras = ['kase0', 'sharif', 'arkano', 'nach', 'duki', 'shotta', 'tote', 'capaz'];
        return $palabras[rand(0, 7)];
        rand(0, 9);
    }

    function isCorrectLetter($letra) {
        $correct = false;
        for ($index = 0; $index < strlen($this->getPalabra()); $index++) {
            if ($letra == $this->getPalabra()[$index]) {
                $correct = true;
                break;
            }
        }
        $this->intentos++;
        return $correct;
    }

    function getId() {
        return $this->id;
    }

    function setId($id) {
        $this->id = $id;
    }

    function getFallos() {
        return $this->fallos;
    }

    function setFallos($fallos) {
        $this->fallos = $fallos;
    }

    function getSolucionada() {
        return $this->solucionada;
    }

    function setSolucionada($solucionada) {
        $this->solucionada = $solucionada;
    }

    function getIdUsuario() {
        return $this->idUsuario;
    }

    function getEstado() {
        return $this->estado;
    }

    function getIntentos() {
        return $this->intentos;
    }

    function getJugadas() {
        return $this->jugadas;
    }

    function getPalabra() {
        return $this->palabra;
    }

    function setIdUsuario($idUsuario) {
        $this->idUsuario = $idUsuario;
    }

    function setEstado($estado) {
        $this->estado = $estado;
    }

    function setIntentos($intentos) {
        $this->intentos = $intentos;
    }

    function setJugadas($jugadas) {
        $this->jugadas = $jugadas;
    }

    function setPalabra($palabra) {
        $this->palabra = $palabra;
    }

    function procesaPalabra($charSelect,$partida) {
        $word = str_split($this->palabra);
        $encript = !isset($_SESSION['encript']) ? array_fill(0, strlen($this->palabra), '_') : $_SESSION['encript'];
        $aciertos = 0;
        $aciertosActuales = 0;
        $keys = array_keys($encript);
        $count = 0;
        
        foreach ($word as $value) {
            if ($charSelect == $value) {
                $encript[$keys[$count]] = $charSelect;
                $aciertosActuales++;
            } else if ($value == ' ') {
                $encript[$keys[$count]] = ' ';
            }
            if ($encript[$keys[$count]] !== '_') {
                $aciertos++;
            }
            $count +=1;
        }        $partida->setSolucionada($encript);
        //Crea una nueva jugada y la añade a la coleccion de jugadas.
        $jugada = new Jugada($charSelect, $partida->getSolucionada());
        if (is_string($partida->getJugadas())) {
            $partida->jugadas = new Collection;
            $partida->getJugadas()->add($jugada);
        } else {
            $partida->getJugadas()->add($jugada);
        }
        //$this->add($jugada);
        $_SESSION['encript'] = $encript;
        $fallos = $aciertosActuales === 0 ? $partida->getFallos() + 1 : $partida->getFallos();
        $partida->setFallos($fallos);
        //Retorna un array booleanos victoria/derrota.
        $win = $aciertos === count($word);
        $lose =  $partida->getFallos() > 4;
        $partida->setSolucionada(implode('', $encript));
        if($win || $lose){
            $partida->setEstado('Finalizada');
        } else {
            $partida->setEstado('empezada');
        }
        return [$win,$lose];
    }

    public function persist($dbh) {
        if ($this->id) {//Mod
            $modificar = 'UPDATE partida SET idUsuario = :idUsuario, palabra = :palabra, estado= :estado, intentos= :intentos , fallos= :fallos, solucionada = :solucionada WHERE  id = :id';
            $prepare = $dbh->prepare($modificar);
            $persistido = $prepare->execute(array(':idUsuario' => $this->getIdUsuario(), ':palabra' => $this->getPalabra(), ':estado' => $this->getEstado(), ':intentos' => $this->getIntentos() , ':fallos' => $this->getFallos(), ':solucionada' => $this->getSolucionada(), ":id" => $this->id));
            if ($persistido) {
                $jugada = $this->getJugadas()->getLast();
                $jugada->persist($dbh, $this->getId());
            }
        } else {//Insert
            $query = "INSERT INTO `partida`(`idUsuario`, `palabra`, `estado`, `intentos` ,`fallos` ,`solucionada`) VALUES" .
                    "(:idUsuario, :palabra, :estado, :intentos , :fallos, solucionada = :solucionada)";
            $stmt = $dbh->prepare($query);
            $persistido = $stmt->execute(array(":idUsuario" => $this->idUsuario, ":palabra" => $this->getPalabra(), ":estado" => $this->getEstado(),
                ":intentos" => $this->getIntentos(),":fallos" => $this->getFallos(),":solucionada" => $this->getSolucionada()));
            if ($persistido) {
                $this->setId($dbh->lastInsertId());
                $jugada = $this->getJugadas()->getLast();
                $jugada->persist($dbh, $this->getId());
            }
        }
        return $persistido;
    }

    public static function getAllPartidas($dbh, $idUsuario) {
        $query = 'SELECT * FROM partida where idUsuario = :idUsuario';
        $prepare = $dbh->prepare($query);
        $prepare->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Partida");
        $prepare->execute(array(":idUsuario" => $idUsuario));
        $result = $prepare->fetchAll();
        return $result;
    }

    public static function getPartidaById($dbh, $id) {
        $query = 'SELECT * FROM partida where id = :id';
        $prepare = $dbh->prepare($query);
        $prepare->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Partida");
        $prepare->execute(array(":id" => $id));
        $partida = $prepare->fetch();
        if ($partida) {
            //Recupera todas las partidas de la base de datos mediante el ID
            
            $jugadas = Jugada::getJugadasByIdPartida($dbh, $partida->getId());
            //$partida->setJugadas = $jugadas;
            foreach ($jugadas as $jugada){
                //Añade partidas a la coleccion del usuario.
                $partida->jugadas->add($jugada);
            }
            return $partida;
        }
    }

}
