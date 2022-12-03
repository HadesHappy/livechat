<?php

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 6 May 1980 03:10:00 GMT");

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 4.0.5                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2021 jakweb All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

if (!file_exists('../config.php')) die('ajax/[available.php] config.php not exist');
require_once '../config.php';

if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || !isset($_SESSION['gcopid'])) die("Nothing to see here");

if (!isset($_POST['action']) || !isset($_POST['id'])) die(json_encode(array("status" => 0)));

// We remove the message
if ($_POST['action'] == "delmsg") {

	// Reset some vars
	$msgfound = false;
	$ctime = microtime(true);

	$row = $jakdb->get("groupchatuser", ["id", "groupchatid"], ["id" => $_SESSION['gcuid']]);

	// Insert the message into the text file
	$groupchatfile = APP_PATH.JAK_CACHE_DIRECTORY.'/groupchat'.$row["groupchatid"].'.txt';

	// Get the file
	$chatfile = file_get_contents($groupchatfile);

	// Each line
	$chatfile = explode(":!n:",$chatfile);

	if (isset($chatfile) && is_array($chatfile)) foreach ($chatfile as $v) {

		// We will go trough each file
		$chatline = explode(":#!#:", $v);

		if ($_POST['id'] == $chatline[1].str_replace(".", "_", $chatline[0])) {
			$msgtoremove = $chatline[0].':#!#:'.$chatline[1].':#!#:'.$chatline[2].':#!#:'.$chatline[3].':#!#:'.$chatline[4].':#!#:'.$chatline[5].':!n:';
			$msgfound = true;
		}

	}

	// Finally remove the file and add the mod line
	if ($msgfound) {

		// Remove the bad line
		file_put_contents($groupchatfile, str_replace($msgtoremove, "", file_get_contents($groupchatfile)));

		// Modify the message with a time stamp
		$cmsg = $ctime.':#!#:'.$row['id'].':#!#:*mod*:#!#:'.$_POST['id'].':#!#:delete:#!#:'.$msgtoremove.':#!#:true:!n:';

		// Let's inform others that a new client has entered the chat
		file_put_contents($groupchatfile, $cmsg, FILE_APPEND);

		die(json_encode(array("status" => 1)));
	}

}

// Ban / UnBan a user
if ($_POST['action'] == "banusr") {

	// time
	$ctime = microtime(true);

	// Reset
	$chatban = false;
	$msgquote = "";

	$row = $jakdb->get("groupchatuser", ["id", "groupchatid", "name", "usr_avatar"], ["id" => $_SESSION['gcuid']]);

	if (is_numeric($_POST['id']) && $row["id"] != $_POST['id']) {

		// Now check the button id
		$cachegroupchat = APP_PATH.JAK_CACHE_DIRECTORY.'/groupchat'.$row['groupchatid'].'.php';
		if (file_exists($cachegroupchat)) include_once $cachegroupchat;

		// Import the language file
		if ($groupchat['lang'] && file_exists(APP_PATH.'lang/'.strtolower($groupchat['lang']).'.php')) {
			include_once(APP_PATH.'lang/'.strtolower($groupchat['lang']).'.php');
		} else {
			include_once(APP_PATH.'lang/'.JAK_LANG.'.php');
		}

		// Get the user
		$usr = $jakdb->get("groupchatuser", ["id", "name", "banned"], ["id" => $_POST['id']]);
		// update the table
		if ($usr["banned"] == 1) {
			$jakdb->update("groupchatuser", ["banned" => 0], ["id" => $usr["id"]]);
			// The un banned message
			$chatban = sprintf($jkl['g80'], $usr["name"]);
			$usrban = "react";
		} elseif ($usr["banned"] == 0) {
			$jakdb->update("groupchatuser", ["banned" => 1], ["id" => $usr["id"]]);
			// The un banned message
			$chatban = sprintf($jkl['g79'], $usr["name"]);
			$usrban = "banned";
		}

		if ($chatban) {
			
			// Insert the message into the text file
			$groupchatfile = APP_PATH.JAK_CACHE_DIRECTORY.'/groupchat'.$row["groupchatid"].'.txt';

			// The ban message
			$cwmsg = $ctime.':#!#:'.$row['id'].':#!#:'.$row['name'].':#!#:'.$row['usr_avatar'].':#!#:'.$chatban.':#!#:'.$msgquote.':#!#:true:!n:';

			$ctime = $ctime + 1;

			// Modify the message with a time stamp to banned or unbanned
			$cwmsg .= $ctime.':#!#:'.$row['id'].':#!#:*mod*:#!#:'.$usr['id'].':#!#:'.$usrban.':!n:';

			// Let's inform others that a new client has entered the chat
			file_put_contents($groupchatfile, $cwmsg, FILE_APPEND);

			die(json_encode(array("status" => 1)));
		}
	}

}
die(json_encode(array("status" => 0)));
?>