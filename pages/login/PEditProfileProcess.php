<?php
// =================================================================================
//
// Updatera profil process
// 
// Skapad av: Benny Henrysson
//

// ---------------------------------------------------------------------------------
//
// Variabler
//
$idUser = isset($_SESSION['idUser']) ? $_SESSION['idUser'] : '';
$emailUser = isset($_POST['emailUser']) ? $_POST['emailUser'] : '';
$jobDescriptionUser = isset($_POST['jobDescriptionUser']) ? $_POST['jobDescriptionUser'] : '';
$gravatarUser = isset($_POST['gravatarUser']) ? $_POST['gravatarUser'] : '';

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
$PUpdateUser = DBSP_PUpdateUser;
$query = <<<EOD
CALL {$PUpdateUser}('{$idUser}', '{$emailUser}', '{$jobDescriptionUser}', '{$gravatarUser}');	
EOD;

$res = $db->MultiQuery($query); 

// ---------------------------------------------------------------------------------
//
// Stänger databas
//
$mysqli->close();

$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : 'home';
header('Location: ' . "?p={$redirect}");
exit;
?>
