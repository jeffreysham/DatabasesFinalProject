DROP TABLE Person;
CREATE TABLE Person (
	Email VARCHAR(100) NOT NULL,
	Name VARCHAR(100) NOT NULL,
	Sex VARCHAR(1) NOT NULL,
	DOB DATE,
	Role VARCHAR(20) NOT NULL,
	NominatedFor VARCHAR(10),
	InFilm VARCHAR(10),
	BirthPlace VARCHAR(10),
	HasVoted VARCHAR(3),
	Password VARCHAR(100)
);

DROP TABLE Film;
CREATE TABLE Film (
	ID VARCHAR(10) NOT NULL,
	Name VARCHAR(100) NOT NULL,
	Year INTEGER,
	Rating FLOAT,
	Genre VARCHAR(20),
	Country VARCHAR(10),
	Language VARCHAR(20),
	Budget INTEGER,
	Gross INTEGER,
	NominatedFor VARCHAR(10)
);

DROP TABLE Award;
CREATE TABLE Award (
	ID VARCHAR(10) NOT NULL,
	Agency VARCHAR(10) NOT NULL,
	Name VARCHAR(50) NOT NULL,
	Year INTEGER,
	Win VARCHAR(3),
	Votes INTEGER
);

DROP TABLE Place;
CREATE TABLE Place (
	ID VARCHAR(10) NOT NULL,
	City VARCHAR(30),
	State VARCHAR(30),
	Country VARCHAR(10)
);

/* Didn't Use this
DROP TABLE ProductionCompany;
CREATE TABLE ProductionCompany (
	Name VARCHAR(50),
	Funds VARCHAR(10)
);*/

delimiter //
CREATE Procedure ViewPerson(IN name VARCHAR(100))
BEGIN
IF EXISTS (SELECT Name FROM Person WHERE Name = name) THEN
	SELECT distinct P.Name, P.Sex, P.DOB, P.Role, 
			A.Name Nominated_For, F.Name In_Film,
			Pl.City Birth_Place_City, Pl.State Birth_Place_State,
			Pl.Country Birth_Place_Country
	FROM Person P, Award A, Film F, Place Pl
	WHERE P.Name = name
	and P.NominatedFor = A.ID
	and P.InFilm = F.ID
	and P.BirthPlace = Pl.ID;
END IF;
END//

/* Didn't use these
CREATE Procedure ViewProductionCompanies()
BEGIN
SELECT distinct P.Name 
FROM ProductionCompany;
END;

CREATE Procedure ViewBirthPlaces()
BEGIN
SELECT distinct City, State, Country
FROM Place;
END;

CREATE Procedure ViewAwards()
BEGIN
SELECT distinct Agency, Name, Year, Win, Votes
FROM Award;
END;*/

DELIMITER //
CREATE Procedure ViewFilms(IN name VARCHAR(100))
BEGIN
IF EXISTS (SELECT Name FROM Film WHERE Name = name) THEN
	SELECT distinct F.Name, F.Year, F.Rating, F.Genre, F.Country, F.Language, F.Budget, F.Gross, A.Name Nominated_For
	FROM Film F, Award A
	WHERE F.Name = name
	and F.NominatedFor = A.ID;
END IF;
END//

/* Didn't use these
CREATE Procedure ViewFilmsWithNomination(IN name VARCHAR(50))
BEGIN
IF EXISTS (SELECT Name FROM Award WHERE Name = name) THEN
	SELECT F.Name, A.Agency, A.Name, A.Year, A.Win, A.Votes
	FROM Award A, Film F
	WHERE A.Name = name
	and F.NominatedFor = A.ID;
END IF;
END;

CREATE Procedure ViewFilmsWithYear(IN year INTEGER)
BEGIN
IF EXISTS (SELECT Film.Year FROM Film WHERE Film.Year = year) THEN
	SELECT F.Name, F.Year, F.Rating, F.Genre, F.Country, F.Language, F.Budget, F.Gross, A.Name Nominated_For
	FROM Film F, Award A
	WHERE F.Year = year
	and F.NominatedFor = A.ID;
END IF;
END//*/

DELIMITER //
CREATE Procedure ViewAwardsWithYear(IN year VARCHAR(50))
BEGIN
IF EXISTS (SELECT Award.Name FROM Award WHERE Award.Year = year) THEN
	(SELECT distinct Agency, Award.Name, Award.Year, Win, Votes, Film.Name
	FROM Award, Film
	WHERE Award.Year = year
	and Film.NominatedFor = Award.ID)
	UNION
	(SELECT distinct Agency, Award.Name, Award.Year, Win, Votes, Person.Name
	FROM Award, Person
	WHERE Award.Year = year
	and Person.NominatedFor = Award.ID);
END IF;
END//

DELIMITER //
CREATE Procedure ViewAwards(IN name VARCHAR(50))
BEGIN
IF EXISTS (SELECT Award.Name FROM Award, Film WHERE Award.Name LIKE CONCAT('%', name, '%') and Film.NominatedFor = Award.ID) THEN
	SELECT distinct Agency, Award.Name, Award.Year, Win, Votes, Film.Name
	FROM Award, Film
	WHERE Award.Name LIKE CONCAT('%', name, '%')
	and Film.NominatedFor = Award.ID;
ELSEIF EXISTS (SELECT Award.Name FROM Award, Person WHERE Award.Name LIKE CONCAT('%', name, '%') and Person.NominatedFor = Award.ID) THEN
	SELECT distinct Agency, Award.Name, Award.Year, Win, Votes, Person.Name
	FROM Award, Person
	WHERE Award.Name LIKE CONCAT('%', name, '%')
	and Person.NominatedFor = Award.ID;
END IF;
END//

DELIMITER //
CREATE Procedure ViewPlaces(IN name VARCHAR(30))
BEGIN
IF EXISTS (SELECT * FROM Place WHERE City = name OR State = name OR Country = name) THEN
	SELECT distinct City, State, Country, Person.Name
	FROM Place, Person
	WHERE (City = name
	OR State = name
	OR Country = name)
	AND Person.BirthPlace = Place.ID;
END IF;
END//

//TODO: replace updatePerson, updateFilm, findFilmsGivenEmail, viewAwardsWithYear, voteForAward

DELIMITER //
CREATE Procedure Login(IN email VARCHAR(100), IN password VARCHAR(100))
BEGIN
IF EXISTS (SELECT Person.Email FROM Person WHERE Person.Email = email AND Person.Password = password) THEN
	SELECT distinct Person.Role
	FROM Person
	WHERE Person.Email = email
	and Person.Password = password;
END IF;
END//

DELIMITER //
CREATE Procedure UpdatePersonDOB(IN email VARCHAR(100), IN dob DATE)
BEGIN
IF EXISTS (SELECT Person.Email FROM Person WHERE Person.Email = email) THEN
	SELECT distinct Person.Name, Person.DOB FROM Person WHERE Person.Email = email; 
	UPDATE Person
	SET Person.DOB = dob
	WHERE Person.Email = email;
	SELECT distinct Person.Name, Person.DOB FROM Person WHERE Person.Email = email; 
END IF;
END//

DELIMITER //
CREATE Procedure UpdateFilm(IN filmName VARCHAR(100), IN Rating FLOAT, IN Genre VARCHAR(20),
	IN Country VARCHAR(10), IN Language VARCHAR(20), IN Budget INTEGER, IN Gross INTEGER)
BEGIN
IF EXISTS (SELECT Film.Name FROM Film WHERE Film.Name = filmName) THEN
	SELECT F.Name, F.Year, F.Rating, F.Genre, F.Country, F.Language, F.Budget, F.Gross
	FROM Film F 
	WHERE F.Name = filmName;
	
	UPDATE Film
	SET Film.Rating = Rating,
	Film.Genre = Genre,
	Film.Country = Country,
	Film.Language = Language,
	Film.Budget = Budget,
	Film.Gross = Gross
	WHERE Film.Name = filmName;
	
	SELECT F.Name, F.Year, F.Rating, F.Genre, F.Country, F.Language, F.Budget, F.Gross
	FROM Film F 
	WHERE F.Name = filmName;
END IF;
END//

CREATE Procedure FindFilmsGivenEmail(IN email VARCHAR(100))
BEGIN
IF EXISTS (SELECT Person.Email FROM Person WHERE Person.Email = email) THEN
	SELECT distinct F.Name, F.Year, F.Rating, F.Genre, F.Country, F.Language, F.Budget, F.Gross
	FROM Film F, Person P
	WHERE P.InFilm = F.ID
	and P.Email = email;
END IF;
END//

CREATE Procedure VoteForAward(IN email VARCHAR(100), IN awardName VARCHAR(50), IN nominee VARCHAR(100))
BEGIN
IF EXISTS (SELECT A.Name FROM Award A, Person P, Film F WHERE A.Name Like awardName AND ((P.Name = nominee AND P.NominatedFor = A.ID) OR (F.Name = nominee AND F.NominatedFor = A.ID))) THEN
	UPDATE Award
	INNER JOIN (
		SELECT A.ID FROM Award A, Person P, Film F 
						WHERE A.Name = awardName 
						AND ((P.Name = nominee AND P.NominatedFor = A.ID) 
						OR (F.Name = nominee AND F.NominatedFor = A.ID))) TheAward
	ON Award.ID = TheAward.ID
	SET Award.Votes = Award.Votes + 1;

	IF EXISTS (SELECT Person.Email FROM Person WHERE Person.Email = email) THEN
		UPDATE Person
		SET Person.HasVoted = 'YES'
		WHERE Person.Email = email;
	END IF;
END IF;
END//

drop table Send_Person_Email;
CREATE TABLE Send_Person_Email (
	ID INTEGER AUTO_INCREMENT PRIMARY KEY,
	ToEmail VARCHAR(100),
	ChangeDate DATE
);

DELIMITER //
CREATE TRIGGER after_person_update
	AFTER UPDATE ON Person
	FOR EACH ROW
BEGIN
	INSERT INTO Send_Person_Email
	SET ToEmail = OLD.Email,
	ChangeDate = NOW();
END//
DELIMITER ;

