-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 08-02-2026 a las 03:56:45
-- Versión del servidor: 8.0.17
-- Versión de PHP: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proyecto_29797`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria`
--

CREATE TABLE `auditoria` (
  `id_auditoria` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `usuario_nombre` varchar(100) DEFAULT NULL,
  `accion` text,
  `ip_conexion` varchar(45) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `auditoria`
--

INSERT INTO `auditoria` (`id_auditoria`, `id_usuario`, `usuario_nombre`, `accion`, `ip_conexion`, `fecha_registro`) VALUES
(1, 1, 'Administrador', 'Creó al nuevo usuario: user2', '::1', '2026-02-01 21:39:40'),
(2, 1, 'Administrador', 'Actualizó datos del usuario: Pedrinchi123 (Estado: A)', '::1', '2026-02-01 21:39:54'),
(3, 1, 'Administrador', 'Creó un nuevo rol: Prueba', '::1', '2026-02-01 21:42:54'),
(4, 1, 'Administrador', 'Actualizó el rol: Superventas ', '::1', '2026-02-01 21:44:16'),
(5, 1, 'Administrador', 'Actualizó el rol: Superventas', '::1', '2026-02-01 21:45:19'),
(6, 1, 'Administrador', 'Creó un nuevo rol: Nuebo ROl', '::1', '2026-02-01 21:47:03'),
(7, 1, 'Administrador', 'Eliminó el rol: Nuebo ROl', '::1', '2026-02-01 21:47:15'),
(8, 1, 'Administrador', 'Actualizó los permisos del rol: Vendedor', '::1', '2026-02-01 21:50:56'),
(9, 1, 'Administrador', 'Actualizó los permisos del rol: Vendedor', '::1', '2026-02-01 21:51:07'),
(10, 1, 'Administrador', 'Actualizó los permisos del rol: Vendedor', '::1', '2026-02-01 21:51:59'),
(11, 1, 'Administrador', 'Actualizó los permisos del rol: Vendedor', '::1', '2026-02-01 21:52:32'),
(12, NULL, 'User1', 'Creó un nuevo rol: Rol del usuario', '::1', '2026-02-01 21:52:52'),
(13, NULL, 'User1', 'Creó al nuevo usuario: user1_1', '::1', '2026-02-01 21:53:07'),
(14, 1, 'Administrador', 'Actualizó el rol: Administrador', '::1', '2026-02-02 07:08:09'),
(15, 1, 'Administrador', 'Creó al nuevo usuario: Daqui', '::1', '2026-02-02 07:10:35'),
(16, 1, 'Administrador', 'Actualizó datos del usuario: Daqui (Estado: A)', '::1', '2026-02-02 07:19:23'),
(17, 1, 'Administrador', 'Actualizó al usuario ID 6. Nuevo nombre: Deivi Quipe, Estado: A, Rol: Rol del usuario', '::1', '2026-02-02 07:21:07'),
(18, 1, 'Administrador', 'Actualizó los permisos del rol: Superventas', '::1', '2026-02-02 07:25:29'),
(19, 1, 'Administrador', 'Actualizó los permisos del rol: Superventas', '::1', '2026-02-02 07:27:24'),
(20, 1, 'Administrador', 'Creó un nuevo rol: Cajero', '::1', '2026-02-02 07:52:03'),
(21, 1, 'Administrador', 'Actualizó los permisos del rol: Cajero', '::1', '2026-02-02 07:53:22'),
(22, 1, 'Administrador', 'Creó al usuario: jchavez y le asignó el Rol ID: 7', '::1', '2026-02-02 07:53:39'),
(23, 1, 'Administrador', 'Actualizó los permisos del rol: Cajero', '::1', '2026-02-02 07:54:21'),
(24, 7, 'jchavez', 'Actualizó al usuario ID 7. Nuevo nombre: Juan Chavez, Estado: A, Rol: Cajero', '::1', '2026-02-02 08:02:40'),
(25, 7, 'jchavez', 'Actualizó al usuario ID 7. Nuevo nombre: Juan Chavez, Estado: I, Rol: Cajero', '::1', '2026-02-02 08:02:46'),
(26, 1, 'Administrador', 'Inactivó al usuario: Administrador', '::1', '2026-02-07 12:35:40'),
(27, 1, 'Administrador', 'Actualizó los permisos del rol: Cajero', '::1', '2026-02-07 12:40:20'),
(28, 1, 'Administrador', 'Actualizó los permisos del rol: Cajero', '::1', '2026-02-07 12:40:47'),
(29, 1, 'Administrador', 'Actualizó los permisos del rol: Cajero', '::1', '2026-02-07 12:41:31'),
(30, 1, 'Administrador', 'Actualizó los permisos del rol: Cajero', '::1', '2026-02-07 12:45:26'),
(31, 1, 'Administrador', 'Actualizó los permisos del rol: Cajero', '::1', '2026-02-07 12:46:07'),
(32, 1, 'Administrador', 'Actualizó al usuario ID 1. Nuevo nombre: ADMIN MAESTRO', '::1', '2026-02-07 13:40:29'),
(33, 1, 'Administrador', 'Actualizó al usuario ID 1. Nuevo nombre: ADMIN MAESTRO', '::1', '2026-02-07 13:40:43'),
(34, 1, 'Administrador', 'Actualizó al usuario ID 1. Nuevo nombre: AM', '::1', '2026-02-07 13:40:59'),
(35, 1, 'Administrador', 'Actualizó al usuario ID 1. Nuevo nombre: AM', '::1', '2026-02-07 13:43:48'),
(36, 1, 'Administrador', 'Actualizó al usuario ID 1. Nuevo nombre: ADMIN MAESTRO', '::1', '2026-02-07 13:44:18'),
(37, 1, 'Administrador', 'Actualizó al usuario ID 1. Nuevo nombre: ADMIN MAESTRO', '::1', '2026-02-07 13:44:33'),
(38, 1, 'Administrador', 'Inactivó al usuario: jchavez', '::1', '2026-02-07 13:45:14'),
(39, 1, 'Administrador', 'Inactivó al usuario: Administrador', '::1', '2026-02-07 14:13:16'),
(40, 1, 'Administrador', 'Activó al usuario: Administrador', '::1', '2026-02-07 14:13:57'),
(41, 1, 'Administrador', 'Inactivó al usuario: Administrador', '::1', '2026-02-07 14:14:10'),
(42, 1, 'Administrador', 'Activó al usuario: jchavez', '::1', '2026-02-07 14:14:32'),
(43, 1, 'Administrador', 'Activó al usuario: Administrador', '::1', '2026-02-07 14:14:35'),
(44, 1, 'Administrador', 'Inactivó al usuario: Administrador', '::1', '2026-02-07 14:14:38'),
(45, 1, 'Administrador', 'Inactivó al usuario: jchavez', '::1', '2026-02-07 14:15:49'),
(46, 1, 'Administrador', 'Eliminó el rol: Administrador', '::1', '2026-02-07 14:19:25'),
(47, 1, 'Administrador', 'Activó al usuario: jchavez', '::1', '2026-02-07 14:27:59'),
(48, 1, 'Administrador', 'Inactivó al usuario: jchavez', '::1', '2026-02-07 14:28:04'),
(49, 1, 'Administrador', 'Activó al usuario: Administrador', '::1', '2026-02-07 14:28:13'),
(50, 1, 'Administrador', 'Inactivó al usuario: Administrador', '::1', '2026-02-07 14:28:23'),
(51, 1, 'Administrador', 'Actualizó al usuario ID 1. Nuevo nombre: ADMIN MAESTRO', '::1', '2026-02-07 14:42:19'),
(52, 1, 'Administrador', 'Activó al usuario: Administrador', '::1', '2026-02-07 14:42:47'),
(53, 1, 'Administrador', 'Actualizó al usuario ID 1. Nuevo nombre: ADMIN MAESTRO', '::1', '2026-02-07 14:46:07'),
(54, 1, 'Administrador', 'Activó al usuario: jchavez', '::1', '2026-02-07 14:51:26'),
(55, 1, 'Administrador', 'Creó al usuario: deivis y le asignó el Rol ID: 7', '::1', '2026-02-07 15:00:17'),
(56, 1, 'Administrador', 'Creó al usuario: daquispe2 y le asignó el Rol ID: 7', '::1', '2026-02-07 15:11:24'),
(57, 1, 'Administrador', 'Creó al usuario: a y le asignó el Rol ID: 0', '::1', '2026-02-07 15:26:57'),
(58, 1, 'Administrador', 'Actualizó al usuario ID 10. Nuevo nombre: andres', '::1', '2026-02-07 15:48:18'),
(59, 1, 'Administrador', 'Actualizó al usuario ID 10. Nuevo nombre: andres', '::1', '2026-02-07 15:48:28'),
(60, 1, 'Administrador', 'Actualizó al usuario ID 10. Nuevo nombre: andres, Estado: , Rol: Cajero', '::1', '2026-02-07 19:58:42'),
(61, 1, 'Administrador', 'Inactivó al usuario: a', '::1', '2026-02-07 20:00:56'),
(62, 1, 'Administrador', 'Activó al usuario: a', '::1', '2026-02-07 20:01:00'),
(63, 1, 'Administrador', 'Inactivó al usuario: Administrador', '::1', '2026-02-07 20:01:10'),
(64, 1, 'Administrador', 'Activó al usuario: Administrador', '::1', '2026-02-07 20:01:12'),
(65, 1, 'Administrador', 'Inactivó al usuario: jchavez', '::1', '2026-02-07 20:03:53'),
(66, 1, 'Administrador', 'Activó al usuario: jchavez', '::1', '2026-02-07 20:03:58'),
(67, 1, 'Administrador', 'Inactivó al usuario: jchavez', '::1', '2026-02-07 20:04:01'),
(68, 1, 'Administrador', 'Activó al usuario: jchavez', '::1', '2026-02-07 20:04:08'),
(69, 1, 'Administrador', 'Actualizó los permisos del rol: Cajero', '::1', '2026-02-07 20:13:35'),
(70, 1, 'Administrador', 'Actualizó al usuario ID 1. Nuevo nombre: ADMIN MAESTRO, Estado: , Rol: Administrador', '::1', '2026-02-07 20:18:55'),
(71, 1, 'Administrador', 'Actualizó al usuario ID 1. Nuevo nombre: ADMIN MAESTRO, Estado: , Rol: Administrador', '::1', '2026-02-07 20:19:20'),
(72, 1, 'Administrador', 'Actualizó al usuario ID 1. Nuevo nombre: ADMIN MAESTRO, Estado: , Rol: Administrador', '::1', '2026-02-07 20:24:36'),
(73, 1, 'Administrador', 'Actualizó al usuario ID 1. Nuevo nombre: ADMIN MAESTRO, Estado: , Rol: Administrador', '::1', '2026-02-07 20:24:55'),
(74, 1, 'Administrador', 'Actualizó los permisos del rol: Cajero', '::1', '2026-02-07 20:31:01'),
(75, 1, 'Administrador', 'El usuario: inicio sesión', '::1', '2026-02-07 20:37:25'),
(76, 1, 'Administrador', 'El usuario: Administrador inicio sesión', '::1', '2026-02-07 20:38:42'),
(77, 1, 'Administrador', 'Ha iniciado sesión', '::1', '2026-02-07 20:39:25'),
(78, 7, 'jchavez', 'Ha iniciado sesión', '::1', '2026-02-07 20:39:37'),
(79, 1, 'Administrador', 'Ha iniciado sesión', '::1', '2026-02-07 20:39:44'),
(80, 1, 'Administrador', 'Ha iniciado sesión', '::1', '2026-02-07 20:41:37'),
(81, 1, 'Administrador', 'Ha iniciado sesión', '::1', '2026-02-07 20:41:55'),
(82, 1, 'Administrador', 'Ha iniciado sesión', '::1', '2026-02-07 20:42:11'),
(84, 1, 'Administrador', 'Ha iniciado sesión', '::1', '2026-02-07 20:43:34'),
(86, 1, 'Administrador', 'Ha iniciado sesión', '::1', '2026-02-07 20:44:09'),
(87, 1, 'Administrador', 'Ha cerrado sesión', '::1', '2026-02-07 20:44:57'),
(88, 1, 'Administrador', 'Ha iniciado sesión', '::1', '2026-02-07 20:45:02'),
(89, 1, 'Administrador', 'Ha cerrado sesión', '::1', '2026-02-07 20:52:44'),
(94, 1, 'Administrador', 'Ha iniciado sesión exitosamente', '::1', '2026-02-07 20:54:08'),
(95, 1, 'Administrador', 'Inactivó al usuario: jchavez', '::1', '2026-02-07 20:55:12'),
(96, 1, 'Administrador', 'Ha cerrado sesión', '::1', '2026-02-07 20:55:14'),
(98, 1, 'Administrador', 'Ha iniciado sesión exitosamente', '::1', '2026-02-07 20:55:32'),
(99, 1, 'Administrador', 'Ha cerrado sesión', '::1', '2026-02-07 20:58:29'),
(104, 1, 'Administrador', 'Ha iniciado sesión exitosamente', '::1', '2026-02-07 20:58:55'),
(105, 1, 'Administrador', 'Ha cerrado sesión', '::1', '2026-02-07 21:00:14'),
(106, 1, 'Administrador', 'Fallo de contraseña (Intento 1)', '::1', '2026-02-07 21:00:20'),
(107, 1, 'Administrador', 'Ha iniciado sesión correctamente', '::1', '2026-02-07 21:00:25'),
(108, 1, 'Administrador', 'Ha cerrado sesión', '::1', '2026-02-07 21:00:41'),
(109, NULL, 'vfsvsd', 'Intento de acceso con usuario no existente: vfsvsd', '::1', '2026-02-07 21:00:46'),
(110, 1, 'Administrador', 'Ha iniciado sesión correctamente', '::1', '2026-02-07 21:00:51'),
(111, 1, 'Administrador', 'Ha cerrado sesión', '::1', '2026-02-07 21:01:02'),
(112, 7, 'jchavez', 'Intento de acceso: Usuario inactivo', '::1', '2026-02-07 21:01:13'),
(113, 1, 'Administrador', 'Ha iniciado sesión correctamente', '::1', '2026-02-07 21:01:19'),
(114, 1, 'Administrador', 'Activó al usuario: jchavez', '::1', '2026-02-07 21:03:43'),
(115, 1, 'Administrador', 'Inactivó al usuario: jchavez', '::1', '2026-02-07 21:14:49'),
(116, 1, 'Administrador', 'Activó al usuario: jchavez', '::1', '2026-02-07 21:14:54'),
(117, 1, 'Administrador', 'Inactivó al usuario: jchavez', '::1', '2026-02-07 21:15:05'),
(118, 1, 'Administrador', 'Activó al usuario: jchavez', '::1', '2026-02-07 21:18:28'),
(119, 1, 'Administrador', 'Eliminó el rol: Cajero', '::1', '2026-02-07 21:18:39'),
(120, 1, 'Administrador', 'Creó un nuevo rol: cajero', '::1', '2026-02-07 21:19:22'),
(121, 1, 'Administrador', 'Actualizó al usuario ID 7. Nuevo nombre: Juan Chavez, Estado: , Rol: cajero', '::1', '2026-02-07 21:20:03'),
(122, 1, 'Administrador', 'Creó al usuario: ejemplo1 y le asignó el Rol ID: 0', '::1', '2026-02-07 21:21:43'),
(123, 1, 'Administrador', 'Creó al usuario: ejemplo2 y le asignó el Rol ID: 0', '::1', '2026-02-07 21:21:55'),
(124, 1, 'Administrador', 'Creó un nuevo rol: Nuevas', '::1', '2026-02-07 21:28:38'),
(125, 1, 'Administrador', 'Creó un nuevo rol: xd', '::1', '2026-02-07 21:28:45'),
(126, 1, 'Administrador', 'Creó un nuevo rol: as', '::1', '2026-02-07 21:28:49'),
(127, 1, 'Administrador', 'Eliminó el rol: cajero', '::1', '2026-02-07 21:49:35'),
(128, 1, 'Administrador', 'Actualizó al usuario ID 7. Nuevo nombre: Juan Chavez, Estado: , Rol: xd', '::1', '2026-02-07 21:53:31'),
(129, 1, 'Administrador', 'Inactivó al usuario: ID 11', '::1', '2026-02-07 21:53:42'),
(130, 1, 'Administrador', 'Eliminó el rol: xd', '::1', '2026-02-07 21:53:42'),
(131, 1, 'Administrador', 'Actualizó al usuario ID 7. Nuevo nombre: Juan Chavez, Estado: , Rol: as', '::1', '2026-02-07 21:57:45'),
(132, 1, 'Administrador', 'Eliminó los roles al usuario: ID 12', '::1', '2026-02-07 21:58:02'),
(133, 1, 'Administrador', 'Eliminó el rol: as', '::1', '2026-02-07 21:58:02'),
(134, 1, 'Administrador', 'Actualizó al usuario ID 7. Nuevo nombre: Juan Chavez, Estado: , Rol: Nuevas', '::1', '2026-02-07 21:59:42'),
(135, 1, 'Administrador', 'Eliminó el rol al usuario: ID 10', '::1', '2026-02-07 22:04:28'),
(136, 1, 'Administrador', 'Eliminó el rol: Nuevas', '::1', '2026-02-07 22:04:28'),
(137, 1, 'Administrador', 'Creó un nuevo rol: asa', '::1', '2026-02-07 22:04:57'),
(138, 1, 'Administrador', 'Actualizó al usuario ID 7', '::1', '2026-02-07 22:05:16'),
(139, 1, 'Administrador', 'Eliminó el rol al usuario: ID 13', '::1', '2026-02-07 22:12:49'),
(140, 1, 'Administrador', 'Eliminó el rol: asa', '::1', '2026-02-07 22:12:49'),
(141, 1, 'Administrador', 'Ha cerrado sesión', '::1', '2026-02-07 22:14:48'),
(142, 1, 'Administrador', 'Ha iniciado sesión correctamente', '::1', '2026-02-07 22:14:53'),
(143, 1, 'Administrador', 'Creó un nuevo rol: as', '::1', '2026-02-07 22:17:14'),
(144, 1, 'Administrador', 'Actualizó al usuario ID 7', '::1', '2026-02-07 22:17:27'),
(145, 1, 'Administrador', 'Actualizó al usuario ID 9', '::1', '2026-02-07 22:21:15'),
(146, 1, 'Administrador', 'Eliminó el rol al usuario: ID 14', '::1', '2026-02-07 22:21:50'),
(147, 1, 'Administrador', 'Eliminó el rol: as', '::1', '2026-02-07 22:21:50'),
(148, 1, 'Administrador', 'Inactivó al usuario: jchavez', '::1', '2026-02-07 22:27:52'),
(149, 1, 'Administrador', 'Actualizó al usuario ID 7', '::1', '2026-02-07 22:30:53'),
(150, 1, 'Administrador', 'Actualizó al usuario ID 7', '::1', '2026-02-07 22:31:04'),
(151, 1, 'Administrador', 'Actualizó al usuario ID 7', '::1', '2026-02-07 22:31:55'),
(152, 1, 'Administrador', 'Actualizó al usuario ID 7', '::1', '2026-02-07 22:32:07'),
(153, 1, 'Administrador', 'Actualizó al usuario ID 9', '::1', '2026-02-07 22:32:51'),
(154, 1, 'Administrador', 'Actualizó al usuario ID 9', '::1', '2026-02-07 22:32:55'),
(155, 1, 'Administrador', 'Actualizó al usuario ID 9', '::1', '2026-02-07 22:33:00'),
(156, 1, 'Administrador', 'Actualizó al usuario ID 9', '::1', '2026-02-07 22:33:13'),
(157, 1, 'Administrador', 'Actualizó al usuario ID 7', '::1', '2026-02-07 22:34:39'),
(158, 1, 'Administrador', 'Actualizó al usuario ID 7', '::1', '2026-02-07 22:34:44'),
(159, 1, 'Administrador', 'Creó el curso: USABILIDAD', '::1', '2026-02-07 22:54:20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `id_curso` int(11) NOT NULL,
  `nombre_curso` varchar(100) NOT NULL,
  `descripcion` text,
  `fecha_inicio` date DEFAULT NULL,
  `duracion_horas` int(11) DEFAULT '0',
  `estado` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`id_curso`, `nombre_curso`, `descripcion`, `fecha_inicio`, `duracion_horas`, `estado`) VALUES
(1, 'Introducción a PHP', 'Curso básico de backend', '2024-03-01', 20, 1),
(2, 'Base de Datos MySQL', 'Gestión y diseño de BD', '2024-03-15', 30, 1),
(3, 'JavaScript Moderno', 'ES6 y manipulación del DOM', '2024-04-01', 25, 1),
(4, 'USABILIDAD', 'ESTUDIAR', '2026-02-11', 50, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menus`
--

CREATE TABLE `menus` (
  `id_menu` int(11) NOT NULL,
  `nombre_texto` varchar(50) NOT NULL,
  `url` varchar(100) DEFAULT '#',
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `menus`
--

INSERT INTO `menus` (`id_menu`, `nombre_texto`, `url`, `parent_id`) VALUES
(1, 'Inicio', 'principal.php', NULL),
(2, 'Usuarios', 'usuarios.php', NULL),
(3, 'Roles', 'crear_rol.php', NULL),
(6, 'Administrar Permisos', 'roles_permisos.php', NULL),
(7, 'Auditoría', 'auditoria.php', NULL),
(10, 'Crear Usuario', 'usuarios_crear.php', 2),
(11, 'Editar Usuario', 'usuarios_actualizar.php', 2),
(12, 'Eliminar Usuario', 'usuarios_eliminar.php', 2),
(13, 'Crear Rol', 'roles_crear.php', 3),
(14, 'Editar Rol', 'roles_actualizar.php', 3),
(15, 'Eliminar Rol', 'roles_eliminar.php', 3),
(16, 'Cursos', 'cursos.php', NULL),
(17, 'Crear Curso', 'cursos_crear.php', 16),
(18, 'Editar Curso', 'cursos_actualizar.php', 16),
(19, 'Eliminar Curso', '#', 16);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos_rol`
--

CREATE TABLE `permisos_rol` (
  `id_rol` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `permisos_rol`
--

INSERT INTO `permisos_rol` (`id_rol`, `id_menu`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 6),
(1, 7),
(1, 10),
(1, 11),
(1, 12),
(1, 13),
(1, 14),
(1, 15),
(1, 16),
(1, 17),
(1, 18),
(1, 19);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(50) NOT NULL,
  `descripcion` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre_rol`, `descripcion`) VALUES
(0, 'SIN_ROL', 'EL USUARIO NO TIENE PERMISOS'),
(1, 'Administrador', 'El más de más de másiso');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre_real` varchar(100) NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `cedula` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `direccion` text,
  `ultimo_acceso` datetime DEFAULT NULL,
  `estado` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `username`, `password`, `nombre_real`, `fecha_nacimiento`, `cedula`, `email`, `direccion`, `ultimo_acceso`, `estado`) VALUES
(1, 'Administrador', '$2y$10$YP1OQjdP401hYGsbulOYcujc8hfhKZCpKQ5XFGveUMLspWUZd0oKW', 'ADMIN MAESTRO', '2026-02-05', '1721676268', 'flickse234@gmail.com', 'Quitumbe', '2026-02-07 22:14:53', 1),
(7, 'jchavez', '$2y$10$76vVKnl9lbNyvmz490ngo./qmA6D9DUhQ.v1cQguYtEf8yV6falf2', 'Juan Chavez', '0000-00-00', '', '', '', '2026-02-07 20:39:37', 0),
(9, 'daquispe2', '$2y$10$xw/vW6S6xrkbejly70N2l.7xiqkA6bEf6YtDNhtCW.w9yhS19GNUq', 'DEIVIS', '2026-02-17', '54', 'flickse234@gmail.com', 'Quitumbe', NULL, 1),
(10, 'a', '$2y$10$/4F2pFtluiyR4zobmxTCXeLThKJbN3R1AQaVLLooBI3PZxjqAK9Om', 'andres', '0000-00-00', '', '', '', NULL, 1),
(11, 'ejemplo1', '$2y$10$roNI85/Z7Gb3zAgpg0UPD.f7tI/WO/FUL503ot9OMtGONsFizLbzS', 'ejemplo', '0000-00-00', '', '', '', NULL, 1),
(12, 'ejemplo2', '$2y$10$KTRQo/Netk.OpkQ9UiYePugACEbIoi09X49T.mi5skT25PTgUNNBy', 'ejemplo2', '0000-00-00', '', '', '', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_roles`
--

CREATE TABLE `usuario_roles` (
  `id_usuario` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuario_roles`
--

INSERT INTO `usuario_roles` (`id_usuario`, `id_rol`) VALUES
(7, 0),
(9, 0),
(11, 0),
(12, 0),
(1, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  ADD PRIMARY KEY (`id_auditoria`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id_curso`);

--
-- Indices de la tabla `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id_menu`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indices de la tabla `permisos_rol`
--
ALTER TABLE `permisos_rol`
  ADD PRIMARY KEY (`id_rol`,`id_menu`),
  ADD KEY `id_menu` (`id_menu`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indices de la tabla `usuario_roles`
--
ALTER TABLE `usuario_roles`
  ADD PRIMARY KEY (`id_usuario`,`id_rol`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  MODIFY `id_auditoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id_curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `menus`
--
ALTER TABLE `menus`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `auditoria`
--
ALTER TABLE `auditoria`
  ADD CONSTRAINT `auditoria_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL;

--
-- Filtros para la tabla `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `menus_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `menus` (`id_menu`) ON DELETE CASCADE;

--
-- Filtros para la tabla `permisos_rol`
--
ALTER TABLE `permisos_rol`
  ADD CONSTRAINT `permisos_rol_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON DELETE CASCADE,
  ADD CONSTRAINT `permisos_rol_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `menus` (`id_menu`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuario_roles`
--
ALTER TABLE `usuario_roles`
  ADD CONSTRAINT `usuario_roles_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `usuario_roles_ibfk_2` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
