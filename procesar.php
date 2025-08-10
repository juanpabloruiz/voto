<?php
include('conexion.php');
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['ip']) || empty($_POST['candidato'])) {
    die('Acceso inválido');
}
$token = $_SESSION['token'];
$candidato = $_POST['candidato'];
$ip = $_POST['ip'];
//$consulta = mysqli_query($conexion, "SELECT id FROM votos WHERE ip = '$ip'");
//if (mysqli_num_rows($consulta) > 0) {
//    mysqli_query($conexion, "INSERT INTO intentos (ip, candidato, intento_fecha) VALUES ('$ip', '$candidato', NOW())");
//    echo 'Usted ya votó';
//    exit;
//}
if ($token !== '') {
    mysqli_query($conexion, "UPDATE token SET estado = 1 WHERE codigo = '$token'");
}
mysqli_query($conexion, "INSERT INTO votos (candidato, ip, fecha) VALUES ('$candidato', '$ip', NOW())");
header("Location: resultados.php");
exit;
?>
