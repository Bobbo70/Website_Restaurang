<?php
session_start();

require_once(LIBRARY_PATH . "/class.user.php");
$session = new USER();

// if user session is not active(not loggedin) this page will help menu user to redirect to login page
// put this file within secured pages that users (users can't access without login)

if(!$session->is_loggedin())
{
	// session no set redirects to login page
	$session->redirect(SITE_PATH. "admin/login.php");
}