<?php
// =================================================================================
//
// Login sida
// 
// Skapad av: Benny Henrysson
//

// ---------------------------------------------------------------------------------
//
// Sidans inneh�ll
//
$content = <<<EOD
<h1>Logga in</h1>
<div id="loginform">
<form action="?p=loginp" method="post">
<p><input type="hidden" name="redirect" value="home" /></p>
<p>Anv�ndarnamn:</p>
<p><input type="text" name="nameUser" /></p>
<p>L�senord:</p>
<p><input type="password" name="passwordUser" /></p>
<p>
<button name="undo" value="undo" type="reset">�ngra</button>
<button name="login" type="submit">Logga in</button>
</p>
</form>
<p><a href="?p=newprofile">Skapa ny anv�ndarprofil</a></p>
</div><!-- loginform -->
EOD;

// ---------------------------------------------------------------------------------
//
// Skriver ut sidans inneh�ll
//
require_once(WS_COMMONPATH . 'CHtml.php');

$page = new CHtml();

$page->printHTMLHeader('Logga in');
$page->printPageHeader();
$page->printPageBody($content);
$page->printPageFooter();

?>
