<?php
/**
 * Description of Pintor
 *
 * @author jaboo
 */
class Pintor {
    private $nombre;
    
    function __construct($nombre = null) {
        $this->nombre = $nombre;
    }
    
    function getNombre() {
        return $this->nombre;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }
    function getIdPintor() {
        return $this->idPintor;
    }

    
    public function getPintores($dbh) {
        $select = "SELECT * FROM pintores";
        $consulta = $dbh->prepare($select);
        //Tipo dato que quiero sacar SOLO en los select
        
        //FETCH_CLASS -> Devuleve objeto del clase que se le indique
        //FETCH_ASSOC -> Devuelve el array asociativo.
        $consulta->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Pintor");
        
        $consulta->execute(array());
        //Cuando saco solo un usuario hago un fecth, si quiero todos los que coincidan con ese id utilizo FetchAll
        $pintores = $consulta->fetchAll();
        return $pintores;
    }

}
