<?php
// =================================================================================
//
// SQL för skapande av användare och grupper
// 
// Skapad av: Benny Henrysson
//

// Hämtar tabellnamn
$tableUser          = DBT_User;
$tableGroup         = DBT_Group;
$tableGroupMember   = DBT_GroupMember;
$tableStatistics    = DBT_Statistics;
$tableArticle		= DBT_Article;

// Hämtar namn för lagrade procedurer, funktioner och triggers
$TInsertUser = DBT_TInsertUser;
$PCreateNewUser = DBSP_PCreateNewUser;
$PUpdateUser = DBSP_PUpdateUser;

// Skapar query
$query = <<<EOD
DROP TABLE IF EXISTS {$tableGroupMember};
DROP TABLE IF EXISTS {$tableUser};
DROP TABLE IF EXISTS {$tableGroup};

--
-- Table for the User
--
CREATE TABLE {$tableUser} (

  -- Primary key(s)
  idUser INT AUTO_INCREMENT NOT NULL PRIMARY KEY,

  -- Attributes
  accountUser CHAR(20) NOT NULL UNIQUE,
  passwordUser CHAR(32) NOT NULL,
  jobDescriptionUser CHAR(50) NOT NULL,
  nameUser CHAR(50) NOT NULL,
  emailUser CHAR(100) NULL,
  gravatarUser VARCHAR(100) NULL
);


--
-- Table for the Group
--
CREATE TABLE {$tableGroup} (

  -- Primary key(s)
  idGroup CHAR(3) NOT NULL PRIMARY KEY,

  -- Attributes
  nameGroup CHAR(40) NOT NULL
);


--
-- Table for the GroupMember
--
CREATE TABLE {$tableGroupMember} (

  -- Primary key(s)
  --
  -- The PK is the combination of the two foreign keys, see below.
  --
 
  -- Foreign keys
  GroupMember_idUser INT NOT NULL,
  GroupMember_idGroup CHAR(3) NOT NULL,
    
  FOREIGN KEY (GroupMember_idUser) REFERENCES {$tableUser}(idUser),
  FOREIGN KEY (GroupMember_idGroup) REFERENCES {$tableGroup}(idGroup),

  PRIMARY KEY (GroupMember_idUser, GroupMember_idGroup)
 
  -- Attributes

);

--
-- Create a Statistics table 
--
DROP TABLE IF EXISTS {$tableStatistics};
CREATE TABLE {$tableStatistics} 
(

  -- Primary key(s)
  -- Foreign keys
  Statistics_idUser INT NOT NULL,
  
  FOREIGN KEY (Statistics_idUser) REFERENCES {$tableUser}(idUser),
  PRIMARY KEY (Statistics_idUser),
  
  -- Attributes
  numOfArticles INT NOT NULL DEFAULT 0
);

--
--  Create trigger which adds rows when new user is added
--
DROP TRIGGER IF EXISTS {$TInsertUser};
CREATE TRIGGER {$TInsertUser}
AFTER INSERT ON {$tableUser}
FOR EACH ROW
BEGIN
	INSERT INTO {$tableStatistics} (Statistics_idUser) VALUES (NEW.idUser);
END;

--
-- SP to create new users
--
DROP PROCEDURE IF EXISTS {$PCreateNewUser};
CREATE PROCEDURE {$PCreateNewUser}
(
	IN aAccountUser CHAR(20),
    IN aPasswordUser CHAR(32),
    IN aGroupMember_idGroup CHAR(3)
)
BEGIN	
	INSERT INTO
		{$tableUser} (accountUser, passwordUser)
	VALUES
		(aAccountUser,md5(aPasswordUser));
	
	INSERT INTO 
		{$tableGroupMember} (GroupMember_idUser, GroupMember_idGroup)
	VALUES 
		((SELECT idUser FROM {$tableUser} WHERE accountUser = aAccountUser), aGroupMember_idGroup);
END;

--
-- SP to update user info
--
DROP PROCEDURE IF EXISTS {$PUpdateUser};
CREATE PROCEDURE {$PUpdateUser}
(
	IN aIdUser INT,
    IN aEmailUser CHAR(100),
    IN aJobDescriptionUser CHAR(50),
    IN aGravatarUser VARCHAR(100)
)
BEGIN	
	UPDATE {$tableUser}
	SET
		emailUser = aEmailUser,
		jobDescriptionUser = aJobDescriptionUser,
		gravatarUser = aGravatarUser
	WHERE
		idUser = aIdUser
	LIMIT 1;
END;

EOD;

// -------------------------------------------------------------------------------
//
// Skapar exempelanvändare: Ta bort eller kommentera bort om du inte behöver dessa
// 
$query .= <<<EOD
--
-- Add default user(s)
--
INSERT INTO {$tableUser} (accountUser, passwordUser, jobDescriptionUser, nameUser, emailUser,gravatarUser)
VALUES ('benny', md5('benny'), 'Chef', 'Benny Henrysson', 'bhenrysson@yahoo.com', 'bhenrysson@yahoo.com');
INSERT INTO {$tableUser} (accountUser, passwordUser, jobDescriptionUser, nameUser, emailUser, gravatarUser)
VALUES ('eddie', md5('eddie'), 'Hälsocoach', 'Edward Gustafsson', 'eddieg@hotmail.com', '');
INSERT INTO {$tableUser} (accountUser, passwordUser, jobDescriptionUser, nameUser, emailUser, gravatarUser)
VALUES ('bosse', md5('bosse'), 'Kassör', 'Bo Tvär', 'bothetvaer@yahoo.com', '');
INSERT INTO {$tableUser} (accountUser, passwordUser, jobDescriptionUser, nameUser, emailUser, gravatarUser)
VALUES ('tanja', md5('tanja'), 'Hålkontrollant', 'Tanja Ost', 'osteater@gmail.com', '');
INSERT INTO {$tableUser} (accountUser, passwordUser, jobDescriptionUser, nameUser, emailUser, gravatarUser)
VALUES ('vera', md5('vera'), 'Databasbas', 'Vera Hovimäki', 'veraveravera@hotmail.com', '');

--
-- Add default groups
--
INSERT INTO {$tableGroup} (idGroup, nameGroup) VALUES ('adm', 'Administrator');
INSERT INTO {$tableGroup} (idGroup, nameGroup) VALUES ('aut', 'Authors');

--
-- Add default groupmembers
--
INSERT INTO {$tableGroupMember} (GroupMember_idUser, GroupMember_idGroup)
VALUES ((SELECT idUser FROM {$tableUser} WHERE accountUser = 'eddie'), 'aut');
INSERT INTO {$tableGroupMember} (GroupMember_idUser, GroupMember_idGroup)
VALUES ((SELECT idUser FROM {$tableUser} WHERE accountUser = 'bosse'), 'aut');
INSERT INTO {$tableGroupMember} (GroupMember_idUser, GroupMember_idGroup)
VALUES ((SELECT idUser FROM {$tableUser} WHERE accountUser = 'tanja'), 'aut');
INSERT INTO {$tableGroupMember} (GroupMember_idUser, GroupMember_idGroup)
VALUES ((SELECT idUser FROM {$tableUser} WHERE accountUser = 'benny'), 'aut');
INSERT INTO {$tableGroupMember} (GroupMember_idUser, GroupMember_idGroup)
VALUES ((SELECT idUser FROM {$tableUser} WHERE accountUser = 'vera'), 'adm');
EOD;

?>
