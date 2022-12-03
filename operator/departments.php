<?php

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 4.0.2                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2020 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

// Check if the file is accessed only via index.php if not stop the script from running
if (!defined('JAK_ADMIN_PREVENT_ACCESS')) die('You cannot access this file directly.');


// Check if the user has access to this file
if (!jak_get_access("departments", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)) jak_redirect(BASE_URL);

// All the tables we need for this plugin
$errors = array();
$jaktable = 'departments';

// Reset some vars
$newdep = true;
$totalChange = 0;
$busy_department = '-';
$lastChange = '';

// Now start with the plugin use a switch to access all pages
switch ($page1) {

	case 'delete':
		 
		// Check if user exists and can be deleted
		if (is_numeric($page2) && $page2 != 1) {
		        
			// Now check how many languages are installed and do the dirty work
			$result = $jakdb->delete($jaktable, ["id" => $page2]);
		
			if (!$result) {
		    	$_SESSION["infomsg"] = $jkl['i'];
		    	jak_redirect($_SESSION['LCRedirect']);
			} else {
			
				// Now let us delete the define cache file
				$cachestufffile = APP_PATH.JAK_CACHE_DIRECTORY.'/stuff.php';
				if (file_exists($cachestufffile)) {
					unlink($cachestufffile);
				}

				// Write the log file each time someone tries to login before
          		JAK_base::jakWhatslog('', JAK_USERID, 0, 63, $page3, (isset($_COOKIE['WIOgeoData']) ? $_COOKIE['WIOgeoData'] : ''), $jakuser->getVar("username"), $_SERVER['REQUEST_URI'], $ipa, $valid_agent);
			
		    	$_SESSION["successmsg"] = $jkl['g14'];
		    	jak_redirect($_SESSION['LCRedirect']);
			}
		    
		} else {
		   	$_SESSION["errormsg"] = $jkl['i3'];
		    jak_redirect($_SESSION['LCRedirect']);
		}
		
	break;
	case 'edit':
	
		// Check if the user exists
		if (is_numeric($page2) && jak_row_exist($page2,$jaktable)) {
		
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		    $jkp = $_POST;
		
		    if (empty($jkp['title'])) {
		        $errors['e'] = $jkl['e2'];
		    }
		    
		    if ($jkp['email'] != '' && !filter_var($jkp['email'], FILTER_VALIDATE_EMAIL)) { 
		    	$errors['e1'] = $jkl['e3'];
		    }
		    
		    if (count($errors) == 0) {

		    	$result = $jakdb->update($jaktable, ["title" => $jkp['title'],
					"description" => $jkp['description'],
					"email" => $jkp['email'],
					"faq_url" => $jkp['faq'],
					"time" => $jakdb->raw("NOW()")], ["id" => $page2]);
		
				if (!$result) {
				    $_SESSION["infomsg"] = $jkl['i'];
		    		jak_redirect($_SESSION['LCRedirect']);
				} else {
					
					// Now let us delete the define cache file
					$cachestufffile = APP_PATH.JAK_CACHE_DIRECTORY.'/stuff.php';
					if (file_exists($cachestufffile)) {
						unlink($cachestufffile);
					}

					// Write the log file each time someone tries to login before
          			JAK_base::jakWhatslog('', JAK_USERID, 0, 61, $page2, (isset($_COOKIE['WIOgeoData']) ? $_COOKIE['WIOgeoData'] : ''), $jakuser->getVar("username"), $_SERVER['REQUEST_URI'], $ipa, $valid_agent);
					
				    $_SESSION["successmsg"] = $jkl['g14'];
		    		jak_redirect($_SESSION['LCRedirect']);
				}
		
		// Output the errors
		} else {
		
		    $errors = $errors;
		}
		
		}
			// Title and Description
			$SECTION_TITLE = $jkl["m17"];
			$SECTION_DESC = "";
		
			$JAK_FORM_DATA = jak_get_data($page2, $jaktable);
			$template = 'editdepartment.php';
		
		} else {
		   	$_SESSION["errormsg"] = $jkl['i3'];
		    jak_redirect(JAK_rewrite::jakParseurl('departments'));
		}
		
	break;
	case 'lock':
	
		// Check if user exists and can be deleted
		if (is_numeric($page2) && $page2 != 1) {

			// Check what we have to do
			$datausrac = $jakdb->get($jaktable, "active", ["id" => $page2]);
			// update the table
			if ($datausrac) {
				$result = $jakdb->update($jaktable, ["active" => 0], ["id" => $page2]);
			} else {
				$result = $jakdb->update($jaktable, ["active" => 1], ["id" => $page2]);
			}
			
			if (!$result) {
				$_SESSION["infomsg"] = $jkl['i'];
		    jak_redirect($_SESSION['LCRedirect']);
			} else {
				
				// Now let us delete the define cache file
				$cachestufffile = APP_PATH.JAK_CACHE_DIRECTORY.'/stuff.php';
				if (file_exists($cachestufffile)) {
					unlink($cachestufffile);
				}
				
			    $_SESSION["successmsg"] = $jkl['g14'];
		    jak_redirect($_SESSION['LCRedirect']);
			}
		
		} else {
		   	$_SESSION["errormsg"] = $jkl['i3'];
		    jak_redirect($_SESSION['LCRedirect']);
		}
	
	break;
	default:
		
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		    $jkp = $_POST;

		    // Hosting is active we need to count the total operators
			if ($jakhs['hostactive']) {
				$totaldep = $jakdb->count($jaktable);

				if ($totaldep >= $jakhs['departments']) {
					$_SESSION["errormsg"] = $jkl['i6'];
		    		jak_redirect($_SESSION['LCRedirect']);
				}
			}
		    
		    if (isset($_POST['insert_department'])) {
		    
		    if (empty($jkp['title'])) {
		    	$errors['e'] = $jkl['e2'];
		    }
		    
		    if ($jkp['email'] != '' && !filter_var($jkp['email'], FILTER_VALIDATE_EMAIL)) { 
		    	$errors['e1'] = $jkl['e3'];
		    }
		        
		    if (count($errors) == 0) {

		    	// Get the next order
		    	$last = $jakdb->get($jaktable, "dorder", ["ORDER" => ["dorder" => "DESC"]]);
		    	$last = $last + 1;

		    	$jakdb->insert($jaktable, ["title" => $jkp['title'],
					"description" => $jkp['description'],
					"email" => $jkp['email'],
					"faq_url" => $jkp['faq'],
					"dorder" => $last,
					"time" => $jakdb->raw("NOW()")]);

		    	$lastid = $jakdb->id();
		    
		    	if (!$lastid) {
		    		$_SESSION["infomsg"] = $jkl['i'];
		    		jak_redirect($_SESSION['LCRedirect']);
		    	} else {
		    			
		    		// Now let us delete the define cache file
		    		$cachestufffile = APP_PATH.JAK_CACHE_DIRECTORY.'/stuff.php';
		    		if (file_exists($cachestufffile)) {
		    			unlink($cachestufffile);
		    		}

		    		// Write the log file each time someone tries to login before
          			JAK_base::jakWhatslog('', JAK_USERID, 0, 62, $lastid, (isset($_COOKIE['WIOgeoData']) ? $_COOKIE['WIOgeoData'] : ''), $jakuser->getVar("username"), $_SERVER['REQUEST_URI'], $ipa, $valid_agent);
		    			
		    		$_SESSION["successmsg"] = $jkl['g14'];
		    		jak_redirect($_SESSION['LCRedirect']);
		    	}
		    
		    // Output the errors
		    } else {
		    
		        $errors = $errors;
		    }
		 }
		    
		 if (isset($jkp['corder']) && isset($jkp['real_dep_id'])) {
		     
		 	$dorders = $jkp['corder'];
		    $depid = $jkp['real_dep_id'];
		    $realid = implode(',', $jkp['real_dep_id']);
		    $dep = array_combine($depid, $dorders);
		    $updatesql = '';       
		   	
		   	foreach ($dep as $key => $order) {
		    	$result = $jakdb->update($jaktable, ["dorder" => $order], ["id" => $key]);
		    }
		             
		    if (!$result) {
		 		$_SESSION["infomsg"] = $jkl['i'];
		    	jak_redirect($_SESSION['LCRedirect']);
		 	} else {
		 	
		 		// Now let us delete the define cache file
		 		$cachestufffile = APP_PATH.JAK_CACHE_DIRECTORY.'/stuff.php';
		 		if (file_exists($cachestufffile)) {
		 			unlink($cachestufffile);
		 		}
		 	
		     	$_SESSION["successmsg"] = $jkl['g14'];
		    	jak_redirect($_SESSION['LCRedirect']);
		 	}
		 	
		 }
		    
   
		 }
		
		// Get all departments
		$DEPARTMENTS_ALL = $jakdb->select($jaktable, "*", ["ORDER" => ["dorder" => "ASC"]]);

		// Get the busiest operator
		$busy_department = $jakdb->query("SELECT COUNT(t1.department) AS mostDEP, t2.title FROM ".JAKDB_PREFIX."sessions AS t1 LEFT JOIN ".JAKDB_PREFIX."departments AS t2 ON(t1.department = t2.id) GROUP BY t1.department ORDER BY mostDEP DESC LIMIT 1")->fetch();

		// How often we had changes
	    $totalChange = $jakdb->count("whatslog", ["whatsid" => [61,62,63]]);

	    // Last Edit
	    if ($totalChange != 0) {
	      $lastChange = $jakdb->get("whatslog", "time", ["whatsid" => [61,62,63], "ORDER" => ["time" => "DESC"], "LIMIT" => 1]);
	    }

		// Hosting is active we need to count the total departments
		if ($jakhs['hostactive']) {
			$totaldep = $jakdb->count($jaktable);
			if ($totaldep >= $jakhs['departments']) $newdep = false;
		}
		 
		// Title and Description
		$SECTION_TITLE = $jkl["m9"];
		$SECTION_DESC = "";
		
		// Include the javascript file for results
		$js_file_footer = 'js_pages.php';
		
		// Call the template
		$template = 'departments.php';
}
?>