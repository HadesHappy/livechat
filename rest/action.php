<?php

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 6 May 1998 03:10:00 GMT");

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 5.0.3                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2022 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

if (!file_exists('config.php')) die('rest_api config.php not exist');
require_once 'config.php';

$userid = $loginhash = $chatid = $contactid = $invid = $invmsg = "";
$takechat = $denychat = $taketransfer = $denytransfer = $ostatus = $deletechat = $deletecontact = false;
if (isset($_REQUEST['userid']) && !empty($_REQUEST['userid']) && is_numeric($_REQUEST['userid'])) $userid = $_REQUEST['userid'];
if (isset($_REQUEST['loginhash']) && !empty($_REQUEST['loginhash'])) $loginhash = $_REQUEST['loginhash'];
if (isset($_REQUEST['chatid']) && !empty($_REQUEST['chatid'])) $chatid = $_REQUEST['chatid'];
if (isset($_REQUEST['contactid']) && !empty($_REQUEST['contactid'])) $contactid = $_REQUEST['contactid'];
if (isset($_REQUEST['takechat']) && !empty($_REQUEST['takechat'])) $takechat = $_REQUEST['takechat'];
if (isset($_REQUEST['denychat']) && !empty($_REQUEST['denychat'])) $denychat = $_REQUEST['denychat'];
if (isset($_REQUEST['taketransfer']) && !empty($_REQUEST['taketransfer'])) $taketransfer = $_REQUEST['taketransfer'];
if (isset($_REQUEST['denytransfer']) && !empty($_REQUEST['denytransfer'])) $denytransfer = $_REQUEST['denytransfer'];
if (isset($_REQUEST['deletechat']) && !empty($_REQUEST['deletechat'])) $deletechat = $_REQUEST['deletechat'];
if (isset($_REQUEST['deletecontact']) && !empty($_REQUEST['deletecontact'])) $deletecontact = $_REQUEST['deletecontact'];
if (isset($_REQUEST['invid']) && !empty($_REQUEST['invid'])) $invid = $_REQUEST['invid'];
if (isset($_REQUEST['invmsg']) && !empty($_REQUEST['invmsg'])) $invmsg = $_REQUEST['invmsg'];
if (isset($_REQUEST['ostatus']) && !empty($_REQUEST['ostatus'])) $ostatus = $_REQUEST['ostatus'];

if (!empty($userid) && !empty($loginhash)) {

	// Let's check if we are logged in
	$usr = $jakuserlogin->jakCheckrestlogged($userid, $loginhash);

	if ($usr) {

		// Select the user fields
		$jakuser = new JAK_user($usr);
		// Only the SuperAdmin in the config file see everything
		if ($jakuser->jakSuperadminaccess($userid)) {
			define('JAK_SUPERADMINACCESS', true);
		} else {
			define('JAK_SUPERADMINACCESS', false);
		}

		$USER_LANGUAGE = strtolower($jakuser->getVar("language"));

		// Import the language file
		if ($USER_LANGUAGE && file_exists(APP_PATH.JAK_OPERATOR_LOC.'/lang/'.$USER_LANGUAGE.'.php')) {
		    include_once APP_PATH.JAK_OPERATOR_LOC.'/lang/'.$USER_LANGUAGE.'.php';
		    $lang = $USER_LANGUAGE;
		} else {
		    include_once(APP_PATH.JAK_OPERATOR_LOC.'/lang/'.JAK_LANG.'.php');
		    $lang = JAK_LANG;
		}

		// Change status on live chat
		if ($ostatus && !empty($ostatus) && is_numeric($ostatus)) {

			if ($ostatus == 1) {

				// operator makes himself available
				$jakdb->update("user", ["available" => 1], ["id" => $userid]);

				die(json_encode(array('status' => true, 'task' => "ostatus", "statusid" => 1)));
				
			} elseif ($ostatus == 2) {

				// Operator goes busy
				$jakdb->update("user", ["available" => 2], ["id" => $userid]);
				
				die(json_encode(array('status' => true, 'task' => "ostatus", "statusid" => 2)));
				
			} else {

				// operator goes offline
				$jakdb->update("user", ["available" => 0], ["id" => $userid]);
				
				die(json_encode(array('status' => true, 'task' => "ostatus", "statusid" => 3)));
				
			}

		}

		// Take the chat and go to the chat window
		if ($takechat && !empty($chatid) && is_numeric($chatid)) {

			// Check if we have an operator
			$sessup = $jakdb->update("sessions", ["operatorid" => $userid, "operatorname" => $jakuser->getVar("name")], ["AND" => ["operatorid" => [0, $userid], "id" => $chatid]]);
			if ($sessup) {

				$jakdb->update("checkstatus", ["newc" => 1, "operatorid" => $userid, "operator" => $jakuser->getVar("name"), "pusho" => 1, "statuso" => time()], ["convid" => $chatid]);

				if (!empty($LC_ANSWERS) && is_array($LC_ANSWERS)) foreach ($LC_ANSWERS as $v) {
					
					if ($v["msgtype"] == 2 && $v["lang"] == $lang) {
					
						$clientname = $jakdb->get("sessions", "name", ["id" => $chatid]);
					
						$phold = array("%operator%","%client%","%email%");
						$replace   = array($jakuser->getVar("name"), $clientname, JAK_EMAIL);
						$message = str_replace($phold, $replace, $v["message"]);

						$jakdb->insert("transcript", [ 
							"name" => $jakuser->getVar("name"),
							"message" => $message,
							"user" => $userid.'::'.$jakuser->getVar("username"),
							"operatorid" => $userid,
							"convid" => $chatid,
							"class" => "admin",
							"time" => $jakdb->raw("NOW()")]);
						
					}
						
				}

				// Take the chat go to chat
				die(json_encode(array('status' => true, 'task' => "takechat")));

			} else {

				// someone else was quicker
				die(json_encode(array('status' => false, 'task' => "takechat", 'errorcode' => 10)));
			}
		}

		// allow uploading files for the client
		if ($denychat && !empty($chatid) && is_numeric($chatid)) {

			if ($jakdb->update("sessions", ["deniedoid" => $userid, "status" => 0, "ended" => time()], ["id" => $chatid])) {
				$jakdb->update("checkstatus", ["denied" => 1, "hide" => 1], ["convid" => $chatid]);

				// Deny chat stay in queue
				die(json_encode(array('status' => true, 'task' => "denychat")));
			} else {
				die(json_encode(array('status' => false, 'task' => "denychat", 'errorcode' => 7)));
			}

		}

		// Accept the transfer
		if ($taketransfer && !empty($chatid) && is_numeric($chatid)) {

			$trow = $jakdb->get("checkstatus", ["convid", "transferid"], ["convid" => $chatid]);

			if ($trow) {

				$jakdb->insert("transcript", [ 
						"name" => $jakuser->getVar("name"),
						"message" => $jakuser->getVar("name").' '.$jkl["g121"],
						"user" => $userid.'::'.$jakuser->getVar("username"),
						"operatorid" => $userid,
						"convid" => $trow["convid"],
						"class" => "admin",
						"time" => $jakdb->raw("NOW()")]);

				$jakdb->update("sessions", ["operatorid" => $userid, "operatorname" => $jakuser->getVar("name")], ["id" => $trow['convid']]);

				$jakdb->update("checkstatus", ["operatorid" => $userid, "operator" => $jakuser->getVar("name"), "transferid" => 0, "transferoid" => 0], ["convid" => $trow['convid']]);
				$jakdb->update("transfer", ["used" => 1], ["id" => $trow["transferid"]]);

				// accept transfer go to chat
				die(json_encode(array('status' => true, 'task' => "taketransfer")));

			} else {
				die(json_encode(array('status' => false, 'task' => "taketransfer", 'errorcode' => 7)));
			}

		}

		// Send a knock knock
		if ($denytransfer && !empty($chatid) && is_numeric($chatid)) {

			$trow = $jakdb->get("checkstatus", ["convid", "transferid"], ["convid" => $chatid]);

			if ($trow) {

				$jakdb->update("checkstatus", ["transferid" => 0, "transferoid" => 0], ["convid" => $trow['convid']]);
				$jakdb->update("transfer", ["used" => 2], ["id" => $trow["transferid"]]);

				// Deny transfer stay in queue
				die(json_encode(array('status' => true, 'task' => "denytransfer")));

			} else {
				die(json_encode(array('status' => false, 'task' => "denytransfer", 'errorcode' => 7)));
			}

		}

		// Invite website vistors from mobile phone
		if ($invid && !empty($invid) && is_numeric($invid)) {

			if ($invmsg && !empty($invmsg)) {

				$result = $jakdb->update("buttonstats", ["opid" => $userid, "message" => $invmsg, "readtime" => 0], ["id" => $invid]);

				// Now let us delete and recreate the proactive cache file
				$proactivefile = APP_PATH.JAK_CACHE_DIRECTORY.'/mproactive.php';
				
				// Delete the current proactive file
				if (file_exists($proactivefile)) unlink($proactivefile);
			
				// Get the departments
				$manualproactive = $jakdb->select("buttonstats", ["id", "session", "message"], ["readtime" => 0, "ORDER" => ["lasttime" => "DESC"]]);
			
				$pafile = "<?php\n";
				
				$pafile .= "\$mproactiveserialize = '".base64_encode(gzcompress(serialize($manualproactive)))."';\n\n\$LV_MPROACTIVE = unserialize(gzuncompress(base64_decode(\$mproactiveserialize)));\n\n";
				
				$pafile .= "?>";
			
				if (JAK_base::jakWriteinCache($proactivefile, $pafile, '')) {
					die(json_encode(array('status' => true, 'task' => "invite")));
				} else {
					die(json_encode(array('status' => false, 'task' => "invite")));
				}

			} else {
				die(json_encode(array('status' => false, 'task' => "invite", 'errorcode' => 8)));
			}
			
		}

		// Delete conversation
		if ($deletechat && !empty($chatid) && is_numeric($chatid)) {

			if (JAK_SUPERADMINACCESS) {

				$sessionid = $jakdb->get("sessions", "id", ["id" => $chatid]);
			    $jakdb->delete("transcript", ["convid" => $sessionid]);
			    $jakdb->delete("checkstatus", ["convid" => $sessionid]);
			   	$result = $jakdb->delete("sessions", ["id" => $chatid]);

				// Deny transfer stay in queue
				die(json_encode(array('status' => true, 'task' => "deletechat")));

			} else {
				die(json_encode(array('status' => false, 'task' => "deletechat", 'errorcode' => 7)));
			}

		}

		// Delete offline message
		if ($deletecontact && !empty($contactid) && is_numeric($contactid)) {

			if (JAK_SUPERADMINACCESS) {

       			$jakdb->delete("contactsreply", ["contactid" => $contactid]);
       			$result = $jakdb->delete("contacts", ["id" => $contactid]);

				// Deny transfer stay in queue
				die(json_encode(array('status' => true, 'task' => "deletecontact")));

			} else {
				die(json_encode(array('status' => false, 'task' => "deletecontact", 'errorcode' => 8)));
			}

		}

	} else {
		die(json_encode(array('status' => false, 'errorcode' => 1, 'errorcode' => false)));
	}
}

die(json_encode(array('status' => false, 'errorcode' => 7, 'errorcode' => false)));
?>