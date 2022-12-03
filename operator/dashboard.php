<?php

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 5.0                   # ||
|| # ----------------------------------------- # ||
|| # Copyright 2021 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

// Check if the file is accessed only via index.php if not stop the script from running
if (!defined('JAK_ADMIN_PREVENT_ACCESS')) die('You cannot access this file directly.');

// Reset
$opmain = '';
$count = 0;

// Change for 3.0.3
use JAKWEB\JAKsql;

// Now if we have multi site we have fully automated process
if (JAK_SUPERADMINACCESS && !empty(JAKDB_MAIN_NAME) && JAK_MAIN_LOC && $jakhs['hostactive']) {
	// Database connection to the main site
	$jakdb1 = new JAKsql([
		// required
		'database_type' => JAKDB_MAIN_DBTYPE,
		'database_name' => JAKDB_MAIN_NAME,
		'server' => JAKDB_MAIN_HOST,
		'username' => JAKDB_MAIN_USER,
		'password' => JAKDB_MAIN_PASS,
		'charset' => 'utf8',
		'port' => JAKDB_MAIN_PORT,
		'prefix' => JAKDB_MAIN_PREFIX,
			         
		// [optional] driver_option for connection, read more from http://www.php.net/manual/en/pdo.setattribute.php
		'option' => [PDO::ATTR_CASE => PDO::CASE_NATURAL]
		]);

	// We get the user data from the main table
	$opmain = $jakdb1->get("users", ["id", "signup", "trial", "paidtill", "active"], ["AND" => ["opid" => JAK_USERID, "locationid" => JAK_MAIN_LOC]]);

	// Check if we have some new and unread tickets.
	$count = $jakdb1->count("support_tickets", ["AND" => ["userid" => $opmain["id"], "readtime" => 0]]);

	// We get the settings for the payment
    $sett = array();
    $settings = $jakdb1->select("settings", ["varname", "used_value"]);
    foreach ($settings as $v) {
        $sett[$v["varname"]] = $v["used_value"]; 
    }

	// Get stat out for the super operator
	$totalop = $jakdb->count("user");
	$totaldep = $jakdb->count("departments");
	$totalwidg = $jakdb->count("chatwidget");

}

// Statistics
$sessCtotal = $commCtotal = $statsCtotal = $visitCtotal = 0;
if (jak_get_access("statistic_all", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)) {

	// Get the stats
	$sessCtotal = $jakdb->count("sessions");
	$commCtotal = $jakdb->count("transcript");
	$statsCtotal = $jakdb->count("user_stats");
	$visitCtotal = $jakdb->count("buttonstats");
		
} else {

	// Get the stats
	$sessCtotal = $jakdb->count("sessions", ["operatorid" => JAK_USERID]);
	// Get all convid into an array
	$sessids = $jakdb->select("sessions", "id", ["operatorid" => JAK_USERID]);
	// Get all messages from the convids
	if ($sessids) {
		$commCtotal = $jakdb->count("transcript", ["convid" => $sessids]);
		$statsCtotal = $jakdb->count("user_stats", ["userid" => JAK_USERID]);
		$visitCtotal = $jakdb->count("buttonstats", ["depid" => [$jakuser->getVar("departments")]]);
	}

}

// Get the open chats for this operator
$statschat = false;
$openChats = 0;
if (jak_get_access("leads", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)) {

	if (jak_get_access("leads_all", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)) {

		$openChats = $jakdb->select("sessions", ["id", "name", "initiated"], ["ORDER" => ["id" => "DESC"], "LIMIT" => 10]);

	} else {

		$openChats = $jakdb->select("sessions", ["id", "name", "initiated"], ["AND" => ["operatorid" => $jakuser->getVar("id")], "ORDER" => ["id" => "DESC"], "LIMIT" => 10]);

	}

}

// Get the offline messages if allowed
$statscontact = false;
$openContacts = 0;
if (jak_get_access("off_all", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)) {

	$openContacts = $jakdb->select("contacts", ["id", "name", "email", "sent"], ["ORDER" => ["id" => "DESC"], "LIMIT" => 10]);

}

// Get the country list
$ctl = $jakdb->pdo->prepare("SELECT COUNT(id) AS total_country, countrycode, country FROM ".JAKDB_PREFIX."buttonstats WHERE countrycode != '' GROUP BY countrycode ORDER BY total_country DESC LIMIT 8");

$ctl->execute();

$ctlres = $ctl->fetchAll();

// Get the public operator chat, check if we have access
if (($jakhs['hostactive'] == 1 && $jakhs['groupchat'] == 1) || $jakhs['hostactive'] == 0) {
	$gcarray = array();
	$JAK_PUBLICCHAT = $jakdb->select("groupchat", ["id", "title", "opids", "lang"], ["AND" => ["active" => 1]]);
	if (isset($JAK_PUBLICCHAT) && !empty($JAK_PUBLICCHAT)) foreach ($JAK_PUBLICCHAT as $gc) {
		// Let's check if we have access
		if ($gc["opids"] == 0 || in_array(JAK_USERID, explode(",", $gc["opids"]))) {
			$gcarray[] = $gc;
		}
	}
}

// Title and Description
$SECTION_TITLE = $jkl['m'];
$SECTION_DESC = "";

// Include the javascript file for results
$js_file_footer = 'js_dashboard.php';
// Call the template
$template = 'dashboard.php';

?>