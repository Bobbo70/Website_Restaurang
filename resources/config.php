<?php
/* Important to include config file in every page you need to access to these setting. */
$config = array (
	"img" => array (
		"content" => $_SERVER["DOCUMENT_ROOT"] . "/img/content",
		"layout" => $_SERVER["DOCUMENT_ROOT"] . "/img/layout"
	),
	"recaptcha" => array (
		"publickey" => "6Lft_UsUAAAAAKN5iIBjJ6UZhvLpSbfJT1NVW5Ov",
		"privatkey" => "?????????????????????"
	),	
);

define("SITE_PATH" , "http://www.restaurangkarl.se/");

/* Resources path */
defined ("INCLUDE_PATH")
	or define ("INCLUDE_PATH" , realpath(dirname(__FILE__) . '/include'));

defined ("MAILER_PATH")
	or define ("MAILER_PATH" , realpath(dirname(__FILE__) . '/phpmailer/vendor'));
	
defined ("LIBRARY_PATH")
	or define ("LIBRARY_PATH" , realpath(dirname(__FILE__) . '/library'));
	
defined ("TEMPLATES_PATH")
	or define ("TEMPLATES_PATH" , realpath(dirname(__FILE__) . '/templates'));

/* Error reporting */
error_reporting(-1);
ini_set("display_errors",1);
ini_set("output_buffering",0);
?>