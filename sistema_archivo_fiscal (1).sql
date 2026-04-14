-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-04-2026 a las 05:07:09
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
-- Base de datos: `sistema_archivo_fiscal`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria`
--

CREATE TABLE `auditoria` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `accion` varchar(50) NOT NULL,
  `tabla_afectada` varchar(50) DEFAULT NULL,
  `registro_id` int(11) DEFAULT NULL,
  `datos_anteriores` text DEFAULT NULL,
  `datos_nuevos` text DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `auditoria`
--

INSERT INTO `auditoria` (`id`, `usuario_id`, `accion`, `tabla_afectada`, `registro_id`, `datos_anteriores`, `datos_nuevos`, `ip`, `fecha`) VALUES
(1, 1, 'INSERT', 'carpeta_fiscal', 1, 'null', '{\"numero_carpeta\":\"FISC-2026-002\",\"imputado\":\"Jhon Smith\",\"delito\":\"Homicidio\",\"agravado\":\"Si\",\"estado\":\"Activo\",\"ubicacion_fisica\":\"Estante A2\",\"observaciones\":\"No lo encontramos\"}', '::1', '2026-04-14 01:34:43'),
(2, 1, 'IMPORTAR', 'carpeta_fiscal', 0, 'null', '{\"insertados\":14,\"actualizados\":2,\"total_registros\":16}', '::1', '2026-04-14 01:50:49'),
(3, 1, 'IMPORTAR', 'carpeta_fiscal', 0, 'null', '{\"insertados\":0,\"actualizados\":16,\"total_registros\":16}', '::1', '2026-04-14 01:51:12'),
(4, 1, 'IMPORTAR', 'carpeta_fiscal', 0, 'null', '{\"insertados\":0,\"actualizados\":16,\"total_registros\":16}', '::1', '2026-04-14 01:51:26'),
(5, 1, 'IMPORTAR', 'carpeta_fiscal', 0, 'null', '{\"insertados\":0,\"duplicados\":16,\"total_procesados\":16}', '::1', '2026-04-14 01:57:30'),
(6, 1, 'INSERT', 'prestamo', 1, 'null', '{\"dependencia_id\":\"1\",\"usuario_solicitante\":\"1\",\"plazo\":\"7\",\"carpetas\":[\"4\",\"1\",\"5\",\"6\"]}', '::1', '2026-04-14 02:00:38'),
(7, 1, 'INSERT', 'prestamo', 2, 'null', '{\"dependencia_id\":\"4\",\"usuario_solicitante\":\"1\",\"plazo\":\"7\",\"carpetas\":[\"9\",\"11\",\"12\"]}', '::1', '2026-04-14 02:03:24'),
(8, 1, 'INSERT', 'prestamo', 3, 'null', '{\"dependencia_id\":\"2\",\"usuario_solicitante\":\"1\",\"plazo\":\"7\",\"carpetas\":[\"7\",\"8\",\"10\",\"13\"]}', '::1', '2026-04-14 02:03:31'),
(9, 1, 'INSERT', 'carpeta_fiscal', 18, 'null', '{\"numero_carpeta\":\"FISC-2026-209\",\"imputado\":\"dfsdgfsdg\",\"delito\":\"Homicidio\",\"agravado\":\"No\",\"estado\":\"Archivado\",\"ubicacion_fisica\":\"Estante A5\",\"observaciones\":\"fdhdgb\"}', '::1', '2026-04-22 02:17:24'),
(10, 1, 'IMPORTAR', 'carpeta_fiscal', 0, 'null', '{\"insertados\":0,\"duplicados\":16,\"total_procesados\":16}', '::1', '2026-04-22 02:17:38'),
(11, 1, 'IMPORTAR', 'carpeta_fiscal', 0, 'null', '{\"insertados\":2,\"duplicados\":0,\"total_procesados\":2}', '::1', '2026-04-22 02:18:32'),
(12, 1, 'INSERT', 'prestamo', 4, 'null', '{\"dependencia_id\":\"2\",\"usuario_solicitante\":\"1\",\"plazo\":\"7\",\"carpetas\":[\"14\",\"15\"]}', '::1', '2026-04-22 02:18:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carpeta_fiscal`
--

CREATE TABLE `carpeta_fiscal` (
  `id` int(11) NOT NULL,
  `numero_carpeta` varchar(50) NOT NULL COMMENT 'Número de carpeta (único)',
  `imputado` varchar(150) NOT NULL COMMENT 'Imputado',
  `delito` varchar(100) NOT NULL COMMENT 'Delito',
  `agravado` varchar(100) DEFAULT NULL COMMENT 'Agravado',
  `estado` enum('Activo','Archivado','En préstamo','Vencido') DEFAULT 'Activo' COMMENT 'Estado',
  `ubicacion_fisica` varchar(100) NOT NULL COMMENT 'Ubicación física',
  `observaciones` text DEFAULT NULL,
  `usuario_creacion` int(11) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `usuario_modificacion` int(11) DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carpeta_fiscal`
--

INSERT INTO `carpeta_fiscal` (`id`, `numero_carpeta`, `imputado`, `delito`, `agravado`, `estado`, `ubicacion_fisica`, `observaciones`, `usuario_creacion`, `fecha_creacion`, `usuario_modificacion`, `fecha_modificacion`) VALUES
(1, 'FISC-2026-002', 'María López Ramírez', 'Estafa', 'No', 'En préstamo', 'Estante B-2 - Bandeja 3', '⚠️ DUPLICADO INTENCIONAL - Este registro actualizará el anterior', 1, '2026-04-14 01:34:43', 1, '2026-04-13 20:51:26'),
(4, 'FISC-2026-001', 'Juan Pérez González', 'Robo Agravado', 'Si', 'En préstamo', 'Estante A-1 - Bandeja 1', 'Caso en investigación preliminar', 1, '2026-04-14 01:50:49', 1, '2026-04-13 20:51:26'),
(5, 'FISC-2026-003', 'Carlos Ruiz Mendoza', 'Homicidio Simple', 'No', 'En préstamo', 'Estante A-3 - Bandeja 5', 'Esperando peritaje balístico', 1, '2026-04-14 01:50:49', 1, '2026-04-13 20:51:26'),
(6, 'FISC-2026-004', 'Ana Torres Vega', 'Lavado de Activos', 'Si', 'En préstamo', 'Estante C-1 - Bandeja 2', 'Investigación con reserva', 1, '2026-04-14 01:50:49', 1, '2026-04-13 20:51:26'),
(7, 'FISC-2026-005', 'Pedro Sánchez Díaz', 'Violencia Familiar', 'Si', 'En préstamo', 'Estante D-2 - Bandeja 4', 'Medidas de protección vigentes', 1, '2026-04-14 01:50:49', 1, '2026-04-13 20:51:26'),
(8, 'FISC-2026-006', 'Luisa Fernández Castro', 'Falsificación Documental', 'No', 'En préstamo', 'Estante E-1 - Bandeja 1', 'Pericia grafotécnica en curso', 1, '2026-04-14 01:50:49', 1, '2026-04-13 20:51:26'),
(9, 'FISC-2026-007', 'Roberto Gómez Luna', 'Tráfico Ilícito de Drogas', 'Si', 'En préstamo', 'Estante F-3 - Bandeja 2', 'Investigación preliminar', 1, '2026-04-14 01:50:49', 1, '2026-04-13 20:51:26'),
(10, 'FISC-2026-008', 'Sandra Rojas Paz', 'Apropiación Ilícita', 'No', 'En préstamo', 'Estante G-2 - Bandeja 4', 'En espera de documentos', 1, '2026-04-14 01:50:49', 1, '2026-04-13 20:51:26'),
(11, 'FISC-2026-009', 'Miguel Ángel Castro', 'Secuestro', 'Si', 'En préstamo', 'Estante H-1 - Bandeja 3', 'Caso en etapa de instrucción', 1, '2026-04-14 01:50:49', 1, '2026-04-13 20:51:26'),
(12, 'FISC-2026-010', 'Diana Morales León', 'Fraude Informático', 'No', 'En préstamo', 'Estante I-4 - Bandeja 1', 'Pericia informática solicitada', 1, '2026-04-14 01:50:49', 1, '2026-04-13 20:51:26'),
(13, 'FISC-2026-011', 'Oscar Ruiz Paredes', 'Cohecho', 'Si', 'En préstamo', 'Estante J-2 - Bandeja 5', 'Investigación fiscal', 1, '2026-04-14 01:50:49', 1, '2026-04-13 20:51:26'),
(14, 'FISC-2026-012', 'Carmen Vega Soto', 'Lesiones Graves', 'No', 'En préstamo', 'Estante K-1 - Bandeja 2', 'Certificado médico pendiente', 1, '2026-04-14 01:50:49', 1, '2026-04-13 20:51:26'),
(15, 'FISC-2026-013', 'Ricardo Palma Flores', 'Extorsión', 'Si', 'En préstamo', 'Estante L-3 - Bandeja 4', 'Víctima en protección', 1, '2026-04-14 01:50:49', 1, '2026-04-13 20:51:26'),
(16, 'FISC-2026-014', 'Sofía Mendoza Ruiz', 'Usurpación', 'No', 'Activo', 'Estante M-2 - Bandeja 1', 'Desalojo programado', 1, '2026-04-14 01:50:49', 1, '2026-04-13 20:51:26'),
(17, 'FISC-2026-015', 'Alberto Núñez Díaz', 'Peculado', 'Si', 'Activo', 'Estante N-1 - Bandeja 3', 'Auditoría en curso', 1, '2026-04-14 01:50:49', 1, '2026-04-13 20:51:26'),
(18, 'FISC-2026-209', 'dfsdgfsdg', 'Homicidio', 'No', 'Archivado', 'Estante A5', 'fdhdgb', 1, '2026-04-22 02:17:24', NULL, NULL),
(19, 'FISC-2026-100', 'Juan Pérez González', 'Robo Agravado', 'Si', 'Activo', 'Estante A-1 - Bandeja 1', 'Caso en investigación preliminar', 1, '2026-04-22 02:18:32', NULL, NULL),
(20, 'FISC-2026-101', 'María López Ramírez', 'Estafa', 'No', 'Activo', 'Estante B-2 - Bandeja 3', 'Audiencia programada 15/04/2026', 1, '2026-04-22 02:18:32', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dependencia`
--

CREATE TABLE `dependencia` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `codigo` varchar(20) NOT NULL,
  `ubicacion` varchar(100) DEFAULT NULL,
  `estado` tinyint(4) DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `dependencia`
--

INSERT INTO `dependencia` (`id`, `nombre`, `codigo`, `ubicacion`, `estado`, `fecha_creacion`) VALUES
(1, 'Fiscalía Penal 1', 'FP001', 'Piso 1 - Oficina 101', 1, '2026-04-14 01:04:48'),
(2, 'Fiscalía Penal 2', 'FP002', 'Piso 1 - Oficina 102', 1, '2026-04-14 01:04:48'),
(3, 'Fiscalía Civil', 'FC001', 'Piso 2 - Oficina 201', 1, '2026-04-14 01:04:48'),
(4, 'Archivo Central', 'AC001', 'Sótano - Módulo A', 1, '2026-04-14 01:04:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_prestamo`
--

CREATE TABLE `detalle_prestamo` (
  `id` int(11) NOT NULL,
  `prestamo_id` int(11) NOT NULL,
  `carpeta_id` int(11) NOT NULL,
  `estado` enum('Prestado','Devuelto','Extraviado') DEFAULT 'Prestado',
  `fecha_devolucion` datetime DEFAULT NULL,
  `observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_prestamo`
--

INSERT INTO `detalle_prestamo` (`id`, `prestamo_id`, `carpeta_id`, `estado`, `fecha_devolucion`, `observaciones`) VALUES
(1, 1, 4, 'Prestado', NULL, NULL),
(2, 1, 1, 'Prestado', NULL, NULL),
(3, 1, 5, 'Prestado', NULL, NULL),
(4, 1, 6, 'Prestado', NULL, NULL),
(5, 2, 9, 'Prestado', NULL, NULL),
(6, 2, 11, 'Prestado', NULL, NULL),
(7, 2, 12, 'Prestado', NULL, NULL),
(8, 3, 7, 'Prestado', NULL, NULL),
(9, 3, 8, 'Prestado', NULL, NULL),
(10, 3, 10, 'Prestado', NULL, NULL),
(11, 3, 13, 'Prestado', NULL, NULL),
(12, 4, 14, 'Prestado', NULL, NULL),
(13, 4, 15, 'Prestado', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `devolucion`
--

CREATE TABLE `devolucion` (
  `id` int(11) NOT NULL,
  `prestamo_id` int(11) NOT NULL,
  `fecha_devolucion` date NOT NULL,
  `usuario_recepcion` int(11) NOT NULL,
  `estado_carpetas` enum('Completo','Incompleto','Con observaciones') DEFAULT 'Completo',
  `observaciones` text DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificacion`
--

CREATE TABLE `notificacion` (
  `id` int(11) NOT NULL,
  `prestamo_id` int(11) NOT NULL,
  `tipo` enum('Recordatorio','Vencimiento','Apercibimiento') NOT NULL,
  `fecha_generacion` date NOT NULL,
  `fecha_envio` date DEFAULT NULL,
  `estado` enum('Pendiente','Enviado','Entregado') DEFAULT 'Pendiente',
  `contenido` text DEFAULT NULL,
  `usuario_generacion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `notificacion`
--

INSERT INTO `notificacion` (`id`, `prestamo_id`, `tipo`, `fecha_generacion`, `fecha_envio`, `estado`, `contenido`, `usuario_generacion`) VALUES
(1, 1, 'Vencimiento', '2026-04-21', NULL, 'Pendiente', 'Se notifica que el préstamo ha excedido el plazo establecido. Se requiere devolución inmediata.', 1),
(2, 4, 'Vencimiento', '2026-04-29', NULL, 'Pendiente', 'Se notifica que el préstamo ha excedido el plazo establecido. Se requiere devolución inmediata.', 1),
(3, 1, 'Vencimiento', '2026-04-13', NULL, 'Pendiente', 'Se notifica que el préstamo ha excedido el plazo establecido. Se requiere devolución inmediata.', 1),
(4, 1, 'Vencimiento', '2026-04-13', NULL, 'Pendiente', 'Se notifica que el préstamo ha excedido el plazo establecido. Se requiere devolución inmediata.', 1),
(5, 1, 'Vencimiento', '2026-04-13', NULL, 'Pendiente', 'Se notifica que el préstamo ha excedido el plazo establecido. Se requiere devolución inmediata.', 1),
(6, 1, 'Vencimiento', '2026-04-13', NULL, 'Pendiente', 'Se notifica que el préstamo ha excedido el plazo establecido. Se requiere devolución inmediata.', 1),
(7, 1, 'Vencimiento', '2026-04-29', NULL, 'Pendiente', 'Se notifica que el préstamo ha excedido el plazo establecido. Se requiere devolución inmediata.', 1),
(8, 1, 'Vencimiento', '2026-04-29', NULL, 'Pendiente', 'Se notifica que el préstamo ha excedido el plazo establecido. Se requiere devolución inmediata.', 1),
(9, 1, 'Vencimiento', '2026-04-29', NULL, 'Pendiente', 'Se notifica que el préstamo ha excedido el plazo establecido. Se requiere devolución inmediata.', 1),
(10, 1, 'Vencimiento', '2026-04-29', NULL, 'Pendiente', 'Se notifica que el préstamo ha excedido el plazo establecido. Se requiere devolución inmediata.', 1),
(11, 1, 'Vencimiento', '2026-04-29', NULL, 'Pendiente', 'Se notifica que el préstamo ha excedido el plazo establecido. Se requiere devolución inmediata.', 1),
(12, 1, 'Vencimiento', '2026-04-29', NULL, 'Pendiente', 'Se notifica que el préstamo ha excedido el plazo establecido. Se requiere devolución inmediata.', 1),
(13, 1, 'Vencimiento', '2026-04-29', NULL, 'Pendiente', 'Se notifica que el préstamo ha excedido el plazo establecido. Se requiere devolución inmediata.', 1),
(14, 1, 'Vencimiento', '2026-04-29', NULL, 'Pendiente', 'Se notifica que el préstamo ha excedido el plazo establecido. Se requiere devolución inmediata.', 1),
(15, 1, 'Vencimiento', '2026-04-29', NULL, 'Pendiente', 'Se notifica que el préstamo ha excedido el plazo establecido. Se requiere devolución inmediata.', 1),
(16, 1, 'Vencimiento', '2026-04-29', NULL, 'Pendiente', 'Se notifica que el préstamo ha excedido el plazo establecido. Se requiere devolución inmediata.', 1),
(17, 1, 'Vencimiento', '2026-04-29', NULL, 'Pendiente', 'Se notifica que el préstamo ha excedido el plazo establecido. Se requiere devolución inmediata.', 1),
(18, 1, 'Vencimiento', '2026-04-29', NULL, 'Pendiente', 'Se notifica que el préstamo ha excedido el plazo establecido. Se requiere devolución inmediata.', 1),
(19, 1, 'Vencimiento', '2026-04-29', NULL, 'Pendiente', 'Se notifica que el préstamo ha excedido el plazo establecido. Se requiere devolución inmediata.', 1),
(20, 1, 'Vencimiento', '2026-04-29', NULL, 'Pendiente', 'Se notifica que el préstamo ha excedido el plazo establecido. Se requiere devolución inmediata.', 1),
(21, 1, 'Vencimiento', '2026-04-29', NULL, 'Pendiente', 'Se notifica que el préstamo ha excedido el plazo establecido. Se requiere devolución inmediata.', 1),
(22, 1, 'Vencimiento', '2026-04-29', NULL, 'Pendiente', 'Se notifica que el préstamo ha excedido el plazo establecido. Se requiere devolución inmediata.', 1),
(23, 1, 'Vencimiento', '2026-04-29', NULL, 'Pendiente', 'Se notifica que el préstamo ha excedido el plazo establecido. Se requiere devolución inmediata.', 1),
(24, 1, 'Vencimiento', '2026-04-29', NULL, 'Pendiente', 'Se notifica que el préstamo ha excedido el plazo establecido. Se requiere devolución inmediata.', 1),
(25, 1, 'Vencimiento', '2026-04-13', NULL, 'Pendiente', 'Se notifica que el préstamo ha excedido el plazo establecido. Se requiere devolución inmediata.', 1),
(26, 1, 'Vencimiento', '2026-04-13', NULL, 'Pendiente', 'Se notifica que el préstamo ha excedido el plazo establecido. Se requiere devolución inmediata.', 1),
(27, 1, 'Vencimiento', '2026-04-29', NULL, 'Pendiente', 'Se notifica que el préstamo ha excedido el plazo establecido. Se requiere devolución inmediata.', 1),
(28, 1, 'Vencimiento', '2026-04-29', NULL, 'Pendiente', 'Se notifica que el préstamo ha excedido el plazo establecido. Se requiere devolución inmediata.', 1),
(29, 1, 'Vencimiento', '2026-04-29', NULL, 'Pendiente', 'Se notifica que el préstamo ha excedido el plazo establecido. Se requiere devolución inmediata.', 1),
(30, 1, 'Vencimiento', '2026-04-29', NULL, 'Pendiente', 'Se notifica que el préstamo ha excedido el plazo establecido. Se requiere devolución inmediata.', 1),
(31, 1, 'Vencimiento', '2026-04-29', NULL, 'Pendiente', 'Se notifica que el préstamo ha excedido el plazo establecido. Se requiere devolución inmediata.', 1),
(32, 1, 'Vencimiento', '2026-04-29', NULL, 'Pendiente', 'Se notifica que el préstamo ha excedido el plazo establecido. Se requiere devolución inmediata.', 1),
(33, 4, 'Vencimiento', '2026-04-29', NULL, 'Pendiente', 'Se notifica que el préstamo ha excedido el plazo establecido. Se requiere devolución inmediata.', 1),
(34, 4, 'Vencimiento', '2026-04-29', NULL, 'Pendiente', 'Se notifica que el préstamo ha excedido el plazo establecido. Se requiere devolución inmediata.', 1),
(35, 1, 'Vencimiento', '2026-04-29', NULL, 'Pendiente', 'Se notifica que el préstamo ha excedido el plazo establecido. Se requiere devolución inmediata.', 1),
(36, 1, 'Vencimiento', '2026-04-29', NULL, 'Pendiente', 'Se notifica que el préstamo ha excedido el plazo establecido. Se requiere devolución inmediata.', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamo`
--

CREATE TABLE `prestamo` (
  `id` int(11) NOT NULL,
  `numero_guia` varchar(30) NOT NULL COMMENT 'Número de guía (único)',
  `dependencia_id` int(11) NOT NULL COMMENT 'Dependencia solicitante',
  `usuario_solicitante` int(11) NOT NULL,
  `fecha_prestamo` date NOT NULL COMMENT 'Fecha préstamo',
  `fecha_devolucion_esperada` date NOT NULL COMMENT 'Plazo',
  `fecha_devolucion_real` date DEFAULT NULL,
  `estado` enum('Activo','Devuelto','Vencido') DEFAULT 'Activo',
  `observaciones` text DEFAULT NULL,
  `usuario_registro` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `prestamo`
--

INSERT INTO `prestamo` (`id`, `numero_guia`, `dependencia_id`, `usuario_solicitante`, `fecha_prestamo`, `fecha_devolucion_esperada`, `fecha_devolucion_real`, `estado`, `observaciones`, `usuario_registro`, `fecha_creacion`) VALUES
(1, 'PREST-001', 1, 1, '2026-04-13', '2026-04-20', NULL, 'Vencido', NULL, 1, '2026-04-14 02:00:38'),
(2, 'PREST-002', 4, 1, '2026-04-13', '2026-04-20', NULL, 'Vencido', NULL, 1, '2026-04-14 02:03:24'),
(3, 'PREST-003', 2, 1, '2026-04-13', '2026-04-20', NULL, 'Vencido', NULL, 1, '2026-04-14 02:03:31'),
(4, 'PREST-004', 2, 1, '2026-04-21', '2026-04-28', NULL, 'Vencido', NULL, 1, '2026-04-22 02:18:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','fiscal','archivo') DEFAULT 'fiscal',
  `estado` tinyint(4) DEFAULT 1,
  `ultimo_acceso` datetime DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nombre`, `email`, `password`, `rol`, `estado`, `ultimo_acceso`, `fecha_creacion`) VALUES
(1, 'Administrador', 'admin@mp.gob.pe', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1, NULL, '2026-04-14 01:28:09'),
(2, 'Fiscal García', 'fiscal1@mp.gob.pe', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'fiscal', 1, NULL, '2026-04-14 01:28:09'),
(3, 'Fiscal Martínez', 'fiscal2@mp.gob.pe', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'fiscal', 1, NULL, '2026-04-14 01:28:09'),
(4, 'Archivo Central', 'archivo@mp.gob.pe', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'archivo', 1, NULL, '2026-04-14 01:28:09');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `carpeta_fiscal`
--
ALTER TABLE `carpeta_fiscal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_carpeta` (`numero_carpeta`),
  ADD KEY `usuario_creacion` (`usuario_creacion`),
  ADD KEY `idx_numero` (`numero_carpeta`),
  ADD KEY `idx_estado` (`estado`);

--
-- Indices de la tabla `dependencia`
--
ALTER TABLE `dependencia`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indices de la tabla `detalle_prestamo`
--
ALTER TABLE `detalle_prestamo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_prestamo` (`prestamo_id`),
  ADD KEY `idx_carpeta` (`carpeta_id`);

--
-- Indices de la tabla `devolucion`
--
ALTER TABLE `devolucion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prestamo_id` (`prestamo_id`),
  ADD KEY `usuario_recepcion` (`usuario_recepcion`);

--
-- Indices de la tabla `notificacion`
--
ALTER TABLE `notificacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prestamo_id` (`prestamo_id`),
  ADD KEY `usuario_generacion` (`usuario_generacion`);

--
-- Indices de la tabla `prestamo`
--
ALTER TABLE `prestamo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_guia` (`numero_guia`),
  ADD KEY `dependencia_id` (`dependencia_id`),
  ADD KEY `usuario_solicitante` (`usuario_solicitante`),
  ADD KEY `usuario_registro` (`usuario_registro`),
  ADD KEY `idx_estado` (`estado`),
  ADD KEY `idx_fechas` (`fecha_devolucion_esperada`),
  ADD KEY `idx_guia` (`numero_guia`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `carpeta_fiscal`
--
ALTER TABLE `carpeta_fiscal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `dependencia`
--
ALTER TABLE `dependencia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `detalle_prestamo`
--
ALTER TABLE `detalle_prestamo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `devolucion`
--
ALTER TABLE `devolucion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notificacion`
--
ALTER TABLE `notificacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `prestamo`
--
ALTER TABLE `prestamo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `auditoria`
--
ALTER TABLE `auditoria`
  ADD CONSTRAINT `auditoria_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `carpeta_fiscal`
--
ALTER TABLE `carpeta_fiscal`
  ADD CONSTRAINT `carpeta_fiscal_ibfk_1` FOREIGN KEY (`usuario_creacion`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `detalle_prestamo`
--
ALTER TABLE `detalle_prestamo`
  ADD CONSTRAINT `detalle_prestamo_ibfk_1` FOREIGN KEY (`prestamo_id`) REFERENCES `prestamo` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detalle_prestamo_ibfk_2` FOREIGN KEY (`carpeta_id`) REFERENCES `carpeta_fiscal` (`id`);

--
-- Filtros para la tabla `devolucion`
--
ALTER TABLE `devolucion`
  ADD CONSTRAINT `devolucion_ibfk_1` FOREIGN KEY (`prestamo_id`) REFERENCES `prestamo` (`id`),
  ADD CONSTRAINT `devolucion_ibfk_2` FOREIGN KEY (`usuario_recepcion`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `notificacion`
--
ALTER TABLE `notificacion`
  ADD CONSTRAINT `notificacion_ibfk_1` FOREIGN KEY (`prestamo_id`) REFERENCES `prestamo` (`id`),
  ADD CONSTRAINT `notificacion_ibfk_2` FOREIGN KEY (`usuario_generacion`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `prestamo`
--
ALTER TABLE `prestamo`
  ADD CONSTRAINT `prestamo_ibfk_1` FOREIGN KEY (`dependencia_id`) REFERENCES `dependencia` (`id`),
  ADD CONSTRAINT `prestamo_ibfk_2` FOREIGN KEY (`usuario_solicitante`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `prestamo_ibfk_3` FOREIGN KEY (`usuario_registro`) REFERENCES `usuario` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
