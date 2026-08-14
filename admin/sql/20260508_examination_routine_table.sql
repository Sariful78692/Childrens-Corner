--
-- Table structure for table `examination_routine`
--

DROP TABLE IF EXISTS `examination_routine`;
CREATE TABLE IF NOT EXISTS `examination_routine` (
  `id` int NOT NULL AUTO_INCREMENT,
  `class_name` varchar(100) NOT NULL,
  `routine_title` varchar(255) NOT NULL,
  `routine_file` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
