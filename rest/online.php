<?php

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 6 May 1998 03:10:00 GMT");

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 5.0.1                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2021 JAKWEB All Rights Reserved # ||
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

		// Get the current time
		$currentime = time();

		// Get the user fields
		$jakuser = new JAK_user($usr);

		$useronline = array();

		// 5 Minutes ago
		$mino = date('Y-m-d H:i:s', $currentime - 5 * 60);

		// Get the user department
		$usrdep = $jakuser->getVar("departments");

		// Now only get the department for the user
		if (isset($usrdep) && is_numeric($usrdep) && $usrdep != 0) {
			$useronline = $jakdb->select("buttonstats", ["[>]sessions" => ["session" => "session"]], ["buttonstats.id", "buttonstats.referrer", "buttonstats.agent", "buttonstats.hits", "buttonstats.ip", "buttonstats.lasttime", "buttonstats.time", "buttonstats.readtime", "sessions.initiated", "sessions.ended"], ["AND" => ["buttonstats.opid" => [0, $userid], "buttonstats.depid" => $usrdep, "#buttonstats.lasttime[>]" => $mino], "GROUP" => "buttonstats.session", "ORDER" => ["buttonstats.lasttime" => "DESC"], "LIMIT" => 30]);
		} elseif (isset($usrdep) && $usrdep == 0) {
			$useronline = $jakdb->select("buttonstats", ["[>]sessions" => ["session" => "session"]], ["buttonstats.id", "buttonstats.referrer", "buttonstats.agent", "buttonstats.hits", "buttonstats.ip", "buttonstats.lasttime", "buttonstats.time", "buttonstats.readtime", "sessions.initiated", "sessions.ended"], ["AND" => ["buttonstats.opid" => [0, $userid], "buttonstats.lasttime[>]" => $mino], "GROUP" => "buttonstats.session", "ORDER" => ["buttonstats.lasttime" => "DESC"], "LIMIT" => 30]);
		} elseif (isset($usrdep)) {
			$useronline = $jakdb->select("buttonstats", ["[>]sessions" => ["session" => "session"]], ["buttonstats.id", "buttonstats.referrer", "buttonstats.agent", "buttonstats.hits", "buttonstats.ip", "buttonstats.lasttime", "buttonstats.time", "buttonstats.readtime", "sessions.initiated", "sessions.ended"], ["AND" => ["buttonstats.opid" => [0, $userid], "buttonstats.depid" => explode(",",$usrdep), "#buttonstats.lasttime[>]" => $mino], "GROUP" => "buttonstats.session", "ORDER" => ["buttonstats.lasttime" => "DESC"], "LIMIT" => 30]);
		}

		if (isset($useronline) && !empty($useronline)) {

			$uo = array();
			foreach ($useronline as $v) {

				if (empty($v["initiated"])) $v["initiated"] = 0;
				if (empty($v["ended"])) $v["ended"] = 0;
				
				$uo[] = $v;
			}

			die(json_encode(array('status' => true, 'useronline' => $uo)));
		} else {
			die(json_encode(array('status' => false, 'errorcode' => 9)));
		}

	} else {
		die(json_encode(array('status' => false, 'errorcode' => 1)));
	}
}

die(json_encode(array('status' => false, 'errorcode' => 7)));
?>