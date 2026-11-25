
CREATE TABLE `website_files` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`type` enum('pdf','zip','exe','sh') NOT NULL,
	`filename` varchar(30) NOT NULL,
	`fileblob` longblob NOT NULL,
	`description` varchar(255) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_uca1400_ai_ci;

CREATE TABLE `staff` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(40) NOT NULL,
	`phone` varchar(8) NOT NULL DEFAULT '',
	`email` varchar(80) NOT NULL,
	`department_id` int(10) unsigned NOT NULL,
	PRIMARY KEY (`id`),
	KEY `department_id` (`department_id`),
	FOREIGN KEY (`department_id`) REFERENCES `department` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_uca1400_ai_ci;

CREATE TABLE `department` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(40) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_uca1400_ai_ci;
