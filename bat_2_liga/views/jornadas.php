<html>
    <head>
        <meta charset="UTF-8">
        <title>Jornadas</title>
    </head>
    <body>
        <?php
        echo "<form action='index.php' method='post'>";
        echo "<table>";
        while($jornada = $_SESSION['liga']->getJornadas()->iterate()){
            
            echo "<tr><td><input type='radio' name='id' value=".$jornada->getId()."> Jornada ".$jornada->getId()." ".$jornada->getFecha()."</td></tr>";
        }
        echo"</table>";
        echo "<input type='submit' value='Ver' name='ver'>";
        echo "<input type='submit' value='Salir' name='salir'>";
        echo "<input type='submit' value='Clasificacion' name='clasificacion'>";
        echo "</form>";
        ?>
    </body>
</html>
