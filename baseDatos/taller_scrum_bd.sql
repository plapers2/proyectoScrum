-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-11-2025 a las 14:27:33
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
-- Base de datos: `taller_scrum_bd`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aprendices`
--

CREATE TABLE `aprendices` (
  `id_aprendiz` int(11) NOT NULL,
  `nombre_aprendiz` varchar(45) NOT NULL,
  `apellido_aprendiz` varchar(45) NOT NULL,
  `correo_aprendiz` varchar(45) NOT NULL,
  `contrasena_aprendiz` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `id_curso` int(11) NOT NULL,
  `nombre_curso` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajos`
--

CREATE TABLE `trabajos` (
  `id_trabajo` int(11) NOT NULL,
  `calificacion_trabajo` double NOT NULL,
  `comentario_trabajo` varchar(255) NOT NULL,
  `fk_aprendiz_trabajo` int(11) NOT NULL,
  `fk_usuario_trabajo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre_usuario` varchar(45) NOT NULL,
  `apellido_usuario` varchar(45) NOT NULL,
  `pass_usuario` varchar(45) NOT NULL,
  `correo_usuario` varchar(45) NOT NULL,
  `fk_rol_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_cursos`
--

CREATE TABLE `usuarios_cursos` (
  `id_pivote` int(11) NOT NULL,
  `fk_usuario_cursos` int(11) NOT NULL,
  `fk_curso_cursos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `aprendices`
--
ALTER TABLE `aprendices`
  ADD PRIMARY KEY (`id_aprendiz`),
  ADD UNIQUE KEY `fk_correisito_aprendiz` (`correo_aprendiz`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id_curso`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `trabajos`
--
ALTER TABLE `trabajos`
  ADD PRIMARY KEY (`id_trabajo`),
  ADD KEY `fk_aprendiz` (`fk_aprendiz_trabajo`),
  ADD KEY `fk_instructor` (`fk_usuario_trabajo`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `fk_correisito_instructor` (`correo_usuario`),
  ADD KEY `fk_rolesito_instructor` (`fk_rol_usuario`);

--
-- Indices de la tabla `usuarios_cursos`
--
ALTER TABLE `usuarios_cursos`
  ADD PRIMARY KEY (`id_pivote`),
  ADD KEY `fk_instructor` (`fk_usuario_cursos`),
  ADD KEY `fk_curso` (`fk_curso_cursos`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `aprendices`
--
ALTER TABLE `aprendices`
  MODIFY `id_aprendiz` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `trabajos`
--
ALTER TABLE `trabajos`
  MODIFY `id_trabajo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios_cursos`
--
ALTER TABLE `usuarios_cursos`
  MODIFY `id_pivote` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `trabajos`
--
ALTER TABLE `trabajos`
  ADD CONSTRAINT `trabajos_ibfk_1` FOREIGN KEY (`fk_usuario_trabajo`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `trabajos_ibfk_2` FOREIGN KEY (`fk_aprendiz_trabajo`) REFERENCES `aprendices` (`id_aprendiz`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `instructores_ibfk_1` FOREIGN KEY (`fk_rol_usuario`) REFERENCES `roles` (`id_rol`);

--
-- Filtros para la tabla `usuarios_cursos`
--
ALTER TABLE `usuarios_cursos`
  ADD CONSTRAINT `instructores_cursos_ibfk_1` FOREIGN KEY (`fk_usuario_cursos`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `instructores_cursos_ibfk_2` FOREIGN KEY (`fk_curso_cursos`) REFERENCES `cursos` (`id_curso`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
