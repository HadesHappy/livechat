<?php

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 6 May 1998 03:10:00 GMT");

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 3.5.1                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2018 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

if (!file_exists('../../config.php')) die('ajax/[usronline.php] config.php not exist');
require_once '../../config.php';

if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !isset($_SESSION['jak_lcp_idhash'])) die("Nothing to see here");

if (!is_numeric($_GET['id'])) die("There is no such thing!");

// Import the user or standard language file
if (isset($_SESSION['jak_lcp_lang']) && file_exists(APP_PATH.JAK_OPERATOR_LOC.'/lang/'.$_SESSION['jak_lcp_lang'].'.php')) {
    include_once(APP_PATH.JAK_OPERATOR_LOC.'/lang/'.$_SESSION['jak_lcp_lang'].'.php');
} else {
    include_once(APP_PATH.JAK_OPERATOR_LOC.'/lang/'.JAK_LANG.'.php');
}
	
	$result = $jakdb->update("checkstatus", ["knockknock" => 1], ["convid" => $_GET['id']]);
	
	if ($result) {
		echo '<div class="modal-header"><h4 class="modal-title">'.$jkl['g223'].'</h4>
		      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		    </div>
		    <div class="modal-body"><div class="padded-box"><div class="alert alert-success">'.$jkl['g14'].'</div></div></div>
		        	<div class="modal-footer">
		        		<button type="button" class="btn btn-default" data-dismiss="modal">'.$jkl["g180"].'</button>
			  </div>';
	} else {
		echo '<div class="modal-header"><h4 class="modal-title">'.$jkl['g223'].'</h4>
		      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		    </div>
		    <div class="modal-body"><div class="padded-box"><div class="alert alert-error">'.$jkl['sql'].'</div></div></div>
		        	<div class="modal-footer">
		        		<button type="button" class="btn btn-default" data-dismiss="modal">'.$jkl["g180"].'</button>
			  </div>';
	}
?>