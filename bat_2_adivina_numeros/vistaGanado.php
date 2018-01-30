<html>
    <head>
        <meta charset="UTF-8">
        <title>Adivina numero</title>
    </head>
    <body>
        <form method="POST" action="index.php">
            Has ganado con <?= $partida->getIntentos();?> itentos <br>
            El numero elegido era <?= $partida->getNumSecreto();?>
            <input type="submit" name="try" value="Nueva partida"> 
        </form>
    </body>
</html>
