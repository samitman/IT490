CREATE TABLE `accounts` (
	`Uid` INT NOT NULL AUTO_INCREMENT
	,`Email` VARCHAR(100) NOT NULL
    ,`Username` VARCHAR(100) NOT NULL
	,`Password` VARCHAR(100) NOT NULL
    ,`FirstName` VARCHAR(60) NOT NULL
    ,`LastName` VARCHAR(60) NOT NULL
    ,`Balance` money(60)
	,'eftMeme' float(60)
    ,'eftBoomer' float(60)
    ,'eftTech' float(60)
    ,'eftCrypto' float(60)
    ,'eftModerate' float(60)
    ,'eftAggressive' float(60)
    ,'eftGrowth' float(60)
	,PRIMARY KEY (`id`)
    ENGINE=NDBCLUSTER
	,
	);