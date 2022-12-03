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
if (!JAK_USERID || !JAK_SUPERADMINACCESS) jak_redirect(BASE_URL);

// All the tables we need for this plugin
$errors = array();

switch ($page1) {

    case 'delete':
         
        // Check if the file can be deleted
        if (!empty($page2)) {

            // Offline button
            $offbtn = str_replace("_on", "_off", $page2);
            
            // Now let us delete the file
            $filedel = APP_PATH.JAK_FILES_DIRECTORY.'/buttons/'.$page2;
            if (file_exists($filedel)) {
                unlink($filedel);
            }

            $filedel1 = APP_PATH.JAK_FILES_DIRECTORY.'/buttons/'.$offbtn;
            if (file_exists($filedel1)) {
                unlink($filedel1);
            }

            // Write the log file each time someone tries to login before
            JAK_base::jakWhatslog('', JAK_USERID, 0, 95, 0, (isset($_COOKIE['WIOgeoData']) ? $_COOKIE['WIOgeoData'] : ''), $jakuser->getVar("username"), $_SERVER['REQUEST_URI'], $ipa, $valid_agent);
            
            $_SESSION["successmsg"] = $jkl['g14'];
            jak_redirect($_SESSION['LCRedirect']);
            
        } else {
            $_SESSION["errormsg"] = $jkl['i3'];
            jak_redirect($_SESSION['LCRedirect']);
        }
        
    break;
    case 'deletef':
         
        // Check if the file can be deleted
        if (!empty($page2)) {

            // Offline button
            $offbtn = str_replace("_on", "_off", $page2);
            
            // Now let us delete the file
            $filedel = APP_PATH.JAK_FILES_DIRECTORY.'/slideimg/'.$page2;
            if (file_exists($filedel)) {
                unlink($filedel);
            }

            $filedel1 = APP_PATH.JAK_FILES_DIRECTORY.'/slideimg/'.$offbtn;
            if (file_exists($filedel1)) {
                unlink($filedel1);
            }

            // Write the log file each time someone tries to login before
            JAK_base::jakWhatslog('', JAK_USERID, 0, 95, 0, (isset($_COOKIE['WIOgeoData']) ? $_COOKIE['WIOgeoData'] : ''), $jakuser->getVar("username"), $_SERVER['REQUEST_URI'], $ipa, $valid_agent);
            
            $_SESSION["successmsg"] = $jkl['g14'];
            jak_redirect($_SESSION['LCRedirect']);
            
        } else {
            $_SESSION["errormsg"] = $jkl['i3'];
            jak_redirect($_SESSION['LCRedirect']);
        }
        
    break;
    default:

    // Upload a button
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $jkp = $_POST;
        
        // We have an upload
        if (isset($jkp['upload'])) {
    	
    	// We check if we have two files
    	if (($_FILES['uploadpp']['name'] != '' && $_FILES['uploadpp1']['name'] != '') || ($_FILES['uploadppsi']['name'] != '' && $_FILES['uploadppsi1']['name'] != '')) {
    	   
            if ($_FILES['uploadpp']['name'] != '' && $_FILES['uploadpp1']['name'] != '') {
               $tempFile = $_FILES['uploadpp']['tmp_name']; // original filename
               $tempFile1 = $_FILES['uploadpp1']['tmp_name']; // original filename
        	   $filename = $_FILES['uploadpp']['name']; // original filename
        	   $filename1 = $_FILES['uploadpp1']['name']; // original filename
                // first get the target path
                $targetPath = APP_PATH.JAK_FILES_DIRECTORY.'/buttons/';
            } elseif ($_FILES['uploadppsi']['name'] != '' && $_FILES['uploadppsi1']['name'] != '') {
                $tempFile = $_FILES['uploadppsi']['tmp_name']; // original filename
                $tempFile1 = $_FILES['uploadppsi1']['tmp_name']; // original filename
                $filename = $_FILES['uploadppsi']['name']; // original filename
                $filename1 = $_FILES['uploadppsi1']['name']; // original filename
                // first get the target path
                $targetPath = APP_PATH.JAK_FILES_DIRECTORY.'/slideimg/';
            }
        	
        	// Fix explode when upload in 1.2
        	$tmpf = explode(".", $filename);
        	$jak_xtension = end($tmpf);
        	
        	$tmpf1 = explode(".", $filename1);
        	$jak_xtension1 = end($tmpf1);
        	
        	if (($jak_xtension == "jpg" && $jak_xtension1 == "jpg") || ($jak_xtension == "jpeg" && $jak_xtension1 == "jpeg") || ($jak_xtension == "png" && $jak_xtension1 == "png") || ($jak_xtension == "gif" && $jak_xtension1 == "gif")) {
        	
        	if ($_FILES['uploadpp']['size'] <= 500000 && $_FILES['uploadpp1']['size'] <= 500000) {
        	
        	list($width, $height, $type, $attr) = getimagesize($tempFile);
        	$mime = image_type_to_mime_type($type);
        	
        	list($width1, $height1, $type1, $attr1) = getimagesize($tempFile1);
        	$mime1 = image_type_to_mime_type($type1);
        	
        	if (($mime == "image/jpeg" && $mime1 == "image/jpeg") || ($mime == "image/pjpeg" && $mime1 == "image/pjpeg") || ($mime == "image/png" && $mime1 == "image/png") || ($mime == "image/gif" && $mime1 == "image/gif")) {
        	
        	$name_space = strtolower($filename);
        	$middle_name = str_replace(" ", "_", $name_space);
        	$button = str_replace(".jpeg", ".jpg", $name_space);
        	$name_space1 = strtolower($filename1);
        	$middle_name1 = str_replace(" ", "_", $name_space1);
        	$button1 = str_replace(".jpeg", ".jpg", $name_space1);
        	    
        	$targetFile =  str_replace('//','/',$targetPath) . $button;
        	$targetFile1 =  str_replace('//','/',$targetPath) . $button1;
        	
        	// Move file
        	if (!file_exists($targetFile) && !file_exists($targetFile1)) {
        		move_uploaded_file($tempFile,$targetFile);
        		move_uploaded_file($tempFile1,$targetFile1);
        		
        		$_SESSION["successmsg"] = $jkl['g14'];
        		    jak_redirect($_SESSION['LCRedirect']);
        	} else {
        		$errors['e'] = $jkl['e24'].'<br />';
        	}
        	 		
        	} else {
        		$errors['e'] = $jkl['e24'].'<br />';
        	}
        	
        	} else {
        		$errors['e'] = $jkl['e24'].'<br />';
        	}
        	
        	} else {
        		$errors['e'] = $jkl['e24'].'<br />';
        	}
        	
        	} else {
        		$errors['e'] = $jkl['e24'].'<br />';
        	}
    	
    	}
        
        if (count($errors) == 0) {

            // Write the log file each time someone tries to login before
            JAK_base::jakWhatslog('', JAK_USERID, 0, 94, 0, (isset($_COOKIE['WIOgeoData']) ? $_COOKIE['WIOgeoData'] : ''), $jakuser->getVar("username"), $_SERVER['REQUEST_URI'], $ipa, $valid_agent);
        		
            $_SESSION["successmsg"] = $jkl['g14'];
    		jak_redirect($_SESSION['LCRedirect']);
        
        } else {
            
            $errors = $errors;
        }
    }

    // Get all buttons
    $BUTTONS_ALL = jak_get_files(APP_PATH.JAK_FILES_DIRECTORY.'/buttons');

    // Get all slideup images
    $SLIDEIMG_ALL = jak_get_files(APP_PATH.JAK_FILES_DIRECTORY.'/slideimg');

    // Title and Description
    $SECTION_TITLE = $jkl["g71"];
    $SECTION_DESC = "";

    // Include the javascript file for results
    $js_file_footer = 'js_buttons.php';

    // Call the template
    $template = 'buttons.php';

}

?>