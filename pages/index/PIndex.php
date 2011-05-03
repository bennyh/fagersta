<?php
// =================================================================================
//
// Hemsidan f�r webbplatsen
// 
// Skapad av: Benny Henrysson
//

// ---------------------------------------------------------------------------------
//
// Sidans inneh�ll
//

$content = <<< EOD
<h1>Fagersta</h1>
<p>Detta �r Fagersta. En webbtemplate f�rdig att anv�ndas. �ndra i denna fil (pages/index/PIndex.php) 
f�r att komma ig�ng. Eller anv�nd g�rna filen Ptemplate.php (pages/template/Ptemplate.php) som grund 
n�r du skapar nya sidor.</p>
EOD;

// ---------------------------------------------------------------------------------
//
// Skriver ut sidans inneh�ll
//
require_once(WS_COMMONPATH . 'CHtml.php');

$page = new CHtml();

$page->printHTMLHeader('Fagersta');
$page->printPageHeader();
$page->printPageBody($content);
$page->printPageFooter('Benny Henrysson, 2011');

?>
