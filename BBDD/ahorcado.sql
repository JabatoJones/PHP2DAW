-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-02-2018 a las 21:54:48
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
-- Base de datos: `ahorcado`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jugadas`
--

CREATE TABLE `jugadas` (
  `idPartida` int(100) NOT NULL,
  `solucionada` varchar(100) NOT NULL,
  `letra` varchar(100) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `jugadas`
--

INSERT INTO `jugadas` (`idPartida`, `solucionada`, `letra`, `id`) VALUES
(28, 'a _ a a _ a', 'a', 62),
(28, 'a _ a a _ a', 'c', 63),
(29, '_ _ _ _ _ a', 'a', 64),
(29, '_ _ _ _ _ a', 'k', 65),
(29, 's _ _ _ _ a', 's', 66),
(28, 'a _ a a _ a', 'r', 67),
(28, 'a _ o a _ a', 'o', 68),
(28, 'a _ o a _ a', '4', 69);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partida`
--

CREATE TABLE `partida` (
  `id` int(100) NOT NULL,
  `idUsuario` int(100) NOT NULL,
  `palabra` varchar(100) NOT NULL,
  `estado` varchar(100) NOT NULL,
  `intentos` int(11) NOT NULL,
  `fallos` int(11) NOT NULL,
  `solucionada` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `partida`
--

INSERT INTO `partida` (`id`, `idUsuario`, `palabra`, `estado`, `intentos`, `fallos`, `solucionada`) VALUES
(28, 10, 'shotta', 'empezada', 5, 3, 'a_oa_a'),
(29, 10, 'shotta', 'empezada', 3, 1, 's____a');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `nombre` varchar(100) NOT NULL,
  `pass` varchar(100) NOT NULL,
  `id` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`nombre`, `pass`, `id`) VALUES
('a', 'a', 10);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `jugadas`
--
ALTER TABLE `jugadas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idPartida` (`idPartida`);

--
-- Indices de la tabla `partida`
--
ALTER TABLE `partida`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idUsuario` (`idUsuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `jugadas`
--
ALTER TABLE `jugadas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT de la tabla `partida`
--
ALTER TABLE `partida`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `jugadas`
--
ALTER TABLE `jugadas`
  ADD CONSTRAINT `jugadas_ibfk_1` FOREIGN KEY (`idPartida`) REFERENCES `partida` (`id`);

--
-- Filtros para la tabla `partida`
--
ALTER TABLE `partida`
  ADD CONSTRAINT `partida_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
