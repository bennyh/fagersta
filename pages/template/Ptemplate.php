<?php
// =================================================================================
//
// Templatesida
// 
// Skapad av: Benny Henrysson
//

// -------------------------------------------------------------------------------------------
//
// Variabler
//


// -------------------------------------------------------------------------------------------
//
// Anslut till databas
//
//$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
//
//if (mysqli_connect_error()) {
//   echo "Connect failed: ".mysqli_connect_error()."<br>";
//   exit();
//}

// -------------------------------------------------------------------------------------------
//
// Utför databasförfrågning
//



// -------------------------------------------------------------------------------------------
//
// Sidans innehåll
//

$content = <<< EOD
<h1>Template</h1>
<p>Detta är en template-sida</p>
EOD;


//$rightCol = '';
//$leftCol = '';

// -------------------------------------------------------------------------------------------
//
// Stänger databas
//
//$mysqli->close();

// ---------------------------------------------------------------------------------
//
// Skriver ut sidans innehåll
//

require_once(WS_COMMONPATH . 'CHtml.php');

$page = new CHtml();

//$page->addRightCol($rightCol);
//$page->addLeftCol($leftCol);

$page->printHTMLHeader();
$page->printPageHeader();
$page->printPageBody($content);
$page->printPageFooter();

?>
