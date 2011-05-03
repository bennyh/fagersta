<?php
// =================================================================================
//
// Logout process
// 
// Skapad av: Benny Henrysson
//

// ---------------------------------------------------------------------------------
//
// Förstör session
//
require_once(WS_COMMONPATH . 'FSessionDestroy.php');

// ---------------------------------------------------------------------------------
//
// Omdirigering
//
$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : 'home';
header('Location: ' . "?p={$redirect}");
exit;
?>
