--
-- Table structure for table `syllabus`
--

DROP TABLE IF EXISTS `syllabus`;
CREATE TABLE IF NOT EXISTS `syllabus` (
  `id` int NOT NULL AUTO_INCREMENT,
  `class_name` varchar(100) NOT NULL,
  `syllabus_title` varchar(255) NOT NULL,
  `documents` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;