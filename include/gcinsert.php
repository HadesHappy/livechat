<?php

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 6 May 1980 03:10:00 GMT");

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 5.0.2                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2022 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

if (!file_exists('../config.php')) die('ajax/[available.php] config.php not exist');
require_once '../config.php';

if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || !isset($_SESSION['groupchatid']) || !isset($_SESSION['gcuid'])) die("Nothing to see here");

if (!$_POST['msg']) die(json_encode(array("status" => 0, "html" => "")));

$row = $jakdb->get("groupchatuser", ["id", "groupchatid", "usr_avatar", "lastmsg", "banned"], ["id" => $_SESSION['gcuid']]);

if (isset($row) && !empty($row)) {
	
	$message = html_entity_decode($_POST['msg']);
	$message = strip_tags($message);
	$message = filter_var($message, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

	// Ok we do need to remove our special signs for placeholder and new lines
	$badstuff = array(":#!#:", ":!n:");
	$goodstuff   = array("", "");

	$message = str_replace($badstuff, $goodstuff, $message);
	$message = trim($message);
		
	if (isset($message) && !empty($message) && $row['banned'] == 0) {

		// Current time
		$ctime = microtime(true);

		// Flood time
		$ftime = $ctime - 5;

		// Check for duplicate messages and 5 second flood
		if ($row["lastmsg"] > $ftime || (isset($_SESSION["lastmsg"]) && $_SESSION["lastmsg"] == $message)) {
			// Now check the button id
			$cachegroupchat = APP_PATH.JAK_CACHE_DIRECTORY.'/groupchat'.$_SESSION['groupchatid'].'.php';
			if (file_exists($cachegroupchat)) {
				include_once $cachegroupchat;

				// Import the language file
				if ($groupchat['lang'] && file_exists(APP_PATH.'lang/'.strtolower($groupchat['lang']).'.php')) {
					include_once(APP_PATH.'lang/'.strtolower($groupchat['lang']).'.php');
				} else {
					include_once(APP_PATH.'lang/'.JAK_LANG.'.php');
				}

			}

			$errormsg = $jkl['e17'];
			if ($row["lastmsg"] > $ftime) $errormsg = $jkl['e18'];

			die(json_encode(array("status" => 0, "html" => $errormsg)));
		}

		// the last message in a session
		$_SESSION["lastmsg"] = $message;

		// Check if we have a quote
		$msgquote = "";
		if (isset($_POST['msgquote']) && !empty($_POST['msgquote'])) $msgquote = filter_var($_POST['msgquote'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		// Insert the message into the text file
		$groupchatfile = APP_PATH.JAK_CACHE_DIRECTORY.'/groupchat'.$row["groupchatid"].'.txt';

		if (file_exists($groupchatfile)) {

			// Check file size, if bigger than 500kb save to db and start fresh.
			$gcfilesize = filesize($groupchatfile);

			if ($gcfilesize > 500000) {

				$chatfile = file_get_contents($groupchatfile);

				// we have a chatfile
				if (isset($chatfile) && !empty($chatfile)) {

					// Insert into the database
					$jakdb->insert("groupchatmsg", ["groupchatid" => $row["groupchatid"], "chathistory" => $chatfile, "operatorid" => 0, "created" => $jakdb->raw("NOW()")]);

					// Finally remove the file and start fresh
					unlink($groupchatfile);
				}

			}

		}

		// We have an operator
		$ismod = "false";
		if (isset($_SESSION['gcopid'])) {
			$ismod = "true";
		}

		// Modify the message with a time stamp
		$cmsg = $ctime.':#!#:'.$row['id'].':#!#:'.$_SESSION['gcname'].':#!#:'.$row["usr_avatar"].':#!#:'.$message.':#!#:'.$msgquote.':#!#:'.$ismod.':!n:';

		// Let's inform others that a new client has entered the chat
		file_put_contents($groupchatfile, $cmsg, FILE_APPEND);

		$jakdb->update("groupchatuser", ["lastmsg" => time()], ["id" => $row['id']]);
			
		die(json_encode(array("status" => 1)));
			
	} else {
		die(json_encode(array("status" => 0)));
	}
} else {
	die(json_encode(array("status" => 0)));
}
?>