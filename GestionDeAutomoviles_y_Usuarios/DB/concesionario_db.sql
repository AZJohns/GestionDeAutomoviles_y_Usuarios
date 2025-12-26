-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 31-10-2025 a las 18:49:19
-- Versión del servidor: 9.1.0
-- Versión de PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `concesionario_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbautomoviles`
--

DROP TABLE IF EXISTS `tbautomoviles`;
CREATE TABLE IF NOT EXISTS `tbautomoviles` (
  `id_auto` int NOT NULL AUTO_INCREMENT,
  `marca_auto` varchar(100) NOT NULL,
  `modelo_auto` varchar(100) NOT NULL,
  `anio_auto` int NOT NULL,
  `precio_auto` decimal(10,2) NOT NULL,
  `descripcion_auto` text,
  `foto_auto` varchar(255) DEFAULT NULL,
  `estado_auto` enum('disponible','vendido','mantenimiento') NOT NULL DEFAULT 'disponible',
  PRIMARY KEY (`id_auto`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `tbautomoviles`
--

INSERT INTO `tbautomoviles` (`id_auto`, `marca_auto`, `modelo_auto`, `anio_auto`, `precio_auto`, `descripcion_auto`, `foto_auto`, `estado_auto`) VALUES
(6, 'Porsche', '911 Carrera S', 2023, 145000.00, 'El deportivo icónico, redefinido. Motor bóxer de 6 cilindros, 450 CV. Interior en cuero negro y alcántara. Experiencia de conducción pura.', 'uploads/6904f0c261f7f8.35190669.jpg', 'disponible'),
(7, 'Mercedes-Benz', 'Clase G 63 AMG', 2022, 210000.00, 'Lujo y potencia todoterreno. Motor V8 biturbo, 585 CV. Sonido inconfundible y un interior de lujo artesanal.', 'uploads/6904f218091c22.54677274.jpg', 'vendido'),
(8, 'Ferrari', '296 GTB', 2023, 280000.00, 'La nueva era V6 híbrida de Maranello. 830 CV combinados. Diseño espectacular y rendimiento de competición.', 'uploads/6904f2774d2ff7.24292596.jpg', 'disponible'),
(9, 'Lamborghini', 'Urus Performante', 2023, 265000.00, 'El alma de un superdeportivo en el cuerpo de un SUV. 666 CV, diseño agresivo y prestaciones increíbles en cualquier terreno.', 'uploads/6904f2d36cc718.58213977.jpg', 'disponible'),
(10, 'Bentley', 'Continental GT', 2022, 240000.00, 'El gran turismo de lujo por excelencia. Motor W12, 635 CV. Confort supremo, acabados a mano y un diseño que gira cabezas.', 'uploads/6904f356b3d6f3.67753940.jpg', 'disponible'),
(11, 'BMW', 'M5 Competition', 2023, 155000.00, 'La berlina de negocios definitiva. 625 CV, tracción M xDrive. Lujo ejecutivo en el interior, una bestia indomable en el asfalto.\'', 'uploads/6904f3d04c15d1.95954001.jpg', 'disponible'),
(12, 'Land Rover', 'Range Rover SVAutobiography', 2022, 190000.00, 'Lujo redefinido. Asientos ejecutivos reclinables, acabados en madera noble y una capacidad todoterreno sin igual. El yate de la carretera.', 'uploads/6904f46f2e5243.98213763.jpg', 'disponible'),
(13, 'McLaren', '720S', 2022, 295000.00, 'Pura aerodinámica y rendimiento de competición. Chasis monocasco de fibra de carbono. Una obra de arte de la ingeniería británica.', 'uploads/6904f4be7443b3.43626635.jpg', 'disponible'),
(14, 'Tesla', 'Model S Plaid', 2023, 135000.00, 'Aceleración de 0 a 100 km/h en 2.1 segundos. Más de 600 km de autonomía. El futuro de la conducción eléctrica de lujo con 1020 CV.', 'uploads/6904f5b2c31085.78878864.jpg', 'disponible');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbclientes`
--

DROP TABLE IF EXISTS `tbclientes`;
CREATE TABLE IF NOT EXISTS `tbclientes` (
  `id_cliente` int NOT NULL AUTO_INCREMENT,
  `nombre_cli` varchar(100) NOT NULL,
  `apellido_cli` varchar(100) NOT NULL,
  `dni_cli` varchar(20) NOT NULL,
  `email_cli` varchar(100) NOT NULL,
  `telefono_cli` varchar(20) DEFAULT NULL,
  `direccion_cli` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_cliente`),
  UNIQUE KEY `dni_cli` (`dni_cli`),
  UNIQUE KEY `email_cli` (`email_cli`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `tbclientes`
--

INSERT INTO `tbclientes` (`id_cliente`, `nombre_cli`, `apellido_cli`, `dni_cli`, `email_cli`, `telefono_cli`, `direccion_cli`) VALUES
(1, 'sebastian', 'andrade', 's12345d', 's@gmail.com', '1324567', 'madrid');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbroles`
--

DROP TABLE IF EXISTS `tbroles`;
CREATE TABLE IF NOT EXISTS `tbroles` (
  `id_rol` int NOT NULL AUTO_INCREMENT,
  `nom_rol` varchar(50) NOT NULL,
  `descripcion_rol` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_rol`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `tbroles`
--

INSERT INTO `tbroles` (`id_rol`, `nom_rol`, `descripcion_rol`) VALUES
(1, 'Administrador', 'Control total del sistema'),
(2, 'Usuario', 'Gestión limitada de automóviles'),
(3, 'Invitado', 'Solo visualización');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbsolicitudes`
--

DROP TABLE IF EXISTS `tbsolicitudes`;
CREATE TABLE IF NOT EXISTS `tbsolicitudes` (
  `id_solicitud` int NOT NULL AUTO_INCREMENT,
  `auto_id` int DEFAULT NULL,
  `nombre_interesado` varchar(100) NOT NULL,
  `email_interesado` varchar(100) NOT NULL,
  `telefono_interesado` varchar(50) DEFAULT NULL,
  `mensaje` text,
  `fecha_solicitud` datetime DEFAULT CURRENT_TIMESTAMP,
  `estado_solicitud` enum('pendiente','contactado','descartado') DEFAULT 'pendiente',
  PRIMARY KEY (`id_solicitud`),
  KEY `auto_id` (`auto_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `tbsolicitudes`
--

INSERT INTO `tbsolicitudes` (`id_solicitud`, `auto_id`, `nombre_interesado`, `email_interesado`, `telefono_interesado`, `mensaje`, `fecha_solicitud`, `estado_solicitud`) VALUES
(1, 4, 'jonh', '1@gmail.com', '12345', '', '2025-10-31 01:01:16', 'contactado'),
(2, 10, 'Jonh', 'Jonh@gmail.com', '31234567', 'Estoy interesado', '2025-10-31 18:46:20', 'pendiente'),
(3, 7, 'Paco', 'paco@gmail.com', '53138793', 'Estoy Interesado', '2025-10-31 19:47:28', 'contactado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbusuarios`
--

DROP TABLE IF EXISTS `tbusuarios`;
CREATE TABLE IF NOT EXISTS `tbusuarios` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `nom_user` varchar(100) NOT NULL,
  `email_user` varchar(100) NOT NULL,
  `pass_user` varchar(255) NOT NULL,
  `rol_id` int DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `nom_user` (`nom_user`),
  UNIQUE KEY `email_user` (`email_user`),
  KEY `rol_id` (`rol_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `tbusuarios`
--

INSERT INTO `tbusuarios` (`id_usuario`, `nom_user`, `email_user`, `pass_user`, `rol_id`) VALUES
(4, 'jonh', 'jonh@gmail.com', '$2y$10$jDpTAqpwRuSYEV6Fh7FCl.5lXeRWoIhQHomqi4RaEKtlPnrE7J/Ie', 2),
(3, 'admin', 'admin@gmail.com', '$2y$10$l9yvQTWt2P4j79HT8Z3DWuMnQX62qwp/kT7ETnqZhb.aO4i9zO/p6', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbventas`
--

DROP TABLE IF EXISTS `tbventas`;
CREATE TABLE IF NOT EXISTS `tbventas` (
  `id_venta` int NOT NULL AUTO_INCREMENT,
  `auto_id` int DEFAULT NULL,
  `cliente_id` int DEFAULT NULL,
  `usuario_id` int DEFAULT NULL,
  `fecha_venta` datetime DEFAULT CURRENT_TIMESTAMP,
  `precio_final` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_venta`),
  KEY `auto_id` (`auto_id`),
  KEY `cliente_id` (`cliente_id`),
  KEY `usuario_id` (`usuario_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `tbventas`
--

INSERT INTO `tbventas` (`id_venta`, `auto_id`, `cliente_id`, `usuario_id`, `fecha_venta`, `precio_final`) VALUES
(1, 5, 1, 4, '2025-10-29 01:43:56', 120000.00),
(2, 7, 1, 3, '2025-10-31 19:48:04', 210000.00);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
