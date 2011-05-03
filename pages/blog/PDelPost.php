<?php
// =================================================================================
//
// Ta bort blogginl�gg
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
	$_SESSION['errorMessage'] = "Du har inte till�telse att ta bort detta inl�gg!";
	header('Location: ' . "?p=post&idPost={$idPost}");
	exit;
}

if(!is_numeric($idUser)) {
	die("idUser m�ste vara ett heltal. F�rs�k igen.");
}
if(!is_numeric($idPost)) {
	die("idPost m�ste vara ett heltal. F�rs�k igen.");
}


// -------------------------------------------------------------------------------------------
//
// Kontroll av beh�righet
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
// Utf�r databasf�rfr�gning
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
// St�nger databas
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
