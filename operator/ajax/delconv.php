<?php

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 3.5.1                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2018 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

if (!file_exists('../../config.php')) die('ajax/[available.php] config.php not exist');
require_once '../../config.php';

if(!isset($_SESSION['jak_lcp_idhash'])) die("Nothing to see here");

// Import the user or standard language file
if (isset($_SESSION['jak_lcp_lang']) && file_exists(APP_PATH.JAK_OPERATOR_LOC.'/lang/'.$_SESSION['jak_lcp_lang'].'.php')) {
    include_once(APP_PATH.JAK_OPERATOR_LOC.'/lang/'.$_SESSION['jak_lcp_lang'].'.php');
} else {
    include_once(APP_PATH.JAK_OPERATOR_LOC.'/lang/'.JAK_LANG.'.php');
}

if (is_numeric($_GET['id'])) {

	echo '<div class="modal-header"><h4 class="modal-title">'.JAK_TITLE.'</h4>
	      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	    </div>
	    <div class="modal-body"><div class="padded-box"><form method="post" action="index.php">';
	        echo '<p>'.$jkl['e35'].'</p>';
	        echo '
	        <input type="hidden" name="id" id="id" value="'.$_GET['id'].'" />
	        
	        <button class="btn btn-danger btn-block" type="submit" name="delete_conv" value="delete">'.$jkl['g19'].'</button>
	        
	        </form></div></div>
	        	<div class="modal-footer">
	        		<button type="button" class="btn btn-default" data-dismiss="modal">'.$jkl["g180"].'</button>
	        	</div>';
}
?>