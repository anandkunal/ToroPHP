CREATE TABLE `articles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(25) NOT NULL DEFAULT '',
  `slug` varchar(25) NOT NULL,
  `body` text NOT NULL,
  `published` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `articles` (`id`, `title`, `slug`, `body`, `published`)
VALUES
	(1,'First Post','first-post','This is the first post for ToroBlog. Hello, world?','2012-08-18 16:28:10'),
	(2,'Second Post','second-post','Just another post to test out some features.\n\nLine break and *asterisks* to show Markdown integration.','2012-08-18 16:39:03');

DROP TABLE IF EXISTS `comments`;

CREATE TABLE `comments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL DEFAULT '',
  `body` text NOT NULL,
  `posted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `comments` (`id`, `article_id`, `name`, `body`, `posted`)
VALUES
	(1,1,'Joe Shmoe','First!','2012-08-18 16:32:52');
