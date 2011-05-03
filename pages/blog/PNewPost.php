<?php
// =================================================================================
//
// Nytt blogginl�gg
// 
// Skapad av: Benny Henrysson
//

// -------------------------------------------------------------------------------------------
//
// Variabler
//
$idUser = isset($_SESSION['idUser']) ? $_SESSION['idUser'] : '';

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
<h1>Nytt blogginl�gg</h1>
<div class="newpost">
<form action="?p=newpostp" method="post">
<p><input type="hidden" name="idUser" value="{$idUser}" /></p>
<p>Titel:</p>
<p><input type="text" size="40" name="titlePost" /></p>
<p>Text:</p>
<p><textarea name="textPost" rows="20" cols="70"></textarea></p>
<p>Taggar (separerade med komman):</p>
<p><input type="text" size="40" name="tagsPost" /></p>
<p>
<button name="undo" value="undo" type="reset">�terst�ll</button>
<button name="save" value="save" type="submit">Skicka</button>
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
$page->addScriptTagsToHeader(Array('editorScriptsBlog.php'));
$page->printHTMLHeader('Fagersta Blogg');
$page->printPageHeader();
$page->printPageBody($content);
$page->printPageFooter();

?>
