/* IIS FIT BIT 3r 2017 */
/* Martin Vasko */
/* Marek Tamaskovic */
/* Michal Vasko */

SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `Osoba` CASCADE;
DROP TABLE IF EXISTS `Pravidlo` CASCADE;
DROP TABLE IF EXISTS `Prihlasuje` CASCADE;
DROP TABLE IF EXISTS `Student` CASCADE;
DROP TABLE IF EXISTS `Zamestnanec` CASCADE;
DROP TABLE IF EXISTS `Studijny_program` CASCADE;
DROP TABLE IF EXISTS `Predmet` CASCADE;
DROP TABLE IF EXISTS `Spravca` CASCADE;
SET FOREIGN_KEY_CHECKS=1;

/*  Student specializuje osobu, je v osobitnej tabulke kvoli konfliktom so zamestnancom.
    Zamestnanec uz nestuduje (iba ak doktorand), a takyto pripad riesi ze je v 2 tabulkach naraz.
    Kedze ER diagram sa zle zviazal (Osoba - St. program) tak sme pre studenta vytvorili nasledujuce atributy,
    aby sme ho mohli previazat so St. programom a osobu previazat iba s registraciou / vyukou predmetov */
CREATE TABLE Pravidlo (
Id_pravidla INT PRIMARY KEY,
Pocet_kreditov INT,
Max_pocet_registracii INT,
Rocny_kreditovy_strop INT UNIQUE
);

CREATE TABLE Prihlasuje (
Login CHAR(8) NOT NULL ,
Skratka_predmetu CHAR(3) NOT NULL,
Ak_rok YEAR NOT NULL,
PRIMARY KEY (Login, Skratka_predmetu, Ak_rok)
);

CREATE TABLE Osoba (
Login  CHAR(8) PRIMARY KEY,
Email VARCHAR(120) UNIQUE,
Meno VARCHAR(80) UNIQUE NOT NULL,
Heslo VARCHAR(160) NOT NULL,
Adresa VARCHAR(80),
Mesto VARCHAR(80) NOT NULL,
PSC VARCHAR(5) NOT NULL
);

CREATE TABLE Student (
Login CHAR(8) PRIMARY KEY,
Rocnik INT CHECK (Rocnik > 0 and Rocnik <= 6),
Semester INT CHECK (Semester > 0 and Semester <= 12),
Skratka_programu VARCHAR (3),
Ak_rok YEAR NOT NULL,
FOREIGN KEY (Login) REFERENCES Osoba(Login)
);

CREATE TABLE Zamestnanec (
Login CHAR (8) PRIMARY KEY,
Pracuje_pre_ustav VARCHAR (4),
Vyucuje BIT NOT NULL ,
FOREIGN KEY (Login) REFERENCES Osoba(Login)
);

CREATE TABLE Spravca (
Login CHAR (8) PRIMARY KEY,
FOREIGN KEY (Login) REFERENCES Zamestnanec(Login)
);

CREATE TABLE Studijny_program (
Skratka_programu VARCHAR(3),
Typ_studia VARCHAR(20),
Odbor VARCHAR(30),
Ak_rok YEAR NOT NULL,
Akreditacia YEAR NOT NULL,
Doba_studia INT NOT NULL,
Forma_studia VARCHAR(20),
Cislo_pravidla INT,
Popis VARCHAR(512),
FOREIGN KEY(Cislo_pravidla) REFERENCES Pravidlo(Id_pravidla)
);

ALTER TABLE Studijny_program ADD CONSTRAINT pk_Studijny_program PRIMARY KEY (Ak_rok,Skratka_programu);
ALTER TABLE Student ADD CONSTRAINT fk_Program FOREIGN KEY (Ak_rok,Skratka_programu) REFERENCES Studijny_program(Ak_rok,Skratka_programu);

CREATE TABLE Predmet (
Skratka_predmetu CHAR(3),
Ak_rok YEAR NOT NULL,
Nazov VARCHAR(40) NOT NULL,
Typ VARCHAR(3) NOT NULL,
Obsadenost INT NOT NULL,
Ukoncenie_predmetu VARCHAR(5) NOT NULL,
Fakulta VARCHAR(40) NOT NULL,
Semester VARCHAR(8) NOT NULL,
Limit_prihlasenych INT,
Skratka_programu VARCHAR(3),
Pocet_kreditov INT CHECK (Pocet_kreditov >0),
Rocnik INT CHECK (Rocnik > 0 and Rocnik <= 6),
PRIMARY KEY (Ak_rok, Skratka_predmetu),
FOREIGN KEY (Ak_rok,Skratka_programu) REFERENCES Studijny_program (Ak_rok,Skratka_programu)
);


ALTER TABLE Prihlasuje ADD CONSTRAINT fk_Predmet FOREIGN KEY (Ak_rok,Skratka_predmetu) REFERENCES Predmet(Ak_rok,Skratka_predmetu);
ALTER TABLE Prihlasuje ADD CONSTRAINT fk_Student FOREIGN KEY (Login) REFERENCES Student(Login);



INSERT INTO Pravidlo VALUES(1,180,2,30);
INSERT INTO Pravidlo VALUES(2,60,1,70);
INSERT INTO Pravidlo VALUES(3,120,3,75);
INSERT INTO Pravidlo VALUES(4,130,3,80);

INSERT INTO Studijny_program VALUES('BIT','Bakalarsky','Informatika','2016', '2020', 3,'prezenčná',1, 'Informatika je super');
INSERT INTO Studijny_program VALUES('BGR','Bakalarsky','Grafika','2016', '2021', 3,'prezenčná',1, 'Grafika matika');
INSERT INTO Studijny_program VALUES('BIT','Bakalarsky','Informatika','2017', '2021', 3,'externá',2, 'Blablacar');
INSERT INTO Studijny_program VALUES('BIT','Bakalarsky','Bioinfromatika','2015', '2020', 2,'prezenčná',3, 'Slimaky');
INSERT INTO Studijny_program VALUES('MBI','Magistersky','Bioinfromatika','2015', '2021', 2,'prezenčná',3, 'Geneticke algoritmy');
INSERT INTO Studijny_program VALUES('MBE','Magistersky','Bezpečnosť','2014', '2020', 2,'prezenčná',4, 'Aladin');
INSERT INTO Studijny_program VALUES('MAA','Magistersky','Lorem','2013', '2019', 2,'prezenčná',4, 'Aladin');
INSERT INTO Studijny_program VALUES('MAB','Magistersky','Ipsum','2014', '2018', 2,'prezenčná',4, 'Aladin');
INSERT INTO Studijny_program VALUES('MAC','Magistersky','Dolor','2017', '2022', 2,'prezenčná',4, 'Aladin');
INSERT INTO Studijny_program VALUES('MAD','Magistersky','Sit amet','2014', '2020', 2,'prezenčná',4, 'Aladin');


INSERT INTO Osoba VALUES('xvasko14', 'xvasko14@stud.fit.vutbr.cz', 'Michal Vaško', 'LPJNul+wow4m6DsqxbninhsWHlwfp0JecwQzYpOLmCQ=', '', 'Presov', '07552');
INSERT INTO Osoba VALUES('xvasko12', 'xvasko12@stud.fit.vutbr.cz', 'Martin Vaško', 'j0NDRmSPa5bfid2pAcUXaxCm2Dlh3TwayItZstwyeqQ=', '', 'Parchovany', '07662');
INSERT INTO Osoba VALUES('xtamas01', 'xtamas01@stud.fit.vutbr.cz', 'Marek Tamaškovič', '10/w7o2juYBrGMh32/KbveULW9jk2tejpyUAD+uC6PE=', '', 'Sered', '05764');
INSERT INTO Osoba VALUES('vesely', 'vesely@stud.fit.vutbr.cz', 'Vladimir Veselý' , 'DWvmmyZHF/LdM2UuISsXMQS0pke3wRrnLpiF8RzTEvs=', '', 'Brno', '60200');
INSERT INTO Osoba VALUES('ikanich', 'ikanich@stud.fit.vutbr.cz', 'Vlasta Kanich' , 'XohImNooBHFR0OVvjcYpJ3NgPQ1qq73WKhHvch0VQtg=', '', 'Brno', '60200');
INSERT INTO Osoba VALUES('smrcka', 'smrcka@stud.fit.vutbr.cz', 'Aleš Smrčka' , 'uebonUCLrSmUFw0UBTiy6xENTgMo3TpOwSgneXnTppQ=', '', 'Brno', '60200');
INSERT INTO Osoba VALUES('vojnar', 'vojnar@stud.fit.vutbr.cz', 'Tomas Vojnar' , 'MUjVkVfjd26+9CQIixQDtAZDMvU3abAdwC6RyXfC+T4=', '', 'Brno', '60200');
INSERT INTO Osoba VALUES('xpotte02', 'xpotte02@stud.fit.vutbr.cz', 'Harry Potter', 'ZYcq05lmOvAaWS+XfVIfFA6Nu4OjGq0VUNB0kspJAao=', '', 'Rockfort', '01234');
INSERT INTO Osoba VALUES('xmessi03', 'xmessi03@stud.fit.vutbr.cz', 'Lionel Messi', 'm82ujrwqlwo2UQOlLagw85WPTzgfz9VE98BFLuCdugA=', '', 'Barcelona', '98765');
INSERT INTO Osoba VALUES('xpasty09', 'xpasty09@stud.fit.vutbr.cz', 'Jozef Pastyrik', 'UuoyYOZCjX6CDO8/+d9EoDCQwust6FEd+au6ngV7BsM=', '', 'Brno', '60200');
INSERT INTO Osoba VALUES('admin', 'admin@stud.fit.vutbr.cz', 'Morek Tamaškovič', 'jGl25bVBBBW96Qi9Te4V37Fnqchz/Eu4qB9vKrRIqRg=', '', 'Brno', '60200');

INSERT INTO Osoba VALUES('xabale00', 'xabale00@stud.fit.vutbr.cz', 'Andrej Abaled', 'LPJNul+wow4m6DsqxbninhsWHlwfp0JecwQzYpOLmCQ=', '', 'Brno', '60200');
INSERT INTO Osoba VALUES('xdante01', 'xdante01@stud.fit.vutbr.cz', 'Dante Dante', 'LPJNul+wow4m6DsqxbninhsWHlwfp0JecwQzYpOLmCQ=', '', 'Brno', '60200');
INSERT INTO Osoba VALUES('xhokan31', 'xhokan31@stud.fit.vutbr.cz', 'Ji Hokan', 'LPJNul+wow4m6DsqxbninhsWHlwfp0JecwQzYpOLmCQ=', '', 'Brno', '60200');
INSERT INTO Osoba VALUES('xblaho24', 'xblaho24@stud.fit.vutbr.cz', 'Rudolf Blaho', 'LPJNul+wow4m6DsqxbninhsWHlwfp0JecwQzYpOLmCQ=', '', 'Brno', '60200');
INSERT INTO Osoba VALUES('xorman00', 'xorman00@stud.fit.vutbr.cz', 'Adam Ormanovsky', 'LPJNul+wow4m6DsqxbninhsWHlwfp0JecwQzYpOLmCQ=', '', 'Brno', '60200');
INSERT INTO Osoba VALUES('xorsza00', 'xorsza00@stud.fit.vutbr.cz', 'Pavol Orszagh', 'LPJNul+wow4m6DsqxbninhsWHlwfp0JecwQzYpOLmCQ=', '', 'Brno', '60200');
INSERT INTO Osoba VALUES('xormos12', 'xormos12@stud.fit.vutbr.cz', 'Matej Ormosovsky', 'LPJNul+wow4m6DsqxbninhsWHlwfp0JecwQzYpOLmCQ=', '', 'Brno', '60200');
INSERT INTO Osoba VALUES('xchlan02', 'xchlan02@stud.fit.vutbr.cz', 'Sypac Chlanofuz', 'LPJNul+wow4m6DsqxbninhsWHlwfp0JecwQzYpOLmCQ=', '', 'Brno', '60200');
INSERT INTO Osoba VALUES('xricht00', 'xricht00@stud.fit.vutbr.cz', 'Master LPR', 'LPJNul+wow4m6DsqxbninhsWHlwfp0JecwQzYpOLmCQ=', '', 'Brno', '60200');
INSERT INTO Osoba VALUES('xricht01', 'xricht01@stud.fit.vutbr.cz', 'Slave LPR', 'LPJNul+wow4m6DsqxbninhsWHlwfp0JecwQzYpOLmCQ=', '', 'Brno', '60200');
INSERT INTO Osoba VALUES('xblade32', 'xblade32@stud.fit.vutbr.cz', 'Runner Blade', 'LPJNul+wow4m6DsqxbninhsWHlwfp0JecwQzYpOLmCQ=', '', 'Brno', '60200');
INSERT INTO Osoba VALUES('xblaze03', 'xblaze03@stud.fit.vutbr.cz', 'Anton Blazek', 'LPJNul+wow4m6DsqxbninhsWHlwfp0JecwQzYpOLmCQ=', '', 'Brno', '60200');
INSERT INTO Osoba VALUES('xpotte12', 'xpotte12@stud.fit.vutbr.cz', 'Hary Pottter', 'LPJNul+wow4m6DsqxbninhsWHlwfp0JecwQzYpOLmCQ=', '', 'Brno', '60200');
INSERT INTO Osoba VALUES('xpotte13', 'xpotte13@stud.fit.vutbr.cz', 'Harrry Potttter', 'LPJNul+wow4m6DsqxbninhsWHlwfp0JecwQzYpOLmCQ=', '', 'Brno', '60200');
INSERT INTO Osoba VALUES('xpotte14', 'xpotte14@stud.fit.vutbr.cz', 'Yeah Boyyyy', 'LPJNul+wow4m6DsqxbninhsWHlwfp0JecwQzYpOLmCQ=', '', 'Brno', '60200');

INSERT INTO Student VALUES('xvasko14',2,4, 'BIT','2016');
INSERT INTO Student VALUES('xvasko12',2,3,'BGR', '2016');
INSERT INTO Student VALUES('xtamas01',2,4, 'BIT' , '2016');
INSERT INTO Student VALUES('xpotte02',2,4, 'BGR', '2016');
INSERT INTO Student VALUES('xmessi03',2,4, 'BIT', '2017');
INSERT INTO Student VALUES('xpasty09',2,4, 'BGR', '2016');
INSERT INTO Student VALUES('ikanich',2,4,'MBI', '2015');
INSERT INTO Student VALUES('xabale00',2,4,'MBI', '2015');
INSERT INTO Student VALUES('xdante01',2,4,'BGR', '2014');
INSERT INTO Student VALUES('xhokan31',2,4,'MBI', '2015');
INSERT INTO Student VALUES('xblaho24',2,4,'BIT', '2015');
INSERT INTO Student VALUES('xorman00',2,4,'BIT', '2015');
INSERT INTO Student VALUES('xorsza00',2,4,'BIT', '2016');
INSERT INTO Student VALUES('xormos12',2,4,'BIT', '2015');
INSERT INTO Student VALUES('xchlan02',2,4,'MBI', '2013');
INSERT INTO Student VALUES('xricht00',2,4,'MBI', '2011');
INSERT INTO Student VALUES('xricht01',2,4,'MAA', '2015');
INSERT INTO Student VALUES('xblade32',2,4,'MAA', '2018');
INSERT INTO Student VALUES('xblaze03',2,4,'MAD', '2015');
INSERT INTO Student VALUES('xpotte12',2,4,'MAA', '2010');
INSERT INTO Student VALUES('xpotte13',2,4,'MBI', '2015');
INSERT INTO Student VALUES('xpotte14',2,4,'MAB', '2017');


INSERT INTO Zamestnanec VALUES('vesely', 'UPS', 1);
INSERT INTO Zamestnanec VALUES('ikanich', 'UPGM', 0);
INSERT INTO Zamestnanec VALUES('smrcka', 'UVF', 1);
INSERT INTO Zamestnanec VALUES('vojnar', 'UPS', 1);
INSERT INTO Zamestnanec VALUES('peringer', 'UVF', 1);
INSERT INTO Zamestnanec VALUES('hruby', 'UPGM', 1);
INSERT INTO Zamestnanec VALUES('inovotny', 'UPS', 1);
INSERT INTO Zamestnanec VALUES('ibrunej', 'UVF', 1);
INSERT INTO Zamestnanec VALUES('admin', 'UIFS', 0);

INSERT INTO Spravca VALUES('admin');

INSERT INTO Predmet VALUES ('IDS', '2017', 'Databázové systémy','P',592,'ZaZk','FIT', 'Letny', 600, 'BIT',5, 2);
INSERT INTO Predmet VALUES ('ITY', '2016', 'Typografia','V',350,'KlZa','FIT', 'Letny', 600, 'BGR',4, 2);
INSERT INTO Predmet VALUES ('IOS', '2016', 'Operačné systémy','P',420,'ZaZk','FIT', 'Letny', 600, 'BIT',6, 1);
INSERT INTO Predmet VALUES ('IFJ', '2016', 'Formálne jazyky','P',410,'ZaZk','FIT', 'Zimny', 600, 'BIT',5, 2);
INSERT INTO Predmet VALUES ('IPP', '2017', '00P','P',420,'Zk','FIT', 'Letny', 600, 'BIT',7, 2);
INSERT INTO Predmet VALUES ('IAL', '2017', 'Algoritmy','P',420,'ZaZk','FIT', 'Zimny', 600, 'BIT',5, 2);
INSERT INTO Predmet VALUES ('AGS', '2017', 'Agentní a multiagentní systémy', 'P', '420', 'Zk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IAL', '2017', 'Algoritmy', 'P', '420', 'ZáZk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('AIS', '2017', 'Analýza a návrh informačních systémů', 'P', '420', 'ZáZk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IAN', '2017', 'Analýza binárního kódu', 'P', '420', 'Klz', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('JA3', '2017', 'Angličtina: konverzace', 'P', '420', 'ZáZk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('JA3', '2017', 'Angličtina: konverzace', 'P', '420', 'ZáZk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('AEU', '2017', 'Angličtina pro Evropu', 'P', '420', 'ZáZk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('AEU', '2017', 'Angličtina pro Evropu', 'P', '420', 'ZáZk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('AIT', '2017', 'Angličtina pro IT', 'P', '420', 'ZáZk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('AIT', '2017', 'Angličtina pro IT', 'P', '420', 'ZáZk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('FCE', '2017', 'Angličtina: příprava na zkoušku FCE', 'P', '420', 'Zá', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('PDD', '2017', 'Aplikace paralelních počítačů', 'P', '420', 'Zk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('EVO', '2017', 'Aplikované evoluční algoritmy', 'P', '420', 'Zk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('ARC', '2017', 'Architektura a programování paralelních systémů', 'P', '420', 'ZáZk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('ACH', '2017', 'Architektura procesorů', 'P', '420', 'ZáZk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('BMS', '2017', 'Bezdrátové a mobilní sítě', 'P', '420', 'ZáZk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IBS', '2017', 'Bezpečnost a počítačové sítě', 'P', '420', 'ZáZk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('BIS', '2017', 'Bezpečnost informačních systémů', 'P', '420', 'ZáZk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('BID', '2017', 'Bezpečnost informačních systémů a kryptografie', 'P', '420', 'Zk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('BIF', '2017', 'Bioinformatika', 'P', '420', 'Zk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('BIN', '2017', 'Biologií inspirované počítače', 'P', '420', 'Zk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IDS', '2017', 'Databázové systémy', 'P', '420', 'ZáZk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IW1', '2017', 'Desktop systémy Microsoft Windows', 'P', '420', 'ZáZk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IDA', '2017', 'Diskrétní matematika', 'P', '420', 'Zk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('DJA', '2017', 'Dynamické jazyky', 'P', '420', 'Zk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('EIP', '2017', 'Ekonomie informačních produktů', 'P', '420', 'Zk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IEL', '2017', 'Elektronika pro informační technologie', 'P', '420', 'ZáZk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('EUD', '2017', 'Evoluční a nekonvenční hardware', 'P', '420', 'Zk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('EVD', '2017', 'Evoluční výpočetní techniky', 'P', '420', 'Zk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('FAV', '2017', 'Formální analýza a verifikace', 'P', '420', 'ZáZk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('FAD', '2017', 'Formální analýza programů', 'P', '420', 'Zk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IFJ', '2017', 'Formální jazyky a překladače', 'P', '420', 'ZáZk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('FLP', '2017', 'Funkcionální a logické programování', 'P', '420', 'ZáZk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('FVS', '2017', 'Funkční verifikace číslicových systémů', 'P', '420', 'Zk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('FYO', '2017', 'Fyzikální optika', 'P', '420', 'Zk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IFS', '2017', 'Fyzikální seminář', 'P', '420', 'Zá', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('GIS', '2017', 'Geografické informační systémy', 'P', '420', 'ZáZk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('GZN', '2017', 'Grafická a zvuková rozhraní a normy', 'P', '420', 'Zk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('GJA', '2017', 'Grafická uživatelská rozhraní v Javě', 'P', '420', 'ZáZk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('GUX', '2017', 'Grafická uživatelská rozhraní v X Window', 'P', '420', 'Zk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('GMU', '2017', 'Grafické a multimediální procesory', 'P', '420', 'ZáZk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('GAL', '2017', 'Grafové algoritmy', 'P', '420', 'Zk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('HSC', '2017', 'Hardware/Software Codesign', 'P', '420', 'ZáZk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IIS', '2017', 'Informační systémy', 'P', '420', 'ZáZk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IVG', '2017', 'Informační výchova a gramotnost', 'P', '420', 'Zá', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('ISD', '2017', 'Inteligentní systémy', 'P', '420', 'Zk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('SIN', '2017', 'Inteligentní systémy', 'P', '420', 'Zk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('WAP', '2017', 'Internetové aplikace', 'P', '420', 'ZáZk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IJC', '2017', 'Jazyk C', 'P', '420', 'Zk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IKR', '2017', 'Klasifikace a rozpoznávání', 'P', '420', 'Zk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('KRD', '2017', 'Klasifikace a rozpoznávání', 'P', '420', 'Zk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('KKO', '2017', 'Kódování a komprese dat', 'P', '420', 'ZáZk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('HKO', '2017', 'Komunikační dovednosti', 'P', '420', 'Zá', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('HKO', '2017', 'Komunikační dovednosti', 'P', '420', 'Zá', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('HKA', '2017', 'Konflikty a asertivita', 'P', '420', 'Zá', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('HKA', '2017', 'Konflikty a asertivita', 'P', '420', 'Zá', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('KRY', '2017', 'Kryptografie', 'P', '420', 'ZáZk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('LOG', '2017', 'Logika', 'P', '420', 'ZáZk', 'FIT', 'Letny', 600, 'BIT', 5, 2); FSI
INSERT INTO Predmet VALUES ('MPR', '2017', 'Management projektů', 'P', '420', 'ZáZk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('MEK', '2017', 'Manažerská ekonomika', 'P', '420', 'ZáZk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('MAR', '2017', 'Marketing', 'P', '420', 'ZáZk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IMA', '2017', 'Matematická analýza', 'P', '420', 'Zk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('MLD', '2017', 'Matematická logika', 'P', '420', 'Zk', 'FIT', 'Letny', 600, 'BIT', 5, 2); FSI
INSERT INTO Predmet VALUES ('MAT', '2017', 'Matematické struktury v informatice', 'P', '420', 'Zk', 'FIT', 'Zimny', 600, 'BIT', 5, 2); FSI
INSERT INTO Predmet VALUES ('IMF', '2017', 'Matematické základy fuzzy logiky', 'P', '420', 'Klz', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('ISM', '2017', 'Matematický seminář', 'P', '420', 'Zá', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IMK', '2017', 'Mechanika a akustika', 'P', '420', 'Klz', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IMP', '2017', 'Mikroprocesorové a vestavěné systémy', 'P', '420', 'ZáZk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IMS', '2017', 'Modelování a simulace', 'P', '420', 'ZáZk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('MSD', '2017', 'Modelování a simulace', 'P', '420', 'Zk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('MOB', '2017', 'Modelování biologických systémů', 'P', '420', 'ZáZk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('MID', '2017', 'Moderní matematické metody v informatice', 'P', '420', 'Zk', 'FIT', 'Letny', 600, 'BIT', 5, 2); FSI
INSERT INTO Predmet VALUES ('MMD', '2017', 'Moderní metody zobrazování 3D scény', 'P', '420', 'Zk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('MZD', '2017', 'Moderní metody zpracování řeči', 'P', '420', 'Zk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('TID', '2017', 'Moderní teoretická informatika', 'P', '420', 'Zk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('MUL', '2017', 'Multimédia', 'P', '420', 'Zk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IMU', '2017', 'Multimédia v počítačových sítích', 'P', '420', 'Klz', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('INI', '2017', 'Návrh a implementace IT služeb', 'P', '420', 'Klz', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('INC', '2017', 'Návrh číslicových systémů', 'P', '420', 'ZáZk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('NAV', '2017', 'Návrh externích adaptérů a vestavěných systémů', 'P', '420', 'Zk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('INP', '2017', 'Návrh počítačových systémů', 'P', '420', 'ZáZk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('NSB', '2017', 'Návrh, správa a bezpečnost', 'P', '420', 'ZáZk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('QB4', '2017', 'Neuronové sítě, adaptivní a optimální filtrace', 'P', '420', 'Zk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('INM', '2017', 'Numerická matematika a pravděpodobnost', 'P', '420', 'ZáZk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('CPB', '2017', 'Obchodování cenných papírů a komodit', 'P', '420', 'Klz', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IOS', '2017', 'Operační systémy', 'P', '420', 'ZáZk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('OPD', '2017', 'Optika', 'P', '420', 'Zk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('OPM', '2017', 'Optimalizace', 'P', '420', 'ZáZk', 'FIT', 'Letny', 600, 'BIT', 5, 2); FSI
INSERT INTO Predmet VALUES ('PRL', '2017', 'Paralelní a distribuované algoritmy', 'P', '420', 'ZáZk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IPZ', '2017', 'Periferní zařízení', 'P', '420', 'Zk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('PES', '2017', 'Petriho sítě', 'P', '420', 'Zk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('PGD', '2017', 'Počítačová grafika', 'P', '420', 'Zk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('PGR', '2017', 'Počítačová grafika', 'P', '420', 'Zk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IPK', '2017', 'Počítačové komunikace a sítě', 'P', '420', 'ZáZk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('ISC', '2017', 'Počítačový seminář', 'P', '420', 'Zá', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IW4', '2017', 'Podnikové technologie Microsoft', 'P', '420', 'Zk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('PBI', '2017', 'Pokročilá bioinformatika', 'P', '420', 'Zk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IAM', '2017', 'Pokročilá matematika', 'P', '420', 'Klz', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('ILI', '2017', 'Pokročilá témata administrace operačního systému Linux', 'P', '420', 'Klz', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IPA', '2017', 'Pokročilé asemblery', 'P', '420', 'Zk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('PCS', '2017', 'Pokročilé číslicové systémy', 'P', '420', 'Zk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('PDB', '2017', 'Pokročilé databázové systémy', 'P', '420', 'ZáZk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('PIS', '2017', 'Pokročilé informační systémy', 'P', '420', 'ZáZk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('PKS', '2017', 'Pokročilé komunikační systémy', 'P', '420', 'ZáZk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('POS', '2017', 'Pokročilé operační systémy', 'P', '420', 'Zk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('PND', '2017', 'Pokročilé techniky návrhu číslicových systémů', 'P', '420', 'Zk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('CCS', '2017', 'Pokročilý návrh a zabezpečení podnikových sítí', 'P', '420', 'Klz', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IVS', '2017', 'Praktické aspekty vývoje software', 'P', '420', 'Klz', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('HPR', '2017', 'Prezentační dovednosti', 'P', '420', 'Zá', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('HPR', '2017', 'Prezentační dovednosti', 'P', '420', 'Zá', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IPP', '2017', 'Principy programovacích jazyků a OOP', 'P', '420', 'ZáZk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('PTD', '2017', 'Principy syntézy testovatelných obvodů', 'P', '420', 'Zk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IPS', '2017', 'Programovací seminář', 'P', '420', 'Zá', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('ISU', '2017', 'Programování na strojové úrovni', 'P', '420', 'ZáZk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IW5', '2017', 'Programování v .NET a C#', 'P', '420', 'Klz', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IZA', '2017', 'Programování zařízení Apple', 'P', '420', 'Zk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IP1', '2017', 'Projektová praxe 1', 'P', '420', 'Klz', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IP2', '2017', 'Projektová praxe 2', 'P', '420', 'Klz', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IP3', '2017', 'Projektová praxe 3', 'P', '420', 'Klz', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('PMA', '2017', 'Projektový manažer', 'P', '420', 'Klz', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('PDI', '2017', 'Prostředí distribuovaných aplikací', 'P', '420', 'Zk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('PDS', '2017', 'Přenos dat, počítačové sítě a protokoly', 'P', '420', 'Zk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('HPO', '2017', 'Psychologie osobnosti', 'P', '420', 'Zá', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('HPO', '2017', 'Psychologie osobnosti', 'P', '420', 'Zá', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('ICP', '2017', 'Seminář C++', 'P', '420', 'Zá', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IJA', '2017', 'Seminář Java', 'P', '420', 'Zá', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('SMT', '2017', 'Seminář matematických struktur', 'P', '420', 'Zá', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('STI', '2017', 'Seminář teoretické informatiky', 'P', '420', 'Zá', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IVH', '2017', 'Seminář VHDL', 'P', '420', 'Zá', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IW2', '2017', 'Serverové systémy Microsoft Windows', 'P', '420', 'ZáZk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('ISS', '2017', 'Signály a systémy', 'P', '420', 'Zk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('SNT', '2017', 'Simulační nástroje a techniky', 'P', '420', 'ZáZk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('ISA', '2017', 'Síťové aplikace a správa sítí', 'P', '420', 'ZáZk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IW3', '2017', 'Síťové technologie Microsoft Windows', 'P', '420', 'Zk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('ISJ', '2017', 'Skriptovací jazyky', 'P', '420', 'Zk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('SFC', '2017', 'Soft Computing', 'P', '420', 'ZáZk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('SVD', '2017', 'Specifikace vestavěných systémů', 'P', '420', 'Zk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IIZ', '2017', 'Správa serverů IBM zSeries', 'P', '420', 'Klz', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('SSP', '2017', 'Stochastické procesy', 'P', '420', 'ZáZk', 'FIT', 'Letny', 600, 'BIT', 5, 2); FSI
INSERT INTO Predmet VALUES ('SRI', '2017', 'Strategické řízení informačních systémů', 'P', '420', 'Zk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('STM', '2017', 'Strategický management', 'P', '420', 'ZáZk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('SYS', '2017', 'Systémová biologie', 'P', '420', 'ZáZk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('SOD', '2017', 'Systémy odolné proti poruchám', 'P', '420', 'Zk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('SPP', '2017', 'Systémy odolné proti poruchám', 'P', '420', 'ZáZk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('ITP', '2017', 'Technika personálních počítačů', 'P', '420', 'Zk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('TIN', '2017', 'Teoretická informatika', 'P', '420', 'ZáZk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('TAD', '2017', 'Teorie a aplikace Petriho sítí', 'P', '420', 'Zk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('THE', '2017', 'Teorie her', 'P', '420', 'ZáZk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('TKD', '2017', 'Teorie kategorií v informatice', 'P', '420', 'Zk', 'FIT', 'Zimny', 600, 'BIT', 5, 2); FSI
INSERT INTO Predmet VALUES ('TJD', '2017', 'Teorie programovacích jazyků', 'P', '420', 'Zk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('ITS', '2017', 'Testování a dynamická analýza', 'P', '420', 'ZáZk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('ITU', '2017', 'Tvorba uživatelských rozhraní', 'P', '420', 'Klz', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('ITW', '2017', 'Tvorba webových stránek', 'P', '420', 'Klz', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('ITY', '2017', 'Typografie a publikování', 'P', '420', 'Klz', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IUS', '2017', 'Úvod do softwarového inženýrství', 'P', '420', 'ZáZk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('HVR', '2017', 'Vedení a řízení lidí', 'P', '420', 'Zá', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('HVR', '2017', 'Vedení a řízení lidí', 'P', '420', 'Zá', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('APD', '2017', 'Vybraná témata z analýzy a překladu jazyků', 'P', '420', 'Zk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('VKD', '2017', 'Vybrané kapitoly z algoritmů', 'P', '420', 'Zk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('VPD', '2017', 'Vybrané problémy informačních systémů', 'P', '420', 'Zk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('SID', '2017', 'Vybrané problémy softwarového inženýrství a databázových systémů', 'P', '420', 'Zk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('ZZD', '2017', 'Vybrané problémy získávání znalostí z databází', 'P', '420', 'Zk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('VYF', '2017', 'Výpočetní fotografie', 'P', '420', 'Klz', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('VGE', '2017', 'Výpočetní geometrie', 'P', '420', 'Zk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('VND', '2017', 'Vysoce náročné výpočty', 'P', '420', 'Zk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('VNV', '2017', 'Vysoce náročné výpočty', 'P', '420', 'Zk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('MZS', '2017', 'Vyšší metody zpracování signálů', 'P', '420', 'ZáZk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('VIN', '2017', 'Výtvarná informatika', 'P', '420', 'Klz', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IZG', '2017', 'Základy počítačové grafiky', 'P', '420', 'ZáZk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IZP', '2017', 'Základy programování', 'P', '420', 'ZáZk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('IZU', '2017', 'Základy umělé inteligence', 'P', '420', 'ZáZk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('ZZN', '2017', 'Získávání znalostí z databází', 'P', '420', 'ZáZk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('ZPO', '2017', 'Zpracování obrazu', 'P', '420', 'Zk', 'FIT', 'Letny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('ZPD', '2017', 'Zpracování přirozeného jazyka', 'P', '420', 'Zk', 'FIT', 'Zimny', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('ASD', '2017', 'Zpracování řeči a audia člověkem a počítačem', 'P', '420', 'Zk', 'FIT', 'Z', 600, 'BIT', 5, 2);
INSERT INTO Predmet VALUES ('ZRE', '2017', 'Zpracování řečových signálů', 'P', '420', 'Zk', 'FIT', 'Letny', 600, 'BIT', 5, 2);


INSERT INTO Prihlasuje VALUES('xvasko12', 'IDS', '2017');
INSERT INTO Prihlasuje VALUES('xvasko12', 'ITY', '2016');
INSERT INTO Prihlasuje VALUES('xvasko12', 'IOS', '2016');
INSERT INTO Prihlasuje VALUES('xvasko14', 'IDS', '2017');
INSERT INTO Prihlasuje VALUES('xvasko14', 'IFJ', '2016');
INSERT INTO Prihlasuje VALUES('xtamas01', 'IDS', '2017');
INSERT INTO Prihlasuje VALUES('xtamas01', 'IPP', '2017');
INSERT INTO Prihlasuje VALUES('xpotte02', 'IDS', '2017');
INSERT INTO Prihlasuje VALUES('xmessi03', 'IDS', '2017');
INSERT INTO Prihlasuje VALUES('xpasty09', 'IDS', '2017');



SELECT
Student.Login, Student.Ak_rok
FROM
Student NATURAL JOIN Studijny_program;


/*SELECT
Prihlasuje.Login, Predmet.Ak_rok, Predmet.Skratka_predmetu
FROM
Predmet, Prihlasuje
WHERE
Prihlasuje.Skratka_predmetu = Predmet.Skratka_predmetu
ORDER BY
Prihlasuje.Login;

SELECT
Student.Login, Student.Ak_rok
FROM
Student,Studijny_program
WHERE
Studijny_program.Ak_rok = Student.Ak_rok
AND
Studijny_program.Skratka_programu = Student.Skratka_programu
ORDER BY
Studijny_program.Ak_rok;

SELECT Student.Login, Studijny_program.Odbor, Pravidlo.pocet_kreditov
FROM
Student,Studijny_program,Pravidlo
WHERE
Studijny_program.Cislo_pravidla= Pravidlo.id_pravidla
AND
Student.Ak_rok=Studijny_program.Ak_rok
AND
Student.Skratka_programu = Studijny_program.Skratka_programu
ORDER BY
Student.Login;

SELECT
  COUNT (Predmet.nazov), Predmet.Typ
FROM
  Predmet
GROUP BY
  Predmet.Typ;


SELECT
Prihlasuje.Login, SUM(Predmet.Pocet_kreditov)
FROM
Prihlasuje,Predmet,Student
WHERE
Prihlasuje.Skratka_predmetu=Predmet.Skratka_predmetu
AND
Prihlasuje.Ak_rok=Predmet.Ak_rok
AND
Student.Login = Prihlasuje.Login
GROUP BY
Prihlasuje.Login;


SELECT
Student.Login,Student.Skratka_programu
FROM
Student
WHERE EXISTS (
 SELECT *
 FROM Predmet
 WHERE Student.Skratka_programu=Predmet.Skratka_programu
);

SELECT
  Predmet.Skratka_predmetu, Predmet.Ak_rok, Predmet.Nazov, Predmet.Ukoncenie_predmetu
FROM
  Predmet
WHERE
  Predmet.Ukoncenie_predmetu IN (
    SELECT
      Predmet.Ukoncenie_predmetu
    FROM
      Predmet
    WHERE
      Predmet.Ukoncenie_predmetu = 'ZaZk'




      Rocnik INT CHECK (Rocnik > 0 and Rocnik <= 6),
Semester INT CHECK (Semester > 0 and Semester <= 12),




  )*/

