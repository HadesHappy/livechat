<?php

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 5.0                   # ||
|| # ----------------------------------------- # ||
|| # Copyright 2021 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

if (!file_exists('../../config.php')) die('ajax/[available.php] config.php not exist');
require_once '../../config.php';

if (!isset($_SESSION['jak_lcp_idhash'])) die("Nothing to see here");

// Import the user or standard language file
if (isset($_SESSION['jak_lcp_lang']) && file_exists(APP_PATH.JAK_OPERATOR_LOC.'/lang/'.$_SESSION['jak_lcp_lang'].'.php')) {
    include_once(APP_PATH.JAK_OPERATOR_LOC.'/lang/'.$_SESSION['jak_lcp_lang'].'.php');
} else {
    include_once(APP_PATH.JAK_OPERATOR_LOC.'/lang/'.JAK_LANG.'.php');
}

if (is_numeric($_GET['id']) && is_numeric($_GET['userid'])) {

$timeout = 180;
$operator = '';
$udepl = array();

$result = $jakdb->select("user", ["id", "hours_array", "phonenumber", "available", "emailnot", "pusho_tok", "push_notifications", "departments", "username", "name", "lastactivity"], ["AND" => ["access" => 1, "id[!]" => $_GET['userid']]]);

// Get departments
$lsdata = $jakdb->select("departments", ["id", "title"], ["ORDER" => ["dorder" => "ASC"]]);
	
if (isset($result) && !empty($result)) {
	foreach ($result as $row) {
	
		if (time() > ($row['lastactivity'] + $timeout)) {
			$jakdb->update("user", ["available" => 0], ["id" => $row['userid']]);
		}

		if ($row["departments"] == 0) {
			$udep = $jkl['g105'];
		} else {
		
			if (isset($lsdata) && is_array($lsdata)) foreach($lsdata as $z) {
			
				if (in_array($z["id"], explode(',', $row["departments"]))) {
				
					$udepl[] = $z["title"];
				
				}
			
			}
		
		}

		if (!empty($udepl) && is_array($udepl)) $departmentlist = join(", ", $udepl);
		
		if (isset($departmentlist) && $departmentlist) $udep = $jkl['m9'].': '.$departmentlist;

		$oponline = false;
			
		// Operator is available
		if ($row["available"] == 1) {
			$operator .= '<option value="'.$row['id'].'">'.$row['name'].' - '.$row['username'].' ('.$udep.')</option>';
			$oponline = true;
		}

		// Now let's check if we have a time available
		if (!$oponline && JAK_base::jakAvailableHours($row["hours_array"], date('Y-m-d H:i:s')) && ($row["phonenumber"] || $row["emailnot"] || JAK_NATIVE_APP_TOKEN || $row["pusho_tok"] || $row["push_notifications"])) {
			$operator .= '<option value="'.$row['id'].'">'.$row['name'].' - '.$row['username'].' ('.$udep.')</option>';
		}
	}
}

if ($operator) {
	$oselect = $operator;
	$showbutton = '<hr><button class="btn btn-primary btn-block" type="submit" name="transfer_customer">'.$jkl['g4'].'</button>';
	
} else {
	$oselect = '<option value="0">'.$jkl['g114'].'</option>';
	$showbutton = '';
}

	echo '<div class="modal-header"><h4 class="modal-title">'.JAK_TITLE.'</h4>
	      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	    </div>
	    <div class="modal-body"><div class="padded-box"><form method="post" action="index.php">';
	        echo '<p>'.$jkl['g112'].'</p>';
	        echo '
	        <label for="transfermsg">'.$jkl['g113'].'</label>
	        <input type="text" name="transfermsg" id="transfermsg" class="form-control">
	        <label for="operator">'.$jkl['g106'].'</label>
	        <select name="operator" id="operator" class="form-control">
	        '.$oselect.'
	        </select>
	        <input type="hidden" name="cid" id="cid" value="'.$_GET['id'].'">
	        
	        '.$showbutton.'
	        
	        </form></div></div>
	        	<div class="modal-footer">
	        		<button type="button" class="btn btn-default" data-dismiss="modal">'.$jkl["g180"].'</button>
	        	</div>';
	
}
?>