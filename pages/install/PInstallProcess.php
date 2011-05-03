<?php
// =================================================================================
//
// Installation av databas utförs
// 
// Skapad av: Benny Henrysson
//

require_once(WS_COMMONPATH . 'CDatabaseController.php');

// -------------------------------------------------------------------------------------------
//
// Anslut till databas
//
$db = new CDatabaseController();
$mysqli = $db->Connect();

// -------------------------------------------------------------------------------------------
//
// Utför databasförfrågning
//

$queries = Array('SQLCreateUserAndGroupTables.php', 'SQLCreateArticleTable.php', 'SQLCreatePostTable.php');

$content = "<h2>Installation av databas</h2>";

foreach($queries as $val) {

    $query = $db->LoadSQL($val);
    $res = $db->MultiQuery($query); 
    $statements = $db->RetrieveAndIgnoreResultsFromMultiQuery();

$query = htmlspecialchars($query);


$content .= "<p>Query=</p>";
$content .= "<table class='source'><tr><td><pre>{$query}</pre></td></tr></table>";
$content .= "<p>Antal lyckade statements: {$statements}</p>";
$content .= "<p>Error code: {$mysqli->errno} ({$mysqli->error})</p>";
$content .= "<p><a href='?p=hem'>Tillbaka till hemsidan</a></p>";
}

// ---------------------------------------------------------------------------------
//
// Skriver ut sidans innehåll
//
require_once(WS_COMMONPATH . 'CHtml.php');

$page = new CHtml();

$page->printHTMLHeader('Installation av databas');
$page->printPageHeader();
$page->printPageBody($content);
$page->printPageFooter();
?>
