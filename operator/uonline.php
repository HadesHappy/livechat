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

$jaktable = 'buttonstats';
$jaktable1 = 'user_stats';
$jaktable2 = 'user';
$jaktable3 = 'sessions';

// We do load the user online list
$JAK_UONLINE = 1;

switch ($page1) {
	case 'opstat':
	
		if (is_numeric($page2) && $jakuser->getVar("operatorlist")) {

			$totalAll = 0;
			
			// Get the operator stats
			$row = $jakdb->get($jaktable2, ["id", "username", "name"], ["id" => $page2]);
			$totalAll = $jakdb->count($jaktable3, ["AND" => ["operatorid" => $page2, "status" => 1]]);
			$totalAllu = $jakdb->count($jaktable1, ["userid" => $page2]);
			$total_vote = $jakdb->sum($jaktable1, "vote", ["userid" => $page2]);
			$total_support = $jakdb->sum($jaktable1, "support_time", ["userid" => $page2]);
			
		}
		
		// Call the template
		$template = 'opstat.php';
	break;
	case 'delete':
	
		if (!JAK_SUPERADMINACCESS) jak_redirect(BASE_URL);
   		
       	$result = $jakdb->delete($jaktable, ["id" => $page2]);
		
		if (!$result) {
   			$_SESSION["infomsg"] = $jkl['i'];
		    jak_redirect($_SESSION['LCRedirect']);
		} else {
       		$_SESSION["successmsg"] = $jkl['g14'];
		    jak_redirect($_SESSION['LCRedirect']);
   		}
   		
  	break;
  	case 'truncate':
  	
  		if (!JAK_SUPERADMINACCESS) jak_redirect(BASE_URL);
  		
  	    $result = $jakdb->query('TRUNCATE '.JAKDB_PREFIX.$jaktable);
  		
  		if (!$result) {
  			$_SESSION["infomsg"] = $jkl['i'];
		    jak_redirect($_SESSION['LCRedirect']);
  		} else {
  		    $_SESSION["successmsg"] = $jkl['g14'];
		    jak_redirect($_SESSION['LCRedirect']);
  		}
  		 
  	break;
  	default:
		
		$totalAll = 0;
		$UONLINE_ALL = array();
		
		// Now only get the department for the user
		if (isset($_SESSION['usr_department']) && is_numeric($_SESSION['usr_department']) && $_SESSION['usr_department'] != 0) {
			$totalAll = $jakdb->count($jaktable, ["depid" => $_SESSION['usr_department']]);
		} elseif (isset($_SESSION['usr_department']) && $_SESSION['usr_department'] == 0) {
			$totalAll = $jakdb->count($jaktable);
		} elseif (isset($_SESSION['usr_department'])) {
			$totalAll = $jakdb->count($jaktable, ["depid" => explode(",",$_SESSION['usr_department'])]);
		}
		 
		if ($totalAll != 0) {
		
			// Paginator
			$uonline = new JAK_Paginator;
			$uonline->items_total = $totalAll;
			$uonline->mid_range = 10;
			$uonline->items_per_page = 20;
			$uonline->jak_get_page = $page1;
			$uonline->jak_where = JAK_rewrite::jakParseurl('uonline');
			$uonline->paginate();
			$JAK_PAGINATE = $uonline->display_pages();

			// Now only get the department for the user
			if (isset($_SESSION['usr_department']) && is_numeric($_SESSION['usr_department']) && $_SESSION['usr_department'] != 0) {
				$UONLINE_ALL = $jakdb->select("buttonstats", ["[>]sessions" => ["session" => "session"]], ["buttonstats.id", "buttonstats.referrer", "buttonstats.firstreferrer", "buttonstats.agent", "buttonstats.hits", "buttonstats.ip", "buttonstats.country", "buttonstats.countrycode", "buttonstats.lasttime", "buttonstats.time", "buttonstats.readtime", "sessions.initiated", "sessions.ended"], ["buttonstats.depid" => $_SESSION['usr_department'], "GROUP" => "buttonstats.session", "ORDER" => ["buttonstats.lasttime" => "DESC"], "LIMIT" => $uonline->limit]);
			} elseif (isset($_SESSION['usr_department']) && $_SESSION['usr_department'] == 0) {
				$UONLINE_ALL = $jakdb->select("buttonstats", ["[>]sessions" => ["session" => "session"]], ["buttonstats.id", "buttonstats.referrer", "buttonstats.firstreferrer", "buttonstats.agent", "buttonstats.hits", "buttonstats.ip", "buttonstats.country", "buttonstats.countrycode", "buttonstats.lasttime", "buttonstats.time", "buttonstats.readtime", "sessions.initiated", "sessions.ended"], ["GROUP" => "buttonstats.session", "ORDER" => ["buttonstats.lasttime" => "DESC"], "LIMIT" => $uonline->limit]);
			} elseif (isset($_SESSION['usr_department'])) {
				$UONLINE_ALL = $jakdb->select("buttonstats", ["[>]sessions" => ["session" => "session"]], ["buttonstats.id", "buttonstats.referrer", "buttonstats.firstreferrer", "buttonstats.agent", "buttonstats.hits", "buttonstats.ip", "buttonstats.country", "buttonstats.countrycode", "buttonstats.lasttime", "buttonstats.time", "buttonstats.readtime", "sessions.initiated", "sessions.ended"], ["buttonstats.depid" => explode(",",$_SESSION['usr_department']), "GROUP" => "buttonstats.session", "ORDER" => ["buttonstats.lasttime" => "DESC"], "LIMIT" => $uonline->limit]);
			}
			
		}
		
		// Title and Description
		$SECTION_TITLE = $jkl["g122"];
		$SECTION_DESC = "";

		// Include the javascript file for results
		$js_file_footer = 'js_uonline.php';
		
		// Call the template
		$template = 'uonline.php';
}
?>