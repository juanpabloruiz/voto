<?php
session_start();
include('conexion.php');
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
    exit;
}
$candidato = $_POST['candidato'] ?? '';
$ip = $_SERVER['REMOTE_ADDR'];

if (empty($candidato)) {
    echo json_encode(['status' => 'error', 'message' => 'Debe seleccionar un candidato.']);
    exit;
}

$sentencia = $conexion->prepare("INSERT INTO votos (candidato, ip, fecha) VALUES (?, ?, NOW())");
$sentencia->bind_param('ss', $candidato, $ip);

if ($sentencia->execute()) {
    if (isset($_SESSION['token'])) {
        $token = $_SESSION['token'];
        $update_sentencia = $conexion->prepare("UPDATE tokens_voto SET estado = 'usado' WHERE token = ?");
        $update_sentencia->bind_param('s', $token);
        $update_sentencia->execute();
        $update_sentencia->close();
    }
    echo json_encode(['status' => 'success', 'message' => 'Gracias por votar']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error al procesar el voto.']);
}

$sentencia->close();
$conexion->close();
exit;