<?php
// =================================================================================
//
// Ta bort blogginlägg
// 
// Skapad av: Benny Henrysson
//

// -------------------------------------------------------------------------------------------
//
// Variabler
//
$idPost = isset($_GET['idPost']) ? $_GET['idPost'] : '';
$idUser = isset($_SESSION['idUser']) ? $_SESSION['idUser'] : '';

if(!isset($_SESSION['accountUser'])) {
	$_SESSION['errorMessage'] = "Du har inte tillåtelse att ta bort detta inlägg!";
	header('Location: ' . "?p=post&idPost={$idPost}");
	exit;
}

if(!is_numeric($idUser)) {
	die("idUser måste vara ett heltal. Försök igen.");
}
if(!is_numeric($idPost)) {
	die("idPost måste vara ett heltal. Försök igen.");
}


// -------------------------------------------------------------------------------------------
//
// Kontroll av behörighet
//
if(!isset($indexVisited)) die('No direct access to pagecontroller is allowed');
if(!isset($_SESSION['accountUser'])) {
	$_SESSION['errorMessage'] = "Please login to access requested page!";
	header('Location: ' . "?p=login");
	exit;
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
// Utför databasförfrågning
//
$tablePost = DB_PREFIX . 'Post';
$tableComment = DB_PREFIX . 'Comment';
$tableTags = DB_PREFIX . 'Tags';

$query = <<< EOD
DELETE FROM {$tableTags}
WHERE (idPostTag = {$idPost});

DELETE FROM {$tableComment}
WHERE (idPostComment = {$idPost});

DELETE FROM {$tablePost}
WHERE (idPost = {$idPost})
LIMIT 1;
EOD;

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

$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : 'showblog';
header("Location: " . "?p={$redirect}");
exit;
?>
