CREATE TABLE stocks (
	,Ticker VARCHAR(100) NOT NULL
    ,Price float NOT NULL DEFAULT 0
	,PRIMARY KEY (Ticker)
	)ENGINE=NDBCLUSTER;