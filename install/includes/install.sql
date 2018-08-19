-- Create syntax for TABLE 'articles'
CREATE TABLE `articles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(512) DEFAULT NULL,
  `content` text,
  `link_id` varchar(512) DEFAULT NULL,
  `category` int(11) DEFAULT NULL,
  `description` varchar(1024) DEFAULT NULL,
  `keywords` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- Create syntax for TABLE 'categories'
CREATE TABLE `categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(512) DEFAULT NULL,
  `description` text,
  `link_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

-- Create syntax for TABLE 'routes'
CREATE TABLE `routes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(512) DEFAULT '',
  `internal_route` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`)
);

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
);