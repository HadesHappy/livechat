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

$userid = $loginhash = $contactid = "";
$errors = $rowi = array();
$sendform = false;
if (isset($_REQUEST['userid']) && !empty($_REQUEST['userid']) && is_numeric($_REQUEST['userid'])) $userid = $_REQUEST['userid'];
if (isset($_REQUEST['loginhash']) && !empty($_REQUEST['loginhash'])) $loginhash = $_REQUEST['loginhash'];
if (isset($_REQUEST['contactid']) && !empty($_REQUEST['contactid'])) $contactid = $_REQUEST['contactid'];
if (isset($_REQUEST['sendform']) && !empty($_REQUEST['sendform'])) $sendform = $_REQUEST['sendform'];

if (!empty($userid) && !empty($loginhash)) {

	// Let's check if we are logged in
	$usr = $jakuserlogin->jakCheckrestlogged($userid, $loginhash);

	if ($usr) {

		if (!empty($contactid)) $rowi = $jakdb->get("contacts", ["name", "email", "message", "latitude", "longitude"], ["id" => $contactid]);

		if ($sendform && !empty($contactid)) {

			if (empty($_REQUEST['subject'])) {
			    $errors['subject'] = true;
			}

			if (empty($_REQUEST['message'])) {
			    $errors['message'] = true;
			}

			if (count($errors) == 0) {

				// Ok, we send the email // email address, cc email address, reply to, subject, message, attachment
        		if (jak_send_email($rowi['email'], "", "", trim($_REQUEST['subject']), trim(nl2br($_REQUEST['message'])), "")) {

	  				// Get the operator details
	  				$jakuser = new JAK_user($usr);
	  			  
	  			  	// Insert the stuff into the database
	  			  	$jakdb->insert("contactsreply", [ 
	  			  	"contactid" => $contactid,
	  			  	"operatorid" => $jakuser->getVar("id"),
	  			  	"operatorname" => $jakuser->getVar("username"),
	  			  	"subject" => trim($_REQUEST['subject']),
	  			  	"message" => trim($_REQUEST['message']),
	  			  	"sent" => $jakdb->raw("NOW()")]);
	  			  	
	  			  	$jakdb->update("contacts", ["reply" => 1, "answered" => $jakdb->raw("NOW()")], ["id" => $contactid]);

	  			  	// Form has been sent, let's send the success status
					die(json_encode(array('status' => true)));

	  			}

			} else {
				die(json_encode(array('status' => false, 'errors' => $errors)));
			}

		}

		// Display the message content and location from the client
		die(json_encode(array('status' => true, 'data' => $rowi)));

	} else {
		die(json_encode(array('status' => false, 'errorcode' => 1, 'errorcode' => false)));
	}
}

die(json_encode(array('status' => false, 'errorcode' => 7, 'errorcode' => false)));
?>