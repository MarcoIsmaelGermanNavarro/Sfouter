-- --------------------------------------------------------
-- Host:                         kodama.proxy.rlwy.net
-- Versión del servidor:         9.4.0 - MySQL Community Server - GPL
-- SO del servidor:              Linux
-- HeidiSQL Versión:             12.17.0.7270
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Volcando datos para la tabla railway.equipo: ~6 rows (aproximadamente)
INSERT INTO `equipo` (`id`, `nombre`) VALUES
	(1, 'Juventus de Turín'),
	(2, 'Real Ávila'),
	(3, 'Oporto'),
	(4, 'Real Madrid'),
	(5, 'Arsenal'),
	(6, 'Barcelona');

-- Volcando datos para la tabla railway.informe: ~4 rows (aproximadamente)
INSERT INTO `informe` (`id`, `idUsuario`, `idJugador`, `idEquipo`, `fechaInf`, `fechaReg`, `posicion`, `velocidad`, `resistencia`, `dribbling`, `observaciones`) VALUES
	(3, 18, 4, 1, '2026-05-22', '2026-05-22 15:50:02', 'Medio', 5, 5, 5, 'A'),
	(4, 18, 4, 3, '2026-05-22', '2026-05-22 17:24:40', 'Delantero', 5, 5, 5, 'b'),
	(5, 18, 5, 1, '2026-05-23', '2026-05-23 12:35:52', 'Delantero', 10, 7, 10, 'Jugador excepcional de clase mundial.'),
	(11, 18, 12, 3, '2026-05-27', '2026-05-27 00:21:49', 'Delantero', 7, 7, 4, 'b');

-- Volcando datos para la tabla railway.jugador: ~4 rows (aproximadamente)
INSERT INTO `jugador` (`id`, `nombre`, `apellidos`, `fechaNac`, `foto`) VALUES
	(2, 'Lamine', 'Yamal', '2007-07-13', 'lamine.jpg'),
	(4, 'Seko', 'Fofana', '1995-05-07', '1779368854_SekoFofana.jpg'),
	(5, 'Kylian', 'Mbappé', '1998-12-20', '1779539727_Mbappe.jpg'),
	(12, 'Romelu', 'Lukaku', '1997-07-02', '1779841257_Lukaku.jpg');

-- Volcando datos para la tabla railway.usuario: ~7 rows (aproximadamente)
INSERT INTO `usuario` (`id`, `nombre`, `apellidos`, `email`, `password`, `rol`, `fechaReg`) VALUES
	(1, 'Marco', 'Pérez', 'marcosd@gmail.com', '$2y$10$sH17GqyLLuboczKNsdk6HOksTtW/ZL1vex2BBq.Afwr5mXrhb/iIC', 'scout', '2026-04-29 17:50:51'),
	(16, 'Javier', 'Gomez', 'Gomez@gmail.com', '$2y$10$oL1YjewdWelsgQZRu2wgLuv46xIlTlKkmGo/6EDN6NqI1pblhXOhe', 'scout', '2026-05-19 12:05:58'),
	(17, 'Javier', 'Perez', 'javi@gmail.com', '$2y$10$QEbuKEZAHnKyk70hGMBoP.dSUMWwuCl35Ulhgp4jyW7I.kwqPTp7q', 'admin', '2026-05-20 22:15:40'),
	(18, 'Marco', 'Germán', 'admin@gmail.com', '$2y$10$w3mgVtpDi8SfetoElp6w6.9xh3e3ueWlFPuxSWuf4sv.HEgt6p2Ja', 'admin', '2026-05-22 13:46:02'),
	(19, 'Pepe', 'Mayorga', 'Pepe@gmail.com', '$2y$12$xMChcN1yrNuJxOYGWb9Dq.lmfg0bh.w2mfIb5Yq.1RsXu.909LK0y', 'admin', '2026-05-26 11:54:38'),
	(20, 'Jorge', 'Perez12', 'Jorge@gmail.com', '$2y$12$NrNxO8V9uo6IExpLPGpTyeVHF4817JKiXW.KGOWP/ldy5/khFRqbO', 'admin', '2026-05-26 23:35:21'),
	(21, 'Scouter', 'Scout', 'scouter@gmail.com', '$2y$12$SqhgbbuiXmUXe9T4QascfuIzduEvf3mkVg0mN8W0zgVzoah6LpQRm', 'scout', '2026-05-29 11:35:48');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
