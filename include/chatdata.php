<?php

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 6 May 1998 03:10:00 GMT");

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 5.0.4                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2022 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

if (!file_exists('../config.php')) die('include/[chatdata.php] config.php not exist');
require_once '../config.php';

// include the PHP library (if not autoloaded)
require('../class/class.emoji.php');

// Get the client browser
$ua = new Browser();

if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && isset($_GET['id']) && !is_numeric($_GET['id'])) die(json_encode(array('status' => false, 'error' => "No valid ID")));

// Language file
$lang = JAK_LANG;
if (isset($_GET['lang']) && !empty($_GET['lang']) && $_GET['lang'] != $lang) $lang = $_GET['lang'];

// Import the language file
if ($lang && file_exists(APP_PATH.'lang/'.strtolower($lang).'.php')) {
    include_once(APP_PATH.'lang/'.strtolower($lang).'.php');
} else {
    include_once(APP_PATH.'lang/'.JAK_LANG.'.php');
    $lang = JAK_LANG;
}

// Get the current time
$currentime = time();
// Reset vars
$deptitle = $lastvisit = "";

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
	$cachewidget = APP_PATH.JAK_CACHE_DIRECTORY.'/widget'.$_GET['id'].'.php';
} else {
    die(json_encode(array('status' => false, 'error' => "No valid ID.")));   
}

// Finally import the cached widget file
if (file_exists($cachewidget)) include_once $cachewidget;

// Get the absolute url for the image
$base_url = str_replace('include/', '', BASE_URL);

$switchc = '';
if (isset($_GET['run']) && !empty($_GET['run'])) $switchc = $_GET['run'];

switch($switchc) {

	case 'engage':

		if (isset($_POST['customer']) && !empty($_POST['customer'])) {

			if (isset($_POST["chatstatus"]) && ($_POST["chatstatus"] == "closed")) {

				// Let's safely encrypt the chat data from the customer
				$cudetails = jak_string_encrypt_decrypt($_POST['customer'], false);

				// Let's explode the string (0 = convid, 1 = userid, 2 = name, 3 = email, 4 = phone, 5 = avatar)
				$cudetails = explode(":#:", $cudetails);

	            // Get the current status
				$row = $jakdb->get("checkstatus", ["convid", "newc", "knockknock", "hide"], ["convid" => $cudetails[0]]);
				if (isset($row) && !empty($row)) {

	                // Check if that sessions has not been ended by operator
					if ($row["hide"] != 2) {

	                    // Set the new message session
						$latestmsg = $row['newc'];

	                    // Update the status for better user handling
						$jakdb->update("checkstatus", ["statusc" => $currentime, "newc" => 0, "knockknock" => 0, "hide" => 0], ["convid" => $row['convid']]);

	                    // Get the new message sound and check if we have a page redirect.
						if ((!isset($latestmsg) && $row['newc']) || (isset($latestmsg) && $latestmsg == 1)) {

	                        // Now we get the last message and show it in the proactive window.
							$lastmessage = $jakdb->get("transcript", "message", ["AND" => ["convid" => $cudetails[0], "class" => "admin"], "ORDER" => ["id" => "DESC"]]);

							die(json_encode(array('status' => true, 'title' => $jakwidget['title'], 'newmessage' => true, 'ended' => false, 'soundalert' => JAK_CLIENT_SOUND, 'lastmessage' => $lastmessage, 'widgetstyle' => $widgetstyle, 'widgettype' => $jakwidget['widget'])));
						}

	                    // Get the knock knock
						if ($row['knockknock']) {

							die(json_encode(array('status' => true, 'knockknock' => $jkl["g22"], 'ended' => false, 'soundalert' => JAK_CLIENT_SOUND)));
						}

					}

				}
			}

		} else {

			// Reset vars
			$lastvisit = $_POST['lastvisit'];

            // Now update the online status
			if (isset($_POST['rlbid']) && !empty($_POST['rlbid'])) $jakdb->update("buttonstats", ["lasttime" => $jakdb->raw("NOW()")], ["session" => $_POST['rlbid']]);

			// Now let's check every 30 seconds if we still have operators online (live status)
			if (JAK_LIVE_ONLINE_STATUS && isset($_POST['lastvisit']) && is_numeric($_POST['lastvisit']) && ($_POST['lastvisit'] + 10) < $currentime) {

                // Holiday Mode set to offline
				if (JAK_HOLIDAY_MODE > 0) {
					$onoff = "offline";
                // Check if an operator is online
				} else {
					$onoff = (online_operators($LC_DEPARTMENTS, $jakwidget['depid'], $jakwidget['opid']) ? "online" : "offline");
				}

                // The status has been changed since last time
				if (isset($_POST['onlinestatus']) && $onoff != $_POST['onlinestatus']) {

					die(json_encode(array('status' => true, 'widget' => true, 'onlinestatus' => $onoff, 'lastvisit' => $lastvisit)));

				}

			}

			// We correct the last visit if it is older than 15
			if (isset($_POST['lastvisit']) && is_numeric($_POST['lastvisit']) && ($_POST['lastvisit'] + 15) < $currentime) {
				// Set last time visited so we can fire the pro active at the right time
				$lastvisit = time();
			}

			// Now let us delete and recreate the proactive cache file
        	$proactivefile = APP_PATH.JAK_CACHE_DIRECTORY.'/proactive.php';
        	// Manual pro activate
        	$mproactivefile = APP_PATH.JAK_CACHE_DIRECTORY.'/mproactive.php';

	        // Let's check if an operator is online
			if (isset($_POST['onlinestatus']) && $_POST['onlinestatus'] == "online" && isset($_POST["chatstatus"]) && $_POST["chatstatus"] == "closed" && (!isset($_POST["engage"]) || empty($_POST["engage"]))) {

				if (file_exists($mproactivefile)) {

					// We need the pro active file
					include_once $mproactivefile;

					if (isset($LV_MPROACTIVE) && !empty($LV_MPROACTIVE)) {

						foreach($LV_MPROACTIVE as $v) {

							if (isset($_POST['rlbid']) && $v["session"] == $_POST['rlbid']) {

								// Set the engage message to shown
								$jakdb->update("buttonstats", ["message" => $v['message'], "readtime" => 1], ["session" => $_POST['rlbid']]);

								// Get the proactive message in vars so they can be loaded into the template
								$engtitle = $jkl['g10'];
								$engimg = false;
								$engicon = JAK_ENGAGE_ICON;
								$engmsg = $v['message'];
								$engconfirm = "";
								$engcancel = "";
								$engsound = JAK_ENGAGE_SOUND;

								// Prepare the proactive window
								$lcdrm = true;
								include_once APP_PATH.'lctemplate/'.$jakwidget['template'].'/tplblocks/btn/'.$jakwidget["btn_tpl"];

								// Load the style from the package config file
								$engagediv = $wtplsett["engagehtml"];

								// Create the new file
								$manualengage = $jakdb->select("buttonstats", ["id", "session", "message"], ["readtime" => 0]);
					            
						        if (isset($manualengage) && !empty($manualengage)) {

						        	// We empty the file
						        	file_put_contents($mproactivefile, "");

						        	$pafile = "<?php\n";
						                
						            $pafile .= "\$mproactiveserialize = '".base64_encode(gzcompress(serialize($manualengage)))."';\n\n\$LV_MPROACTIVE = unserialize(gzuncompress(base64_decode(\$mproactiveserialize)));\n\n";
						                
						            $pafile .= "?>";
						                
						            JAK_base::jakWriteinCache($mproactivefile, $pafile, '');
						        } else {

						        	// Remove the file
						        	unlink($mproactivefile);
						        }

								die(json_encode(array('status' => true, 'engage' => true, 'engagediv' => $engagediv, 'cposition' => $wtplsett["chatposition"], 'showalert' => JAK_PRO_ALERT, 'lastvisit' => $lastvisit)));

							}

						}
					}

				}

				if (isset($LC_PROACTIVE) && !empty($LC_PROACTIVE)) {

					foreach ($LC_PROACTIVE as $v) {

						// Now we get the last message and show it in the proactive window.
						$cudet = $jakdb->get("buttonstats", ["referrer", "hits", "time"], ["session" => $_POST['rlbid'], "LIMIT" => 1]);

						if (isset($cudet["hits"]) && isset($cudet["referrer"]) && isset($cudet["time"]) && ($cudet["hits"] >= $v["visitedsites"]) && ($v["timeonsite"] <= ($currentime - strtotime($cudet["time"]))) && ($v["path"] == $cudet["referrer"] || fnmatch($v["path"], $cudet["referrer"]))) {

							// Everything is given, now before we go any further, let's check if we have invited this user before
							if (file_exists($proactivefile)) { 

								// We need the pro active file
								include_once $proactivefile;

								if (isset($LV_PROACTIVE) && !empty($LV_PROACTIVE)) {

									foreach($LV_PROACTIVE as $m) {

										if ($m['session'] == $_POST['rlbid'] && $m['message'] == $v['message']) {

											// Not again, so we die here
											die(json_encode(array('status' => false, 'lastvisit' => $lastvisit)));
										}

									}

								}

								// We have no result, so we can remove it
								unlink($proactivefile);

							}

							// Set the engage message to shown
							$jakdb->update("buttonstats", ["message" => $v['message'], "readtime" => 1], ["session" => $_POST['rlbid']]);

							$imgurl = '';
							if (filter_var($v['imgpath'], FILTER_VALIDATE_URL) && is_array(getimagesize($v['imgpath']))) $imgurl = $v['imgpath'];

							// Get the proactive message in vars so they can be loaded into the template
							$engtitle = $v['title'];
							$engimg = $imgurl;
							$engicon = $v['imgpath'];
							$engmsg = $v['message'];
							$engconfirm = $v["btn_confirm"];
							$engcancel = $v["btn_cancel"];
							$engsound = $v['soundalert'];

							// Prepare the proactive window
							$lcdrm = true;
							include_once APP_PATH.'lctemplate/'.$jakwidget['template'].'/tplblocks/btn/'.$jakwidget["btn_tpl"];

							// Load the style from the package config file
							$engagediv = $wtplsett["engagehtml"];

							// Get the engage sessions for auto engage
					        $autoengage = $jakdb->select("buttonstats", ["id", "session", "message"], ["readtime[>]" => 0]);
					            
					        if (isset($autoengage) && !empty($autoengage)) {
					                
					            $pafile = "<?php\n";
					                
					            $pafile .= "\$proactiveserialize = '".base64_encode(gzcompress(serialize($autoengage)))."';\n\n\$LV_PROACTIVE = unserialize(gzuncompress(base64_decode(\$proactiveserialize)));\n\n";
					                
					            $pafile .= "?>";
					                
					            JAK_base::jakWriteinCache($proactivefile, $pafile, '');
					        }

							die(json_encode(array('status' => true, 'engage' => true, 'engagediv' => $engagediv, 'cposition' => $wtplsett["chatposition"], 'showalert' => $v['showalert'], 'lastvisit' => $lastvisit)));

						}

					}

				}

	        } // Finish check slide out window
	    }

	    // We have nothing to do, we will return false
	    die(json_encode(array('status' => false, 'lastvisit' => $lastvisit)));

	break;
	case 'quickstart':

		// Get the department
		$dep_direct = 0;
		if (isset($jakwidget['depid']) && is_numeric($jakwidget['depid']) && $jakwidget['depid'] != 0) {
			$dep_direct = $jakwidget['depid'];
		}

		// Operator ID if set.
		$op_direct = 0;
		if (isset($jakwidget['opid']) && is_numeric($jakwidget['opid']) && $jakwidget['opid'] != 0) $op_direct = $jakwidget['opid'];

		// Get the PHP GET for quick login
		$jkp = $_POST;
		
		// Errors in Array
		$errors = array();
		
		if (empty($jkp['quickstart_chat_msg']) || strlen(trim($jkp['quickstart_chat_msg'])) <= 1) {
		    $errors['quickstart_chat_msg'] = $jkl['e2'];
		}
				
		if (count($errors) > 0) {

			// We have an error, let's send it
			die(json_encode(array('status' => false, 'error' => $errors)));
			
		} else {
			
			// Country stuff
			$countryName = 'Disabled';
			$countryAbbrev = 'xx';
			$city = 'Disabled';
			$countryLong = $countryLat = '';
				
			// if ip is valid do the whole thing
			if ($ipa && !$ua->isRobot()) {

				// we will use the local storage for geo
				$removeloc = false;
				if (isset($_POST['geo']) && !empty($_POST['geo'])) {

					// Always escape any user input, including cookies:
					list($city, $countryName, $countryAbbrev, $countryLat, $countryLong, $storedtime) = explode('|', strip_tags(jak_string_encrypt_decrypt($_POST['geo'], false)));

					// We check if the geo data is not older then
					if (isset($storedtime) && !empty($storedtime) && strtotime('+3 day', $storedtime) > time() || !isset($country_code) || empty($country_code)) $removeloc = true;

				}

				if ($removeloc) {

					// Try to get the data from the button table
					$bts = $jakdb->get("buttonstats", ["country", "countrycode", "latitude", "longitude"], ["session" => $_POST["rlbid"]]);
					if (!empty($bts)) {
						$countryName = $bts["country"];
						$countryAbbrev = $bts["countrycode"];
						$city = 'Disabled';
						$countryLong = $bts["longitude"];
						$countryLat = $bts["latitude"];
					}
				}
					
			}

			// Get the user agent
			$valid_agent = filter_var($_SERVER['HTTP_USER_AGENT'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

			// Get the crossurl
			$crossurl = "";
			$crossurl = $jakdb->get("buttonstats", "crossurl", ["session" => $_POST['rlbid']]);

			// Clean message
			$message = strip_tags($jkp['quickstart_chat_msg']);
			$message = filter_var($message, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$message = trim($message);

			// Now let's check if we have a unique random id
			if ($jakdb->has("sessions", ["uniqueid" => $message])) {

				// Now we can reopen the chat
				$restorechat = $jakdb->get("sessions", "*", ["uniqueid" => $message]);

				// Get the vars
				$unique_restore = $restorechat["uniqueid"];
				$cid = $restorechat["id"];

				// Restore the tables
				$jakdb->update("sessions", ["status" => 1, "ended" => 0, "session" => $_POST['rlbid']], ["id" => $restorechat["id"]]);
				$jakdb->update("checkstatus", ["statusc" => $currentime, "newc" => 0, "hide" => 0], ["convid" => $restorechat["id"]]);

				// Now send notifications if whish so
				$result = $jakdb->select("user", ["id", "username", "email", "alwaysnot", "emailnot", "hours_array", "pusho_tok", "pusho_key", "phonenumber", "push_notifications"], ["AND" => ["OR" => ["available" => 0, "alwaysnot" => 1], "departments" => [0, $dep_direct], "access" => 1]]);
						
				if (isset($result) && !empty($result)) foreach ($result as $row) {

					if (JAK_base::jakAvailableHours($row["hours_array"], date('Y-m-d H:i:s')) || $row["alwaysnot"] == 1) {

						$url = JAK_rewrite::jakParseurl(JAK_OPERATOR_LOC, 'live', $cid);
						jak_send_notifications($row["id"], $cid, JAK_TITLE, JAK_TW_MSG, $url, $row["push_notifications"], $row["emailnot"], $row["email"], $row["pusho_tok"], $row["pusho_key"], $row["phonenumber"]);
					}
				}

				// Get the details encrypted
				$cudetails = jak_string_encrypt_decrypt($restorechat["id"].":#:".$restorechat["uniqueid"].":#:".$restorechat["userid"].":#:".$restorechat["name"].":#:".$restorechat["email"].":#:".$restorechat["phone"].":#:".$restorechat["usr_avatar"]);

			} else {
			
				// Get the avatar
				$avatar = "/lctemplate/".$jakwidget['template']."/avatar/business/1.jpg";

				// create the guest account
				$salt = rand(100, 1000000);
				$userid = $jkl['g51'].$ipa.$salt;
				$clientname = ($jkp['start_name'] ? $jkp['start_name'] : filter_var($jkl['g51'].'_'.$salt, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
				$clientemail = ($jkp['start_email'] ? $jkp['start_email'] : "");
				$uphone = "";
				if (isset($jkp['start_phone']) && !empty($jkp['start_phone'])) $uphone = $jkp['start_phone'];

				// Generate a random string to open the chat again
				$unique_chatid = random_bytes(5);
				$unique_restore = bin2hex($unique_chatid);

				// add entry to sql
				$jakdb->insert("sessions", ["widgetid" => $jakwidget["id"],
					"uniqueid" => $unique_restore,
					"userid" => $userid,
					"department" => $dep_direct,
					"operatorid" => $op_direct,
					"template" => $jakwidget['template'],
					"avatarset" => $jakwidget['avatarset'],
					"usr_avatar" => $avatar,
					"name" => $clientname,
					"email" => $clientemail,
					"city" => $city,
					"country" => $countryName,
					"countrycode" => $countryAbbrev,
					"longitude" => $countryLong,
					"latitude" => $countryLat,
					"lang" => $lang,
					"initiated" => time(),
					"status" => 1,
					"session" => $_POST['rlbid']]);

				$cid = $jakdb->id();
							
				if ($cid) {

					// Get the user details encrypted
					$cudetails = jak_string_encrypt_decrypt($cid.":#:".$unique_restore.":#:".$userid.":#:".$clientname.":#:".$clientemail.":#:".$uphone.":#:".$avatar);

					// Start with the checkstatus table
					foreach ($LC_DEPARTMENTS as $d) {
					    if ($dep_direct == $d["id"]) {
					        if ($d['title']) $deptitle = $d['title'];
					    }
					}

					// Write the log file each time a new chat starts
			    	JAK_base::jakWhatslog($userid, 0, 0, 7, $cid, (isset($_COOKIE['WIOgeoData']) ? $_COOKIE['WIOgeoData'] : ''), $clientname, $_SERVER['REQUEST_URI'], $ipa, $valid_agent);

					// Set the chat session status
					$jakdb->insert("checkstatus", ["convid" => $cid, "depid" => $dep_direct, "department" => $deptitle, "operatorid" => $op_direct, "files" => JAK_CHAT_UPLOAD_STANDARD, "initiated" => time()]);

					if (!empty($LC_ANSWERS) && is_array($LC_ANSWERS)) foreach ($LC_ANSWERS as $v) {
						
						if ($v["msgtype"] == 5 && $v["lang"] == $lang && ($dep_direct == $v["department"] || $v["department"] == 0)) {
							
							$phold = array("%operator%","%client%","%email%");
							$replace   = array("", $clientname, JAK_EMAIL);
							$messageaut = str_replace($phold, $replace, $v["message"]);

							$jakdb->insert("transcript", [ 
								"name" => $jkl["g56"],
								"message" => $messageaut,
								"convid" => $cid,
								"class" => "notice",
								"time" => $jakdb->raw("NOW()")]);
								
						}
								
					}

					// Your personal restore code
					$jakdb->insert("transcript", [ 
						"name" => $jkl["g56"],
						"message" => sprintf($jkl["g70"], $unique_restore),
						"convid" => $cid,
						"class" => "notice",
						"time" => $jakdb->raw("NOW()")]);

					// Convert emotji
					$answerdisp = Emojione\Emojione::toImage($message);

					$jakdb->insert("transcript", [ 
						"name" => $clientname,
						"user" => $clientemail,
						"message" => $message,
						"convid" => $cid,
						"class" => "user",
						"time" => $jakdb->raw("NOW()")]);

					// Now we pick the bot answer if one exists
					$botanswer = $answerdisp = '';
					if (!empty($JAK_BOT_ANSWER)) {

						// we set the message to lower case
						$message = strtolower($message);
						
						foreach ($JAK_BOT_ANSWER as $v) {

							if (($v['widgetids'] == 0 || in_array($_GET['id'], explode(",", $v['widgetids']))) && ($v["depid"] == 0 || $v["depid"] == $dep_direct) && $v["lang"] == $lang) {

								// we set the bot question to lower case
								$bot_question = strtolower($v["question"]);

								if (strpos($bot_question, ",") !== false ) {
									$bot_question = explode(",", $bot_question);

									// We do not have to type the exact word, it will pick the correct word in the string
									if (isset($bot_question) && is_array($bot_question)) foreach ($bot_question as $q) {

										// We have an exact word
										if ($message == $q) {
											$botanswer = strip_tags($v["answer"]);
											break;
										}

										// we filter out sentences
										if (strpos($message, $q) !== false) {
											$botanswer = strip_tags($v["answer"]);
											break;
										}
									}

								} else {

									// Check if we have a word only
									if ($message == $bot_question) {
										$botanswer = strip_tags($v["answer"]);
										break;
									}

									// we filter out sentences
									if (strpos($message, $bot_question) !== false) {
									    $botanswer = strip_tags($v["answer"]);
										break;
									}

								}

							}

						}

						// Fix for wildcard bot answer
						if (empty($botanswer)) foreach ($JAK_BOT_ANSWER as $v) {

							if (($v['widgetids'] == 0 || in_array($_GET['id'], explode(",", $v['widgetids']))) && ($v["depid"] == 0 || $v["depid"] == $dep_direct) && $v["lang"] == $lang) {

								$bot_question = strtolower($v["question"]);

								// Check if we have a wildcard
								if ($bot_question == "*") {

									$botanswer = strip_tags($v["answer"]);

								}

							}

						}

						// Proceed with displaying the bot answer
						if (!empty($botanswer)) {

							$botanswer = filter_var($botanswer, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

							// Place holder converters
							$phold = array("%client%","%email%");
							$replace   = array($clientname, JAK_EMAIL);
							$botanswer = str_replace($phold, $replace, $botanswer);

							// Url converter
							$answerdisp = nl2br(replace_urls($botanswer));

							// Convert emotji
							$answerdisp = Emojione\Emojione::toImage($answerdisp);

							$jakdb->insert("transcript", [ 
								"name" => $jkl["g74"],
								"message" => $botanswer,
								"convid" => $cid,
								"class" => "bot",
								"time" => $jakdb->raw("NOW()")]);

						}
					}

					// Now send notifications if whish so
					$result = $jakdb->select("user", ["id", "departments", "username", "email", "alwaysnot", "emailnot", "hours_array", "pusho_tok", "pusho_key", "phonenumber", "push_notifications"], ["AND" => ["OR" => ["available" => 0, "alwaysnot" => 1], "access" => 1]]);
					
					// Any client?
					if (isset($result) && !empty($result)) foreach ($result as $row) {

						// Let's check if we have the time or always notification on
						if (JAK_base::jakAvailableHours($row["hours_array"], date('Y-m-d H:i:s')) || $row["alwaysnot"] == 1) {

							// Now we check for the department
							if ($row["departments"] == 0 || in_array($dep_direct, explode(",", $row["departments"]))) {

								$url = JAK_rewrite::jakParseurl(JAK_OPERATOR_LOC, 'live', $cid);
								jak_send_notifications($row["id"], $cid, JAK_TITLE, JAK_TW_MSG, $url, $row["push_notifications"], $row["emailnot"], $row["email"], $row["pusho_tok"], $row["pusho_key"], $row["phonenumber"]);
							}
						}
					}
				}
			}
		}

		// Forward the client to the correct URL
		$chaturl = JAK_rewrite::jakParseurl('lc', $_POST["chatstatus"], $_GET["id"], $_GET["lang"], $cid, $unique_restore);

		// Ok, we can send the true status
		die(json_encode(array('status' => true, 'customer' => $cudetails, 'crossurl' => $crossurl, 'gotochat' => str_replace('include/', '', $chaturl))));

	break;

	case 'start':

		$jkp = $_POST;
		
		// Errors in Array
		$errors = array();

		if (empty($jkp['start_name']) || strlen(trim($jkp['start_name'])) <= 2) {
		    $errors['start_name'] = $jkl['e'];
		}

		if (((isset($widgetsettings['start_email_required']) && $widgetsettings['start_email_required'] == "Yes") || !empty($jkp['start_email'])) && !filter_var($jkp['start_email'], FILTER_VALIDATE_EMAIL)) {
			$errors['start_email'] = $jkl['e1'];
		}

		if (JAK_EMAIL_BLOCK && ((isset($widgetsettings['start_email_required']) && $widgetsettings['start_email_required'] == "Yes") || !empty($jkp['start_email']))) {
			$blockede = explode(',', JAK_EMAIL_BLOCK);
			if (in_array($jkp['start_email'], $blockede) || in_array(strrchr($jkp['email'], "@"), $blockede)) {
				$errors['start_email'] = $jkl['e10'];
			}
		}
			
		if (isset($widgetsettings['start_phone_required']) && $widgetsettings['start_phone_required'] == "Yes" && !filter_var($jkp['start_phone'], FILTER_SANITIZE_NUMBER_INT)) {
			$errors['start_phone'] = $jkl['e14'];
		}
		
		if (empty($jkp['start_chat_msg']) || strlen(trim($jkp['start_chat_msg'])) <= 1) {
		    $errors['start_chat_msg'] = $jkl['e2'];
		}

		// Now let's check if we have some custom fields
		if (isset($jkp['mycustomfields']) && !empty($jkp['mycustomfields'])) {

			// Ok we have some customfields
			$customI = explode(",", $jkp['mycustomfields']);

			// Now let us go through the fields
			foreach ($customI as $v) {

				// Get the correct value and check if we they are mandatory or not the value should be 
				$fvstore = explode(":#:", $v);

				// Ok field by field
				if (isset($jkp[$fvstore[0]]) && isset($fvstore[1]) && $fvstore[1] == 1 && empty($jkp[$fvstore[0]])) {
					$errors[$fvstore[0]] = $jkl['f'];
				}
			}
		}
		
		// Count the errors if erros show them of not proceed	
		if (count($errors) > 0) {

			// We have an error, let's send it
			die(json_encode(array('status' => false, 'error' => $errors)));
			
		} else {
			
			// Country stuff
			$countryName = 'Disabled';
			$countryAbbrev = 'xx';
			$city = 'Disabled';
			$countryLong = $countryLat = '';
				
			// if ip is valid do the whole thing
			if ($ipa && !$ua->isRobot()) {

				// we will use the local storage for geo
				$removeloc = false;
				if (isset($_POST['geo']) && !empty($_POST['geo'])) {

					// Always escape any user input, including cookies:
					list($city, $countryName, $countryAbbrev, $countryLat, $countryLong, $storedtime) = explode('|', strip_tags(jak_string_encrypt_decrypt($_POST['geo'], false)));

					// We check if the geo data is not older then
					if (isset($storedtime) && !empty($storedtime) && strtotime('+3 day', $storedtime) > time() || !isset($country_code) || empty($country_code)) $removeloc = true;

				}

				if ($removeloc) {

					// Try to get the data from the button table
					$bts = $jakdb->get("buttonstats", ["country", "countrycode", "latitude", "longitude"], ["session" => $_POST["rlbid"]]);
					if (!empty($bts)) {
						$countryName = $bts["country"];
						$countryAbbrev = $bts["countrycode"];
						$city = 'Disabled';
						$countryLong = $bts["longitude"];
						$countryLat = $bts["latitude"];
					}
				}
					
			}

			// Get the user agent
			$valid_agent = filter_var($_SERVER['HTTP_USER_AGENT'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

			// Get the crossurl
			$crossurl = "";
			$crossurl = $jakdb->get("buttonstats", "crossurl", ["session" => $_POST['rlbid']]);

			// Clean message
			$message = strip_tags($jkp['start_chat_msg']);
			$message = filter_var($message, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
			$message = trim($message);

			// We collect the department and operator id
			$dep_direct = $op_direct = 0;
			if (isset($jkp['start_department']) && is_numeric($jkp['start_department'])) $dep_direct = $jkp['start_department'];
			if (isset($jakwidget['opid']) && is_numeric($jakwidget['opid']) && $jakwidget['opid'] != 0) $op_direct = $jakwidget['opid'];
			if (isset($jkp['start_op_direct']) && is_numeric($jkp['start_op_direct'])) $op_direct = $jkp['start_op_direct'];

			// We do have a direct operator selection
			if (isset($jkp['start_opid_select']) && is_numeric($jkp['start_opid_select'])) $op_direct = $jkp['start_opid_select'];

			// Now let's check if we have a unique random id
			if ($jakdb->has("sessions", ["uniqueid" => $message])) {

				// Now we can reopen the chat
				$restorechat = $jakdb->get("sessions", "*", ["uniqueid" => $message]);

				// Get the vars
				$unique_restore = $restorechat["uniqueid"];
				$cid = $restorechat["id"];

				// Restore the tables
				$jakdb->update("sessions", ["status" => 1, "ended" => 0, "session" => $_POST['rlbid']], ["id" => $restorechat["id"]]);
				$jakdb->update("checkstatus", ["statusc" => $currentime, "newc" => 0, "hide" => 0], ["convid" => $restorechat["id"]]);

				// Now send notifications if whish so
				$result = $jakdb->select("user", ["id", "username", "email", "alwaysnot", "emailnot", "hours_array", "pusho_tok", "pusho_key", "phonenumber", "push_notifications"], ["AND" => ["OR" => ["available" => 0, "alwaysnot" => 1], "departments" => [0, $dep_direct], "access" => 1]]);
						
				if (isset($result) && !empty($result)) foreach ($result as $row) {

					if (JAK_base::jakAvailableHours($row["hours_array"], date('Y-m-d H:i:s')) || $row["alwaysnot"] == 1) {

						$url = JAK_rewrite::jakParseurl(JAK_OPERATOR_LOC, 'live', $cid);
						jak_send_notifications($row["id"], $cid, JAK_TITLE, JAK_TW_MSG, $url, $row["push_notifications"], $row["emailnot"], $row["email"], $row["pusho_tok"], $row["pusho_key"], $row["phonenumber"]);
					}
				}

				// Get the details encrypted
				$cudetails = jak_string_encrypt_decrypt($restorechat["id"].":#:".$restorechat["uniqueid"].":#:".$restorechat["userid"].":#:".$restorechat["name"].":#:".$restorechat["email"].":#:".$restorechat["phone"].":#:".$restorechat["usr_avatar"]);

			} else {
			
				// Get the avatar
				$avatar = "/lctemplate/".$jakwidget['template']."/avatar/business/1.jpg";
				if (isset($jkp['avatar']) && !empty($jkp['avatar'])) $avatar = $jkp['avatar'];
				
				// create the guest account
				$salt = rand(100, 1000000);
				$userid = $jkl['g51'].$ipa.$salt;
				$clientname = ($jkp['start_name'] ? $jkp['start_name'] : filter_var($jkl['g51'].'_'.$salt, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
				$clientemail = ($jkp['start_email'] ? $jkp['start_email'] : "");
				$uphone = "";
				if (isset($jkp['start_phone']) && !empty($jkp['start_phone'])) $uphone = $jkp['start_phone'];

				// Generate a random string to open the chat again
				$unique_chatid = random_bytes(5);
				$unique_restore = bin2hex($unique_chatid);

				// add entry to sql
				$jakdb->insert("sessions", ["widgetid" => $jakwidget["id"],
					"uniqueid" => $unique_restore,
					"userid" => $userid,
					"department" => $dep_direct,
					"operatorid" => $op_direct,
					"template" => $jakwidget['template'],
					"avatarset" => $jakwidget['avatarset'],
					"usr_avatar" => $avatar,
					"name" => $clientname,
					"email" => $clientemail,
					"phone" => $uphone,
					"city" => $city,
					"country" => $countryName,
					"countrycode" => $countryAbbrev,
					"longitude" => $countryLong,
					"latitude" => $countryLat,
					"lang" => $lang,
					"initiated" => time(),
					"status" => 1,
					"session" => $_POST['rlbid']]);

				$cid = $jakdb->id();
							
				if ($cid) {

					// Get the user details encrypted
					$cudetails = jak_string_encrypt_decrypt($cid.":#:".$unique_restore.":#:".$userid.":#:".$clientname.":#:".$clientemail.":#:".$uphone.":#:".$avatar);

					// Start with the checkstatus table
					foreach ($LC_DEPARTMENTS as $d) {
					    if ($dep_direct == $d["id"]) {
					        if ($d['title']) $deptitle = $d['title'];
					    }
					}

					// Write the log file each time someone tries to login before
			    	JAK_base::jakWhatslog($userid, 0, 0, 7, $cid, (isset($_POST['geo']) ? $_POST['geo'] : ''), $clientname, $_SERVER['REQUEST_URI'], $ipa, $valid_agent);

					// Set the chat session status
					$jakdb->insert("checkstatus", ["convid" => $cid, "depid" => $dep_direct, "department" => $deptitle, "operatorid" => $op_direct, "files" => JAK_CHAT_UPLOAD_STANDARD, "initiated" => time()]);

					// Enter the predefined answer
					if (!empty($LC_ANSWERS) && is_array($LC_ANSWERS)) foreach ($LC_ANSWERS as $v) {
						
						if ($v["msgtype"] == 5 && $v["lang"] == $lang && ($dep_direct == $v["department"] || $v["department"] == 0)) {
							
							$phold = array("%operator%","%client%","%email%");
							$replace   = array("", $clientname, JAK_EMAIL);
							$messageaut = str_replace($phold, $replace, $v["message"]);

							$jakdb->insert("transcript", [ 
								"name" => $jkl["g56"],
								"message" => $messageaut,
								"convid" => $cid,
								"class" => "notice",
								"time" => $jakdb->raw("NOW()")]);
								
						}
								
					}

					// Your personal restore code
					$jakdb->insert("transcript", [ 
						"name" => $jkl["g56"],
						"message" => sprintf($jkl["g70"], $unique_restore),
						"convid" => $cid,
						"class" => "notice",
						"time" => $jakdb->raw("NOW()")]);

					// The customer message and convert emotji
					$answerdisp = Emojione\Emojione::toImage($message);

					$jakdb->insert("transcript", [ 
						"name" => $clientname,
						"user" => $userid,
						"message" => $message,
						"convid" => $cid,
						"class" => "user",
						"time" => $jakdb->raw("NOW()")]);

					// Now we pick the bot answer if one exists
					$botanswer = $answerdisp = '';
					if (!empty($JAK_BOT_ANSWER)) {

						// we set the message to lower case
						$message = strtolower($message);
						
						foreach ($JAK_BOT_ANSWER as $v) {

							if (($v['widgetids'] == 0 || in_array($_GET['id'], explode(",", $v['widgetids']))) && ($v["depid"] == 0 || $v["depid"] == $dep_direct) && $v["lang"] == $lang) {

								// we set the bot question to lower case
								$bot_question = strtolower($v["question"]);

								if (strpos($bot_question, ",") !== false ) {
									$bot_question = explode(",", $bot_question);

									// We do not have to type the exact word, it will pick the correct word in the string
									if (isset($bot_question) && is_array($bot_question)) foreach ($bot_question as $q) {

										// We have an exact word
										if ($message == $q) {
											$botanswer = strip_tags($v["answer"]);
											break;
										}

										// we filter out sentences
										if (strpos($message, $q) !== false) {
											$botanswer = strip_tags($v["answer"]);
											break;
										}
									}

								} else {

									// Check if we have a word only
									if ($message == $bot_question) {
										$botanswer = strip_tags($v["answer"]);
										break;
									}

									// we filter out sentences
									if (strpos($message, $bot_question) !== false) {
									    $botanswer = strip_tags($v["answer"]);
										break;
									}

								}

							}

						}

						// Fix for wildcard bot answer
						if (empty($botanswer)) foreach ($JAK_BOT_ANSWER as $v) {

							if (($v['widgetids'] == 0 || in_array($_GET['id'], explode(",", $v['widgetids']))) && ($v["depid"] == 0 || $v["depid"] == $dep_direct) && $v["lang"] == $lang) {

								// we set the bot question to lower case
								$bot_question = strtolower($v["question"]);

								// Check if we have a wildcard
								if ($bot_question == "*") {
									$botanswer = strip_tags($v["answer"]);
									break;

								}

							}

						}

						// Proceed with displaying the bot answer
						if (!empty($botanswer)) {

							$botanswer = filter_var($botanswer, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

							// Place holder converters
							$phold = array("%client%","%email%");
							$replace   = array($clientname, JAK_EMAIL);
							$botanswer = str_replace($phold, $replace, $botanswer);

							// Url converter
							$answerdisp = nl2br(replace_urls($botanswer));

							// Convert emotji
							$answerdisp = Emojione\Emojione::toImage($answerdisp);

							$jakdb->insert("transcript", [ 
								"name" => $jkl["g74"],
								"message" => $botanswer,
								"convid" => $cid,
								"class" => "bot",
								"time" => $jakdb->raw("NOW()")]);

						}
					}

					// Now let's check if we have some custom fields
					if (isset($jkp['mycustomfields']) && !empty($jkp['mycustomfields'])) {

						// Ok we have some customfields
						$customI = explode(",", $jkp['mycustomfields']);

						// Now let us go through the fields
						foreach ($customI as $v) {

							// Get the correct value and check if we they are mandatory or not the value should be 
							$fvstore = explode(":#:", $v);

							if (isset($jkp[$fvstore[0]]) && !empty($jkp[$fvstore[0]])) {
								$jakdb->insert("chatcustomfields", ["convid" => $cid, "name" => $clientname, "settname" => $fvstore[0], "settvalue" => jak_input_filter($jkp[$fvstore[0]]), "updated" => $jakdb->raw("NOW()"), "created" => $jakdb->raw("NOW()")]);
							}
						}
					}

					// Now send notifications if whish so
					$result = $jakdb->select("user", ["id", "departments", "username", "email", "alwaysnot", "emailnot", "hours_array", "pusho_tok", "pusho_key", "phonenumber", "push_notifications"], ["AND" => ["OR" => ["available" => 0, "alwaysnot" => 1], "access" => 1]]);
					
					// Any client?
					if (isset($result) && !empty($result)) foreach ($result as $row) {

						// Let's check if we have the time or always notification on
						if (JAK_base::jakAvailableHours($row["hours_array"], date('Y-m-d H:i:s')) || $row["alwaysnot"] == 1) {

							// Now we check for the department
							if ($row["departments"] == 0 || in_array($dep_direct, explode(",", $row["departments"]))) {

								$url = JAK_rewrite::jakParseurl(JAK_OPERATOR_LOC, 'live', $cid);
								jak_send_notifications($row["id"], $cid, JAK_TITLE, JAK_TW_MSG, $url, $row["push_notifications"], $row["emailnot"], $row["email"], $row["pusho_tok"], $row["pusho_key"], $row["phonenumber"]);
							}
						}
					}
				}
			}
		}

		// Forward the client to the correct URL
		$chaturl = JAK_rewrite::jakParseurl('lc', $_POST["chatstatus"], $_GET["id"], $_GET["lang"], $cid, $unique_restore);

		// Ok, we can send the true status
		die(json_encode(array('status' => true, 'customer' => $cudetails, 'crossurl' => $crossurl, 'gotochat' => str_replace('include/', '', $chaturl))));

	break;

	case 'sendmsg':

		if (isset($_POST['customer']) && !empty($_POST['customer'])) {

			// Get the absolute url for the image
			$ava_url = str_replace('include/', '', BASE_URL);

			// Let's make sure we have an active chat and it is available
			$cudetails = jak_string_encrypt_decrypt($_POST['customer'], false);

			// Let's explode the string (0 = convid, 1 = uniqueid, 2 = userid, 3 = name, 4 = email, 5 = phone, 6 = avatar)
			$cudetails = explode(":#:", $cudetails);

			// Get the absolute url for the image
			$ava_url = str_replace('include/', '', BASE_URL);

			if (isset($cudetails[0]) && is_numeric($cudetails[0]) && $jakdb->has("sessions", ["session" => $_POST['rlbid']])) {

				$row = $jakdb->get("checkstatus", ["convid", "depid", "operatorid", "operator", "pusho", "hide", "statuso", "initiated"], ["convid" => $cudetails[0]]);

				if (isset($row) && !empty($row)) {
					
					$message = html_entity_decode($_POST['msg']);
					$message = strip_tags($message);
					$message = filter_var($message, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$message = trim($message);
					
					if (isset($message) && !empty($message) && $row['hide'] <= 1) {

						// We reset the chat so we can send messages again
						if ($row["hide"] == 1) {
							// Update the database
							$jakdb->update("sessions", ["status" => 1, "fcontact" => 0, "ended" => 0], ["id" => $row['convid']]);
							$jakdb->update("checkstatus", ["hide" => 0], ["convid" => $row['convid']]);
						}

						// Remove the file from the cache directory
						$livepreviewfile = APP_PATH.JAK_CACHE_DIRECTORY.'/livepreview'.$row['convid'].'.txt';

						if (file_exists($livepreviewfile)) {
							// Finally remove the file and start fresh
							unlink($livepreviewfile);
						}

						// Convert urls
						$messagedisp = nl2br(replace_urls($message));

						// Convert emotji
						$messagedisp = Emojione\Emojione::toImage($messagedisp);

						// Check for duplicate messages
						if (isset($_POST["lastmsg"]) && $_POST["lastmsg"] == $message) {

							die(json_encode(array('status' => false, 'error' => stripcslashes($jkl['g75']))));

						}

						$jakdb->insert("transcript", [ 
							"name" => $cudetails[3],
							"message" => $message,
							"user" => $cudetails[2],
							"convid" => $row['convid'],
							"sentstatus" => 1,
							"class" => "user",
							"time" => $jakdb->raw("NOW()")]);

						$lastid = $jakdb->id();

						$jakdb->update("checkstatus", ["newo" => 1, "typec" => 0], ["convid" => $row['convid']]);

						// New Bot, if no one is chatting, bot message available, department match and message the same as question
						$answer = $botdisp = "";
						if (empty($row["operator"]) && !empty($JAK_BOT_ANSWER)) {

							// we set the message to lower case
							$message = strtolower($message);
							
							foreach ($JAK_BOT_ANSWER as $v) {

								if (($v['widgetids'] == 0 || in_array($_GET['id'], explode(",", $v['widgetids']))) && ($v["depid"] == 0 || $v["depid"] == $row["depid"]) && $v["lang"] == $lang) {

									// we set the message to lower case
									$question = strtolower($v["question"]);

									if (strpos($question, ",") !== false ) {
										$question = explode(",", $question);

										// We do not have to type the exact word, it will pick the correct word in the string
										if (isset($question) && is_array($question)) foreach ($question as $q) {

											if ($message == $q) {
												$answer = strip_tags($v["answer"]);
												break;
											}

											// we filter out sentences
											if (strpos($message, $q) !== false) {
											    $answer = strip_tags($v["answer"]);
												break;
											}
										}

									} else {

										// Check if we have a word only
										if ($message == $question) {
											$answer = strip_tags($v["answer"]);
											break;
										}

										// we filter out sentences
										if (strpos($message, $question) !== false) {
										    $answer = strip_tags($v["answer"]);
											break;
										}

									}

								}

							}

							// Fix for wildcard bot answer
							if (empty($answer)) foreach ($JAK_BOT_ANSWER as $v) {

								if (($v['widgetids'] == 0 || in_array($_GET['id'], explode(",", $v['widgetids']))) && ($v["depid"] == 0 || $v["depid"] == $row["depid"]) && $v["lang"] == $lang) {

									$question = $v["question"];

									// Check if we have a wildcard
									if (empty($answer) && $question == "*") {

										$answer = strip_tags($v["answer"]);

									}

								}

							}

							// Proceed with displaying the bot answer
							if (!empty($answer)) {

								$answer = filter_var($answer, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

								// Place holder converters
								$phold = array("%client%","%email%");
								$replace   = array($cudetails[3], JAK_EMAIL);
								$answer = str_replace($phold, $replace, $answer);

								$jakdb->insert("transcript", [ 
									"name" => $jkl["g74"],
									"message" => $answer,
									"convid" => $row['convid'],
									"class" => "bot",
									"time" => $jakdb->raw("NOW()")]);

								// Get he last id
								$lastidbot = $jakdb->id();

								// Url converter
								$botdisp = nl2br(replace_urls($answer));

								// Convert emotji
								$botdisp = Emojione\Emojione::toImage($botdisp);

							}

						}

						// We will need design
						$lcdnm = true;
						include_once APP_PATH.'lctemplate/'.$jakwidget['template'].'/tplblocks/chat/'.$jakwidget["chat_tpl"];

						// Now load the message
						$chatmsg = $wtplsett["chatinsert"];

						// Now attach the bot message
						if (!empty($botdisp)) $chatmsg .= $wtplsett["chatbotinsert"];

						// Finally let's inform the operator if set and time is older then the set minutes
						if ($row['pusho'] && (time() - $row['statuso']) > JAK_PUSH_REMINDER) {

							$jakdb->update("checkstatus", ["statuso" => time()], ["convid" => $row['convid']]);
							$pushorow = $jakdb->get("user", ["pusho_tok", "pusho_key", "push_notifications"], ["id" => $row['operatorid']]);

							// Let's send some notifications
							if ($pushorow["push_notifications"]) {

								$url = JAK_rewrite::jakParseurl(JAK_OPERATOR_LOC, 'live', $row['convid']);

								jak_send_notifications($row["operatorid"], $row['convid'], JAK_TITLE.' '.$jkl['g22'], $messagedisp, $url, $pushorow["push_notifications"], 0, "", $pushorow["pusho_tok"], $pushorow["pusho_key"], "");
							}
						}

						die(json_encode(array('status' => true, "html" => $chatmsg, 'lastid' => $lastid, 'lastmsg' => $message, "placeholder" => $jkl["g60"])));
					
					// Chat is hidden, no more messages and end the session
					} elseif ($row['hide'] == 2) {
					
						$message = '';
					
						if (!empty($LC_ANSWERS) && is_array($LC_ANSWERS)) foreach ($LC_ANSWERS as $v) {
							
							if ($v["msgtype"] == 4 && $v["lang"] == $lang) {
							
								$phold = array("%operator%","%client%","%email%");
								$replace   = array($row['operator'], $cudetails[3], JAK_EMAIL);
								$message = str_replace($phold, $replace, $v["message"]);

								$jakdb->insert("transcript", [ 
									"name" => $jkl["g56"],
									"message" => $message,
									"convid" => $row['convid'],
									"class" => "notice",
									"time" => $jakdb->raw("NOW()")]);

								$lastid = $jakdb->id();
								
							}
								
						}

						// $jakdb->update("sessions", ["ended" => 0, "status" => 1], ["id" => $row['convid']]);
						// $jakdb->update("checkstatus", ["newo" => 1, "typec" => 0, "hide" => 0], ["convid" => $row['convid']]);

						// We will need design
						$lcdnm = true;
						include_once APP_PATH.'lctemplate/'.$jakwidget['template'].'/tplblocks/chat/'.$jakwidget["chat_tpl"];
						
						// Load the style from the package config file
						$chatmsg = $wtplsett["chatinsertended"];

						die(json_encode(array('status' => true, "html" => $chatmsg, 'lastid' => $lastid, "placeholder" => $jkl["g60"])));
						
					} else {
					
						die(json_encode(array('status' => false, 'error' => stripcslashes($jkl['e2']))));
					}
					
					
				}
			}
		}

		die(json_encode(array('status' => false, 'error' => stripcslashes($jkl['e2']))));

	break;

	case 'getmsg':

		if (isset($_POST['customer']) && !empty($_POST['customer'])) {

			// Get the absolute url for the image
			$ava_url = str_replace('include/', '', BASE_URL);

			// Let's make sure we have an active chat and it is available
			$cudetails = jak_string_encrypt_decrypt($_POST['customer'], false);

			// Let's explode the string (0 = convid, 1 = uniqueid, 2 = userid, 3 = name, 4 = email, 5 = phone, 6 = avatar)
			$cudetails = explode(":#:", $cudetails);

			if (isset($cudetails[0]) && is_numeric($cudetails[0]) && $jakdb->has("sessions", ["session" => $_POST['rlbid']])) {

				// Reset vars
				$chat = $botanswer = '';
				$lastid = 0;
				$loadsingle = false;

				if (isset($_POST['lastid']) && is_numeric($_POST['lastid']) && $_POST['lastid'] != 0) {
					$lastid = $_POST['lastid'];
					$loadsingle = true;
				}

				$result = $jakdb->select("transcript", ["[>]user" => ["operatorid" => "id"]], ["transcript.id", "transcript.name", "transcript.message", "transcript.operatorid", "transcript.editoid", "transcript.edited", "transcript.quoted", "transcript.time", "transcript.class", "user.picture"], ["OR #Extra clause" => [
					"AND #normal" => [
						"transcript.convid" => $cudetails[0], 
						"transcript.id[>]" => $lastid,
						"transcript.plevel" => 1
					],
					"AND #not read" => [
						"transcript.convid" => $cudetails[0],
						"transcript.sentstatus" => 0,
						"transcript.plevel" => 1
					]
				], "ORDER" => ["transcript.id" => "ASC"]]);

				if (isset($result) && !empty($result)) {

					// Let's load the template array
					$lcdrm = true;

					foreach ($result as $row) {

						// On which class to show a system image
						$systemimg = array("bot", "notice", "url", "ended");

						// Get the current class
						$chatclass = $row["class"];
						
						$avaimg = $ava_url.$cudetails[6];
						if ($row["picture"] && $row["operatorid"]) $avaimg = $ava_url.JAK_FILES_DIRECTORY.$row["picture"];
						if (in_array($chatclass, $systemimg)) $avaimg = $ava_url.'lctemplate/'.$jakwidget['template'].'/avatar/'.$jakwidget['avatarset'].'/system.jpg';

						// We convert the br
						$message = nl2br($row['message'], false);

						// we have file
						if ($chatclass == "download" && file_exists(APP_PATH.JAK_FILES_DIRECTORY.$message)) {

							if ($row['operatorid'] == 0) $chatclass = "user";

							// We have an image
					    	if (getimagesize(APP_PATH.JAK_FILES_DIRECTORY.$message)) {
					    		$message = '<a href="'.$ava_url.JAK_FILES_DIRECTORY.$message.'" target="_blank"><img src="'.$ava_url.JAK_FILES_DIRECTORY.$message.'" class="lc_image_dl" alt="chat-img"></a>';
					    	} else {
					    		$message = '<a href="'.$ava_url.JAK_FILES_DIRECTORY.$message.'" target="_blank">'.basename($message).'</a>';
					    	}
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

						// We will need design
						include APP_PATH.'lctemplate/'.$jakwidget['template'].'/tplblocks/chat/'.$jakwidget["chat_tpl"];

						// Now we need to figure for who the message is
						if (isset($chatclass) && $chatclass == "user") {
							$chat .= $wtplsett["clientmsg"];
						} elseif (isset($chatclass) && $chatclass == "admin") {
							$chat .= $wtplsett["operatormsg"];
						} elseif (isset($chatclass) && $chatclass == "notice") {
							$chat .= $wtplsett["infomsg"];
						} elseif (isset($chatclass) && $chatclass == "download") {
							$chat .= $wtplsett["download"];
						} elseif (isset($chatclass) && $chatclass == "bot") {
							$chat .= $wtplsett["chatbot"];
						}
						
						// Set the session for the redirect
						$redirecturl = "";
						if ($row["class"] == "url") {
							// Update the url to visited
			            	$jakdb->update("transcript", ["class" => "urlvisited", "plevel" => 2], ["id" => $row["id"]]);
							$redirecturl = $row['message'];
						}

						// finally update the entry to read
						$jakdb->update("transcript", ["sentstatus" => 1], ["id" => $row["id"]]);

						// Get the last id
						$lastid = $row["id"];

					}
					
					die(json_encode(array("status" => 1, "html" => $chat, "lastid" => $lastid, "placeholder" => $jkl["g60"], "redirecturl" => $redirecturl)));
				}
			}

		}

		die(json_encode(array("status" => false)));

	break;

	case 'typing':

		if (isset($_POST['customer']) && !empty($_POST['customer'])) {

			// Let's make sure we have an active chat and it is available
			$cudetails = jak_string_encrypt_decrypt($_POST['customer'], false);

			// Let's explode the string (0 = convid, 1 = uniqueid, 2 = userid, 3 = name, 4 = email, 5 = phone, 6 = avatar)
			$cudetails = explode(":#:", $cudetails);

			if (isset($cudetails[0]) && is_numeric($cudetails[0])) {

				if (isset($_POST['typestatus']) && $_POST['typestatus'] == 1) {
					$result = $jakdb->update("checkstatus", ["typec" => 1], ["convid" => $cudetails[0]]);
				} else {
					$result = $jakdb->update("checkstatus", ["typec" => 0], ["convid" => $cudetails[0]]);
				}

				if ($result) {
					die(json_encode(array('status' => true, 'tid' => 1)));
				}

			} else {
				die(json_encode(array('status' => true, 'tid' => 0)));
			}
		}

	break;

	case 'livetyping':

		if (isset($_POST['customer']) && !empty($_POST['customer'])) {

			// Let's make sure we have an active chat and it is available
			$cudetails = jak_string_encrypt_decrypt($_POST['customer'], false);

			// Let's explode the string (0 = convid, 1 = uniqueid, 2 = userid, 3 = name, 4 = email, 5 = phone, 6 = avatar)
			$cudetails = explode(":#:", $cudetails);

			if (isset($cudetails[0]) && is_numeric($cudetails[0])) {

				// Insert the preview message into the text file
				$livepreviewfile = APP_PATH.JAK_CACHE_DIRECTORY.'/livepreview'.$cudetails[0].'.txt';

				if (isset($_POST['msg']) && empty($_POST['msg'])) {

					if (file_exists($livepreviewfile)) {
						// Finally remove the file and start fresh
						unlink($livepreviewfile);
					}

					die(json_encode(array("status" => false)));

				} else {

					// Filter the message
					$message = html_entity_decode($_POST['msg']);
					$message = strip_tags($message);
					$message = filter_var($message, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					$message = trim($message);

					// Let's inform others that a new client has entered the chat
					file_put_contents($livepreviewfile, $message);

					die(json_encode(array("status" => true)));

				}
			}
		}

		die(json_encode(array("status" => false)));

	break;

	case 'chatupdate':

		if (isset($_POST['customer']) && !empty($_POST['customer'])) {

			// Reset some vars
			$editedmsg = $showedit = $avaimg = $operabout = '';
			$otyping = $knockknock = $inchat = $kk = false;
			$opern = $jkl['g59'];

			// Filter get vars
			$getlang = jak_url_input_filter($_POST["lang"]);

			// Let's make sure we have an active chat and it is available
			$cudetails = jak_string_encrypt_decrypt($_POST['customer'], false);

			// Let's explode the string (0 = convid, 1 = uniqueid, 2 = userid, 3 = name, 4 = email, 5 = phone, 6 = avatar)
			$cudetails = explode(":#:", $cudetails);

			// it looks like the client is not currently active
			if (isset($_POST["chatstatus"]) && $_POST["chatstatus"] == "closed") {

				// In this case, let's make sure we don't kill the session but everything else is on hold
				$jakdb->update("checkstatus", ["statusc" => $currentime, "newc" => 0], ["convid" => $cudetails[0]]);

				die(json_encode(array('status' => true)));

			} else {

				$row = $jakdb->get("checkstatus", ["convid", "depid", "operatorid", "operator", "newc", "files", "knockknock", "msgdel", "msgedit", "typeo", "denied", "hide", "datac", "initiated"], ["convid" => $cudetails[0]]);

				if (isset($row) && !empty($row)) {
						
					// Get the knock knock
					if ($row['knockknock'] == 1) $kk = $jkl["g22"];
						
					// Update the status for better user handling
					$jakdb->update("checkstatus", ["statusc" => $currentime, "knockknock" => 0], ["convid" => $row['convid']]);
						
					if ($row['denied'] == 1) {
							
						$jakdb->insert("transcript", [ 
							"name" => $cudetails[3],
							"message" => $jkl['g57'],
							"convid" => $row['convid'],
							"class" => "ended",
							"time" => $jakdb->raw("NOW()")]);

						// Forward the client to the correct URL
						$contacturl = JAK_rewrite::jakParseurl('lc', 'contactform', $_GET["id"], $_GET["lang"]);

						// The client goes to the contact form
						die(json_encode(array('status' => true, 'redirect_c' => true, 'action' => 'contactform', 'contacturl' => str_replace('include/', '', $contacturl))));
							
					}
					
					// Chat has been expired
					$softended = 0;
					if ($row['hide'] == 1) {
						
						if (!empty($LC_ANSWERS) && is_array($LC_ANSWERS)) foreach ($LC_ANSWERS as $v) {
								
							if ($v["msgtype"] == 15 && $v["lang"] == $lang) {
								
								$phold = array("%operator%","%client%","%email%");
								$replace   = array($row['operator'], $cudetails[3], JAK_EMAIL);
								$message = str_replace($phold, $replace, $v["message"]);
									
								// Insert the ended message
								$jakdb->insert("transcript", [ 
									"name" => $cudetails[3],
									"message" => $message,
									"convid" => $row['convid'],
									"class" => "notice",
									"time" => $jakdb->raw("NOW()")]);

								// Inform the client about the expired chat
								$row['newc'] = 1;
								$softended = 1;
									
							}
									
						}

					}
					$answid = 0;
					if (empty($row['operator'])) {
						
						if (isset($_POST['answid'])) {
							$answid = json_decode(stripslashes($_POST['answid']));
						} else {
							$answid = array();
						}
						
						if (!empty($LC_ANSWERS) && is_array($LC_ANSWERS)) foreach ($LC_ANSWERS as $v) {
								
							if ($v["msgtype"] == 1 && $v["lang"] == $lang && ($v["department"] == 0 || $v["department"] == $row["depid"]) && (isset($_POST['answid']) && !in_array($v["id"], $_POST['answid'])) && $row['initiated'] < ($currentime - $v["fireup"])) {
								
								$phold = array("%operator%","%client%","%email%");
								$replace   = array("", $cudetails[3], JAK_EMAIL);
								$message = str_replace($phold, $replace, $v["message"]);
										
								// Insert the ended message
								$jakdb->insert("transcript", [ 
									"name" => $jkl["g56"],
									"message" => $message,
									"convid" => $row['convid'],
									"class" => "notice",
									"time" => $jakdb->raw("NOW()")]);
									
								// Set the session to the id we have insert so we don't get it twice
								$answid[] = $v["id"];
									
								$row['newc'] = 1;
									
							}	
						}
					}
						
					if ($jakwidget['redirect_active'] && (empty($row['operator']) && $row['initiated'] < ($currentime - ($jakwidget['redirect_after'] * 60)))) {
						
						$jakdb->update("sessions", ["status" => 0, "fcontact" => 1, "ended" => $currentime], ["id" => $row['convid']]);
						$jakdb->update("checkstatus", ["hide" => 1], ["convid" => $row['convid']]);
							
						// Insert the ended message
						$jakdb->insert("transcript", [ 
							"name" => $cudetails[3],
							"message" => $jkl['g57'],
							"convid" => $row['convid'],
							"class" => "ended",
							"time" => $jakdb->raw("NOW()")]);
							
						if (filter_var($jakwidget['redirect_url'], FILTER_VALIDATE_URL)) {

							die(json_encode(array('status' => true, 'redirect_cu' => $jakwidget['redirect_url'])));
						} else {

							// Forward the client to the correct URL
							$contacturl = JAK_rewrite::jakParseurl('lc', 'contactform', $_GET["id"], $_GET["lang"]);

							// The client goes to the contact form
							die(json_encode(array('status' => true, 'redirect_c' => true, 'action' => 'contactform', 'contacturl' => str_replace('include/', '', $contacturl))));
						}
							
					}

					// We have an operator
					if (isset($row['operator']) && !empty($row['operator'])) {
						$inchat = true;
						$opern = $row['operator'];
						if ((isset($_POST['opname']) && !empty($_POST['opname']) && $_POST['opname'] != $opern) || !isset($_POST['opname'])) {
							// Get the operator details but only once
							$opdetails = $jakdb->get("user", ["aboutme", "picture"], ["id" => $row['operatorid']]);
							$operabout = $opdetails['aboutme'];
							$avaimg = $base_url.JAK_FILES_DIRECTORY.$opdetails["picture"];
						}
					} else {
						// System Avatar
						$avaimg = $base_url.'lctemplate/'.$jakwidget['template'].'/avatar/'.$jakwidget['avatarset'].'/system.jpg';
					}

					// We reset the new message alert
					if ($row['newc']) $jakdb->update("checkstatus", ["newc" => 0], ["convid" => $row['convid']]);

					// We reset the delete message
					if ($row['msgdel']) $jakdb->update("checkstatus", ["msgdel" => 0], ["convid" => $row['convid']]);

					// We change the edit message
					if ($row['msgedit']) {
						// include the PHP library (if not autoloaded)
						require('../class/class.emoji.php');
						$editedmsg = $jakdb->get("transcript", ["message", "edited"], ["id" => $row['msgedit']]);

						$showedit = ' | <i class="fa fa-edit"></i> '.JAK_base::jakTimesince($editedmsg["edited"], "", JAK_TIMEFORMAT);
						// Convert urls
						$editedmsg = nl2br(replace_urls($editedmsg["message"]), false);

						// Convert emotji
						$editedmsg = Emojione\Emojione::toImage($editedmsg);
						$jakdb->update("checkstatus", ["msgedit" => 0], ["convid" => $row['convid']]);
					}

					// We have a name/email change
					$newcudetails = "";
					if ($row['datac']) {
						$rowc = $jakdb->get("sessions", ["name", "email", "phone"], ["id" => $row['convid']]);

						if (filter_var($rowc['email'], FILTER_VALIDATE_EMAIL)) {
							$newemail = filter_var($rowc['email'], FILTER_SANITIZE_EMAIL);
						} else {
							$newemail = "";
						}

						// We update the database table
						$jakdb->update("checkstatus", ["datac" => 0], ["convid" => $row['convid']]);

						// We update the local storage
						$newcudetails = jak_string_encrypt_decrypt($cudetails[0].":#:".$cudetails[1].":#:".$cudetails[2].":#:".$rowc['name'].":#:".$newemail.":#:".$rowc['phone'].":#:".$cudetails[6]);

					}

					// Typing
					$otyping = $oname = '';
					$oname = $row["operator"];
					if ($row['typeo']) $otyping = str_replace("%s", $oname, $jkl["g37"]);
						
					die(json_encode(array('status' => true, 'redirect_c' => false, 'knockknock' => $kk, 'operator' => $opern, 'aboutme' => $operabout, 'avaimg' => $avaimg, 'newmsg' => $row['newc'], 'answid' => $answid, 'newmsgtxt' => $jkl['g22'], 'delmsg' => $row['msgdel'], 'msgedit' => $row['msgedit'], 'editmsg' => $editedmsg, 'showedit' => $showedit, 'datac' => $row['datac'], 'customer' => $newcudetails, 'files' => $row['files'], 'typing' => $otyping, 'inchat' => $inchat, 'pushnotify' => JAK_CLIENT_PUSH_NOT, 'softended' => $softended)));

				} else {

					// Forward the client to the correct URL
					$starturl = JAK_rewrite::jakParseurl('lc', $_POST["chatstatus"], $_GET["id"], $_POST["lang"]);

					die(json_encode(array('status' => false, 'action' => 'notfound', 'url' => str_replace('include/', '', $starturl))));
				}

			}

		}

		// It seems the client left
		die(json_encode(array('status' => false)));

	break;

	case 'loadcustomvars':

		if (isset($_POST['customvars']) && !empty($_POST['customvars'])) {

			// Let's make sure we have an active chat and it is available
			$custvar = jak_string_encrypt_decrypt($_POST['customvars'], false);

			// Let's explode the string (0 = name, 1 = email, 2 = msg)
			$custvar = explode(":#:", $custvar);

			// Go back to the chat
			die(json_encode(array('status' => true, 'name' => $custvar[0], 'email' => $custvar[1], 'msg' => $custvar[2])));
			
		}

	break;

	case 'loadprofile':

		if (isset($_POST['customer']) && !empty($_POST['customer'])) {

			// Let's make sure we have an active chat and it is available
			$cudetails = jak_string_encrypt_decrypt($_POST['customer'], false);

			// Let's explode the string (0 = convid, 1 = uniqueid, 2 = userid, 3 = name, 4 = email, 5 = phone, 6 = avatar)
			$cudetails = explode(":#:", $cudetails);

			if (isset($cudetails[0]) && is_numeric($cudetails[0])) {

				// Go back to the chat
				die(json_encode(array('status' => true, 'avatar' => $cudetails[6], 'name' => $cudetails[3], 'email' => $cudetails[4], 'phone' => $cudetails[5])));

			}
		}

	break;

	case 'changeprofile':

		// Reset vars
		$errors = $op_phones = array();

		$jkp = $_POST;

		if (empty($jkp['name']) || strlen(trim($jkp['name'])) <= 2) {
			$errors['name'] = $jkl['e'];
		}
			
		if (JAK_EMAIL_BLOCK && ((isset($widgetsettings['profile_email_required']) && $widgetsettings['profile_email_required'] == "Yes") || !empty($jkp['email']))) {
			$blockede = explode(',', JAK_EMAIL_BLOCK);
			if (in_array($jkp['email'], $blockede) || in_array(strrchr($jkp['email'], "@"), $blockede)) {
				$errors['email'] = $jkl['e10'];
			}
		}
			
		if (((isset($widgetsettings['profile_email_required']) && $widgetsettings['profile_email_required'] == "Yes") || !empty($jkp['email'])) && !filter_var($jkp['email'], FILTER_VALIDATE_EMAIL)) {
			$errors['email'] = $jkl['e1'];
		}
			
		if (isset($widgetsettings['profile_phone_required']) && $widgetsettings['profile_phone_required'] == "Yes" && !filter_var($jkp['phone'], FILTER_SANITIZE_NUMBER_INT)) {
			$errors['phone'] = $jkl['e14'];
		}

		// Now let's check if we have some custom fields
		if (isset($jkp['mycustomfields']) && !empty($jkp['mycustomfields'])) {

			// Ok we have some customfields
			$customI = explode(",", $jkp['mycustomfields']);

			// Now let us go through the fields
			foreach ($customI as $v) {

				// Get the correct value and check if we they are mandatory or not the value should be 
				$fvstore = explode(":#:", $v);

				// Ok field by field
				if (isset($jkp[$fvstore[0]]) && isset($fvstore[1]) && $fvstore[1] == 1 && empty($jkp[$fvstore[0]])) {
					$errors[$fvstore[0]] = $jkl['f'];
				}
			}
		}
		
		// Count the errors if erros show them of not proceed
		if (count($errors) > 0) {

			// We have an error, let's send it
			die(json_encode(array('status' => false, 'error' => $errors)));
				
		} else {

			if (isset($_POST['customer']) && !empty($_POST['customer'])) {

				// Let's make sure we have an active chat and it is available
				$cudetails = jak_string_encrypt_decrypt($_POST['customer'], false);

				// Let's explode the string (0 = convid, 1 = uniqueid, 2 = userid, 3 = name, 4 = email, 5 = phone, 6 = avatar)
				$cudetails = explode(":#:", $cudetails);

				if (isset($cudetails[0]) && is_numeric($cudetails[0])) {

					// Get the avatar
					$avatar = "";
					if (isset($jkp['avatar']) && isset($cudetails[6]) && $jkp['avatar'] != $cudetails[6]) {
						$avatar = $jkp['avatar'];
					} else {
						$avatar = $cudetails[6];
					}

					// Filter the new name
					$newname = filter_var(jak_input_filter($jkp['name']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);

					// Update the tables
					$jakdb->update("transcript", ["name" => $newname], ["AND" => ["convid" => $cudetails[0], "class" => "user"]]);
					$jakdb->update("sessions", ["name" => $newname, "email" => $jkp['email'], "phone" => $jkp['phone'], "usr_avatar" => $avatar], ["id" => $cudetails[0]]);

					// Now let's check if we have some custom fields
					if (isset($jkp['mycustomfields']) && !empty($jkp['mycustomfields'])) {

						// Ok we have some customfields
						$customI = explode(",", $jkp['mycustomfields']);

						// Now let us go through the fields
						foreach ($customI as $v) {

							// Get the correct value and check if we they are mandatory or not the value should be 
							$fvstore = explode(":#:", $v);

							if ($jakdb->has("chatcustomfields", ["AND" => ["convid" => $cudetails[0], "settname" => $fvstore[0]]])) {
								$jakdb->update("chatcustomfields", ["settvalue" => jak_input_filter($jkp[$fvstore[0]]), "updated" => $jakdb->raw("NOW()")], ["AND" => ["convid" => $cudetails[0], "settname" => $fvstore[0]]]);
							} else {
								if (isset($jkp[$fvstore[0]]) && !empty($jkp[$fvstore[0]])) {
									$jakdb->insert("chatcustomfields", ["convid" => $cudetails[0], "name" => $cudetails[3], "settname" => $fvstore[0], "settvalue" => jak_input_filter($jkp[$fvstore[0]]), "updated" => $jakdb->raw("NOW()"), "created" => $jakdb->raw("NOW()")]);
								}
							}
						}
					}

					// Get the details encrypted
					$cudetails = jak_string_encrypt_decrypt($cudetails[0].":#:".$cudetails[1].":#:".$cudetails[2].":#:".$newname.":#:".$jkp['email'].":#:".$jkp['phone'].":#:".$avatar);
					
					// Go back to the chat
					die(json_encode(array('status' => true, 'customer' => $cudetails)));

				}
			}
		}

		die(json_encode(array('status' => false)));

	break;

	case 'sendfeedback':

		// Errors in Array
		$errors = array();

		// Change Post
		$jkp = $_POST;
				
		if (isset($jkp['send_email']) && !filter_var($jkp['email'], FILTER_VALIDATE_EMAIL)) {
		    $errors['email'] = $jkl['e1'];
		}

		// Now let's check if we have some custom fields
		if (isset($jkp['mycustomfields']) && !empty($jkp['mycustomfields'])) {

			// Ok we have some customfields
			$customI = explode(",", $jkp['mycustomfields']);

			// Now let us go through the fields
			foreach ($customI as $v) {

				// Get the correct value and check if we they are mandatory or not the value should be 
				$fvstore = explode(":#:", $v);

				// Ok field by field
				if (isset($jkp[$fvstore[0]]) && isset($fvstore[1]) && $fvstore[1] == 1 && empty($jkp[$fvstore[0]])) {
					$errors[$fvstore[0]] = $jkl['f'];
				}
			}
		}
		
		// Count the errors if erros show them of not proceed
		if (count($errors) > 0) {
			
			// We have an error, let's send it
			die(json_encode(array('status' => false, 'error' => $errors)));
			
		} else {

			if (isset($_POST['customer']) && !empty($_POST['customer'])) {

				// Let's make sure we have an active chat and it is available
				$cudetails = jak_string_encrypt_decrypt($_POST['customer'], false);

				// Let's explode the string (0 = convid, 1 = uniqueid, 2 = userid, 3 = name, 4 = email, 5 = phone, 6 = avatar)
				$cudetails = explode(":#:", $cudetails);

				if (isset($cudetails[0]) && is_numeric($cudetails[0])) {
					
					// Send the transcript
					if (JAK_SEND_TSCRIPT == 1 && isset($jkp['send_email']) && $jkp['send_email'] == 1 && !empty($jkp['email'])) {

						$result = $jakdb->select("transcript", "*", ["AND" => ["convid" => $cudetails[0], "plevel" => 1]]);
				
						if (isset($result) && !empty($result)) {
						
							$email_body = '<body style="margin:10px;">
							<div style="width:550px; font-family: \'Droid Serif\', Helvetica, Arial, sans-serif;">
							<table style="width:100%;margin:0;padding:0;font-size: 13px;" cellspacing="10" border="0">
							<tr>
							<td>
							<h1>'.JAK_TITLE.'</h1>
							<p>'.$jkl['g66'].'</p>
							<div style="margin: 10px 0 10px 10px;
							border:1px solid #A8B9CB;
							height: 500px;
							overflow:auto;
							letter-spacing: normal;
							line-height: 1.5em;
							-moz-border-radius: 9px;
							-webkit-border-radius: 9px;
							border-radius: 9px;"><ul style="list-style: none;margin:0;padding:0;">';
						
							foreach ($result as $rowt) {
							
								if ($rowt['class'] == "admin") {
									$css_chat = 'background-color:#effcff;
									padding:5px 5px 10px 5px;
									border-bottom:1px solid #c4dde1;';
								} elseif ($rowt['class'] == "download") {
									$css_chat = 'padding:10px 5px 10px 5px;
									background-color:#d0e5f9;
									background-image:url('.BASE_URL.'img/download.png);
									background-position:98% 50%;
									background-repeat:no-repeat;
									border-bottom:1px solid #c4dde1;';
								} elseif ($rowt['class'] == "notice") {
									$css_chat = 'padding:10px 5px 10px 5px;
									background-color:#d0e5f9;
									background-image:url('.BASE_URL.'img/notice.png);
									background-position:98% 50%;
									background-repeat:no-repeat;
									border-bottom:1px solid #c4dde1;';
								} else {
									$css_chat = 'background-color:#f4fdf1;
									padding:5px 5px 10px 5px;
									border-bottom:1px solid #c4dde1;';
								}
						
								$email_body .= '<li style="'.$css_chat.'"><span style="font-size:10px;color:#555;">'.date(JAK_DATEFORMAT.JAK_TIMEFORMAT, strtotime($rowt['time'])).' '.$rowt['name'].' '.$jkl['g14'].' :</span><br />'.stripcslashes($rowt['message']).'</li>';	
							}
							
							$email_body .= '</ul></div></td>
							</tr>
							</table>
							</div>
							</body>';

						$jakdb->insert("transcript", [ 
							"name" => $jkp['name'],
							"message" => $jkl['g54'],
							"user" => $cudetails[2],
							"convid" => $cudetails[0],
							"class" => "notice",
							"time" => $jakdb->raw("NOW()")]);

						// Ok, we send the email // email address, cc email address, reply to, subject, message, attachment
        				jak_send_email($jkp['email'], "", "", JAK_TITLE.' - '.$jkl['g44'], str_ireplace("[\]", "", $email_body), "");
						
						}
					
					}

					// Update the email if we can
					$email = "";
					if (isset($jkp['email']) && filter_var($jkp['email'], FILTER_VALIDATE_EMAIL)) {
						$email = $jkp['email'];
					} else {
						$email = $cudetails[4];
					}
					$message = filter_var($jkp['feedback'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

					// update session table to new name:
					$jakdb->update("sessions", ["name" => jak_input_filter($jkp['name']), "email" => $email], ["id" => $cudetails[0]]);
					
					$email = filter_var($jkp['email'], FILTER_SANITIZE_EMAIL);
					$message = filter_var($jkp['feedback'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					
					// Now get the support time
					$row2 = $jakdb->get("sessions", ["department", "name", "initiated", "ended", "operatorid"], ["id" => $cudetails[0]]);
				
					$total_supporttime = $row2['ended'] - $row2['initiated'];

					if (!JAK_CRATING) $jkp["rating"] = 0;

					$jakdb->insert("user_stats", [ 
						"userid" => $row2["operatorid"],
						"vote" => $jkp["rating"],
						"name" => $row2['name'],
						"email" => $email,
						"comment" => $message,
						"support_time" => $total_supporttime,
						"time" => $jakdb->raw("NOW()")]);
			
					$listform = $jkl["g27"].': '.$jkp['name'].'<br />';
					if ($jkp['feedback']) {
						$listform .= $jkl["g24"].': '.$message.'<br />';
					} else {
						$listform .= $jkl["g24"].': '.$jkl["g12"].'<br />';
					}
					$listform .= $jkl["g29"].': '.$jkp['rating'].'/5';
					
					// Get the department for the contact form if set
					$op_email = JAK_EMAIL;
					if (is_numeric($row2["department"]) && $row2["department"] != 0) {
						
						if (isset($LC_DEPARTMENTS)) foreach ($LC_DEPARTMENTS as $d) {
						    if (in_array($row2["department"], $d)) {
						        if ($d['email']) $op_email = $d['email'];
						    }
						}
						
					}

					// Ok, we send the email // email address, cc email address, reply to, subject, message, attachment
        			jak_send_email($op_email, "", $email, JAK_TITLE.' - '.$jkl['g24'], $listform, "");

					// Has for sure left the chat
					$jakdb->insert("transcript", [ 
						"name" => $cudetails[3],
						"message" => sprintf($jkl['g16'], $cudetails[3]),
						"user" => $cudetails[2],
						"convid" => $cudetails[0],
						"class" => "ended",
						"time" => $jakdb->raw("NOW()")]);

					// Close the chat
					$jakdb->update("sessions", ["status" => 0, "ended" => time()], ["id" => $cudetails[0]]);
					$jakdb->update("checkstatus", ["hide" => 1], ["convid" => $cudetails[0]]);

					// Now let's check if we have some custom fields
					if (isset($jkp['mycustomfields']) && !empty($jkp['mycustomfields'])) {

						// Ok we have some customfields
						$customI = explode(",", $jkp['mycustomfields']);

						// Now let us go through the fields
						foreach ($customI as $v) {

							// Get the correct value and check if we they are mandatory or not the value should be 
							$fvstore = explode(":#:", $v);

							if ($jakdb->has("chatcustomfields", ["AND" => ["convid" => $cudetails[0], "settname" => $fvstore[0]]])) {
								$jakdb->update("chatcustomfields", ["settvalue" => jak_input_filter($jkp[$fvstore[0]]), "updated" => $jakdb->raw("NOW()")], ["AND" => ["convid" => $cudetails[0], "settname" => $fvstore[0]]]);
							} else {
								if (isset($jkp[$fvstore[0]]) && !empty($jkp[$fvstore[0]])) {
									$jakdb->insert("chatcustomfields", ["convid" => $cudetails[0], "name" => $cudetails[3], "settname" => $fvstore[0], "settvalue" => jak_input_filter($jkp[$fvstore[0]]), "updated" => $jakdb->raw("NOW()"), "created" => $jakdb->raw("NOW()")]);
								}
							}
						}
					}

					// The success message
					$gosuccess = '<div class="jaklcb_success">'.$jkl['g64'].'</div>';

					// Ok, we can send the true status
					die(json_encode(array('status' => true, 'successdiv' => $gosuccess)));

				}

			}

		}

		die(json_encode(array('status' => false)));

	break;

	case 'sendcontact':

		// Errors in Array
		$errors = array();
		$jkp = $_POST;
		
		if (empty($jkp['name']) || strlen(trim($jkp['name'])) <= 2) {
		    $errors['name'] = $jkl['e'];
		}
		
		if (JAK_EMAIL_BLOCK) {
			$blockede = explode(',', JAK_EMAIL_BLOCK);
			if (in_array($jkp['email'], $blockede) || in_array(strrchr($jkp['email'], "@"), $blockede)) {
				$errors['email'] = $jkl['e10'];
			}
		}
		
		if ($jkp['email'] == '' || !filter_var($jkp['email'], FILTER_VALIDATE_EMAIL)) {
		    $errors['email'] = $jkl['e1'];
		}
		
		if (isset($jakwidget['contact_phone_required']) && $jakwidget['contact_phone_required'] == "Yes" && !filter_var($jkp['phone'], FILTER_SANITIZE_NUMBER_INT)) {
		    $errors['phone'] = $jkl['e14'];
		}
		
		if (empty($jkp['message']) || strlen(trim($jkp['message'])) <= 2) {
		    $errors['message'] = $jkl['e2'];
		}

		if (!empty($jakwidget['dsgvo']) && empty($jkp['dsgvo'])) {
			$errors['dsgvo'] = $jkl['e3'];
		}

		// Now let's check if we have some custom fields
		if (isset($jkp['mycustomfields']) && !empty($jkp['mycustomfields'])) {

			// Ok we have some customfields
			$customI = explode(",", $jkp['mycustomfields']);

			// Now let us go through the fields
			foreach ($customI as $v) {

				// Get the correct value and check if we they are mandatory or not the value should be 
				$fvstore = explode(":#:", $v);

				// Ok field by field
				if (isset($jkp[$fvstore[0]]) && isset($fvstore[1]) && $fvstore[1] == 1 && empty($jkp[$fvstore[0]])) {
					$errors[$fvstore[0]] = $jkl['f'];
				}
			}
		}
		
		// Count the errors if erros show them of not proceed
		if (count($errors) > 0) {
			
			// We have an error, let's send it
			die(json_encode(array('status' => false, 'error' => $errors)));
			
		} else {
		
			// Country stuff
			$countryName = 'Disabled';
			$countryAbbrev = 'xx';
			$city = 'Disabled';
			$countryLong = $countryLat = '';
				
			// if ip is valid do the whole thing
			if ($ipa && !$ua->isRobot()) {

				// we will use the local storage for geo
				$removeloc = false;
				if (isset($_POST['geo']) && !empty($_POST['geo'])) {

					// Always escape any user input, including cookies:
					list($city, $countryName, $countryAbbrev, $countryLat, $countryLong, $storedtime) = explode('|', strip_tags(jak_string_encrypt_decrypt($_POST['geo'], false)));

					// We check if the geo data is older th3n
					if (isset($storedtime) && !empty($storedtime) && strtotime('+3 day', $storedtime) < time() || !isset($country_code) || empty($country_code)) $removeloc = true;

				}

				if ($removeloc) {

					// Try to get the data from the button table
					$bts = $jakdb->get("buttonstats", ["country", "countrycode", "latitude", "longitude"], ["session" => $_POST["rlbid"]]);
					if (!empty($bts)) {
						$countryName = $bts["country"];
						$countryAbbrev = $bts["countrycode"];
						$city = 'Disabled';
						$countryLong = $bts["longitude"];
						$countryLat = $bts["latitude"];
					}
				}
					
			}
			
			// Get the referrer
			$rowref = $jakdb->get("buttonstats", "referrer", ["session" => $_POST['rlbid']]);

			// Get the department for the contact form if set
			if (is_numeric($jakwidget['depid']) && $jakwidget['depid'] != 0) {
				
				$op_email = JAK_EMAIL;
					
				if (isset($LC_DEPARTMENTS)) foreach ($LC_DEPARTMENTS as $d) {
					if (in_array($jakwidget['depid'], $d)) {
				        if (isset($d['email']) && !empty($d['email'])) $op_email = $d['email'];
				    }
				}

				$depid = $jakwidget['depid'];
					
			} else {
				$op_email = JAK_EMAIL;
				$depid = 0;
			}
			
			// Reset phone var
			$cphone = '';
			
			$listform = $jkl["g27"].': '.$jkp['name'].'<br>';
			$listform .= $jkl["g47"].': '.$jkp['email'].'<br>';
			if (isset($jkp['phone'])) {
				$listform .= $jkl["g50"].': '.$jkp['phone'].'<br>';
				$cphone = $jkp['phone'];
			}
			$listform .= 'Referrer: '.$rowref.'<br>';
			$listform .= 'IP: '.$ipa.'<br>';
			$listform .= $jkl["g28"].': '.$jkp['message'];
			
			// We save the data
			$jakdb->insert("contacts", [ 
			"depid" => $depid,
			"name" => $jkp['name'],
			"email" => $jkp['email'],
			"phone" => $cphone,
			"message" => $jkp['message'],
			"ip" => $ipa,
			"city" => $city,
			"country" => $countryName,
			"countrycode" => $countryAbbrev,
			"longitude" => $countryLong,
			"latitude" => $countryLat,
			"referrer" => $rowref,
			"sent" => $jakdb->raw("NOW()")]);

			// Get the ID from the ticket
            $lastid = $jakdb->id();

            // Now let's check if we have some custom fields
			if (isset($jkp['mycustomfields']) && !empty($jkp['mycustomfields'])) {

				// Ok we have some customfields
				$customI = explode(",", $jkp['mycustomfields']);

				// Now let us go through the fields
				foreach ($customI as $v) {

					// Get the correct value and check if we they are mandatory or not the value should be 
					$fvstore = explode(":#:", $v);

					if (isset($jkp[$fvstore[0]]) && !empty($jkp[$fvstore[0]])) {
						$jakdb->insert("chatcustomfields", ["contactid" => $lastid, "name" => $jkp['name'], "settname" => $fvstore[0], "settvalue" => jak_input_filter($jkp[$fvstore[0]]), "updated" => $jakdb->raw("NOW()"), "created" => $jakdb->raw("NOW()")]);
					}
				}
			}

			// Get the user agent
			$valid_agent = filter_var($_SERVER['HTTP_USER_AGENT'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

			// Write the log file each time someone tries to login before
		    JAK_base::jakWhatslog(0, 0, 0, 31, $lastid, (isset($_COOKIE['WIOgeoData']) ? $_COOKIE['WIOgeoData'] : ''), $jkp['name'], $_SERVER['REQUEST_URI'], $ipa, $valid_agent);

		    // Ok, we send the email // email address, cc email address, reply to, subject, message, attachment
        	if (jak_send_email($op_email, explode(',', JAK_EMAILCC), $jkp['email'], JAK_TITLE.' - '.$jkl['g45'], $listform, "")) {

				// The success message
				$gosuccess = '<div class="jaklcb_success">'.$jkl['g65'].'</div>';

				// Ok, we can send the true status
				die(json_encode(array('status' => true, 'successdiv' => $gosuccess)));
				
			}
		}

		die(json_encode(array('status' => false)));

	break;

}
?>