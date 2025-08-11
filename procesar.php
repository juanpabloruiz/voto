<?php
include('conexion.php');
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
    exit;
}
$ip = $_POST['ip'] ?? '';
$candidato = $_POST['candidato'] ?? '';
if (!$ip || !$candidato) {
    echo json_encode(['status' => 'error', 'message' => 'Faltan datos']);
    exit;
}
mysqli_query($conexion, "INSERT INTO votos (candidato, ip, fecha) VALUES ('$candidato', '$ip', NOW())");
echo json_encode(['status' => 'success', 'message' => 'Gracias por votar']);
exit;