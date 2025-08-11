<?php
include('conexion.php');
include('config.php');
$candidatos = array_keys($candidates);
$totales = array_fill_keys($candidatos, 0);
$sentencia = $conexion->prepare("SELECT candidato, COUNT(*) AS totales FROM votos GROUP BY candidato");
$sentencia->execute();
$resultado = $sentencia->get_result();
while ($campo = $resultado->fetch_assoc()) {
    $candidato = $campo['candidato'];
    $total = (int)$campo['totales'];
    if (in_array($candidato, $candidatos)) {
        $totales[$candidato] = $total;
    }
}
$sentencia->close();
foreach ($totales as $candidato => $total) {
    $palabra = ($total === 1) ? 'voto' : 'votos';
    echo ucfirst($candidato) . ": $total $palabra<br>";
}