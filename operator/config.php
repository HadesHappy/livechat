<?php

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 4.0.5                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2021 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

// Do not go any further if install folder still exists
if (is_dir('../install')) die('Please delete or rename install folder.');

// The DB connections data
require_once '../include/db.php';

// Get the real stuff
require_once '../config.php';

define('BASE_URL_ADMIN', BASE_URL);
define('BASE_URL_ORIG', str_replace('/'.JAK_OPERATOR_LOC.'/', '/', BASE_URL));
define('BASE_PATH_ORIG', str_replace('/'.JAK_OPERATOR_LOC.'', '/', _APP_MAIN_DIR));

// Include some functions for the ADMIN Area
include_once 'include/admin.function.php';
include_once '../class/class.paginator.php';

// Get the license file
require_once '../class/class.jaklic.php';
$jaklic = new JAKLicenseAPI();

// Check if user is logged in
$jakuserlogin = new JAK_userlogin();
$jakuserrow = $jakuserlogin->jakChecklogged();
$jakuser = new JAK_user($jakuserrow);
if ($jakuser) {
	define('JAK_USERID', $jakuser->getVar("id"));
} else {
	define('JAK_USERID', false);
}

// Update last activity from this user
if (JAK_USERID) $jakuserlogin->jakUpdatelastactivity(JAK_USERID);
?>