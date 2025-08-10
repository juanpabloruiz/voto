CREATE DATABASE IF NOT EXISTS elecciones;
USE elecciones;

CREATE TABLE votos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    candidato VARCHAR(50) NOT NULL,
    ip VARCHAR(45) NOT NULL,
    fecha DATETIME NOT NULL
);

CREATE TABLE tokens_voto (
    id INT AUTO_INCREMENT PRIMARY KEY,
    token VARCHAR(64) NOT NULL UNIQUE,
    estado ENUM('pendiente', 'usado') NOT NULL,
    creado DATETIME NOT NULL
);