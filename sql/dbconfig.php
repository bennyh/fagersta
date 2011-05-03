<?php
// =================================================================================
//
// SQL konfigurationsfil
// 
// Skapad av: Benny Henrysson
//

//
// Databas inställningar
//
define('DB_HOST', 'HOST');
define('DB_USER', 'USER');
define('DB_PASSWORD', 'PASSWORD');
define('DB_DATABASE', 'DATABASE');
// Prefix för tabeller
define('DB_PREFIX', 'PREFIX');

//
// Inställningar för tabeller
//
define('DBT_User', DB_PREFIX . 'User');
define('DBT_Group', DB_PREFIX . 'Group');
define('DBT_GroupMember', DB_PREFIX . 'GroupMember');
define('DBT_Statistics', DB_PREFIX . 'Statistics');
define('DBT_Article', DB_PREFIX . 'Article');
define('DBT_Topic2Article', DB_PREFIX . 'Topic2Article');
define('DBT_Topic', DB_PREFIX . 'Topic');
define('DBT_Post', DB_PREFIX . 'Post');
define('DBT_Comment', DB_PREFIX . 'Comment');
define('DBT_Tags', DB_PREFIX . 'Tags');

// Lagrade procedurer
define('DBSP_PCreateNewArticle', DB_PREFIX . 'CreateNewArticle');
define('DBSP_PUpdateArticle', DB_PREFIX . 'UpdateArticle');
define('DBSP_PListArticle', DB_PREFIX . 'ListArticle');
define('DBSP_PDisplayArticle', DB_PREFIX . 'DisplayArticle');
define('DBSP_PDeleteArticle', DB_PREFIX . 'DeleteArticle');
define('DBSP_PListTopic', DB_PREFIX . 'ListTopic');
define('DBSP_PDisplayTopicAndArticles', DB_PREFIX . 'DisplayTopicAndArticles');
define('DBSP_PDisplayTopic', DB_PREFIX . 'DisplayTopic');
define('DBSP_PCreateNewUser', DB_PREFIX . 'CreateNewUser');
define('DBSP_PUpdateUser', DB_PREFIX . 'UpdateUser');

// Funktioner
define('DBF_FCheckUserIsOwnerOrAdmin', DB_PREFIX . 'CheckUserIsOwnerOrAdmin');

// Triggers
define('DBT_TInsertUser', DB_PREFIX . 'InsertUser');
define('DBT_TAddArticle', DB_PREFIX . 'AddArticle');
define('DBT_TRemArticle', DB_PREFIX . 'RemArticle');
?>
