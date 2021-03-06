<?php

require_once 'class/BD.php';
require_once 'class/Usuario.php';
require_once 'class/Partida.php';
require_once 'class/Jugada.php';
//Inicia la conexion con la bbdd
$dbh = BD::getConexion();
session_start();
if (empty($_POST)) {
    $_SESSION['partida'] = null;
    $_SESSION['encript'] = null;
    session_unset();
    session_destroy();
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
                $logado->setPartidas(Partida::getAllPartidas($dbh, $logado->getId()));
                $_SESSION['user'] = $logado;
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
                $_SESSION['mensaje'] = 'Hola '.$user->getNombre();
                $user->setPartidas(Partida::getAllPartidas($dbh, $user->getId()));
                $_SESSION['user'] = $user;
                include './views/partidas.php';
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
    $_SESSION['partida'] = null;
    $_SESSION['encript'] = null;
    $partida = new Partida();
    $_SESSION['partida'] = $partida;
    include './views/juego.php';
} else if (isset($_POST['jugada'])) {
    $letra = $_POST['letra'];
    $partida = $_SESSION['partida'];
    $partida->setIdUsuario($_SESSION['user']->getId());
    $result = $_SESSION['partida']->procesaPalabra($letra);
    $partida->persist($dbh);
    if ($result == 1) {
        include './views/win.php';
    } elseif ($result == 2) {
        include './views/lose.php';
    } else {
        include './views/juego.php';
    }
} elseif (isset($_POST['partidas'])) {
    $_SESSION['user']->setPartidas(Partida::getAllPartidas($dbh, $_SESSION['user']->getId()));
    include './views/partidas.php';
} elseif (isset($_POST['XML'])) {
    $id = $_POST['XML'];
    $partida = Partida::getPartidaById($dbh, $_POST['XML']);
    $_SESSION['partida'] = $partida;
    $pruebaXml = "<idPartida></idPartida>";
    $miPartida = new SimpleXMLElement($pruebaXml); //Crea un nuevo objeto SimpleXMLElement
    $miPartida->addAttribute('id', $_SESSION['partida']->getId()); //Añade un elemento hijo al nodo XML
    //DEFINE DOCUMENTO XML ->new DOMDocument('1.0','UTF-8');
    //creamos el nodo raiz -> $xml->createElement('');s
    //appendChild
    //formatear el xml ->$mifichero->formauoutput = true;

    while ($jugada = $partida->getJugadas()->iterate()) {
        $jugadas = $miPartida->addChild('Jugada');
        $jugadas->addAttribute('idJugada', $jugada->getId());
        $acierto = $jugadas->addChild('Solucion', $jugada->getSolucionada());
        $letra = $jugadas->addChild('Letra', $jugada->getLetra());
    }
    $miPartida->preserveWhiteSpace = true;
    $miPartida->formatOutput = true;
    $_SESSION['xml'] = $miPartida->asXML();
    $miFichero = $miPartida->asXML(); //Retorna un string XML correcto basado en un elemento SimpleXML
    $miArchivo = fopen("xml/movimientos.xml", "w+"); //Abre un fichero o un URL
    fwrite($miArchivo, $miFichero); //Escritura archivo
    include './views/xml.php';
}

//Elimina la conexion
$dbh = null;

