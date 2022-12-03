<?php

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 4.0.5                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2021 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

class JAK_userlogin {

	protected $name = '', $email = '', $pass = '', $time = '';
	var $username;     //Username given on sign-up
	
	public function __construct() {
	    $this->username = '';
	}
	   
	function jakChecklogged() {
	
	    /* Check if user has been remembered */
	    if (isset($_COOKIE['jak_lcp_cookname']) && isset($_COOKIE['jak_lcp_cookid'])) {
	        $_SESSION['jak_lcp_username'] = $_COOKIE['jak_lcp_cookname'];
	        $_SESSION['jak_lcp_idhash'] = $_COOKIE['jak_lcp_cookid'];
	    }
	
	    /* Username and idhash have been set */
	    if (isset($_SESSION['jak_lcp_username']) && isset($_SESSION['jak_lcp_idhash']) && $_SESSION['jak_lcp_username'] != $this->username) {
	        /* Confirm that username and userid are valid */
	        if (!JAK_userlogin::jakConfirmidhash($_SESSION['jak_lcp_username'], $_SESSION['jak_lcp_idhash'])) {
	        	/* Variables are incorrect, user not logged in */
	            unset($_SESSION['jak_lcp_username']);
	            unset($_SESSION['jak_lcp_idhash']);
	            
	            return false;
	        }
	         
	        // Return the user data
	        return JAK_userlogin::jakUserinfo($_SESSION['jak_lcp_username']);

	    /* User not logged in */
	    } else {
	    	return false;
	    }
	}

	function jakCheckrestlogged($userid, $hash) {
	
	    /* UserID and Hash have been set */
	    global $jakdb;
	    $datauinfo = $jakdb->get("user", "*", ["AND" => ["id" => $userid, "idhash" => $hash]]);
	    if (isset($datauinfo) && !empty($datauinfo)) {

	        // Return the user data
	        return $datauinfo;

	    /* User not logged in */
	    } else {
	    	return false;
	    }
	}
	
	public static function jakCheckuserdata($username, $pass) {
	
		// The new password encrypt with hash_hmac
		$passcrypt = hash_hmac('sha256', $pass, DB_PASS_HASH);
		
		if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
		
			if (!preg_match('/^([a-zA-Z0-9\-_])+$/', $username)) {
				return false;
			}
			
		}
	
		global $jakdb;
		$datausr = $jakdb->get("user", ["id", "username"], ["AND" => ["OR" => ["username" => strtolower($username), "email" => strtolower($username)], "password" => $passcrypt, "access" => 1]]);
		if ($datausr) {
			return $datausr;
		} else {
			return false;
		}
			
	}
	
	public static function jakLogin($name, $pass, $remember) {
		
		// The new password encrypt with hash_hmac
		$passcrypt = hash_hmac('sha256', $pass, DB_PASS_HASH);
	
		global $jakdb;
		
		// Get the stuff out the database
		$datausr = $jakdb->get("user", ["idhash", "logins"], ["AND" => ["username" => $name, "password" => $passcrypt]]);
		
		if ($datausr['logins'] % 10 == 0) {
		
			// Generate new idhash
			$nidhash = JAK_userlogin::generateRandID();
			
		} else {
		
			if (isset($datausr['idhash']) && !empty($datausr['idhash']) && $datausr['idhash'] != "NULL") { 
		
				// Take old idhash
				$nidhash = $datausr['idhash'];
			
			} else {
			
				// Generate new idhash
				$nidhash = JAK_userlogin::generateRandID();
			
			}
		
		}
		
		// Set session in database
		$jakdb->update("user", ["session" => session_id(), "idhash" => $nidhash, "logins[+]" => 1, "forgot" => 0, "lastactivity" => time()], ["AND" => ["username" => $name, "password" => $passcrypt]]);
		
		$_SESSION['jak_lcp_username'] = $name;
		$_SESSION['jak_lcp_idhash'] = $nidhash;
		
		// Check if cookies are set previous (wrongly) and delete
		if (isset($_COOKIE['jak_lcp_cookname']) || isset($_COOKIE['jak_lcp_cookid'])) {

			JAK_base::jakCookie('jak_lcp_cookname', $name, -JAK_COOKIE_TIME, JAK_COOKIE_PATH);
			JAK_base::jakCookie('jak_lcp_cookid', $nidhash, -JAK_COOKIE_TIME, JAK_COOKIE_PATH);
		}
		
		// Now check if remember is selected and set cookies new...
		if ($remember) {
			
			JAK_base::jakCookie('jak_lcp_cookname', $name, JAK_COOKIE_TIME, JAK_COOKIE_PATH);
			JAK_base::jakCookie('jak_lcp_cookid', $nidhash, JAK_COOKIE_TIME, JAK_COOKIE_PATH);

		}
		
	}

	public static function jakrestLogin($name, $pass) {
		
		// The new password encrypt with hash_hmac
		$passcrypt = hash_hmac('sha256', $pass, DB_PASS_HASH);
	
		global $jakdb;
		
		// Get the stuff out the database
		$datausr = $jakdb->get("user",["idhash", "logins"], ["AND" => ["username" => $name, "password" => $passcrypt]]);
		
		if ($datausr['logins'] % 10 == 0) {
		
			// Generate new idhash
			$nidhash = JAK_userlogin::generateRandID();
			
		} else {
		
			if (isset($datausr['idhash']) && !empty($datausr['idhash']) && $datausr['idhash'] != "NULL") { 
		
				// Take old idhash
				$nidhash = $datausr['idhash'];
			
			} else {
			
				// Generate new idhash
				$nidhash = JAK_userlogin::generateRandID();
			
			}
		
		}
		
		// Set session in database
		$jakdb->update("user", ["session" => session_id(), "idhash" => $nidhash, "logins[+]" => 1, "available" => 1, "forgot" => 0, "lastactivity" => time()], ["AND" => ["username" => $name, "password" => $passcrypt]]);
		
		// Return the user data
	    return JAK_userlogin::jakUserinfo($name);
		
	}

	public static function jakWriteDeviceToken($userid, $device, $token, $appname, $appversion) {

		// Check the data before we run any queries
		$allowed_device_types = ['ios', 'android'];
		if (in_array($device, $allowed_device_types) === false) {
        	return false;
	    }

	    if ((strlen(trim($token)) === 0) || (strlen(trim($token)) > 250)) {
	        return false;
	    }

	    $allowed_app_versions = ['LC3', 'HD3'];
		if (in_array($appname, $allowed_app_versions) === false) {
        	$appname = 'LC3';
	    }

	    // trim token
	    $token = trim($token);
	
		global $jakdb;
		// Get the info out the database
		$pushid = $jakdb->get("push_notification_devices", "id", ["token" => $token]);

		if (isset($pushid) && !empty($pushid)) {
			$jakdb->update("push_notification_devices", ["lastedit" => $jakdb->raw("NOW()")], ["id" => $pushid]);
		} else {
			// We have no entry at all
			$jakdb->insert("push_notification_devices", ["userid" => $userid, "ostype" => $device, "token" => $token, "appname" => $appname, "appversion" => $appversion, "lastedit" => $jakdb->raw("NOW()"), "created" => $jakdb->raw("NOW()")]);
		}

		// Remove old entries older than 2 weeks
		$deleteold = strtotime("-2 weeks");
		$deleteoldmysql = date('Y-m-d H:i:s', $deleteold);
       	$jakdb->delete("push_notification_devices", ["lastedit[<]" => $deleteoldmysql]);

		return true;
		
	}
	
	public static function jakConfirmidhash($username, $idhash) {
	
		global $jakdb;
		
		if (isset($username) && !empty($username)) {
		
		    $datausr = $jakdb->get("user","idhash",["AND" => ["username" => $username, "access" => 1]]);
		    
		    if ($datausr) {
		    
		    	$datausr = stripslashes($datausr);
		    	$idhash = stripslashes($idhash);
		    			    	
		    	/* Validate that userid is correct */
		    	if(!is_null($datausr) && $idhash == $datausr) {
		    		return true; //Success! Username and idhash confirmed
		    	}

		    }
		        
		}
	
		return false;
			
	}
	
	public static function jakUserinfo($username) {
	
			global $jakdb;
			$datauinfo = $jakdb->get("user", "*", ["AND" => ["username" => $username, "access" => 1]]);
			if ($datauinfo) {
			   return $datauinfo;
			} else {
				return false;
			}
			
	}
	
	public static function jakUpdatelastactivity($userid) {
	
			global $jakdb;
			if (is_numeric($userid)) $jakdb->update("user", ["lastactivity" => time()], ["id" => $userid]);
			
	}
	
	public static function jakForgotpassword($email, $time) {
	
			global $jakdb;
			if ($jakdb->has("user", ["AND" => ["email" => $email, "access" => 1]])) {
				if ($time != 0) $jakdb->update("user", ["forgot" => $time], ["email" => $email]);
			    return true;
			} else {
			    return false;
			}
			
	}
	
	public static function jakForgotactive($forgotid) {
	
			global $jakdb;
			if ($jakdb->has("user", ["AND" => ["forgot" => $forgotid, "access" => 1]])) {
			    return true;
			} else
			    return false;
			
	}
	
	public static function jakForgotcheckuser($email, $forgotid) {
	
			global $jakdb;
			if ($jakdb->has("user", ["AND" => ["email" => $email, "forgot" => $forgotid, "access" => 1]])) {
			    return true;
			} else
			    return false;
			
	}
	
	public static function jakLogout($userid) {
	
			global $jakdb;

			// Delete cookies from this page
			JAK_base::jakCookie('jak_lcp_cookname', '', -JAK_COOKIE_TIME, JAK_COOKIE_PATH);
			JAK_base::jakCookie('jak_lcp_cookid', '', - JAK_COOKIE_TIME, JAK_COOKIE_PATH);
			
			// Update Database to session NULL
			$jakdb->update("user", ["session" => $jakdb->raw("NULL"), "idhash" => $jakdb->raw("NULL"), "available" => 0], ["id" => $userid]);
			
			// Unset the main sessions
			unset($_SESSION['jak_lcp_username']);
			unset($_SESSION['jak_lcp_idhash']);
			unset($_SESSION['jak_lcp_lang']);
			
			// Destroy session and generate new one for that user
			session_destroy();

			// Start the new session
			if (version_compare(PHP_VERSION, '7.3', '<=')) session_set_cookie_params(JAK_COOKIE_TIME, JAK_COOKIE_PATH.'; samesite=None', $_SERVER['HTTP_HOST'], true, false);
			session_start();
			session_regenerate_id();
			
	}

	public static function jakLogoutRest($userid) {
	
			global $jakdb;
			
			// Update Database to session NULL
			$jakdb->update("user", ["session" => $jakdb->raw("NULL"), "idhash" => $jakdb->raw("NULL"), "available" => 0], ["id" => $userid]);
			
	}
	
	public static function generateRandStr($length) {
	   $randstr = "";
	   for($i=0; $i<$length; $i++){
	      $randnum = mt_rand(0,61);
	      if($randnum < 10){
	         $randstr .= chr($randnum+48);
	      }else if($randnum < 36){
	         $randstr .= chr($randnum+55);
	      }else{
	         $randstr .= chr($randnum+61);
	      }
	   }
	   return $randstr;
	}
	
	private static function generateRandID() {
	   return md5(JAK_userlogin::generateRandStr(16));
	}
}
?>