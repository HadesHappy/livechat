<?php

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 5.0.3                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2022 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

// Check if the file is accessed only via index.php if not stop the script from running
if (!defined('JAK_ADMIN_PREVENT_ACCESS')) die('You cannot access this file directly.');

// Check if the user has access to this file
if ($jakhs['hostactive'] == 1 && $jakhs['groupchat'] == 0) jak_redirect(BASE_URL);
if (!jak_get_access("groupchat", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)) jak_redirect(BASE_URL);

// All the tables we need for this plugin
$errors = array();
$jaktable = 'groupchat';
$jaktable1 = 'groupchatmsg';
$jaktable2 = 'groupchatuser';
$jaktable3 = 'user';

// We reset some vars
$newwidg = true;
$totalChange = 0;
$lastChange = '';

// Now start with the plugin use a switch to access all pages
switch ($page1) {

	case 'lock':

		// Check if user exists and can be deleted
		if (is_numeric($page2)) {

			// Check what we have to do
			$datausrac = $jakdb->get($jaktable, "active", ["id" => $page2]);
			// update the table
			if ($datausrac) {

				// we turn off the public chat means we save the log into the database and remove the file.
				
				// The chat file
				$groupchatfile = APP_PATH.JAK_CACHE_DIRECTORY.'/groupchat'.$page2.'.txt';

				// Get the file
				if (file_exists($groupchatfile)) $chatfile = file_get_contents($groupchatfile);

				// we have a chatfile
				if (isset($chatfile) && !empty($chatfile)) {

					// Insert into the database
					$jakdb->insert($jaktable1, ["groupchatid" => $page2, "chathistory" => $chatfile, "operatorid" => JAK_USERID, "created" => $jakdb->raw("NOW()")]);

					// Finally remove the file and start fresh
					unlink($groupchatfile);
				}

				$result = $jakdb->update($jaktable, ["active" => 0], ["id" => $page2]);
			} else {
				$result = $jakdb->update($jaktable, ["active" => 1], ["id" => $page2]);
			}
		
		if (!$result) {

		    $_SESSION["infomsg"] = $jkl['i'];
		    jak_redirect($_SESSION['LCRedirect']);
		} else {

			// Now let us delete the all the cache file
			$cacheallfiles = APP_PATH.JAK_CACHE_DIRECTORY.'/';
			$msfi = glob($cacheallfiles."*.php");
			if ($msfi) foreach ($msfi as $filen) {
			   if (file_exists($filen)) unlink($filen);
			}

		    $_SESSION["successmsg"] = $jkl['g14'];
		    jak_redirect($_SESSION['LCRedirect']);
		}
		    
		} else {

		   	$_SESSION["errormsg"] = $jkl['i3'];
		    jak_redirect($_SESSION['LCRedirect']);
		}

	break;
	case 'delete':

		// We want to delete a chat log
		if (is_numeric($page2) && $page3 == "chatlog") {

			$result = $jakdb->delete($jaktable1, ["id" => $page2]);

			if (!$result) {
			    $_SESSION["infomsg"] = $jkl['i'];
			    jak_redirect($_SESSION['LCRedirect']);
			} else {

				// Write the log file each time someone tries to login before
          		JAK_base::jakWhatslog('', JAK_USERID, 0, 60, $page2, (isset($_COOKIE['WIOgeoData']) ? $_COOKIE['WIOgeoData'] : ''), $jakuser->getVar("username"), $_SERVER['REQUEST_URI'], $ipa, $valid_agent);

			    $_SESSION["successmsg"] = $jkl['g14'];
			    jak_redirect($_SESSION['LCRedirect']);
			}

		// We want to delete a chat
		} elseif (is_numeric($page2) && $page2 != 1) {

			// Delete the chat
			$result = $jakdb->delete($jaktable, ["id" => $page2]);

			// Delete all the chat logs
			$jakdb->delete($jaktable1, ["groupchatid" => $page2]);
		
		if (!$result) {

		    $_SESSION["infomsg"] = $jkl['i'];
		    jak_redirect($_SESSION['LCRedirect']);
		} else {

			// Now let us delete the all the cache file
			$cacheallfiles = APP_PATH.JAK_CACHE_DIRECTORY.'/';
			$msfi = glob($cacheallfiles."*.php");
			if ($msfi) foreach ($msfi as $filen) {
			   if (file_exists($filen)) unlink($filen);
			}

	        // Write the log file each time someone tries to login before
          	JAK_base::jakWhatslog('', JAK_USERID, 0, 57, $page2, (isset($_COOKIE['WIOgeoData']) ? $_COOKIE['WIOgeoData'] : ''), $jakuser->getVar("username"), $_SERVER['REQUEST_URI'], $ipa, $valid_agent);

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
		    
		    if (count($errors) == 0) {

		    	if (!isset($jkp['jak_opid']) OR in_array("0", $jkp['jak_opid'])) {
			    	$opids = 0;
			    } else {
			    	$opids = join(',', $jkp['jak_opid']);
			    }

			    if (!isset($jkp['jak_float'])) $jkp['jak_float'] = 0;

			    $gcpass = "";
			    if (isset($jkp['jak_password'])) $gcpass = $jkp['jak_password'];

		    	$result = $jakdb->update($jaktable, ["password" => $gcpass,
		    		"title" => $jkp['title'],
		    		"description" => $jkp['description'],
					"opids" => $opids,
					"maxclients" => $jkp['jak_maxclients'],
					"lang" => $jkp['jak_lang'],
					"buttonimg" => $jkp['jak_buttonimg'],
					"floatpopup" => $jkp['jak_float'],
					"floatcss" => $jkp['jak_floatcss']], ["id" => $page2]);
		
				if (!$result) {
				    $_SESSION["infomsg"] = $jkl['i'];
		    		jak_redirect($_SESSION['LCRedirect']);
				} else {

					// Now let us delete the all the cache file
					$cacheallfiles = APP_PATH.JAK_CACHE_DIRECTORY.'/';
					$msfi = glob($cacheallfiles."*.php");
					if ($msfi) foreach ($msfi as $filen) {
					    if (file_exists($filen)) unlink($filen);
					}

					// Write the log file each time someone tries to login before
          			JAK_base::jakWhatslog('', JAK_USERID, 0, 58, $page2, (isset($_COOKIE['WIOgeoData']) ? $_COOKIE['WIOgeoData'] : ''), $jakuser->getVar("username"), $_SERVER['REQUEST_URI'], $ipa, $valid_agent);

				    $_SESSION["successmsg"] = $jkl['g14'];
		    		jak_redirect($_SESSION['LCRedirect']);
				}
		
			// Output the errors
			} else {
				$errors = $errors;
			}
		
			}
		
			// Title and Description
			$SECTION_TITLE = $jkl["m30"];
			$SECTION_DESC = "";

			// Get all operators
			$JAK_OPERATORS = $jakdb->select($jaktable3, ["id", "username"], ["ORDER" => ["username" => "ASC"]]);

			// Call the settings function
			$lang_files = jak_get_lang_files();

			// Get all buttons
    		$BUTTONS_ALL = jak_get_files('../'.JAK_FILES_DIRECTORY.'/buttons');
			
			// Get the data
			$JAK_FORM_DATA = jak_get_data($page2, $jaktable);

			// Get the 10 latest chat histories
			$JAK_GCHISTORY = $jakdb->select($jaktable1, ["id", "created"], ["groupchatid" => $page2, "ORDER" => ["created" => "DESC"], "LIMIT" => 10]);

			// Include the javascript file for results
			$js_file_footer = 'js_editwidget.php';
			$template = 'editgroupchat.php';
		
		} else {
		    
		   	$_SESSION["errormsg"] = $jkl['i2'];
		    jak_redirect($_SESSION['LCRedirect']);
		}
		
	break;
	case 'view':

		// Check if the user exists
		if (is_numeric($page2) && jak_row_exist($page2,$jaktable1)) {

			$datagc = $jakdb->get($jaktable1, ["groupchatid", "chathistory", "created"], ["id" => $page2]);

			// Each line
			$chatfile = explode(":!n:", $datagc["chathistory"]);

			$chatmsg = "";

			// include the PHP library (if not autoloaded)
			require('../class/class.emoji.php');

			// Get the absolute url for the image
			$ava_url = str_replace('operator/', '', BASE_URL);

			if (isset($chatfile) && is_array($chatfile)) foreach ($chatfile as $v) {
				
				// We will go trough each file
				$chatline = explode(":#!#:", $v);

				// Message format: time:#!#:userid:#!#:name:#!#:avatar:#!#:message:#!#:quote;

				// We want everything except mod
				if ($chatline[0] && $chatline[2] != "*mod*") {

					// Convert urls
					$messagedisp = nl2br(replace_urls($chatline[4]));

					// Convert emotji
					$messagedisp = Emojione\Emojione::toImage($messagedisp);

					// We have a quoted message
					$quoted = "";
					if (isset($chatline[5]) && !empty($chatline[5])) {
						// Convert urls
						$quotemsg = nl2br(replace_urls($chatline[5]));

						// Convert emotji
						$quotemsg = Emojione\Emojione::toImage($quotemsg);

						$quoted = '<blockquote class="blockquote"><i class="fa fa-reply"></i> '.$quotemsg.'</blockquote>';
					}

					$chatmsg .= '<div class="media"><img class="align-self-start mr-3" src="'.$ava_url.JAK_FILES_DIRECTORY.$chatline[3].'" width="30" alt="'.$chatline[2].'"><div class="media-body"><h5 class="mt-0 mb-0">'.$chatline[2].' <span class="small chat-timestamp">'.JAK_base::jakTimesince($chatline[0], JAK_DATEFORMAT, JAK_TIMEFORMAT).'</span></h5><p>'.$quoted.stripcslashes($messagedisp).'</p></div></div>';

				}
			}

			if (isset($chatmsg) && !empty($chatmsg)) {

				// Get the data
				$JAK_FORM_DATA = jak_get_data($datagc["groupchatid"], $jaktable);

				// Title and Description
				$SECTION_TITLE = $JAK_FORM_DATA["title"].' - '.$jkl["g310"];
				$SECTION_DESC = JAK_base::jakTimesince($JAK_FORM_DATA["created"], JAK_DATEFORMAT, JAK_TIMEFORMAT);

				// Call the template
				$template = 'viewgroupchat.php';
			} else {
			   	$_SESSION["errormsg"] = $jkl['i2'];
			    jak_redirect($_SESSION['LCRedirect']);
			}

		} else {
		   	$_SESSION["errormsg"] = $jkl['i2'];
		    jak_redirect($_SESSION['LCRedirect']);
		}

	break;
	default:
		
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_groupchat'])) {
		    $jkp = $_POST;

		    // Hosting is active we need to count the total operators
			if ($jakhs['hostactive']) {
				$totalwidg = $jakdb->count($jaktable);

				if ($totalwidg >= $jakhs['chatwidgets']) {
					$_SESSION["errormsg"] = $jkl['i6'];
		    		jak_redirect($_SESSION['LCRedirect']);
				}
			}
		    
		    if (empty($jkp['title'])) {
		        $errors['e'] = $jkl['e2'];
		    }
		        
		   	if (count($errors) == 0) {

		   		if (!isset($jkp['jak_opid']) OR in_array("0", $jkp['jak_opid'])) {
			    	$opids = 0;
			    } else {
			    	$opids = join(',', $jkp['jak_opid']);
			    }

			    $gcpass = "";
			    if (isset($jkp['jak_password'])) $gcpass = $jkp['jak_password'];

		        $jakdb->insert($jaktable, ["password" => $gcpass,
		        	"title" => $jkp['title'],
					"opids" => $opids,
					"maxclients" => $jkp['jak_maxclients'],
					"lang" => $jkp['jak_lang'],
					"buttonimg" => "colour_on.png",
					"created" => $jakdb->raw("NOW()")]);

		        $lastid = $jakdb->id();

		    	if (!$lastid) {

		    		$_SESSION["infomsg"] = $jkl['i'];
		    		jak_redirect($_SESSION['LCRedirect']);

		    	} else {

		    		// Write the log file each time someone tries to login before
          			JAK_base::jakWhatslog('', JAK_USERID, 0, 59, $page2, (isset($_COOKIE['WIOgeoData']) ? $_COOKIE['WIOgeoData'] : ''), $jakuser->getVar("username"), $_SERVER['REQUEST_URI'], $ipa, $valid_agent);

		    		$_SESSION["successmsg"] = $jkl['g14'];
		    		jak_redirect($_SESSION['LCRedirect']);
		    	}
		    
		    // Output the errors
		    } else {
		    
		        $errors = $errors;
		    }  
   
		 }

		// Get all operators
		$JAK_OPERATORS = $jakdb->select($jaktable3, ["id", "username"], ["ORDER" => ["username" => "ASC"]]);

		// Call the settings function
		$lang_files = jak_get_lang_files();
		
		// Get all responses
		$GROUPCHAT_ALL = jak_get_page_info($jaktable);

		// How often we had changes
	    $totalChange = $jakdb->count("whatslog", ["whatsid" => [57,58,59,60]]);

	    // Last Edit
	    if ($totalChange != 0) {
	      $lastChange = $jakdb->get("whatslog", "time", ["whatsid" => [57,58,59,60], "ORDER" => ["time" => "DESC"], "LIMIT" => 1]);
	    }

		// Hosting is active we need to count the total group chats
		if ($jakhs['hostactive']) {
			$totalwidg = $jakdb->count($jaktable);
			if ($totalwidg >= $jakhs['groupchats']) $newwidg = false;
		}
		
		// Title and Description
		$SECTION_TITLE = $jkl["m29"];
		$SECTION_DESC = "";
		
		// Include the javascript file for results
		$js_file_footer = 'js_widget.php';
		 
		// Call the template
		$template = 'groupchat.php';
}
?>