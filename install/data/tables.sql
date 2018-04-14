SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos:
--

--
-- Estructura de tabla para la tabla `main_permissions`
--

CREATE TABLE `main_permissions` (
  `tag` varchar(20) COLLATE utf8_bin PRIMARY KEY,
  `description` text COLLATE utf8_bin,
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creador` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='App permissions';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `main_roles`
--

CREATE TABLE `main_roles` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `author` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `email` varchar(155) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `id_role` int(11) NOT NULL,
  `logins` int(100) NOT NULL,
  `join_date` datetime NOT NULL,
  `last_login` datetime NOT NULL,
  `email_verified` tinyint(4) NOT NULL DEFAULT '0',
  `vericode` varchar(15) NOT NULL,
  `title` varchar(100) NOT NULL,
  `active` int(1) NOT NULL,
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `author` int(11) DEFAULT '-1',
  FOREIGN KEY (`id_role`) REFERENCES `main_roles` (`id`) ON DELETE CASCADE,
  KEY `EMAIL` (`email`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `main_rolepermissions`
--

CREATE TABLE `main_rolepermissions` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `id_role` int(15) NOT NULL,
  `id_permission` varchar(25) COLLATE utf8_bin NOT NULL,
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `author` int(11) DEFAULT '0',
  FOREIGN KEY (`id_role`) REFERENCES `main_roles` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`id_permission`) REFERENCES `main_permissions` (`tag`) ON UPDATE CASCADE,
  UNIQUE KEY `UC_relation` (`id_role`,`id_permission`),
  KEY `id_permission` (`id_permission`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Relations of permissions and a role.';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users_online`
--

CREATE TABLE `users_online` (
  `id` int(10) PRIMARY KEY AUTO_INCREMENT,
  `ip` varchar(15) NOT NULL,
  `timestamp` varchar(15) NOT NULL,
  `user_id` int(10) NOT NULL,
  `session` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;