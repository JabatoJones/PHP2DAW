<?php

include './class/BD.php';
include './class/Collection.php';
include './class/Equipo.php';
include './class/Jornada.php';
include './class/Liga.php';
include './class/Partido.php';
include './class/Usuario.php';

$dbh = BD::getConexion();
session_start();

if (empty($_POST)) {
    session_destroy();
    include './views/login.php';
} elseif (isset($_POST['exit'])) {
    session_destroy();
    unset($_SESSION['usuario']);
    unset($_SESSION['liga']);
    unset($_SESSION['jornada']);
    unset($_SESSION['descanso']);
    include './views/login.php';
} elseif (isset($_POST['logout'])) {
    include './views/login.php';
} else if (isset($_POST['login'])) {//Vista Login
    $nombre = $_POST['nombre'];
    $pass = $_POST['pass'];
    $user = new Usuario($nombre, $pass);
    $user = $user->getCredencial($dbh);
    if ($user) {
        $_SESSION['user'] = $user;
        $liga = Liga::isLiga($dbh);
        if ($liga) {
            $_SESSION['liga'] = $liga->recuperaLiga($dbh);
            include './views/jornadas.php';
        } else {
            include './views/ligaInicio.php';
        }
    } else {
        $error = 'Error credenciales';
        include './views/login.php';
    }
} elseif (isset($_POST['registro'])) {//Vista registro
    include './views/registro.php';
} else if (isset($_POST['setRegistro'])) {
    $nombre = $_POST['nombre'];
    $pass = $_POST['pass'];
    $user = new Usuario($nombre, $pass);
    $user ->persist($dbh);
    if ($user) {
        $_SESSION['user'] = $user;
        $liga = Liga::isLiga($dbh);
            if ($liga){
                $_SESSION['liga'] = $liga; 
                $_SESSION['liga']->recuperaJornadas($dbh);
                $_SESSION['liga']->recuperaEquipos($dbh);
                $_SESSION['descanso'] = $_SESSION['liga']->getEquipos()->getByProperty('nombre', "Descanso");
                include './views/jornadas.php';   
            }else {
                include './views/ligaInicio.php'; 
            }
        
    } else {
        $error = 'Error credenciales';
        include './views/login.php';
    }
} elseif (isset($_POST['start'])) {
    $nombre = $_POST['liga'];
    $equipos = explode(',', $_POST['equipos']);
    //TODO empieza la logica de solo un liga
    $liga = new Liga($nombre);
    $liga->persist($dbh);
    $liga->setEquipos($equipos, $dbh); //Necesario haber equipos para crear las jornadas.
    $liga->setJornadas($dbh);
    $_SESSION['liga'] = $liga;
    $_SESSION['descanso'] = $liga->getEquipos()->getByProperty('nombre', "Descanso");
    include './views/jornadas.php';
} elseif (isset($_POST['verJornada'])) {
    include './views/partidos.php';
} elseif (isset($_POST['guardarPartidos'])) {
    //Recogen datos de los partidos.
    //Validar datos
    if ($validos) {
        //Actualizan datos
    } else {
        include './views/partidos.php';
    }
} elseif (isset($_POST['volver'])) {
    include './views/jornadas.php';
}

$dbh = NULL;
?>