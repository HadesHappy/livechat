<?php

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 3.2                   # ||
|| # ----------------------------------------- # ||
|| # Copyright 2018 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

// Check if the file is accessed only via index.php if not stop the script from running
if (!defined('JAK_ADMIN_PREVENT_ACCESS')) die('You cannot access this file directly.');

// Check if the user has access to this file
if (!jak_get_access("statistic", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)) jak_redirect(BASE_URL);

// All the tables we need for this plugin
$errors = array();
$jaktable = 'sessions';
$jaktable1 = 'user_stats';
$jaktable2 = 'user';
$jaktable3 = 'departments';
$jaktable4 = 'transcript';
$jaktable5 = 'buttonstats';

$department = 0;

if (jak_get_access("statistic_all", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)) {

	$country_sql = $support_sql = $feedback_sql = $button_sql = '';
	
	// Departments
	$JAK_DEPARTMENTS = $jakdb->select($jaktable3, ["id", "title"], ["ORDER" => ["dorder" => "ASC"]]);
	
} else {
	
	$country_sql = ' operatorid = "'.JAK_USERID.'" AND';
	$support_sql = ' operatorid = "'.JAK_USERID.'" AND';
	$feedback_sql = ' userid = "'.JAK_USERID.'" AND';
	$button_sql = ' WHERE opid = "'.JAK_USERID.'"';
}

$_SESSION["stat_start_date"] = '2014-01-01';
$_SESSION["stat_end_date"] = date("Y-m-d");

// Get the time into a session
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	if ($_POST["start_date"]) {

		$start_date = $_POST["start_date"];
		$end_date = $_POST["end_date"];
		
		if ($start_date == $end_date) {
			
			$country_sql = " (UNIX_TIMESTAMP(DATE_FORMAT(initiated, '%Y-%c-%d')) = UNIX_TIMESTAMP('".$end_date."')) AND";
			$support_sql = " (UNIX_TIMESTAMP(DATE_FORMAT(t1.initiated, '%Y-%c-%d')) = UNIX_TIMESTAMP('".$end_date."')) AND";
			$feedback_sql = " (UNIX_TIMESTAMP(DATE_FORMAT(t1.time, '%Y-%c-%d')) = UNIX_TIMESTAMP('".$end_date."')) AND";
			$button_sql = $button_sql ? $button_sql." AND (UNIX_TIMESTAMP(DATE_FORMAT(time, '%Y-%c-%d')) = UNIX_TIMESTAMP('".$end_date."'))" : " WHERE (UNIX_TIMESTAMP(DATE_FORMAT(time, '%Y-%c-%d')) = UNIX_TIMESTAMP('".$end_date."'))";
			
		} else {
		
			$country_sql = " (initiated BETWEEN UNIX_TIMESTAMP('".$start_date."') AND UNIX_TIMESTAMP('".$end_date."')) AND";
			$support_sql = " (t1.initiated BETWEEN UNIX_TIMESTAMP('".$start_date."') AND UNIX_TIMESTAMP('".$end_date."')) AND";
			$feedback_sql = " (UNIX_TIMESTAMP(t1.time) BETWEEN UNIX_TIMESTAMP('".$start_date."') AND UNIX_TIMESTAMP('".$end_date."')) AND";
			$button_sql = $button_sql ? $button_sql." AND (UNIX_TIMESTAMP(time) BETWEEN UNIX_TIMESTAMP('".$start_date."') AND UNIX_TIMESTAMP('".$end_date."'))" : " WHERE (UNIX_TIMESTAMP(time) BETWEEN UNIX_TIMESTAMP('".$start_date."') AND UNIX_TIMESTAMP('".$end_date."'))";
		
		}
		
		$_SESSION["stat_start_date"] = $start_date;
		$_SESSION["stat_end_date"] = $end_date;
	
	}
	
	if (isset($_POST["jak_depid"]) && is_numeric($_POST["jak_depid"]) && $_POST["jak_depid"] != 0)  {
	
		$department = $_POST["jak_depid"];
	
		$country_sql = $country_sql." department = '".$department."' AND";
		$support_sql = $support_sql." t1.department = '".$department."' AND";
		$button_sql = $button_sql ? $button_sql." AND depid = '".$department."'" : " WHERE depid = '".$department."'";
	
	}

}

if (jak_get_access("statistic_all", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)) {

	// Get the stats
	$sessCtotal = $jakdb->count($jaktable);
	$commCtotal = $jakdb->count($jaktable4);
	$statsCtotal = $jakdb->count($jaktable1);
	$visitCtotal = $jakdb->count($jaktable5);

} else {

	// Get the stats
	$sessCtotal = $jakdb->count($jaktable, ["operatorid" => JAK_USERID]);
	// Get all convid into an array
	$sessids = $jakdb->select($jaktable, "id", ["operatorid" => JAK_USERID]);
	// Get all messages from the convids
	$commCtotal = $jakdb->count($jaktable4, ["convid" => $sessids]);
	$statsCtotal = $jakdb->count($jaktable1, ["userid" => JAK_USERID]);
	$visitCtotal = $jakdb->count($jaktable5, ["depid" => [$jakuser->getVar("departments")]]);

}
		 
// Get the country list
$ctl = $jakdb->pdo->prepare("SELECT COUNT(id) AS total_country, countrycode, country FROM ".JAKDB_PREFIX.$jaktable." WHERE".$country_sql." countrycode != '' GROUP BY countrycode ORDER BY total_country DESC LIMIT 10");

$ctl->execute();

$ctlres = $ctl->fetchAll();

if (isset($ctlres) && !empty($ctlres)) foreach ($ctlres as $row) {
	$lsdata[] = "['".$row['country']."', ".$row['total_country']."]";
}
		 
// Get the support statistic
$sup = $jakdb->pdo->prepare("SELECT COUNT(t1.id) AS total, SUM(t1.ended - t1.initiated) AS total_support, t2.username FROM ".JAKDB_PREFIX.$jaktable." AS t1 LEFT JOIN ".JAKDB_PREFIX.$jaktable2." AS t2 ON(t1.operatorid = t2.id) WHERE".$support_sql." ended > 0 AND t1.operatorid != 0 GROUP BY operatorid ORDER BY operatorid ASC LIMIT 20");

$sup->execute();

$supres = $sup->fetchAll();

if (isset($supres) && !empty($supres)) foreach ($supres as $rowst1) {
		         
	// get the operators in one table
	$arrayoperator[] = $rowst1['username'];
		         
	// get the days in one table
	$arraytotal[] = $rowst1['total'];
		         
	$arraytotalsupport[] = $rowst1['total_support'];
}
		 
// Get the feedback statistic
$fed = $jakdb->pdo->prepare("SELECT COUNT(t1.id) AS total_id, SUM(t1.vote) AS total_vote, SUM(t1.support_time) AS total_support, t2.username FROM ".JAKDB_PREFIX.$jaktable1." AS t1 LEFT JOIN ".JAKDB_PREFIX.$jaktable2." AS t2 ON(t1.userid = t2.id) WHERE".$feedback_sql." t1.userid != 0 GROUP BY t1.userid ORDER BY t1.userid DESC LIMIT 20");

$fed->execute();

$fedres = $fed->fetchAll();

if (isset($fedres) && !empty($fedres)) foreach ($fedres as $rowst4) {
		         
	// collect each record into $_data
	$fostat1[] = round(($rowst4['total_vote'] / $rowst4['total_id']), 2);
		         
	// get the operators in one table
	$arrayoperatorf[] = $rowst4['username'];
		         
	// total for each user
	$fostat2[] = $rowst4['total_id'];
}
		 
// Get the button statistic
$btn = $jakdb->pdo->prepare("SELECT SUM(hits) AS total_hits, referrer FROM ".JAKDB_PREFIX."buttonstats".$button_sql." GROUP BY referrer ORDER BY total_hits DESC LIMIT 10");

$btn->execute();

$btnres = $btn->fetchAll();

if (isset($btnres) && !empty($btnres)) foreach ($btnres as $rowst3) {
	// collect each record into $_data
	$fostat13[] = "['".parse_url($rowst3['referrer'], PHP_URL_PATH)."', ".$rowst3['total_hits']."]";
}
		 
$stat1op = $stat1opt = $statsuptime = $stat1totalf = $stat1total = $stat1vote = $stat1country = $stat1ref = '';
// Load operators
if (!empty($arrayoperator)) $stat1op = join("', '", $arrayoperator);
if (!empty($arraytotal)) $stat1opt = join(", ", $arraytotal);
if (!empty($arraytotalsupport)) $statsuptime = join(", ", $arraytotalsupport);
// Feedback Operator
if (!empty($arrayoperatorf)) $stat1totalf = join("', '", $arrayoperatorf);
// Total
if (!empty($fostat2)) $stat1total = join(", ", $fostat2);
// Average Vote
if (!empty($fostat1)) $stat1vote = join(", ", $fostat1);
		 
// Load all countries
if (!empty($lsdata)) $stat1country = join(", ", $lsdata);
		 
// Load referrer
if (!empty($fostat13)) $stat1ref = join(", ", $fostat13);
		 
// Title and Description
$SECTION_TITLE = $jkl["m10"];
$SECTION_DESC = "";
		
// Include the javascript file for results
$js_file_footer = 'js_statistics.php';
		 
// Call the template
$template = 'statistics.php';

?>