<?php
// --------------------------------------------------------------------------------------
//
// config.php
//

// -------------------------------------------------------------------------------------------
//
// S�kv�g f�r filer
//
define('WS_COMMONPATH', dirname(__FILE__) . '/');
define('WS_PATH', dirname(WS_COMMONPATH) . '/');
define('WS_PAGEPATH', WS_PATH . 'pages/');
define('WS_IMGPATH', WS_PATH . 'img/');
define('WS_SQLPATH', WS_PATH . 'sql/');
define('WS_JSPATH', WS_PATH . 'js/');

// --------------------------------------------------------------------------------------
//
// Default-v�rden f�r webbsidan
//
define('WS_TITLE', 'TITLE OF THE WEBPAGE');
define('WS_FOOTER', 'FOOTER OF THE WEBPAGE');
define('WS_CHARSET', 'ISO-8859-1');
define('WS_SITELINK', 'LINK TO THE WEBPAGE');

if (isset($_SESSION['stylesheet'])) { 
	define('WS_STYLESHEET', $_SESSION['stylesheet']);
}
else {
	define('WS_STYLESHEET', 'stylesheets/standard.css');
}

// --------------------------------------------------------------------------------------
//
// Nycklar f�r recaptcha
//
define('reCAPTCHA_PUBLIC', 'RECAPTHA PUBLIC KEY');
define('reCAPTCHA_PRIVATE', 'RECAPTHA PRIVATE KEY');

// --------------------------------------------------------------------------------------
//
// Stylesheets, l�gg till valbara sheets i arrayen
//
$wsStylesheets = Array(
	'stylesheets/standard.css',
	'stylesheets/style2.css',
	'stylesheets/style3.css'
	);
define('WS_STYLESHEETSELECTION', serialize($wsStylesheets));

//
// Meny f�r webbsidan
//
$wsMenu = Array('Hem' => '.',
    'V�xla stylesheet' => '?p=stylechange',
	'Databasinstallation' => '?p=install',
	'Blogg' => '?p=showblog',
	'Forum' => '?p=topics',
	'Visa k�llkod' => '?p=source');
define('WS_MENU', serialize($wsMenu));

?>
