<?php
session_start();

// Archivo de ejemplo para configurar la conexión a la base de datos
// Copiar y renombrar a conexion.php con datos reales

define('DB_HOST', 'localhost');
define('DB_USER', 'usuario');
define('DB_PASS', 'clave');
define('DB_NAME', 'base');

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($mysqli->connect_errno) {
    die("Error de conexión: " . $mysqli->connect_error);
}

$mysqli->set_charset('utf8mb4');
?>