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

// Get the current time
$currentime = time();

if (!is_numeric($_GET['uid'])) die("There is no such thing!");

	$useronline = '';
	$useronlinemap = array();
	$userocount = 0;
	$sendalert = 0;

	if (isset($_GET['uonline']) && $_GET['uonline']) {

		// 5 Minutes ago
		$mino = date('Y-m-d H:i:s',$currentime - 5 * 60);

		// Now only get the department for the user
		if (isset($_SESSION['usr_department']) && is_numeric($_SESSION['usr_department']) && $_SESSION['usr_department'] != 0) {
			$result = $jakdb->select("buttonstats", ["[>]sessions" => ["session" => "session"]], ["buttonstats.id", "buttonstats.referrer", "buttonstats.firstreferrer", "buttonstats.agent", "buttonstats.hits", "buttonstats.ip", "buttonstats.country", "buttonstats.countrycode", "buttonstats.latitude", "buttonstats.longitude", "buttonstats.lasttime", "buttonstats.time", "buttonstats.readtime", "sessions.initiated", "sessions.ended"], ["AND" => ["buttonstats.opid" => [0,$_GET['uid']], "buttonstats.depid" => [0,$_SESSION['usr_department']], "#buttonstats.lasttime[>]" => $mino], "GROUP" => "buttonstats.session", "ORDER" => ["buttonstats.lasttime" => "DESC"], "LIMIT" => 50]);
		} elseif (isset($_SESSION['usr_department']) && $_SESSION['usr_department'] == 0) {
			$result = $jakdb->select("buttonstats", ["[>]sessions" => ["session" => "session"]], ["buttonstats.id", "buttonstats.referrer", "buttonstats.firstreferrer", "buttonstats.agent", "buttonstats.hits", "buttonstats.ip", "buttonstats.country", "buttonstats.countrycode", "buttonstats.latitude", "buttonstats.longitude", "buttonstats.lasttime", "buttonstats.time", "buttonstats.readtime", "sessions.initiated", "sessions.ended"], ["AND" => ["buttonstats.opid" => [0,$_GET['uid']], "#buttonstats.lasttime[>]" => $mino], "GROUP" => "buttonstats.session", "ORDER" => ["buttonstats.lasttime" => "DESC"], "LIMIT" => 50]);
		} elseif (isset($_SESSION['usr_department'])) {
			$result = $jakdb->select("buttonstats", ["[>]sessions" => ["session" => "session"]], ["buttonstats.id", "buttonstats.referrer", "buttonstats.firstreferrer", "buttonstats.agent", "buttonstats.hits", "buttonstats.ip", "buttonstats.country", "buttonstats.countrycode", "buttonstats.latitude", "buttonstats.longitude", "buttonstats.lasttime", "buttonstats.time", "buttonstats.readtime", "sessions.initiated", "sessions.ended"], ["AND" => ["buttonstats.opid" => [0,$_GET['uid']], "buttonstats.depid" => explode(",",$_SESSION['usr_department'].',0'), "#buttonstats.lasttime[>]" => $mino], "GROUP" => "buttonstats.session", "ORDER" => ["buttonstats.lasttime" => "DESC"], "LIMIT" => 50]);
		}
			
		if (isset($result) && !empty($result)) {
			
			$useronline = '<div class="table-responsive"><table class="table table-striped"><th>'.$jkl["g224"].'</th><th>'.$jkl["g169"].'</th><th>'.$jkl["g171"].'</th><th>'.$jkl["g172"].'</th><th>'.$jkl["g11"].'</th><th>'.$jkl["g173"].'</th><th>'.$jkl["g174"].'</th><th></th>';
				
			foreach ($result as $row) {
					
				// Convert time to minutes and hours
				$row['lasttime'] = JAK_base::jakTimesince($row['lasttime'], JAK_DATEFORMAT, JAK_TIMEFORMAT);
				$row['time'] = JAK_base::jakTimesince($row['time'], JAK_DATEFORMAT, JAK_TIMEFORMAT);

				$uclass = '';
				$button = '<a href="javascript:void(0)" id="usero-'.$row['id'].'" class="btn btn-secondary btn-sm jakweb-online-user"><i class="fa fa-user"></i></a>';

				if ($row['readtime'] == 1) $uclass = ' class="table-warning"';
					
				if ($row['readtime'] == 2) $uclass = ' class="table-success"';

				if ($row['readtime'] == 3) $uclass = ' class="table-danger"';

				if ($row['readtime'] >= 2) $button = '<a href="javascript:void(0)" class="btn btn-info btn-sm"><i class="fa fa-bell"></i></a>';
					
				if ($row['initiated'] && $row['ended'] == 0) $button = '<a href="javascript:void(0)" class="btn btn-info btn-sm"><i class="fa fa-comment"></i></a>';
				
				$useronline .= '<tr'.$uclass.'><td><img src="'.str_replace("ajax/", "", BASE_URL."img/blank.png").'" class="flag-big flag-'.$row['countrycode'].'" title="'.$row['country'].'" alt="'.$row['country'].'"></td><td><strong>'.$jkl["g169"].':</strong> '.$row['referrer'].'<br><strong>'.$jkl["g170"].':</strong> '.$row['firstreferrer'].'</td><td>'.$row['agent'].'</td><td>'.$row['hits'].'</td><td>'.$row['ip'].'</td><td>'.$row['time'].'</td><td>'.$row['lasttime'].'</td><td>'.$button.'</td></tr>';

				// Get the map thing
				if (JAK_SHOW_IPS && $row['latitude']) {
					$popupd = '<div class="row"><div class="col-2"><a href="javascript:void(0)" id="usero-'.$row['id'].'" class="jakweb-online-user"><img src="'.str_replace("ajax/", "", BASE_URL."img/blank.png").'" class="flag-big flag-'.$row['countrycode'].'" title="'.$row['country'].'" alt="'.$row['country'].'"></a></div><div class="col-10">'.$jkl["g169"].': '.$row['referrer'].'</div></div>';
					$useronlinemap[] = array('id' => 'm_'.$row['id'], 'lat' => $row['latitude'], 'lng' => $row['longitude'], 'popup' => $popupd);
				}

				$userocount++;
					
			}
				
			$useronline .= '</table></div>';
				
		}
		
	}

	// We check if we have access to the operator chat and the operator list
	$oponline = false;
	$countops = 0;
	
	if (isset($_GET['olist']) && $_GET['olist']) {
	
		$resop = $jakdb->select("user", ["id", "username", "name", "operatorchat"], ["AND" => ["available" => 1, "id[!]" => $_GET['uid']], "LIMIT" => 20]);
		
		if (isset($resop) && !empty($resop)) {
			
			$oponline = '<ul class="list-group">';
			
			foreach ($resop as $row) {

				$openchat = $openop = '';
				$newopmsg = 'secondary';

				if (!JAK_OPENOP) {
					// Pop up Window
					if ($row['operatorchat']) $openop = ' <a href="javascript:void(0)" class="btn btn-info btn-sm jakweb-oponline" data-user="'.$row['id'].':#:'.$row['username'].'"><i class="fa fa-user"></i></a>';
				} else {
					// Slide Up Window

					// Now let us add the active chat file so the operator can come online.
					$chatsfile = APP_PATH.JAK_CACHE_DIRECTORY.'/chats.txt';
	
					if (file_exists($chatsfile)) {
	
						$trimmed = file($chatsfile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		
						foreach ($trimmed as $v) {
							
							$opid = explode(":#:", $v);
							
							// Check if we have a chat request
							if ($row['operatorchat'] && $opid[2] == $_GET['uid'] && $opid[0] > ($currentime - 10)) {

								$newopmsg = 'primary';
								$countops++;
								
							}
							
						}
		
					}

					if ($row['operatorchat']) {

						$openchat = " onclick=\"if(navigator.userAgent.toLowerCase().indexOf('opera') != -1 && window.event.preventDefault) window.event.preventDefault();this.newWindow = window.open('".str_replace('ajax/', '', JAK_rewrite::jakParseurl('chat', $_GET['uid'], $row['id']))."', 'lsr', 'toolbar=0,scrollbars=1,location=0,status=1,menubar=0,width=600,height=520,resizable=1');this.newWindow.focus();this.newWindow.opener=window;return false;\"";

						$openop = '<a href="javascript:void(0)"'.$openchat.' class="btn btn-'.$newopmsg.'" data-user="'.$row['id'].':#:'.$row['username'].'"><i class="fa fa-user"></i></a>';

					}

				}

				$oponline .= '<li class="list-group-item list-group-item-'.$newopmsg.'">'.$row['name'].' <div class="btn-group btn-group-sm"><a href="'.str_replace('ajax/', '', JAK_rewrite::jakParseurl('uonline', 'opstat', $row['id'])).'" data-toggle="modal" data-target="#jakModal" class="btn btn-secondary"><i class="fa fa-chart-bar"></i></a>'.$openop.'</div></li>';
				
			}
			
			$oponline .= '</ul>';
			
		}
	
	}

	// Reset vars
	$transfer_msg = $jsmsg = $newclient = $soundjs = $newmsg = $soundmsgjs = '';
	$newConv = $transferid = 0;
	$loadClients = false;
	$convid = $answers = $typing = array();

	$resnew = $jakdb->select("checkstatus", ["convid", "depid", "operatorid", "operator", "newo", "transferoid", "transferid", "typec", "alert", "statusc", "initiated"], ["AND" => ["hide" => 0, "denied" => 0]]);
	
	if (isset($resnew) && !empty($resnew)) {
	
		foreach ($resnew as $row) {
			// We have a dead client connection cancel it.
			if ($row['statusc'] && (($currentime - $row['statusc']) > JAK_CLIENT_LEFT)) {
				
				$jakdb->update("sessions", ["status" => 0, "ended" => $currentime], ["id" => $row['convid']]);
				$jakdb->update("checkstatus", ["hide" => 1], ["convid" => $row['convid']]);
				$cname = $jakdb->get("sessions", "name", ["id" => $row['convid']]);

				$jakdb->insert("transcript", [ 
					"name" => $jkl['g274'],
					"message" => sprintf($jkl['g168'], $cname),
					"convid" => $row['convid'],
					"class" => "notice",
					"plevel" => 2,
					"time" => $jakdb->raw("NOW()")]);
				break;
			}

			// The client is expired
			if ($row['statusc'] && (($currentime - $row['statusc']) > JAK_CLIENT_EXPIRED)) {

				$jakdb->update("sessions", ["status" => 0, "ended" => $currentime], ["id" => $row['convid']]);
				$jakdb->update("checkstatus", ["hide" => 1], ["convid" => $row['convid']]);

				$jakdb->insert("transcript", [ 
					"name" => $jkl['g274'],
					"message" => $jkl['g72'],
					"convid" => $row['convid'],
					"class" => "notice",
					"time" => $jakdb->raw("NOW()")]);
				break;
			}
			
			// We have a transfer, need to display it!
			if ($row['transferoid'] == $_GET['uid']) {

				$trow = $jakdb->get("transfer", ["fromname", "message"], ["AND" => ["tooid" => $_GET['uid'], "convid" => $row['convid'], "used" => 0]]);

				if (isset($trow) && !empty($trow)) {

					// Display underneath the button
					$transfer_msg = '<h4>'.sprintf($jkl['g110'], $trow["fromname"]).'</h4><p>'.$trow["message"].'</p><div class="transfer-btn"><a href="javascript:void(0)" class="btn btn-sm btn-danger" onclick="acceptTransfer(0, '.$row['transferoid'].', '.$row['convid'].');"><i class="fa fa-times"></i></a><a href="javascript:void(0)" class="btn btn-sm btn-success" onclick="acceptTransfer(1, '.$row['transferoid'].', '.$row['convid'].');"><i class="fa fa-check"></i></a></div>';
					$transferid = $row['transferid'];
					$jsmsg = sprintf($jkl['g110'], $trow["fromname"]);
					$soundjs = $jakopsett['newclient'];

				}
			}

			// Only load if we are in the correct department
			if (isset($_SESSION['usr_department']) && ($_SESSION['usr_department'] == 0 || $row["depid"] == $_SESSION['usr_department'] || in_array($row["depid"], explode(",", $_SESSION['usr_department'])) || $row["operatorid"] == $_GET['uid'])) {
				// check for new conversations
				if ($row['operatorid'] == 0 || ($row['operatorid'] == $_GET['uid'] && empty($row['operator']))) {
					$newConv = 1;
					$sendalert = 0;
					if ($row["alert"] == 0) {
						$jakdb->update("checkstatus", ["alert" => 1], ["convid" => $row['convid']]);
						$sendalert = 1;
					}
					$newclient = $jkl['g69'];
					$soundjs = JAK_RING_TONE;
					if ($row['typec']) $typing[] = $row['convid'];
					$convid[] = $row["convid"];
					$loadClients = true;
				}
				if ($row['operatorid'] == $_GET['uid'] && !empty($row['operator'])) {
					$jakdb->update("checkstatus", ["statuso" => $currentime], ["convid" => $row['convid']]);
					if ($row['typec']) $typing[] = $row['convid'];
					$convid[] = $row["convid"];
					$loadClients = true;
				}
				if ($row['operatorid'] == $_GET['uid'] && $row['newo']) {
					$newConv = 2;
					$newmsg = $jkl['g70'];
					$soundmsgjs = JAK_MSG_TONE;
					$answers[] = $row['convid'];
					if ($row['typec']) $typing[] = $row['convid'];
					$convid[] = $row["convid"];
					$loadClients = true;
 				}
			}
		} 
	}
	
	// Reset convlist
	$convlist = '';

	// Now let's get the conversation list
	$new = $updated = $current = array();
	$count = 0;
	
	// Only go for it if we want to
	if ($loadClients) {
		
		// Check if there is a new client, message or a transfer is awaiting for approval.
		$resconv = $jakdb->select("sessions", ["[>]buttonstats" => ["session" => "session"]], ["sessions.id", "sessions.name", "sessions.operatorname", "sessions.countrycode", "sessions.status", "sessions.ended", "buttonstats.referrer"], ["sessions.id" => $convid]);
		
		if (isset($resconv) && !empty($resconv)) {
		
			foreach ($resconv as $row) {
				
				if ($row['status']) {

					// Get all available chats
					if ($row['operatorname'] == "") {
						$new[$count]["name"] = $row['name'];		
						$new[$count]["convid"] = $row['id'];
						$new[$count]["countrycode"] = $row['countrycode'];
						$new[$count]["referrer"] = $row['referrer'];
						$new[$count]["typing"] = '';
						if (!empty($typing) && in_array($row['id'], $typing)) $new[$count]["typing"] = ' <i class="fa fa-pencil"></i>';
					} elseif (!empty($answers) && $row['operatorname'] && in_array($row['id'], $answers)) {
						$updated[$count]["name"] = $row['name'];
			            $updated[$count]["convid"] = $row['id'];
			            $updated[$count]["countrycode"] = $row['countrycode'];
			            $updated[$count]["referrer"] = $row['referrer'];
			            $updated[$count]["typing"] = '';
			            if (!empty($typing) && in_array($row['id'], $typing)) $updated[$count]["typing"] = ' <i class="fa fa-pencil"></i>';
						
					} else {
						$current[$count]["name"] = $row['name'];
			            $current[$count]["convid"] = $row['id'];
			            $current[$count]["countrycode"] = $row['countrycode'];
			            $current[$count]["referrer"] = $row['referrer'];
			            $current[$count]["typing"] = '';
			            if (!empty($typing) && in_array($row['id'], $typing)) $current[$count]["typing"] = ' <i class="fa fa-pencil"></i>';
					}
				}
		
				if (!$row['status']) {
					if ((($currentime - $row['ended']) > 600)) {

						$jakdb->update("sessions", ["hide" => 1], ["id" => $row['id']]);
						$jakdb->update("checkstatus", ["hide" => 1], ["convid" => $row['id']]);

						$jakdb->insert("transcript", [ 
							"name" => $jkl['g274'],
							"message" => $jkl['g73'],
							"convid" => $row['id'],
							"class" => "notice",
							"plevel" => 2,
							"time" => $jakdb->raw("NOW()")]);
						
					}
				}

				$count++;

			}
	
		shuffle($new);
		shuffle($updated);
		shuffle($current);
		sort($new);
		sort($updated);
		sort($current);
		$newTotal = count($new);
		$updatedTotal = count($updated);
		$currentTotal = count($current);

		for($i = 0; $i < $newTotal; $i ++ ) {
			$convlist .= '<a href="'.str_replace('ajax/', '', JAK_rewrite::jakParseurl('live', $new[$i]["convid"])).'" class="dropdown-item a-confirm-chat" data-convid="'.$new[$i]["convid"].'" data-opid="'.$_GET['uid'].'" data-title="'.addslashes($jkl["g108"]).'" data-type="info" data-okbtn="'.addslashes($jkl["g283"]).'" data-cbtn="'.addslashes($jkl["g203"]).'"><span class="pull-xs-right">'.(isset($new[$i]['countrycode']) && !empty($new[$i]['countrycode'] && $new[$i]['countrycode'] != 'xx') ? '<img src="'.str_replace("ajax/", "", BASE_URL."img/blank.png").'" class="flag flag-'.$new[$i]['countrycode'].'"> ' : '').'</span>'.$new[$i]["name"].$new[$i]["typing"].'<br>'.$new[$i]["referrer"].'</a>';
		}
		for($i = 0; $i < $updatedTotal; $i ++ ) {

			$convlist .= '<a href="'.str_replace('ajax/', '', JAK_rewrite::jakParseurl('live', $updated[$i]["convid"])).'" class="dropdown-item'.($updated[$i]["convid"] == $_SESSION["activechatid"] ? ' new-active' : ' new').'"><span class="pull-xs-right">'.(isset($updated[$i]['countrycode']) && !empty($updated[$i]['countrycode'] && $updated[$i]['countrycode'] != 'xx') ? '<img src="'.str_replace("ajax/", "", BASE_URL."img/blank.png").'" class="flag flag-'.$updated[$i]['countrycode'].'"> ' : '').'</span>'.$updated[$i]["name"].$updated[$i]["typing"].'<br>'.$updated[$i]["referrer"].'</a>';
		}
		for($i = 0; $i < $currentTotal; $i ++ ) {

			$convlist .= '<a href="'.str_replace('ajax/', '', JAK_rewrite::jakParseurl('live', $current[$i]["convid"])).'" class="dropdown-item'.($current[$i]["convid"] == $_SESSION["activechatid"] ? ' active' : '').'"><span class="pull-xs-right">'.(isset($current[$i]['countrycode']) && !empty($current[$i]['countrycode'] && $current[$i]['countrycode'] != 'xx') ? '<img src="'.str_replace("ajax/", "", BASE_URL."img/blank.png").'" class="flag flag-'.$current[$i]['countrycode'].'"> ' : '').'</span>'.$current[$i]["name"].$current[$i]["typing"].'<br>'.$current[$i]["referrer"].'</a>';
		}
	
	}
	
}

// Is the site we are on active or already expired
$activeconv = false;
if (is_array($convid) && in_array($_GET['activconv'], $convid)) $activeconv = true;

// Let us check the live preview file
$prevmsgonly = "";
$prevtxt = false;
if (isset($_GET['activconv']) && is_numeric($_GET['activconv']) && $activeconv) {
	// Check the file from the cache directory
	$livepreviewfile = APP_PATH.JAK_CACHE_DIRECTORY.'/livepreview'.$_GET['activconv'].'.txt';

	if (file_exists($livepreviewfile)) {

		$prev = $jakdb->get("sessions", ["name", "usr_avatar", "department"], ["id" => $_GET['activconv']]);

		// Get the absolute url for the image
		$ava_url = str_replace(JAK_OPERATOR_LOC.'/ajax/', '', BASE_URL);

		$prevmsg = file_get_contents($livepreviewfile);

		// We convert the br
		$prevmsg = nl2br($prevmsg, false);

		$prevtxt = '<div id="prevcontainer_'.$_GET['activconv'].'"><div class="media livepreview">
					<img class="d-flex mr-3 img-thumbnail" src="'.$ava_url.$prev["usr_avatar"].'" width="53" height="53" alt="live preview">
					<div class="media-body">
					   	<h4 class="media-heading">'.$prev['name'].' <i class="fa fa-pencil animate__animated animate__flash animate__slower animate__infinite infinite"></i> <small>'.JAK_base::jakTimesince($currentime, "", JAK_TIMEFORMAT).'</small></h4>
					    <div class="media-text" id="prevmsg_'.$_GET['activconv'].'">'.stripcslashes($prevmsg).'</div>
					</div>
					</div>
					<hr></div>';

		$prevmsgonly = stripcslashes($prevmsg);
	}
}
	
die(json_encode(array("useronline" => $useronline, "useronlinemap" => $useronlinemap, "totalonline" => $userocount, "nouseronline" => $jkl['i3'], "oponline" => $oponline, 'newc' => $newConv, 'tid' => $transferid, 'tmsg' => $transfer_msg, 'jsmsg' => $jsmsg, 'newclient' => $newclient, 'soundjs' => $soundjs, 'soundalert' => $sendalert, 'newmsg' => $newmsg, 'soundmsgjs' => $soundmsgjs, "conversation" => $convlist, "noconv" => $jkl['g79'], 'totalchats' => $count, 'totalops' => $countops, 'previewmsg' => $prevtxt, 'prevmsgonly' => $prevmsgonly)));
?>