<?php
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 6 May 1998 03:10:00 GMT");

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 4.0.5                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2021 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

if (!file_exists('../../config.php')) die('ajax/[available.php] config.php not exist');
require_once '../../config.php';

// include the PHP library (if not autoloaded)
require('../../class/class.emoji.php');

if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !isset($_SESSION['jak_lcp_idhash'])) die("Nothing to see here");

// Import the user or standard language file
if (isset($_SESSION['jak_lcp_lang']) && file_exists(APP_PATH.JAK_OPERATOR_LOC.'/lang/'.$_SESSION['jak_lcp_lang'].'.php')) {
    include_once(APP_PATH.JAK_OPERATOR_LOC.'/lang/'.$_SESSION['jak_lcp_lang'].'.php');
} else {
    include_once(APP_PATH.JAK_OPERATOR_LOC.'/lang/'.JAK_LANG.'.php');
}

$statusmsg = $chatended = false;
$chatmsg = '';
$lastid = 0;
if (!isset($_SESSION["lastido"])) $_SESSION["lastido"] = 0;

// Get the absolute url for the image
$ava_url = str_replace(JAK_OPERATOR_LOC.'/ajax/', '', BASE_URL);

if (is_numeric($_POST['id'])) {

	if (isset($_POST['lastid']) && is_numeric($_POST['lastid'])) {
		$lastid = $_SESSION["lastido"];
	}

	$result = $jakdb->select("transcript", ["[>]user" => ["operatorid" => "id"], "[>]sessions" => ["convid" => "id"]], ["transcript.id", "transcript.name", "transcript.message", "transcript.operatorid", "transcript.time", "transcript.class", "transcript.starred", "transcript.quoted", "transcript.editoid", "transcript.edited", "transcript.convid", "transcript.plevel", "user.picture", "sessions.usr_avatar", "sessions.template", "sessions.avatarset", "sessions.status"], ["AND" => ["transcript.convid" => $_POST['id'], "transcript.id[>]" => $lastid], "ORDER" => ["transcript.id" => "ASC"]]);

	if (isset($result) && !empty($result)) {

		foreach ($result as $row) {

			// On which class to show a system image
			$systemimg = array("bot", "notice", "url", "ended");
			
			$oimage = $ava_url.$row["usr_avatar"];
			if ($row["picture"] && $row["operatorid"]) $oimage = $ava_url.JAK_FILES_DIRECTORY.$row["picture"];
			if (in_array($row["class"], $systemimg)) $oimage = $ava_url.'lctemplate/'.$row["template"].'/avatar/'.$row["avatarset"].'/system.jpg';

			if ($row['class'] == "ended") $chatended = true;

			// We convert the br
			$message = nl2br($row['message'], false);

			// we have file
			if ($row['class'] == "download" && file_exists(APP_PATH.JAK_FILES_DIRECTORY.$message)) {
				// We have an image
		    	if (getimagesize(APP_PATH.JAK_FILES_DIRECTORY.$message)) {
		    		$messageemoji = '<a href="'.$ava_url.JAK_FILES_DIRECTORY.$message.'" data-toggle="lightbox"><img src="'.$ava_url.JAK_FILES_DIRECTORY.$message.'" class="img-thumbnail img-fluid chat-img" alt="chat-img"></a>';
		    	} else {
		    		$messageemoji = '<a href="'.$ava_url.JAK_FILES_DIRECTORY.$message.'" target="_blank">'.basename($message).'</a>';
		    	}
			} else {
				// We convert the urls
				$messageemoji = replace_urls($message);
			}

			// Convert emotji
			$messageemoji = Emojione\Emojione::toImage($messageemoji);

			// Get the quote msg
			$quotemsg = '';
			if ($row['quoted']) {
				$quotemsg = $jakdb->get("transcript", "message", ["id" => $row["quoted"]]);
				// Convert urls
				$quotemsg = nl2br(replace_urls($quotemsg), false);

				// Convert emotji
				$quotemsg = Emojione\Emojione::toImage($quotemsg);

			}

			$chatmsg .= '<div class="media" id="postid_'.$row['id'].'">
					<img class="d-flex mr-3 img-thumbnail" src="'.$oimage.'" width="53" height="53" alt="'.$row['name'].'">
				    <div class="media-body">
				    	<h4 class="media-heading">'.$row['name'].' <small>'.JAK_base::jakTimesince($row['time'], "", JAK_TIMEFORMAT).'<span id="edited_'.$row['id'].'">'.($row['editoid'] ? ' | <i class="fa fa-edit"></i> '.JAK_base::jakTimesince($row['edited'], "", JAK_TIMEFORMAT) : '').'</span></small>'.(!in_array($row["class"], $systemimg) ? ' <div class="chat-edit"><a href="javascript:void(0)" class="chat-edit-link clipboard" data-clipboard-target="#msg'.$row['id'].'"><i class="fa fa-clipboard"></i></a>'.($row['status'] ? ' <a href="javascript:void(0)" class="edit-msg" data-id="'.$row['id'].'" data-msg="'.stripcslashes($message).'"><i class="fa fa-edit"></i></a>' : '').' <a href="javascript:void(0)" class="edit-starred" data-id="'.$row['id'].'" data-starred="'.$row['starred'].'"><i class="fa'.($row['starred'] ? '' : 'l').' fa-star"></i></a>'.($row['status'] ? ' <a href="javascript:void(0)" class="edit-quote" data-id="'.$row['id'].'"><i class="fa fa-quote-right"></i></a>' : '').(($row['status'] && $row["class"] == "admin") ? ' <a href="javascript:void(0)" class="edit-remove'.($row["plevel"] == 2 ? ' text-danger' : '').'" data-id="'.$row['id'].'" data-plevel="'.$row['plevel'].'"><i class="fa fa-trash"></i></a>' : '').'</div>' : '').'</h4>
				        <div class="media-text" id="msg'.$row['id'].'">'.($row['quoted'] ? '<blockquote class="blockquote"><i class="fa fa-reply"></i> '.$quotemsg.'</blockquote>' : '').stripcslashes($messageemoji).'</div>
				    </div>
				</div>
				<hr>';

			$lastid = $row["id"];
			$_SESSION["lastido"] = $lastid;
		}
	
		$statusmsg = true;
	}
}

die(json_encode(array('status' => $statusmsg, 'chatended' => $chatended, 'chat' => $chatmsg, 'lastid' => $lastid)));
?>