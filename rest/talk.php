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

// include the PHP library (if not autoloaded)
require(APP_PATH.'class/class.emoji.php');

$userid = $loginhash = $chatid = $message = "";
$task = false;
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
		    $lang = $USER_LANGUAGE;
		} else {
		    include_once(APP_PATH.JAK_OPERATOR_LOC.'/lang/'.JAK_LANG.'.php');
		    $lang = JAK_LANG;
		}

		// Typing status
		if ($task == "typing") {
			
			$result = $jakdb->update("checkstatus", ["typeo" => 1], ["convid" => $chatid]);

			if ($result) {
				// Take the chat go to chat
				die(json_encode(array('status' => true, 'task' => "typing")));
			}

			// Take the chat go to chat
			die(json_encode(array('status' => false, 'task' => 7)));

		}

		// Typing status false
		if ($task == "untyping") {
			
			$result = $jakdb->update("checkstatus", ["typeo" => 0], ["convid" => $chatid]);

			if ($result) {
				// Take the chat go to chat
				die(json_encode(array('status' => true, 'task' => "untyping")));
			}

			// Take the chat go to chat
			die(json_encode(array('status' => false, 'task' => 7)));

		}


		// Save the message
		if ($task == "new") {

			$message = trim($_REQUEST['message']);

			if (!empty($message)) {

				$row = $jakdb->get("checkstatus", ["convid", "hide"], ["convid" => $chatid]);

				if (isset($row) && !empty($row)) {
					
					// We sanitize the input
					$message = strip_tags($message);
					$message = filter_var($message, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$message = trim($message);

					// Convert the smilies to shortcode
					$message = Emojione\Emojione::toShort($message);
					
					if (!$row['hide']) {

						// Check if we have to quote
						$msgquote = 0;
						if (isset($_REQUEST['msgid']) && !empty($_REQUEST['msgid']) && is_numeric($_REQUEST['msgid'])) $msgquote = $_REQUEST['msgid'];

						$jakdb->insert("transcript", [ 
							"name" => $jakuser->getVar("name"),
							"message" => $message,
							"user" => $userid.'::'.$jakuser->getVar("username"),
							"operatorid" => $userid,
							"convid" => $row['convid'],
							"quoted" => $msgquote,
							"class" => "admin",
							"time" => $jakdb->raw("NOW()")]);

						// Update the status after answer
						$jakdb->update("checkstatus", ["newc" => 1, "typeo" => 0, "newo" => 0, "statuso" => time()], ["convid" => $row['convid']]);

						// Take the chat go to chat
						die(json_encode(array('status' => true, 'task' => "sendmsg")));

					} elseif ($row['hide']) {
					
						if (!empty($HD_ANSWERS) && is_array($HD_ANSWERS)) foreach ($HD_ANSWERS as $v) {
							
							if ($v["msgtype"] == 4 && $v["lang"] == $lang) {
							
								$phold = array("%operator%","%client%","%email%");
								$replace   = array($jakuser->getVar("name"), $jakuser->getVar("username"), JAK_EMAIL);
								$message = str_replace($phold, $replace, $v["message"]);

								$jakdb->insert("transcript", [ 
									"name" => $jakuser->getVar("name"),
									"message" => $message,
									"convid" => $row['convid'],
									"class" => "notice",
									"time" => $jakdb->raw("NOW()")]);
								
							}
								
						}
						
						// Take the chat go to chat
						die(json_encode(array('status' => true, 'task' => "sendmsg")));
						
					}
				}
			}

			die(json_encode(array('status' => false, 'errorcode' => 11)));
			
		}

		// Edit a message
		if ($task == "edit") {

			$message = trim($_REQUEST['message']);

			if (!empty($message) && isset($_REQUEST['msgid']) && is_numeric($_REQUEST['msgid'])) {

				// We sanitize the input
				$message = strip_tags($message);
				$message = filter_var($message, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				$message = trim($message);

				// update the message
				$jakdb->update("transcript", ["message" => $message, "editoid" => $userid, "edited" => $jakdb->raw("NOW()")], ["AND" => ["id" => $_REQUEST['msgid'], "convid" => $chatid]]);

				// send to client
				$jakdb->update("checkstatus", ["msgedit" => $_REQUEST['msgid'], "typeo" => 0], ["convid" => $chatid]);

				// Take the chat go to chat
				die(json_encode(array('status' => true, 'task' => "editmsg")));

			}

			die(json_encode(array('status' => false, 'errorcode' => 7)));

		}

		// Starred Message
		if ($task == "starred") {

			// message = starred
			if (isset($_REQUEST['message']) && isset($_REQUEST['msgid']) && is_numeric($_REQUEST['msgid'])) {

				if ($_REQUEST['message'] == 1) {
					$starred = 0;
				} else {
					$starred = 1;
				}

				$update = $jakdb->update("transcript", ["starred" => $starred], ["AND" => ["id" => $_REQUEST['msgid'], "convid" => $chatid]]);

				// Take the chat go to chat
				die(json_encode(array('status' => true, 'task' => "starrmsg")));

			}

			die(json_encode(array('status' => false, 'errorcode' => 7)));

		}

		// Delete a message
		if ($task == "delete") {

			// message = plevel
			if (isset($_REQUEST['message']) && isset($_REQUEST['msgid']) && is_numeric($_REQUEST['msgid'])) {

				if ($_REQUEST['message'] == 1) {
					$plevel = 2;
				} else {
					$plevel = 1;
				}

				// Update the plevel in the transcript table
				$update = $jakdb->update("transcript", ["plevel" => $plevel], ["AND" => ["id" => $_REQUEST['msgid'], "convid" => $chatid]]);

				// Update the status page
				$jakdb->update("checkstatus", ["typeo" => 0, "msgdel" => $_REQUEST['msgid']], ["convid" => $chatid]);

				// Take the chat go to chat
				die(json_encode(array('status' => true, 'task' => "deletemsg")));

			}

			die(json_encode(array('status' => false, 'errorcode' => 7)));

		}

	} else {
		die(json_encode(array('status' => false, 'errorcode' => 1)));
	}
}

die(json_encode(array('status' => false, 'errorcode' => 7)));
?>