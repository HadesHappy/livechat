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
if (!jak_get_access("proactive", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)) jak_redirect(BASE_URL);

// All the tables we need for this plugin
$errors = array();
$jaktable = 'autoproactive';

// Reset
$totalChange = 0;
$lastChange = '';

// Now start with the plugin use a switch to access all pages
switch ($page1) {

	case 'delete':
		 
		// Check if user exists and can be deleted
		if (is_numeric($page2)) {
		        
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
          	JAK_base::jakWhatslog('', JAK_USERID, 0, 81, $page2, (isset($_COOKIE['WIOgeoData']) ? $_COOKIE['WIOgeoData'] : ''), $jakuser->getVar("username"), $_SERVER['REQUEST_URI'], $ipa, $valid_agent);
			
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
		
		    if (!filter_var($jkp['path'], FILTER_VALIDATE_URL)) {
		        $errors['e'] = $jkl['e21'];
		    }
		    
		    if (empty($jkp['title'])) {
		        $errors['e2'] = $jkl['e2'];
		    }
		    
		    if (empty($jkp['message'])) {
		        $errors['e1'] = $jkl['e1'];
		    }

		    if (empty($jkp['btn-s'])) {
		       	$errors['e3'] = $jkl['e12'];
		    }

		    if (empty($jkp['btn-c'])) {
		        $errors['e4'] = $jkl['e12'];
		    }
		    
		    if (count($errors) == 0) {

		    	$result = $jakdb->update($jaktable, ["path" => $jkp['path'],
					"showalert" => $jkp['showalert'],
					"soundalert" => $jkp['jak_ringtone'],
					"timeonsite" => $jkp['onsite'],
					"visitedsites" => $jkp['visited'],
					"title" => $jkp['title'],
					"imgpath" => $jkp['imgpath'],
					"message" => $jkp['message'],
					"btn_confirm" => $jkp['btn-s'],
					"btn_cancel" => $jkp['btn-c']], ["id" => $page2]);
		
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
          			JAK_base::jakWhatslog('', JAK_USERID, 0, 79, $page2, (isset($_COOKIE['WIOgeoData']) ? $_COOKIE['WIOgeoData'] : ''), $jakuser->getVar("username"), $_SERVER['REQUEST_URI'], $ipa, $valid_agent);
					
				    $_SESSION["successmsg"] = $jkl['g14'];
		    		jak_redirect($_SESSION['LCRedirect']);
				}
		
			// Output the errors
			} else {
			
			    $errors = $errors;
			}
		
		}
		
			// Title and Description
			$SECTION_TITLE = $jkl["m18"];
			$SECTION_DESC = "";
			
			// Get the form data
			$JAK_FORM_DATA = jak_get_data($page2, $jaktable);

			// Get all sound files
			$sound_files = jak_get_sound_files();

			// Include the javascript file for results
			$js_file_footer = 'js_proactive.php';

			// Finally call the template
			$template = 'editproactive.php';
		
		} else {
		    
		   	$_SESSION["errormsg"] = $jkl['i3'];
		    jak_redirect(JAK_rewrite::jakParseurl('proactive'));
		}
		
	break;
	default:
		
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['insert_proactive'])) {
		    $jkp = $_POST;
		    
		    if (!filter_var($jkp['path'], FILTER_VALIDATE_URL)) {
		            $errors['e'] = $jkl['e21'];
		        }
		        
		        if (empty($jkp['title'])) {
		            $errors['e2'] = $jkl['e2'];
		        }
		        
		        if (empty($jkp['message'])) {
		            $errors['e1'] = $jkl['e1'];
		        }

		        if (empty($jkp['btn-s'])) {
		            $errors['e3'] = $jkl['e12'];
		        }

		        if (empty($jkp['btn-c'])) {
		            $errors['e4'] = $jkl['e12'];
		        }
		        
		        if (count($errors) == 0) {

		        	$jakdb->insert($jaktable, ["path" => $jkp['path'],
					"showalert" => $jkp['showalert'],
					"soundalert" => $jkp['jak_ringtone'],
					"timeonsite" => $jkp['onsite'],
					"visitedsites" => $jkp['visited'],
					"title" => $jkp['title'],
					"imgpath" => $jkp['imgpath'],
					"message" => $jkp['message'],
					"btn_confirm" => $jkp['btn-s'],
					"btn_cancel" => $jkp['btn-c'],
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
          				JAK_base::jakWhatslog('', JAK_USERID, 0, 80, $lastid, (isset($_COOKIE['WIOgeoData']) ? $_COOKIE['WIOgeoData'] : ''), $jakuser->getVar("username"), $_SERVER['REQUEST_URI'], $ipa, $valid_agent);
		    			
		    		    $_SESSION["successmsg"] = $jkl['g14'];
		    			jak_redirect($_SESSION['LCRedirect']);
		    		}
		    
		    // Output the errors
		    } else {
		    
		        $errors = $errors;
		    }
   
		 }
		 
		// Get all proactive
		$PROACTIVE_ALL = jak_get_page_info($jaktable);

		// How often we had changes
	    $totalChange = $jakdb->count("whatslog", ["whatsid" => [79,80,81]]);

	    // Last Edit
	    if ($totalChange != 0) {
	      $lastChange = $jakdb->get("whatslog", "time", ["whatsid" => [79,80,81], "ORDER" => ["time" => "DESC"], "LIMIT" => 1]);
	    }

		// Get all sound files
		$sound_files = jak_get_sound_files();
		 
		// Title and Description
		$SECTION_TITLE = $jkl["m18"];
		$SECTION_DESC = "";
		 
		// Include the javascript file for results
		$js_file_footer = 'js_proactive.php';
		 
		// Call the template
		$template = 'proactive.php';
}
?>