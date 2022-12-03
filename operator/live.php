<?php

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 5.0.4                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2022 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

// Check if the file is accessed only via index.php if not stop the script from running
if (!defined('JAK_ADMIN_PREVENT_ACCESS')) die('You cannot access this file directly.');

// Check if we have a valid ID.
if (isset($page1) && is_numeric($page1)) {

	// reset the lastido session
	unset($_SESSION["lastido"]);

	// Now get a few information from the session table
	$livechat = $jakdb->get("sessions", ["operatorid", "department", "operatorname", "name", "status", "ended"], ["id" => $page1]);

	if ($livechat["status"] == 1) {
		$jakdb->update("checkstatus", ["typeo" => 0, "newo" => 0, "statuso" => time()], ["convid" => $page1]);
	}

	// reset the lastido session
	$_SESSION["activechatid"] = $page1;

	// Now this is a new chat and the operator comes from the a direct link (push notification, email)
	if (empty($livechat['operatorname']) && ($livechat['operatorid'] == 0 || $livechat['operatorid'] == JAK_USERID)) {
		$jakdb->update("checkstatus", ["newc" => 1, "operatorid" => JAK_USERID, "operator" => $jakuser->getVar("name"), "pusho" => $jakuser->getVar("push_notifications"), "statuso" => time()], ["convid" => $page1]);
		$jakdb->update("sessions", ["operatorid" => JAK_USERID, "operatorname" => $jakuser->getVar("name")], ["id" => $page1]);

		if (!empty($LC_ANSWERS) && is_array($LC_ANSWERS)) foreach ($LC_ANSWERS as $v) {
		
			if ($v["msgtype"] == 2 && $v["lang"] == (isset($_SESSION['jak_lcp_lang']) ? $_SESSION['jak_lcp_lang'] : JAK_LANG)) {
			
				$phold = array("%operator%","%client%","%email%");
				$replace   = array($jakuser->getVar("name"), $livechat['name'], JAK_EMAIL);
				$message = str_replace($phold, $replace, $v["message"]);

				$jakdb->insert("transcript", [ 
					"name" => $jakuser->getVar("name"),
					"message" => $message,
					"user" => JAK_USERID.'::'.$jakuser->getVar("username"),
					"operatorid" => JAK_USERID,
					"convid" => $page1,
					"class" => "admin",
					"time" => $jakdb->raw("NOW()")]);
				
			}
			
		}
	}

	// Remove the live preview file / fixed in 3.5.1
	$livepreviewfile = APP_PATH.JAK_CACHE_DIRECTORY.'/livepreview'.$page1.'.txt';

	if (file_exists($livepreviewfile)) {
		// Finally remove the file and start fresh
		unlink($livepreviewfile);
	}

	if (!$livechat) jak_redirect(BASE_URL);

	if (!jak_get_access("leads_all", $jakuser->getVar("permissions"), $jakuser->jakSuperadminaccess(JAK_USERID))) {

		if (is_numeric($jakuser->getVar("departments")) && $jakuser->getVar("departments") != 0 && $livechat['department'] != $jakuser->getVar("departments")) {
			jak_redirect(BASE_URL);
		} elseif (!((boolean)$jakuser->getVar("departments")) && $jakuser->getVar("departments") != 0 && !in_array($livechat['department'], $jakuser->getVar("departments"))) {
			jak_redirect(BASE_URL);
		} elseif ($jakuser->getVar("departments") == 0 && JAK_USERID != $livechat['operatorid']) {
			jak_redirect(BASE_URL);
		}
	}
}

// Title and Description
$SECTION_TITLE = $jkl['g138'];
$SECTION_DESC = "";

// Include the javascript file for results
$js_file_footer = 'js_live.php';
// Call the template
$template = 'live.php';

?>