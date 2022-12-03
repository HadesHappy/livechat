<?php

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 6 May 1998 03:10:00 GMT");

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 5.0.2                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2022 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

if (!file_exists('../../config.php')) die('ajax/[usronline.php] config.php not exist');
require_once '../../config.php';

if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !isset($_SESSION['jak_lcp_idhash'])) die("Nothing to see here");

// Import the user or standard language file
if (isset($_SESSION['jak_lcp_lang']) && file_exists(APP_PATH.JAK_OPERATOR_LOC.'/lang/'.$_SESSION['jak_lcp_lang'].'.php')) {
    include_once(APP_PATH.JAK_OPERATOR_LOC.'/lang/'.$_SESSION['jak_lcp_lang'].'.php');
} else {
    include_once(APP_PATH.JAK_OPERATOR_LOC.'/lang/'.JAK_LANG.'.php');
}

if (!is_numeric($_POST['uid'])) die("There is no such thing!");

$sent_time = time() - OPERATOR_CHAT_EXPIRE;
$chatmsg = '';
	
switch ($_POST['page']) {
	
	case 'load-msg':
		
			// Load Messages
			$result = $jakdb->select("operatorchat", ["[>]user" => ["fromid" => "id"]], ["operatorchat.id", "operatorchat.fromid", "operatorchat.message", "operatorchat.system_message", "operatorchat.sent", "user.username"], ["AND" => ["msgpublic" => 1, "sent[>]" => $sent_time], "ORDER" => ["sent" => "ASC"]]);	
			
			if (isset($result) && !empty($result)) {
			
				$chatmsg = '<ul class="list-group op-chat">';
			
				foreach ($result as $rowm) {
				
					//print messages
					if ($rowm['system_message'] != 'no') {
						
						$chatmsg .= '<li class="list-group-item system"><p class="mb-1">'. stripcslashes($rowm['message']).'</p></li>';
															
					} elseif ($rowm['fromid'] != $_POST['uid']) {
					
						$chatmsg .= '<li class="list-group-item me"><div class="d-flex w-100 justify-content-between">
      <h5 class="mb-1">'.$rowm['username'].' <span class="badge badge-dark">'.date("H:i", $rowm['sent']).'</span></h5></div><p class="mb-1">'.$rowm['message'].'</p></li>';
					
					} else {
						$chatmsg .= '<li class="list-group-item"><div class="d-flex w-100 justify-content-between">
      <h5 class="mb-1">'.$jkl["g140"].' <span class="badge badge-dark">'.date("H:i", $rowm['sent']).'</span></h5></div><p class="mb-1">'.$rowm['message'].'</p></li>';
					}	
					
					$last_msg = $rowm['sent'];
				}	
				
				//print last message time if older than 2 mins
				$math = time() - $last_msg;
				if ($math > 120) {
					$chatmsg .= '<li class="list-group-item system"><p class="mb-1">'.str_replace("%s", date("H:i", $last_msg), $jkl["g141"]).'</p></li>';
				}
				
				$chatmsg .= '</ul>';
					
			}
					
		die($chatmsg);
	
	break;
	
	case 'send-msg':
	
		if (empty($_POST['message'])) {
			echo $jkl['e1'];
		} else {
		
			$message = trim($_POST['message']);
			
			$message = filter_var($message, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		
			$jakdb->insert("operatorchat", ["fromid" => $_POST['uid'], "toid" => 0, "message" => $message, "sent" => time(), "received" => 1, "msgpublic" => 1]);

			$lastid = $jakdb->id();
		
			if ($lastid) {
				echo 'success';
			} else {
				echo $jkl['e1'];
			}
			
		}
				
	break;
	
	default:
	
		return false;

}