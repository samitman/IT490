CREATE TABLE accounts (
	Uid INT NOT NULL AUTO_INCREMENT
	,Email VARCHAR(100) NOT NULL
    ,Username VARCHAR(100) NOT NULL
	,Password VARCHAR(100) NOT NULL
    ,FirstName VARCHAR(60) NOT NULL
    ,LastName VARCHAR(60) NOT NULL
    ,Balance float NOT NULL DEFAULT 0
	,etfMeme float NOT NULL DEFAULT 0
    ,etfBoomer float NOT NULL DEFAULT 0
    ,etfTech float NOT NULL DEFAULT 0
    ,etfCrypto float NOT NULL DEFAULT 0
    ,etfModerate float NOT NULL DEFAULT 0
    ,etfAggressive float NOT NULL DEFAULT 0
    ,etfGrowth float NOT NULL DEFAULT 0
	,PRIMARY KEY (Uid)
	)ENGINE=NDBCLUSTER;