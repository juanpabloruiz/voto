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

// Insertar voto
$stmt = $conexion->prepare("INSERT INTO votos (candidato, ip, fecha) VALUES (?, ?, NOW())");
$stmt->bind_param('ss', $candidato, $ip);
$stmt->execute();
$stmt->close();

// Marcar token como "ya votó"
if (isset($_SESSION['token'])) {
    $token = $_SESSION['token'];
    $stmt = $conexion->prepare("UPDATE token SET estado = 1 WHERE codigo = ?");
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $stmt->close();
}

echo json_encode(['status' => 'success', 'message' => '']);
exit;