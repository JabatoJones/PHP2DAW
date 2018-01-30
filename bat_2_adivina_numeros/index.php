<?php
include 'partida.php';
// Iniciar una nueva sesión o reanudar la existente
session_start();

//Nueva partida
if (empty($_SESSION)) {
    $partida = new Partida();
    $_SESSION['partida'] = $partida;
    include 'vistaJugada.php';
} elseif (isset($_POST['numeroEscogido'])) {
    //Has añadido un numero.
    $numeroElegido = $_POST['numeroEscogido'];
    //Añado un nuevo intento.
    $intentos = $_SESSION['partida']->getIntentos();
    $intentos++;
    //Recupero la partida.
    $partida = $_SESSION['partida'];
    //Seteo los nuevos intentos.
    $partida->setIntentos($intentos);
    if ($partida->hasGanado($numeroElegido)) {
        //Elimino la sesion en caso de haber ganado la partida.
        unset($_SESSION["partida"]);
        include 'vistaGanado.php';
    } else {
        include 'vistaJugada.php';
    }
} else {
    header('Location: http://localhost:8000/');
}
?>
