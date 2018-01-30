<?php
define("MIN",1);
define("MAX",10);

class Partida {
    
    private $intentos;
    private $numSecreto;
    
    function __construct() {
        $this->intentos = 0;
        $this->numSecreto = $this->genRandomNumber();
    }
    
    function getIntentos() {
        return $this->intentos;
    }

    function getNumSecreto() {
        return $this->numSecreto;
    }

    function setIntentos($intentos) {
        $this->intentos = $intentos;
    }

    function setNumSecreto($numSecreto) {
        $this->numSecreto = $numSecreto;
    }

    function hasGanado($numeElegido) {
        return $this->numSecreto == $numeElegido;
    }
    
    function genRandomNumber() {
        return rand(MIN, MAX);
    }
}
