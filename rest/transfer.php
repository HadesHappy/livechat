<?php

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 6 May 1998 03:10:00 GMT");

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 5.0.2                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2022 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

if (!file_exists('config.php')) die('rest_api config.php not exist');
require_once 'config.php';

$userid = $loginhash = $chatid = "";
$errors = $operator = $responses = $udepl = array();
$task = false;
$timeout = 180;
if (isset($_REQUEST['userid']) && !empty($_REQUEST['userid']) && is_numeric($_REQUEST['userid'])) $userid = $_REQUEST['userid'];
if (isset($_REQUEST['loginhash']) && !empty($_REQUEST['loginhash'])) $loginhash = $_REQUEST['loginhash'];
if (isset($_REQUEST['chatid']) && !empty($_REQUEST['chatid'])) $chatid = $_REQUEST['chatid'];
if (isset($_REQUEST['task']) && !empty($_REQUEST['task'])) $task = $_REQUEST['task'];

if (!empty($userid) && !empty($loginhash) && !empty($chatid) && is_numeric($chatid)) {

	// Let's check if we are logged in
	$usr = $jakuserlogin->jakCheckrestlogged($userid, $loginhash);

	if ($usr) {

		// Select the user fields
		$jakuser = new JAK_user($usr);

		$USER_LANGUAGE = strtolower($jakuser->getVar("language"));

		// Import the language file
		if ($USER_LANGUAGE && file_exists(APP_PATH.JAK_OPERATOR_LOC.'/lang/'.$USER_LANGUAGE.'.php')) {
		    include_once APP_PATH.JAK_OPERATOR_LOC.'/lang/'.$USER_LANGUAGE.'.php';
		} else {
		    include_once(APP_PATH.JAK_OPERATOR_LOC.'/lang/'.JAK_LANG.'.php');
		}

		// Send standard responds
		if ($task == "respond") {

			if (empty($_REQUEST['respondid'])) {
			    $errors['respondid'] = true;
			}

			if (count($errors) == 0) {

				$row = $jakdb->get("sessions", ["name", "operatorname", "department"], ["id" => $chatid]);

				// get the responses from the file specific for this client
				foreach($LC_RESPONSES as $r) {
				
					if ($r["id"] == $_REQUEST["respondid"]) {
				
						$phold = array("%operator%","%client%","%email%");
						$replace   = array($row['operatorname'], $row["name"], JAK_EMAIL);
						$message = str_replace($phold, $replace, $r["message"]);
						
					}
				
				}

				if (isset($message) && !empty($message)) {

					$cs = $jakdb->get("checkstatus", "convid", ["convid" => $chatid]);

					if (isset($row) && !empty($row)) {
		
						// We sanitize the input
						$message = strip_tags($message);
						$message = filter_var($message, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
						$message = trim($message);

						$jakdb->insert("transcript", [ 
							"name" => $jakuser->getVar("name"),
							"message" => $message,
							"user" => $userid.'::'.$jakuser->getVar("username"),
							"operatorid" => $userid,
							"convid" => $cs,
							"quoted" => 0,
							"class" => "admin",
							"time" => $jakdb->raw("NOW()")]);

						// Update the status after answer
						$jakdb->update("checkstatus", ["newc" => 1, "typeo" => 0, "newo" => 0, "statuso" => time()], ["convid" => $cs]);

						// Transfer success display message
						die(json_encode(array('status' => true, 'task' => "respond")));
					}
				}

			} else {
				die(json_encode(array('status' => false, 'errors' => $errors)));
			}

		}

		// allow uploading files for the client
		if ($task == "file") {

			$sendfile = $jakdb->get("checkstatus", "files", ["convid" => $chatid]);

			if ($sendfile == 1) {
				$jakdb->update("checkstatus", ["files" => 0], ["convid" => $chatid]);
				// File upload off 
				die(json_encode(array('status' => true, 'task' => "files", 'upload' => false)));
					
			} else {
				$jakdb->update("checkstatus", ["files" => 1], ["convid" => $chatid]);
				// File upload on 
				die(json_encode(array('status' => true, 'task' => "files", 'upload' => true)));
			}

		}

		// End the sessions
		if ($task == "end") {

			// check to see if conversation has to be stored
			$row = $jakdb->get("sessions", ["id", "name", "email"], ["id" => $chatid]);

			$jakdb->update("sessions", ["status" => 0, "ended" => time()], ["id" => $row['id']]);
		    $jakdb->update("checkstatus", ["hide" => 1], ["convid" => $row['id']]);

		    $jakdb->insert("transcript", [ 
		         "name" => $jakuser->getVar("name"),
		         "message" => $jkl['g63'],
		         "user" => $jakuser->getVar("id").'::'.$jakuser->getVar("username"),
		         "operatorid" => $jakuser->getVar("id"),
		         "convid" => $row['id'],
		         "class" => "notice",
		        "time" => $jakdb->raw("NOW()")]);

			die(json_encode(array('status' => true, 'task' => "endsession")));

		}

		// Send a knock knock
		if ($task == "knock") {

			$result = $jakdb->update("checkstatus", ["knockknock" => 1], ["convid" => $chatid]);

			die(json_encode(array('status' => true, 'task' => "knock")));

		}

		// Send the transfer request
		if ($task == "transfer") {

			if ($jakuser->getVar("transferc") == 0) die(json_encode(array('status' => false, 'errorcode' => 8)));

			if (empty($_REQUEST['operator'])) {
			    $errors['operator'] = true;
			}

			if (empty($_REQUEST['transfermsg'])) {
			    $errors['transfermsg'] = true;
			}

			if (count($errors) == 0) {

				// check to see if conversation has to be stored
		        $toname = $jakdb->get("user", "name", ["id" => $_REQUEST['operator']]);

		    	$msg = strip_tags($_REQUEST['transfermsg']);

		        $jakdb->insert("transfer", ["convid" => $chatid, "fromoid" => $jakuser->getVar("id"), "fromname" => $jakuser->getVar("name"), "tooid" => $_REQUEST['operator'], "toname" => $toname, "message" => $msg, "created" => $jakdb->raw("NOW()")]);

		        $lastid = $jakdb->id();
        	
	        	if ($lastid) {
	                $jakdb->update("checkstatus", ["transferoid" => $_REQUEST['operator'], "transferid" => $lastid], ["convid" => $chatid]);
	            }
				
				// Transfer success display message and go back to queue
				die(json_encode(array('status' => true, 'task' => "transfer")));

			} else {
				die(json_encode(array('status' => false, 'errors' => $errors)));
			}

		}

		// Get the predefined messages plus online operators
		if ($jakuser->getVar("responses") == 1) {
			$depid = $jakdb->get("sessions", "department", ["id" => $chatid]);
			if (isset($depid) && !empty($depid) && isset($LC_RESPONSES) && is_array($LC_RESPONSES)) {

				$responses[] = array('id' => 0, 'message' => $jkl["g7"]);
					
				// get the responses from the file specific for this client
				foreach($LC_RESPONSES as $r) {
					
					if ($r["department"] == 0 || $r["department"] == $depid) {
							
						$responses[] = array('id' => $r['id'], 'message' => $r["title"]);
							
					}
					
				}
					
			} else {
				$responses[] = array('id' => 0, 'message' => $jkl["g7"]);
			}
		} else {
			$responses[] = array('id' => 0, 'message' => $jkl["e37"]);
		}

		if ($jakuser->getVar("transferc") == 1) {

			// Get all the operators
			$result = $jakdb->select("user", ["id", "departments", "username", "name", "available", "lastactivity"], ["AND" => ["access" => 1, "available" => 1, "id[!]" => $userid]]);

			// Get departments
			$lsdata = $jakdb->select("departments", ["id", "title"], ["ORDER" => ["dorder" => "ASC"]]);
				
			if (isset($result) && !empty($result)) {
				foreach ($result as $row) {
				
					if (time() > ($row['lastactivity'] + $timeout)) {
						$jakdb->update("user", ["available" => 0], ["id" => $row['id']]);
					}
					
					if ($row["departments"] == 0) {
						$udep = $jkl['g105'];
					} else {
					
						if (isset($lsdata) && is_array($lsdata)) foreach($lsdata as $z) {
						
							if (in_array($z["id"], explode(',', $row["departments"]))) {
							
								$udepl[] = $z["title"];
							
							}
						
						}
					
					}
					
					if (!empty($udepl) && is_array($udepl)) $departmentlist = join(", ", $udepl);
					
					if (isset($departmentlist) && $departmentlist) $udep = $jkl['m9'].': '.$departmentlist;
					
					$operator[] = array('id' => $row['id'], 'operators' => $row['name'].' - '.$row['username'].' ('.$udep.')');
				
				}
			}

			if ($operator) {
				$oselect = $operator;
				
			} else {
				$oselect[] = array('id' => 0, 'operators' => $jkl['g114']);
			}

		} else {
			$oselect[] = array('id' => 0, 'operators' => $jkl['e37']);
		}

		// Get the current upload status
		$sendfile = $jakdb->get("checkstatus", "files", ["convid" => $chatid]);

		// Display the message content and location from the client
		die(json_encode(array('status' => true, 'responses' => $responses, 'operators' => $oselect, 'fileupload' => $sendfile)));

	} else {
		die(json_encode(array('status' => false, 'errorcode' => 1)));
	}
}

die(json_encode(array('status' => false, 'errorcode' => 7)));
?>