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

// Check if the user has access to this file
if (!jak_get_access("leads", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)) jak_redirect(BASE_URL);

// All the tables we need for this plugin
$errors = array();
$jaktable = 'sessions';
$jaktable1 = 'transcript';
$jaktable2 = 'clientcontact';
$jaktable3 = 'checkstatus';
$jaktable4 = 'transfer';

switch ($page1) {
  	case 'readleads':
  	
  		$rowi = $jakdb->get($jaktable, ["name", "operatorname", "email", "phone", "initiated", "ended"], ["id" => $page2]);
  	
  		// Let's go on with the script
  		if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email_conv'])) {
  		    $jkp = $_POST;
  		    
  		    // Errors in Array
  		    $errors = array();
  		    
  		    if ($jkp['email'] == '' || !filter_var($jkp['email'], FILTER_VALIDATE_EMAIL)) {
  		        $errors['email'] = $jkl['e3'];
  		    }
  		    
  		    if (count($errors) > 0) {
  		    
  		    /* Outputtng the error messages */
  		    	if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
  		    	
  		    		header('Cache-Control: no-cache');
  		    		die('{"status":0, "errors":'.json_encode($errors).'}');
  		    		
  		    	} else {
  		    		$errors = $errors;
  		    	}
  		    	
  		    } else {
  		    
  		    	$result = $jakdb->select($jaktable1, "*", ["convid" => $page2, "ORDER" => ["id" => "ASC"]]);
  		    	
  		    	$subject = $jkl["g57"].' '.$jkp['cagent'].' '.$jkl["g58"].' '.$jkp['cuser'];
  		    	
  		    	$mailchat = '<div style="margin:10px 0px 0px 0px;padding:10px;border:1px solid #A8B9CB;font-family: Verdana, sans-serif;font-size: 13px;
  		    	font-weight: 500;letter-spacing: normal;line-height: 1.5em;"><p>'.$subject.'</p><p>'.$jkl["u"].': '.$rowi['name'].' / '.$jkl["u1"].': '.$rowi['email'].' / '.$jkl["u14"].': '.$rowi['phone'].'</p><ul style="list-style:none;">';
  		    	
  		    	foreach ($result as $row) {
  		    	 // collect each record into $_data
  		    	 if ($row['class'] == "notice") {
  		    	   $mailchat .= '<li style="background-color:#d0e5f9;padding:5px;"><span style="font-size:10px;">'.$row['name'].' '.$jkl['g66'].':</span><br />'.$row['message'].'</li>';
  		    	 } else if ($row['class'] == "admin") {
  		    	   $mailchat .= '<li style="background-color:#effcff;padding:5px;"><span style="font-size:10px;">'.$row['time'].' - '.$row['name']." ".$jkl['g66'].':</span><br />'.$row['message'].'</li>';
  		    	 } else {
  		    	   $mailchat .= '<li style="background-color:#f4fdf1;padding:5px;"><span style="font-size:10px;">'.$row['name'].' '.$jkl['g66'].':</span><br />'.$row['message'].'</li>';
  		    	 }
  		    	}
  		    	    
  		    	$mailchat .= '</ul></div>';

            // Ok, we send the email // email address, cc email address, reply to, subject, message, attachment
            if (jak_send_email($jkp['email'], "", "", $subject, $mailchat, "")) {
  		    	
    		    	// Ajax Request
    		    	if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    		    	
    		    		header('Cache-Control: no-cache');
    		    		die(json_encode(array('status' => 1, 'html' => $jkl["g14"])));
    		    		
    		    	} else {
    		    	
    		            jak_redirect($_SERVER['HTTP_REFERER']);
    		        
    		      }
  		      }
  		    }
  	   }
  		
  		$CONVERSATION_LS = $jakdb->select($jaktable1, "*", ["convid" => $page2, "ORDER" => ["id" => "ASC"]]);
  		
  	  // Call the template
  	  $template = 'readleads.php';
  	    
  	break;
  	case 'clientcontact':
  	
  		// Let's go on with the script
  		if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_email'])) {
  		    $jkp = $_POST;
  		    
  		    // Errors in Array
  		    $errors = array();

          if ($jkp['email'] == '' || !filter_var($jkp['email'], FILTER_VALIDATE_EMAIL)) {
              $errors['email'] = $jkl['e3'];
          }
  		    
  		    if (empty($jkp['subject']) || strlen(trim($jkp['subject'])) <= 2) {
  		        $errors['subject'] = $jkl['e17'];
  		    }
  		    
  		    if (empty($jkp['message']) || strlen(trim($jkp['message'])) <= 2) {
  		        $errors['message'] = $jkl['e1'];
  		    }
  		    
  		    if (count($errors) > 0) {
  		    
  		    /* Outputtng the error messages */
  		    	if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
  		    	
  		    		header('Cache-Control: no-cache');
  		    		die('{"status":0, "errors":'.json_encode($errors).'}');
  		    		
  		    	} else {
  		    		$errors = $errors;
  		    	}
  		    	
  		    } else {

            // Ok, we send the email // email address, cc email address, reply to, subject, message, attachment
            if (jak_send_email($jkp['email'], "", "", trim($jkp['subject']), trim(nl2br($jkp['message'])), "")) {
  		    
    		    	// Insert the stuff into the database
    		    	$jakdb->insert($jaktable2, [
    		    	"sessionid" => $page2,
    		    	"operatorid" => JAK_USERID,
    		    	"operatorname" => $jakuser->getVar("username"),
    		    	"subject" => trim($jkp['subject']),
    		    	"message" => trim($jkp['message']),
    		    	"sent" => $jakdb->raw("NOW()")]);
    		    	
    		    	// Ajax Request
    		    	if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    		    	
    		    		header('Cache-Control: no-cache');
    		    		die(json_encode(array('status' => 1, 'html' => $jkl["g14"])));
    		    		
    		    	} else {
    		    	
    		        jak_redirect($_SERVER['HTTP_REFERER']);
    		        
    		      }
  		      }
  		    }
  		}
  		
      // Ouput all messages, well with paginate of course
      $MESSAGES_ALL = $jakdb->select($jaktable2, ["id", "operatorname", "subject", "message", "sent"], ["sessionid" => $page2]);
  		    
  	  $rowi = $jakdb->get($jaktable, ["name", "email"], ["id" => $page2]);
  		
  	  // Call the template
  	  $template = 'clientcontact.php';
  	    
  	break;
  	case 'truncate':
  	
  		if (!JAK_USERID || !JAK_SUPERADMINACCESS) jak_redirect(BASE_URL);
  		
  		$jakdb->query('TRUNCATE '.JAKDB_PREFIX.$jaktable1);
      $jakdb->query('TRUNCATE '.JAKDB_PREFIX.$jaktable3);
      $jakdb->query('TRUNCATE '.JAKDB_PREFIX.$jaktable4);
  	  $result = $jakdb->query('TRUNCATE '.JAKDB_PREFIX.$jaktable);
  	    
	  	if (!$result) {
	  		$_SESSION["infomsg"] = $jkl['i'];
		    jak_redirect(JAK_rewrite::jakParseurl('leads'));
	  	} else {
	  	    $_SESSION["successmsg"] = $jkl['g14'];
		    jak_redirect(JAK_rewrite::jakParseurl('leads'));
	  	}
	  	 
  break;
	default:

    // Let's go on with the script
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $jkp = $_POST;
        
        if (isset($jkp['action']) && $jkp['action'] == "delete") {

        if (!JAK_USERID || !JAK_SUPERADMINACCESS) jak_redirect(BASE_URL);
        
          if (isset($jkp['jak_delete_leads'])) {
            
            $lockuser = $jkp['jak_delete_leads'];
      
              for ($i = 0; $i < count($lockuser); $i++) {
                  $locked = $lockuser[$i];

                  $convid = $jakdb->get($jaktable, "id", ["id" => $locked]);
                  $jakdb->delete($jaktable1, ["convid" => $convid]);
                  $jakdb->delete($jaktable3, ["convid" => $convid]);
                  $jakdb->delete($jaktable4, ["convid" => $convid]);
                  $result = $jakdb->delete($jaktable, ["id" => $locked]);
                
              }
              
              $_SESSION["successmsg"] = $jkl['g14'];
              jak_redirect($_SESSION['LCRedirect']);
          }
      
          $_SESSION["errormsg"] = $jkl['i3'];
          jak_redirect($_SESSION['LCRedirect']);
        
        }     
    }
		
		// Leads
		$total_support = $totalAll = $total_ended = $total_initated = $totalAllc = $bounce_percentage = 0;

    // Get the totals
    if (jak_get_access("leads_all", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)) {
      $totalAll = $jakdb->count($jaktable);
    } else {
      if (is_numeric($jakuser->getVar("departments")) && $jakuser->getVar("departments") != 0) {

        $totalAll = $jakdb->count($jaktable, ["OR" => ["operatorid" => JAK_USERID, "department" => $jakuser->getVar("departments")]]);

      } elseif (!((boolean)$jakuser->getVar("departments")) && $jakuser->getVar("departments") != 0) {

        $totalAll = $jakdb->count($jaktable, ["OR" => ["operatorid" => JAK_USERID, "department" => [$jakuser->getVar("departments")]]]);

      } else {
        $totalAll = $jakdb->count($jaktable, ["operatorid" => JAK_USERID]);
      }

    }

    // contact available
    if (jak_get_access("leads_all", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)) {
      $totalAllc = $jakdb->count($jaktable, ["fcontact" => 1]);
    } else {
      if (is_numeric($jakuser->getVar("departments")) && $jakuser->getVar("departments") != 0) {

        $totalAllc = $jakdb->count($jaktable, ["AND" => ["OR" => ["operatorid" => JAK_USERID, "department" => $jakuser->getVar("departments")], "fcontact" => 1]]);

      } elseif (!((boolean)$jakuser->getVar("departments")) && $jakuser->getVar("departments") != 0) {

        $totalAllc = $jakdb->count($jaktable, ["AND" => ["OR" => ["operatorid" => JAK_USERID, "department" => [$jakuser->getVar("departments")]], "fcontact" => 1]]);

      } else {
        $totalAllc = $jakdb->count($jaktable, ["AND" => ["operatorid" => JAK_USERID, "fcontact" => 1]]);
      }

    }
		
		// Get percentage
		$bounce_percentage = 0;
		if ($totalAllc && $totalAll) $bounce_percentage = round($totalAllc / $totalAll * 100, 2, PHP_ROUND_HALF_UP);

    if (jak_get_access("leads_all", $jakuser->getVar("permissions"), JAK_SUPERADMINACCESS)) {
      $total_ended = $jakdb->sum($jaktable, "ended", ["AND" => ["ended[!]" => 0, "initiated[!]" => 0]]);
      $total_initated = $jakdb->sum($jaktable, "initiated", ["AND" => ["ended[!]" => 0, "initiated[!]" => 0]]);
    } else {

      if (is_numeric($jakuser->getVar("departments")) && $jakuser->getVar("departments") != 0) {

        $total_ended = $jakdb->sum($jaktable, "ended", ["AND" => ["OR" => ["operatorid" => JAK_USERID, "department" => $jakuser->getVar("departments")], "ended[!]" => 0, "initiated[!]" => 0]]);
        $total_initated = $jakdb->sum($jaktable, "initiated", ["AND" => ["OR" => ["operatorid" => JAK_USERID, "department" => $jakuser->getVar("departments")], "ended[!]" => 0, "initiated[!]" => 0]]);

      } elseif (!((boolean)$jakuser->getVar("departments")) && $jakuser->getVar("departments") != 0) {

        $total_ended = $jakdb->sum($jaktable, "ended", ["AND" => ["OR" => ["operatorid" => JAK_USERID, "department" => [$jakuser->getVar("departments")]], "ended[!]" => 0, "initiated[!]" => 0]]);
        $total_initated = $jakdb->sum($jaktable, "initiated", ["AND" => ["OR" => ["operatorid" => JAK_USERID, "department" => [$jakuser->getVar("departments")]], "ended[!]" => 0, "initiated[!]" => 0]]);

      } else {

        $total_ended = $jakdb->sum($jaktable, "ended", ["AND" => ["operatorid" => JAK_USERID, "ended[!]" => 0, "initiated[!]" => 0]]);
        $total_initated = $jakdb->sum($jaktable, "initiated", ["AND" => ["operatorid" => JAK_USERID, "ended[!]" => 0, "initiated[!]" => 0]]);

      }

    }

    $total_support = $total_ended - $total_initated;

    // Check and validate
    $verify_response = $jaklic->verify_license(true);
    if ($verify_response['status'] != true) {
        if (JAK_SUPERADMINACCESS) {
            jak_redirect(JAK_rewrite::jakParseurl('maintenance'));
        } else {
            $_SESSION["errormsg"] = $jkl['e27'];
            jak_redirect(BASE_URL);
        }
    }
		
		// Title and Description
		$SECTION_TITLE = $jkl["m1"];
		$SECTION_DESC = "";
		
		// Include the javascript file for results
		$js_file_footer = 'js_leads.php';
		
		// Call the template
		$template = 'leads.php';
}
?>