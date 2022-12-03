<?php

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 6 May 1998 03:10:00 GMT");

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 5.0                   # ||
|| # ----------------------------------------- # ||
|| # Copyright 2021 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

if (!file_exists('../config.php')) die('ajax/[available.php] config.php not exist');
require_once '../config.php';

// include the PHP library (if not autoloaded)
require('../class/class.emoji.php');

// Extensive test if that is the real user or not
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || !isset($_SESSION['groupchatid']) || !isset($_SESSION['gcuid'])) die("Nothing to do here");

// The chat window is active
$winactive = true;
if (isset($_GET["active"]) && $_GET["active"] == "false") $winactive = false;

// Current time stamp
$ctime = microtime(true);

// Reset vars
$lastid = $newmsg = $banned = $creload = 0;
$delmsg = array();
$chatmsg = $userlist = "";
if (isset($_GET['lastid']) && is_numeric($_GET['lastid']) && $_GET['lastid'] != 0) {
	$lastid = $_GET['lastid'];
}

// Get the absolute url for the image
$ava_url = str_replace('include/', '', BASE_URL).JAK_FILES_DIRECTORY;

// We have an offline client call just return nothing
if ($_GET["usract"] == 3) {
	die(json_encode(array("status" => 1, "newmsg" => 3)));
} else {

// Get the chat file
$groupchatfile = APP_PATH.JAK_CACHE_DIRECTORY.'/groupchat'.$_SESSION['groupchatid'].'.txt';

// Check if file is available and user is valid
if (file_exists($groupchatfile)) {

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

	} else {

		// The chat has gone offline show the message
		if (!empty($LC_ANSWERS) && is_array($LC_ANSWERS)) foreach ($LC_ANSWERS as $v) {

			if ($v["msgtype"] == 12 && $v["lang"] == JAK_LANG) {
					
				$phold = array("%operator%","%client%","%email%");
				$replace   = array("", $_SESSION['gcname'], JAK_EMAIL);
				$offlinemsg = str_replace($phold, $replace, $v["message"]);
						
			}
		}

		$chatmsg .= '<div class="message system"><span>'.$jkl['g56'].' - '.JAK_base::jakTimesince($ctime, "", JAK_TIMEFORMAT).'</span>'.stripcslashes($offlinemsg).'</div>';

		die(json_encode(array("status" => 1, "html" => $chatmsg, "newmsg" => 3, "vislist" => "", "delmsg" => "", "lastid" => $lastid)));
	}

	// Update the user status every 2 minutes
	if (!isset($_SESSION["usrbanned"]) && (!isset($_SESSION["vislasttime"]) || $_SESSION["vislasttime"] < $ctime - 120)) {
		$_SESSION["vislasttime"] = $ctime;
		$jakdb->update("groupchatuser", ["statusc" => $ctime], ["id" => $_SESSION['gcuid']]);
		// We are a operator update the user table as well.
		if (isset($_SESSION['gcopid']) && !empty($_SESSION['gcopid'])) {
			$jakdb->update("user", ["lastactivity" => time(), "session" => session_id()], ["id" => $_SESSION['gcopid']]);
		}
	}

	if ($winactive) {

		// Get the file
		$chatfile = file_get_contents($groupchatfile);

		// Each line
		$chatfile = explode(":!n:", $chatfile);

		$modstuff = "";

		if (isset($chatfile) && is_array($chatfile)) foreach ($chatfile as $v) {
				
			// We will go trough each file
			$chatline = explode(":#!#:", $v);

			// Message format: time:#!#:userid:#!#:name:#!#:avatar:#!#:message:#!#:quote;

			// Now we check if we have messages after our timeline
			if ($lastid < $chatline[0]) {

				// We are banned
				if (isset($_SESSION["usrbanned"])) {

					// We have a mod line
					if ($chatline[2] == "*mod*" && $chatline[4] == "react" && $chatline[3] == $_SESSION['gcuid']) {

						// remove the banned session
						unset($_SESSION["usrbanned"]);
						$lastid = $chatline[0];
						$newmsg = 1;
					}

				} else {

					// We have a mod line
					if ($lastid != 0 && $chatline[2] == "*mod*") {

						// At this moment we have only delete.
						if ($chatline[4] == "delete") {
							$delmsg[] = $chatline[3];
							$newmsg = 1;
						} elseif ($chatline[4] == "banned" && $chatline[3] == $_SESSION['gcuid']) {
							// ok the user that reads that is banned

							// Session banned
							$_SESSION["usrbanned"] = true;

							$lastid = $chatline[0];
							$newmsg = 2;
						}

					} else {

						// Unique Message id
						$umsgid = $chatline[1].str_replace(".", "_", $chatline[0]);

						// Convert urls
						$messagedisp = nl2br(replace_urls($chatline[4]));

						// Convert emotji
						$messagedisp = Emojione\Emojione::toImage($messagedisp);

						// We have an operator
						if (isset($_SESSION['gcopid'])) {
							$modstuff = '<a href="javascript:void(0)" class="edit-remove" data-id="'.$umsgid.'"><i class="fa fa-trash"></i></a>';
						}

						// We have a quoted message
						$quoted = "";
						if (isset($chatline[5]) && !empty($chatline[5])) {
							// Convert urls
							$quotemsg = nl2br(replace_urls($chatline[5]));

							// Convert emotji
							$quotemsg = Emojione\Emojione::toImage($quotemsg);

							$quoted = '<blockquote class="blockquote"><i class="fa fa-reply"></i> '.$quotemsg.'</blockquote>';
						}

						// is mod
						$ismod = false;
						if (isset($chatline[6]) && $chatline[6] === "true") $ismod = true;

						// Now load the time only once a minute
						$chattime = "";
						if (($lastid + 15) < $chatline[0]) $chattime = '<div class="time">'.JAK_base::jakTimesince($chatline[0], "", JAK_TIMEFORMAT).'</div>';

						$chatmsg .= $chattime.'<div class="message'.($ismod ? ' operator' : '').'" id="postid_'.$umsgid.'"><span>'.$chatline[2].'<div class="chat-edit">'.($chatline[1] != $_SESSION['gcuid'] ? '<a href="javascript:void(0)" class="edit-quote" data-msg="'.$chatline[4].'" data-id="'.$umsgid.'"><i class="fa fa-quote-right"></i></a>' : '').$modstuff.'</div></span><div id="msg'.$umsgid.'">'.$quoted.stripcslashes($messagedisp).'</div></div>';

						// Get the latest messages
						$lastid = $chatline[0];
						$newmsg = 1;

					}
				}
			}
		}
	}

	// Ok every two minutes we do load the new user list or if we have a change
	if ($newmsg) {

		// Remove customers from the last 5 minutes
		$listnow = $ctime - 300;

		// Remove user that are older than
		$jakdb->delete("groupchatuser", ["AND" => ["groupchatid" => $_SESSION['groupchatid'], "statusc[<]" => $listnow]]);

		$result = $jakdb->select("groupchatuser", ["id", "name", "usr_avatar", "lastmsg", "banned", "ip", "isop", "created"], ["groupchatid" => $_SESSION['groupchatid'], "ORDER" => ["name" => "ASC"]]);

		if (isset($result) && !empty($result)) {

			foreach ($result as $u) {

				$usermod = $usrip = "";
				// We have an operator
				if (isset($_SESSION["gcopid"]) && $u["isop"] == 0) {
						$usermod = '<a href="javascript:void(0)" class="edit-ban" data-id="'.$u["id"].'"><i class="fa fa-ban"></i></a>';
						$usrip = $u["ip"];
				}

				$userlist .= '<div class="gcuser" id="postid_'.$umsgid.'">
				      <div class="pic"><img class="pic" src="'.$ava_url.$u['usr_avatar'].'" alt="'.$u["name"].'"></div>
				      '.($u["banned"] ? '<div class="badge">'.$jkl['g84'].'</div>' : '').'
				      <div class="name'.($u["isop"] ? ' mod' : '').'">
				        '.$u["name"].' '.$usermod.'
				      </div>
				      <div class="message">
				      	'.($u["lastmsg"] ? sprintf($jkl['g78'], JAK_base::jakTimesince($u["lastmsg"], "", JAK_TIMEFORMAT)) : '-').'<br>
				        '.sprintf($jkl['g89'], JAK_base::jakTimesince($u["created"], "", JAK_TIMEFORMAT)).'<br>
				        '.$usrip.'
				      </div>
				    </div>';

			}

		}
	}

	if ($banned != 0) $newmsg = $banned;

	die(json_encode(array("status" => 1, "html" => $chatmsg, "newmsg" => $newmsg, "vislist" => $userlist, "delmsg" => $delmsg, "reload" => $creload, "lastid" => $lastid)));
}
}
die(json_encode(array("status" => 0)));
?>