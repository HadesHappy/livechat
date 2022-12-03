<?php

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 5.0                   # ||
|| # ----------------------------------------- # ||
|| # Copyright 2021 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

if (!file_exists('../config.php')) die('[uploader.php] config.php not found');
require_once '../config.php';

if(!isset($_SESSION['jak_lcp_idhash'])) die("Nothing to see here");

// Import the language file
if (isset($_REQUEST["operatorLanguage"]) && file_exists(APP_PATH.'lang/'.strtolower($_REQUEST["operatorLanguage"]).'.php')) {
    include_once(APP_PATH.'lang/'.strtolower($_REQUEST["operatorLanguage"]).'.php');
} else {
    include_once(APP_PATH.'lang/'.JAK_LANG.'.php');
}

// The new file upload stuff
if (!empty($_FILES['uploadpp']['name']) && is_numeric($_REQUEST["convID"])) {
	
	$filename = strtolower($_FILES['uploadpp']['name']); // original filename
	$ls_xtension = pathinfo($filename);
	
	// Check if the extension is valid
	$allowedf = explode(',', JAK_ALLOWEDO_FILES);
	if (in_array(".".$ls_xtension['extension'], $allowedf)) {

	// Get the maximum upload or set to 2
	$postmax = (ini_get('post_max_size') ? filter_var(ini_get('post_max_size'), FILTER_SANITIZE_NUMBER_INT) : "2");
	
	if ($_FILES['uploadpp']['size'] <= ($postmax * 1000000)) {

		// first get the target path
		$targetPathd = APP_PATH.JAK_FILES_DIRECTORY.'/operator/';
		$targetPath =  str_replace("//", "/", $targetPathd);
	
	    $tempFile = $_FILES['uploadpp']['tmp_name'];
	    $name_space = explode(".", $_FILES["uploadpp"]["name"]);
	    // Keep the file name but sanitized
	    $fileName = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $name_space[0]);
		$fileName = mb_ereg_replace("([\.]{2,})", '', $fileName);
		$fileName =  preg_replace('/\s+/', '_', $fileName);
		$ufile = 'oc_'.str_replace('.', '_', microtime(true)).'_'.$fileName. '.' . end($name_space);
	    	    
	    $targetFile =  str_replace('//','/',$targetPath).$ufile;
	    $origPath = '/operator/';
	    $message = $origPath.$ufile;
	    	
	    // Move file     
	    move_uploaded_file($tempFile, $targetFile);

	    $jakdb->insert("transcript", [ 
			"name" => $_REQUEST['operatorNameU'],
			"message" => $message,
			"user" => $_REQUEST['userIDU'],
			"operatorid" => $_REQUEST['userIDU'],
			"convid" => $_REQUEST['convID'],
			"class" => "download",
			"time" => $jakdb->raw("NOW()")]);

	    $jakdb->update("checkstatus", ["newc" => 1, "typeo" => 0], ["convid" => $_REQUEST['convID']]);

	    // success
	    $msg = $jkl['s'];
	                
	} else {
		$msg = $jkl['e9'];
	}
	            
	} else {
	    $msg = $jkl['e13'];
	}

switch ($_FILES['uploadpp']['error'])
{
     case 0:
     //$msg = "No Error"; // comment this out if you don't want a message to appear on success.
     break;
     case 1:
     $msg = "The file is bigger than this PHP installation allows";
     break;
     case 2:
     $msg = "The file is bigger than this form allows";
     break;
     case 3:
     $msg = "Only part of the file was uploaded";
     break;
     case 4:
     $msg = "No file was uploaded";
     break;
     case 6:
     $msg = "Missing a temporary folder";
     break;
     case 7:
     $msg = "Failed to write file to disk";
     break;
     case 8:
     $msg = "File upload stopped by extension";
     break;
     default:
     $msg = "unknown error ".$_FILES['uploadpp']['error'];
     break;
}

if (isset($msg)) {
    $stringData = $msg;
} else { 
	$stringData = $jkl['s']; // This is required for onComplete to fire on Mac OSX
}
} else {
	$stringData = "error";
}
echo $stringData;
?>