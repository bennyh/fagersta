<?php
// =================================================================================
//
// Installera databas-sida
// 
// Skapad av: Benny Henrysson
//
require_once(WS_SQLPATH . 'dbconfig.php');
// ---------------------------------------------------------------------------------
//
// Sidans inneh�ll
//
$database = DB_DATABASE;
$prefix = DB_PREFIX;

$content = <<< EOD
<h1>Installation</h1>
<p>Klicka p� l�nken f�r att skapa en ny databas. Vald databas �r '{$database}' och 
valt prefix �r '{$prefix}'.</p>
<p><a href='?p=installp'>T�m databas och skapa nya tabeller</a></p>
EOD;

// ---------------------------------------------------------------------------------
//
// Skriver ut sidans inneh�ll
//
require_once(WS_COMMONPATH . 'CHtml.php');

$page = new CHtml();

$page->printHTMLHeader('Installation');
$page->printPageHeader();
$page->printPageBody($content);
$page->printPageFooter('Benny Henrysson, 2010');

?>
