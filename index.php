<?php
include('conexion.php');
$ip = file_get_contents('https://api.ipify.org');


if (isset($_SESSION['token'])) {
    $token = $_SESSION['token'];

    // Verificar si está en la DB
    $res = mysqli_query($conexion, "SELECT 1 FROM token WHERE codigo = '$token'");
    if (mysqli_num_rows($res) == 0) {
        // Si no existe en DB, regenerar
        $token = bin2hex(random_bytes(32));
        $_SESSION['token'] = $token;
        mysqli_query($conexion, "INSERT INTO token (codigo, estado, creado) VALUES ('$token', 0, NOW())");
    }
} else {
    // Si no hay token en sesión, crearlo
    $token = bin2hex(random_bytes(32));
    $_SESSION['token'] = $token;
    mysqli_query($conexion, "INSERT INTO token (codigo, estado, creado) VALUES ('$token', 0, NOW())");
}

$consulta = mysqli_query($conexion, "SELECT estado FROM token WHERE codigo = '$token'");
$campo = mysqli_fetch_assoc($consulta);
if ($campo['estado'] == 1) {
    echo '<div>Usted ya votó token</Div>';
    include('resultados.php');
    exit;
}

$consulta = mysqli_query($conexion, "SELECT id FROM votos WHERE ip = '$ip'");
if (mysqli_num_rows($consulta) > 0) {
    echo '<div>Usted ya votó ip</Div>';
    include('resultados.php');
    exit;
}

?>
<form method="POST" action="procesar.php">
    <input type="hidden" name="ip" value="<?php echo $ip; ?>">
    <input type="radio" name="candidato" value="valdes">Valdes
    <input type="radio" name="candidato" value="colombi">Colombi
    <input type="radio" name="candidato" value="tincho">Tincho
    <input type="submit" value="Votar">
</form>