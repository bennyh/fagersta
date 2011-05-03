<?php
// =================================================================================
//
// RSS
// 
// Skapad av: Benny Henrysson
//

// -------------------------------------------------------------------------------------------
//
// Anslut till databas
//
require_once(WS_COMMONPATH . 'CDatabaseController.php');
$db = new CDatabaseController();
$mysqli = $db->Connect();

// -------------------------------------------------------------------------------------------
//
// Utför databasförfrågning
//
$tablePost = DB_PREFIX . 'Post';

$query = <<< EOD
SELECT *
FROM {$tablePost}
ORDER BY datePost DESC;
EOD;

$res = $db->Query($query);

$sitelink = WS_SITELINK;
$content = "";	
while($row = $res->fetch_object()) {
	
	$description = htmlentities($row->textPost);
	
	$content .= <<<EOD
<item>
<title>{$row->titlePost}</title>
<link>{$sitelink}?p=post&amp;idPost={$row->idPost}</link>
<description>{$description}</description>
</item>
EOD;
}

// -------------------------------------------------------------------------------------------
//
// Stänger databas
//
$mysqli->close();

// ---------------------------------------------------------------------------------
//
// Skriver ut sidans innehåll
//
$html = <<<EOD
<?xml version="1.0" encoding="ISO-8859-1" ?>
<rss version="1.0">
<channel>
  <title>Foogler Blog RSS</title>
  <link>{$sitelink}</link>
  <description>Foogler blog</description>
  {$content}
</channel>
</rrs>
EOD;

echo $html;
?>
