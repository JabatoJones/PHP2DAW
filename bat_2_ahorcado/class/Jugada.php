<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Jugada
 *
 * @author jaboo
 */
class Jugada {
    
    private $letra;
    private $solucionada;
    private $idPartida;
    private $id;
    
    
    function __construct($letra = null, $solucionada =null , $idPartida=null) {
        $this->letra = $letra;
        $this->solucionada = $solucionada;
        $this->idPartida = $idPartida;
    }
    /*function __construct() {
    }*/
    function getIdPartida() {
        return $this->idPartida;
    }
    function getId() {
        return $this->id;
    }

    function setId($id) {
        $this->id = $id;
    }

    
    function setIdPartida($idPartida) {
        $this->idPartida = $idPartida;
    }

        
    function getLetra() {
        return $this->letra;
    }

    function getSolucionada() {
        return $this->solucionada;
    }

    function setLetra($letra) {
        $this->letra = $letra;
    }

    function setSolucionada($solucionada) {
        $this->solucionada = $solucionada;
    }

    //IMPORTANTE
    //Me falta sacar las jugadas por cada partida, ir guardando cada jugada y hacer el XML.
    //TODO SACAR jugadas de la partida
    public static function getJugadasByIdPartida($bd,$idPartida){
        $query = "SELECT * FROM jugadas WHERE idPartida = :idPartida";
        $stmt = $bd->prepare($query);
        $stmt ->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Jugada");
        $stmt->execute(array(":idPartida" => $idPartida));
        $misJugadas = $stmt->fetchAll();
        return $misJugadas;
    }
    public function persist($bd,$idPartida){
        $query = "INSERT INTO jugadas (idPartida, solucionada, letra) VALUES (:idPartida, :solucionada, :letra)";
        $stmt = $bd->prepare($query);
        $insert = $stmt->execute(array(":idPartida" => $idPartida, ":solucionada" => implode(' ', $this->solucionada),
            ":letra" => $this->letra));
        if($insert){
            $this->idJugada = $bd->lastInsertId();
        }
    }
    public function delete($bd,$idPartida){
        $query = "DELETE FROM jugadas WHERE idPartida = :idPartida";
        $stmt = $bd->prepare($query);
        $stmt->execute(array(":idPartida" => $idPartida));
    }
}
