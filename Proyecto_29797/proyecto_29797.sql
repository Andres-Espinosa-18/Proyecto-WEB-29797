-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 02-02-2026 a las 14:28:12
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
(25, 7, 'jchavez', 'Actualizó al usuario ID 7. Nuevo nombre: Juan Chavez, Estado: I, Rol: Cajero', '::1', '2026-02-02 08:02:46');

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
(4, 'Editar Usuario', 'usuarios_actualizar.php', 2),
(5, 'Eliminar Usuario', 'usuarios_eliminar.php', 2),
(6, 'Administrar Permisos', 'roles_permisos.php', NULL),
(7, 'Auditoría', 'auditoria.php', NULL),
(10, 'Crear Usuario', 'usuarios_crear.php', 2),
(11, 'Editar Usuario', 'usuarios_actualizar.php', 2),
(12, 'Eliminar Usuario', 'usuarios_eliminar.php', 2),
(13, 'Crear Rol', 'roles_crear.php', 3),
(14, 'Editar Rol', 'roles_actualizar.php', 3),
(15, 'Eliminar Rol', 'roles_eliminar.php', 3);

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
(7, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 10),
(1, 11),
(7, 11),
(1, 12),
(1, 13),
(1, 14),
(1, 15);

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
(1, 'Administrador', 'El más de más de másiso'),
(7, 'Cajero', 'Es un cajero');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre_real` varchar(100) NOT NULL,
  `ultimo_acceso` datetime DEFAULT NULL,
  `estado` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `username`, `password`, `nombre_real`, `ultimo_acceso`, `estado`) VALUES
(1, 'Administrador', '$2y$10$Jt9nJktYYO0QXIM6EAyzYeCUbgnWRBfftsEx.Iefb1Ki.J61iOueK', 'Admin Maestro', '2026-02-02 08:04:59', 1),
(7, 'jchavez', '$2y$10$crTdCD0Z8wt45UCrsaw4yuu5Dlejz0vmTqanB6tanEytJTrWwAFfu', 'Juan Chavez', '2026-02-02 08:03:47', 0);

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
(1, 1),
(7, 7);

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
  MODIFY `id_auditoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `menus`
--
ALTER TABLE `menus`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
