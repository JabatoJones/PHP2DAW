<html>
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
    </head>
    <body>
        <h2>LOGIN USUARIO</h2>
        <form action="index.php" method="POST" class="form">
            <p style="color:red"><?=isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : ''?></p>
            <input type="text" name="usuario" placeholder="Usuario">
            <input type="text" name="pass" placeholder="Contraseña">
            <br>
            <input type="submit" name="login" value="Login"><br>
            <input type="submit" name="registro" value="Registro">
        </form>
    </body>
</html>