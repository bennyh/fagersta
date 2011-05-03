<?php
// =================================================================================
//
// Skapa profil process
// 
// Skapad av: Benny Henrysson
//

// ---------------------------------------------------------------------------------
//
// Variabler
//
$accountUser = isset($_POST['accountUser']) ? $_POST['accountUser'] : '';
$passwordUser1 = isset($_POST['passwordUser1']) ? $_POST['passwordUser1'] : '';
$passwordUser2 = isset($_POST['passwordUser2']) ? $_POST['passwordUser2'] : '';

// Recaptcha verifiering
require_once(WS_COMMONPATH . 'recaptchalib.php');
$privatekey = reCAPTCHA_PRIVATE;
$resp = recaptcha_check_answer ($privatekey,
  	  $_SERVER["REMOTE_ADDR"],
      $_POST["recaptcha_challenge_field"],
      $_POST["recaptcha_response_field"]);

if (!$resp->is_valid) {
	$_SESSION['errorMessage'] = "Du har skrivit fel ord i reCAPTCHA. Försök igen.";
	$_POST['redirect'] 	= 'newprofile';
}
else if (empty($passwordUser1) || empty($passwordUser2)) {
	$_SESSION['errorMessage'] = "Du måste skriva in ett lösenord. Försök igen.";
	$_POST['redirect'] 	= 'newprofile';
}
else if ($passwordUser1 != $passwordUser2) {
	$_SESSION['errorMessage'] = "Du har angett olika lösenord. Försök igen.";
	$_POST['redirect'] 	= 'newprofile';
}
else {
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
	$tableUser = DBT_User;
	$tableGroupMember = DBT_GroupMember;
	
	$query = <<<EOD
SELECT idUser
FROM {$tableUser}
WHERE 
	accountUser = '{$accountUser}'
LIMIT 1;
EOD;

	$res = $db->Query($query); 
	$row = $res->fetch_object();
	if (!empty($row->idUser)) {
		$_SESSION['errorMessage'] = "Kontonamnet är upptaget. Välj ett annat namn.";
		$_POST['redirect'] 	= 'newprofile';	
	}
	else {
	
// ---------------------------------------------------------------------------------
//
// Utför databasförfrågning
//
	$PCreateNewUser = DBSP_PCreateNewUser;
	$query = <<<EOD
CALL {$PCreateNewUser}('{$accountUser}', '{$passwordUser1}', 'aut');
EOD;

	$res = $db->MultiQuery($query); 

// ---------------------------------------------------------------------------------
//
// Stänger databas
//
	$mysqli->close();
	}
}

// ---------------------------------------------------------------------------------
//
// Sätter stylesheet innan omdirigering
//
$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : 'home';
header('Location: ' . "?p={$redirect}");
exit;
?>
