CREATE TABLE `ects_courses` (
  `overview_id` varchar(32) NOT NULL DEFAULT '',
  `seminar_id` varchar(32) NOT NULL DEFAULT '',
  `semester_id` varchar(255) DEFAULT NULL,
  `ects` tinyint(4) NOT NULL DEFAULT '0',
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`overview_id`,`seminar_id`)
);

CREATE TABLE `ects_overview` (
  `overview_id` varchar(32) NOT NULL DEFAULT '',
  `user_id` varchar(32) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `autofill` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`overview_id`)
);
