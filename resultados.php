<?php
include('conexion.php');

// Lista de candidatos
$candidatos = [
    'Juan Pablo Valdés',
    'Horacio Ricardo Colombi',
    'Carlos Ezequiel Romero',
    'Martín Ignacio Ascúa',
    'Lisandro Almirón',
    'Sonia Beatriz López',
    'Adriana Leila Vega'
];

// Imágenes
$imagenes = [
    'Juan Pablo Valdés' => 'img/valdes.jpg',
    'Horacio Ricardo Colombi' => 'img/colombi.jpg',
    'Carlos Ezequiel Romero' => 'img/romero.jpg',
    'Martín Ignacio Ascúa' => 'img/ascua.jpg',
    'Lisandro Almirón' => 'img/almiron.jpg',
    'Sonia Beatriz López' => 'img/lopez.jpg',
    'Adriana Leila Vega' => 'img/vega.jpg'
];

// Inicializamos conteo
$conteo = array_fill_keys($candidatos, 0);

// SQL con placeholders
$placeholders = implode(',', array_fill(0, count($candidatos), '?'));
$consulta = "SELECT candidato, COUNT(*) AS total_votos 
             FROM votos 
             WHERE candidato IN ($placeholders) 
             GROUP BY candidato";

$sentencia = $conexion->prepare($consulta);
$tipos = str_repeat('s', count($candidatos));
$sentencia->bind_param($tipos, ...$candidatos);
$sentencia->execute();
$sentencia->bind_result($nombreCandidato, $votos);
while ($sentencia->fetch()) {
    $conteo[$nombreCandidato] = $votos;
}
$sentencia->close();

// Ordenar resultados de mayor a menor por votos
arsort($conteo);

// Total de votos
$totalVotos = array_sum($conteo);

// Mostrar resultados como tarjetas en 2 columnas
echo "<div class='resultados' style='
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 0.7rem;
'>";
foreach ($conteo as $nombre => $votos) {
    $porcentaje = $totalVotos > 0 ? ($votos / $totalVotos) * 100 : 0;
    $textoVoto = $votos === 1 ? "voto" : "votos";
    $img = $imagenes[$nombre] ?? 'img/default.jpg';
    
    echo "<div class='tarjeta' style='
        display:flex;
        align-items:center;
        border:1px solid #ccc;
        border-radius:8px;
        padding:0.5rem;
        box-shadow: inset 0 0 10px rgba(0,0,0,0.5);
    '>";
    echo "
    <div style='
        width:80px;
        height:75px;
        border-radius:6px;
        overflow:hidden;
        box-shadow: inset 0 0 50px rgba(0,0,0,0.5);
        margin-right:0.75rem;
    '>
        <img src='$img' alt='$nombre' style='
            width:80px;
            height:75px;
            border-radius:6px;
            object-fit:cover;
            object-position: top;
        '>
    </div>";
    echo "<div style='display:flex;flex-direction:column;'>";
    echo "<strong>$nombre</strong>";
    echo "<strong style='color:red;font-size:2rem;'>" . round($porcentaje,2) . "%</strong>";
    echo "<small>$votos $textoVoto</small>";
    echo "</div>";
    echo "</div>";
}
echo "</div>";

?>
