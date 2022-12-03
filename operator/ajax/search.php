<?php

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 3.6                   # ||
|| # ----------------------------------------- # ||
|| # Copyright 2018 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

if (!file_exists('../../config.php')) die('ajax/[available.php] config.php not exist');
require_once '../../config.php';

if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !isset($_SESSION['jak_lcp_idhash'])) die("Nothing to see here");

// Import the user or standard language file
if (isset($_SESSION['jak_lcp_lang']) && file_exists(APP_PATH.JAK_OPERATOR_LOC.'/lang/'.$_SESSION['jak_lcp_lang'].'.php')) {
    include_once(APP_PATH.JAK_OPERATOR_LOC.'/lang/'.$_SESSION['jak_lcp_lang'].'.php');
} else {
    include_once(APP_PATH.JAK_OPERATOR_LOC.'/lang/'.JAK_LANG.'.php');
}

// Reset
$searchmsg = '';
// Search
$keyword = strip_tags($_GET['q']);

$result = $jakdb->select("transcript", ["[>]user" => ["operatorid" => "id"], "[>]sessions" => ["convid" => "id"]], ["transcript.id", "transcript.name", "transcript.message", "transcript.operatorid", "transcript.time", "transcript.class", "transcript.quoted", "transcript.edited", "transcript.editoid", "transcript.convid", "user.picture", "sessions.usr_avatar"], ["AND" => ["transcript.convid" => $_GET['convid'], "transcript.message[~]" => $keyword], "ORDER" => ["transcript.id" => "ASC"]]);

		if (isset($result) && !empty($result)) {

			// include the PHP library (if not autoloaded)
			require('../../class/class.emoji.php');

			foreach ($result as $row) {

				// On which class to show a system image
				$systemimg = array("bot", "notice", "url", "ended", "download");
				
				$oimage = str_replace(JAK_OPERATOR_LOC."/ajax/", "", BASE_URL.$row["usr_avatar"]);
				if ($row["picture"] && $row["operatorid"]) $oimage = str_replace(JAK_OPERATOR_LOC."/ajax/", "", BASE_URL.JAK_FILES_DIRECTORY.$row["picture"]);
				if (in_array($row["class"], $systemimg)) $oimage = str_replace(JAK_OPERATOR_LOC."/ajax/", "", BASE_URL.JAK_FILES_DIRECTORY."/system.jpg");

				// We convert the br
				$message = nl2br($row['message'], false);

				// we have file
				if ($row['class'] == "download") {
					$lightbox = "";
		    		if (getimagesize(APP_PATH.$message)) $lightbox = ' data-toggle="lightbox"';
		    		$message = '<a'.$lightbox.' href="'.str_replace(JAK_OPERATOR_LOC."/ajax/", "", BASE_URL.$message).'" target="_blank">'.basename($message).'</a>';
				} else {
					// We convert the urls
					$message = replace_urls($message);
				}

				// Convert emotji
				$message = Emojione\Emojione::toImage($message);

				// Get the quote msg
				$quotemsg = '';
				if ($row['quoted']) {
					$quotemsg = $jakdb->get("transcript", "message", ["id" => $row["quoted"]]);
					// Convert urls
					$quotemsg = nl2br(replace_urls($quotemsg), false);

					// Convert emotji
					$quotemsg = Emojione\Emojione::toImage($quotemsg);

				}

				$searchmsg .= '<div class="media">
						<img class="d-flex mr-3 img-thumbnail" src="'.$oimage.'" width="53" height="53" alt="'.$row['name'].'">
					    <div class="media-body">
					    	<h4 class="media-heading">'.$row['name'].' <small>'.JAK_base::jakTimesince($row['time'], "", JAK_TIMEFORMAT).($row['editoid'] ? ' | <i class="fa fa-edit"></i> '.JAK_base::jakTimesince($row['edited'], "", JAK_TIMEFORMAT) : '').'</small>'.(!in_array($row["class"], $systemimg) ? ' <div class="chat-edit"><a href="javascript:void(0)" class="chat-edit-link clipboard" data-clipboard-target="#msg'.$row['id'].'"><i class="fa fa-clipboard"></i></a></div>' : '').'</h4>
					        <div class="media-text">'.($row['quoted'] ? '<blockquote class="blockquote"><i class="fa fa-reply"></i> '.$quotemsg.'</blockquote>' : '').stripcslashes($message).'</div>
					    </div>
					</div>
					<hr>';
			}
		
			echo $searchmsg;
		} else {

			// Nothing available
			echo '<div class="alert alert-info">'.$jkl["i3"].'</div>';
		}

?>