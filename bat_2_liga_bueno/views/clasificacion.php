<html>
    <head>
        <meta charset="UTF-8">
        <title>Clasificación</title>
    </head>
    <body>
        <table style="border: 1px solid black;">
            <thead>
                <tr>
                    <th>POSICION</th>
                    <th>EQUIPO</th>
                    <th>GOLES A FAVOR</th>
                    <th>GOLES EN CONTRA</th>
                    <th>GOLAVERAGE</th>
                    <th>PUNTOS</th>
                </tr>
            </thead>
            <tbody style="border: 1px solid black;">
                <?php 
                $x = 1;
                foreach ($clasificacion as $nombre => $equipo) {                    
                ?>
                <tr>
                    <td> <?= $x; ?> º</td>
                    <td><?= $nombre; ?></td>
                    <td><?= $equipo['GF']; ?></td>
                    <td><?= $equipo['GC']; ?></td>
                    <td><?= $equipo['GA']; ?></td>
                    <td><?= $equipo['Puntos']; ?></td>
                </tr>
                <?php
                $x++;} 
                ?>
            </tbody>
        </table>
        <form action="index.php" method="POST">
            <input type='submit' value='Volver' name='volver'>
        </form>
    </body>
</html>