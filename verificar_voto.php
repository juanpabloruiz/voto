<?php
session_start();
include('conexion.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$ip = $data['ip'] ?? '';
$token = $data['token'] ?? '';

$sql = "SELECT 1 FROM votos WHERE ip = ? OR token = ? LIMIT 1";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ss", $ip, $token);
$stmt->execute();
$yaVoto = $stmt->get_result()->num_rows > 0;

echo json_encode(['yaVoto' => $yaVoto]);
