<?php

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 5.0.4                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2022 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

// Check if the file is accessed only via index.php if not stop the script from running
if (!defined('JAK_ADMIN_PREVENT_ACCESS')) die('You cannot access this file directly.');

// Login IN
if (!empty($_POST['action']) && $_POST['action'] == 'login') {
	
	$lcookies = false;
    $username = $_POST['username'];
    $userpass = $_POST['password'];
    if (isset($_POST['lcookies'])) $lcookies = $_POST['lcookies'];
    
    // Security fix
    $valid_agent = filter_var($_SERVER['HTTP_USER_AGENT'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $valid_ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);
    $valid_username = filter_var($username, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Write the log file each time someone tries to login before
    JAK_base::jakWhatslog('', 0, 0, 1, 0, (isset($_COOKIE['WIOgeoData']) ? $_COOKIE['WIOgeoData'] : ''), $valid_username, $_SERVER['REQUEST_URI'], $valid_ip, $valid_agent);

    $user_check = $jakuserlogin->jakCheckuserdata($valid_username, $userpass);
    if ($user_check == true) {
    
    	// Now login in the user
        $jakuserlogin->jakLogin($user_check["username"], $userpass, $lcookies);
        
        // Write the log file each time someone login after to show success
        JAK_base::jakWhatslog('', $user_check["id"], 0, 2, 0, '', $user_check["username"], '', $valid_ip, '');
        
        // Unset the recover message
        if (isset($_SESSION['password_recover'])) unset($_SESSION['password_recover']);

        if (isset($_SESSION['LCRedirect']) && strpos($_SESSION['LCRedirect'], JAK_OPERATOR_LOC) !== false) {
        	jak_redirect($_SESSION['LCRedirect']);
        } else {
        	jak_redirect(BASE_URL);
        }

    } else {
        $ErrLogin = $jkl['l'];
    }
}

// Forgot password
 if ($_SERVER["REQUEST_METHOD"] == 'POST' && isset($_POST['forgotP'])) {
 	$jkp = $_POST;
 	
 	$errors = array();
 
 	if ($jkp['lsE'] == '' || !filter_var($jkp['lsE'], FILTER_VALIDATE_EMAIL)) {
 	    $errors['e'] = $jkl['e19'];
 	}
 	
 	// transform user email
    $femail = filter_var($_POST['lsE'], FILTER_SANITIZE_EMAIL);
    $fwhen = time();
 	
 	// Check if this user exist
    $user_check = $jakuserlogin->jakForgotpassword($femail, $fwhen);
     
    if (!$user_check) {
        $errors['e'] = $jkl['e19'];
    }
     
     if (count($errors) == 0) {

        // Get user details
        $oname = $jakdb->get("user", "name", ["AND" => ["email" => $femail, "access" => 1]]);

        // Ok, we send the email // email address, cc email address, reply to, subject, message, attachment
        if (jak_send_email($femail, "", "", JAK_TITLE.' - '.$jkl['l13'], sprintf($jkl['l14'], $oname, '<a href="'.JAK_rewrite::jakParseurl('forgot-password', $fwhen).'">'.JAK_rewrite::jakParseurl('forgot-password', $fwhen).'</a>', JAK_TITLE), "")) {

            $_SESSION["infomsg"] = $jkl["l7"];
            jak_redirect(BASE_URL); 
        }
 
     } else {
         $errorfp = $errors;
     }
}

// Title and Description
$SECTION_TITLE = $jkl["l3"];
$SECTION_DESC = "";

// Include the javascript file for results
$js_file_footer = 'js_login.php';

$template = 'login.php';

?>