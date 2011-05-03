<?php
// =================================================================================
//
// SQL för login
// 
// Skapad av: Benny Henrysson
//

// Förhindra SQL-skräpkod
global $user, $password;
$user         = $mysqli->real_escape_string($user);
$password     = $mysqli->real_escape_string($password);

// Hämtar tabellnamn
$tableUser = DB_PREFIX . 'User';
$tableGroupMember = DB_PREFIX . 'GroupMember';

// Skapar query
$query = <<< EOD
SELECT 
	idUser, 
	accountUser,
	GroupMember_idGroup
FROM {$tableUser} AS U
INNER JOIN {$tableGroupMember} AS GM
ON U.idUser = GM.GroupMember_idUser
WHERE
	accountUser = '{$user}' AND
	passwordUser = md5('{$password}')
;
EOD;
?>
