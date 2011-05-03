<?php
// ========================================================================================
//
// Klass CDatabaseController
// 
//

require_once(WS_SQLPATH . 'dbconfig.php');

class CDatabaseController {

    // ------------------------------------------------------------------------------------
    //
    // Interna variabler
    //
    protected $iMysqli;

    // ------------------------------------------------------------------------------------
    //
    // Konstruktor
    //
    public function __construct() {
    	
        $this->iMysqli = FALSE;        
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
    // Anslut till databas
    //
    public function Connect() {

        $this->iMysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

        if (mysqli_connect_error()) {
               echo "Connect to database failed: ".mysqli_connect_error()."<br>";
               exit();
        }

        return $this->iMysqli;
    }
    
    // ------------------------------------------------------------------------------------
    //
    // Utför en query
    //
     public function Query($aQuery) {

        $res = $this->iMysqli->query($aQuery) 
            or die("Could not query database, query =<br/><pre>{$aQuery}</pre><br/>{$this->iMysqli->error}");

        return $res;
    }
    
    // ------------------------------------------------------------------------------------
    //
    // Utför en Multiquery
    //
    public function MultiQuery($aQuery) {
    	
    	$res = $this->iMysqli->multi_query($aQuery) 
    		or die("Could not query database");

    	return $res;
	}
	
	// ------------------------------------------------------------------------------------
    //
    // Spara resultat från query
    //
    public function StoreResultsFromMultiQuery(&$aResults) {
    	
    	$mysqli = $this->iMysqli;
    	
    	$i = 0;
        do {
            $aResults[$i++] = $mysqli->store_result();
        } while($mysqli->next_result());
        
        // Vid databasfel
        !$mysqli->errno 
            or die("<p>Failed retrieving resultsets.</p><p>Query =<br/><pre>{$query}</pre><br/>Error code: {$this->iMysqli->errno} ({$this->iMysqli->error})</p>");
   
    }
    
	// ------------------------------------------------------------------------------------
    //
    // Hämta antal statement från query
    //
    public function RetrieveAndIgnoreResultsFromMultiQuery() {

        $mysqli = $this->iMysqli;
        
        $statements = 0;
        do {
            $res = $mysqli->store_result();
            $statements++;
        } while($mysqli->next_result());

        return $statements;
    }    
    
    // ------------------------------------------------------------------------------------
    //
    // Ladda databasquery från fil
    //
    public function LoadSQL($aFile) {
        
        $mysqli = $this->iMysqli;
        require(WS_SQLPATH . $aFile);
        return $query;
    }

} // Class

?>
