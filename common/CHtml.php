<?php
// ========================================================================================
//
// Klass CHtml
// 
//

class CHtml {

    // ------------------------------------------------------------------------------------
    //
    // Interna variabler
    //
    protected $iMenu;
    protected $iStylesheet;
    protected $iLeftCol;
    protected $iRightCol;
    protected $iScriptTags;

    // ------------------------------------------------------------------------------------
    //
    // Konstruktor
    //
    public function __construct($aStylesheet = WS_STYLESHEET) {
    	$this->iMenu = unserialize(WS_MENU);
    	$this->iLeftCol = "";
    	$this->iRightCol = "";
    	$this->iStylesheet = $aStylesheet;
    	$this->iScriptTags = "";
    }


    // ------------------------------------------------------------------------------------
    //
    // Destruktor
    //
    public function __destruct() {
        ;
    }

    // ------------------------------------------------------------------------------------
    //
    // HTML Header
    //
    public function printHTMLHeader($aTitle=WS_TITLE, $aCharset=WS_CHARSET ) {
    	
    	$scriptTags = empty($this->iScriptTags) ? "" : $this->iScriptTags;

        $html = <<<EOD
<!DOCTYPE html>
    <html lang="se"> 
	<head>
		<meta charset="{$aCharset}" />
		<link rel="stylesheet" href="{$this->iStylesheet}" type="text/css" media="screen" />
		<title>{$aTitle}</title>
		{$scriptTags}
	</head>

EOD;
        echo $html;
    }        
  

    // ------------------------------------------------------------------------------------
    //
    // Header
    //
    public function printPageHeader() {
    
        $menu = <<<EOD
<div id="menu">
<ul>

EOD;
        
        foreach($this->iMenu as $key => $value) {
            $menu .= <<<EOD
            				<li><a href='{$value}'>{$key}</a></li>

EOD;
		}
        $menu .= <<<EOD
        			</ul>
        			</div><!-- menu -->
EOD;

		$loginMenu = $this->getLoginMenu();
		$title = WS_TITLE;
        $html = <<<EOD
        <body>
        	<div id="page">
        		
        		<div id="header">
        			
        			{$loginMenu}
        			<h1>{$title}</h1>
        			
        			{$menu}
        			
        		</div><!-- header -->

EOD;
        echo $html;  
    }


    // ------------------------------------------------------------------------------------
    //
    // Body
    //
    public function printPageBody($aContent) {
    	$htmlErrorMessage = $this->getErrorMessage();
    	
    	// Tar hand om kolumner
    	$cols = "";
    	if ($this->iLeftCol != "") {
    		$cols .= 'l';
    	}
    	if ($this->iRightCol != "") {
    		$cols .= 'r';
    	}   	
    	$leftCol = empty($this->iLeftCol) ? "" : "<div class='leftcol{$cols}'>{$this->iLeftCol}</div><!-- leftcol -->";
        $rightCol = empty($this->iRightCol) ? "" : "<div class='rightcol{$cols}'>{$this->iRightCol}</div><!-- rightcol -->";
    	$content = empty($aContent) ? "" : "<div class='content{$cols}'>{$htmlErrorMessage}{$aContent}</div><!-- content -->";
    	
        $html = <<<EOD
			<div id="pagebody">
{$leftCol}
{$rightCol}
{$content}
			</div><!-- pagebody -->

EOD;
        echo $html;
    }


    // ------------------------------------------------------------------------------------
    //
    // Footer
    //
    public function printPageFooter($aFooterText = WS_FOOTER) {
    	
    	// Valideringslänkar
    	$pagePath = "http://" . $_SERVER['HTTP_HOST'] . ":" . $_SERVER['SERVER_PORT'] . htmlentities($_SERVER['REQUEST_URI']);
    	$valCSSLink = "<a href='http://jigsaw.w3.org/css-validator/validator?uri={$pagePath}'>CSS</a>";
    	$valHTMLLink = "<a href='http://validator.w3.org/check?uri={$pagePath}'>HTML</a>";
    	$valLinksLink = "<a href='http://validator.w3.org/checklink?uri={$pagePath}'>Links</a>";

        $html = <<<EOD
        		<div id="footer">
        		<p>{$valLinksLink}{$valCSSLink}{$valHTMLLink}</p>
        		<p>{$aFooterText}</p>
        		
        		</div><!-- footer -->
		</div>
	</body>
</html>
EOD;
        echo $html;
    }

    public function getLoginMenu() {
    	$loginMenu = "";
    	
    	// Länkar för inloggade
    	if(isset($_SESSION['accountUser'])) {
    		
    		// Länk för administratörer
    		$admHtml = "";
        	if(isset($_SESSION['groupMemberUser']) && $_SESSION['groupMemberUser'] == 'adm') {
        		$admHtml = "<a href='?p=admin'>Admin</a> ";
        	}
        
    		$loginMenu .= <<<EOD
<p><a href="?p=profile">{$_SESSION['accountUser']}</a> 
{$admHtml}
<a href="?p=logout">Logga ut</a></p>
EOD;
    	}
    	
    	// Länk för icke inloggade
    	else {
    	$loginMenu .= <<<EOD
<p><a href="?p=login">Logga in</a></p>
EOD;
		}
		
		$html = <<<EOD
<div class="login">
{$loginMenu}
</div><!-- login -->
EOD;
		return $html;
	}
	
	
    // ----------------------------------------------------------------------
    //
    // Skapa felmeddelanden
    //
    public function getErrorMessage() {

        $html = "";

        if(isset($_SESSION['errorMessage'])) {
        
            $html = <<<EOD
<div class='errormessage'>
{$_SESSION['errorMessage']}
</div><!-- errormessage -->
EOD;
            unset($_SESSION['errorMessage']);
        }
        return $html;   
    }	
    
    // ----------------------------------------------------------------------
    //
    //  Addera vänsterkolumn
    //
    public function addLeftCol($aLeftCol) {

        $this->iLeftCol = $aLeftCol;
  
    }   
    
    // ----------------------------------------------------------------------
    //
    //  Addera högerkolumn
    //
    public function addRightCol($aRightCol) {

        $this->iRightCol = $aRightCol;
  
    }  
    
    // ----------------------------------------------------------------------
    //
    //  Addera scripttaggar i headern
    //
    public function addScriptTagsToHeader($aScriptTags) {

    	$scriptTags = "";
    	foreach($aScriptTags as $file) {
    		require(WS_JSPATH . $file);
    		$scriptTags .= $tags;
    	}
        $this->iScriptTags = $scriptTags;
    }  
    
	
} // Class

?>
