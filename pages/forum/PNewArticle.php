<?php
// =================================================================================
//
// Ny artikel
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

if(!isset($_SESSION['accountUser'])) {
	$_SESSION['errorMessage'] = "Du m�ste logga in f�r att f� skriva inl�gg!";
	header('Location: ' . "?p=login");
	exit;
}

// -------------------------------------------------------------------------------------------
//
// Sidans inneh�ll
//
$content = <<< EOD
<h1>Nytt foruminl�gg</h1>
<div class="newpost">
<form id="newform" action="?p=savearticle" method="post">
<input type="hidden" id="idUser" name="idUser" value="{$idUser}" />
<input type="hidden" id="idArticle" name="idArticle" value="{$idArticle}" />
<input type="hidden" id="idTopic" name="idTopic" value="{$idTopic}" />
<p>Titel:</p>
<p><input type="text" size="40" name="titleArticle" value="" /></p>
<p>Text:</p>
<p><textarea name="textArticle" class="textArticle" id="textArticle" rows="20" cols="50"></textarea></p>
<p>
<button id="publish" name="publish" value="publish" type="submit">Publicera</button>
<button id="save" name="save" value="save" type="submit">Spara draft</button>
<button id="discard" name="discard" value="discard" type="reset">�terst�ll</button>
</p>
</form>
</div><!-- newpost -->
EOD;

// ---------------------------------------------------------------------------------
//
// Skriver ut sidans inneh�ll
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
