CREATE TABLE IF NOT EXISTS `comment` (
  `commentId` bigint(20) NOT NULL AUTO_INCREMENT,
  `content` varchar(200) NOT NULL,
  `placeId` bigint(20) NOT NULL,
  `userId` bigint(20) DEFAULT NULL,
  `displayName` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`commentId`),
  KEY `userId` (`userId`),
  KEY `placeId` (`placeId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `place` (
  `placeId` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `latitude` double(10,7) NOT NULL,
  `longitude` double(10,7) NOT NULL,
  `schedules` varchar(200) NOT NULL,
  `description` varchar(200) DEFAULT NULL,
  `coffee` tinyint(4) DEFAULT NULL,
  `internetAccess` tinyint(4) DEFAULT NULL,
  `placesNumber` int(11) DEFAULT NULL,
  `comfort` int(11) DEFAULT NULL COMMENT 'note de 1 à 5',
  `frequenting` int(11) DEFAULT NULL COMMENT 'note de 1 à 5',
  `visitNumber` int(11) NOT NULL DEFAULT '0',
  `submissionDate` date NOT NULL,
  `validate` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`placeId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `user` (
  `userId` bigint(20) NOT NULL AUTO_INCREMENT,
  `mailAddress` varchar(50) NOT NULL,
  `privilege` int(11) NOT NULL DEFAULT '0' COMMENT '0, 1 or 2',
  `displayName` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`userId`),
  UNIQUE KEY `mailAddress` (`mailAddress`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

ALTER TABLE `user`
ADD CONSTRAINT CHECK (DATALENGTH(mailAddress) > 1)

ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`placeId`) REFERENCES `place` (`placeId`) ON DELETE CASCADE ON UPDATE CASCADE;