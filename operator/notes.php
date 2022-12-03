<?php

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 4.0                   # ||
|| # ----------------------------------------- # ||
|| # Copyright 2020 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

// Check if the file is accessed only via index.php if not stop the script from running
if (!defined('JAK_ADMIN_PREVENT_ACCESS')) die('You cannot access this file directly.');

// Check if the user has access to this file
if (!JAK_USERID || !JAK_ADMINACCESS) jak_redirect(BASE_URL);

// All the tables we need for this plugin
$errors = array();

// Let's go on with the script
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$jkp = $_POST;

	$result = $jakdb->update("sessions", ["notes" => $jkp['note']], ["id" => $jkp['convid']]);
	
	if ($result) {
		
		// Ajax Request
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
		
			header('Cache-Control: no-cache');
			die(json_encode(array('status' => 1, 'label' => "note")));
			
		}
	} else {
		// Ajax Request
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
			header('Cache-Control: no-cache');
			die(json_encode(array('status' => 2, 'txt' => $jkl['i'])));       
		}
	}

}

// Get the note
if (isset($page1) && is_numeric($page1)) $getnote = $jakdb->get("sessions", ["name", "notes"], ["id" => $page1]);
		
// Call the template
$template = 'notes.php';

?>