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
	die("idUser m�ste vara ett heltal. F�rs�k igen.");
}

if(!is_numeric($idArticle)) {
	die("idArticle m�ste vara ett heltal. F�rs�k igen.");
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
// Utf�r databasf�rfr�gning: Tar bort inl�gg
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
