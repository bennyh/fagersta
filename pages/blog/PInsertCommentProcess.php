<?php
// =================================================================================
//
// Ny kommentar process
// 
// Skapad av: Benny Henrysson
//

// -------------------------------------------------------------------------------------------
//
// Variabler
//
$idPost = isset($_POST['idPost']) ? $_POST['idPost'] : '';
$titleComment  = isset($_POST['titleComment']) ? $_POST['titleComment'] : '';
$textComment = isset($_POST['textComment']) ? $_POST['textComment'] : '';
$authorComment = isset($_POST['authorComment']) ? $_POST['authorComment'] : '';
$emailComment = isset($_POST['emailComment']) ? $_POST['emailComment'] : '';

if(!is_numeric($idPost)) {
	die("idPost m�ste vara ett heltal. F�rs�k igen.");
}

// Enkel emailvalidering
$emailValid = FALSE;

if (preg_match('/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/', $emailComment)) {
	$emailValid = TRUE;
}

if(!$emailValid) {
	$_SESSION['errorMessage'] = "Du m�ste ange en korrekt emailaddress!";
	header('Location: ' . "?p=post&idPost={$idPost}");
	exit;
}

// -------------------------------------------------------------------------------------------
//
// Anslut till databas
//
require_once(WS_COMMONPATH . 'CDatabaseController.php');
$db = new CDatabaseController();
$mysqli = $db->Connect();

// -------------------------------------------------------------------------------------------
//
// Utf�r databasf�rfr�gning
//
$tableComment = DB_PREFIX . 'Comment';

$query = <<< EOD
INSERT INTO {$tableComment}(idPostComment, titleComment, textComment, authorComment, dateComment, emailComment)
VALUES ({$idPost}, '{$titleComment}', '{$textComment}', '{$authorComment}', NOW(), '{$emailComment}');
EOD;

$res = $db->Query($query);

// -------------------------------------------------------------------------------------------
//
// St�nger databas
//
$mysqli->close();

// ---------------------------------------------------------------------------------
//
// Omdirigering
//
$redirect = isset($_POST['redirect']) ? $_POST['redirect'] : 'showblog';
header("Location: " . "?p={$redirect}");
exit;
?>
