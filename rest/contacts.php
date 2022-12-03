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
$newdata = array();
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
	    if (jak_get_access("off_all", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)) {
			$data = $jakdb->select("contacts", ["id", "name", "email", "answered", "sent"], ["ORDER" => ["sent" => "DESC"], "LIMIT" => 30]);
		}

		if (isset($data) && !empty($data)) {

			foreach ($data as $row) {

				if ($row["answered"] == "1980-05-06 00:00:00") {
					$answered = 0;
					$avatar = JAK_FILES_DIRECTORY.'/system.jpg';
				} else {
					$answered = 1;
					$avatar = JAK_FILES_DIRECTORY.'/system.jpg';
				}
				// Write the new data
				$newdata[] = array('id' => $row['id'], 'name' => $row['name'], 'email' => $row['email'], 'answered' => $answered, 'avatar' => $avatar, 'sent' => $row['sent']);
			}
		}

		if (isset($newdata) && !empty($newdata)) {
			die(json_encode(array('status' => true, 'data' => $newdata)));
		} else {
			die(json_encode(array('status' => false, 'errorcode' => 9)));
		}

	} else {
		die(json_encode(array('status' => false, 'errorcode' => 1)));
	}
}

die(json_encode(array('status' => false, 'errorcode' => 7)));
?>