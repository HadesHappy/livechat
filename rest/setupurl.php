<?php

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 6 May 1998 03:10:00 GMT");

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 4.0.5                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2021 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

if (!file_exists('config.php')) die('rest_api config.php not exist');
require_once 'config.php';

$lcurl = "";
if (isset($_REQUEST['url']) && !empty($_REQUEST['url'])) $lcurl = $_REQUEST['url'];
if (isset($_REQUEST['device']) && !empty($_REQUEST['device'])) $device = $_REQUEST['device'];
if (isset($_REQUEST['token']) && !empty($_REQUEST['token'])) $token = $_REQUEST['token'];

if (!empty($lcurl) && filter_var($lcurl, FILTER_VALIDATE_URL)) {
	      
	// Return the json object
	die(json_encode(array('status' => true, 'ios' => "", 'android' => "", 'title' => "Live Chat 3")));
}

die(json_encode(array('status' => false, 'errorcode' => 12)));
?>