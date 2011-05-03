<?php
// =================================================================================
//
// Skapa ny profil sida
// 
// Skapad av: Benny Henrysson
//

// -------------------------------------------------------------------------------------------
//
// Kontroll av behörighet
//
if(!isset($indexVisited)) die('No direct access to pagecontroller is allowed');

// Recaptcha-html
require_once(WS_COMMONPATH . 'recaptchalib.php');
$publickey = reCAPTCHA_PUBLIC;
$recaptchahtml = recaptcha_get_html($publickey);

// ---------------------------------------------------------------------------------
//
// Sidans innehåll
//
$content = <<<EOD
<div class="profil">
<h1>Skapa ny användarprofil</h1>
<form action="?p=newprofilep" method='POST'>
<input type="hidden" name="redirect" value="login">
<table id="newprofile">
<tr>
<th>Kontonamn</th>
<td><input type="text" name="accountUser" size="40"></td>
</tr>
<tr>
<th>Lösenord</th>
<td><input type="password" name="passwordUser1" size="20"></td>
</tr>
<tr>
<th>Bekräfta lösenord</th>
<td><input type="password" name="passwordUser2" size="20"></td>
</tr>
<tr>
<td></td>
<td>{$recaptchahtml}</td>
</tr>
</table>
<button name="save" value="save" type="submit">Skapa konto</button>
</form>
</div>
EOD;

// ---------------------------------------------------------------------------------
//
// Skriver ut sidans innehåll
//
require_once(WS_COMMONPATH . 'CHtml.php');

$page = new CHtml();

$page->printHTMLHeader('Skapa ny användarprofil');
$page->printPageHeader();
$page->printPageBody($content);
$page->printPageFooter('Benny Henrysson, 2011');

?>
