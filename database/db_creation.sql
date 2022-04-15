CREATE TABLE accounts (
	Uid INT NOT NULL AUTO_INCREMENT
	,Email VARCHAR(100) NOT NULL
    ,Username VARCHAR(100) NOT NULL
	,Password VARCHAR(100) NOT NULL
    ,FirstName VARCHAR(60) NOT NULL
    ,LastName VARCHAR(60) NOT NULL
    ,Balance float NOT NULL
	,eftMeme float NOT NULL
    ,eftBoomer float NOT NULL
    ,eftTech float NOT NULL
    ,eftCrypto float NOT NULL
    ,eftModerate float NOT NULL
    ,eftAggressive float NOT NULL
    ,eftGrowth float NOT NULL
	,PRIMARY KEY (Uid)
	)ENGINE=NDBCLUSTER;