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
if (!jak_get_access("files", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)) jak_redirect(BASE_URL);

// All the tables we need for this plugin
$errors = array();
$jaktable = 'files';

// Now start with the plugin use a switch to access all pages
switch ($page1) {

	case 'delete':
		 
		// Check if the file can be deleted
		if (is_numeric($page2)) {
		
			$path = $jakdb->get($jaktable, "path", ["id" => $page2]);
		        
			// Now delete the record from the database
			$result = $jakdb->delete($jaktable, ["id" => $page2]);
			
			// Now let us delete the file
			if (isset($path) && !empty($path)) {
				$filedel = APP_PATH.JAK_CACHE_DIRECTORY.$path;
				if (file_exists($filedel)) {
					unlink($filedel);
				}
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

			// Write the log file each time someone tries to login before
          	JAK_base::jakWhatslog('', JAK_USERID, 0, 84, $page2, (isset($_COOKIE['WIOgeoData']) ? $_COOKIE['WIOgeoData'] : ''), $jakuser->getVar("username"), $_SERVER['REQUEST_URI'], $ipa, $valid_agent);
			
		    $_SESSION["successmsg"] = $jkl['g14'];
		    jak_redirect($_SESSION['LCRedirect']);
		}
		    
		} else {
		   	$_SESSION["errormsg"] = $jkl['i3'];
		    jak_redirect($_SESSION['LCRedirect']);
		}
		
	break;
	case 'deletef':
		 
		// Check if the file can be deleted
		if (!is_numeric($page2)) {
			
			// Now let us delete the file
			$filedel = APP_PATH.JAK_FILES_DIRECTORY.'/user/'.$page2;
			if (file_exists($filedel)) {
				unlink($filedel);
			}

			// Write the log file each time someone tries to login before
          	JAK_base::jakWhatslog('', JAK_USERID, 0, 84, 0, (isset($_COOKIE['WIOgeoData']) ? $_COOKIE['WIOgeoData'] : ''), $jakuser->getVar("username"), $_SERVER['REQUEST_URI'], $ipa, $valid_agent);
			
		    $_SESSION["successmsg"] = $jkl['g14'];
		    jak_redirect($_SESSION['LCRedirect']);
		    
		} else {
		   	$_SESSION["errormsg"] = $jkl['i3'];
		    jak_redirect($_SESSION['LCRedirect']);
		}
		
	break;
	case 'deletefo':
		 
		// Check if the file can be deleted
		if (!is_numeric($page2)) {
			
			// Now let us delete the file
			$filedel = APP_PATH.JAK_FILES_DIRECTORY.'/operator/'.$page2;
			if (file_exists($filedel)) {
				unlink($filedel);
			}

			// Write the log file each time someone tries to login before
          	JAK_base::jakWhatslog('', JAK_USERID, 0, 84, 0, (isset($_COOKIE['WIOgeoData']) ? $_COOKIE['WIOgeoData'] : ''), $jakuser->getVar("username"), $_SERVER['REQUEST_URI'], $ipa, $valid_agent);
			
		    $_SESSION["successmsg"] = $jkl['g14'];
		    jak_redirect($_SESSION['LCRedirect']);
		    
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
		
		    if (empty($jkp['name'])) {
		        $errors['e'] = $jkl['e7'];
		    }
		    
		    if (count($errors) == 0) {

		    	$result = $jakdb->update($jaktable, ["name" => $jkp['name'], "description" => $jkp['description']], ["id" => $page2]);
		
				if (!$result) {
				    $_SESSION["infomsg"] = $jkl['i'];
		    		jak_redirect($_SESSION['LCRedirect']);
				} else {
					
					// Now let us delete the stuff cache file
					$cachestufffile = APP_PATH.JAK_CACHE_DIRECTORY.'/stuff.php';
					if (file_exists($cachestufffile)) {
						unlink($cachestufffile);
					}

					// Write the log file each time someone tries to login before
          			JAK_base::jakWhatslog('', JAK_USERID, 0, 82, $page2, (isset($_COOKIE['WIOgeoData']) ? $_COOKIE['WIOgeoData'] : ''), $jakuser->getVar("username"), $_SERVER['REQUEST_URI'], $ipa, $valid_agent);
					
				    $_SESSION["successmsg"] = $jkl['g14'];
		    		jak_redirect($_SESSION['LCRedirect']);
				}
		
			// Output the errors
			} else {
			
			    $errors = $errors;
			}
			
			}
		
			$JAK_FORM_DATA = jak_get_data($page2, $jaktable);
			
			// Title and Description
			$SECTION_TITLE = $jkl["m15"];
			$SECTION_DESC = "";
			
			$template = 'editfile.php';
		
		} else {
		   	$_SESSION["errormsg"] = $jkl['i3'];
		    jak_redirect($_SESSION['LCRedirect']);
		}
		
	break;
	default:
		
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['insert_response'])) {
		    $jkp = $_POST;
		        
		        if (empty($_FILES['uploadedfile']['name'])) {
		            $errors['e'] = $jkl['e13'];
		        }
		        
		        if (empty($jkp['name'])) {
		            $errors['e1'] = $jkl['e7'];
		        }

		        // Check if the extension is valid
		        $ls_xtension = pathinfo($_FILES['uploadedfile']['name']);
				$allowedf = explode(',', JAK_ALLOWEDO_FILES);
				if (!in_array(".".$ls_xtension['extension'], $allowedf)) {
					$errors['e'] = $jkl['e13'];
		        }
		        
		        if (count($errors) == 0) {

		        	// Rename the file name
		        	$filename = time().'_'.$_FILES['uploadedfile']['name'];
		        
		        	$target_path = APP_PATH.JAK_FILES_DIRECTORY.'/standard/'.$filename;
		        	
		        	$db_path = '/standard/'.$filename;
		        	
		        	if (move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {

		        		$result = $jakdb->insert($jaktable, ["path" => $db_path, "name" => $jkp['name'], "description" => $jkp['description']]);
		    		
		    		}
		    
		    		if (!$result) {
		    		    $_SESSION["infomsg"] = $jkl['i'];
		    			jak_redirect($_SESSION['LCRedirect']);
		    		} else {
		    			
		    			// Now let us delete the stuff cache file
		    			$cachestufffile = APP_PATH.JAK_CACHE_DIRECTORY.'/stuff.php';
		    			if (file_exists($cachestufffile)) {
		    				unlink($cachestufffile);
		    			}

		    			// Write the log file each time someone tries to login before
          				JAK_base::jakWhatslog('', JAK_USERID, 0, 83, $lastid, (isset($_COOKIE['WIOgeoData']) ? $_COOKIE['WIOgeoData'] : ''), $jakuser->getVar("username"), $_SERVER['REQUEST_URI'], $ipa, $valid_agent);
		    			
		    		    $_SESSION["successmsg"] = $jkl['g14'];
		    			jak_redirect($_SESSION['LCRedirect']);
		    		}
		    
		    // Output the errors
		    } else {
		    
		        $errors = $errors;
		    }
		    
   
		 }
		 
		$JAK_USER_FILES = jak_get_files(APP_PATH.JAK_FILES_DIRECTORY.'/user');
		$JAK_OPERATOR_FILES = jak_get_files(APP_PATH.JAK_FILES_DIRECTORY.'/operator');
		 
		$FILES_ALL = jak_get_page_info($jaktable);
		
		// Title and Description
		$SECTION_TITLE = $jkl["m2"];
		$SECTION_DESC = "";
		
		// Include the javascript file for results
		$js_file_footer = 'js_pages.php';
		
		// Call the template
		$template = 'files.php';
}
?>