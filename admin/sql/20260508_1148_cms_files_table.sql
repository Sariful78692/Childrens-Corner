--
-- Table structure for table `cms_files`
--

CREATE TABLE IF NOT EXISTS `cms_files` (
  `id` int NOT NULL AUTO_INCREMENT,
  `file_type` varchar(50) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `file_type` (`file_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
