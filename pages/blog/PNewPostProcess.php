<?php
// =================================================================================
//
// Nytt blogginlägg process
// 
// Skapad av: Benny Henrysson
//

// -------------------------------------------------------------------------------------------
//
// Variabler
//
$titlePost  = isset($_POST['titlePost']) ? $_POST['titlePost'] : '';
$textPost = isset($_POST['textPost']) ? $_POST['textPost'] : '';
$tagsPost = isset($_POST['tagsPost']) ? $_POST['tagsPost'] : '';
$idUser = isset($_SESSION['idUser']) ? $_SESSION['idUser'] : '';

if(!is_numeric($idUser)) {
	die("idUser måste vara ett heltal. Försök igen.");
}

// -------------------------------------------------------------------------------------------
//
// Anslut till databas
//
require_once(WS_COMMONPATH . 'CDatabaseController.php');
$db = new CDatabaseController();
$mysqli = $db->Connect();

// -------------------------------------------------------------------------------------------
//
// Utför databasförfrågning: Lägger in inlägg
//
$tablePost = DB_PREFIX . 'Post';

$query = <<< EOD
INSERT INTO {$tablePost}(idAuthor, titlePost, textPost, datePost)
VALUES ({$idUser}, '{$titlePost}', '{$textPost}', NOW());

SELECT LAST_INSERT_ID() as id
FROM {$tablePost}
LIMIT 1;
EOD;

$res = $db->MultiQuery($query);
$results = Array();
$db->StoreResultsFromMultiQuery($results);

$row = $results[1]->fetch_object();
$idPost = $row->id;

// -------------------------------------------------------------------------------------------
//
// Stänger databas
//
$mysqli->close();

// -------------------------------------------------------------------------------------------
//
// Anslut till databas
//
require_once(WS_COMMONPATH . 'CDatabaseController.php');
$db = new CDatabaseController();
$mysqli = $db->Connect();

// -------------------------------------------------------------------------------------------
//
// Utför databasförfrågning: Lägger in taggar
//
$tableTags = DB_PREFIX . 'Tags';

$query = "";
$tagArray = explode(',', $tagsPost);

foreach ($tagArray as $tag) {
	$tag = trim($tag);
	$query .= <<< EOD
INSERT INTO {$tableTags} (idPostTag, titleTag)
VALUES ({$idPost}, '{$tag}');
EOD;
}

$res = $db->MultiQuery($query);

// -------------------------------------------------------------------------------------------
//
// Stänger databas
//
$mysqli->close();

// ---------------------------------------------------------------------------------
//
// Omdirigering
//
header("Location: " . "?p=post&idPost={$idPost}");
exit;
?>
