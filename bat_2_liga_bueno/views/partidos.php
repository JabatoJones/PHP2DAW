<html>
    <head>
        <meta charset="UTF-8">
        <title>Partidos</title>
    </head>
    <body>
        <form action='index.php' method='post'>
            <table style="border: 1px solid black;">
                <thead>
                <tr>
                    <th>LOCAL</th>
                    <th></th>
                    <th></th>
                    <th>VISITANTE</th>
                    <th></th>
                </tr>
            </thead>
                <?php
                $x = 0;
                while ($partido = $jornada->getpartidos()->iterate()) {
                    ?>
                    <tr>
                        <?php
                        echo "<td><input type='hidden' value='" . $partido->getId() . "' name='resultados[$x][id]'><input type='hidden' name='resultados[$x][eqL]' value='" . $partido->getEquipoL() . "'>" . $partido->getEquipoL(). "</td>";
                        if ($partido->getEquipoL() == $_SESSION['descanso']->getNombre() || $partido->getEquipoV() == $_SESSION['descanso']->getNombre()) {
                            echo "<td><input type='text' readonly value='No Juega' name='resultados[$x][gL]'></td>";
                        } else {
                            echo "<td><input type='number' value='" . $partido->getGV() . "' name='resultados[$x][gL]'></td>";
                        }

                        echo "<td><input type='hidden' name='resultados[$x][eqV]' value='" . $partido->getId() . "'>" . $partido->getEquipoV() . "</td>";
                        if ($partido->getEquipoL() == $_SESSION['descanso']->getNombre() || $partido->getEquipoV() == $_SESSION['descanso']->getNombre()) {
                            echo "<td><input type='text' readonly value='No Juega' name='resultados[$x][gV]'></td>";
                        } else {
                            echo "<td><input type='number' value='" . $partido->getGL() . "'name='resultados[$x][gV]'></td>";
                        }
                        ?>
                    </tr>
                    <?php $x++;
                    }
                    ?>
                </table>
                <td><input type='hidden' value='<?= $idJornada ?>' name='id'></td>
            <input type='submit' value='Guargar' name='guardarPartidos'>
            <input type='submit' value='Volver' name='volver'>
            <input type='submit' value='Salir' name='logout'>
        </form>
    </body>
</html>