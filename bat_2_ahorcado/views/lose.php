<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        DERROTA
        
        <p>La palabra que tenias que adivinar era: <?=$_SESSION['partida']->getPalabra()?></p>
        <form method="POST" action="../index.php">
            <input type="submit" name="partidas" value="Jugar de nuevo" />
            <input type="submit" name="exit" value="Salir" />
        </form>
    </body>
</html>
