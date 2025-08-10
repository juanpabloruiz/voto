<?php
include('conexion.php');
$candidato = $_POST['candidato'];
$ip = $_POST['ip'];
mysqli_query($conexion, "INSERT INTO votos (candidato, ip, fecha) VALUES ('$candidato', '$ip', NOW())");
header("Location: resultados.php");
?>