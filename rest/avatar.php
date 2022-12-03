<?php

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 6 May 1998 03:10:00 GMT");

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH                                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2018 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

if (!file_exists('config.php')) die('rest_api config.php not exist');
require_once 'config.php';

$userid = $loginhash = "";
if (isset($_REQUEST['userid']) && !empty($_REQUEST['userid']) && is_numeric($_REQUEST['userid'])) $userid = $_REQUEST['userid'];
if (isset($_REQUEST['loginhash']) && !empty($_REQUEST['loginhash'])) $loginhash = $_REQUEST['loginhash'];

if (!empty($userid) && !empty($loginhash)) {

	// Let's check if we are logged in
	$usr = $jakuserlogin->jakCheckrestlogged($userid, $loginhash);

	if ($usr) {

		if (empty($_FILES['useravatar']['name'])) die(json_encode(array('status' => false, 'errorcode' => 2)));

		if (!empty($_FILES['useravatar']['name'])) {

			$filename = $_FILES['useravatar']['name']; // original filename
		    $tmpf = explode(".", $filename);
		    $jak_xtension = end($tmpf);
		    	
		    if ($jak_xtension == "jpg" || $jak_xtension == "jpeg" || $jak_xtension == "png" || $jak_xtension == "gif") {
		    	
		    	if ($_FILES['useravatar']['size'] <= 2000000) {
		    	
		    		list($width, $height, $type, $attr) = getimagesize($_FILES['useravatar']['tmp_name']);
		    		$mime = image_type_to_mime_type($type);
		    	
		    		if (($mime == "image/jpeg") || ($mime == "image/pjpeg") || ($mime == "image/png") || ($mime == "image/gif")) {
		    	
			    		// first get the target path
			    		$targetPathd = APP_PATH.JAK_FILES_DIRECTORY.'/'.$userid.'/';
			    		$targetPath =  str_replace("//","/",$targetPathd);
			    		// Create the target path
				    	if (!is_dir($targetPath)) {
				    		mkdir($targetPath, 0755);
				    	    copy(APP_PATH.JAK_FILES_DIRECTORY."/index.html", $targetPath . "/index.html");
				    	
				    	}
			    	
			    		// if old avatars exist delete it
			    		foreach(glob($targetPath.'*.*') as $jak_unlink){
			    	    	unlink($jak_unlink);
			    	    	copy(APP_PATH.JAK_FILES_DIRECTORY."/index.html", $targetPath . "/index.html");
			    		}
			    	
				    	$tempFile = $_FILES['useravatar']['tmp_name'];
				    	$origName = substr($_FILES['useravatar']['name'], 0, -4);
				    	$name_space = strtolower($_FILES['useravatar']['name']);
				    	$middle_name = str_replace(" ", "_", $name_space);
				    	$middle_name = str_replace(".jpeg", ".jpg", $name_space);
				    	$glnrrand = rand(10, 99);
				    	$bigPhoto = str_replace(".", "_" . $glnrrand . ".", $middle_name);
				    	$smallPhoto = str_replace(".", "_t.", $bigPhoto);
				    	    
				    	$targetFile =  str_replace('//','/',$targetPath) . $bigPhoto;
				    	$origPath = '/'.$userid.'/';
				    	$dbSmall = $origPath.$smallPhoto;
				    	$newavatar = JAK_FILES_DIRECTORY.$origPath.$smallPhoto;
			    	
				    	require_once APP_PATH.'include/functions_thumb.php';
				    	// Move file and create thumb     
				    	move_uploaded_file($tempFile,$targetFile);
			    	     
			    		create_thumbnail($targetPath, $targetFile, $smallPhoto, JAK_USERAVATWIDTH, JAK_USERAVATHEIGHT, 80);
			    	     	
				    	// SQL update
				    	$jakdb->update("user", ["picture" => $dbSmall], ["id" => $userid]);

				    	die(json_encode(array('status' => true, 'newurl' => $newavatar)));
		    	     		
		    	    } else {
						die(json_encode(array('status' => false, 'errorcode' => 2)));
					}
		    	} else {
					die(json_encode(array('status' => false, 'errorcode' => 3)));
				}
		    } else {
				die(json_encode(array('status' => false, 'errorcode' => 2)));
			}
		}
	} else {
		die(json_encode(array('status' => false, 'errorcode' => 1)));
	}
}

die(json_encode(array('status' => false, 'errorcode' => 7)));
?>