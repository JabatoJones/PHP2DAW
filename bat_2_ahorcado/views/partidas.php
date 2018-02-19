<html>
    <head>
        <meta charset="UTF-8">
        <title>Partidas</title>
    </head>
    <style>
        table tr:nth-child(even) {
            background-color: #eee;
        }

        table tr:nth-child(odd) {
            background-color: #fff;
        }</style>
    <body>
        <form action="../index.php" class="form" method="POST">
            <h2>Selecciona una partida anterior o empieza una nueva</h2>
            <table>
                <th>PARTIDAS GUARDADAS</th>
                <?php
                if (isset($_SESSION['partidas']) && count($_SESSION['partidas']) > 0) {
                    for ($index = 0; $index < count($_SESSION['partidas']); $index++) {
                        echo '<tr>';
                        $partida = $_SESSION['partidas'][$index];
                        echo '<td> Id partida' . $partida->getId() . '</td>';
                        echo '<td>Intentos -' . $partida->getIntentos() . '</td>';
                        echo '<td>Fallos -' . $partida->getFallos() . '</td>';
                        echo '<td>Palabra solucionada - ' . $partida->getSolucionada() . '</td>';
                        ?>
                        <td>Estado - <?php
                            echo $partida->getEstado();
                            if ($partida->getEstado() == 'empezada') {
                                echo "<td>CONTINUAR <input type='submit' name='continue' value='" . $partida->getId() . "'></td>";
                                echo "<td> <input type='submit' name='XML' value='". $partida->getId() ."'>XML</td>";
                            }
                            echo '</tr>';
                        }
                    }
                    ?>
                    <input type="submit" name="newGame" value="Nueva partida"><br>
                    <input type="submit" name="exit" value="Salir">
            </table>
        </form>
    </body>
</html>
