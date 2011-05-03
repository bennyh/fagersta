<?php
// =================================================================================
//
// Blogginlägg
// 
// Skapad av: Benny Henrysson
//

// -------------------------------------------------------------------------------------------
//
// Variabler
//
$_SESSION['idUser'] = isset($_SESSION['idUser']) ? $_SESSION['idUser'] : '';
$_SESSION['groupMemberUser'] = isset($_SESSION['groupMemberUser']) ? $_SESSION['groupMemberUser'] : '';
$idPost = isset($_GET['idPost']) ? $_GET['idPost'] : '';

if(!is_numeric($idPost)) {
	$_SESSION['errorMessage'] = "Något har gått fel. Försök igen!";
	header('Location: ' . "?p=home");
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
// Utför databasförfrågning
//
$tablePost = DB_PREFIX . 'Post';
$tableComment = DB_PREFIX . 'Comment';
$tableUser = DB_PREFIX . 'User';
$tableGroup = DB_PREFIX . 'Group';
$tableGroupMember = DB_PREFIX . 'GroupMember';
$tableTags = DB_PREFIX . 'Tags';

$query = <<< EOD
SELECT titleTag
FROM {$tableTags}
WHERE (idPostTag = {$idPost})
ORDER BY titleTag;

SELECT *, COUNT(idPostComment) AS commentCount
FROM ({$tablePost} inner join {$tableUser} ON (idAuthor = idUser)) left outer join {$tableComment}
ON (idPost = idPostComment )
WHERE (idPost = {$idPost})
GROUP BY idPost;

SELECT *
FROM {$tableComment}
WHERE idPostComment = {$idPost}
ORDER BY dateComment ASC;
EOD;

$res = $db->MultiQuery($query);
$results = Array();
$db->StoreResultsFromMultiQuery($results);

$tagLinks = " Taggar: ";
$postContainsTag = FALSE;	

// Den första databasförfrågningen hämtar taggar
while($row = $results[0]->fetch_object()) {
   	$tagLinks .= "<a href='?p=showblog&amp;tag={$row->titleTag}' title='Visa inlägg taggade som {$row->titleTag}'>{$row->titleTag}</a>, ";
}
    
if ($tagLinks == " Taggar: ")
  	$tagLinks = "";
else
    $tagLinks = substr($tagLinks, 0, -2);	

// -------------------------------------------------------------------------------------------
//
// Sidans innehåll
//
$content = '';

// Den andra databasförfrågningen hämtar blogginlägg
$row = $results[1]->fetch_object();

$userIsAuthor = ($row->idUser == $_SESSION['idUser']) ? TRUE : FALSE;

$content .= <<< EOD
<div class="blogpost">
<div class="postheader">
<h3><a href="?p=post&amp;idPost={$row->idPost}" title="Visa inlägg och kommentarer">{$row->titlePost}</a></h3>
</div> <!-- postcontent -->
<div class="postcontent">
{$row->textPost}
</div> <!-- postcontent -->
<div class="postfooter">
EOD;

// Länkar för radering och redigering för inläggsskaparen
if ($userIsAuthor) {
		$content .= <<< EOD
<p>
<a href="?p=editpost&amp;idPost={$row->idPost}" class="modifylink" title="Redigera inlägg">Redigera</a>
<a href="?p=delpost&amp;idPost={$row->idPost}" class="modifylink" title="Radera inlägg">Radera</a>
</p>
EOD;
}

// Länkar för radering för admin
else if ($_SESSION['groupMemberUser'] == 'adm') {
			$content .= <<< EOD
<p>
<a href="?p=delpost&amp;idPost={$row->idPost}" class="modifylink" title="Radera inlägg">Radera</a>
</p>
EOD;
}
	
$content .= <<< EOD
<p>Skrivet av <a href="?p=showblog&amp;idUser={$row->idUser}" title="Visa alla inlägg av {$row->accountUser}">{$row->accountUser}</a> den {$row->datePost}</p>	
<p>{$tagLinks}</p>
</div> <!-- postfooter -->
</div> <!-- blogpost -->
EOD;

// Text visas om kommentarer saknas
if ($row->commentCount == 0) {
	$content .= "<div class='comments'><h3>Kommentarer</h3><p>Det finns inga kommentarer.</p>";
}
else {
	$content .= "<div class='comments'><h3>Kommentarer</h3>";	
}

// Den tredje databasförfrågningen hämtar kommentarer	
while($row = $results[2]->fetch_object()) {
	
	// Länka för radering för admin och inläggsskapare
	$delCommentLink = "";
	if($userIsAuthor || $_SESSION['groupMemberUser'] == 'adm') {
		$delCommentLink =  "<p><a href='?p=delcomment&amp;idComment={$row->idComment}&amp;idPost={$row->idPostComment}' class='modifylink' title='Radera kommentar'>Radera</a></p>";
	}
	
	$email = substr($row->emailComment, 0, strpos($row->emailComment, '@'));
	
	$content .= <<< EOD
<div class="comment">		
<div class="commentheader">	
<h3>{$row->titleComment}</h3>
</div> <!-- commentheader -->
<div class="commentcontent">
{$row->textComment}
</div><!-- commentcontent -->
<div class="commentfooter">
{$delCommentLink}
<p>Skrivet av {$row->authorComment}({$email}) den {$row->dateComment}</p>
</div> <!-- commentfooter -->
</div><!-- comment -->
EOD;
}

$content .= <<< EOD
</div><!-- comments -->
<h3>Skicka en kommentar</h3>
<div class="newcomment">
<form action="?p=commentp" method="post">
<p><input type="hidden" name="idPost" value="{$idPost}" />
<input type="hidden" name="redirect" value="post&amp;idPost={$idPost}" /></p>
<p>Titel:</p>
<p><input type="text" size="40" name="titleComment" /></p>
<p>Kommentar:</p>
<p><textarea name="textComment" rows="10" cols="50"></textarea></p>
<p>Signatur:</p>
<p><input type="text" size="25" name="authorComment" /></p>
<p>Epost:</p>
<p><input type="text" size="25" name="emailComment" /></p>
<p>
<button name="undo" value="undo" type="reset">Återställ</button>
<button name="save" value="save" type="submit">Skicka</button>
</p>
</form>
</div><!-- newcomment -->
EOD;

// -------------------------------------------------------------------------------------------
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
$page->addScriptTagsToHeader(Array('editorScriptsBlog.php'));
$page->printHTMLHeader('Fagersta Blogg');
$page->printPageHeader();
$page->printPageBody($content);
$page->printPageFooter();

?>
