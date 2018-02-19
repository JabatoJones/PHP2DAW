-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-02-2018 a las 21:55:02
-- Versión del servidor: 10.1.26-MariaDB
-- Versión de PHP: 7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `loginpintor`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuadros`
--

CREATE TABLE `cuadros` (
  `idPintor` int(100) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `idCuadro` varchar(100) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pintores`
--

CREATE TABLE `pintores` (
  `nombre` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `idPintor` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `pintores`
--

INSERT INTO `pintores` (`nombre`, `idPintor`) VALUES
('MARQUEZ', 5),
('Miguel Angel', 4),
('Picasso', 1),
('Salvador Dali', 2),
('Vicent van Gogh', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `nombre` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `pass` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `correo` varchar(100) COLLATE utf8_spanish2_ci NOT NULL,
  `idpintor` int(100) NOT NULL,
  `id` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`nombre`, `pass`, `correo`, `idpintor`, `id`) VALUES
('pepe', 'pepe1', 'pepe@outlook.es', 1, 1),
('Pepe', '1234', 'pepe@outlook.es', 1, 2),
('javi', '123', '123@pepe.com', 5, 3),
('javi', '', 'pepe@outlook.es', 4, 4),
('pepe', '', 'pepe@outlook.es', 2, 5),
('juan', 'juan', 'jaqwe@safd', 5, 6),
('pepe2', 'pepe2', 'jaboone26@outlook.es', 4, 7),
('andrea', 'andrea', 'asdf@ou', 5, 8),
('Marquitos', '1', 'asdf@ou', 3, 9);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cuadros`
--
ALTER TABLE `cuadros`
  ADD PRIMARY KEY (`idCuadro`),
  ADD KEY `idPintor` (`idPintor`,`nombre`,`idCuadro`);

--
-- Indices de la tabla `pintores`
--
ALTER TABLE `pintores`
  ADD PRIMARY KEY (`idPintor`),
  ADD KEY `nombre` (`nombre`,`idPintor`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cuadros`
--
ALTER TABLE `cuadros`
  MODIFY `idPintor` int(100) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pintores`
--
ALTER TABLE `pintores`
  MODIFY `idPintor` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cuadros`
--
ALTER TABLE `cuadros`
  ADD CONSTRAINT `cuadros_ibfk_1` FOREIGN KEY (`idPintor`) REFERENCES `pintores` (`idPintor`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
