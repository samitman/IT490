CREATE TABLE accounts (
	Uid INT NOT NULL AUTO_INCREMENT
	,Email VARCHAR(100) NOT NULL
    ,Username VARCHAR(100) NOT NULL
	,Password VARCHAR(100) NOT NULL
    ,FirstName VARCHAR(60) NOT NULL
    ,LastName VARCHAR(60) NOT NULL
    ,Balance float NOT NULL DEFAULT 0
	,eftMeme float NOT NULL DEFAULT 0
    ,eftBoomer float NOT NULL DEFAULT 0
    ,eftTech float NOT NULL DEFAULT 0
    ,eftCrypto float NOT NULL DEFAULT 0
    ,eftModerate float NOT NULL DEFAULT 0
    ,eftAggressive float NOT NULL DEFAULT 0
    ,eftGrowth float NOT NULL DEFAULT 0
	,PRIMARY KEY (Uid)
	)ENGINE=NDBCLUSTER;