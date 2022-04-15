CREATE TABLE accounts (
	'Uid' INT NOT NULL AUTO_INCREMENT
	,'Email' VARCHAR(100) NOT NULL
    ,'Username' VARCHAR(100) NOT NULL
	,'Password' VARCHAR(100) NOT NULL
    ,'FirstName' VARCHAR(60) NOT NULL
    ,'LastName' VARCHAR(60) NOT NULL
    ,'Balance' money(60) NOT NULL
	,'eftMeme' float(60) NOT NULL
    ,'eftBoomer' float(60) NOT NULL
    ,'eftTech' float(60) NOT NULL
    ,'eftCrypto' float(60) NOT NULL
    ,'eftModerate' float(60) NOT NULL
    ,'eftAggressive' float(60) NOT NULL
    ,'eftGrowth' float(60) NOT NULL
	,PRIMARY KEY ('id')
    ENGINE=NDBCLUSTER
	,
	);