<?php

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 3.2                   # ||
|| # ----------------------------------------- # ||
|| # Copyright 2018 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

if (!file_exists('../../config.php')) die('[loadmap.php] config.php not exist');
require_once '../../config.php';

// No ajax requests and user has no permission
if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !isset($_SESSION['jak_lcp_idhash'])) die("Nothing to see here");

if (isset($_POST["depid"]) && is_numeric($_POST["depid"]) && $_POST["depid"] != 0) {

	$maparray = $jakdb->select("sessions", ["id", "countrycode", "country", "city", "name", "latitude", "longitude"], ["AND" => ["department" => $_POST["depid"], "longitude[!]" => ''], "ORDER" => ["id" => "DESC"], "LIMIT" => 2000]);

} else {

	$maparray = $jakdb->select("sessions", ["id", "countrycode", "country", "city", "name", "latitude", "longitude"], ["longitude[!]" => '', "ORDER" => ["id" => "DESC"], "LIMIT" => 2000]);
}
  
if ($maparray) {
    header('Cache-Control: no-cache');
    die(json_encode(array("status" => 1, "markers" => $maparray)));
}

die(json_encode(array("status" => 0)));

?>