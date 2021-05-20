
CREATE TABLE `brand` (
  `Id` int(11) NOT NULL,
  `BrandName` varchar(30) NOT NULL,
  `CountryId` int(11) NOT NULL,
  `FoundationYear` int(11) NOT NULL CHECK (`FoundationYear` > 0),
  `CompanyValue` bigint(20) NOT NULL CHECK (`CompanyValue` > 0),
  `Image` longblob DEFAULT NULL,
  `Views` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `country` (
  `Id` int(11) NOT NULL,
  `Name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `drive` (
  `Id` int(11) NOT NULL,
  `Name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `model` (
  `Id` int(11) NOT NULL,
  `Name` varchar(30) NOT NULL,
  `CarBody` varchar(30) NOT NULL,
  `Year` int(11) NOT NULL CHECK (`Year` > 0),
  `BrandId` int(11) NOT NULL,
  `Price` int(11) NOT NULL,
  `Seats` int(11) NOT NULL,
  `DriveId` int(11) NOT NULL,
  `EngineType` varchar(30) NOT NULL,
  `TopSpeed` int(11) NOT NULL,
  `Acceleration` double NOT NULL,
  `Image` longblob DEFAULT NULL,
  `Views` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `userrole` (
  `Id` int(11) NOT NULL,
  `Name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `users` (
  `Id` int(11) NOT NULL,
  `Email` varchar(30) NOT NULL,
  `Username` varchar(30) NOT NULL,
  `PasswordHash` varchar(128) NOT NULL,
  `UserStatus` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `brand`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `BrandName` (`BrandName`),
  ADD KEY `Brand_fk0` (`CountryId`);
ALTER TABLE `brand` ADD FULLTEXT KEY `BrandName_2` (`BrandName`);

ALTER TABLE `country`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Name` (`Name`);

ALTER TABLE `drive`
  ADD PRIMARY KEY (`Id`);

ALTER TABLE `model`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `Model_fk0` (`BrandId`),
  ADD KEY `Model_fk1` (`DriveId`);
ALTER TABLE `model` ADD FULLTEXT KEY `Name` (`Name`);

ALTER TABLE `userrole`
  ADD PRIMARY KEY (`Id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Username_un` (`Username`),
  ADD UNIQUE KEY `Email_un` (`Email`),
  ADD KEY `Users_fk0` (`UserStatus`);

ALTER TABLE `brand`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

ALTER TABLE `country`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

ALTER TABLE `drive`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `model`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

ALTER TABLE `userrole`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `users`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

ALTER TABLE `brand`
  ADD CONSTRAINT `Brand_fk0` FOREIGN KEY (`CountryId`) REFERENCES `country` (`Id`);

ALTER TABLE `model`
  ADD CONSTRAINT `Model_fk0` FOREIGN KEY (`BrandId`) REFERENCES `brand` (`Id`),
  ADD CONSTRAINT `Model_fk1` FOREIGN KEY (`DriveId`) REFERENCES `drive` (`Id`);

ALTER TABLE `users`
  ADD CONSTRAINT `Users_fk0` FOREIGN KEY (`UserStatus`) REFERENCES `userrole` (`Id`),
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`UserStatus`) REFERENCES `userrole` (`Id`);
COMMIT;