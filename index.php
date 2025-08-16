<?php
include('conexion.php');

// Asignamos la IP a una variable
$ip = file_get_contents('https://api.ipify.org');

$consulta = mysqli_query($conexion, "SELECT * FROM votos2 WHERE ip = '$ip'");
$campo = mysqli_fetch_assoc($consulta);

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voto</title>
    <meta name="description" content="Sitio web para votar al mejor candidato">

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-X9SPDSLJ1P"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-X9SPDSLJ1P');
    </script>

</head>

<body>

    <h1>Voto</h1>

    <?php
    if ($campo && $campo['ip'] == $ip || $campo['estado'] == 1) {
        echo 'Su voto ya fue registrado, muchas gracias';
    } else {
    ?>

        <h2>Votemos</h2>

        <form method="POST" action="votar.php">
            <label>
                <input type="radio" name="candidato" value="Valdés"> Valdés
            </label>
            <label>
                <input type="radio" name="candidato" value="Colombi"> Colombi
            </label>
            <label>
                <input type="radio" name="candidato" value="Azcúa"> Azcúa
            </label>
            <input type="submit" name="votar" value="Votar">
        </form>

    <?php
    }
    ?>

</body>

</html>