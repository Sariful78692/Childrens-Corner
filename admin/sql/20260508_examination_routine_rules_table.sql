--
-- Table structure for table `examination_rules`
--

DROP TABLE IF EXISTS `examination_rules`;
CREATE TABLE IF NOT EXISTS `examination_rules` (
  `id` int NOT NULL AUTO_INCREMENT,
  `rule_content` longtext NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
