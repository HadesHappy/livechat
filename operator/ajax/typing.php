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

if (is_numeric($_POST['conv'])) {

	if ($_POST['status'] == 1) {
		$result = $jakdb->update("checkstatus", ["typeo" => 1], ["convid" => $_POST['conv']]);
	} else {
		$result = $jakdb->update("checkstatus", ["typeo" => 0], ["convid" => $_POST['conv']]);
	}
	
	if ($result) die(json_encode(array('tid' => 1)));

} else {
	die(json_encode(array('tid' => 0)));
}
?>