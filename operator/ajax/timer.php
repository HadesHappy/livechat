<?php

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 3.8.4                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2018 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

if (!file_exists('../../config.php')) die('ajax/[available.php] config.php not exist');
require_once '../../config.php';

if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !isset($_SESSION['jak_lcp_idhash'])) die("Nothing to see here");

if (!is_numeric($_POST['id'])) die("There is no such user!");

// Sanitize the user status
$ustatus = filter_var($_POST['ops'], FILTER_SANITIZE_NUMBER_INT);

// Set status to false
$status = 0;

// Check if user is logged in
$jakuserlogin = new JAK_userlogin();
$jakuserrow = $jakuserlogin->jakChecklogged();
$jakuser = new JAK_user($jakuserrow);
if ($jakuser) {
	// Update User
	$jakdb->update("user", ["available" => $ustatus, "lastactivity" => time(), "session" => session_id()], ["id" => $jakuser->getVar("id")]);
	// Check if it is succesful
	$status = 1;
}

die(json_encode(array('status' => $status, 'usrstatus' => $ustatus)));

?>