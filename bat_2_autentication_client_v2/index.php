<?php

require_once 'class/Conexion.php';
require_once 'class/Usuario.php';
require_once 'class/Pintor.php';
//Inicia la conexion con la bbdd
$dbh = Conexion::getConexion();

session_start();
if (empty($_POST)) {
    include './vistas/login.php';
    //Usuario logado
} elseif (isset($_POST['logout'])) {
    session_destroy();
    include './vistas/login.php';
    //Pet Perfil    
} elseif (isset($_POST['modificar'])) {
    include './vistas/perfil.php';
    //Proceso cambioPerfil
} elseif (isset($_POST['setmodificar'])) {
    if (filter_input(INPUT_POST, 'user')) {
        $usuario = new Usuario($_POST['user'], $_POST['pass'], $_POST['correo'], $_POST['pintor']);
        $persistido = $usuario->persist($dbh);
        if($persistido){
            $_SESSION['user'] = $usuario;
            $mensaje = "Bienvenido " .$usuario->getNombre();
            include './vistas/usuario.php';
        }else{
            $mensaje = 'Problemas al crear el usuario, porfavor vuelve a intentarlo...';
            include './vistas/registro.php';
        }
    } else {
        $mensaje = 'Debes rellenar correctamente los campos';
        include './vistas/perfil.php';
    }
}
//Usuario NO logado
elseif (isset($_POST['login'])) {
    //Retorna el usuario en caso de existir.
    $logueado = Usuario::getCredencial($dbh, $_POST['user'], $_POST['pass']);
    if ($logueado) {
        $_SESSION['usuario'] = $logueado;
        include 'vistas/cuadro.php';
    } else {
        $mensaje = "Usuario/Password incorrectos";
        include './vistas/login.php';
    }
    //Pet Registro    
} elseif (isset($_POST['registro'])) {
    $pintores = Pintor::getPintores($dbh);
    $_SESSION['pintores'] = $pintores;
    include './vistas/registro.php';
//Set Registro    
} elseif (isset($_POST['setUser'])) {
    if (filter_input(INPUT_POST, 'user')) {
        $usuario = new Usuario($_POST['user'], $_POST['pass'], $_POST['correo'], $_POST['pintor']);
        $persistido = $usuario->persist($dbh);
        if($persistido){
            $_SESSION['user'] = $usuario;
            $mensaje = "Bienvenido " .$usuario->getNombre();
            include './vistas/usuario.php';
        }else{
            $mensaje = 'Problemas al crear el usuario, porfavor vuelve a intentarlo...';
            include './vistas/registro.php';
        }
    } else {
        $mensaje = 'Debes rellenar correctamente los campos';
        include './vistas/registro.php';
    }
} else {
    include './vistas/login.php';
}
$dbh = null;
        
?>
