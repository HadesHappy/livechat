<?php

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH                                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2016 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

if (!file_exists('../../config.php')) die('ajax/[available.php] config.php not exist');
require_once '../../config.php';

if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !isset($_SESSION['jak_lcp_idhash'])) die("Nothing to see here");

if (!is_numeric($_POST['id'])) die("There is no such user!");

$sendfile = $jakdb->get("checkstatus", "files", ["convid" => $_POST['id']]);

if ($sendfile == 1) {
	$jakdb->update("checkstatus", ["files" => 0], ["convid" => $_POST['id']]);
	die(json_encode(array('status' => 1)));
		
} else {
	$jakdb->update("checkstatus", ["files" => 1], ["convid" => $_POST['id']]);
	die(json_encode(array('status' => 0)));
}


?>