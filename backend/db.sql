DROP TABLE IF EXISTS `images`;

--
-- Table structure for table `images`
--

CREATE TABLE IF NOT EXISTS `images` (
  `id` int NOT NULL AUTO_INCREMENT,
  `filename` varchar(60) NOT NULL,
  `presentationOrder` int(3) NOT NULL,
  `ruleName` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `validStart` date NOT NULL,
  `validEnd` date NULL,
  `approvedDate` date NULL,
  `approvedBy` varchar(30) NULL,
  `linkToOriginal` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  UNIQUE INDEX ruleTextId_unique (ruleTextId),
  PRIMARY KEY (`ruleId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;