<?php
// =================================================================================
//
// Sparar artikel
// 
// Skapad av: Benny Henrysson
//

// -------------------------------------------------------------------------------------------
//
// Variabler
//
$titleArticle  = isset($_POST['titleArticle']) ? utf8_decode($_POST['titleArticle']) : '';
$textArticle = isset($_POST['textArticle']) ? $_POST['textArticle'] : '';
$idUser = isset($_SESSION['idUser']) ? $_SESSION['idUser'] : '';
$idArticle = isset($_POST['idArticle']) ? $_POST['idArticle'] : '';
$idTopic = isset($_POST['idTopic']) ? $_POST['idTopic'] : '0';
$timestamp = "";

// Rensa otillåtna taggar
$allowedTags='<p><strong><em><u><h1><h2><h3><h4><h5><h6><img>';
$allowedTags.='<li><ol><ul><span><div><br><ins><del>'; 
$titleArticle = strip_tags($titleArticle, $allowedTags);
$textArticle = strip_tags($textArticle, $allowedTags);

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
$tableArticle = DBT_Article;
$tableTopic2Article = DBT_Topic2Article;
$tableTopic = DBT_Topic;
$PCreateNewArticle = DBSP_PCreateNewArticle;
$PUpdateArticle = DBSP_PUpdateArticle;

// Om artikeln ska skapas
if (empty($idArticle)) {
	// Om ämnet är nytt
	if ($idTopic == 0) {
		$query = <<< EOD
CALL {$PCreateNewArticle}({$idTopic},{$idUser}, '{$titleArticle}', '{$textArticle}');
SELECT LAST_INSERT_ID() AS id
FROM {$tableTopic};
SELECT 	NOW() AS timestamp;
SELECT idArticle
FROM {$tableArticle}
ORDER BY idArticle DESC
LIMIT 1;
EOD;

		$res = $db->MultiQuery($query);
		$results = Array();
		$db->StoreResultsFromMultiQuery($results);
		$row = $results[1]->fetch_object();
		$idTopic = $row->id;
		$row = $results[2]->fetch_object();
		$timestamp = $row->timestamp;
		$row = $results[3]->fetch_object();
		$idArticle = $row->idArticle;
	}
	// Om ämnet finns sedan tidigare
	else {
		$query = <<< EOD
CALL {$PCreateNewArticle}({$idTopic},{$idUser}, '{$titleArticle}', '{$textArticle}');
SELECT 	NOW() AS timestamp;
SELECT LAST_INSERT_ID() AS idArticle
FROM {$tableArticle};
EOD;

		$res = $db->MultiQuery($query);
		$results = Array();
		$db->StoreResultsFromMultiQuery($results);
		$row = $results[1]->fetch_object();
		$timestamp = $row->timestamp;
		$row = $results[2]->fetch_object();
		$idArticle = $row->idArticle;
	}
}
// Om artikeln ska uppdatereas
else {
	$query = <<< EOD
CALL {$PUpdateArticle}({$idArticle}, {$idUser}, '{$titleArticle}', '{$textArticle}');
SELECT 	NOW() AS timestamp;
EOD;

	$res = $db->MultiQuery($query);
	$results = Array();
	$db->StoreResultsFromMultiQuery($results);
	$row = $results[1]->fetch_object();
	$timestamp = $row->timestamp;
}
$mysqli->close();

// ---------------------------------------------------------------------------------
//
// Omdirigering
//
$json = <<<EOD
{
	"topicId": {$idTopic},
	"articleId": {$idArticle},
	"timestamp": "{$timestamp}"
}
EOD;

echo $json;
exit;
?>                                                                 
