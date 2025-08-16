<?php
include('conexion.php');

if (isset($_POST['votar'])) {

    $candidato = $_POST['candidato'];

    $ip = file_get_contents('https://api.ipify.org');

    $ubicacion = '';
    $geo = @file_get_contents("http://ip-api.com/json/{$ip}?fields=country,city");
    if ($geo) {
        $datos = json_decode($geo, true);
        if (isset($datos['country'])) {
            $ubicacion = $datos['country'] . ', ' . $datos['city'];
        }
    }

    $token = bin2hex(random_bytes(32));

    $_SESSION['token'] = $token;

    $sentencia = $conexion->prepare("INSERT INTO votos2 (candidato, ip, ubicacion, token, estado, ingreso) VALUES (?, ?, ?, ?, 1, NOW())");
    $sentencia->bind_param("ssss", $candidato, $ip, $ubicacion, $token);
    $sentencia->execute();

    header("Location: ./");

    exit;
} elseif (!isset($_POST['candidato'])) {
    die("Debe elegir un candidato.");
}
exit;