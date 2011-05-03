<?php
// =================================================================================
//
// Redigera blogginl�gg process
// 
// Skapad av: Benny Henrysson
//

// -------------------------------------------------------------------------------------------
//
// Variabler
//
$idPost  = isset($_POST['idPost']) ? $_POST['idPost'] : '';
$titlePost  = isset($_POST['titlePost']) ? $_POST['titlePost'] : '';
$textPost = isset($_POST['textPost']) ? $_POST['textPost'] : '';
$idUser = isset($_SESSION['idUser']) ? $_SESSION['idUser'] : '';
$tagsPost = isset($_POST['tagsPost']) ? $_POST['tagsPost'] : '';

if(!is_numeric($idUser)) {
	die("idUser m�ste vara ett heltal. F�rs�k igen.");
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
// Utf�r databasf�rfr�gning
//
$tablePost = DB_PREFIX . 'Post';
$tableTags = DB_PREFIX . 'Tags';

$query = <<< EOD
UPDATE {$tablePost}
SET
titlePost = '{$titlePost}',
textPost = '{$textPost}'
WHERE idPost = {$idPost};

DELETE FROM {$tableTags} WHERE (idPostTag = {$idPost});

EOD;

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
// St�nger databas
//
$mysqli->close();

// ---------------------------------------------------------------------------------
//
// Omdirigering
//
header("Location: " . "?p=post&idPost={$idPost}");
exit;
?>
