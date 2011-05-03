<?php
// =================================================================================
//
// Hemsidan för webbplatsen
// 
// Skapad av: Benny Henrysson
//

// ---------------------------------------------------------------------------------
//
// Sidans innehåll
//

$content = <<< EOD
<h1>Fagersta</h1>
<p>Detta är Fagersta. En webbtemplate färdig att användas. Ändra i denna fil (pages/index/PIndex.php) 
för att komma igång. Eller använd gärna filen Ptemplate.php (pages/template/Ptemplate.php) som grund 
när du skapar nya sidor.</p>
EOD;

// ---------------------------------------------------------------------------------
//
// Skriver ut sidans innehåll
//
require_once(WS_COMMONPATH . 'CHtml.php');

$page = new CHtml();

$page->printHTMLHeader('Fagersta');
$page->printPageHeader();
$page->printPageBody($content);
$page->printPageFooter('Benny Henrysson, 2011');

?>
