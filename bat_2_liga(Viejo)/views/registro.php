<html>
    <head>
        <meta charset="UTF-8">
        <title>Registro</title>
    </head>
    <body>
        <form name="form" action="../index.php" method="POST">
        <?php
            echo '<input type="text" name="nombre" value="" /><br>';
            echo '<input type="password" name="pass" value="" /><hr>';
            echo '<input type="submit" value="Regitrar" name="setRegistro" />';
            echo '<input type="submit" value="Cancelar" name="cancel" />';            
         ?>
        </form>
    </body>
</html>
