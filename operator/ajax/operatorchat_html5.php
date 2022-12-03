<?php

header('Content-Type: text/event-stream; charset=utf-8');
header("Cache-Control: no-cache");
header("Expires: Sat, 6 May 1998 03:10:00 GMT");

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH                                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2016 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

if (!file_exists('../../config.php')) die('ajax/[usronline.php] config.php not exist');
require_once '../../config.php';

if (!isset($_SESSION['jak_lcp_idhash'])) die("Nothing to see here");

// User is on idle let's check if there is a new client
if (isset($_GET['id']) && $_GET['id'] == "check_only" && is_numeric($_GET['opid'])) {

	// Now let us add the active chat file so the operator can come online.
	$chatsfile = APP_PATH.JAK_CACHE_DIRECTORY.'/chats.txt';
	
	if (file_exists($chatsfile)) {
	
		$trimmed = file($chatsfile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		
		foreach ($trimmed as $v) {
			
			$opid = explode(":#:", $v);
			
			// Check if we have a chat request
			if ($opid[2] == $_GET['opid'] && $opid[0] > (time() - 10)) {	
				$nopid[] = $opid[1].":#:".$opid[3];
			} else {
				$nopid = false;
			}
			
		}
		
		// If we have forward the new window array
		if (is_array($nopid)) {
			echo 'retry: 5000\n' . PHP_EOL;
			echo 'data: '.json_encode(array("startcalling" => true, "win" => $nopid))."\n\n";
		} else {
			// Nothing to do, keep idle.
			echo 'retry: 5000\n' . PHP_EOL;
			echo 'data: '.json_encode(array("startcalling" => false))."\n\n";
		}
		
	} else {
		// Nothing to do, keep idle.
		echo 'retry: 5000\n' . PHP_EOL;
		echo 'data: '.json_encode(array("startcalling" => false))."\n\n";
	}

}