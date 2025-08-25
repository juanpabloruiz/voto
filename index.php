<?php
include('conexion.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// función que devuelve bien la IP real
//function cliente_ip() {
//if (!empty($_SERVER['HTTP_CLIENT_IP'])) return $_SERVER['HTTP_CLIENT_IP'];
// if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
//  return $_SERVER['REMOTE_ADDR'];
//}

//$ip = cliente_ip();











?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voto</title>
    <style>
        body {
            background: #f8f9fa;
            font-family: sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #0066fe;
            padding: 0.5rem 1rem;
        }

        header img {
            height: 55px;
            display: block;
        }

        .contenedor {
            max-width: 900px;
            margin: 2rem auto;
            padding: 1rem;
        }

        .titulo {
            font-weight: bold;
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }

        /* Grilla */
        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.2rem;
        }

        .candidatos,
        .resultados {
            background: #fff;
            padding: 1rem;
            border-radius: 1rem;
            box-shadow: 0 0 6px rgba(0, 0, 0, 0.05);
        }

        @media (max-width: 768px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }

        /* Lista candidatos */
        .candidato {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f8f9fa;
            padding: 0.6rem 0.8rem;
            border-radius: 0.75rem;
            margin-bottom: 0.6rem;
            transition: background 0.2s;
        }

        .candidato:hover {
            background: #eef3ff;
        }

        .candidato-info {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .candidato img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .candidato .nombre {
            font-weight: 600;
        }

        .btn-votar {
            background: #007bff;
            color: #fff;
            border: none;
            padding: 0.4rem 0.8rem;
            border-radius: 0.5rem;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-votar:hover {
            background: #0056b3;
        }

        /* Resultados */
        .resultado {
            margin-bottom: 0.8rem;
        }

        .barra-info {
            display: flex;
            justify-content: space-between;
            font-weight: 500;
            margin-bottom: 0.3rem;
        }

        .progress {
            background: #e9ecef;
            height: 6px;
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background-color: #007bff;
            width: 0%;
            transition: width 1s ease-in-out;
        }

        .total-votos {
            text-align: center;
            margin-top: 0.8rem;
            font-weight: bold;
            color: #555;
        }

        /* Overlay */
        .overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            flex-direction: column;
            font-size: 1.2rem;
            display: none;
        }

        .loader {
            border: 6px solid #f3f3f3;
            border-top: 6px solid #007bff;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin-bottom: 1rem;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <header>
        <a href="../"><img src="img/logo.png" alt="Logo"></a>
    </header>

    <div class="contenedor">
        <div class="grid">

            <!-- Columna izquierda: Candidatos -->
            <div class="candidatos">
                <?php

                $ip = file_get_contents('https://api.ipify.org');
                echo $ip.'<br>';

                if (!isset($_SESSION['token'])) {
                    $_SESSION['token'] = bin2hex(random_bytes(32));
                }
                $token = $_SESSION['token'];
                echo $token;

                // Lista de candidatos con imágenes
                $candidatos = [
                    "Pablo Valdés" => "img/valdes.jpg",
                    "Ricardo Colombi" => "img/colombi.jpg",
                    "Ezequiel Romero" => "img/romero.jpg",
                    "Martín Ascúa" => "img/ascua.jpg",
                    "Lisandro Almirón" => "img/almiron.jpg",
                    "Sonia López" => "img/lopez.jpg",
                    "Adriana Vega" => "img/vega.jpg"
                ];

                // Total de votos válidos
                $queryTotal = mysqli_query($conexion, "SELECT COUNT(*) AS total FROM votos");
                $rowTotal = mysqli_fetch_assoc($queryTotal);
                $totalVotos = $rowTotal['total'];

                // Inicializar array de resultados con 0
                $resultados = [];
                foreach (array_keys($candidatos) as $candidato) {
                    $resultados[$candidato] = 0;
                }

                // Traer votos por candidato desde DB
                $queryResultados = mysqli_query($conexion, "
                    SELECT candidato, COUNT(*) AS cantidad 
                    FROM votos 
                    GROUP BY candidato
                ");

                while ($row = mysqli_fetch_assoc($queryResultados)) {
                    $nombre = $row['candidato'];
                    $cantidad = $row['cantidad'];
                    if (isset($resultados[$nombre])) {
                        $resultados[$nombre] = $cantidad;
                    }
                }

                // Verificar si ya votó por IP o por navegador (token)
                $sql = "SELECT 1 FROM votos WHERE ip = ? OR token = ? LIMIT 1";
                $stmt = $conexion->prepare($sql);
                $stmt->bind_param("ss", $ip, $token);
                $stmt->execute();
                $yaVoto = $stmt->get_result()->num_rows > 0;

                if ($yaVoto) {
                    echo '<p>Gracias por tu voto!</p>';
                } else {
                    echo '<p class="titulo">Elegí tu candidato a gobernador y mirá el ranking en vivo:</p>';
                    echo '<form id="formVoto" method="POST" action="votar.php">';
                    foreach ($candidatos as $nombre => $img) {
                        echo '<label class="candidato">';
                        echo '<div class="candidato-info">';
                        echo '<img src="' . $img . '" alt="' . $nombre . '">';
                        echo '<span class="nombre">' . $nombre . '</span>';
                        echo '</div>';
                        echo '<input type="radio" name="candidato" value="' . $nombre . '" hidden>';
                        echo '<button type="button" class="btn-votar" onclick="votarCandidato(this)">Votar</button>';
                        echo '</label>';
                    }
                    echo '</form>';
                }
                ?>
            </div>

            <!-- Columna derecha: Resultados -->
            <div class="resultados">
                <?php
                foreach ($resultados as $nombre => $cantidad) {
                    $porcentaje = $totalVotos > 0 ? round(($cantidad / $totalVotos) * 100) : 0;
                    echo '<div class="resultado">';
                    echo '<div class="barra-info"><span>' . $nombre . '</span><span>' . $porcentaje . '%</span></div>';
                    echo '<div class="progress"><div class="progress-bar" data-porcentaje="' . $porcentaje . '"></div></div>';
                    echo '</div>';
                }

                ?>
            </div>
        </div>

        <!-- Overlay -->
        <div id="overlay" class="overlay">
            <div class="loader"></div>
            <p>Votando...</p>
        </div>
    </div>

    <script>
        const form = document.getElementById('formVoto');
        const overlay = document.getElementById('overlay');

        function votarCandidato(btn) {
            const label = btn.closest('.candidato');
            const radio = label.querySelector('input[type=radio]');
            radio.checked = true;
            if (confirm("¿Confirmas tu voto?")) {
                overlay.style.display = "flex";
                setTimeout(() => form.submit(), 1000);
            }
        }

        window.addEventListener('load', () => {
            document.querySelectorAll('.progress-bar').forEach(bar => {
                const porcentaje = bar.getAttribute('data-porcentaje');
                setTimeout(() => bar.style.width = porcentaje + '%', 100);
            });
        });
    </script>
</body>

</html>