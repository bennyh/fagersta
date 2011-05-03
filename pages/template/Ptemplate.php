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
// Utf�r databasf�rfr�gning
//



// -------------------------------------------------------------------------------------------
//
// Sidans inneh�ll
//

$content = <<< EOD
<h1>Template</h1>
<p>Detta �r en template-sida</p>
EOD;


//$rightCol = '';
//$leftCol = '';

// -------------------------------------------------------------------------------------------
//
// St�nger databas
//
//$mysqli->close();

// ---------------------------------------------------------------------------------
//
// Skriver ut sidans inneh�ll
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
