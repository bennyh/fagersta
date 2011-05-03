<?php
// =================================================================================
//
// Profil sida
// 
// Skapad av: Benny Henrysson
//

// -------------------------------------------------------------------------------------------
//
// Kontroll av behörighet
//
if(!isset($indexVisited)) die('No direct access to pagecontroller is allowed');

if(!isset($_SESSION['accountUser'])) {
	$_SESSION['errorMessage'] = "Du måste vara inloggad för att komma åt önskad sida!";
	header('Location: ' . "?p=login");
	exit;
}

// -------------------------------------------------------------------------------------------
//
// Anslut till databas
//
require_once(WS_COMMONPATH . 'CDatabaseController.php');
$db = new CDatabaseController();
$mysqli = $db->Connect();

// ---------------------------------------------------------------------------------
//
// Utför SQL förfrågning
//
$tableUser = DB_PREFIX . 'User';
$tableGroup = DB_PREFIX . 'Group';
$tableGroupMember = DB_PREFIX . 'GroupMember';

$user = $_SESSION['accountUser'];

$query = <<< EOD
SELECT 
	*
FROM {$tableUser} AS U
	INNER JOIN {$tableGroupMember} AS GM
		ON U.idUser = GM.GroupMember_idUser
	INNER JOIN {$tableGroup} AS G
		ON G.idGroup = GM.GroupMember_idGroup
WHERE
	accountUser	= '{$user}'
;
EOD;

$res = $db->MultiQuery($query);
$results = Array();
$db->StoreResultsFromMultiQuery($results);
$row = $results[0]->fetch_object();

// ---------------------------------------------------------------------------------
//
// Sidans innehåll
//
$gravatarLink = "http://www.gravatar.com/avatar/" . md5(strtolower(trim("{$row->gravatarUser}"))) . ".jpg?s=80";
$content = <<<EOD
<div class="profil">
<h1>Redigera profil</h1>
<form action="?p=editprofilep" method='POST'>
<input type="hidden" name="redirect" value="profile">
<table id="editprofile">
<caption><img src="{$gravatarLink}" width="80" height="80" /></caption>
<tr>
<th>Kontonamn</th>
<td><input type="text" name="accountUser" size="40" readonly value="{$row->accountUser}"></td>
</tr>
<tr>
<th>Email</th>
<td><input type="text" name="emailUser" size="40" value="{$row->emailUser}"></td>
</tr>
<tr>
<th>Position</th>
<td><input type="text" name="jobDescriptionUser" size="40" value="{$row->jobDescriptionUser}"></td>
</tr>
<tr>
<th>Gravatar</th>
<td><input type="text" name="gravatarUser" size="40" value="{$row->gravatarUser}"></td>
</tr>
</table>
<button name="undo" value="undo" type="reset">Ångra</button>
<button name="save" value="save" type="submit">Spara</button>
</form>
</div>
EOD;

// ---------------------------------------------------------------------------------
//
// Stänger databas
//
$mysqli->close();

// ---------------------------------------------------------------------------------
//
// Skriver ut sidans innehåll
//
require_once(WS_COMMONPATH . 'CHtml.php');

$page = new CHtml();

$page->printHTMLHeader('Användarprofil');
$page->printPageHeader();
$page->printPageBody($content);
$page->printPageFooter('Benny Henrysson, 2011');

?>
