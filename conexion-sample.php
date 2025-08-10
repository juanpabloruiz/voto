<?php
session_start();
// En esta sección tienen que cambiar los datos localhost, usuario,contraseña y basededatos
$conexion = mysqli_connect('localhost', 'usuario', 'contraseña', 'basededatos');
mysqli_set_charset($conexion, 'utf8mb4');
// No olviden renombrar este archivo por conexion.php
?>