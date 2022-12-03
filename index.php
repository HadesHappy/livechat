<?php

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 5.0.4                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2022 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

// prevent direct php access
define('JAK_PREVENT_ACCESS', 1);

if (!file_exists('config.php')) die('[index.php] config.php not exist');
require_once 'config.php';

$page = ($tempp ? jak_url_input_filter($tempp) : '');
$page1 = ($tempp1 ? jak_url_input_filter($tempp1) : '');
$page2 = ($tempp2 ? jak_url_input_filter($tempp2) : '');
$page3 = ($tempp3 ? jak_url_input_filter($tempp3) : '');
$page4 = ($tempp4 ? jak_url_input_filter($tempp4) : '');
$page5 = ($tempp5 ? jak_url_input_filter($tempp5) : '');
$page6 = ($tempp6 ? jak_url_input_filter($tempp6) : '');
$page7 = ($tempp7 ? jak_url_input_filter($tempp7) : '');

// Default
$widgetid = 1;
$BT_LANGUAGE = $widgetlang = JAK_LANG;

// We have the main chat call
if (isset($page) && $page == 'lc') {
	// Write the chat widget id
	if (isset($page2) && is_numeric($page2)) $widgetid = $page2;
	// Write the chat language
	if (isset($page3) && !empty($page3)) $widgetlang = $page3;
}

// Ok we have a link, set the sessions.
if (isset($page) && $page == 'link') {
	// Write the chat widget id
	if (isset($page1) && is_numeric($page1)) $widgetid = $page1;
	// Write the chat language
	if (isset($page2) && !empty($page2)) $widgetlang = $page2;
}

// Set the group chat language
if (isset($page) && $page == 'groupchat') {
	// Write the chat language
	if (isset($page2) && !empty($page2)) $widgetlang = $page2;
}

// Now we don't have a widget id session, set one
$cachewidget = APP_PATH.JAK_CACHE_DIRECTORY.'/widget'.$widgetid.'.php';
if (file_exists($cachewidget)) include_once $cachewidget;

// Get the language file if different from settings
if (isset($widgetlang) && $widgetlang != JAK_LANG) $BT_LANGUAGE = $widgetlang;

// Import the language file
if ($BT_LANGUAGE && file_exists(APP_PATH.'lang/'.strtolower($BT_LANGUAGE).'.php')) {
    include_once(APP_PATH.'lang/'.strtolower($BT_LANGUAGE).'.php');
} else {
    include_once(APP_PATH.'lang/'.JAK_LANG.'.php');
}

// If Referer Zero go to the session url
if (!isset($_SERVER['HTTP_REFERER'])) {
    $_SERVER['HTTP_REFERER'] = BASE_URL;
}

// Lang and pages file for template
define('JAK_SITELANG', JAK_LANG);

// Assign Pages to template
define('JAK_PAGINATE_ADMIN', 0);

// Define the avatarpath in the settings
define('JAK_FILEPATH_BASE', BASE_URL.JAK_FILES_DIRECTORY);

// Define the real request
$realrequest = substr($getURL->jakRealrequest(), 1);
define('JAK_PARSE_REQUEST', $realrequest);

// Check if the ip or range is blocked, if so redirect to offline page with a message
$USR_IP_BLOCKED = false;
if (JAK_IP_BLOCK) {
	$blockedips = explode(',', JAK_IP_BLOCK);
	// Do we have a range
	if (is_array($blockedips)) foreach ($blockedips as $bip) {
		$blockedrange = explode(':', $bip);
		
		if (is_array($blockedrange)) {
		
			$network=ip2long($blockedrange[0]);
			$mask=ip2long($blockedrange[1]);
			$remote=ip2long($ipa);
			
			if (($remote & $mask) == $network) {
			    $USR_IP_BLOCKED = $jkl['e11'];
			}	
		}
	}
	// Now let's check if we have another match
	if (in_array($ipa, $blockedips)) {
		$USR_IP_BLOCKED = $jkl['e11'];
	}
}

// Now get the available departments
$online_op = false;
if (JAK_HOLIDAY_MODE != 0) {
	$online_op = false;
} else {
	if (isset($widgetid)) $online_op = online_operators($LC_DEPARTMENTS, $jakwidget['depid'], $jakwidget['opid']);
}

// Set the check page to 0
$JAK_CHECK_PAGE = 0;
	
	// Link we need a redirect
	if ($page == 'link') {
		$_SESSION['islinked'] = true;
		// Set the session for this user
		create_session_id($jakwidget['depid'], $jakwidget['opid'], $ipa);
		// Redirect to the open chat
		jak_redirect(JAK_rewrite::jakParseurl('lc', 'open', $widgetid, $widgetlang));
	}
	// The chat class
	if ($page == 'lc') {
		require_once 'lc.php';
		$JAK_CHECK_PAGE = 1;
		$PAGE_SHOWTITLE = 1;
	}
	// Group Chat
	if ($page == 'groupchat') {
		require_once 'groupchat.php';
		$JAK_CHECK_PAGE = 1;
		$PAGE_SHOWTITLE = 1;
	}
    // Get the 404 page
   	if ($page == '404') {
   	    $PAGE_TITLE = '404 ';
   	    require_once '404.php';
   	    $JAK_CHECK_PAGE = 1;
   	    $PAGE_SHOWTITLE = 1;
   	}

// if page not found
if ($JAK_CHECK_PAGE == 0) jak_redirect(BASE_URL.JAK_OPERATOR_LOC);
?>