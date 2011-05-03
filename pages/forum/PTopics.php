<?php
// =================================================================================
//
// Visa alla topics
// 
// Skapad av: Benny Henrysson
//

// -------------------------------------------------------------------------------------------
//
// Variabler
//
$_SESSION['idUser'] = isset($_SESSION['idUser']) ? $_SESSION['idUser'] : '';
$_SESSION['groupMemberUser'] = isset($_SESSION['groupMemberUser']) ? $_SESSION['groupMemberUser'] : '';
$idUser = empty($_SESSION['idUser']) ? '0' : $_SESSION['idUser'];

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
$tableArticle = DBT_Article;
$tableUser = DBT_User;
$tableGroup = DBT_Group;
$tableGroupMember = DBT_GroupMember;
$tableTopic2Article = DBT_Topic2Article;
$tableTopic = DBT_Topic;
$PListTopic = DBSP_PListTopic;


$query = <<< EOD
CALL {$PListTopic}();
EOD;

$res = $db->MultiQuery($query);
$results = Array();
$db->StoreResultsFromMultiQuery($results);

// -------------------------------------------------------------------------------------------
//
// Sidans innehåll
//
$content = '';

$content .= <<< EOD
<p><a href='?p=newarticle&amp;idTopic=0' class='newpostlink' title='Posta nytt ämne'>Nytt inlägg</a><p>
<table id="forumtable">
<tr>
<th class="col1">Tråd:</th>
<th>Trådskapare:</th>
<th>Antal inlägg:</th>
<th>Senaste inlägg:</th>
</tr>
EOD;

while($row = $results[0]->fetch_object()) {

	$titleArticle = (strlen($row->titleArticle) > 20) ? substr($row->titleArticle, 0, 17) . '...' : $row->titleArticle;
	
	$content .= <<< EOD
<tr>
<td><a href="?p=showtopic&amp;idTopic={$row->idTopic}" title="{$row->titleArticle}">{$titleArticle}</a></td>
<td>{$row->nameAuthor}</td>
<td>{$row->counterTopic}</td>
<td>{$row->lastArticleDate}</td>
</tr>
EOD;
}

$content .= "</table>";
$results[0]->close();
$mysqli->close();

// ---------------------------------------------------------------------------------
//
// Skriver ut sidans innehåll
//
require_once(WS_COMMONPATH . 'CHtml.php');

$page = new CHtml();

$page->addRightCol($rightCol);

$page->printHTMLHeader('Forum Fagersta');
$page->printPageHeader();
$page->printPageBody($content);
$page->printPageFooter();

?>
