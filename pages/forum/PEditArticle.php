<?php
// =================================================================================
//
// Redigera blogginlägg
// 
// Skapad av: Benny Henrysson
//

// -------------------------------------------------------------------------------------------
//
// Variabler
//
$idArticle = isset($_GET['idArticle']) ? $_GET['idArticle'] : '';
$idUser = isset($_SESSION['idUser']) ? $_SESSION['idUser'] : '';
$idTopic = isset($_GET['idTopic']) ? $_GET['idTopic'] : '0';

if(!is_numeric($idUser)) {
	$_SESSION['errorMessage'] = "Du har inte tillåtelse att redigera detta inlägg!";
	header('Location: ' . "?p=home");
	exit;
}

if(!is_numeric($idArticle)) {                             
	$_SESSION['errorMessage'] = "Du har inte tillåtelse att redigera detta inlägg!";
	header('Location: ' . "?p=home");
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
$tableArticle = DBT_Article;
$SPDisplayArticle = DBSP_PDisplayArticle;
$SPListArticle = DBSP_PListArticle;

$query = <<< EOD
CALL {$SPDisplayArticle}({$idArticle});
CALL {$SPListArticle}({$idUser});
EOD;

$res = $db->MultiQuery($query);
$results = Array();
$db->StoreResultsFromMultiQuery($results);
	
$row = $results[0]->fetch_object();

// -------------------------------------------------------------------------------------------
//
// Sidans innehåll
//
$content = <<< EOD
<h1>Redigera foruminlägg</h1>
<div class="editpost">
<form id="editform" action="?p=savearticle" method="post">
<input type="hidden" id="idUser" name="idUser" value="{$idUser}" />
<input type="hidden" id="idArticle" name="idArticle" value="{$idArticle}" />
<input type="hidden" id="idTopic" name="idTopic" value="{$idTopic}" />
<p>Titel:</p>
<p><input type="text" size="40" name="titleArticle" value="{$row->titleArticle}" /></p>
<p>Text:</p>
<p><textarea name="textArticle" class="textArticle" id="textArticle" rows="20" cols="50">{$row->textArticle}</textarea></p>
<p>
<button id="publish" name="publish" value="publish" type="submit">Publicera</button>
<button id="save" name="save" value="save" type="submit">Spara draft</button>
<button id="discard" name="discard" value="discard" type="reset">Återställ</button>
</p>
</form>
</div><!-- editpost -->
<p><a href="?p=showtopic&amp;idTopic={$idTopic}">Tillbaka</a></p>
EOD;

$results[0]->close();
$mysqli->close();

// ---------------------------------------------------------------------------------
//
// Skriver ut sidans innehåll
//
require_once(WS_COMMONPATH . 'CHtml.php');

$page = new CHtml();

$page->addRightCol($rightCol);
$page->addScriptTagsToHeader(Array('editorScripts.php'));
$page->printHTMLHeader('Forum Fagersta');
$page->printPageHeader();
$page->printPageBody($content);
$page->printPageFooter();

?>
