<?php
include('conexion.php');

$ip = file_get_contents('https://api.ipify.org');

if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}
$token = $_SESSION['token'];

$consulta = mysqli_query($conexion, "SELECT * FROM votos WHERE (ip = '$ip' OR token = '$token') AND estado = 1 LIMIT 1");
$campo = mysqli_fetch_assoc($consulta);

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voto</title>
    <!-- Estilo -->
    <link rel="preload" href="css/style.css" as="style">
    <link rel="stylesheet" href="css/style.css">

    <!-- Meta tags Google -->
    <meta name="description" content="Sitio web creado como prototipo para la votación de candidatos o témas de interés general.">
    <meta name="keywords" content="Corrientes, Argentina, Sociedad, Política, Votación, Candidatos, Prototipo">
    <meta name="robots" content="index, follow">
    <meta name="language" content="es_ES">
    <meta name="author" content="Juan Pablo Ruiz">
    <meta name="theme-color" content="#ffffff">

    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="https://voto.ar/iconos/ms-icon-144x144.png">

    <!--- Íconos -->
    <link rel="apple-touch-icon" sizes="57x57" href="https://voto.ar/iconos/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="https://voto.ar/iconos/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="https://voto.ar/iconos/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="https://voto.ar/iconos/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="https://voto.ar/iconos/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="https://voto.ar/iconos/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="https://voto.ar/iconos/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="https://voto.ar/iconos/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="https://voto.ar/iconos/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="https://voto.ar/iconos/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="https://voto.ar/iconos/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="https://voto.ar/iconos/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="https://voto.ar/iconos/favicon-16x16.png">
    <link rel="manifest" href="https://voto.ar/iconos/manifest.json">
    <link rel="canonical" href="https://voto.ar">

    <!-- Open Graph para WhatsApp, Facebook, Telegram -->
    <meta property="og:title" content="Voto">
    <meta property="og:locale" content="es_LA">
    <meta property="og:description" content="Sistema prototipo de votación online.">
    <meta property="og:url" content="https://voto.ar">
    <meta property="og:type" content="business.business">
    <meta property="og:image" content="https://voto.ar/img/logo1200x630.png?v=1">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="Logo de Voto">


    <!-- Twitter Card (opcional, también funciona en WhatsApp) -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Voto">
    <meta name="twitter:description" content="Sistema prototipo de votación online.">
    <meta name="twitter:image" content="https://voto.ar/img/logo1200x630.png.png?v=1">

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

    <style>
        .opcion {
            filter: grayscale(100%);
            cursor: pointer;
            border-radius: 12px;
            transition: filter 0.3s ease;
            width: 120px;
            height: auto;
            margin: 5px;
        }

        .opcion.selected {
            filter: grayscale(0%);
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #000000;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            color: white;
            font-size: 1.5em;
            z-index: 9999;
            display: none;
            /* inicia oculto */
        }

        .loader {
            width: 120px;
            height: 17px;
            border-radius: 5px;
            color: #dee7ffff;
            border: 1px solid;
            position: relative;
        }

        .loader::before {
            content: "";
            position: absolute;
            margin: 1px;
            inset: 0 100% 0 0;
            background: currentColor;
            animation: l6 3s;
        }

        @keyframes l6 {
            100% {
                inset: 0
            }
        }

        .btn-votar {
            background-color: #0d6efd;
            /* color primario de Bootstrap */
            color: #fff;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            font-weight: 500;
            border: none;
            border-radius: 0.375rem;
            /* border-radius de Bootstrap 5 */
            cursor: pointer;
            transition: background-color 0.2s, transform 0.1s;
            -webkit-appearance: none;
            /* Safari */
            -moz-appearance: none;
            /* Firefox */
            appearance: none;
            /* general */
        }

        .btn-votar:hover {
            background-color: #0b5ed7;
        }
        header {
            background-color: #0066fe;
        }
    </style>

</head>

<body>

    <header>
        <a href="../">
            <picture>
                <source srcset="img/logo.webp" type="image/webp" />
                <img src="img/logo.png" fetchpriority=high width="150" height="55" alt="Logotipo" />
            </picture>
        </a>
    </header>

    <div class="contenedor">
        <?php
        if ($campo && $campo['ip'] == $ip || $campo['token'] == $token && $campo['estado'] == 1) {
            include('resultados.php');
        } else {
        ?>

            <p>Elija su candidato a gobernador favorito:</p>

            <form id="formVoto" method="POST" action="votar.php">
                <div class="contenedor-opciones">
                    <label>
                        <input type="radio" name="candidato" value="Juan Pablo Valdés" hidden>
                        <img src="img/valdes.jpg" fetchpriority=high class="opcion" alt="Juan Pablo Valdés">
                    </label>
                    <label>
                        <input type="radio" name="candidato" value="Horacio Ricardo Colombi" hidden>
                        <img src="img/colombi.jpg" fetchpriority=high class="opcion" alt="Horacio Ricardo Colombi">
                    </label>
                    <label>
                        <input type="radio" name="candidato" value="Carlos Ezequiel Romero" hidden>
                        <img src="img/romero.jpg" fetchpriority=high class="opcion" alt="Carlos Ezequiel Romero">
                    </label>
                    <label>
                        <input type="radio" name="candidato" value="Martín Ignacio Ascúa" hidden>
                        <img src="img/ascua.jpg" fetchpriority=high class="opcion" alt="Martín Ignacio Ascúa">
                    </label>
                    <label>
                        <input type="radio" name="candidato" value="Lisandro Almirón" hidden>
                        <img src="img/almiron.jpg" fetchpriority=high class="opcion" alt="Lisandro Almirón">
                    </label>
                    <label>
                        <input type="radio" name="candidato" value="Sonia Beatriz López" hidden>
                        <img src="img/lopez.jpg" fetchpriority=high class="opcion" alt="Sonia Beatriz López">
                    </label>
                    <label>
                        <input type="radio" name="candidato" value="Adriana Leila Vega" hidden>
                        <img src="img/vega.jpg" fetchpriority=high class="opcion" alt="Adriana Leila Vega">
                    </label>
                </div>
                <br>
                <button type="submit" class="btn-votar">Votar</button>
            </form>


            <!-- Overlay con GIF -->
            <div id="overlay" class="overlay">
                <div class="loader"></div>



                <p>Ingresando voto...</p>
            </div>

        <?php
        }
        ?>

    </div>
    <script>
        function customConfirm(message, callback) {
            const modal = document.getElementById("customConfirm");
            document.getElementById("customConfirmText").innerText = message;
            modal.style.display = "flex";

            const yesBtn = document.getElementById("customConfirmYes");
            const noBtn = document.getElementById("customConfirmNo");

            yesBtn.onclick = () => {
                modal.style.display = "none";
                callback(true);
            }
            noBtn.onclick = () => {
                modal.style.display = "none";
                callback(false);
            }
        }

        const radios = document.querySelectorAll('input[name="candidato"]');
        const imagenes = document.querySelectorAll('.opcion');
        const form = document.getElementById('formVoto');
        const overlay = document.getElementById('overlay');

        // Cambio de gris a color al seleccionar
        radios.forEach((radio, i) => {
            radio.addEventListener('change', () => {
                imagenes.forEach(img => img.classList.remove('selected'));
                if (radio.checked) imagenes[i].classList.add('selected');
            });
        });

        // Confirmación + overlay + envío retrasado
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // evitamos envío inmediato

            if (!document.querySelector('input[name="candidato"]:checked')) {
                alert("Debes elegir un candidato.");
            } else {
                customConfirm("¿Confirmas tu voto?", (ok) => {
                    if (ok) {
                        overlay.style.display = "flex";
                        setTimeout(() => form.submit(), 1500);
                    }
                });
            }
        });
    </script>

    <!-- Modal único en tu HTML -->
    <div id="customConfirm" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);justify-content:center;align-items:center;z-index:9999;">
        <div style="background:#fff;padding:1.5rem;border-radius:0.5rem;text-align:center;max-width:300px;width:90%;">
            <p id="customConfirmText"></p>
            <button id="customConfirmYes" style="background:#0d6efd;color:#fff;margin:0.5rem;padding:0.5rem 1rem;border:none;border-radius:0.25rem;cursor:pointer;">Sí</button>
            <button id="customConfirmNo" style="background:#6c757d;color:#fff;margin:0.5rem;padding:0.5rem 1rem;border:none;border-radius:0.25rem;cursor:pointer;">No</button>
        </div>
    </div>

</body>

</html>