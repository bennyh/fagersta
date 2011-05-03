<?php
// =================================================================================
//
// SQL för skapande av användare och grupper
// 
// Skapad av: Benny Henrysson
//

// Hämtar tabellnamn
$tableUser = DBT_User;
$tableGroup = DBT_Group;
$tableGroupMember = DBT_GroupMember;
$tableStatistics = DBT_Statistics;
$tableArticle = DBT_Article;
$tableTopic2Article = DBT_Topic2Article;
$tableTopic = DBT_Topic;

// Hämtar namn för lagrade procedurer, funktioner och triggers
$PCreateNewArticle = DBSP_PCreateNewArticle;
$PUpdateArticle = DBSP_PUpdateArticle;
$PListArticle = DBSP_PListArticle;
$PDisplayArticle = DBSP_PDisplayArticle;
$PDeleteArticle = DBSP_PDeleteArticle;
$FCheckUserIsOwnerOrAdmin = DBF_FCheckUserIsOwnerOrAdmin;
$TAddArticle = DBT_TAddArticle;
$TRemArticle = DBT_TRemArticle;
$PDisplayTopic = DBSP_PDisplayTopic;
$PDisplayTopicAndArticles = DBSP_PDisplayTopicAndArticles;
$PListTopic = DBSP_PListTopic;

// Skapar query
$query = <<<EOD
--
-- Table for the Articles
--
DROP TABLE IF EXISTS {$tableArticle};
CREATE TABLE {$tableArticle} (

    -- Primary key(s)
    idArticle INT AUTO_INCREMENT NOT NULL PRIMARY KEY,

    -- Foreign key(s)
    idAuthor INT,
    FOREIGN KEY (idAuthor) REFERENCES {$tableUser}(idUser),
    
    -- Attributes
    titleArticle CHAR(100) NOT NULL,
    textArticle BLOB NOT NULL,
    createdArticle DATETIME NOT NULL,
    modifiedArticle DATETIME NULL,
    deletedArticle DATETIME NULL
);

--
-- SP to create new articles
--
DROP PROCEDURE IF EXISTS {$PCreateNewArticle};
CREATE PROCEDURE {$PCreateNewArticle}
(
    IN aUserId INT, 
    IN aTitle CHAR(100), 
    IN aText BLOB
)
BEGIN
	INSERT INTO {$tableArticle}
		(idAuthor, titleArticle, textArticle, createdArticle)
	VALUES
		(aUserId, aTitle, aText, NOW());
END;

--
-- SP to update articles
--
DROP PROCEDURE IF EXISTS {$PUpdateArticle};
CREATE PROCEDURE {$PUpdateArticle}
(
	IN aArticleId INT,
    IN aUserId INT, 
    IN aTitle CHAR(100), 
    IN aText BLOB
)
BEGIN
	UPDATE {$tableArticle}
	SET
		titleArticle = aTitle, 
		textArticle = aText, 
		modifiedArticle = NOW()
	WHERE
		idArticle = aArticleId AND
		{$FCheckUserIsOwnerOrAdmin}(aArticleId, aUserId)
	LIMIT 1;	
END;

--
-- SP to display article
--
DROP PROCEDURE IF EXISTS {$PDisplayArticle};
CREATE PROCEDURE {$PDisplayArticle}
(
	IN aArticleId INT
)
BEGIN
	SELECT *
	FROM {$tableArticle} inner join {$tableUser} ON
	(idAuthor = idUser)
	WHERE (idArticle = aArticleId);
END;

--
-- SP to delete article
--
DROP PROCEDURE IF EXISTS {$PDeleteArticle};
CREATE PROCEDURE {$PDeleteArticle}
(
	IN aArticleId INT,
	IN aUserId INT
)
BEGIN
	DELETE FROM {$tableArticle}
	WHERE
	(aArticleId = idArticle) AND
	{$FCheckUserIsOwnerOrAdmin}(aArticleId, aUserId)
	LIMIT 1;
END;

--
-- SP to list articles
--
DROP PROCEDURE IF EXISTS {$PListArticle};
CREATE PROCEDURE {$PListArticle}
(
	IN aUserId INT
)
BEGIN
	SELECT *
	FROM {$tableArticle} 
	WHERE (idAuthor = aUserId)
	ORDER BY modifiedArticle, createdArticle
	LIMIT 10;
END;

--
--  Create UDF that checks if user owns article or is member of group adm.
--
DROP FUNCTION IF EXISTS {$FCheckUserIsOwnerOrAdmin};
CREATE FUNCTION {$FCheckUserIsOwnerOrAdmin}
(
    aArticleId INT,
    aUserId INT
)
RETURNS BOOLEAN
BEGIN
    DECLARE articleUser INT;
	DECLARE groupMember CHAR(3);
	
	SELECT idAuthor INTO articleUser
	FROM {$tableArticle}
	WHERE (idArticle = aArticleId);
	
	SELECT GroupMember_idGroup INTO groupMember
	FROM {$tableGroupMember}
	WHERE (aUserId = GroupMember_idUser);
	
	IF (articleUser = aUserId) THEN
		RETURN TRUE;
	ELSEIF (groupMember = 'adm') THEN
		RETURN TRUE;	
	ELSE
		RETURN FALSE;
	END IF;	  
END;

--
--  Create trigger which increases the statistics value
--
DROP TRIGGER IF EXISTS {$TAddArticle};
CREATE TRIGGER {$TAddArticle}
AFTER INSERT ON {$tableArticle}
FOR EACH ROW
BEGIN
	UPDATE {$tableStatistics}
	SET numOfArticles = numOfArticles + 1
	WHERE Statistics_idUser = NEW.idAuthor;
END;

--
--  Create trigger which decreases the statistics value
--
DROP TRIGGER IF EXISTS {$TRemArticle};
CREATE TRIGGER {$TRemArticle}
AFTER DELETE ON {$tableArticle}
FOR EACH ROW
BEGIN
	UPDATE {$tableStatistics}
	SET numOfArticles = numOfArticles - 1
	WHERE Statistics_idUser = OLD.idAuthor;
END;

-- +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- Forum SQL
--

--
-- Table for the Topics
--
DROP TABLE IF EXISTS {$tableTopic};
CREATE TABLE {$tableTopic} (

  -- Primary key(s)
  idTopic INT AUTO_INCREMENT NOT NULL PRIMARY KEY,

  -- Foreign key(s)
  
  Topic_idArticle INT NOT NULL,
  FOREIGN KEY (Topic_idArticle) REFERENCES {$tableArticle}(idArticle),
  
  lastArticleAuthor INT NOT NULL, 
  FOREIGN KEY (lastArticleAuthor) REFERENCES {$tableUser}(idUser),
  
  -- Attributes
  counterTopic INT NOT NULL,
  lastArticleDate DATETIME NOT NULL
);

--
-- Table for the connections between topic and posts
--
DROP TABLE IF EXISTS {$tableTopic2Article};
CREATE TABLE {$tableTopic2Article} (

  -- Primary key(s)

  -- Foreign key(s)
  
  Topic2Article_idTopic INT NOT NULL,
  FOREIGN KEY (Topic2Article_idTopic) REFERENCES {$tableTopic}(idTopic),
  
  Topic2Article_idArticle INT NOT NULL,
  FOREIGN KEY (Topic2Article_idArticle) REFERENCES {$tableArticle}(idArticle)
  
  -- Attributes
);

--
-- SP to create new articles
--
DROP PROCEDURE IF EXISTS {$PCreateNewArticle};
CREATE PROCEDURE {$PCreateNewArticle}
(
	IN aTopicId INT,
    IN aUserId INT, 
    IN aTitle CHAR(100), 
    IN aText BLOB
)
BEGIN
	DECLARE newArticleId INT;
	
	INSERT INTO {$tableArticle}
		(idAuthor, titleArticle, textArticle, createdArticle)
	VALUES
		(aUserId, aTitle, aText, NOW());
	SET newArticleId = LAST_INSERT_ID();	
		
	IF aTopicId = 0 THEN
	BEGIN
		INSERT INTO {$tableTopic}
	 		(Topic_idArticle, counterTopic, lastArticleAuthor, lastArticleDate)
	 	VALUES
	 		(newArticleId, 1, aUserId, NOW());
	 	SET aTopicId = LAST_INSERT_ID();
	 END;
	 ELSE
	 	BEGIN
	 		UPDATE {$tableTopic} SET
	 			counterTopic = counterTopic + 1,
	 			lastArticleAuthor = aUserId,
	 			lastArticleDate = NOW()
	 		WHERE
	 				idTopic = aTopicId
	 		LIMIT 1;
	 	END;
	 END IF;
	 
	 INSERT INTO {$tableTopic2Article}
	 	(Topic2Article_idTopic, Topic2Article_idArticle)
	 VALUES
	 	(aTopicId, newArticleId);
END;

--
-- SP to list topics
--
DROP PROCEDURE IF EXISTS {$PListTopic};
CREATE PROCEDURE {$PListTopic}()
BEGIN
	SELECT U1.idUser as idAuthor,
		U2.idUser as idLastPoster,
		U1.nameUser as nameAuthor,
		U2.nameUser as nameLastPoster,
		counterTopic,
		lastArticleDate,
		idTopic,
		titleArticle
	FROM {$tableTopic}
	INNER JOIN {$tableArticle}
		ON Topic_idArticle = idArticle	
	INNER JOIN {$tableUser} AS U1
		ON idAuthor = U1.idUser
	INNER JOIN {$tableUser} AS U2
		ON lastArticleAuthor = U2.idUser
	ORDER BY lastArticleDate DESC;
END;

--
-- SP to get topic details
--
DROP PROCEDURE IF EXISTS {$PDisplayTopic};
CREATE PROCEDURE {$PDisplayTopic}
(IN aTopicId INT)
BEGIN
	SELECT *		
	FROM {$tableTopic} 
	INNER JOIN {$tableArticle}
		ON Topic_idArticle = idArticle
	INNER JOIN {$tableUser} AS U1
		ON idAuthor = U1.idUser
	INNER JOIN {$tableUser} AS U2
		ON lastArticleAuthor = U2.idUser
	WHERE (aTopicId = idTopic)
	LIMIT 1;
END;

--
-- SP to get topic and it's articles
--
DROP PROCEDURE IF EXISTS {$PDisplayTopicAndArticles};
CREATE PROCEDURE {$PDisplayTopicAndArticles}
(IN aTopicId INT)
BEGIN

	CALL {$PDisplayTopic}(aTopicId);

	SELECT *
	FROM {$tableTopic2Article} 
	INNER JOIN {$tableArticle}
		ON idArticle = Topic2Article_idArticle
	INNER JOIN {$tableUser}
		ON idUser = idAuthor
	INNER JOIN {$tableStatistics}
		ON idUser = Statistics_idUser	
	WHERE
		Topic2Article_idTopic = aTopicId
	ORDER BY createdArticle,idArticle ASC;
END;
EOD;

// -------------------------------------------------------------------------------
//
// Skapar exempelposter: Ta bort eller kommentera bort om du inte behöver dessa
//

$query .= <<<EOD
--
-- Create example forum posts
--
CALL {$PCreateNewArticle} (0,1,'Första posten i forumet','Hej och välkomna');
CALL {$PCreateNewArticle} (1,2,'','Kul o va här');
CALL {$PCreateNewArticle} (0,1,'Slutspammat','Nej, nu får ni sluta att spamma forumet!');
CALL {$PCreateNewArticle} (2,4,'Ok','Det ska inte uppepas');
CALL {$PCreateNewArticle} (0,3,'Hur står det till därute?','Skriv av er hur det känns just nu.');

EOD;

?>
