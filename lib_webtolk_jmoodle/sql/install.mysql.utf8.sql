CREATE TABLE IF NOT EXISTS `#__lib_jmoodle_users_sync` (`joomla_user_id` int(11) NOT NULL UNIQUE, `moodle_user_id` int(11) NOT NULL UNIQUE,) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE `#__lib_jmoodle_users_sync` ADD UNIQUE KEY `joomla_user_id` (`joomla_user_id`,`moodle_user_id`);
COMMIT;