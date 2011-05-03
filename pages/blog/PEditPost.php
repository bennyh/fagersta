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
$idPost = isset($_GET['idPost']) ? $_GET['idPost'] : '';
$idUser = isset($_SESSION['idUser']) ? $_SESSION['idUser'] : '';

if(!is_numeric($idUser)) {
	$_SESSION['errorMessage'] = "Du har inte tillåtelse att redigera detta inlägg!";
	header('Location: ' . "?p=home");
	exit;
}

if(!is_numeric($idPost)) {                             
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
$tablePost = DB_PREFIX . 'Post';
$tableTags = DB_PREFIX . 'Tags';

$query = <<< EOD
SELECT *
FROM {$tablePost}
WHERE idPost = {$idPost};

SELECT titleTag
FROM {$tableTags}
WHERE (idPostTag = {$idPost})
ORDER BY titleTag;
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
<h1>Redigera inlägg</h1>
<div class="editpost">
<form action="?p=editpostp" method="post">
<p>
<input type="hidden" name="idUser" value="{$idUser}" />
<input type="hidden" name="idPost" value="{$idPost}" />
</p>
<p>Titel:</p>
<p><input type="text" size="40" name="titlePost" value="{$row->titlePost}" /></p>
<p>Text:</p>
<p><textarea name="textPost" rows="20" cols="70">{$row->textPost}</textarea></p>
EOD;

$tags = "";
	
while($row = $results[1]->fetch_object()) {
   	$tags .= "{$row->titleTag}, ";
}

if ($tags != "")
  	$tags = substr($tags, 0, -2);
	
$content .= <<< EOD
<p>Taggar (separerade med komman):</p>
<p><input type="text" size="40" name="tagsPost" value="{$tags}" /></p>
<p>
<button name="undo" value="undo" type="reset">Återställ</button>
<button name="save" value="save" type="submit">Skicka</button>
</p>
</form>
</div><!-- editpost -->
EOD;

// -------------------------------------------------------------------------------------------
//
// Stänger databas
//
$mysqli->close();

// ---------------------------------------------------------------------------------
//
// Skriver ut sidans innehåll
//
require_once(WS_COMMONPATH . 'CHtml.php');

$page = new CHtml();
$page->addScriptTagsToHeader(Array('editorScriptsBlog.php'));
$page->printHTMLHeader('Fagersta Blogg');
$page->printPageHeader();
$page->printPageBody($content);
$page->printPageFooter();

?>
