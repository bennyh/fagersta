<?php
// =================================================================================
//
// Visa artiklar
// 
// Skapad av: Benny Henrysson
//

// -------------------------------------------------------------------------------------------
//
// Variabler
//
$_SESSION['idUser'] = isset($_SESSION['idUser']) ? $_SESSION['idUser'] : '';
$_SESSION['groupMemberUser'] = isset($_SESSION['groupMemberUser']) ? $_SESSION['groupMemberUser'] : '';
$idTopic = isset($_GET['idTopic']) ? $_GET['idTopic'] : '0';
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
$PDisplayTopicAndArticles = DBSP_PDisplayTopicAndArticles;

$query = <<< EOD
CALL {$PDisplayTopicAndArticles} ({$idTopic});
EOD;

$res = $db->MultiQuery($query);
$results = Array();
$db->StoreResultsFromMultiQuery($results);

// -------------------------------------------------------------------------------------------
//
// Sidans innehåll
//
$content = '';

$content .="<p><a href='?p=newarticle&amp;idTopic={$idTopic}' class='newpostlink' title='Svara'>Besvara</a><p>";

while($row = $results[1]->fetch_object()) {

	$userIsAuthor = ($row->idUser == $_SESSION['idUser']) ? TRUE : FALSE;
	
	$gravatarLink = "http://www.gravatar.com/avatar/" . md5(strtolower(trim("{$row->gravatarUser}"))) . ".jpg?s=80";
	
	$content .= <<< EOD
<div class="forumpost">
<div class="postheader">
<h3>{$row->titleArticle}</h3>
</div> <!-- postheader -->
<table class="forumpostcontent">
<tr>
<th>
<p><a href="mailto:{$row->emailUser}" title="Skriv ett mail till {$row->accountUser}">{$row->accountUser}</a></p>
<p><img src="{$gravatarLink}" width="80" height="80" /></p>
<p>{$row->jobDescriptionUser}</p>
<p>Antal inlägg: {$row->numOfArticles}</p>
</th>
<td>
{$row->textArticle}
</td>
</tr>
</table>                         
<div class="postfooter">
EOD;

// Länkar för radering och redigering för inläggsskaparen
if ($userIsAuthor) {
		$content .= <<< EOD
<p>
<a href="?p=editarticle&amp;idArticle={$row->idArticle}&amp;idTopic={$idTopic}" class="modifylink" title="Redigera detta inlägg">Redigera</a>
</p>
EOD;
}
$createdModified = "<p>Skrivet {$row->createdArticle}.</p>";
if (!empty($row->modifiedArticle)) {
	$createdModified .= "<p>Ändrad {$row->modifiedArticle}.</p>";
}

$content .= <<< EOD
{$createdModified}
</div> <!-- postfooter -->
</div> <!-- forumpost -->
EOD;
	
}

$content .="<p><a href='?p=newarticle&amp;idTopic={$idTopic}' class='newpostlink' title='Svara'>Besvara</a><p>";

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
