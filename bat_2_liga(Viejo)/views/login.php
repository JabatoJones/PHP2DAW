<html>
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
    </head>
    <body>
        <form name="form" action="../index.php" method="POST">
        <?php
            echo '<input type="text" name="nombre" value="" /><br>';
            echo '<input type="password" name="pass" value="" /><hr>';
            echo '<input type="submit" value="Login" name="login" />';
            echo '<input type="submit" value="Registro" name="registro" />';            
         ?>
        </form>
    </body>
</html>
