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

// include the PHP library (if not autoloaded)
require(APP_PATH.'class/class.emoji.php');

$userid = $loginhash = $chatid = "";
if (isset($_REQUEST['userid']) && !empty($_REQUEST['userid']) && is_numeric($_REQUEST['userid'])) $userid = $_REQUEST['userid'];
if (isset($_REQUEST['loginhash']) && !empty($_REQUEST['loginhash'])) $loginhash = $_REQUEST['loginhash'];
if (isset($_REQUEST['chatid']) && !empty($_REQUEST['chatid'])) $chatid = $_REQUEST['chatid'];

if (!empty($userid) && !empty($loginhash) && !empty($chatid) && is_numeric($chatid)) {

	// Let's check if we are logged in
	$usr = $jakuserlogin->jakCheckrestlogged($userid, $loginhash);

	if ($usr) {

		$statusmsg = $chatended = $typingstatus = false;
		$chatmsg = array();

		if (is_numeric($chatid)) {

			if (!isset($_REQUEST['lastmsgid']) && !is_numeric($_REQUEST['lastmsgid'])) {
				$lastid = 0;
			} else {
				$lastid = $_REQUEST['lastmsgid'];
			}

			$result = $jakdb->select("transcript", ["[>]user" => ["operatorid" => "id"], "[>]sessions" => ["convid" => "id"]], ["transcript.id", "transcript.name", "transcript.message", "transcript.operatorid", "transcript.time", "transcript.class", "transcript.starred", "transcript.quoted", "transcript.editoid", "transcript.edited", "transcript.convid", "transcript.plevel", "user.picture", "sessions.usr_avatar", "sessions.status", "sessions.template"], ["AND" => ["transcript.convid" => $chatid, "transcript.id[>]" => $lastid], "ORDER" => ["transcript.id" => "ASC"]]);

			if (isset($result) && !empty($result)) {

				foreach ($result as $row) {

					// Right
					$msgposition = 1;
					// Left
					if ($row["class"] == "user") $msgposition = 2;

					// On which class to show a system image
					$systemimg = array("bot", "notice", "url", "ended");
					
					// Get the avatar
					$cimage = $row["usr_avatar"];
					if ($row["picture"] && $row["operatorid"]) $cimage = JAK_FILES_DIRECTORY.$row["picture"];
					if (in_array($row["class"], $systemimg)) $cimage = 'lctemplate/'.$row['template'].'/avatar/system.jpg';

					// Get the quote msg
					$quotemsg = '';
					if ($row['quoted']) {
						$quotemsg = $jakdb->get("transcript", "message", ["id" => $row["quoted"]]);
					}

					// we have file
					$msgfile = false;
					if ($row['class'] == "download" && file_exists(APP_PATH.JAK_FILES_DIRECTORY.$row['message'])) {
						$msgfile = JAK_FILES_DIRECTORY.$row['message'];
						// Left
						if ($row["operatorid"] == 0) $msgposition = 2;
						$message = false;
					} else {
						// Convert the smilies to unicode
						$message = Emojione\Emojione::shortnameToUnicode($row['message']);
					}

					// respond with the array
					$chatmsg[] = array('id' => $row['id'], 'image' => $cimage, 'name' => $row['name'], 'time' => $row['time'], 'class' => $row['class'], 'starred' => $row['starred'], 'editoid' => $row['editoid'], 'edited' => $row['edited'], 'plevel' => $row['plevel'], 'message' => $message, 'msgfile' => $msgfile, 'quotemsg' => $quotemsg, 'msgposition' => $msgposition);

					$lastid = $row["id"];
					$chatstatus = $row["status"];
				}
			}

			// Let's check the status
			if (!isset($chatstatus)) {
				$chatstatus = $jakdb->get("sessions", "status", ["id" => $chatid]);

				if ($chatstatus) {
					$typingstatus = $jakdb->get("checkstatus", "typec", ["convid" => $chatid]);
				}
			}

			// Return the messages
			die(json_encode(array('status' => true, 'chatmsg' => $chatmsg, 'lastid' => $lastid, 'typingstatus' => $typingstatus, 'chatstatus' => $chatstatus)));

		}

	} else {
		die(json_encode(array('status' => false, 'errorcode' => 1)));
	}
}

die(json_encode(array('status' => false, 'errorcode' => 7)));
?>