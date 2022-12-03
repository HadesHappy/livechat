<?php

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 5.0.4                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2022 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

//Import the PHPMailer class into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
// use PHPMailer\PHPMailer\OAuth;
//Alias the League Google OAuth2 provider class
// use League\OAuth2\Client\Provider\Google;

// Redirect to something...
function jak_redirect($url, $code = 302) {
    header('Location: '.html_entity_decode($url), true, $code);
    exit;
}

// Filter inputs
function jak_input_filter($value) {
  $value = filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  return preg_replace("/[^0-9 _,.@\-\p{L}]/u", '', $value);
}

// filter url inputs
function jak_url_input_filter($value) {
	$value = html_entity_decode($value);
    $value = preg_replace('/[^\w\-.]/', '', $value);
    return trim(filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
}

// Check if userid can have access to the pages.
function jak_get_access($page, $array, $superoperator) {
	$roles = explode(',', $array);
	if ((is_array($roles) && in_array($page, $roles)) || $superoperator) {
		return true;
	}
}

function jak_clean_string($string) {

    // Remove all spaces
    $string = str_replace(' ', '_', $string); // Replaces all spaces with hyphens.

    // all lower case
    $string = strtolower($string);

    // return
    return preg_replace('/[^a-z0-9\_]/', '', $string); // Removes special chars.
}

// Get the data only per ID (e.g. edit single user, edit category)
function jak_get_data($id, $table) {	
	global $jakdb;
	$datasett = $jakdb->get($table, "*", ["id" => $id]);
    return $datasett;
}

// Check if row exist
function jak_row_exist($id, $table) {
	global $jakdb;
    if ($jakdb->has($table, ["id" => $id])) {
        return true;
	}
}

// Check if row exist with custom field
function jak_field_not_exist($check, $table, $field) {
	global $jakdb;
    if ($jakdb->has($table, [$field => $check])) {
        return true;
	}
}

// Verify paramaters
function verifyparam($name, $regexp, $default = null) {

	if (isset($_GET[$name])) {
		$val = $_GET[$name];
		if (preg_match($regexp, $val))
			return $val;

	} else if (isset($_POST[$name])) {
		$val = $_POST[$name];
		if (preg_match($regexp, $val))
			return $val;

	} else {
		if (isset($default))
			return $default;
	}
	die("<html><head></head><body>Wrong parameter used or absent: " . $name . "</body></html>");
}

// create session id
function create_session_id($depid, $opid, $ipa) {

    global $jakdb;

    // Get the referrer URL
    $referrer = selfURL(NULL);

    if (isset($_SESSION['rlbid']) && !empty($_SESSION['rlbid'])) {
        $jakdb->update("buttonstats", ["depid" => $depid, "opid" => $opid, "hits[+]" => 1, "referrer" => $referrer, "ip" => $ipa, "lasttime" => $jakdb->raw("NOW()")], ["session" => $_SESSION['rlbid']]);
        $rlbid = $_SESSION['rlbid'];
    } else {
        $salt = rand(100, 99999);
        $rlbid = $salt.time();

        // Get the client browser
        $ua = new Browser();

        // get client information
        $clientsystem = $ua->getPlatform().' - '.$ua->getBrowser(). " " . $ua->getVersion();

        // Country Stuff
        $country_name = 'Disabled';
        $country_code = 'xx';
        $city = 'Disabled';
        $country_lng = $country_lat = '';

        // Now let's check if the ip is ipv4
        if ($ipa && !$ua->isRobot()) {

            $ipc = curl_init();
            curl_setopt($ipc, CURLOPT_URL, "https://ipgeo.jakweb.ch/api/".$ipa);
            curl_setopt($ipc, CURLOPT_HEADER, false);
            curl_setopt($ipc, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ipc);
            curl_close($ipc);

            $getinfo = json_decode($response, true);

            if (isset($getinfo) && !empty($getinfo)) {

                $country_name = ucwords(strtolower(filter_var($getinfo["country"]["name"], FILTER_SANITIZE_FULL_SPECIAL_CHARS)));
                $country_code = strtolower(filter_var($getinfo["country"]["code"], FILTER_SANITIZE_FULL_SPECIAL_CHARS));
                $city = filter_var($getinfo["city"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $country_lng = filter_var($getinfo["location"]["longitude"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION); 
                $country_lat = filter_var($getinfo["location"]["latitude"], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

                // Setting a cookie with the data, which is set to expire in a week:
                $geodata = jak_string_encrypt_decrypt($city.'|'.$country_name.'|'.$country_code.'|'.$country_lat.'|'.$country_lng.'|'.time());

            }

        }

        $jakdb->insert("buttonstats", ["depid" => $depid, "opid" => $opid, "referrer" => $referrer, "firstreferrer" => $referrer, "agent" => $clientsystem, "hits" => 1, "ip" => $ipa, "country" => $country_name, "countrycode" => $country_code, "latitude" => $country_lat, "longitude" => $country_lng, "session" => $rlbid, "time" => $jakdb->raw("NOW()"), "lasttime" => $jakdb->raw("NOW()")]);

        // Set the session because we can use it
        $_SESSION['rlbid'] = $rlbid;
    }

    return $rlbid;
}

// Verfiy if there is a online operator
function online_operators($dp, $did = 0, $oid = 0) {
	
	$timeout = time() - 300;
	$timerunout = 1;
	$department = 0;
	$departmentall = array();
	$departments = array();
	$departmentp = array();
	
	global $jakdb;
	
	// Update database first to see who is online!
	$jakdb->update("user", ["available" => 0], ["lastactivity[<]" => $timeout]);

	// Set to zero
	$sql_where = '';
	
	// We do have a department id
	if ($did > 0) {
		$sql_where = ' AND (departments = 0 OR FIND_IN_SET(:did, departments))';
	}
	
	// We do have an operator id
	if ($oid > 0) {
		$sql_where = ' AND id = :oid';
	}
	
	$sth = $jakdb->pdo->prepare("SELECT id, hours_array, phonenumber, available, departments, emailnot, pusho_tok, push_notifications, alwaysonline FROM ".JAKDB_PREFIX."user WHERE access = 1".$sql_where);

	if ($oid > 0)$sth->bindParam(':oid', $oid, PDO::PARAM_INT);
	if ($did > 0 && $oid == 0) $sth->bindParam(':did', $did, PDO::PARAM_INT);

	$sth->execute();

	$result = $sth->fetchAll();

	if (isset($result) && !empty($result)) {
		foreach ($result as $row) {
			
			$oponline = false;
			
			// Operator is available
			if ($row["available"] == 1 || $row["alwaysonline"] == 1) $oponline = true;
			
			// Now let's check if we have a time available
			if (!$oponline && JAK_base::jakAvailableHours($row["hours_array"], date('Y-m-d H:i:s')) && ($row["phonenumber"] || $row["emailnot"] || JAK_NATIVE_APP_TOKEN || $row["pusho_tok"] || $row["push_notifications"])) $oponline = true;
			
			// Now we have an available operator
			if ($oponline) {
			
				// Departments is 0 we use all.
				if ($row["departments"] == 0) {
					$departmentall = $dp;
				}
				
				// Single department we use the one
				if (is_numeric($row["departments"])) {
					if (isset($dp) && is_array($dp)) foreach($dp as $z) {
						if ($z["id"] == $row["departments"]) {
							$departments[] = $z;
						}
					}
				}
				
				// Department array, let's get the right ones.
				if ($row["departments"] != 0 && !is_numeric($row["departments"])) {
					if (isset($dp) && is_array($dp)) foreach($dp as $z) {
						if (in_array($z["id"], explode(',', $row["departments"]))) {
							$departmentp[] = $z;
						}
					}
				}
			}
		}
		
	} else {
		$timerunout = 0;
	}
	
	if ($timerunout) {
		$department = array_merge($departmentp, $departmentall, $departments);
		
		if (is_array($department)) $department = array_map("unserialize", array_unique(array_map("serialize", $department)));


		
		return $department;
	} else {
		return false;
	}
}

// Verfiy if there is a online operator with whatsapp
function online_operator_list_whatsapp($dp, $did = 0, $oid = 0) {
        
        $timeout = time() - 300;
        $timerunout = 1;
        $department = 0;
        $opdetails = array();
        
        global $jakdb;
        
        // Update database first to see who is online!
        $jakdb->update("user", ["available" => 0], ["lastactivity[<]" => $timeout]);

        // Set to zero
        $sql_where = '';
        
        // We do have a department id
        if ($did > 0) {
            $sql_where = ' AND (departments = 0 OR FIND_IN_SET(:did, departments))';
        }
        
        // We do have an operator id
        if ($oid > 0) {
            $sql_where = ' AND id = :oid';
        }

        $sth = $jakdb->pdo->prepare("SELECT id, departments, name, picture, aboutme, hours_array, whatsappnumber, available, alwaysnot, alwaysonline FROM ".JAKDB_PREFIX."user WHERE access = 1 AND whatsappnumber != ''".$sql_where." ORDER BY departments ASC");

        if ($oid > 0)$sth->bindParam(':oid', $oid, PDO::PARAM_INT);
        if ($did > 0 && $oid == 0) $sth->bindParam(':did', $did, PDO::PARAM_INT);

        $sth->execute();

        $result = $sth->fetchAll();

        if (isset($result) && !empty($result)) {
            foreach ($result as $row) {
                
                $oponline = false;
                
                // Operator is available
                if ($row["available"] == 1 || $row["alwaysnot"] == 1  || $row["alwaysonline"] == 1) $oponline = true;
                
                // Now let's check if we have a time available
                if (!$oponline && JAK_base::jakAvailableHours($row["hours_array"], date('Y-m-d H:i:s')) && ($row["whatsappnumber"])) $oponline = true;
                
                // Departments is 0 we use all.
                $deptitle = array();
                if (is_numeric($row["departments"])) {
                    
                        if (isset($dp) && is_array($dp)) foreach($dp as $z) {
                        
                            if ($z["id"] == $row["departments"]) {
                                $deptitle[] = $z["title"];
                            }
                        
                        }
                // Department array, let's get the right ones.
                } elseif ($row["departments"] != 0 && !is_numeric($row["departments"])) {

                    $deptitle = array();
                        
                    if (isset($dp) && is_array($dp)) foreach($dp as $z) {
                        
                        if (in_array($z["id"], explode(',', $row["departments"]))) {
                                $deptitle[] = $z["title"];
                        }
                        
                    }
                    
                }

                $deptitle = join(', ', $deptitle);

                $opdetails[] = array("id" => $row["id"], "name" => $row["name"], "picture" => $row["picture"], "aboutme" => $row["aboutme"], "whatsappnumber" => $row["whatsappnumber"], "title" => $deptitle, "isonline" => $oponline);
            
            }
            
        } else {
            $timerunout = 0;
        }
        
    if ($timerunout) {
        return $opdetails;
    } else {
        return false;
    }
}

function online_operator_list($dp, $did = 0, $oid = 0) {
        
    $timeout = time() - 300;
    $timerunout = 1;
    $department = 0;
    $opdetails = array();
    
    global $jakdb;

    // Check if the client is logged in
    $dbfiltered = $dp;
    
    // Update database first to see who is online!
    $jakdb->update("user", ["available" => 0], ["lastactivity[<]" => $timeout]);

    // Set to zero
    $sql_where = '';
    
    // We do have a department id
    if ($did > 0) {
        $sql_where = ' AND (departments = 0 OR FIND_IN_SET(:did, departments))';
    }
    
    // We do have an operator id
    if ($oid > 0) {
        $sql_where = ' AND id = :oid';
    }

    $sth = $jakdb->pdo->prepare("SELECT id, name, picture, hours_array, aboutme, phonenumber, available, departments, emailnot, pusho_tok, push_notifications, alwaysonline FROM ".JAKDB_PREFIX."user WHERE access = 1".$sql_where." ORDER BY departments ASC");

    if ($oid > 0)$sth->bindParam(':oid', $oid, PDO::PARAM_INT);
    if ($did > 0 && $oid == 0) $sth->bindParam(':did', $did, PDO::PARAM_INT);

    $sth->execute();

    $result = $sth->fetchAll();

    if (isset($result) && !empty($result)) {
        foreach ($result as $row) {
            
            $oponline = false;
            
            // Operator is available
            if ($row["available"] == 1 || $row["alwaysonline"] == 1) $oponline = true;
            
            // Now let's check if we have a time available
            if (!$oponline && JAK_base::jakAvailableHours($row["hours_array"], date('Y-m-d H:i:s')) && ($row["phonenumber"] || $row["emailnot"] || JAK_NATIVE_APP_TOKEN || $row["pusho_tok"] || $row["push_notifications"])) $oponline = true;
            
            // Now we have an available operator
            if ($oponline) {

                // Get the user stats
                $ustat = $jakdb->count("user_stats", "id", ["AND" => ["userid" => $row["id"], "vote[>]" => 2]]);
            
                // Departments is 0 we use all.
                $deptitle = "";
                if (is_numeric($row["departments"])) {
                
                    if (isset($dbfiltered) && is_array($dbfiltered)) foreach($dbfiltered as $z) {
                    
                        if ($z["id"] == $row["departments"]) {
                            $deptitle = $z["title"];
                        }
                    
                    }
                // Department array, let's get the right ones.
                } elseif ($row["departments"] != 0 && !is_numeric($row["departments"])) {
                    
                    if (isset($dbfiltered) && is_array($dbfiltered)) foreach($dbfiltered as $z) {
                    
                        if (in_array($z["id"], explode(',', $row["departments"]))) {
                            $deptitle = $z["title"];
                        }
                    
                    }
                
                }

                $opdetails[] = array("id" => $row["id"], "name" => $row["name"], "picture" => $row["picture"], "aboutme" => $row["aboutme"], "sum" => $ustat, "title" => $deptitle);
            }
        
        }
        
    } else {
        $timerunout = 0;
    }
    
    if ($timerunout) {
        return $opdetails;
    } else {
        return false;
    }
}

// Check if the lang folder for buttons exist
function folder_lang_button($lang) {
	return file_exists('./img/buttons/'.$lang.'/');
}

// Get the real IP Address
function get_ip_address() {
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE |  FILTER_FLAG_NO_RES_RANGE) !== false) {
                    return $ip;
                } elseif (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false) {
                    return $ip;
                } else {
                	return 0;
                }
            }
        }
    }
}

// Send Emails via PHP MAILER
function jak_send_email($mailaddress, $mailccaddress, $replyto, $subject, $message, $emailfiles) {

    // Now let's start with sending emails
    $mail = new PHPMailer(true);

    if (JAK_SMTP_MAIL) {
        $mail->IsSMTP(); // telling the class to use SMTP
        $mail->Host = JAK_SMTPHOST;
        $mail->SMTPAuth = (JAK_SMTP_AUTH ? true : false); // enable SMTP authentication
        $mail->SMTPSecure = JAK_SMTP_PREFIX; // sets the prefix to the server
        $mail->SMTPAutoTLS = false;
        $mail->SMTPKeepAlive = (JAK_SMTP_ALIVE ? true : false); // SMTP connection will not close after each email sent
        $mail->Port = JAK_SMTPPORT; // set the SMTP port for the GMAIL server
        $mail->Username = JAK_SMTPUSERNAME; // SMTP account username
        $mail->Password = JAK_SMTPPASSWORD; // SMTP account password
    }

    // Finally send the email
    $mail->SetFrom(JAK_SMTP_SENDER);

    // Email Adressess
    if (isset($mailaddress) && !empty($mailaddress)) {
        if (is_array($mailaddress)) {
            foreach ($mailaddress as $k => $ea) {
                if (!empty($ea)) {
                    $mail->addAddress($ea);
                }
            }
        } else {
            $mail->addAddress($mailaddress);
        }
    }

    // CC Adressess
    if (isset($mailccaddress) && !empty($mailccaddress)) {
        if (is_array($mailccaddress)) {
            foreach ($mailccaddress as $k => $occ) {
                if (!empty($occ)) {
                    $mail->addCC($occ);
                }
            }
        } else {
            $mail->addCC($mailccaddress);
        }
    }

    // Reply To
    if (isset($replyto) && !empty($replyto)) $mail->AddReplyTo($replyto);

    // We have attachments
    if (isset($emailfiles) && !empty($emailfiles)) {
        if (is_array($emailfiles)) {
            foreach ($emailfiles as $k => $ef) {
                if (!empty($ef)) {
                    $mail->addAttachment($ef);
                }
            }
        } else {
            $mail->addAttachment($emailfiles);
        }
    }

    $mail->CharSet = 'UTF-8';
    $mail->Subject = $subject;
    $mail->MsgHTML($message);
    $mail->AltBody = strip_tags($message);
    // Send
    if ($mail->Send()) {

        //Clear all addresses and attachments for the next iteration
        $mail->clearAddresses();
        $mail->clearAttachments();

        return true;

    } else {
        return false;
    }  
}

// Send Notifications
function jak_send_notifications($userid, $convid, $title, $msg, $url, $pushnot, $emailnot, $email, $pushotok, $pushokey, $phonenr) {

	$op_phones = array();
	$msg = stripcslashes($msg);

	// Native App notification
    if ($pushnot && JAK_NATIVE_APP_TOKEN && JAK_NATIVE_APP_KEY) {

    	global $jakdb;

       	// Do we have a valid device for this user
        $vd = false;
        $vd = $jakdb->select("push_notification_devices", ["ostype", "token", "appname"], ["userid" => $userid]);

        if (isset($vd) && !empty($vd)) foreach ($vd as $vdrow) {

            $fields = array("token" => JAK_NATIVE_APP_TOKEN, "user" => JAK_NATIVE_APP_KEY, "ostype" => $vdrow["ostype"], "device" => $vdrow["token"], "appname" => $vdrow["appname"], "title" => JAK_TITLE, "message" => $msg);

            $str = http_build_query($fields);

            $jm = curl_init();
            curl_setopt($jm, CURLOPT_URL, "https://www.jakweb.ch/push/m");
            curl_setopt($jm, CURLOPT_HEADER, false);
            curl_setopt($jm, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($jm, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($jm, CURLOPT_POST, count($fields));
            curl_setopt($jm, CURLOPT_POSTFIELDS, $str);
            $response = curl_exec($jm);
           	curl_close($jm);

        }
    }

    // Email Notifications
    if ($emailnot) {

        // Ok, we send the email // email address, cc email address, reply to, subject, message, attachment
        jak_send_email($email, "", "", JAK_TITLE, '<a href="'.$url.'">'.$msg.'</a>', "");
                                    
    }

    // Pushover
    if ($pushnot && $pushotok && $pushokey) {

        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, "https://api.pushover.net/1/messages.json");
        curl_setopt($c, CURLOPT_HEADER, false);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, array(
            "token" => $pushotok,
            "user" => $pushokey,
            "message" => $msg,
            "title" => JAK_TITLE,
            "url" => $url
        ));
        $response = curl_exec($c);
        curl_close($c);

    }

    if (JAK_TW_SID && JAK_TW_TOKEN && !empty($phonenr)) {
                                
        // Twilio
        if (JAK_TWILIO_NEXMO == 1) {

            $twurl = "https://api.twilio.com/2010-04-01/Accounts/".JAK_TW_SID."/Messages.json";
            $data = array (
                'From' => JAK_TW_PHONE,
                'To' => $phonenr,
                'Body' => $msg
            );
            $post = http_build_query($data);
            $tw = curl_init($twurl);
            curl_setopt($tw, CURLOPT_POST, true);
            curl_setopt($tw, CURLOPT_RETURNTRANSFER, true);
            // curl_setopt($tw, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($tw, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($tw, CURLOPT_USERPWD, JAK_TW_SID.':'.JAK_TW_TOKEN);
            curl_setopt($tw, CURLOPT_POSTFIELDS, $post);
            $twresponse = curl_exec($tw);
            curl_close($tw);
                                        
        // Plivo
        } elseif (JAK_TWILIO_NEXMO == 2) {

            $op_phones[] = $phonenr;
                                                
        // Nexmo
        } else {
                                        
            require_once(APP_PATH.'include/nexmo/NexmoMessage.php');
                                                    
            // Step 1: Declare new NexmoMessage. (Api Key) (Api Secret)
            $nexmo_sms = new NexmoMessage(JAK_TW_SID, JAK_TW_TOKEN);
                                                            
            // Step 2: Use sendText( $to, $from, $message ) method to send a message. 
            $info = $nexmo_sms->sendText($phonenr, JAK_TITLE, $msg);
                                                                                
        }
    }

    if (JAK_TW_SID && !empty($phonenr) && JAK_TWILIO_NEXMO == 3) {

            // https://gw.cmtelecom.com/gateway.ashx?producttoken=8747b4bd-b656-482b-a321-326135c956e7&body=Example+message+text&to=0041778140757&from=SenderName&reference=your_reference

            $cmurl = "https://gw.cmtelecom.com/gateway.ashx";
            $data = array (
                'producttoken' => JAK_TW_SID,
                'to' => $phonenr,
                'body' => $msg,
                'from' => JAK_TITLE,
                'reference' => $convid
            );
            $post = http_build_query($data);
            $cm = curl_init($cmurl);
            curl_setopt($cm, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($cm, CURLOPT_POSTFIELDS, $post);
            $cmresponse = curl_exec($cm);
            curl_close($cm);

    }

    // Send the sms with plivo
    if (isset($op_phones) && !empty($op_phones) && JAK_TWILIO_NEXMO == 2) {
                                                
        // Join the number to a list
        $sendsmsop = join(',', $op_phones);

        # SMS sender ID.
        $src = (JAK_TW_PHONE ? JAK_TW_PHONE : JAK_TITLE);
        # SMS destination number
        $dst = $sendsmsop;
        # SMS text
        $text = $msg;
        $url = 'https://api.plivo.com/v1/Account/'.JAK_TW_SID.'/Message/';
        $data = array("src" => "$src", "dst" => "$dst", "text" => "$text");
        $data_string = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
       	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_USERPWD, JAK_TW_SID . ":" . JAK_TW_TOKEN);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $curl_output = curl_exec($ch);
        curl_close($ch);
    }

    return true;

}

// Replace urls
function replace_urls($string) {
	$string = preg_replace('/(https?|ftp)([\:\/\/])([^\\s]+)/', '<a href="$1$2$3" target="_blank">$1$2$3</a>', $string);
	return $string;
}

// only full words
function ls_cut_text($jakvar,$jakvar1,$jakvar2) {
	if (empty($jakvar1)) {
		$jakvar1 = 160;
	}
	$crepl = array('<?','<?php','"',"'","?>");
	$cfin = array('','','','','');
	$jakvar = str_replace($crepl, $cfin, $jakvar);
    $jakvar = trim($jakvar);
    $jakvar = strip_tags($jakvar);
    $txtl = strlen($jakvar);
    if($txtl > $jakvar1) {
        for($i=1;$jakvar[$jakvar1-$i]!=" ";$i++) {
            if($i == $jakvar1) {
                return substr($jakvar,0,$jakvar1).$jakvar2;
            }
        }
        $jakdata = substr($jakvar,0,$jakvar1-$i+1).$jakvar2;
    } else {
    	$jakdata = $jakvar;
    }
    return $jakdata;
}

// Detect Mobile Browser in a simple way to display videos in html5 or video/template not available message
function jak_find_browser($useragent, $wap) {

	$ifmobile = preg_match('/android|avantgo|blackberry|blazer|compal|elaine|fennec|hiptop|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile|o2|opera m(ob|in)i|palm( os)?|p(ixi|re)\/|plucker|pocket|psp|smartphone|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce; (iemobile|ppc)|xiino/i', $useragent);
	
	$ifmobileM = preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i', substr($useragent,0,4));
	
	if ($ifmobile || $ifmobileM || isset($wap)) {
		return true;
	} else {
		return false;
	}
}

// only full words
function jak_cut_text($jakvar,$jakvar1,$jakvar2) {
    if (empty($jakvar1)) {
        $jakvar1 = 160;
    }
    $crepl = array('<?','<?php','"',"'","?>");
    $cfin = array('','','','','');
    $jakvar = str_replace($crepl, $cfin, $jakvar);
    $jakvar = trim($jakvar);
    $jakvar = strip_tags($jakvar);
    $txtl = strlen($jakvar);
    if($txtl > $jakvar1) {
        for($i=1;$jakvar[$jakvar1-$i]!=" ";$i++) {
            if($i == $jakvar1) {
                return substr($jakvar,0,$jakvar1).$jakvar2;
            }
        }
        $jakdata = substr($jakvar,0,$jakvar1-$i+1).$jakvar2;
    } else {
        $jakdata = $jakvar;
    }
    return $jakdata;
}

function selfURL($url) {

    if (isset($url) && !empty($url)) {
        $referrer = $url;
    } else {
        $referrer = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }
	$referrer = filter_var($referrer, FILTER_VALIDATE_URL);
    
    return $referrer;  
}

// Password generator
function jak_password_creator($length = 8) {
	return substr(md5(rand().rand()), 0, $length);
}

// Get the html chat widget content
function jak_html_widget_css($chatposition) {

	// Reset some vars
	$iframestyle = "";

	if ($chatposition) {
		$iframestyle = 'position:fixed;'.$chatposition;
	}

	$iframestyle .= 'z-index:9999;';

	return $iframestyle;
}

// Encrypt / Decrypt strings for strings
function jak_string_encrypt_decrypt($data, $ed = true) {

    $output = false;
    $encrypt_method = "AES-128-CTR";
    $key = JAK_STRING_SECRET_KEY;
    $iv = substr(hash('sha256', JAK_STRING_SECRET_IV), 0, 16);

    if ($ed) {
        $output = openssl_encrypt($data, $encrypt_method, $key, 0, $iv);
    } elseif (!$ed){
        $output = openssl_decrypt($data, $encrypt_method, $key, 0, $iv);
    }

    return $output;
}
?>