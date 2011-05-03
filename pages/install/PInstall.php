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
// Sidans innehåll
//
$database = DB_DATABASE;
$prefix = DB_PREFIX;

$content = <<< EOD
<h1>Installation</h1>
<p>Klicka på länken för att skapa en ny databas. Vald databas är '{$database}' och 
valt prefix är '{$prefix}'.</p>
<p><a href='?p=installp'>Töm databas och skapa nya tabeller</a></p>
EOD;

// ---------------------------------------------------------------------------------
//
// Skriver ut sidans innehåll
//
require_once(WS_COMMONPATH . 'CHtml.php');

$page = new CHtml();

$page->printHTMLHeader('Installation');
$page->printPageHeader();
$page->printPageBody($content);
$page->printPageFooter('Benny Henrysson, 2010');

?>
