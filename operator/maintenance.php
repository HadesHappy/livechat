<?php

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 5.0.2                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2022 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

// Check if the file is accessed only via index.php if not stop the script from running
if (!defined('JAK_ADMIN_PREVENT_ACCESS')) die('You cannot access this file directly.');

// Check if the user has access to this file
if (!jak_get_access("maintenance", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)) jak_redirect(BASE_URL);

// Check and validate
$verify_response = $jaklic->verify_license(false);
$licmsg = $verify_response['message'];

// Flag to select step
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jkp = $_POST;
    
if (isset($jkp['delCache'])) {
	
	// Now let us delete the all the cache file
	$cacheallfiles = APP_PATH.JAK_CACHE_DIRECTORY.'/';
	$msfi = glob($cacheallfiles."*.php");
	if ($msfi) foreach ($msfi as $filen) {
	    if (file_exists($filen)) unlink($filen);
	}

	// Delete the live typing review files
	$msfipr = glob($cacheallfiles."livepreview*.txt");
	if ($msfipr) foreach ($msfipr as $fileprev) {
	    if (file_exists($fileprev)) unlink($fileprev);
	}
	
	// Delete the chat file
	if (file_exists($cacheallfiles.'chats.txt')) unlink($cacheallfiles.'chats.txt');

	// Write the log file each time someone login after to show success
    JAK_base::jakWhatslog('', JAK_USERID, 0, 10, 0, (isset($_COOKIE['WIOgeoData']) ? $_COOKIE['WIOgeoData'] : ''), $jakuser->getVar("username"), $_SERVER['REQUEST_URI'], $ipa, $valid_agent);
	
	$_SESSION["successmsg"] = $jkl['g14'];
    jak_redirect(JAK_rewrite::jakParseurl('maintenance'));

}

if (isset($jkp['delTokens'])) {

	$result = $jakdb->query('TRUNCATE '.JAKDB_PREFIX.'push_notification_devices');
   		
	if (!$result) {
		$_SESSION["infomsg"] = $jkl['i'];
		jak_redirect($_SESSION['LCRedirect']);
	} else {

		// Write the log file each time someone login after to show success
    	JAK_base::jakWhatslog('', JAK_USERID, 0, 15, 0, (isset($_COOKIE['WIOgeoData']) ? $_COOKIE['WIOgeoData'] : ''), $jakuser->getVar("username"), $_SERVER['REQUEST_URI'], $ipa, $valid_agent);

	    $_SESSION["successmsg"] = $jkl['g14'];
		jak_redirect(JAK_rewrite::jakParseurl('maintenance'));
	}

}

if (isset($jkp['optimize'])) {
	
	$tables = $jakdb->query('SHOW TABLES')->fetchAll();

    foreach ($tables as $db => $tablename) { 
        $jakdb->query('OPTIMIZE TABLE '.$tablename); 
    }

    // Write the log file each time someone login after to show success
    JAK_base::jakWhatslog('', JAK_USERID, 0, 11, 0, (isset($_COOKIE['WIOgeoData']) ? $_COOKIE['WIOgeoData'] : ''), $jakuser->getVar("username"), $_SERVER['REQUEST_URI'], $ipa, $valid_agent);

    $_SESSION["successmsg"] = $jkl['g14'];
    jak_redirect(JAK_rewrite::jakParseurl('maintenance'));

}

if (isset($jkp['regLicense'])) {

	if (!empty($_POST['jak_lic']) && !empty($_POST['jak_licusr'])) {
		$license_code = strip_tags(trim($_POST["jak_lic"]));
	  	$env_name = strip_tags(trim($_POST["jak_licusr"]));

		// Now let's check the license
	  	$activate_response = $jaklic->activate_license($license_code, $env_name);
	  	if (empty($activate_response)) {
			$errors['e1'] = LB_TEXT_CONNECTION_FAILED;
	  	}

	  	if ($activate_response['status'] != true) { 
	    	$errors['e1'] = $activate_response['message'];
	  	} else {

	  		// We update the order number
	  		$jakdb->update("settings", ["used_value" => filter_var($_POST['jak_licusr'], FILTER_SANITIZE_FULL_SPECIAL_CHARS)], ["varname" => "o_number"]);

	  		// Now let us delete the all the cache file
            $cacheallfiles = APP_PATH.JAK_CACHE_DIRECTORY.'/';
            $msfi = glob($cacheallfiles."*.php");
            if ($msfi) foreach ($msfi as $filen) {
                if (file_exists($filen)) unlink($filen);
            }

		  	$_SESSION["successmsg"] = $jkl['g14'];
	    	jak_redirect(JAK_rewrite::jakParseurl('maintenance'));
	    }

	} else {
		$errors['e1'] = $jkl['e28'];
		$errors['e2'] = $jkl['e8'];
	}

}

if (isset($jkp['deregLicense']) && JAK_SUPERADMINACCESS) {

	$deactivate_response = $jaklic->deactivate_license();
    if (empty($deactivate_response)) {
    	$errors['e1'] = LB_TEXT_CONNECTION_FAILED;
    }

    if ($deactivate_response['status'] != true) { 
	    $errors['e1'] = $deactivate_response['message'];
	} else {
		$_SESSION["successmsg"] = $jkl['g14'];
	    jak_redirect(JAK_rewrite::jakParseurl('maintenance'));
	}

}

}

$totalPND = $totalFiles = 0;
// Get the totals logs
$totalPND = $jakdb->count("push_notification_devices");

// Count all files
$totalFiles = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(APP_PATH.JAK_FILES_DIRECTORY), RecursiveIteratorIterator::SELF_FIRST);

// Title and Description
$SECTION_TITLE = $jkl["m19"];
$SECTION_DESC = "";

// Call the template
$template = 'maintenance.php';

?>