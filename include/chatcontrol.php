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

if (!file_exists('../config.php')) die('include/[chatcontrol.php] config.php not exist');
require_once '../config.php';

if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && isset($_GET['id']) && !is_numeric($_GET['id'])) die(json_encode(array('status' => false, 'error' => "No valid ID")));

// Language file
$lang = JAK_LANG;
if (isset($_GET['lang']) && !empty($_GET['lang']) && $_GET['lang'] != $lang) $lang = $_GET['lang'];

// Import the language file
if ($lang && file_exists(APP_PATH.'lang/'.strtolower($lang).'.php')) {
    include_once(APP_PATH.'lang/'.strtolower($lang).'.php');
} else {
    include_once(APP_PATH.'lang/'.JAK_LANG.'.php');
    $lang = JAK_LANG;
}

// Get the current time
$currentime = time();

if (isset($_GET['id'])) {
    $cachewidget = APP_PATH.JAK_CACHE_DIRECTORY.'/widget'.$_GET['id'].'.php';
    if (file_exists($cachewidget)) include_once $cachewidget;
} else {
    die(json_encode(array('status' => false, 'error' => "No valid ID.")));   
}

// Get the absolute url for the image
$base_url = str_replace('include/', '', BASE_URL);

$switchc = '';
if (isset($_GET['run']) && !empty($_GET['run'])) $switchc = $_GET['run'];

switch($switchc) {

	case 'backtochat':

		if (isset($_POST['customer']) && !empty($_POST['customer'])) {

			// Let's make sure we have an active chat and it is available
			$cudetails = jak_string_encrypt_decrypt($_POST['customer'], false);

			// Let's explode the string (0 = convid, 1 = uniqueid, 2 = userid, 3 = name, 4 = email, 5 = phone, 6 = avatar)
			$cudetails = explode(":#:", $cudetails);

			if (isset($cudetails[0]) && is_numeric($cudetails[0])) {

				// Update the database
				$jakdb->update("sessions", ["status" => 1, "fcontact" => 0, "ended" => 0], ["id" => $cudetails[0]]);
				$jakdb->update("checkstatus", ["hide" => 0], ["convid" => $cudetails[0]]);

				die(json_encode(array('status' => true)));
			}
		}

	break;

	case 'stopchat':

		if (isset($_POST['customer']) && !empty($_POST['customer'])) {

			// Let's make sure we have an active chat and it is available
			$cudetails = jak_string_encrypt_decrypt($_POST['customer'], false);

			// Let's explode the string (0 = convid, 1 = uniqueid, 2 = userid, 3 = name, 4 = email, 5 = phone, 6 = avatar)
			$cudetails = explode(":#:", $cudetails);

			if (isset($cudetails[0]) && is_numeric($cudetails[0])) {

				// Let's inform the operator that the user has gone to the feedback form or has ended the chat
				if ($jakwidget['feedback']) {

					$jakdb->insert("transcript", [ 
						"name" => $cudetails[3],
						"message" => sprintf($jkl['g43'], $cudetails[3]),
						"user" => $cudetails[2],
						"convid" => $cudetails[0],
						"class" => "notice",
						"time" => $jakdb->raw("NOW()")]);

					// We need to know if we stop the chat without feedback
					$feedbackform = "yes";
				} else {
					// That's it finish the chat and reload
			        $jakdb->insert("transcript", [ 
			          "name" => $cudetails[3],
			          "message" => sprintf($jkl['g16'], $cudetails[3]),
			          "user" => $cudetails[2],
			          "convid" => $cudetails[0],
			          "class" => "ended",
			          "time" => $jakdb->raw("NOW()")]);

			        // Close the chat
			        $jakdb->update("sessions", ["status" => 0, "ended" => time()], ["id" => $cudetails[0]]);
			        $jakdb->update("checkstatus", ["hide" => 1], ["convid" => $cudetails[0]]);

			        $feedbackform = "nope";

			    }

				die(json_encode(array('status' => true, 'feedbackform' => $feedbackform)));
			}
		}

	break;

}

?>