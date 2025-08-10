<?php
include('conexion.php');
$ip = file_get_contents('https://api.ipify.org');
?>
<form method="POST" action="procesar.php">
    <input type="text" name="ip" value="<?php echo $ip; ?>">
    <input type="radio" name="candidato" value="valdes">Valdes
    <input type="radio" name="candidato" value="colombi">Colombi
    <input type="radio" name="candidato" value="tincho">Tincho
    <input type="submit" value="Votar">
</form>