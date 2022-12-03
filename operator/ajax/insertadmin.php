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

if (!file_exists('../../config.php')) die('ajax/[available.php] config.php not exist');
require_once '../../config.php';

// Import the user or standard language file
if (isset($_SESSION['jak_lcp_lang']) && file_exists(APP_PATH.JAK_OPERATOR_LOC.'/lang/'.$_SESSION['jak_lcp_lang'].'.php')) {
    include_once(APP_PATH.JAK_OPERATOR_LOC.'/lang/'.$_SESSION['jak_lcp_lang'].'.php');
    $lang = $_SESSION['jak_lcp_lang'];
} else {
    include_once(APP_PATH.JAK_OPERATOR_LOC.'/lang/'.JAK_LANG.'.php');
    $lang = JAK_LANG;
}

if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !isset($_SESSION['jak_lcp_idhash'])) die(json_encode(array('status' => 0, "html" => $jkl['g79'])));

if ($_POST['conv'] == "open" || (!is_numeric($_POST['id']) && !is_numeric($_POST['uid']))) die(json_encode(array('status' => 0, "html" => $jkl['g79'])));

// We sanitize the input
$message = strip_tags($_POST['msg']);
$message = filter_var($message, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$message = trim($message);

// Check for empty message
if (empty($message)) die(json_encode(array('status' => 0, "html" => $jkl['e1'])));

// Check for duplicate messages
if (isset($_SESSION["oplastmsg"]) && $_SESSION["oplastmsg"] == $message) die(json_encode(array("status" => 0, "html" => $jkl['e45'])));

$row = $jakdb->get("checkstatus", ["convid", "hide"], ["convid" => $_POST['id']]);

if (isset($row) && !empty($row)) {
	
		define('BASE_URL_IMG', str_replace(JAK_OPERATOR_LOC.'/ajax/', '', BASE_URL));
		
		if (!$row['hide']) {

			if (isset($_POST['msgedit']) && !empty($_POST['msgedit']) && is_numeric($_POST['msgedit'])) {

				// include the PHP library (if not autoloaded)
				require('../../class/class.emoji.php');

				// update the message
				$jakdb->update("transcript", ["message" => $message, "editoid" => $_POST['userid'], "edited" => $jakdb->raw("NOW()")], ["AND" => ["id" => $_POST['msgedit'], "convid" => $row['convid']]]);

				// send to client
				$jakdb->update("checkstatus", ["msgedit" => $_POST['msgedit'], "typeo" => 0], ["convid" => $row['convid']]);

				// Show the edited symbol with the date
				$showedit = ' | <i class="fa fa-edit"></i> '.JAK_base::jakTimesince(time(), "", JAK_TIMEFORMAT);

				// We convert the urls/br
				$messageemoji = nl2br(replace_urls($message), false);

				$messageemoji = Emojione\Emojione::toImage($messageemoji);

				die(json_encode(array('status' => 1, 'edit' => $messageemoji, 'editblank' => $message, 'editid' => $_POST['msgedit'], 'showedit' => $showedit)));

			} else {

				// Check if we have to quote
				$msgquote = 0;
				if (isset($_POST['msgquote']) && !empty($_POST['msgquote']) && is_numeric($_POST['msgquote'])) $msgquote = $_POST['msgquote'];

				// Check if we have a short code for the message.
				if (!empty($LC_RESPONSES) && is_array($LC_RESPONSES)) foreach ($LC_RESPONSES as $r) {

					if ($message == $r["short_code"]) {
						$message = $r["message"];
						break;
					}
				}

				// the last message in a session
				$_SESSION["oplastmsg"] = $message;

				$jakdb->insert("transcript", [ 
					"name" => $_POST['oname'],
					"message" => $message,
					"user" => $_POST['userid'].'::'.$_POST['uname'],
					"operatorid" => $_POST['userid'],
					"convid" => $row['convid'],
					"quoted" => $msgquote,
					"class" => "admin",
					"time" => $jakdb->raw("NOW()")]);
				
				if (!empty($_POST['url'])) {

					$jakdb->insert("transcript", [ 
						"name" => $_POST['oname'],
						"message" => $_POST['url'],
						"user" => $_POST['userid'].'::'.$_POST['uname'],
						"operatorid" => $_POST['userid'],
						"convid" => $row['convid'],
						"class" => "url",
						"plevel" => 1,
						"time" => $jakdb->raw("NOW()")]);
				
				}

				// Update the status after answer
				$jakdb->update("checkstatus", ["newc" => 1, "typeo" => 0, "newo" => 0, "statuso" => time()], ["convid" => $row['convid']]);

			}
			
			die(json_encode(array('status' => 1, 'edit' => false)));
			
		} elseif ($row['hide']) {
		
			if (!empty($LC_ANSWERS) && is_array($LC_ANSWERS)) foreach ($LC_ANSWERS as $v) {
				
				if ($v["msgtype"] == 4 && $v["lang"] == $lang) {
				
					$phold = array("%operator%","%client%","%email%");
					$replace   = array($_POST['oname'], $_POST['uname'], JAK_EMAIL);
					$message = str_replace($phold, $replace, $v["message"]);

					$jakdb->insert("transcript", [ 
						"name" => $_POST['oname'],
						"message" => $message,
						"convid" => $row['convid'],
						"class" => "notice",
						"time" => $jakdb->raw("NOW()")]);
					
				}
					
			}
			
			die(json_encode(array('status' => 1, 'edit' => false)));
			
		} else {
	
			die(json_encode(array('status' => 0, "html" => $jkl['e1'])));
		}
	}
?>