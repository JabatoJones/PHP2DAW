<form  action="index" method="POST">
    <input type="submit" name="volver" value="Volver">
</form>
<?php
echo htmlentities($_SESSION['xml'],ENT_COMPAT, 'UTF-8');
?>
