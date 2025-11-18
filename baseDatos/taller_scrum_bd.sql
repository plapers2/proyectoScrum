-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 18, 2025 at 01:51 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `taller_scrum_bd`
--

-- --------------------------------------------------------

--
-- Table structure for table `aprendices`
--

CREATE TABLE `aprendices` (
  `id_aprendiz` int(11) NOT NULL,
  `nombre_aprendiz` varchar(45) NOT NULL,
  `apellido_aprendiz` varchar(45) NOT NULL,
  `correo_aprendiz` varchar(45) NOT NULL,
  `contrasena_aprendiz` varchar(255) NOT NULL,
  `fk_rol_aprendiz` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cursos`
--

CREATE TABLE `cursos` (
  `id_curso` int(11) NOT NULL,
  `nombre_curso` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instructores`
--

CREATE TABLE `instructores` (
  `id_instructor` int(11) NOT NULL,
  `nombre_instructor` varchar(45) NOT NULL,
  `apellido_instructor` varchar(45) NOT NULL,
  `correo_instructor` varchar(45) NOT NULL,
  `fk_rol_instructor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instructores_cursos`
--

CREATE TABLE `instructores_cursos` (
  `id_pivote` int(11) NOT NULL,
  `fk_instructor_cursos` int(11) NOT NULL,
  `fk_curso_cursos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre_rol`) VALUES
(1, 'Aprendiz'),
(2, 'Instructor'),
(3, 'Administrador');

-- --------------------------------------------------------

--
-- Table structure for table `trabajos`
--

CREATE TABLE `trabajos` (
  `id_trabajo` int(11) NOT NULL,
  `calificacion_trabajo` double NOT NULL,
  `comentario_trabajo` varchar(255) NOT NULL,
  `fk_aprendiz_trabajo` int(11) NOT NULL,
  `fk_instructor_trabajo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aprendices`
--
ALTER TABLE `aprendices`
  ADD PRIMARY KEY (`id_aprendiz`),
  ADD UNIQUE KEY `fk_correisito_aprendiz` (`correo_aprendiz`),
  ADD KEY `fk_rol_aprendiz` (`fk_rol_aprendiz`);

--
-- Indexes for table `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id_curso`);

--
-- Indexes for table `instructores`
--
ALTER TABLE `instructores`
  ADD PRIMARY KEY (`id_instructor`),
  ADD UNIQUE KEY `fk_correisito_instructor` (`correo_instructor`),
  ADD KEY `fk_rolesito_instructor` (`fk_rol_instructor`);

--
-- Indexes for table `instructores_cursos`
--
ALTER TABLE `instructores_cursos`
  ADD PRIMARY KEY (`id_pivote`),
  ADD KEY `fk_instructor` (`fk_instructor_cursos`),
  ADD KEY `fk_curso` (`fk_curso_cursos`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indexes for table `trabajos`
--
ALTER TABLE `trabajos`
  ADD PRIMARY KEY (`id_trabajo`),
  ADD KEY `fk_aprendiz` (`fk_aprendiz_trabajo`),
  ADD KEY `fk_instructor` (`fk_instructor_trabajo`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aprendices`
--
ALTER TABLE `aprendices`
  MODIFY `id_aprendiz` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `instructores`
--
ALTER TABLE `instructores`
  MODIFY `id_instructor` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `instructores_cursos`
--
ALTER TABLE `instructores_cursos`
  MODIFY `id_pivote` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `trabajos`
--
ALTER TABLE `trabajos`
  MODIFY `id_trabajo` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aprendices`
--
ALTER TABLE `aprendices`
  ADD CONSTRAINT `aprendices_ibfk_1` FOREIGN KEY (`fk_rol_aprendiz`) REFERENCES `roles` (`id_rol`);

--
-- Constraints for table `instructores`
--
ALTER TABLE `instructores`
  ADD CONSTRAINT `instructores_ibfk_1` FOREIGN KEY (`fk_rol_instructor`) REFERENCES `roles` (`id_rol`);

--
-- Constraints for table `instructores_cursos`
--
ALTER TABLE `instructores_cursos`
  ADD CONSTRAINT `instructores_cursos_ibfk_1` FOREIGN KEY (`fk_instructor_cursos`) REFERENCES `instructores` (`id_instructor`),
  ADD CONSTRAINT `instructores_cursos_ibfk_2` FOREIGN KEY (`fk_curso_cursos`) REFERENCES `cursos` (`id_curso`);

--
-- Constraints for table `trabajos`
--
ALTER TABLE `trabajos`
  ADD CONSTRAINT `trabajos_ibfk_1` FOREIGN KEY (`fk_instructor_trabajo`) REFERENCES `instructores` (`id_instructor`),
  ADD CONSTRAINT `trabajos_ibfk_2` FOREIGN KEY (`fk_aprendiz_trabajo`) REFERENCES `aprendices` (`id_aprendiz`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
