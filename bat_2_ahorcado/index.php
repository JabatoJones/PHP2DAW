<?php

require_once 'class/BD.php';
require_once 'class/Usuario.php';
require_once 'class/Partida.php';
require_once 'class/Jugada.php';
//Inicia la conexion con la bbdd
$dbh = BD::getConexion();
session_start();
if (empty($_POST)) {
    include './views/login.php';
} elseif (isset($_POST['cancelRegister'])) {
    include './views/login.php';
} else if (isset($_POST['exit'])) {
    session_unset();
    session_destroy();
    include './views/login.php';
} elseif (isset($_POST['login'])) { //LOGIN SHOW ALL GAMES
    //Filtro para inpedir name y pass limpio de caracteres especiales.
    $name = filter_input(INPUT_POST, "usuario", FILTER_SANITIZE_STRING);
    $pass = filter_input(INPUT_POST, "pass", FILTER_SANITIZE_STRING);
    if ($name && $pass) {
        $user = new Usuario($name, $pass);
        try {
            $logado = $user->getUsuarioByCredentials($dbh, $name, $pass);
            if ($logado) {
                $_SESSION['mensaje'] = 'Hola ' . $logado->getNombre();
                $_SESSION['user'] = $logado;
                $_SESSION['partidas'] = Partida::getAllPartidas($dbh, $logado->getId());
                include './views/partidas.php';
            } else {
                $_SESSION['mensaje'] = 'Credenciales invaldas';
                include './views/login.php';
            }
        } catch (Exception $ex) {
            $_SESSION['mensaje'] = 'Problemas al crear el usuario, intentelo en unos minutos';
            include './views/login.php';
        }
    } else {
        $_SESSION['mensaje'] = 'Credenciales invalidas';
        include './views/login.php';
    }
} else if (isset($_POST['continue'])) {
    $partida = Partida::getPartidaById($dbh, $_POST['continue']);
    $_SESSION['partida'] = $partida;
    include './views/juego.php';
} else if (isset($_POST['registro'])) { //Vista Registro
    include './views/registro.php';
} else if (isset($_POST['setRegistro'])) { //Set Registro
    //Filtro para inpedir name y pass limpio de caracteres especiales.
    $name = filter_input(INPUT_POST, "usuario", FILTER_SANITIZE_STRING);
    $pass = filter_input(INPUT_POST, "pass", FILTER_SANITIZE_STRING);
    if ($name && $pass) {
        $user = new Usuario($name, $pass);
        try {
            $registrado = $user->persist($dbh);
            if ($registrado) {
                include './views/login.php';
            } else {
                $_SESSION['mensaje'] = 'Credenciales invaldas';
                include './views/login.php';
            }
        } catch (Exception $ex) {
            $_SESSION['mensaje'] = 'Problemas al validar el usuario, intentelo en unos minutos';
            include './views/login.php';
        }
    } else {
        $_SESSION['mensaje'] = 'Parametros entrada invalidos';
        include './views/juego.php';
    }
} else if (isset($_POST['newGame'])) { //Nueva partida
    $partida = new Partida();
    $_SESSION['user']->setIdPartida($partida->getId());
    $_SESSION['partida'] = $partida;
    include './views/juego.php';
} else if (isset($_POST['jugada'])) {
    $letra = $_POST['letra'];
    $partida = $_SESSION['partida'];
    $partida->setIdUsuario($_SESSION['user']->getId());
    $_SESSION['partida']->isCorrectLetter($letra);
    $result = $_SESSION['partida']->procesaPalabra($letra, $partida);
    $win = $result[0];
    $lose = $result[1];
    $partida->persist($dbh);
    //$partida->getJugadas()->persist($dbh,$partida->getId());
    if ($win) {
        include './views/win.php';
    } elseif ($lose) {
        include './views/lose.php';
    } else {
        include './views/juego.php';
    }
} elseif (isset($_POST['partidas'])) {
    $_SESSION['partidas'] = Partida::getAllPartidas($dbh, $_SESSION['user']->getId());
    include './views/partidas.php';
} elseif (isset ($_POST['XML'])) {
    $id = $_POST['XML'];
    $partida = Partida::getPartidaById($dbh, $_POST['XML']);
    $_SESSION['partida'] = $partida;
    $pruebaXml ="<idPartida></idPartida>";
$miPartida = new SimpleXMLElement($pruebaXml);//Crea un nuevo objeto SimpleXMLElement
        $miPartida->addAttribute('id', $_SESSION['partida']->getId());//Añade un elemento hijo al nodo XML
        
        
        while( $jugada = $partida->getJugadas()->iterate()){
            $jugadas = $miPartida->addChild('Jugada');
            $jugadas->addAttribute('id',$jugada->getId());
            $acierto = $jugadas->addChild('PalabraEncontrada', $jugada->getSolucionada());
            $acierto = $jugadas->addChild('Letra',$jugada->getLetra());
        }
        $_SESSION['xml'] = $miPartida->asXML();
        $miFichero = $miPartida->asXML();//Retorna un string XML correcto basado en un elemento SimpleXML
        $miArchivo = fopen("xml/miPartida.xml", "w+");//Abre un fichero o un URL
        fwrite($miArchivo, $miFichero);//Escritura archivo
        include './views/xml.php';
}

//Elimina la conexion
$dbh = null;

