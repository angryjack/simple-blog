-- Create syntax for TABLE 'articles'
CREATE TABLE `articles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(512) DEFAULT NULL,
  `content` text,
  `description` varchar(1024) DEFAULT NULL,
  `keywords` varchar(1024) DEFAULT NULL,
  `create_date` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'routes'
CREATE TABLE `routes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(512) DEFAULT '',
  `internal_route` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'users'
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(128) DEFAULT NULL,
  `email` varchar(512) DEFAULT NULL,
  `passwd` varchar(512) DEFAULT NULL,
  `role` varchar(128) DEFAULT 'user',
  `token` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;