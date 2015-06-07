CREATE TABLE `gallery_pictures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `size` bigint(20) NOT NULL,
  `album_id` int(11) NOT NULL,
  `main_id` int(11) DEFAULT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `style` varchar(255) NOT NULL DEFAULT 'full',
  `order` int(11) NOT NULL DEFAULT 9999999,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `folder_id` (`album_id`),
  KEY `main_id` (`main_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE `gallery_albums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `default_name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `model` varchar(255) DEFAULT NULL,
  `model_id` int(11) DEFAULT NULL,
  `tags` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
