-- phpMyAdmin SQL Dump
-- version 5.2.2deb1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 10-08-2025 a las 03:24:28
-- Versión del servidor: 8.4.5-0ubuntu0.2
-- Versión de PHP: 8.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `elecciones`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `intentos`
--

CREATE TABLE `intentos` (
  `id` int NOT NULL,
  `ip` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `candidato` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `intento_fecha` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tokens_voto`
--

CREATE TABLE `tokens_voto` (
  `id` int NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `estado` enum('pendiente','usado') COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `creado` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `votos`
--

CREATE TABLE `votos` (
  `id` int NOT NULL,
  `candidato` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `ip` varchar(45) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `fecha` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Volcado de datos para la tabla `votos`
--

INSERT INTO `votos` (`id`, `candidato`, `ip`, `fecha`) VALUES
(1, 'valdes', '190.229.217.89', '2025-08-10 00:14:06'),
(2, 'colombi', '190.229.217.89', '2025-08-10 00:14:34'),
(3, 'colombi', '190.229.217.89', '2025-08-10 00:15:45');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `intentos`
--
ALTER TABLE `intentos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tokens_voto`
--
ALTER TABLE `tokens_voto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`);

--
-- Indices de la tabla `votos`
--
ALTER TABLE `votos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `intentos`
--
ALTER TABLE `intentos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `tokens_voto`
--
ALTER TABLE `tokens_voto`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `votos`
--
ALTER TABLE `votos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
