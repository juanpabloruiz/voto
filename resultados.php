<?php
include('conexion.php');

// Lista de candidatos
$candidatos = [
    'Juan Pablo Valdés',
    'Horacio Ricardo Colombi',
    'Carlos Ezequiel Romero',
    'Martín Ignacio Ascúa',
    'Lisandro Almirón'
];

// Inicializamos conteo en 0
$conteo = [];
foreach ($candidatos as $candidato) {
    $conteo[$candidato] = 0;
}

// Creamos placeholders para IN (?, ?, ...)
$posicion = implode(',', array_fill(0, count($candidatos), '?'));
$consulta = "SELECT candidato, COUNT(*) AS total_votos
        FROM votos
        WHERE candidato IN ($posicion)
        GROUP BY candidato
        ORDER BY total_votos DESC";

// Preparamos sentencia
$sentencia = $conexion->prepare($consulta);

// Creamos tipos y parámetros
$tipos = str_repeat('s', count($candidatos)); // todos strings
$sentencia->bind_param($tipos, ...$candidatos);

// Ejecutamos
$sentencia->execute();

// Guardamos resultados
$sentencia->bind_result($nombreCandidato, $votos);
while ($sentencia->fetch()) {
    $conteo[$nombreCandidato] = $votos;
}

$sentencia->close();

// Ordenamos de mayor a menor
arsort($conteo);

// Calculamos total de votos
$totalVotos = array_sum($conteo);

foreach ($conteo as $nombre => $votos) {
    $porcentaje = $totalVotos > 0 ? ($votos / $totalVotos) * 100 : 0;
    $textoVoto = $votos === 1 ? "voto" : "votos"; // singular/plural
    echo $nombre . ": <span style='color:red;'>" . round($porcentaje, 2) . "%</span> | <small>" . $votos . " " . $textoVoto . " </small><br>";
}