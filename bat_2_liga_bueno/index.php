<?php

//Importacion clases
include './class/BD.php';
include './class/Collection.php';
include './class/Equipo.php';
include './class/Jornada.php';
include './class/Liga.php';
include './class/Partido.php';
include './class/Usuario.php';

//Conexion y session Up
$dbh = BD::getConexion();
session_start();

if (empty($_POST)) {
    session_destroy();
    include './views/login.php';
} else if (isset($_POST['login'])) {
    //Login
    $nombre = $_POST['nombre'];
    $pass = $_POST['pass'];
    $user = new Usuario($nombre, $pass);
    $user = $user->getCredencial($dbh);
    //Usuario Correctamente logado
    if ($user) {
        $_SESSION['user'] = $user;
        //Se comprueba si hay liga ya creada.
        $liga = Liga::isLiga($dbh);
        $_SESSION['liga'] = $liga;    
        if ($liga) {
            $_SESSION['descanso'] = $liga->getEquipos()->getByProperty('nombre', "Descanso");
            include './views/jornadas.php';
        } else {
            include './views/ligaInicio.php';
        }
    } else {
        $error = 'Error credenciales';
        include './views/login.php';
    }
    //Registro Vista
} elseif (isset($_POST['registro'])) {
    include './views/registro.php';
    //Seteo del registro.
} else if (isset($_POST['setRegistro'])) {
    $nombre = $_POST['nombre'];
    $pass = $_POST['pass'];
    $user = new Usuario($nombre, $pass);
    $user->persist($dbh);
    if ($user) {
        $_SESSION['user'] = $user;
        //Se comprueba si hay liga ya creada.
        $liga = Liga::isLiga($dbh);
        if ($liga) {
            $_SESSION['liga'] = $liga;
            $_SESSION['descanso'] = $_SESSION['liga']->getEquipos()->getByProperty('nombre', "Descanso");
            include './views/jornadas.php';
        } else {
            include './views/ligaInicio.php';
        }
    } else {
        $error = 'Error credenciales';
        include './views/login.php';
    }
    //No hay liga, y se inicia una nueva.
} elseif (isset($_POST['start'])) {
    $nombre = $_POST['liga'];
    $equipos = explode(',', $_POST['equipos']);
    //Empieza la logica de solo una liga.
    $liga = new Liga($nombre,$equipos);
    $_SESSION['liga'] = $liga;
    $liga->persist($dbh);
    $_SESSION['descanso'] = $liga->getEquipos()->getByProperty('nombre', "Descanso");
    include './views/jornadas.php';
    //Se muestran las jornadas.
} elseif (isset($_POST['verJornada'])) {
    //Usamos el id de la jornada para posteriormente poder ver los partidos de cada jornada.
    $idJornada = $_POST['id'];
    $jornada = $_SESSION['liga']->getJornadas()->getByProperty("id", $idJornada);
    $_SESSION['jornada'] = $jornada;
    include './views/partidos.php';
    //Se actualizan los partidos en la BBDD.
} elseif (isset($_POST['guardarPartidos'])) {
    //Recogen resultados de los partidos. 
    $resultados = $_POST['resultados'];
    //Validar datos
    if ($resultados) {
        //Actualizan datos        
        $idJornada = $_POST['id'];
        //Guardo en la BD
        Partido::actualizaPartidos($resultados, $dbh);
        $count = 0;
        //Modifico la sesion
        while ($actual = $_SESSION['liga']->getJornadas()->getByProperty('id', $idJornada)->getPartidos()->iterate()) {            
            $actual->setGL($resultados[$count]['gL'] == 'No Juega' ? 0 : $resultados[$count]['gL']);
            $actual->setGV($resultados[$count]['gV'] == 'No Juega' ? 0 : $resultados[$count]['gV']);
            $count++;
        }
        unset($_POST['resultados']);
        include './views/jornadas.php';
    } else {
        include './views/partidos.php';
    }
    //Regreso a la vista jorandas.
} elseif (isset($_POST['volver'])) {
    include './views/jornadas.php';
    //Vista clasificacion
} elseif (isset($_POST['clasificacion'])) {
    //Se genera la clasificacion.
    $clasificacion = $_SESSION['liga']->generaClasificacion($dbh);
    include './views/clasificacion.php';
} elseif (isset($_POST['logout'])) {
    //Logout
    session_destroy();
    session_unset();
    include './views/login.php';
}

$dbh = NULL;
?>