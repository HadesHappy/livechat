<?php

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 6 May 1998 03:10:00 GMT");

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 3.8.5                 # ||
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

		$USER_LANGUAGE = strtolower($jakuser->getVar("language"));
		$USER_DEPARTMENTS = $jakuser->getVar("departments");

		// Import the language file
		if ($USER_LANGUAGE && file_exists(APP_PATH.JAK_OPERATOR_LOC.'/lang/'.$USER_LANGUAGE.'.php')) {
		    include_once APP_PATH.JAK_OPERATOR_LOC.'/lang/'.$USER_LANGUAGE.'.php';
		} else {
		    include_once(APP_PATH.JAK_OPERATOR_LOC.'/lang/'.JAK_LANG.'.php');
		}

		// Reset vars
		$transfer_msg = $soundjs = $soundmsgjs = '';
		$newclient = $newmsg = $transferid = $convtransfer = 0;
		$loadClients = $jsmsg = $convid = $answers = $typing = false;

		$resnew = $jakdb->select("checkstatus", ["convid", "depid", "operatorid", "operator", "newo", "transferoid", "transferid", "typec", "statusc", "initiated"], ["AND" => ["hide" => 0, "denied" => 0]]);
		
		if (isset($resnew) && !empty($resnew)) {
		
			foreach ($resnew as $row) {
			
				// We have a dead client connection cancel it.
				if ($row['statusc'] && (time() - $row['statusc']) > JAK_CLIENT_LEFT) {
					
					$jakdb->update("sessions", ["status" => 0, "ended" => time()], ["id" => $row['convid']]);
					$jakdb->update("checkstatus", ["hide" => 1], ["convid" => $row['convid']]);

					// We need the client name
					$cname = $jakdb->get("sessions", "name", ["convid" => $row['convid']]);

					$jakdb->insert("transcript", [ 
						"name" => $jkl['g274'],
						"message" => sprintf($jkl['g168'], $cname),
						"convid" => $row['convid'],
						"class" => "notice",
						"plevel" => 2,
						"time" => $jakdb->raw("NOW()")]);
					break;
				}

				// We have an expired client cancel it
				if ($row['statusc'] && (time() - $row['statusc']) > JAK_CLIENT_EXPIRED) {

					$jakdb->update("sessions", ["status" => 0, "ended" => time()], ["id" => $row['convid']]);
					$jakdb->update("checkstatus", ["hide" => 1], ["convid" => $row['convid']]);

					$jakdb->insert("transcript", [ 
						"name" => $jkl['g274'],
						"message" => $jkl['g72'],
						"convid" => $row['convid'],
						"class" => "notice",
						"time" => $jakdb->raw("NOW()")]);
					break;
				}
				
				// We have a transfer, need to display it!
				if ($row['transferoid'] == $userid) {

					$trow = $jakdb->get("transfer", ["fromname", "message"], ["AND" => ["tooid" => $userid, "convid" => $row['convid'], "used" => 0]]);

					if (isset($trow) && !empty($trow)) {
					
						// Display underneath the button
						$transfer_msg = $trow["message"];
						$transferid = $row['transferid'];
						$convtransfer = $row["convid"];
						$convid[] = $row["convid"];
						$jsmsg = sprintf($jkl['g110'], $trow["fromname"]);
						$loadClients = true;

					}
				}

				// Only load if we are in the correct department
				if ($USER_DEPARTMENTS == 0 || $row["depid"] == $USER_DEPARTMENTS || in_array($row["depid"], explode(",", $USER_DEPARTMENTS))) {
					// check for new conversations
					if ($row['operatorid'] == 0 || ($row['operatorid'] == $userid && empty($row['operator']))) {
						$newclient = 1;
						$soundjs = JAK_RING_TONE;
						if ($row['typec']) $typing[] = $row['convid'];
						$convid[] = $row["convid"];
						$loadClients = true;
					}
					if ($row['operatorid'] == $userid && !empty($row['operator'])) {
						$jakdb->update("checkstatus", ["statuso" => time()], ["convid" => $row['convid']]);
						if ($row['typec']) $typing[] = $row['convid'];
						$convid[] = $row["convid"];
						$loadClients = true;
					}
					if ($row['operatorid'] == $userid && $row['newo']) {
						$newmsg = 2;
						$soundmsgjs = JAK_MSG_TONE;
						$answers[] = $row['convid'];
						if ($row['typec']) $typing[] = $row['convid'];
						$convid[] = $row["convid"];
						$loadClients = true;
	 				}
				}
			}
		}
		
		// Reset convlist
		$convlist = array();
		
		// Only go for it if we want to
		if ($loadClients) {
		
			// Now let's get the conversation list
			$new = $updated = $current = $transfer = array();
			$count = 0;
			$dep_title = "";

			$resconv = $jakdb->select("sessions", ["id", "department", "name", "usr_avatar", "operatorname", "status", "initiated", "ended"], ["id" => $convid]);
			
			if (isset($resconv) && !empty($resconv)) {
			
				foreach ($resconv as $row) {
					
					if ($row['status']) {

						// Get all available chats
						if ($row['operatorname'] == "") {
							$new[$count]["name"] = $row['name'];
							$new[$count]["usr_avatar"] = $row['usr_avatar'];
							$new[$count]["convid"] = $row['id'];
							$new[$count]["initiated"] = $row['initiated'];
							$new[$count]["typing"] = false;
							if (!empty($typing) && in_array($row['id'], $typing)) $new[$count]["typing"] = true;
						// Get the transfer into the array as well
						} elseif ($row["id"] == $convtransfer) {
				        	$transfer[$count]["name"] = $row['name'];
							$transfer[$count]["usr_avatar"] = $row['usr_avatar'];
				            $transfer[$count]["convid"] = $row['id'];
				            $transfer[$count]["initiated"] = $row['initiated'];
				            $transfer[$count]["typing"] = false;
				            if (!empty($typing) && in_array($row['id'], $typing)) $transfer[$count]["typing"] = true;
				        // Get updated chats
						} elseif (!empty($answers) && $row['operatorname'] && in_array($row['id'], $answers)) {
							$updated[$count]["name"] = $row['name'];
							$updated[$count]["usr_avatar"] = $row['usr_avatar'];
				            $updated[$count]["convid"] = $row['id'];
				            $updated[$count]["initiated"] = $row['initiated'];
				            $updated[$count]["typing"] = false;
				            if (!empty($typing) && in_array($row['id'], $typing)) $updated[$count]["typing"] = true;
						// Get the rest
						} else {
							$current[$count]["name"] = $row['name'];
							$current[$count]["usr_avatar"] = $row['usr_avatar'];
				            $current[$count]["convid"] = $row['id'];
				            $current[$count]["initiated"] = $row['initiated'];
				            $current[$count]["typing"] = false;
				            if (!empty($typing) && in_array($row['id'], $typing)) $current[$count]["typing"] = true;
						}

						// Now load the department title if we have any
						if (isset($row["department"]) && is_numeric($row["department"])) {

							foreach ($LC_DEPARTMENTS as $d) {
								# code...
								if ($d["id"] == $row["department"]) {
									$dep_title = $d["title"];
								}
							}
						}
					}
			
					if (!$row['status']) {
						if (((time() - $row['ended']) > 300)) {

							$jakdb->update("sessions", ["hide" => 1], ["id" => $row['id']]);
							$jakdb->update("checkstatus", ["hide" => 1], ["convid" => $row['id']]);

							$jakdb->insert("transcript", [ 
								"name" => $jkl['g274'],
								"message" => $jkl['g73'],
								"convid" => $row['id'],
								"class" => "notice",
								"plevel" => 2,
								"time" => $jakdb->raw("NOW()")]);
							
						}
					}

					$count = $count + 1;
				}
		
				shuffle($new);
				shuffle($updated);
				shuffle($current);
				shuffle($transfer);
				sort($new);
				sort($updated);
				sort($current);
				sort($transfer);
				$newTotal = count($new);
				$updatedTotal = count($updated);
				$currentTotal = count($current);
				$transferTotal = count($transfer);

				// Status 1 = New client (confirm or send to contact), 2 = New Message, 3 = Unchanged status
				
				for($i = 0; $i < $newTotal; $i ++ ) {
					$convlist[] = array('status' => 1, 'department' => $dep_title, 'chatid' => $new[$i]["convid"], 'name' => $new[$i]["name"], 'avatar' => $new[$i]["usr_avatar"], 'typing' => $new[$i]["typing"], 'initiated' => $new[$i]["initiated"]);
				}
				for($i = 0; $i < $updatedTotal; $i ++ ) {
					$convlist[] = array('status' => 2, 'department' => $dep_title, 'chatid' => $updated[$i]["convid"], 'name' => $updated[$i]["name"], 'avatar' => $updated[$i]["usr_avatar"], 'typing' => $updated[$i]["typing"], 'initiated' => $updated[$i]["initiated"]);
				}
				for($i = 0; $i < $currentTotal; $i ++ ) {
					$convlist[] = array('status' => 3, 'department' => $dep_title, 'chatid' => $current[$i]["convid"], 'name' => $current[$i]["name"], 'avatar' => $current[$i]["usr_avatar"], 'typing' => $current[$i]["typing"], 'initiated' => $current[$i]["initiated"]);
				}
				for($i = 0; $i < $transferTotal; $i ++ ) {
					$convlist[] = array('status' => 4, 'department' => $dep_title, 'chatid' => $transfer[$i]["convid"], 'name' => $transfer[$i]["name"], 'avatar' => $transfer[$i]["usr_avatar"], 'typing' => $transfer[$i]["typing"], 'initiated' => $transfer[$i]["initiated"]);
				}
			
			}

		}

		if (isset($convlist) && !empty($convlist)) {
			die(json_encode(array('status' => true, 'convlist' => $convlist, 'newclient' => $newclient, 'newmsg' => $newmsg, 'transferid' => $transferid, 'transfermsg' => $transfer_msg, 'transfernotifcation' => $jsmsg, 'url' => BASE_URL)));
		} else {
			die(json_encode(array('status' => false, 'errorcode' => 9)));
		}

	} else {
		die(json_encode(array('status' => false, 'errorcode' => 1)));
	}
}

die(json_encode(array('status' => false, 'errorcode' => 7)));
?>