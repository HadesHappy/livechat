<?php

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 6 May 1998 03:10:00 GMT");

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

if (!is_numeric($_GET['id'])) die("There is no such user!");

$sqlw = '';

// Now only get the department for the user
if ($_SESSION['usr_department'] && is_numeric($_SESSION['usr_department'])) {
	$sqlw = 'department = :depid AND status = 1 AND operatorid = 0 OR ';
}
if ($_SESSION['usr_department']) {
	$sqlw = 'department IN(:depid) AND status = 1 AND operatorid = 0 OR ';
}
if ($_SESSION['usr_department'] == 0) {
	$sqlw = 'department >= 0 AND status = 1 AND operatorid = 0 OR ';
}

$sth = $jakdb->pdo->prepare("SELECT id, operatorid, answered, updated, transferid, transfermsg FROM ".JAKDB_PREFIX."sessions WHERE ".$sqlw."operatorid = :oid AND status = 1 OR department = 0 AND status = 1 AND operatorid = 0 OR transferid = :oid AND status = 1");

$sth->bindParam(':depid', $_SESSION['usr_department'], PDO::PARAM_INT);
$sth->bindParam(':oid', $_GET['id'], PDO::PARAM_INT);

$sth->execute();

$result = $sth->fetchAll();

if (isset($result) && !empty($result)) {

	foreach ($result as $row) {
		
		// We have a transfer, need to display it!
		if ($row['transferid'] == $_GET['id']) {
			
			if ($row["transfermsg"]) $split_transfer_msg = explode(':#:', $row["transfermsg"]);
			
			// Display underneath the button
			$transfer_msg = '<p>'.$split_transfer_msg[1].' <a href="javascript:void(0)" onclick="acceptTransfer(0, '.$row['transferid'].', '.$row['id'].');"><i class="fa fa-times"></i></a> <a href="javascript:void(0)" onclick="acceptTransfer(1, '.$row['transferid'].', '.$row['id'].');"><i class="fa fa-check"></i></a></p>';
			$transferid = $row['transferid'];
		}
			
			$newConv = 0;
		
			// check for new conversations
			if ($row['operatorid'] == 0) {
				$newConv = 1;
			}
			if ($row['operatorid'] > 0 && ($row['updated'] > $row['answered'])) {
				$newConv = 2;
			}		
	}
	
	echo json_encode(array('newc' => $newConv, 'tid' => $transferid, 'tmsg' => $transfer_msg));
} else {

	echo json_encode(array('newc' => 0, 'tid' => 0, 'tmsg' => 0));
}
?>