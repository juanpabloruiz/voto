<?php
include('conexion.php');
$ip = file_get_contents('https://api.ipify.org');
if (isset($_SESSION['token'])) {
    $token = $_SESSION['token'];
    $sentencia = $conexion->prepare("SELECT 1 FROM token WHERE codigo = ? LIMIT 1");
    $sentencia->bind_param('s', $token);
    $sentencia->execute();
    $sentencia->store_result();
    if ($sentencia->num_rows === 0) {
        $token = bin2hex(random_bytes(32));
        $_SESSION['token'] = $token;

        $sentencia_insertar = $conexion->prepare("INSERT INTO token (codigo, estado, creado) VALUES (?, 0, NOW())");
        $sentencia_insertar->bind_param('s', $token);
        $sentencia_insertar->execute();
        $sentencia_insertar->close();
    }
    $sentencia->close();
} else {
    $token = bin2hex(random_bytes(32));
    $_SESSION['token'] = $token;

    $sentencia_insertar = $conexion->prepare("INSERT INTO token (codigo, estado, creado) VALUES (?, 0, NOW())");
    $sentencia_insertar->bind_param('s', $token);
    $sentencia_insertar->execute();
    $sentencia_insertar->close();
}
$sentencia_estado = $conexion->prepare("SELECT estado FROM token WHERE codigo = ?");
$sentencia_estado->bind_param('s', $token);
$sentencia_estado->execute();
$resultado_estado = $sentencia_estado->get_result();
$campo = $resultado_estado->fetch_assoc();
$sentencia_estado->close();

if ($campo && (int)$campo['estado'] === 1) {
    include('resultados.php');
    exit;
}
$sentencia_ip = $conexion->prepare("SELECT id FROM votos WHERE ip = ? LIMIT 1");
$sentencia_ip->bind_param('s', $ip);
$sentencia_ip->execute();
$sentencia_ip->store_result();
if ($sentencia_ip->num_rows > 0) {
    include('resultados.php');
    exit;
}
$sentencia_ip->close();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <style>
        .radio-img {
            display: none;
        }

        .radio-img+label {
            cursor: pointer;


            display: inline-block;
        }

        .radio-img+label img {
            max-width: 100%;
            border-radius: 8px;
        }

        .radio-img:checked+label {
            border: 6px solid #0d6efd;
                        border-radius: 2px solid transparent;
            
        }

        .card:checked {
            border: 6px solid #0d6efd;
            border-radius: 8px;
        }
    </style>
</head>

<body>

    <div class="container">
        <form method="POST" id="formVoto">
            <input type="hidden" name="ip" value="<?php echo $ip; ?>">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <input type="radio" name="candidato" id="op1" class="radio-img" value="massa">
                        <label for="op1">
                            <img src="img/massa.jpg" class="card-img-top" alt="massa">
                        </label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <input type="radio" name="candidato" id="op2" class="radio-img" value="grabois">
                        <label for="op2">
                            <img src="img/grabois.jpg" class="card-img-top" alt="massa">
                        </label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <input type="radio" name="candidato" id="op3" class="radio-img" value="alberto">
                        <label for="op3">
                            <img src="img/alberto.jpg" class="card-img-top" alt="massa">
                        </label>
                    </div>
                </div>
            </div>
            <input type="submit" value="Votar" class="btn btn-primary mt-3">
        </form>

        <div id="mensaje"></div>
        <div id="resultados"></div>
    </div>
</body>

</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
<script>
    $('#formVoto').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'procesar.php',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(data) {
                console.log('Respuesta:', data);
                if (data.status === 'success') {
                    $('#mensaje').css('color', 'green').text(data.message);
                    $('#formVoto').hide();
                    cargarResultados();
                } else {
                    $('#mensaje').css('color', 'red').text(data.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', status, error);
                $('#mensaje').css('color', 'red').text('Error en la conexión');
            }
        });
    });

    function cargarResultados() {
        $('#resultados').load('resultados.php');
    }
</script>