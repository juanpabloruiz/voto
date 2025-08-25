<?php
include('conexion.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$ip = file_get_contents('https://api.ipify.org');

function obtener_ubicacion($ip) {
    // API pública gratuita (hasta cierto límite por día)
    $url = "http://ipinfo.io/{$ip}/json";
    $json = @file_get_contents($url);
    if ($json) {
        $data = json_decode($json, true);
        return [
            "pais" => $data['country'] ?? null,
            "region" => $data['region'] ?? null,
            "ciudad" => $data['city'] ?? null
        ];
    }
    return ["pais" => null, "region" => null, "ciudad" => null];
}


$ubicacion = obtener_ubicacion($ip);

$pais = $ubicacion['pais'];
$region = $ubicacion['region'];
$ciudad = $ubicacion['ciudad'];

$ubicacion = $pais.'/'.$region.'/'.$ciudad;


$token = $_SESSION['token'];
$candidato = $_POST['candidato'] ?? '';

// Insertar solo si no votó antes
$sql = "SELECT 1 FROM votos WHERE (ip = ? OR token = ?) AND estado = 1 LIMIT 1";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ss", $ip, $token);
$stmt->execute();
$yaVoto = $stmt->get_result()->num_rows > 0;

if (!$yaVoto && $candidato) {
    $stmt = $conexion->prepare("INSERT INTO votos (candidato, ip, ubicacion, token, estado, ingreso) VALUES (?, ?, ?, ?, 1, NOW())");
    $stmt->bind_param("ssss", $candidato, $ip, $ubicacion, $token);
    $stmt->execute();
}

header("Location: ./");
exit;
