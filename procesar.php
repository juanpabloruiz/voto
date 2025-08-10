<?php
include('conexion.php');
$ip = $_POST['ip'];
$candidato = $_POST['candidato'];
$consulta = mysqli_query($conexion, "SELECT id FROM votos WHERE ip = '$ip'");
if (mysqli_num_rows($consulta) > 0) {
    mysqli_query($conexion, "INSERT INTO intentos (ip, candidato, intento_fecha) VALUES ('$ip', '$candidato', NOW())");
    header("Location: rechazo.php");
    exit;
}
mysqli_query($conexion, "INSERT INTO votos (candidato, ip, fecha) VALUES ('$candidato', '$ip', NOW())");
header("Location: resultados.php");
exit;
?>
