<?php
include('conexion.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (isset($_POST['candidato'])) {
    $candidato = $_POST['candidato'];

    $ip = file_get_contents('https://api.ipify.org');

    $token = $_SESSION['token'];

    $ubicacion = '';
    $geo = @file_get_contents("http://ip-api.com/json/{$ip}?fields=country,city");
    if ($geo) {
        $datos = json_decode($geo, true);
        if (isset($datos['country'])) {
            $ubicacion = $datos['country'] . ', ' . $datos['city'];
        }
    }

    $sentencia = $conexion->prepare("INSERT INTO votos (candidato, ip, ubicacion, token, estado, ingreso) VALUES (?, ?, ?, ?, 1, NOW())");
    $sentencia->bind_param("ssss", $candidato, $ip, $ubicacion, $token);
    $sentencia->execute();
    $sentencia->close();

    header("Location: ./");

    exit;
} elseif (!isset($_POST['candidato'])) {
    die("Debe elegir un candidato.");
}
exit;
