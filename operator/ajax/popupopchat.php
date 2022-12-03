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

if (!$_SERVER['HTTP_X_REQUESTED_WITH'] && !isset($_SESSION['idhash'])) die("Nothing to see here");

// Import the user or standard language file
if (isset($_SESSION['jak_lcp_lang']) && file_exists(APP_PATH.JAK_OPERATOR_LOC.'/lang/'.$_SESSION['jak_lcp_lang'].'.php')) {
    include_once(APP_PATH.JAK_OPERATOR_LOC.'/lang/'.$_SESSION['jak_lcp_lang'].'.php');
} else {
    include_once(APP_PATH.JAK_OPERATOR_LOC.'/lang/'.JAK_LANG.'.php');
}

if (!is_numeric($_POST['uid']) || !is_numeric($_POST['opid'])) die("There is no such thing!");

$sent_time = time() - OPERATOR_CHAT_EXPIRE;
$print_offline = $chatmsg = '';
	
switch ($_POST['page']) {
	
	case 'load-msg':

			// Operator is still online
			$oponline = $jakdb->has("user", ["AND" => ["id" => $_POST['opid'], "available" => 1]]);
		
			if (!$oponline) $print_offline = '<li class="list-group-item error"><p class="mb-1">'.$jkl["g139"].'</p></li>';

			$chatmsg = '<ul class="list-group op-chat">';
		
			// Load Messages
			$resultm = $jakdb->select("operatorchat", ["[>]user" => ["fromid" => "id"]], ["operatorchat.id", "operatorchat.fromid", "operatorchat.message", "operatorchat.system_message", "operatorchat.sent", "user.username", "user.available"], ["AND #check" => ["OR #first" => ["operatorchat.fromid" => $_POST['uid'], "operatorchat.toid" => $_POST['uid']], "OR #second" => ["operatorchat.fromid" => $_POST['opid'], "operatorchat.toid" => $_POST['opid']], "operatorchat.sent[>]" => $sent_time, "operatorchat.msgpublic" => 0], "ORDER" => ["operatorchat.sent" => "ASC"]]);
			
			if (isset($resultm) && !empty($resultm)) {
			
				foreach ($resultm as $rowm) {	
					
					//print messages
					if ($rowm['system_message'] != 'no') {								
						$chatmsg .= '<li class="list-group-item system"><p class="mb-1">'.$rowm['message'].'</p></li>';
															
					} elseif ($rowm['fromid'] != $_POST['uid']) {
					
						$chatmsg .= '<li class="list-group-item me"><div class="d-flex w-100 justify-content-between">
      <h5 class="mb-1">'.$rowm['username'].' <span class="chat-timestamp">'.date("H:i", $rowm['sent']).'</span></h5></div><p class="mb-1">'.$rowm['message'].'</p></li>';
					
					} else {
						$chatmsg .= '<li class="list-group-item"><div class="d-flex w-100 justify-content-between">
      <h5 class="mb-1">'.$jkl["g140"].' <span class="chat-timestamp">'.date("H:i", $rowm['sent']).'</span></h5></div><p class="mb-1">'.$rowm['message'].'</p></li>';
					}		
					
					$last_msg = $rowm['sent'];
				}	
				
				//print last message time if older than 2 mins
				$math = time() - $last_msg;
				if ($math > 120) {
					$chatmsg .= '<li class="list-group-item system"><p class="mb-1">'.str_replace("%s", date("H:i", $last_msg), $jkl["g141"]).'</p></li>';
				}

				if ($rowm["available"] == 0) {
					$chatmsg .= $print_offline;
				}
				
			} else {
				$chatmsg .= $print_offline;
			}

			$chatmsg .= '</ul>';

			echo $chatmsg;
	
	break;
	
	case 'send-msg':
	
		if (empty($_POST['message'])) {
			echo $jkl['e1'];
		} else {
		
			$message = trim($_POST['message']);
			
			$message = filter_var($message, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		
			$jakdb->insert("operatorchat", ["fromid" => $_POST['uid'], "toid" => $_POST['opid'], "message" => $message, "sent" => time()]);

			$lastid = $jakdb->id();

			if ($lastid) {
				
				// Now let us add the active chat file so the operator can come online.
				$chatsfile = APP_PATH.JAK_CACHE_DIRECTORY.'/chats.txt';
				
				if (file_exists($chatsfile)) {
					unlink($chatsfile);
				}
				
				// Write the from to operator id
				$opid = time().":#:".$_POST['uid'].":#:".$_POST['opid'].":#:".$_POST['uname']."\n";
				
				// Create, write or append to the file.
				file_put_contents($chatsfile, $opid, FILE_APPEND);

				echo 'success';
			} else {
				echo $jkl['e1'];
			}
			
		}
				
	break;
	
	default:
	
		return false;

}