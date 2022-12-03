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

$userid = $loginhash = "";
if (isset($_REQUEST['userid']) && !empty($_REQUEST['userid']) && is_numeric($_REQUEST['userid'])) $userid = $_REQUEST['userid'];
if (isset($_REQUEST['loginhash']) && !empty($_REQUEST['loginhash'])) $loginhash = $_REQUEST['loginhash'];

if (!empty($userid) && !empty($loginhash)) {

	// Let's check if we are logged in
	$usr = $jakuserlogin->jakCheckrestlogged($userid, $loginhash);

	if ($usr) {

		// Select the fields
		$jakuser = new JAK_user($usr);
		// Only the SuperAdmin in the config file see everything
		if ($jakuser->jakSuperadminaccess($userid)) {
			define('JAK_SUPERADMINACCESS', true);
		} else {
			define('JAK_SUPERADMINACCESS', false);
		}

		// Ok, we have check for some data, pull it
	    if (jak_get_access("leads_all", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)) {
			$data = $jakdb->select("sessions", ["[>]departments" => ["department" => "id"]], ["sessions.id", "sessions.usr_avatar", "sessions.name", "sessions.initiated", "sessions.operatorname", "sessions.status", "departments.title"], ["ORDER" => ["sessions.initiated" => "DESC"], "LIMIT" => 30]);
		} else {
			$data = $jakdb->select("sessions", ["[>]departments" => ["department" => "id"]], ["sessions.id", "sessions.usr_avatar", "sessions.name", "sessions.initiated", "sessions.operatorname", "sessions.status", "departments.title"], ["sessions.operatorid" => $userid, "ORDER" => ["sessions.initiated" => "DESC"], "LIMIT" => 30]);
		}

		if (isset($data) && !empty($data)) {
			die(json_encode(array('status' => true, 'data' => $data, 'filepath' => '', 'url' => BASE_URL)));
		} else {
			die(json_encode(array('status' => false, 'errorcode' => 9)));
		}

	} else {
		die(json_encode(array('status' => false, 'errorcode' => 1)));
	}
}

die(json_encode(array('status' => false, 'errorcode' => 7)));
?>