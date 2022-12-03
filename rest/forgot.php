<?php

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 6 May 1998 03:10:00 GMT");

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 5.0.4                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2022 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

if (!file_exists('config.php')) die('rest_api config.php not exist');
require_once 'config.php';

$email = "";
// Get the email
if (isset($_REQUEST['email']) && !empty($_REQUEST['email']) && filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL)) $email = filter_var($_REQUEST['email'], FILTER_SANITIZE_EMAIL);

if (!empty($email)) {

	$fwhen = time();

	// Check if this user exist
	$user_check = $jakuserlogin->jakForgotpassword($email, $fwhen);

	if ($user_check == true) {

		// Include the mail library
		include_once APP_PATH.'class/PHPMailerAutoload.php';

		// Import the language file
		include_once(APP_PATH.JAK_OPERATOR_LOC.'/lang/'.JAK_LANG.'.php');

		// Get user details
	    $oname = $jakdb->get("user", "name", ["AND" => ["email" => $email, "access" => 1]]);

	    // Ok, we send the email // email address, cc email address, reply to, subject, message, attachment
        if (jak_send_email($email, "", "", JAK_TITLE.' - '.$jkl['l13'], sprintf($jkl['l14'], $oname, '<a href="'.JAK_rewrite::jakParseurl('forgot-password', $fwhen).'">'.JAK_rewrite::jakParseurl('forgot-password', $fwhen).'</a>', JAK_TITLE), "")) {
	        die(json_encode(array('status' => true)));
	    }

 	} else {
 		die(json_encode(array('status' => false, 'errorcode' => 6)));
 	}
}

die(json_encode(array('status' => false, 'errorcode' => 5)));
?>