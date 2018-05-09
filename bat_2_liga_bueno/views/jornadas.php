<html>
    <head>
        <meta charset="UTF-8">
        <title>Jornadas</title>
    </head>
    <body>
        <form action='index.php' method='post'>
            <table  style="border: 1px solid black;">
                   <thead>
                    <tr>
                        <td>Jornada</td>
                        <td>Fecha</td>
                        <td></td>
                    </tr>
                <thead>
                    <?php while ($jornada = $_SESSION['liga']->getJornadas()->iterate()) { ?>
                        <tr><?php
                            echo "<td>Jornada " . $jornada->getId() . "</td>";
                            echo "<td>" . $jornada->getFecha() . "</td>";
                            echo "<td><input type='radio' name='id' value=" . $jornada->getId() . "></td>";
                            ?></tr><?php } ?>
            </table>
            <input type='submit' value='Ver' name='verJornada'>
            <input type='submit' value='Salir' name='logout'>
            <input type='submit' value='Clasificacion' name='clasificacion'>
        </form>
    </body>
</html>
