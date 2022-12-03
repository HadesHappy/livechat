<?php

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 6 May 1998 03:10:00 GMT");

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH                                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2018 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

if (!file_exists('config.php')) die('rest_api config.php not exist');
require_once 'config.php';

include_once APP_PATH.JAK_OPERATOR_LOC.'/include/admin.function.php';

$userid = $loginhash = "";
$errors = array();
$updatepass = false;
if (isset($_REQUEST['userid']) && !empty($_REQUEST['userid']) && is_numeric($_REQUEST['userid'])) $userid = $_REQUEST['userid'];
if (isset($_REQUEST['loginhash']) && !empty($_REQUEST['loginhash'])) $loginhash = $_REQUEST['loginhash'];

if (!empty($userid) && !empty($loginhash)) {

	// Let's check if we are logged in
	$usr = $jakuserlogin->jakCheckrestlogged($userid, $loginhash);

	if ($usr) {

		if (!isset($_REQUEST['name']) || empty($_REQUEST['name'])) {
		    $errors['name'] = true;
		}
		    
		if (!isset($_REQUEST['email']) || $_REQUEST['email'] == '' || !filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL)) {
		    $errors['email'] = true;
		}

		if (!isset($_REQUEST['email']) || jak_field_not_exist_id($_REQUEST['email'], $userid, "user", "email")) {
		    $errors['email'] = true;
		}
		    
		if (!isset($_REQUEST['username']) || !preg_match('/^([a-zA-Z0-9\-_])+$/', $_REQUEST['username'])) {
		   	$errors['username'] = true;
		}
		    
		if (!isset($_REQUEST['username']) || jak_field_not_exist_id($_REQUEST['username'], $userid, "user", "username")) {
		    $errors['username'] = true;
		}
		    
		if (isset($_REQUEST['password']) && !empty($_REQUEST['password']) && (!empty($_REQUEST['new_password']) || !empty($_REQUEST['confirm_new_password']))) {    
			if ($_REQUEST['new_password'] != $_REQUEST['confirm_new_password']) {
			   	$errors['new_password'] = true;
			} elseif (strlen($_REQUEST['new_password']) <= '7') {
			   	$errors['new_password'] = true;
			} else {
			   	$updatepass = true;
			}
		}

		if (count($errors) == 0) {

			// Let's update the password
			if ($updatepass) $jakdb->update("user", ["password" => hash_hmac('sha256', $_REQUEST['new_password'], DB_PASS_HASH)], ["id" => $userid]);

			// Update other fields
			$jakdb->update("user", ["username" => trim($_REQUEST['username']),
				"name" => trim($_REQUEST['name']),
				"email" => filter_var($_REQUEST['email'], FILTER_SANITIZE_EMAIL)], ["id" => $userid]);


			die(json_encode(array('status' => true)));
		} else {
			die(json_encode(array('status' => false, 'errors' => $errors)));
		}	

	} else {
		die(json_encode(array('status' => false, 'errorcode' => 1)));
	}
}

die(json_encode(array('status' => false, 'errorcode' => 7)));
?>