-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 09, 2026 at 01:30 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `atividades`
--

-- --------------------------------------------------------

--
-- Table structure for table `atividades`
--

CREATE TABLE `atividades` (
  `cd_atividade` int(11) NOT NULL,
  `id_materia` int(11) NOT NULL,
  `ds_atividade` text NOT NULL,
  `dt_entrega` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `atividades_usuarios`
--

CREATE TABLE `atividades_usuarios` (
  `cd_atividade_usuario` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_atividade` int(11) NOT NULL,
  `st_status` enum('pendente','feito') NOT NULL DEFAULT 'pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `materias`
--

CREATE TABLE `materias` (
  `cd_materia` int(11) NOT NULL,
  `nm_materia` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nm_nome` varchar(255) NOT NULL,
  `ds_senha` varchar(255) NOT NULL,
  `st_nivel` enum('normal','admin') NOT NULL,
  `ds_email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `nm_nome`, `ds_senha`, `st_nivel`, `ds_email`) VALUES
(1, 'isaque', 'teste', 'admin', 'isaque@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `atividades`
--
ALTER TABLE `atividades`
  ADD PRIMARY KEY (`cd_atividade`),
  ADD KEY `idx_materia` (`id_materia`);

--
-- Indexes for table `atividades_usuarios`
--
ALTER TABLE `atividades_usuarios`
  ADD PRIMARY KEY (`cd_atividade_usuario`),
  ADD KEY `idusuario` (`id_usuario`),
  ADD KEY `idatividade` (`id_atividade`);

--
-- Indexes for table `materias`
--
ALTER TABLE `materias`
  ADD PRIMARY KEY (`cd_materia`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ds_email` (`ds_email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `atividades`
--
ALTER TABLE `atividades`
  MODIFY `cd_atividade` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `atividades_usuarios`
--
ALTER TABLE `atividades_usuarios`
  MODIFY `cd_atividade_usuario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `materias`
--
ALTER TABLE `materias`
  MODIFY `cd_materia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `atividades`
--
ALTER TABLE `atividades`
  ADD CONSTRAINT `relacaoMateriaAtividade` FOREIGN KEY (`id_materia`) REFERENCES `materias` (`cd_materia`);

--
-- Constraints for table `atividades_usuarios`
--
ALTER TABLE `atividades_usuarios`
  ADD CONSTRAINT `idatividade` FOREIGN KEY (`id_atividade`) REFERENCES `atividades` (`cd_atividade`),
  ADD CONSTRAINT `idusuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
