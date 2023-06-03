-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.24-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping data for table eds_school.codes: ~4 rows (approximately)
INSERT IGNORE INTO `codes` (`id`, `code`, `created_by`, `date`, `used`, `category`, `type`) VALUES
	(1, 'aaaaaa@0000@AAA', 0, '2023-05-09', 1, 'B', 0),
	(14, 'rkmvek@5377@6GE', 14, '2023-05-27', 1, 'B', 0),
	(39, 'ygrjfw@3218@FYN', 23, '2023-06-02', 0, 'D', 0),
	(40, 'lfrboi@3324@GUQ', 23, '2023-06-04', 1, 'B', 1);

-- Dumping data for table eds_school.questions: ~0 rows (approximately)
INSERT IGNORE INTO `questions` (`id`, `question`, `a`, `b`, `c`, `ans`) VALUES
	(1, 'test', 'esasdasd', 'asdasd', 'asdasdasd', 'a');

-- Dumping data for table eds_school.users: ~10 rows (approximately)
INSERT IGNORE INTO `users` (`id`, `code`, `username`, `password`, `name`, `prename`, `date_created`, `rank`, `teacher_id`, `category`, `phone`) VALUES
	(14, 'aaaaaa@0000@AAA', 'AlexMihai04', '$2y$10$UA1j.I32oCfrL/QAFZoSxegi9RLBBNpW3P8RgAy1u1YxNAKyR1VXy', 'Udrescu', 'Alexandru', '2023-05-09', 'instructor', 23, 'B', 733472020),
	(16, 'zevaun@3747@KOL', 'test', '$2y$10$RJd.lUUQf8IGiCJkKA1XnedS1rXbllzJd1NFpEA28oFnTtfZQTQiK', 'Alex', 'Mihai', '2023-05-24', 'elev', 14, 'B', 733472020),
	(17, 'rkmvek@5377@6GE', 'infoeeducatie', '$2y$10$ZEV9/9mbyqD/qZi9VYedB.yhMnlauPceK10TWfBvCTZ4K9ASpkfVS', 'Alex', 'Mihai', '2023-05-27', 'elev', 14, 'B', 733472020),
	(18, 'otsppf@1553@NRV', 'concurs', '$2y$10$ToKmCJ2zCTAOcs.LzRocgOmIMQm0nEVVDCLQmEz8zfA0dCBe0WN/W', 'Gica', 'Petrescu', '2023-05-31', 'elev', 14, 'B', 733472020),
	(19, 'djxvcq@7424@JOQ', 'mm24', '$2y$10$RDEsBel5R2WEg8hhPzIqR.TeBhGGPzCT76UtUP2L/5lX8qXOXWl8u', 'Mindrila', 'Mihai', '2023-06-01', 'elev', 14, 'B', 733472020),
	(20, 'afmltq@2660@IGB', 'alexm', '$2y$10$RkETNNLf7gyMCdUX.jaPyucTMncUhOwxVtbJ0f6L3Tklw9cGt0rHm', 'tanase', 'andrei', '2023-06-01', 'elev', 14, 'B', 733472020),
	(21, 'hnatde@9150@PBS', 'jojo', '$2y$10$ODnmObZaopVP7mbYq8WnIuXMK.Nx5gG37vbiqEVNV.swxlbd.qxrO', 'jojo', 'jojo', '2023-06-02', 'elev', 14, 'B', 733472020),
	(23, 'nagjbd@5822@XDE', 'instructor', '$2y$10$cI7d8a1X6C7RU8HXXhsdFOqMjhCKatq6rGRrpP76uV0DzRYPTvece', 'instructor', 'instructor', '2023-06-02', 'detinator_firma', 0, 'B', 733472020),
	(24, 'efbjkd@6537@JUY', 'koko', '$2y$10$20crf3eVGyKpUqKhEDz7GO7/u/nFSutZnqnM1PDUfi.sKCBdi4m9i', 'koko', 'koko', '2023-06-02', 'elev', 14, 'B', 721168093),
	(25, 'lfrboi@3324@GUQ', 'test2', '$2y$10$AgXaXvotX5WVdzwypUdAQ.C6Cz.MSUZOliyXqPQCTEY3golSISx7.', 'test', 'test', '2023-06-03', 'instructor', 23, 'B', 733472020);

-- Dumping data for table eds_school.user_data: ~11 rows (approximately)
INSERT IGNORE INTO `user_data` (`id`, `data`) VALUES
	(14, '{"hours_added":[{"date":"5\\/25\\/2023","hour":"10:30","used":"true"},{"date":"5\\/25\\/2023","hour":"11:30","used":"true"},{"date":"5\\/26\\/2023","hour":"10:30","used":"true"},{"date":"5\\/27\\/2023","hour":"10:30","used":"true"},{"date":"5\\/27\\/2023","hour":"12:30","used":"true"},{"date":"5\\/28\\/2023","hour":"10:30"},{"date":"5\\/28\\/2023","hour":"12:30"},{"date":"5\\/30\\/2023","hour":"10:30","used":"true","used_by":"16"},{"date":"6\\/1\\/2023","hour":"11:30","used":"false","used_by":"18"},{"date":"6\\/1\\/2023","hour":"11:40","used":"false","used_by":"18"},{"date":"6\\/1\\/2023","hour":"11:50"},{"date":"6\\/2\\/2023","hour":"10:30","used":"true","used_by":"19"},{"date":"6\\/30\\/2023","hour":"11:30"},{"date":"6\\/30\\/2023","hour":"10:30"},{"date":"6\\/28\\/2023","hour":"9:30"},{"date":"6\\/6\\/2023","hour":"11:30"},{"date":"6\\/7\\/2023","hour":"11:30"},{"date":"6\\/5\\/2023","hour":"11:30"}],"step":"1","car_plate":"PH82UDR","banned":"false"}'),
	(16, '{"schooldw":"true","step":"2","hours_done":[{"date":"5\\/27\\/2023","hour":"10:30"},{"date":"5\\/28\\/2023","hour":"10:30"},{"date":"5\\/30\\/2023","hour":"10:30"},{"date":"5\\/30\\/2023","hour":"10:30"}],"next_hour":"","chestionare":[{"date":"5\\/29\\/2023","correct":"0","total_q":"23"},{"date":"5\\/29\\/2023","correct":"0","total_q":"23"},{"date":"5\\/29\\/2023","correct":"0","total_q":"23"},{"date":"5\\/29\\/2023","correct":"0","total_q":"23"},{"date":"5\\/29\\/2023","correct":"0","total_q":"23"},{"date":"5\\/29\\/2023","correct":"0","total_q":"23"},{"date":"5\\/29\\/2023","correct":"1","total_q":"23"},{"date":"5\\/29\\/2023","correct":"0","total_q":"4"},{"date":"5\\/29\\/2023","correct":"0","total_q":"1"},{"date":"5\\/29\\/2023","correct":"0","total_q":"23"},{"date":"5\\/29\\/2023","correct":"1","total_q":"22"},{"date":"5\\/31\\/2023","correct":"3","total_q":"21"},{"date":"5\\/31\\/2023","correct":"1","total_q":"20"}],"schoold":"false","cod_reg":"123998","sala_p":"15.05.2023","oras_p":"10.06.2023","sala_d":"true","oras_d":"false","banned":"false"}'),
	(17, '{"step":"1","next_hour":{"date":"6\\/2\\/2023","hour":"12:30"}}'),
	(18, '{"step":"1","next_hour":{"date":"6\\/2\\/2023","hour":"12:30"},"cod_reg":"123998","hours_done":[{"date":"6\\/1\\/2023","hour":"12:00"}],"schoold":"false"}'),
	(19, '{"step":"2","next_hour":{"date":"6\\/7\\/2023","hour":"11:30"},"chestionare":[{"date":"6\\/1\\/2023","correct":"1","total_q":"20"},{"date":"6\\/1\\/2023","correct":"0","total_q":"20"},{"date":"6\\/1\\/2023","correct":"0","total_q":"20"},{"date":"6\\/1\\/2023","correct":"0","total_q":"20"},{"date":"6\\/1\\/2023","correct":"1","total_q":"20"},{"date":"6\\/1\\/2023","correct":"0","total_q":"20"},{"date":"6\\/1\\/2023","correct":"0","total_q":"20"},{"date":"6\\/3\\/2023","correct":"0","total_q":"3"},{"date":"6\\/3\\/2023","correct":"0","total_q":"2"},{"date":"6\\/3\\/2023","correct":"0","total_q":"2"},{"date":"6\\/3\\/2023","correct":"0","total_q":"4"},{"date":"6\\/3\\/2023","correct":"0","total_q":"4"},{"date":"6\\/3\\/2023","correct":"0","total_q":"4"}],"hours_done":[{"date":"6\\/2\\/2023","hour":"10:30"},{"date":"6\\/2\\/2023","hour":"12:30"}]}'),
	(20, '{"step":"1","next_hour":"","chestionare":[{"date":"6\\/1\\/2023","correct":"3","total_q":"20"}],"hours_done":[{"date":"6\\/2\\/2023","hour":"12:30"}]}'),
	(21, '{"step":"1"}'),
	(22, '{"next_hour":{"date":"6\\/2\\/2023","hour":"12:30"}}'),
	(23, '{"step":"1"}'),
	(24, '[]'),
	(25, '{"banned":"false"}');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
