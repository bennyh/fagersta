<?php
// =================================================================================
//
// Login process
// 
// Skapad av: Benny Henrysson
//

// ---------------------------------------------------------------------------------
//
// Sparar stylesheet innan session förstörs
//
$stylesheets = unserialize(WS_STYLESHEETSELECTION);
$stylesheet = isset($_SESSION['stylesheet']) ? $_SESSION['stylesheet'] : $stylesheets[0];

require_once(WS_COMMONPATH . 'FSessionDestroy.php');

// ---------------------------------------------------------------------------------
//
// Variabler
//
$user = isset($_POST['nameUser']) ? $_POST['nameUser'] : '';
$password = isset($_POST['passwordUser']) ? $_POST['passwordUser'] : '';

// -------------------------------------------------------------------------------------------
//
// Anslut till databas
//
require_once(WS_COMMONPATH . 'CDatabaseController.php');
$db = new CDatabaseController();
$mysqli = $db->Connect();

// ---------------------------------------------------------------------------------
//
// Utför databasförfrågning
//
$query = $db->LoadSQL('SQLLogin.php');
$res = $db->Query($query); 

// Startar ny session
session_start();
session_regenerate_id();

$row = $res->fetch_object();

if($res->num_rows === 1) {
	$_SESSION['idUser'] = $row->idUser;
	$_SESSION['accountUser'] = $row->accountUser;	
	$_SESSION['groupMemberUser'] = $row->GroupMember_idGroup;	
} 
else {
	$_SESSION['errorMessage'] = "Inloggningen misslyckades";
	$_POST['redirect'] 	= 'login';
}
$res->close();

// ---------------------------------------------------------------------------------
//
// Stänger databas
//
$mysqli->close();

// ---------------------------------------------------------------------------------
//
// Sätter stylesheet innan omdirigering
//
$_SESSION['stylesheet'] = $stylesheet;
$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : 'home';
header('Location: ' . "?p={$redirect}");
exit;
?>
