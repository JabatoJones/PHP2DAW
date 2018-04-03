<html>
    <head>
        <meta charset="UTF-8">
        <title>Liga</title>
    </head>
    <body>
        <form name="form" action="../index.php" method="POST">
            <h3>Introduce el nombre de la liga y de los equipos</h3>
            <h4>*El nombre de los equipos debe ir separado por comas</h4>
        <?php
            echo '<input type="text" name="equipos" placeholder="Equipos" value="" /><br>';
            echo '<input type="text" name="liga" value="" placeholder="Nombre Liga"/><hr>';
            echo '<input type="submit" value="Empezar" name="start" />';           
         ?>
        </form>
    </body>
</html>
