<?php
// =================================================================================
//
// Tar bort artikel
// 
// Skapad av: Benny Henrysson
//

// -------------------------------------------------------------------------------------------
//
// Variabler
//
$idUser = isset($_SESSION['idUser']) ? $_SESSION['idUser'] : '';
$idArticle = isset($_GET['idArticle']) ? $_GET['idArticle'] : '';

if(!is_numeric($idUser)) {
	die("idUser måste vara ett heltal. Försök igen.");
}

if(!is_numeric($idArticle)) {
	die("idArticle måste vara ett heltal. Försök igen.");
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
// Utför databasförfrågning: Tar bort inlägg
//
$tableArticle = DBT_Article;
$PDeleteArticle = DBSP_PDeleteArticle;

$query = <<< EOD
CALL {$PDeleteArticle}({$idArticle}, {$idUser});
EOD;

$res = $db->Query($query);
$mysqli->close();

// ---------------------------------------------------------------------------------
//
// Omdirigering
//
header("Location: " . "?p=topics");              
exit;
?>                                                                 
