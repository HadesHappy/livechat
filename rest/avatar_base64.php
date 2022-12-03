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

$userid = $loginhash = $avatar = "";
if (isset($_REQUEST['userid']) && !empty($_REQUEST['userid']) && is_numeric($_REQUEST['userid'])) $userid = $_REQUEST['userid'];
if (isset($_REQUEST['loginhash']) && !empty($_REQUEST['loginhash'])) $loginhash = $_REQUEST['loginhash'];
if (isset($_REQUEST['avatar']) && !empty($_REQUEST['avatar'])) $avatar = $_REQUEST['avatar'];

if (!empty($userid) && !empty($loginhash)) {

	// Let's check if we are logged in
	$usr = $jakuserlogin->jakCheckrestlogged($userid, $loginhash);

	if ($usr) {

		if (empty($avatar)) die(json_encode(array('status' => false, 'errorcode' => 2)));

		$imgdata = base64_decode($avatar);
		$im = imagecreatefromstring($imgdata); 
		if ($im !== false) {

			// if you need the image mime type
			$f = finfo_open();
			$mime = finfo_buffer($f, $imgdata, FILEINFO_MIME_TYPE);
		    	
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

				$curtime = time();

				// Get the correct ending.
				if ($mime == "image/jpeg" || $mime == "image/pjpeg") {
					$imend = ".jpg";
				} elseif ($mime == "image/gif") {
					$imend = ".gif";
				} else {
					$imend = ".png";
				}

				$bigPhoto = 'avatarb'.$curtime.$imend;
				$smallPhoto = 'avatar'.$curtime.$imend;
					    	    
				$targetFile =  str_replace('//','/',$targetPath) . $bigPhoto;
				$origPath = '/'.$userid.'/';
				$dbSmall = $origPath.$smallPhoto;
				$newavatar = JAK_FILES_DIRECTORY.$origPath.$bigPhoto;

				// Save as the correct ending
				if ($imend == ".jpg") {
					imagejpeg($im, $targetFile);
				} elseif ($imend == ".gif") {
					imagegif($im, $targetFile);
				} else {
					imagepng($im, $targetFile);
				}

				require_once APP_PATH.'include/functions_thumb.php';
				create_thumbnail($targetPath, $targetFile, $smallPhoto, JAK_USERAVATWIDTH, JAK_USERAVATHEIGHT, 80);

				imagedestroy($im);

				// SQL update
				$jakdb->update("user", ["picture" => $dbSmall], ["id" => $userid]);

				// Output the avatar
				die(json_encode(array('status' => true, 'newurl' => $newavatar)));

			} else {
				die(json_encode(array('status' => false, 'errorcode' => 2)));
			}

		} else {
			die(json_encode(array('status' => false, 'errorcode' => 9)));
		}

	} else {
		die(json_encode(array('status' => false, 'errorcode' => 1)));
	}
}

die(json_encode(array('status' => false, 'errorcode' => 7)));
?>