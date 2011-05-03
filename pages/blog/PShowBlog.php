<?php
// =================================================================================
//
// Indexsida med blogg
// 
// Skapad av: Benny Henrysson
//

// -------------------------------------------------------------------------------------------
//
// Variabler
//
$tag = isset($_GET['tag']) ? $_GET['tag'] : '';
$idUser = isset($_GET['idUser']) ? $_GET['idUser'] : '';
$_SESSION['idUser'] = isset($_SESSION['idUser']) ? $_SESSION['idUser'] : '';
$_SESSION['groupMemberUser'] = isset($_SESSION['groupMemberUser']) ? $_SESSION['groupMemberUser'] : '';

// -------------------------------------------------------------------------------------------
//
// Anslut till databas
//
require_once(WS_COMMONPATH . 'CDatabaseController.php');
$db = new CDatabaseController();
$mysqli = $db->Connect();
$db2 = new CDatabaseController();
$mysqli2 = $db2->Connect();

// -------------------------------------------------------------------------------------------
//
// Utför databasförfrågning
//
$tablePost = DB_PREFIX . 'Post';
$tableComment = DB_PREFIX . 'Comment';
$tableUser = DB_PREFIX . 'User';
$tableGroupMember = DB_PREFIX . 'GroupMember';
$tableTags = DB_PREFIX . 'Tags';

$query = <<< EOD
SELECT *, COUNT(idPostComment) AS commentCount
FROM ({$tablePost} inner join {$tableUser} ON (idAuthor = idUser)) left outer join {$tableComment}
ON (idPost = idPostComment )
GROUP BY idPost
ORDER BY datePost DESC;

SELECT *
FROM ({$tableUser} join {$tableGroupMember} ON GroupMember_idUser = idUser)
where (GroupMember_idGroup = 'aut')
ORDER BY accountUser;

SELECT idPost, titlePost
FROM {$tablePost}
ORDER BY datePost DESC
LIMIT 10;

SELECT titleTag, COUNT(idTag) as tagCount
FROM {$tableTags}
GROUP BY titleTag;

SELECT COUNT(idPost) AS nrOfPosts
FROM {$tablePost}
WHERE datePost >= DATE_SUB(CURDATE(), INTERVAL 10 DAY);

SELECT COUNT(idPost) AS nrOfPosts
FROM {$tablePost}
WHERE datePost >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH);

SELECT COUNT(idPost) AS nrOfPosts
FROM {$tablePost}
WHERE datePost >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR);

SELECT COUNT(idPost) AS nrOfPosts
FROM {$tablePost};

EOD;

$res = $db->MultiQuery($query);
$results = Array();
$db->StoreResultsFromMultiQuery($results);


// -------------------------------------------------------------------------------------------
//
// Sidans innehåll
//

$content = "<p><a href='?p=newpost' class='newpostlink' title='Posta nytt inlägg'>Nytt inlägg</a><p>";

// Den första databasförfrågningen hämtar blogginlägg
while($row = $results[0]->fetch_object()) {
	

	// Query2 hämtar taggar
	$query2 = <<< EOD
SELECT titleTag
FROM {$tableTags}
WHERE (idPostTag = {$row->idPost})
ORDER BY titleTag;
EOD;
	
	$res2 = $db2->Query($query2);
	
    $tagLinks = " Taggar: ";
	$postContainsTag = FALSE;	
    
    while($row2 = $res2->fetch_object()) {
    	if ($tag == $row2->titleTag)
    		$postContainsTag = TRUE;
    	
    	$tagLinks .= "<a href='?p=showblog&amp;tag={$row2->titleTag}' title='Visa inlägg taggade som {$row2->titleTag}'>{$row2->titleTag}</a>, ";
    }
    
    if ($tagLinks == " Taggar: ")
    	$tagLinks = "";
    else
    	$tagLinks = substr($tagLinks, 0, -2);
    	
    $res2->close();
	
	// Visar alla inlägg om $idUser ej är satt, annars aktuell användares inlägg.
	if ( ($idUser == "" && $tag == "") || ($idUser == $row->idUser) || ($postContainsTag) ) {
		
		// Formulering av kommentarlänkar
		$commentLink= "<a href='?p=post&amp;idPost={$row->idPost}' title='Visa inlägg och kommentarer'>{$row->commentCount} kommentarer</a>";
		if ($row->commentCount == 0)
			$commentLink = "<a href='?p=post&amp;idPost={$row->idPost}' title='Visa inlägg och kommentarer'>Kommentera</a>";
		if ($row->commentCount == 1)
			$commentLink = "<a href='?p=post&amp;idPost={$row->idPost}' title='Visa inlägg och kommentarer'>{$row->commentCount} kommentar</a>";
		
		$content .= <<< EOD
<div class="blogpost">
<div class="postheader">
<h3><a href="?p=post&amp;idPost={$row->idPost}" title="Visa inlägg och kommentarer">{$row->titlePost}</a></h3>
</div> <!-- postheader -->
<div class="postcontent">
{$row->textPost}
</div> <!-- postcontent -->
<div class="postfooter">
<p>Skrivet av <a href="?p=showblog&amp;idUser={$row->idUser}" title="Visa alla inlägg av {$row->accountUser}">{$row->accountUser}</a> den {$row->datePost}</p>
EOD;

		// Länkar för radering och redigering för inläggsskaparen
		if ($row->idUser == $_SESSION['idUser']) {
			$content .= <<< EOD
<p>
<a href="?p=editpost&amp;idPost={$row->idPost}" class="modifylink" title="Redigera inlägg">Redigera</a>
<a href="?p=delpost&amp;idPost={$row->idPost}" class="modifylink" title="Radera inlägg">Radera</a>
</p>
EOD;
		}
		// Länkar för radering för admin
		if ($_SESSION['groupMemberUser'] == 'adm') {
			$content .= <<< EOD
<p>
<a href="?p=delpost&amp;idPost={$row->idPost}" class="modifylink" title="Radera inlägg">Radera</a>
</p>
EOD;
		}
		
		$content .= <<< EOD
<p>{$commentLink}</p>
<p>{$tagLinks}</p>
</div> <!-- postfooter -->
</div> <!-- blogpost -->
EOD;
	}
}

if ($content == '')
	$content .= '<p>Det finns inga inlägg att visa</p>';

$rightCol = "<div class='sidemenu'><h3>Författare</h3><ul>";

// Den andra databasförfrågningen hämtar användarinfo
while($row = $results[1]->fetch_object()) {
	$rightCol .= <<< EOD
<li>
<p><a href="?p=showblog&amp;idUser={$row->idUser}" title="Visa alla inlägg av {$row->accountUser}">{$row->accountUser}</a></p>
<p>{$row->nameUser}</p>
<p>{$row->jobDescriptionUser}</p>
</li>
EOD;
}
	
$rightCol .= <<< EOD
</ul>
</div> <!-- sidemenu -->
<div class='sidemenu'>
<h3>Senaste inlägg</h3>
<ul>
EOD;

// Den tredje databasförfrågningen hämtar senaste inläggen
while($row = $results[2]->fetch_object()) {
	$rightCol .= <<< EOD
<li>
<p><a href="?p=post&amp;idPost={$row->idPost}" title="Visa inlägg och kommentarer">{$row->titlePost}</a></p>
</li>
EOD;
}
$rightCol .= <<< EOD
</ul>
</div> <!-- sidemenu -->
EOD;

$rightCol .= <<< EOD
<div class='sidemenu'>
<h3>Taggar</h3>
<ul>
EOD;

// Den fjärde databasförfrågningen hämtar taggar
while($row = $results[3]->fetch_object()) {
	$rightCol .= <<< EOD
<li>
<p><a href="?p=showblog&amp;tag={$row->titleTag}" title="Visa inlägg taggade som {$row->titleTag}">{$row->titleTag}</a> ({$row->tagCount})</p>
</li>
EOD;
}

$rightCol .= <<< EOD
</ul>
</div> <!-- sidemenu -->
EOD;

$row = $results[4]->fetch_object();

$postsLast10Days = $row->nrOfPosts;

$row = $results[5]->fetch_object();

$postsLastMonth = $row->nrOfPosts;

$row = $results[6]->fetch_object();

$postsLastYear = $row->nrOfPosts;

$row = $results[7]->fetch_object();

$postsTotal = $row->nrOfPosts;

$rightCol .= <<< EOD
<div class='sidemenu'>
<h3>Statistik</h3>
<ul>
<li>
<p>Antal inlägg i bloggen:</p>
</li>
<li>
<p>Senaste 10 dagarna: {$postsLast10Days}</p>
</li>
<li>
<p>Senaste månaden: {$postsLastMonth}</p>
</li>
<li>
<p>Senaste året: {$postsLastYear}</p>
</li>
<li>
<p>Totalt: {$postsTotal}</p>
</li>
</ul>
</div> <!-- sidemenu -->
EOD;

// -------------------------------------------------------------------------------------------
//
// Stänger databas
//
$mysqli->close();
$mysqli2->close();

// ---------------------------------------------------------------------------------
//
// Skriver ut sidans innehåll
//

require_once(WS_COMMONPATH . 'CHtml.php');

$page = new CHtml();

$page->addRightCol($rightCol);

$page->printHTMLHeader('Fagersta Blogg');
$page->printPageHeader();
$page->printPageBody($content);
$page->printPageFooter();

?>
