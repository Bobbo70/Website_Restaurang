<?php
// databas class
require_once(realpath(dirname(__FILE__) . "/../resources/config.php"));
// session fil ger begränsa åtkomst
require_once(INCLUDE_PATH . "/session.php");
require_once(LIBRARY_PATH . "/class.user.php");

// get database connection
$user_logout= new USER();

// login-logout
if($user_logout->is_loggedin()!=""){
	$user_logout->redirect(SITE_PATH. "cms/index.php");
}
if(isset($_GET['logout']) && $_GET['logout']=='true'){
	$user_logout->logout();
	$user_logout->redirect(SITE_PATH. "admin/login.php?logout");
}