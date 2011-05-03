<?php
// =================================================================================
//
// Ta bort kommentar
// 
// Skapad av: Benny Henrysson
//

// -------------------------------------------------------------------------------------------
//
// Variabler
//
$idComment  = isset($_GET['idComment']) ? $_GET['idComment'] : '';
$idPost = isset($_GET['idPost']) ? $_GET['idPost'] : '';

if(!is_numeric($idPost)) {
	die("idPost måste vara ett heltal. Försök igen.");
}
if(!is_numeric($idComment)) {
	die("idPost måste vara ett heltal. Försök igen.");
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
$tableComment = DB_PREFIX . 'Comment';

$query = <<< EOD
DELETE FROM {$tableComment}
WHERE (idComment = {$idComment});
EOD;

$res = $db->Query($query);

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
