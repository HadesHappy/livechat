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
			die(json_encode(array("startcalling" => true, "win" => $nopid)));
		} else {
			// Nothing to do, keep idle.
			die(json_encode(array("startcalling" => false)));
		}
		
	} else {
		// Nothing to do, keep idle.
		die(json_encode(array("startcalling" => false)));
	}

}

if (!is_numeric($_POST['uid'])) die("There is no such thing!");

// Import the user or standard language file
if (isset($_SESSION['jak_lcp_lang']) && file_exists(APP_PATH.JAK_OPERATOR_LOC.'/lang/'.$_SESSION['jak_lcp_lang'].'.php')) {
    include_once(APP_PATH.JAK_OPERATOR_LOC.'/lang/'.$_SESSION['jak_lcp_lang'].'.php');
} else {
    include_once(APP_PATH.JAK_OPERATOR_LOC.'/lang/'.JAK_LANG.'.php');
}

$sent_time = time() - OPERATOR_CHAT_EXPIRE;
$print_offline = '';
	
switch ($_POST['page']) {
	
	case 'load-msg':
		
			// Check if the Operater is still available
			$available = $jakdb->has("user", ["AND" => ["id" => $_POST['partner_id'], "available" => 1]]);
			
			if (!$available) {
				$print_offline = '<li class="list-group-item error">'.$jkl["g139"].'</li>';
			}
			
			// A few vars
			$unreadmsg = false;
			$chatmsg = "";
		
			// Load Messages
			$resultu = $jakdb->select("operatorchat", "*", ["AND #check" => ["OR #first" => ["fromid" => $_POST['uid'], "toid" => $_POST['uid']], "OR #second" => ["operatorchat.fromid" => $_POST['opid'], "operatorchat.toid" => $_POST['opid']], "sent[>]" => $sent_time, "msgpublic" => 0], "ORDER" => ["sent" => "ASC"]]);
			
			if (isset($resultu) && !empty($resultu)) {
			
				foreach ($resultu as $rowu) {
				
					// check if current user has unreceived messages which are older than limit, if yes, display it with date
					if ($rowu["sent"] < $sent_time AND $rowu["received"] == 0) {
				
						$updatesqla .= sprintf("WHEN %d THEN '%s' ", $rowu['id'], time());
						$updatesqla1 .= sprintf("WHEN %d THEN '%s' ", $rowu['id'], 1);
						$alloid[] = $rowu['id'];
						$unreadmsg = true;
					
					}
					
					// Load Messages
					if (($rowu["fromid"] == $_POST['uid'] && $rowu["toid"] == $_POST['partner_id']) || ($rowu["fromid"] == $_POST['partner_id'] && $rowu["toid"] == $_POST['uid'])) {
					
						//print messages
						if ($rowu['system_message'] != 'no') {
							
							$chatmsg .= '<li class="list-group-item system">'. stripcslashes($rowu['message']).'</li>';
																
						} elseif ($rowu['fromid'] != $_POST['uid']) {
						
							$chatmsg .= '<li class="list-group-item me">'.$rowu['message'].'</li>';
						
						} else {
							$chatmsg .= '<li class="list-group-item">'.$rowu['message'].'</li>';
						}
						
						
						// If message has been received mark it!
						if ($rowu['toid'] == $_POST['uid']) {
							$jakdb->update("operatorchat", ["received" => 1], ["AND" => ["id" => $rowu['id'], "received" => 0]]);
						}		
						
						$last_msg = $rowu['sent'];
					
					}
					
					
				}
				
				//print last message time if older than 2 mins
				$math = time() - $last_msg;
				if ($math > 120) {
					$chatmsg .= '<li class="list-group-item system">'.str_replace("%s", date("H:i", $last_msg), $jkl["g141"]).'</li>';
				}
				
				$chatmsg .= $print_offline;
				
				// Let's update the unread messages
				if ($unreadmsg && is_array($alloid)) {
				
					$realid = implode(',', $alloid);
						
					// Update in one query
					$jakdb->query('UPDATE '.DB_PREFIX.'operatorchat SET received = CASE id
						'.$updatesqla.'
						END,
						sent = CASE id
						'.$updatesqla1.'
						END
						WHERE id IN ('.$realid.')');
				}
				
				die(json_encode(array("status" => true, "msg" => '<ul class="list-group">'.$chatmsg.'</ul>')));
			
			} else {
				die(json_encode(array("status" => false)));	
			}
	
	break;
	
	case 'send-msg':
	
		if (empty($_POST['message'])) {
			echo $jkl['e1'];
		} else {
			
			$message = trim($_POST['message']);
			
			$message = filter_var($message, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		
			$jakdb->insert("operatorchat", ["fromid" => $_POST['uid'], "toid" => $_POST['to_id'], "message" => $message, "sent" => time()]);

			$lastid = $jakdb->id();
		
			if ($lastid) {
				
				// Now let us add the active chat file so the operator can come online.
				$chatsfile = APP_PATH.JAK_CACHE_DIRECTORY.'/chats.txt';
				
				if (file_exists($chatsfile)) {
					unlink($chatsfile);
				}
				
				// Write the from to operator id
				$opid = time().":#:".$_POST['uid'].":#:".$_POST['to_id'].":#:".$_POST['uname']."\n";
				
				// Create, write or append to the file.
				file_put_contents($chatsfile, $opid, FILE_APPEND);

				die(json_encode(array("status" => true, "txt" => '<li class="list-group-item">'.$message.'</li>')));
			} else {
				die(json_encode(array("status" => false)));
			}
		
		}
	
	break;
	
	default:
	
		return false;

}