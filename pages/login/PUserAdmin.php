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
if($_SESSION['groupMemberUser'] != 'adm') die('You do not have the authourity to access this page');


// -------------------------------------------------------------------------------------------
//
// Variabler
//
$orderBy = isset($_GET['orderby']) ? $_GET['orderby'] : '';
$orderOrder = isset($_GET['order']) ? $_GET['order'] : '';

$orderStr = "";
if(!empty($orderBy) && !empty($orderOrder)) {
	$orderStr = " ORDER BY {$orderBy} {$orderOrder}";
}

$ascOrDesc = $orderOrder == 'ASC' ? 'DESC' : 'ASC';
$httpRef = '?p=admin&amp;order=' . $ascOrDesc . '&amp;orderby=';



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

$query = <<< EOD
SELECT 
	idUser, 
	accountUser,
	idGroup,
	nameGroup
FROM {$tableUser} AS U
	INNER JOIN {$tableGroupMember} AS GM
		ON U.idUser = GM.GroupMember_idUser
	INNER JOIN {$tableGroup} AS G
		ON G.idGroup = GM.GroupMember_idGroup
{$orderStr}
;
EOD;

$res = $db->MultiQuery($query);
$results = Array();
$db->StoreResultsFromMultiQuery($results);

	
// ---------------------------------------------------------------------------------
//
// Sidans innehåll
//	
$content = <<<EOD
<h1>Admin: Användarprofiler</h1>
<table id="adminuserprofiletable">
<tr>
<th><a href='{$httpRef}idUser'>Id</a></th>
<th><a href='{$httpRef}accountUser'>Account</a></th>
<th><a href='{$httpRef}idGroup'>Group</a></th>
<th><a href='{$httpRef}nameGroup'>Group description</a></th>
</tr>
EOD;

while ($row = $results[0]->fetch_object()) {
	$content .= <<<EOD
<tr>
<td>{$row->idUser}</td>
<td>{$row->accountUser}</td>
<td>{$row->idGroup}</td>
<td>{$row->nameGroup}</td>
</tr>
EOD;
}

$content .= "</table>";


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

$page->printHTMLHeader('Admin');
$page->printPageHeader();
$page->printPageBody($content);
$page->printPageFooter('Benny Henrysson, 2010');
?>
