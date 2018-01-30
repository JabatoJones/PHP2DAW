<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
    </head>
    <script src="../css/estilos.css"></script>
    <script>
        function changeMode() {
            var pass = document.getElementById('pass');
            pass.type = pass.type === 'text' ? 'password' : 'text';

        }
    </script>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <form action="../index.php" method="POST" role="form">
                                <fieldset>
                                    <div class="panel-heading">
                                        <h3 style="text-align: center" class="panel-title"></h3>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="User" name="user" type="text" autofocus>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" id="pass" placeholder="Password" name="pass" type="password" value="">
                                    </div>
                                    <div><?php isset($mensaje) ? print_r($mensaje) : '';?></div>
                                    <input type="button" class="btn btn-info btn-info" onclick="changeMode();" value="Ver"></button><hr>
                                    <input type="submit" name="login" class="btn btn-success btn-block" value="Login"></input>
                                    <input type="submit" name="registro" class="btn btn-success btn-block"value="Registrarse"></input>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
