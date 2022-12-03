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
if (!jak_get_access("logs", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)) jak_redirect(BASE_URL);

// All the tables we need for this plugin
$errors = array();
$jaktable = 'whatslog';
 
 switch ($page1) {
   	case 'truncate':
   	    
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

		// Let's go on with the script
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$jkp = $_POST;

			if (isset($jkp['action']) && $jkp['action'] == "delete") {

				if (isset($jkp['jak_delete_logs'])) {
			    
				    $lockuser = $jkp['jak_delete_logs'];
				
				    for ($i = 0; $i < count($lockuser); $i++) {
				        $locked = $lockuser[$i];
				       	$result = $jakdb->delete($jaktable, ["id" => $locked]);
				    }
				        
				    $_SESSION["successmsg"] = $jkl['g14'];
					jak_redirect($_SESSION['LCRedirect']);
				        
				}
				    
				$_SESSION["infomsg"] = $jkl['i'];
				jak_redirect($_SESSION['LCRedirect']);

			}

			$_SESSION["infomsg"] = $jkl['i'];
			jak_redirect($_SESSION['LCRedirect']);
		    
		}

		// Total Logs
		$totalAll = 0;
		$busy_operator["username"] = $busy_client["email"] = '-';

	    // Get the totals logs
	    $totalAll = $jakdb->count($jaktable);

	    if (isset($totalAll) && $totalAll != 0) {
		    // Get the busiest operator
			$busy_operator = $jakdb->query("SELECT COUNT(t1.operatorid) AS totalOP, t2.username FROM ".JAKDB_PREFIX."whatslog AS t1 LEFT JOIN ".JAKDB_PREFIX."user AS t2 ON(t1.operatorid = t2.id) WHERE t1.operatorid != 0 GROUP BY t1.operatorid ORDER BY totalOP DESC LIMIT 1")->fetch();

		}
	
		// Title and Description
		$SECTION_TITLE = $jkl["m6"];
		$SECTION_DESC = "";
		
		// Include the javascript file for results
		$js_file_footer = 'js_logs.php';
		
		// Call the template
		$template = 'logs.php';
	}
?>