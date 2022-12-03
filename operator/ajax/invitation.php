<?php

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 6 May 1998 03:10:00 GMT");

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 5.0.1                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2021 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

if (!file_exists('../../config.php')) die('ajax/[invitation.php] config.php not exist');
require_once '../../config.php';

if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !isset($_SESSION['jak_lcp_idhash'])) die("Nothing to see here");

if (!is_numeric($_POST['id']) && !is_numeric($_POST['uid'])) die("There is no such thing!");

	$result = $jakdb->update("buttonstats", ["opid" => $_POST['uid'], "message" => $_POST['msg'], "readtime" => 0], ["id" => $_POST['id']]);

	// Now let us delete and recreate the proactive cache file
	$proactivefile = APP_PATH.JAK_CACHE_DIRECTORY.'/mproactive.php';
	
	if (file_exists($proactivefile)) {
		unlink($proactivefile);
	}
	
	// Get the departments
	$manualproactive = $jakdb->select("buttonstats", ["id", "session", "message"], ["readtime" => 0, "ORDER" => ["lasttime" => "DESC"]]);
	
	$pafile = "<?php\n";
	
	$pafile .= "\$mproactiveserialize = '".base64_encode(gzcompress(serialize($manualproactive)))."';\n\n\$LV_MPROACTIVE = unserialize(gzuncompress(base64_decode(\$mproactiveserialize)));\n\n";
	
	$pafile .= "?>";
	
	if (JAK_base::jakWriteinCache($proactivefile, $pafile, '')) {
		echo json_encode(array('status' => 1));
	} else {
		echo json_encode(array('status' => 0));
	}
?>