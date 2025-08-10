<?php
include('conexion.php');
$consulta = mysqli_query($conexion, "SELECT candidato, COUNT(*) AS total_votos FROM votos GROUP BY candidato ORDER BY total_votos DESC");
while($campo = mysqli_fetch_assoc($consulta)) {
    echo $campo['candidato'] . ': ' . $campo['total_votos'] . ' votos<br>';
}
?>