<?php

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 5.0.5                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2022 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

// Check if the file is accessed only via index.php if not stop the script from running
if (!defined('JAK_ADMIN_PREVENT_ACCESS')) die('You cannot access this file directly.');

// Check if the user has access to this file
if (!jak_get_access("settings", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS) && !jak_get_access("blocklist", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)) jak_redirect(BASE_URL);

//Import the PHPMailer class into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
// use PHPMailer\PHPMailer\OAuth;
//Alias the League Google OAuth2 provider class
// use League\OAuth2\Client\Provider\Google;

// All the tables we need for this plugin
$errors = $success = array();
$ss = false;

// Reset some vars
$totalAll = $totalChange = $totalFiles = $totalEntries = $totalSMTP = $totalMAIL = 0;

// Get the total settings
$totalAll = $jakdb->count("settings");
// Get the total settings
$totalAllD = $jakdb->count("departments");

switch ($page1) {
    case 'email':
        // Let's go on with the script
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $jkp = $_POST;

        if (isset($jkp['save'])) {

            if (jak_get_access("settings", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)) {

                // Update the fields
                $jakdb->update("settings", ["used_value" => $jkp['jak_smpt']], ["varname" => "smtp_mail"]);
                $jakdb->update("settings", ["used_value" => $jkp['jak_smtpsender']], ["varname" => "smtp_sender"]);
                $jakdb->update("settings", ["used_value" => $jkp['jak_host']], ["varname" => "smtphost"]);
                $jakdb->update("settings", ["used_value" => $jkp['jak_port']], ["varname" => "smtpport"]);
                $jakdb->update("settings", ["used_value" => $jkp['jak_alive']], ["varname" => "smtp_alive"]);
                $jakdb->update("settings", ["used_value" => $jkp['jak_auth']], ["varname" => "smtp_auth"]);
                $jakdb->update("settings", ["used_value" => $jkp['jak_prefix']], ["varname" => "smtp_prefix"]);
                $jakdb->update("settings", ["used_value" => $jkp['jak_smtpusername']], ["varname" => "smtpusername"]);
                $jakdb->update("settings", ["used_value" => $jkp['jak_smtppassword']], ["varname" => "smtppassword"]);

                    // Now let us delete the define cache file
                $cachedefinefile = APP_PATH.JAK_CACHE_DIRECTORY.'/define.php';
                if (file_exists($cachedefinefile)) {
                    unlink($cachedefinefile);
                }

                // Write the log file each time someone login after to show success
                JAK_base::jakWhatslog('', JAK_USERID, 0, 42, 0, (isset($_COOKIE['WIOgeoData']) ? $_COOKIE['WIOgeoData'] : ''), $jakuser->getVar("username"), $_SERVER['REQUEST_URI'], $ipa, $valid_agent);

                $_SESSION["successmsg"] = $jkl['g14'];
                jak_redirect($_SESSION['LCRedirect']);

            }

        } else {

                $mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch

                // Send email the smpt way or else the mail way
                if (JAK_SMTP_MAIL) {

                    try {
                        $mail->IsSMTP(); // telling the class to use SMTP
                        $mail->Host = JAK_SMTPHOST;
                        $mail->SMTPAuth = (JAK_SMTP_AUTH ? true : false); // enable SMTP authentication
                        $mail->SMTPSecure = JAK_SMTP_PREFIX; // sets the prefix to the server
                        $mail->SMTPAutoTLS = false;
                        $mail->SMTPKeepAlive = (JAK_SMTP_ALIVE ? true : false); // SMTP connection will not close after each email sent
                        $mail->Port = JAK_SMTPPORT; // set the SMTP port for the GMAIL server
                        $mail->Username = JAK_SMTPUSERNAME; // SMTP account username
                        $mail->Password = JAK_SMTPPASSWORD;        // SMTP account password
                        $mail->SetFrom(JAK_SMTP_SENDER);
                        $mail->AddReplyTo(JAK_EMAIL);
                        $mail->AddAddress(JAK_EMAIL);
                        $mail->AltBody = $jkl["g215"]; // optional, comment out and test
                        $mail->Subject = $jkl["g216"];
                        $mail->MsgHTML($jkl["g217"].'SMTP.');
                        $mail->Send();
                        $success['e'] = $jkl["g217"].'SMTP.';
                    } catch (phpmailerException $e) {
                        $errors['e'] = $e->errorMessage(); //Pretty error messages from PHPMailer
                    } catch (Exception $e) {
                        $errors['e'] = $e->getMessage(); //Boring error messages from anything else!
                    }

                    // Write the log file each time someone login after to show success
                    JAK_base::jakWhatslog('', JAK_USERID, 0, 48, 0, (isset($_COOKIE['WIOgeoData']) ? $_COOKIE['WIOgeoData'] : ''), $jakuser->getVar("username"), $_SERVER['REQUEST_URI'], $ipa, $valid_agent);
                    
                } else {

                    try {
                        $mail->SetFrom(JAK_SMTP_SENDER);
                        $mail->AddReplyTo(JAK_EMAIL);
                        $mail->AddAddress(JAK_EMAIL);
                        $mail->AltBody = $jkl["g215"]; // optional, comment out and test
                        $mail->Subject = $jkl["g216"];
                        $mail->MsgHTML($jkl["g217"].'Mail().');
                        $mail->Send();
                        $success['e'] = $jkl["g217"].'Mail().';
                    } catch (phpmailerException $e) {
                        $errors['e'] = $e->errorMessage(); //Pretty error messages from PHPMailer
                    } catch (Exception $e) {
                        $errors['e'] = $e->getMessage(); //Boring error messages from anything else!
                    }

                    // Write the log file each time someone login after to show success
                    JAK_base::jakWhatslog('', JAK_USERID, 0, 47, 0, (isset($_COOKIE['WIOgeoData']) ? $_COOKIE['WIOgeoData'] : ''), $jakuser->getVar("username"), $_SERVER['REQUEST_URI'], $ipa, $valid_agent);

                }
            }
        }

        // How often has it been changed
        $totalChange = $jakdb->count("whatslog", ["whatsid" => 43]);

        // How often has it been tested
        $totalSMTP = $jakdb->count("whatslog", ["whatsid" => 47]);

        // How often has it been tested
        $totalMAIL = $jakdb->count("whatslog", ["whatsid" => 48]);

        // Title and Description
        $SECTION_TITLE = $jkl["m35"];
        $SECTION_DESC = "";

        // Include the javascript file for results
        $js_file_footer = 'js_email.php';

        // Call the template
        $template = 'emailsetting.php';
        break;

        default:

// Let's go on with the script
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jkp = $_POST;
    
    if (isset($jkp['save'])) {

        if (jak_get_access("settings", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)) {
    
            if ($jkp['jak_email'] == '' || !filter_var($jkp['jak_email'], FILTER_VALIDATE_EMAIL)) { 
            	$errors['e1'] = $jkl['e3'];
            }
            
            if ($jkp['jak_lang'] == '') { $errors['e6'] = $jkl['e29']; }

            if (count($errors) == 0) {

                // Update the fields
                $jakdb->update("settings", ["used_value" => $jkp['jak_title']], ["varname" => "title"]);
                $jakdb->update("settings", ["used_value" => $jkp['jak_email']], ["varname" => "email"]);
                $jakdb->update("settings", ["used_value" => $jkp['jak_emailcc']], ["varname" => "emailcc"]);
                $jakdb->update("settings", ["used_value" => $jkp['jak_holidaym']], ["varname" => "holiday_mode"]);
                $jakdb->update("settings", ["used_value" => $jkp['jak_trans']], ["varname" => "send_tscript"]);
                $jakdb->update("settings", ["used_value" => $jkp['jak_rating']], ["varname" => "crating"]);
                $jakdb->update("settings", ["used_value" => $jkp['jak_showip']], ["varname" => "show_ips"]);
                $jakdb->update("settings", ["used_value" => $jkp['jak_lang']], ["varname" => "lang"]);
                $jakdb->update("settings", ["used_value" => $jkp['jak_avatwidth']], ["varname" => "useravatwidth"]);
                $jakdb->update("settings", ["used_value" => $jkp['jak_avatheight']], ["varname" => "useravatheight"]);
                $jakdb->update("settings", ["used_value" => $jkp['allowed_files']], ["varname" => "allowed_files"]);
                $jakdb->update("settings", ["used_value" => $jkp['allowedo_files']], ["varname" => "allowedo_files"]);
                $jakdb->update("settings", ["used_value" => $jkp['jak_date']], ["varname" => "dateformat"]);
                $jakdb->update("settings", ["used_value" => $jkp['jak_time']], ["varname" => "timeformat"]);
                $jakdb->update("settings", ["used_value" => $jkp['jak_timezone_server']], ["varname" => "timezoneserver"]);
                $jakdb->update("settings", ["used_value" => $jkp['jak_ringtone']], ["varname" => "ring_tone"]);
                $jakdb->update("settings", ["used_value" => $jkp['jak_msgtone']], ["varname" => "msg_tone"]);
                $jakdb->update("settings", ["used_value" => $jkp['showalert']], ["varname" => "pro_alert"]);
                $jakdb->update("settings", ["used_value" => $jkp['jak_proactive_time']], ["varname" => "proactive_time"]);
                $jakdb->update("settings", ["used_value" => $jkp['jak_user_expired']], ["varname" => "client_expired"]);
                $jakdb->update("settings", ["used_value" => $jkp['jak_user_left']], ["varname" => "client_left"]);
                $jakdb->update("settings", ["used_value" => $jkp['jak_pushrem']], ["varname" => "push_reminder"]);
                $jakdb->update("settings", ["used_value" => $jkp['ip_block']], ["varname" => "ip_block"]);
                $jakdb->update("settings", ["used_value" => $jkp['email_block']], ["varname" => "email_block"]);
                $jakdb->update("settings", ["used_value" => $jkp['jak_twilio_nexmo']], ["varname" => "twilio_nexmo"]);
                $jakdb->update("settings", ["used_value" => $jkp['jak_tw_phone']], ["varname" => "tw_phone"]);
                $jakdb->update("settings", ["used_value" => $jkp['jak_tw_msg']], ["varname" => "tw_msg"]);
                $jakdb->update("settings", ["used_value" => $jkp['jak_tw_sid']], ["varname" => "tw_sid"]);
                $jakdb->update("settings", ["used_value" => $jkp['jak_tw_token']], ["varname" => "tw_token"]);
                $jakdb->update("settings", ["used_value" => $jkp['jak_openop']], ["varname" => "openop"]);
                $jakdb->update("settings", ["used_value" => trim($jkp['jak_nativtok'])], ["varname" => "native_app_token"]);
                $jakdb->update("settings", ["used_value" => trim($jkp['jak_nativkey'])], ["varname" => "native_app_key"]);
                $jakdb->update("settings", ["used_value" => $jkp['jak_client_push_not']], ["varname" => "client_push_not"]);
                $jakdb->update("settings", ["used_value" => $jkp['jak_engage_sound']], ["varname" => "engage_sound"]);
                $jakdb->update("settings", ["used_value" => $jkp['jak_engage_icon']], ["varname" => "engage_icon"]);
                $jakdb->update("settings", ["used_value" => $jkp['jak_client_sound']], ["varname" => "client_sound"]);
                $jakdb->update("settings", ["used_value" => $jkp['jak_live_online_status']], ["varname" => "live_online_status"]);
                $jakdb->update("settings", ["used_value" => $jkp['jak_chat_upload_standard']], ["varname" => "chat_upload_standard"]);

                $ss = true;

            } else {
    
                $errors['e'] = $jkl['e'];
                $errors = $errors;
            }


        } elseif (jak_get_access("blocklist", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)) {
            $jakdb->update("settings", ["used_value" => $jkp['ip_block']], ["varname" => "ip_block"]);
            $jakdb->update("settings", ["used_value" => $jkp['email_block']], ["varname" => "email_block"]);
            $ss = true;
        }

        if ($ss) {
    		
            // Now let us delete the define cache file
            $cachedefinefile = APP_PATH.JAK_CACHE_DIRECTORY.'/define.php';
            if (file_exists($cachedefinefile)) {
                unlink($cachedefinefile);
            }

            // Write the log file each time someone login after to show success
            JAK_base::jakWhatslog('', JAK_USERID, 0, 16, 0, (isset($_COOKIE['WIOgeoData']) ? $_COOKIE['WIOgeoData'] : ''), $jakuser->getVar("username"), $_SERVER['REQUEST_URI'], $ipa, $valid_agent);

            $_SESSION["successmsg"] = $jkl['g14'];
            jak_redirect($_SESSION['LCRedirect']);

        } else {

            $_SESSION["infomsg"] = $jkl['i'];
            jak_redirect($_SESSION['LCRedirect']);

        }
    
    }
    
}

// Call the settings function
$lang_files = jak_get_lang_files();

// Get all sound files
$sound_files = jak_get_sound_files();

// How often has it been changed
$totalChange = $jakdb->count("whatslog", ["whatsid" => 16]);

// Count all files
$totalFiles = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(APP_PATH.JAK_FILES_DIRECTORY), RecursiveIteratorIterator::SELF_FIRST);

// Title and Description
$SECTION_TITLE = $jkl["m5"];
$SECTION_DESC = "";

// Include the javascript file for results
$js_file_footer = 'js_settings.php';

// Call the template
if (jak_get_access("settings", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)) {
    $template = 'setting.php';
} else {
    $template = 'blockvisitors.php';
}

}

?>