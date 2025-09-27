<?php
session_start();
include('conexion.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Token del navegador
if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}
$token = $_SESSION['token'];

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

// Inicializar resultados en 0
$resultados = [];
foreach ($candidatos as $nombre => $img) {
    $resultados[$nombre] = 0;
}

// Traer resultados reales
$queryResultados = mysqli_query($conexion, "SELECT candidato, COUNT(*) AS cantidad FROM votos GROUP BY candidato");
while ($row = mysqli_fetch_assoc($queryResultados)) {
    $nombre = $row['candidato'];
    if (isset($resultados[$nombre])) {
        $resultados[$nombre] = $row['cantidad'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Voto</title>
<style>
body {background:#f8f9fa;font-family:sans-serif;margin:0;padding:0;}
header {background-color:#0066fe;padding:.5rem 1rem;}
header img {height:55px;display:block;}
.contenedor {max-width:900px;margin:2rem auto;padding:1rem;}
.titulo {font-weight:bold;margin-bottom:1rem;font-size:1.2rem;}
.grid {display:grid;grid-template-columns:1fr 1fr;gap:1.2rem;}
.candidatos,.resultados {background:#fff;padding:1rem;border-radius:1rem;box-shadow:0 0 6px rgba(0,0,0,.05);}
@media (max-width:768px){.grid{grid-template-columns:1fr;}}
.candidato {display:flex;align-items:center;justify-content:space-between;background:#f8f9fa;padding:.6rem .8rem;border-radius:.75rem;margin-bottom:.6rem;transition:background .2s;}
.candidato:hover {background:#eef3ff;}
.candidato-info {display:flex;align-items:center;gap:.6rem;}
.candidato img {width:40px;height:40px;border-radius:50%;object-fit:cover;}
.candidato .nombre {font-weight:600;}
.btn-votar {background:#007bff;color:#fff;border:none;padding:.4rem .8rem;border-radius:.5rem;cursor:pointer;font-weight:600;}
.btn-votar:hover {background:#0056b3;}
.resultado {margin-bottom:.8rem;}
.barra-info {display:flex;justify-content:space-between;font-weight:500;margin-bottom:.3rem;}
.progress {background:#e9ecef;height:6px;border-radius:3px;overflow:hidden;}
.progress-bar {height:100%;background-color:#007bff;width:0%;transition:width 1s ease-in-out;}
.total-votos {text-align:center;margin-top:.8rem;font-weight:bold;color:#555;}
.overlay {position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:999;display:flex;justify-content:center;align-items:center;color:#fff;flex-direction:column;font-size:1.2rem;display:none;}
.loader {border:6px solid #f3f3f3;border-top:6px solid #007bff;border-radius:50%;width:40px;height:40px;animation:spin 1s linear infinite;margin-bottom:1rem;}
@keyframes spin {0%{transform:rotate(0deg);}100%{transform:rotate(360deg);}}
</style>
</head>
<body>
<header><a href="../"><img src="img/logo.png" alt="Logo"></a></header>
<div class="contenedor">
<div class="grid">

<!-- Candidatos -->
<div class="candidatos">

<div id="formContainer" style="display:none;">
<p class="titulo">Elegí tu candidato a gobernador y mirá el ranking en vivo:</p>
<form id="formVoto" method="POST" action="votar.php">
<input type="hidden" id="ipInput" name="ip">
<input type="hidden" name="token" value="<?php echo $token; ?>">
<?php
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
?>
</form>
</div>
</div>

<!-- Resultados -->
<div class="resultados">
<?php
foreach ($resultados as $nombre => $cantidad) {
    $porcentaje = $totalVotos>0 ? round(($cantidad/$totalVotos)*100) : 0;
    echo '<div class="resultado">';
    echo '<div class="barra-info"><span>'.$nombre.'</span><span>'.$porcentaje.'%</span></div>';
    echo '<div class="progress"><div class="progress-bar" data-porcentaje="'.$porcentaje.'"></div></div>';
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
// Obtener IP del cliente y mostrar formulario
fetch('https://api.ipify.org?format=json')
.then(res => res.json())
.then(data=>{
    document.getElementById('ipInput').value = data.ip;
    document.getElementById('formContainer').style.display='block';
})
.catch(err=>console.error('No se pudo obtener la IP:',err));

// Votar candidato
const form=document.getElementById('formVoto');
const overlay=document.getElementById('overlay');

async function votarCandidato(btn){
    const label=btn.closest('.candidato');
    const radio=label.querySelector('input[type=radio]');
    radio.checked=true;

    const ip=document.getElementById('ipInput').value;
    const token="<?php echo $token; ?>";

    try{
        const res=await fetch('verificar_voto.php',{
            method:'POST',
            headers:{'Content-Type':'application/json'},
            body:JSON.stringify({ip,token})
        });
        const data=await res.json();

        if(data.yaVoto){
            overlay.style.display='flex';
            overlay.innerHTML='<p>Ya votaste!</p>';
            setTimeout(()=>overlay.style.display='none',2000);
            return;
        }

        if(confirm("¿Confirmas tu voto?")){
            overlay.style.display='flex';
            overlay.innerHTML='<div class="loader"></div><p>Votando...</p>';
            setTimeout(()=>form.submit(),1000);
        }

    }catch(err){
        console.error(err);
        alert("Error al verificar tu voto");
    }
}

// Animar barras
window.addEventListener('load',()=>{
    document.querySelectorAll('.progress-bar').forEach(bar=>{
        const p=bar.getAttribute('data-porcentaje');
        setTimeout(()=>bar.style.width=p+'%',100);
    });
});
</script>

</body>
</html>
