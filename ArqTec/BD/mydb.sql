-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-05-2025 a las 18:16:34
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mydb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrativo`
--

CREATE TABLE `administrativo` (
  `id` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `apellido` varchar(45) NOT NULL,
  `documento` varchar(45) NOT NULL,
  `correo` varchar(45) NOT NULL,
  `contrasena` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administrativo`
--

INSERT INTO `administrativo` (`id`, `nombre`, `apellido`, `documento`, `correo`, `contrasena`) VALUES
(1, 'admin', 'admin', '0000', 'admin@gmail.com', 'admin');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alerta`
--

CREATE TABLE `alerta` (
  `id` int(11) NOT NULL,
  `formulario_id` int(11) NOT NULL,
  `resultado_prediccion` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `alerta`
--

INSERT INTO `alerta` (`id`, `formulario_id`, `resultado_prediccion`) VALUES
(1, 1, NULL),
(2, 2, NULL),
(3, 4, 0),
(4, 6, 0),
(5, 7, 1),
(6, 8, 0),
(7, 11, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante`
--

CREATE TABLE `estudiante` (
  `id` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `apellido` varchar(45) NOT NULL,
  `carrera` varchar(45) NOT NULL,
  `semestre` int(11) NOT NULL,
  `documento` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiante`
--

INSERT INTO `estudiante` (`id`, `nombre`, `apellido`, `carrera`, `semestre`, `documento`) VALUES
(1, 'Adam', 'J', 'Sistemas', 2, '1111'),
(2, 'Bart', 'Simpson', 'Sistemas', 2, '2222'),
(3, 'Homero', 'Simpson', 'Física Nuclear', 2, '3333'),
(4, 'Harry ', 'Snow', 'Física Nuclear', 3, '4444'),
(5, 'sadsa', 'J', 'Física Nuclear', 3, '5555'),
(6, 'hhhh', 'hhhh', 'Sistemas', 4, '43243'),
(7, 'gggggggg', 'ggggggg', 'Sistemas', 3, '324234'),
(8, 'aaadsdsd', 'asdasdas', 'Sistemas', 6, '76777687'),
(9, 'Carl', 'Son', 'Sistemas', 5, '9999'),
(10, 'Clark', 'Kent', 'Sistemas', 8, '8888'),
(11, 'a', 'a', 'Sistemas', 9, '7777'),
(12, '6', '6', 'Sistemas', 7, '6666');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formulario`
--

CREATE TABLE `formulario` (
  `id` int(11) NOT NULL,
  `tuiton_fees_up_to_date` tinyint(4) NOT NULL,
  `international` tinyint(4) NOT NULL,
  `curricular_units_2nd_str_approved` int(11) NOT NULL,
  `curricular_units_2nd_str_enrolled` int(11) NOT NULL,
  `debtor` tinyint(4) NOT NULL,
  `scholarship_holder` tinyint(4) NOT NULL,
  `curricular_units_1st_str_approved` int(11) NOT NULL,
  `displaced` tinyint(4) NOT NULL,
  `fecha` date NOT NULL,
  `estudiante_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `formulario`
--

INSERT INTO `formulario` (`id`, `tuiton_fees_up_to_date`, `international`, `curricular_units_2nd_str_approved`, `curricular_units_2nd_str_enrolled`, `debtor`, `scholarship_holder`, `curricular_units_1st_str_approved`, `displaced`, `fecha`, `estudiante_id`) VALUES
(1, 1, 1, 1, 1, 1, 1, 1, 1, '2025-05-26', 1),
(2, 1, 1, 1, 1, 1, 1, 1, 1, '2025-05-26', 3),
(3, 1, 1, 1, 1, 1, 1, 1, 1, '2025-05-26', 4),
(4, 1, 1, 1, 1, 1, 1, 1, 1, '2025-05-26', 5),
(5, 1, 1, 1, 1, 1, 1, 1, 1, '2025-05-26', 6),
(6, 1, 1, 1, 1, 1, 1, 1, 1, '2025-05-26', 7),
(7, 0, 0, 0, 0, 0, 0, 0, 0, '2025-05-26', 8),
(8, 1, 1, 1, 1, 1, 1, 1, 1, '2025-05-27', 9),
(9, 0, 0, 0, 0, 0, 0, 0, 0, '2025-05-27', 10),
(10, 0, 0, 1, 1, 0, 0, 1, 0, '2025-05-27', 11),
(11, 0, 0, 2, 2, 0, 0, 2, 0, '2025-05-27', 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reporte`
--

CREATE TABLE `reporte` (
  `id` int(11) NOT NULL,
  `administrativo_id` int(11) NOT NULL,
  `alerta_id` int(11) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reporte`
--

INSERT INTO `reporte` (`id`, `administrativo_id`, `alerta_id`, `fecha`) VALUES
(1, 1, 3, '2025-05-26'),
(2, 1, 2, '2025-05-26'),
(3, 1, 1, '2025-05-26'),
(4, 1, 5, '2025-05-26'),
(5, 1, 4, '2025-05-26'),
(6, 1, 5, '2025-05-26'),
(7, 1, 4, '2025-05-26');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administrativo`
--
ALTER TABLE `administrativo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `documento_UNIQUE` (`documento`),
  ADD UNIQUE KEY `correo_UNIQUE` (`correo`);

--
-- Indices de la tabla `alerta`
--
ALTER TABLE `alerta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_formulario_idx` (`formulario_id`);

--
-- Indices de la tabla `estudiante`
--
ALTER TABLE `estudiante`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `documento_UNIQUE` (`documento`);

--
-- Indices de la tabla `formulario`
--
ALTER TABLE `formulario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_estudiante_idx` (`estudiante_id`);

--
-- Indices de la tabla `reporte`
--
ALTER TABLE `reporte`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_administrativo_idx` (`administrativo_id`),
  ADD KEY `fk_alerta_idx` (`alerta_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administrativo`
--
ALTER TABLE `administrativo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `alerta`
--
ALTER TABLE `alerta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `estudiante`
--
ALTER TABLE `estudiante`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `formulario`
--
ALTER TABLE `formulario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `reporte`
--
ALTER TABLE `reporte`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alerta`
--
ALTER TABLE `alerta`
  ADD CONSTRAINT `fk_formulario` FOREIGN KEY (`formulario_id`) REFERENCES `formulario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `formulario`
--
ALTER TABLE `formulario`
  ADD CONSTRAINT `fk_estudiante` FOREIGN KEY (`estudiante_id`) REFERENCES `estudiante` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `reporte`
--
ALTER TABLE `reporte`
  ADD CONSTRAINT `fk_administrativo` FOREIGN KEY (`administrativo_id`) REFERENCES `administrativo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_alerta` FOREIGN KEY (`alerta_id`) REFERENCES `alerta` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
