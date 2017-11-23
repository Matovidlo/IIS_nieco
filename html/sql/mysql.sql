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
Login CHAR(8) NOT NULL,
Skratka_predmetu CHAR(3) NOT NULL,
Ak_rok YEAR NOT NULL
);

CREATE TABLE Osoba (
Login  CHAR(8) PRIMARY KEY,
Meno VARCHAR(80) UNIQUE,
Heslo VARCHAR(160) UNIQUE
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
Pracuje_pre_ustav VARCHAR (10) NOT NULL,
Vyucuje BIT NOT NULL ,
FOREIGN KEY (Login) REFERENCES Osoba(Login)
);

CREATE TABLE Studijny_program (
Skratka_programu VARCHAR(3),
Typ_studia VARCHAR(20),
Odbor VARCHAR(30),
Ak_rok YEAR NOT NULL,
Doba_studia INT NOT NULL,
Forma_studia VARCHAR(20),
Cislo_pravidla INT,
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
Limit_prihlasenych INT,
Skratka_programu VARCHAR(3),
Pocet_kreditov INT CHECK (Pocet_kreditov >0),
PRIMARY KEY (Ak_rok, Skratka_predmetu),
FOREIGN KEY (Ak_rok,Skratka_programu) REFERENCES Studijny_program (Ak_rok,Skratka_programu)
); 

ALTER TABLE Prihlasuje ADD CONSTRAINT fk_Predmet FOREIGN KEY (Ak_rok,Skratka_predmetu) REFERENCES Predmet(Ak_rok,Skratka_predmetu);
ALTER TABLE Prihlasuje ADD CONSTRAINT fk_Osoba FOREIGN KEY (Login) REFERENCES Osoba(Login);


INSERT INTO Pravidlo
VALUES(1,180,2,30);
INSERT INTO Pravidlo
VALUES(2,60,1,70);
INSERT INTO Pravidlo
VALUES(3,120,3,75);
INSERT INTO Pravidlo
VALUES(4,130,3,80);

INSERT INTO Studijny_program
VALUES('BIT','Bakalarsky','Informatika','2016',3,'prezenčná',1);
INSERT INTO Studijny_program
VALUES('BGR','Bakalarsky','Grafika','2017',3,'prezenčná',1);
INSERT INTO Studijny_program
VALUES('BIT','Bakalarsky','Informatika','2017',3,'externá',2);
INSERT INTO Studijny_program
VALUES('BIT','Bakalarsky','Bioinfromatika','2015',2,'prezenčná',3);
INSERT INTO Studijny_program
VALUES('MBI','Magistersky','Bioinfromatika','2015',2,'prezenčná',3);
INSERT INTO Studijny_program
VALUES('MBE','Magistersky','Bezpečnosť','2014',2,'prezenčná',4);

INSERT INTO Osoba
VALUES('xvasko14','Michal Vaško', 'LPJNul+wow4m6DsqxbninhsWHlwfp0JecwQzYpOLmCQ=');
INSERT INTO Osoba
VALUES('xvasko12','Martin Vaško', 'j0NDRmSPa5bfid2pAcUXaxCm2Dlh3TwayItZstwyeqQ=');
INSERT INTO Osoba
VALUES('xtamas01','Marek Tamaškovič', '10/w7o2juYBrGMh32/KbveULW9jk2tejpyUAD+uC6PE=');
INSERT INTO Osoba
VALUES('vesely','Vladimir Veselý' , 'DWvmmyZHF/LdM2UuISsXMQS0pke3wRrnLpiF8RzTEvs=');
INSERT INTO Osoba
VALUES('ikanich','Vlasta Kanich' , 'XohImNooBHFR0OVvjcYpJ3NgPQ1qq73WKhHvch0VQtg=');
INSERT INTO Osoba
VALUES('smrcka','Aleš Smrčka' , 'uebonUCLrSmUFw0UBTiy6xENTgMo3TpOwSgneXnTppQ=');
INSERT INTO Osoba
VALUES('vojnar','Tomas Vojnar' , 'MUjVkVfjd26+9CQIixQDtAZDMvU3abAdwC6RyXfC+T4=');
INSERT INTO Osoba
VALUES('xkiska01','Andrej Kiska' , '9/rZLVTHTLM584lEuZph3WSuF88afJInmpHK3eGAlio=');
INSERT INTO Osoba
VALUES('xronal07','Cristiano Ronaldo', '4k3SIQgDtHN6m9njFjpMqAe2MgHDvDK2j7EiylLv/zY=');
INSERT INTO Osoba
VALUES('xpotte02','Harry Potter', 'ZYcq05lmOvAaWS+XfVIfFA6Nu4OjGq0VUNB0kspJAao=');
INSERT INTO Osoba
VALUES('xmessi03','Lionel Messi', 'm82ujrwqlwo2UQOlLagw85WPTzgfz9VE98BFLuCdugA=');
INSERT INTO Osoba
VALUES('xpasty09','Jozef Pastyrik', 'UuoyYOZCjX6CDO8/+d9EoDCQwust6FEd+au6ngV7BsM=');
INSERT INTO Osoba
VALUES('admin','Admin', 'jGl25bVBBBW96Qi9Te4V37Fnqchz/Eu4qB9vKrRIqRg=');


INSERT INTO Student
VALUES('xvasko14',2,4, 'BIT','2016');
INSERT INTO Student
VALUES('xvasko12',1,2,'BGR', '2017');
INSERT INTO Student
VALUES('xtamas01',2,4, 'BIT' , '2016');
INSERT INTO Student
VALUES('xkiska01',3,4, 'BIT', '2015');
INSERT INTO Student
VALUES('xronal07',2,4, 'BIT', '2017');
INSERT INTO Student
VALUES('xpotte02',2,4, 'BGR', '2017');
INSERT INTO Student
VALUES('xmessi03',2,4, 'BIT', '2017');
INSERT INTO Student
VALUES('xpasty09',2,4, 'BGR', '2017');
INSERT INTO Student
VALUES('ikanich',2,4,'MBI', '2015');


INSERT INTO Zamestnanec
VALUES('vesely', 'UPS', 1);
INSERT INTO Zamestnanec
VALUES('ikanich', 'UPGM', 0);
INSERT INTO Zamestnanec
VALUES('smrcka', 'UVF', 1);
INSERT INTO Zamestnanec
VALUES('vojnar', 'UVF', 1);

INSERT INTO Predmet
VALUES('IDS','2017','Databázové systémy','P',592,'ZaZk','FIT',600, 'BIT',5);
INSERT INTO Predmet
VALUES('ITY','2017','Typografia','V',350,'KlZa','FIT', 600, 'BGR',4);
INSERT INTO Predmet
VALUES('IOS','2016','Operačné systémy','P',420,'ZaZk','FIT',600, 'BIT',6);
INSERT INTO Predmet
VALUES('IFJ','2016','Formálne jazyky','P',410,'ZaZk','FIT',600, 'BIT',5);
INSERT INTO Predmet
VALUES('IPP','2017','00P','P',420,'Zk','FIT',600, 'BIT',7);
INSERT INTO Predmet
VALUES('IAL','2016','Algoritmy','P',420,'ZaZk','FIT',600, 'BIT',5);


INSERT INTO Prihlasuje
VALUES('xvasko12', 'IDS', '2017');
INSERT INTO Prihlasuje
VALUES('xvasko12', 'ITY', '2017');
INSERT INTO Prihlasuje
VALUES('xvasko12', 'IOS', '2016');
INSERT INTO Prihlasuje
VALUES('xvasko14', 'IDS', '2017');
INSERT INTO Prihlasuje
VALUES('xvasko14', 'IFJ', '2016');
INSERT INTO Prihlasuje
VALUES('xtamas01', 'IDS' , '2017');
INSERT INTO Prihlasuje
VALUES('xtamas01', 'IPP' , '2017');
INSERT INTO Prihlasuje
VALUES('xkiska01', 'IDS', '2017');
INSERT INTO Prihlasuje
VALUES('xronal07', 'IDS', '2017');
INSERT INTO Prihlasuje
VALUES('xpotte02', 'IDS', '2017');
INSERT INTO Prihlasuje
VALUES('xmessi03','IDS', '2017');
INSERT INTO Prihlasuje
VALUES('xpasty09','IDS', '2017');

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
  )*/

