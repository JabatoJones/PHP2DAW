<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>JUGADA</title>
        <script src="../js/front.js"></script>
    </head>
    <body>
        <h1>Bienvenido al juego del ahorcado version hip-hop</h1>
        <h3>Player :<?= $_SESSION['user']->getNombre() ?></h3>
        <h4>Tienes que adivinar un rapero español.</h4>
        <form action="../index.php" method="POST">
            <input type="submit"value="SALIR" name="partidas"></form>
        <?php
        isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : '';
        ?>
        <div style="    
             width: 12em;
             position: absolute;
             left: 30em;
             top: 5em;">
            <p> <script type="text/javascript">setImg(<?= $_SESSION['partida']->getFallos() ?>)</script><p/>
        </div> 
        <p> Intentos: <?= $_SESSION['partida']->getIntentos() ?></p>
        <p> Nº Fallos: <?= $_SESSION['partida']->getFallos() ?></p>
        <p>Letras usadas usadas:
            <?php
            if (count($_SESSION['partida']->getJugadas()) >= 1) {
                while ($jugadaActual = $_SESSION['partida']->getJugadas()->iterate()) {
                    $solucionada = $jugadaActual->getSolucionada();
                    if (is_string($solucionada)) {
                        $arraySol = str_split($solucionada);
                    } else {
                        $arraySol = $solucionada;
                    }
                    $filterArraySol = array_filter($arraySol, function ($val) {
                        return $val !== ' ';
                    });
                    $_SESSION['encript'] = $filterArraySol;
                    echo $jugadaActual->getLetra();
                }
            }
            ?>
        <form method="POST" action="../index.php">
            <input type="text" max="1" name="letra">
            <input type="submit" name="jugada">
        </form>
    </p>
    <div>
        <p><?php
            if (isset($_SESSION['encript'])) {
                if (count($_SESSION['encript']) >= 1) {
                    foreach ($_SESSION['encript'] as $letra) {
                        echo $letra;
                    }
                }
            }
            ?>
        </p>
    </div>
</body>
</html>
