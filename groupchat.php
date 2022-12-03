<?php

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 5.0.3                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2022 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

// Message format: time:#!#:userid:#!#:name:#!#:avatar:#!#:message:#!#:quote;

// Check if the file is accessed only via index.php if not stop the script from running
if (!defined('JAK_PREVENT_ACCESS')) die('You cannot access this file directly.');

// start buffer
ob_start();

// Reset some vars for the html use
$gcoffline = $gcmax = $gcop = false;
$gclogin = true;
$errors = array();

// Now check the button id
$cachegroupchat = APP_PATH.JAK_CACHE_DIRECTORY.'/groupchat'.$page1.'.php';
if (!file_exists($cachegroupchat)) {
$gcoffline = true;
} else {

// The group chat settings
include_once $cachegroupchat;

// Back to the chat
$gochat = JAK_rewrite::jakParseurl('groupchat', $page1, $groupchat["lang"]);

// The chat file
$groupchatfile = APP_PATH.JAK_CACHE_DIRECTORY.'/groupchat'.$page1.'.txt';

// Check if operator is logged in and has access to the chat otherwise show the login form
$jakuserlogin = new JAK_userlogin();
$jakuserrow = $jakuserlogin->jakChecklogged();
$jakuser = new JAK_user($jakuserrow);

// Let's check if the operator has access
if ($jakuser->getVar("id") && (isset($groupchat['opids']) && ($groupchat['opids'] == 0 || in_array($jakuser->getVar("id"), explode(",", $groupchat['opids']))))) {
	$gcop = true;
	// Insert the user into the group chat database
	if (!isset($_SESSION['gcopid']) && empty($_SESSION['gcopid'])) {

		// Current time
		$ctime = time();

		// Create the session
		if (!isset($_SESSION['gcuid'])) {
			$salt = rand(100, 99999);
			$gcid = $salt.$ctime;
		}

		$jakdb->insert("groupchatuser", [ 
			"groupchatid" => $page1,
			"name" => $jakuser->getVar("name"),
			"usr_avatar" => $jakuser->getVar("picture"),
			"statusc" => $ctime,
			"ip" => $ipa,
			"isop" => 1,
			"session" => $gcid,
			"created" => $jakdb->raw("NOW()")]);

		$cid = $jakdb->id();

		// The left message
		$chatopwelcome = sprintf($jkl['g76'], $jakuser->getVar("name"));
		
		// The welcome final message
		$clmsg = time().':#!#:'.$cid.':#!#:'.$jakuser->getVar("name").':#!#:'.$jakuser->getVar("picture").':#!#:'.$chatopwelcome.':#!#::#!#:true:!n:';

		// Let's inform others that a new client has entered the chat
		file_put_contents($groupchatfile, $clmsg, FILE_APPEND);

		// Set the operator sessions for the public chat
		$_SESSION['groupchatid'] = $page1;
		$_SESSION['gcname'] = $jakuser->getVar("name");
		$_SESSION['gcavatar'] = $jakuser->getVar("picture");
		$_SESSION['gcuid'] = $cid;
		$_SESSION['gcopid'] = $jakuser->getVar("id");
	}
}

// Ok user is not logged in, show the login form or the message that the chat is full
if ($gcop || (isset($_SESSION['gcuid']) && !empty($_SESSION['gcuid']))) {

	$gclogout = false;

	// Logout feature
	if ($page2 == "logout") {

		// Get the user information
		$row = $jakdb->get("groupchatuser", ["id", "name", "usr_avatar"], ["id" => $_SESSION['gcuid']]);

		// Remove the user from the database
		$jakdb->delete("groupchatuser", ["id" => $row['id']]);

		// The left message
		$chatleave = sprintf($jkl['g16'], $_SESSION['gcname']);
		
		// The welcome final message
		$clmsg = time().':#!#:0:#!#:'.$_SESSION['gcname'].':#!#:'.$row['usr_avatar'].':#!#:'.$chatleave.':#!#::#!#:false:!n:';

		// Let's inform others that a new client has entered the chat
		file_put_contents($groupchatfile, $clmsg, FILE_APPEND);

		// Unset the sessions
		$gclogout = true;
	}

	// Check if that customer still exists
	if (!$jakdb->has("groupchatuser", ["id" => $_SESSION['gcuid']])) {
		// Unset the sessions
		$gclogout = true;
	}

	// Unset the sessions
	if ($gclogout) {
		unset($_SESSION['groupchatid']);
		unset($_SESSION['gcname']);
		unset($_SESSION['gcuid']);
		unset($_SESSION['gcavatar']);
		if (isset($_SESSION['gcopid']) && !empty($_SESSION['gcopid'])) {
			unset($_SESSION['gcopid']);
			jak_redirect(JAK_rewrite::jakParseurl(JAK_OPERATOR_LOC));
		} else {
			// Set success message
			$_SESSION['hasloggedout'] = true;
			// Redirect back to the chat
			jak_redirect(JAK_rewrite::jakParseurl('groupchat', $page1, $groupchat["lang"]));
		}
	}

	// All is normal just show the chat
	$gclogin = false;

	// Reset the last visit session
	unset($_SESSION["vislasttime"]);

} else {

	// Count total user
	$gcuser = $jakdb->count("groupchatuser", ["groupchatid" => $page1]);

	// if we have reached the maximum clients or the ip is blocked abort
	if ($gcuser >= $groupchat['maxclients'] || $USR_IP_BLOCKED) {
		$gcmax = true;
	} else {

		// Login form
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['groupchat'])) {

			// Shorter post var
			$jkp = $_POST;

			$dbSmall = "";

			// Check Name at least 3
			if (empty($jkp['name']) || strlen(trim($jkp['name'])) <= 2) {
				$errors['name'] = $jkl['e'];
			}

			// Check if it is valid
			if (!preg_match('/^([a-zA-Z0-9\-_ ])+$/', $jkp["name"])) {
				$errors['name'] = $jkl['e16'];
			}

			// We have a password check if it is correct.
			if (!empty($groupchat['password']) && $groupchat['password'] != $jkp["password"]) {
				$errors['password'] = $jkl['l'];
			}

			// We have a name, let's check if it exists already.
			if (count($errors) == 0) {

				if ($jakdb->has("groupchatuser", ["AND" => ["groupchatid" => $page1, "name" => $jkp['name']]])) {
					$errors['name'] = $jkl['e15'];
				}
			}

			// We have a custom avatar
			if (count($errors) == 0) {
				if (!empty($_FILES['customavatar']['name'])) {

					if ($_FILES['customavatar']['name'] != '') {

				    	$filename = $_FILES['customavatar']['name']; // original filename
				    	// Fix explode when upload in 1.2
				    	$tmpf = explode(".", $filename);
				    	$jak_xtension = end($tmpf);
				    	
				    	if ($jak_xtension == "jpg" || $jak_xtension == "jpeg" || $jak_xtension == "png" || $jak_xtension == "gif") {

				    		// Get the maximum upload or set to 2
				    		$postmax = (ini_get('post_max_size') ? filter_var(ini_get('post_max_size'), FILTER_SANITIZE_NUMBER_INT) : "2");

				    		if ($_FILES['customavatar']['size'] <= ($postmax * 1000000)) {

				    			list($width, $height, $type, $attr) = getimagesize($_FILES['customavatar']['tmp_name']);
				    			$mime = image_type_to_mime_type($type);

				    			if (($mime == "image/jpeg") || ($mime == "image/pjpeg") || ($mime == "image/png") || ($mime == "image/gif")) {

							    	// first get the target path
				    				$targetPathd = APP_PATH.JAK_FILES_DIRECTORY.'/user/';
				    				$targetPath =  str_replace("//","/",$targetPathd);

							    	// Create the target path
				    				if (!is_dir($targetPath)) {
				    					mkdir($targetPath, 0755);
				    					copy(APP_PATH.JAK_FILES_DIRECTORY."/index.html", $targetPath . "/index.html");

				    				}

				    				$tempFile = $_FILES['customavatar']['tmp_name'];
				    				$origName = substr($_FILES['customavatar']['name'], 0, -4);
				    				$name_space = strtolower($_FILES['customavatar']['name']);
				    				$middle_name = str_replace(" ", "_", $name_space);
				    				$middle_name = str_replace(".jpeg", ".jpg", $name_space);
				    				$glnrrand = rand(10, 99);
				    				$bigPhoto = str_replace(".", "_" . $glnrrand . ".", $middle_name);
				    				$smallPhoto = str_replace(".", "_t.", $bigPhoto);

				    				$targetFile =  str_replace('//','/',$targetPath) . $bigPhoto;
				    				$origPath = '/user/';
				    				$dbSmall = $origPath.$smallPhoto;

				    				require_once 'include/functions_thumb.php';
							    	// Move file and create thumb     
				    				move_uploaded_file($tempFile,$targetFile);

				    				create_thumbnail($targetPath, $targetFile, $smallPhoto, 250, 250, 80);

							    	// remove target file
				    				if (is_file($targetFile)) unlink($targetFile);

				    			} else {
				    				$errors['uploadavatar'] = $jkl['e9'].'<br>';
				    			}

				    		} else {
				    			$errors['uploadavatar'] = $jkl['e9'].'<br>';
				    		}

				    	} else {
				    		$errors['uploadavatar'] = $jkl['e9'].'<br>';
				    	}

				    } else {
				    	$errors['uploadavatar'] = $jkl['e9'].'<br>';
				    }

				}
			}

			// Ok name is not given or already taken
			if (count($errors) > 0) {

				$errors = $errors;
				
			// Proceed with the login
			} else {

				// Get the avatar
				$avatar = "";
				if (isset($dbSmall) && !empty($dbSmall)) {
					$avatar = $dbSmall;
				} elseif (isset($jkp['avatar']) && $jkp['avatar']) {
					$avatar = $jkp['avatar'];
				} else {
					$avatar = "/standard.jpg";
				}

				// Current time
				$ctime = time();

				// Create the session
				if (!isset($_SESSION['gcuid'])) {
					$salt = rand(100, 99999);
					$gcid = $salt.$ctime;
				}

				// User is still banned after logout and login again
				$usrbanned = 0;
				if (isset($_SESSION["usrbanned"])) $usrbanned = 1;

				// Insert the user into the group chat database
				$jakdb->insert("groupchatuser", [ 
					"groupchatid" => $page1,
					"name" => $jkp['name'],
					"usr_avatar" => $avatar,
					"statusc" => $ctime,
					"banned" => $usrbanned,
					"ip" => $ipa,
					"session" => $gcid,
					"created" => $jakdb->raw("NOW()")]);

				$cid = $jakdb->id();

				// Set the user stuff into a session
				$_SESSION['groupchatid'] = $page1;
				$_SESSION['gcname'] = $jkp["name"];
				$_SESSION['gcavatar'] = $avatar;
				$_SESSION['gcuid'] = $cid;

				// The welcome message
				$chatenter = sprintf($jkl['g76'], $jkp["name"]);

				// The welcome final message
				$cwmsg = $ctime.':#!#:'.$cid.':#!#:'.$_SESSION['gcname'].':#!#:'.$avatar.':#!#:'.$chatenter.':#!#::#!#:false:!n:';

				// Let's inform others that a new client has entered the chat
				file_put_contents($groupchatfile, $cwmsg, FILE_APPEND);

				// No operator is online for this chat, send a push notification when available.
				if (!$jakdb->has("groupchatuser", ["groupchatid" => $page1])) {
					// Now send notifications if whish so
					$result = $jakdb->select("user", ["id", "hours_array", "pusho_tok", "pusho_key", "hours_array"], ["AND" => ["access" => 1, "pusho_tok[!]" => null]]);
					if (isset($result) && !empty($result)) {

						foreach ($result as $row) {

							if (JAK_base::jakAvailableHours($row["hours_array"], date('Y-m-d H:i:s')) && ($groupchat['opids'] == 0 || in_array($row["id"], explode(",", $groupchat['opids'])))) {

								// Pushover
								if ($row["pusho_tok"] && $row["pusho_key"]) {

									$c = curl_init();
									curl_setopt($c, CURLOPT_URL, "https://api.pushover.net/1/messages.json");
									curl_setopt($c, CURLOPT_HEADER, false);
									curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
									curl_setopt($c, CURLOPT_POSTFIELDS, array(
										"token" => $row["pusho_tok"],
										"user" => $row["pusho_key"],
										"message" => JAK_TW_MSG.' '.$groupchat['title'],
										"title" => JAK_TITLE,
										"url" => $gochat
									));
									$response = curl_exec($c);
									curl_close($c);

								}
							}
						}
					}
				}

				/* Go to the chat */
				jak_redirect($gochat);

			}

		}
	} 
}

}

// Load messages for the group chat.
if (empty($headermsg)) {
if (!empty($HD_ANSWERS) && is_array($HD_ANSWERS)) foreach ($HD_ANSWERS as $v) {
	
	if ($v["msgtype"] == 11 && $v["lang"] == $page2) {

		$phold = array("%operator%","%client%","%email%");
		$replace   = array("", "", JAK_EMAIL);
		$welcomemsg = str_replace($phold, $replace, $v["message"]);
		
	}

	if ($v["msgtype"] == 12 && $v["lang"] == $page2) {

		$phold = array("%operator%","%client%","%email%");
		$replace   = array("", "", JAK_EMAIL);
		$offlinemsg = str_replace($phold, $replace, $v["message"]);
		
	}

	if ($v["msgtype"] == 13 && $v["lang"] == $page2) {

		$phold = array("%operator%","%client%","%email%");
		$replace   = array("", "", JAK_EMAIL);
		$fullmsg = str_replace($phold, $replace, $v["message"]);
		
	}
	
}
}

?>
<!DOCTYPE html>
<html lang="<?php echo $BT_LANGUAGE;?>">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="author" content="Live Chat 3">
<title><?php echo $groupchat["title"];?> - <?php echo JAK_TITLE;?></title>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="<?php echo BASE_URL;?>css/stylesheet.css" type="text/css" media="screen">
<link rel="stylesheet" href="<?php echo BASE_URL;?>css/groupchat.css" type="text/css" media="screen">

<?php if ($jkl["rtlsupport"]) { ?>
<!-- RTL Support -->
<link rel="stylesheet" href="https://cdn.rtlcss.com/bootstrap/v4.5.3/css/bootstrap.min.css" integrity="sha384-JvExCACAZcHNJEc7156QaHXTnQL3hQBixvj5RV5buE7vgnNEzzskDtx9NQ4p6BJe" crossorigin="anonymous">
<!-- End RTL Support -->
<?php } ?>

<!-- Le fav and touch icons -->
<link rel="shortcut icon" href="<?php echo BASE_URL;?>img/ico/favicon.ico">

</head>
<body>

<div class="group-chat-main">
	<div class="online-users">
		<i class="fas fa-bars fa-2x"></i>
		<h2>
			<?php echo $jkl['g87'];?>
		</h2>

		<!-- User List -->
		<div id="visitorslist" class="flowvisitors"></div>

	</div>
	<div class="chat">
		<div class="contact bar">
			<div class="row">
				<div class="col-md-10">
					<h3><?php echo $groupchat["title"];?></h3>
					<p><?php echo $groupchat["description"];?></p>
				</div>
				<div class="col-md-2">
					<div class="text-right">
						<a href="<?php echo JAK_rewrite::jakParseurl('groupchat', $page1, "logout");?>" class="btn btn-sm btn-danger btn-confirm" data-title="<?php echo addslashes($jkl["g90"]);?>" data-text="<?php echo addslashes($jkl["g40"]);?>" data-type="" data-okbtn="<?php echo addslashes($jkl["g72"]);?>" data-cbtn="<?php echo addslashes($jkl["g73"]);?>"><i class="fa fa-sign-out"></i> <?php echo $jkl['g26'];?></a>
					</div>
				</div>
			</div>
		</div>

		<!-- Load the chat messages -->
		<div class="messages" id="group_chat_output"></div>

		<!-- Show the input field -->
		<div class="input">
			<div id="emoji"></div>
			<input type="text" name="message" id="message" class="form-control" placeholder="<?php echo $jkl["g6"];?>"><span id="sendMessage"><i class="fas fa-paper-plane"></i></span>
			<input type="hidden" name="msgquote" id="msgquote">
		</div>
	</div>
</div>

<?php if ($gclogin) { ?>
	<!-- Login Form -->

	<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-hidden="false">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title"><?php echo $groupchat["title"];?></h3>
				</div>
				<div class="modal-body">

					<p class="text-center"><?php echo $groupchat["description"];?></p>

					<!-- Chat is offline -->
					<?php if ($gcoffline) { ?>

						<div class="alert alert-danger"><?php echo $offlinemsg;?></div>

						<!-- Chat is full -->
					<?php } elseif ($gcmax) { ?>

						<div class="alert alert-info"><?php echo $fullmsg;?></div>

					<?php } else { ?>

						<div class="form-signin text-center">

							<form method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>" enctype="multipart/form-data">

								<div class="row avatars">
									<div class="col-3">
										<label>
											<input type="radio" name="avatar" value="/4.jpg">
											<img src="<?php echo BASE_URL.JAK_FILES_DIRECTORY;?>/4.jpg" class="rounded img-fluid" alt="avatar4">
										</label>
									</div>
									<div class="col-3">
										<label>
											<input type="radio" name="avatar" value="/2.jpg">
											<img src="<?php echo BASE_URL.JAK_FILES_DIRECTORY;?>/2.jpg" class="rounded img-fluid" alt="avatar2">
										</label>
									</div>
									<div class="col-3">
										<label>
											<input type="radio" name="avatar" value="/3.jpg">
											<img src="<?php echo BASE_URL.JAK_FILES_DIRECTORY;?>/3.jpg" class="rounded img-fluid" alt="avatar3">
										</label>
									</div>
									<div class="col-3">
										<label>
											<input type="radio" name="avatar" value="/1.jpg">
											<img src="<?php echo BASE_URL.JAK_FILES_DIRECTORY;?>/1.jpg" class="rounded img-fluid" alt="avatar">
										</label>
									</div>
								</div>

								<hr>

								<img id="customavatar" class="rounded <?php if (isset($errors["customavatar"]) && !empty($errors["customavatar"])) echo ' is-invalid';?>">
								<div class="avatarupload btn btn-primary">
									<span><i class="fa fa-camera"></i> <?php echo $jkl["g18"];?></span>
									<input type="file" id="uploadavatar" class="upload" name="customavatar" accept="image/*">
								</div>

								<div class="form-group mt-3">
									<label for="name" class="sr-only"><?php echo $jkl["g27"];?></label>
									<input type="text" name="name" class="form-control<?php if (isset($errors["name"]) && !empty($errors["name"])) echo ' is-invalid';?>" id="name" placeholder="<?php echo $jkl["g27"];?>">
									<span id="name-help" class="help-block"><?php if (isset($errors["name"]) && !empty($errors["name"])) echo $errors["name"];?></span>
								</div>

								<?php if (!empty($groupchat['password'])) { ?>

									<div class="form-group">
										<label for="password" class="sr-only"><?php echo $jkl["g27"];?></label>
										<input type="password" name="password" class="form-control<?php if (isset($errors["password"]) && !empty($errors["password"])) echo ' is-invalid';?>" placeholder="<?php echo $jkl["g77"];?>">
									</div>

								<?php } ?>

								<input type="hidden" name="groupchat" value="1">
								<p class="mb-0"><button type="submit" name="start_gchat" id="start_gchat" class="btn btn-success ls-submit"><i class="fa fa-sign-in"></i> <?php echo $jkl["g17"];?></button></p>
							</form>

							<?php if (isset($jakhs['copyright']) && !empty($jakhs['copyright'])) echo '<div class="copyright mt-3">'.$jakhs['copyright'].'</div>';?>

						</div>

					<?php } ?>

				</div>
			</div>
		</div>
	</div>

	<?php } ?>

	<!-- Javascript Files -->
	<script src="<?php echo BASE_URL;?>js/jquery.js?=<?php echo JAK_UPDATED;?>"></script>
	<script src="<?php echo BASE_URL;?>js/functions.js?=<?php echo JAK_UPDATED;?>"></script>

	<?php if ($gclogin) { ?>
		<script src="<?php echo BASE_URL;?>js/jakavatar.js"></script>
		<script>

			ls.ls_submit = "<?php echo $jkl['g17'];?>";
			ls.ls_submitwait = "<?php echo $jkl['g8'];?>";

			$('#loginModal').modal({backdrop: 'static', keyboard: false});

		</script>
	<?php } else { ?>
		<script src="<?php echo BASE_URL.JAK_OPERATOR_LOC;?>/js/emoji.js"></script>
		<script src="<?php echo BASE_URL;?>js/groupchat.js?=<?php echo JAK_UPDATED;?>"></script>
		<script>
			$(document).ready(function() {
				$("#emoji").emojioneArea();
			});
		</script>
	<?php } ?>
	<script>
		ls.main_url = "<?php echo BASE_URL;?>";
		ls.lsrequest_uri = "<?php echo JAK_PARSE_REQUEST;?>";
	</script>
</body>
</html>
<?php unset($_SESSION['hasloggedout']); ob_flush(); ?>