<?php
// ===========================================================================================
//
// index.php
//
// An implementation of a PHP frontcontroller for a web-site.
//
// All requests passes through this page, for each request is a pagecontroller choosen.
// The pagecontroller results in a response or a redirect.
//

// -------------------------------------------------------------------------------------------
//
// Require the files that are common for all pagecontrollers.
//
session_start();
require_once('common/config.php');

$indexVisited = TRUE;

// -------------------------------------------------------------------------------------------
//
// Redirect to the choosen pagecontroller.
//
$page = isset($_GET['p']) ? $_GET['p'] : 'home';

switch($page) {

    case 'home': require_once(WS_PAGEPATH .'index/PIndex.php'); break;

    case 'source': require_once(WS_COMMONPATH . 'source.php'); break;  

    case 'stylechange': require_once(WS_PAGEPATH . 'stylechange/PStyleChange.php'); break;	
    	
    //Database install pages
    case 'install': require_once(WS_PAGEPATH . 'install/PInstall.php'); break;
    	
    case 'installp': require_once(WS_PAGEPATH . 'install/PInstallProcess.php'); break;

    //Account & login pages    	
    case 'login': require_once(WS_PAGEPATH . 'login/PLogin.php'); break;
    	
    case 'loginp': require_once(WS_PAGEPATH . 'login/PLoginProcess.php'); break;
    	
    case 'logout': require_once(WS_PAGEPATH . 'login/PLogoutProcess.php'); break;	
    	
    case 'profile': require_once(WS_PAGEPATH . 'login/PProfile.php'); break;
     	 
    case 'admin': require_once(WS_PAGEPATH . 'login/PUserAdmin.php'); break; 	 
    	
    case 'editprofilep': require_once(WS_PAGEPATH . 'login/PEditProfileProcess.php'); break; 
    	
    case 'newprofile': require_once(WS_PAGEPATH . 'login/PNewProfile.php'); break;
    	
    case 'newprofilep': require_once(WS_PAGEPATH . 'login/PNewProfileProcess.php'); break;	

    //Forum pages
    case 'showarticle': require_once(WS_PAGEPATH . 'forum/PShowArticle.php'); break;	
    	
    case 'newarticle': require_once(WS_PAGEPATH . 'forum/PNewArticle.php'); break;
     	 
    case 'editarticle': require_once(WS_PAGEPATH . 'forum/PEditArticle.php'); break; 	 

    case 'savearticle': require_once(WS_PAGEPATH . 'forum/PSaveArticle.php'); break;  
    	
    case 'deletearticle': require_once(WS_PAGEPATH . 'forum/PDeleteArticle.php'); break;
    	
    case 'newtopic': require_once(WS_PAGEPATH . 'forum/PNewTopic.php'); break;
    	
    case 'topics': require_once(WS_PAGEPATH . 'forum/PTopics.php'); break;
    	
    case 'showtopic': require_once(WS_PAGEPATH . 'forum/PShowTopic.php'); break;	
    	
    case 'about': require_once(WS_PAGEPATH . 'forum/PAbout.php'); break;	    	

    //Blog pages	
    case 'showblog': require_once(WS_PAGEPATH . 'blog/PShowBlog.php'); break;
    
    case 'post': require_once(WS_PAGEPATH . 'blog/PShowPost.php'); break;
    	
    case 'newpost': require_once(WS_PAGEPATH . 'blog/PNewPost.php'); break;	
    	
    case 'newpostp': require_once(WS_PAGEPATH . 'blog/PNewPostProcess.php'); break;
    	
    case 'editpost': require_once(WS_PAGEPATH . 'blog/PEditPost.php'); break;	
    	
    case 'editpostp': require_once(WS_PAGEPATH . 'blog/PEditPostProcess.php'); break;
    	
    case 'delpost': require_once(WS_PAGEPATH . 'blog/PDelPost.php'); break;
    	
    case 'delcomment': require_once(WS_PAGEPATH . 'blog/PDelComment.php'); break;		
    	
    case 'commentp': require_once(WS_PAGEPATH . 'blog/PInsertCommentProcess.php'); break; 		
    	
    case 'rss': require_once(WS_PAGEPATH . 'blog/PRss.php'); break;

    	
    default: require_once(WS_PAGEPATH .'index/PIndex.php'); break;
}

?>
