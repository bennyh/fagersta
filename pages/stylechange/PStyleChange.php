<?php
// =================================================================================
//
// Växla stylesheet
// 
// Skapad av: Benny Henrysson
//

// -------------------------------------------------------------------------------------------
//
// Variabler
//
$_SESSION['stylesheet'] = isset($_SESSION['stylesheet']) ? $_SESSION['stylesheet'] : 'stylesteets/standard.css';

$stylesheet = unserialize(WS_STYLESHEETSELECTION);

// ---------------------------------------------------------------------------------
//
// Byter stylesheet
//
$currentStyleNr = 0;
for ($i=0;$i<sizeof($stylesheet);$i++) {
	if ($_SESSION['stylesheet'] == $stylesheet[$i]) {		
		$currentStyleNr = $i;
	}
}

if ($_SESSION['stylesheet'] == $stylesheet[sizeof($stylesheet)-1]) {
	$_SESSION['stylesheet'] = $stylesheet[0];
}
else {
	$_SESSION['stylesheet'] = $stylesheet[$currentStyleNr+1];
}

// ---------------------------------------------------------------------------------
//
// Omdirigering
//
header("Location: " . "?p=hem");
exit;
?>
