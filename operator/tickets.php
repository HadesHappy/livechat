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
if (!JAK_ADMINACCESS) jak_redirect(BASE_URL);

// Change for 1.0.3
use JAKWEB\JAKsql;

// All the tables we need for this plugin
$errors = array();
$jaktable = 'support_tickets';

if (!empty(JAKDB_MAIN_NAME) && JAK_MAIN_LOC && JAK_MAIN_OP) {
	
	// Database connection to the main site
	$jakdb1 = new JAKsql([
		// required
		'database_type' => JAKDB_MAIN_DBTYPE,
		'database_name' => JAKDB_MAIN_NAME,
		'server' => JAKDB_MAIN_HOST,
		'username' => JAKDB_MAIN_USER,
		'password' => JAKDB_MAIN_PASS,
		'charset' => 'utf8',
		'port' => JAKDB_MAIN_PORT,
		'prefix' => JAKDB_MAIN_PREFIX,
			         
		// [optional] driver_option for connection, read more from http://www.php.net/manual/en/pdo.setattribute.php
		'option' => [PDO::ATTR_CASE => PDO::CASE_NATURAL]
		]);

	// Get all answers
	$totalAll = $jakdb1->count($jaktable, "id", ["AND" => ["opid" => JAK_USERID, "status[!]" => 2]]);
		 
	if ($totalAll != 0) {

		// Paginator
		$tickets = new JAK_Paginator;
		$tickets->items_total = $totalAll;
		$tickets->mid_range = 10;
		$tickets->items_per_page = 20;
		$tickets->jak_get_page = $page1;
		$tickets->jak_where = JAK_rewrite::jakParseurl('tickets');
		$tickets->paginate();
		$JAK_PAGINATE = $tickets->display_pages();

		// Ouput all tickets, well with paginate of course	
		$TICKETS_ALL = $jakdb1->select($jaktable, "*", ["AND" => ["opid" => JAK_USERID, "status[!]" => 2], "ORDER" => ["sent" => "DESC"], "LIMIT" => $tickets->limit]);
				
	}

	// Ok, we need some stuff from the settings table
	$sett = array();
	$settings = $jakdb1->select("settings", ["varname", "used_value"]);
	foreach ($settings as $v) {
	    $sett[$v["varname"]] = $v["used_value"]; 
	}

	// Get all admins
	$admins = array();
	$admins = $jakdb1->select("admins", ["name", "email"], ["access" => 1]);

	// Now start with the plugin use a switch to access all pages
	switch ($page1) {

		case 'r':

			if (isset($page2) && is_numeric($page2) && $jakdb1->has($jaktable, ["id" => $page2])) {

				// Now get the user information
		        $ticket = $jakdb1->get($jaktable, ["id", "subject", "content"], ["id" => $page2]);

		        // Mark message as read
		        $jakdb1->update($jaktable, ["readtime" => time()], ["id" => $page2]);

		        // Title and Description
				$SECTION_TITLE = $jkl['g318'];
				$SECTION_DESC = '';

				// Form title
				$FORM_TITLE = $ticket["subject"];

				// Include the javascript file for results
				$js_file_footer = 'js_pages.php';

				// Call the template
				$template = 'tickets.php';

			} else {
				// No database information
				$_SESSION["errormsg"] = $jkl['i2'];
				jak_redirect(JAK_rewrite::jakParseurl('tickets'));
			}

		break;
		case 'a':

			if (isset($page2) && is_numeric($page2) && $jakdb1->has($jaktable, ["id" => $page2])) {

				$usr = $jakdb1->get("users", ["id", "username"], ["AND" => ["locationid" => JAK_MAIN_LOC, "opid" => JAK_USERID]]);

				if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				    $jkp = $_POST;
				    
				    if (isset($jkp['create_ticket'])) {

				    	if (empty($jkp['subject'])) {
						    $errors['e'] = $jkl['e2'];
						}
				    
				        if ($jkp['content'] == '') { 
				        	$errors['e1'] = $jkl['e1'];
				        }

				        if (count($errors) == 0) {

				        	$timenow = time();
					        			
					        // Insert the news for user(s)
							$jakdb1->insert($jaktable, ["userid" => $usr["id"],
								"opid" => JAK_USERID,
								"ticketid" => $page2,
								"username" => $usr["username"],
								"subject" => trim($jkp['subject']),
								"content" => trim($jkp['content']),
								"isnews" => 0,
								"status" => 3,
								"readtime" => $timenow,
								"sent" => $timenow]);

							// Set status from old ticket as solved
							$jakdb1->update($jaktable, ["status" => 2], ["id" => $page2]);

							// We start the email notifications
							$mail = new PHPMailer(); // defaults to using php "mail()" or optional SMTP

							// Get the email template
							$nlhtml = file_get_contents(APP_PATH.'email/index.html');

					    	// We inform the site owner
					    	if ($sett["smtp"] == 1) {

								$mail->IsSMTP(); // telling the class to use SMTP
								$mail->Host = $sett["smtphost"];
								$mail->SMTPAuth = ($sett["smtpauth"] ? true : false); // enable SMTP authentication
								$mail->SMTPSecure = $sett["smtpprefix"]; // sets the prefix to the server
								$mail->SMTPKeepAlive = ($sett["smtpalive"] ? true : false); // SMTP connection will not close after each email sent
								$mail->Port = $sett["smtpport"]; // set the SMTP port for the GMAIL server
								$mail->Username = $sett["smtpusername"]; // SMTP account username
								$mail->Password = $sett["smtppass"];        // SMTP account password
																
							}

							$mail->SetFrom($sett["emailaddress"]);

							// Send email to all admins
							if (isset($admins)) foreach ($admins as $ad) {
								# code...
								$mail->AddAddress($ad["email"]);
							}

							// Say Hello
							$webtext = '<h1>'.$sett["newtickettitle"].'</h1>';

							// Send the operator url
							$webtext .= sprintf($sett["newticketmsg"], $usr["username"]);

							// Get the link to the dashboard
							$webtext .= '<p><a href="'.jak_redirect(JAK_rewrite::jakParseurl('tickets')).'">'.$jkl['m31'].'</a></p>';
									
							// Change fake vars into real ones.
							$cssAtt = array('{emailcontent}', '{weburl}');
							$cssUrl   = array($webtext, $sett["webaddress"]);
							$nlcontent = str_replace($cssAtt, $cssUrl, $nlhtml);
								
							$body = str_ireplace("[\]", "", $nlcontent);
															
							$mail->Subject = $sett["newtickettitle"];
							$mail->MsgHTML($body);
							$mail->Send();

						    $_SESSION["successmsg"] = $jkl['i7'];
					        jak_redirect(JAK_rewrite::jakParseurl('tickets'));

					    } else {
					        $errors = $errors;
					    }

				    }
				}

				// Now get the user information
		        $ticket = $jakdb1->get($jaktable, ["id", "subject", "content", "sent"], ["id" => $page2]);

		        // Mark message as read
		        $jakdb1->update($jaktable, ["readtime" => time()], ["id" => $page2]);

		        // Title and Description
				$SECTION_TITLE = $jkl['g323'];
				$SECTION_DESC = '';

				// Form title
				$FORM_TITLE = $jkl["g323"];

				// Include the javascript file for results
				$js_file_footer = 'js_pages.php';

				// Call the template
				$template = 'tickets.php';

			} else {
				// No database information
				$_SESSION["errormsg"] = $jkl['i2'];
				jak_redirect(JAK_rewrite::jakParseurl('tickets'));
			}

		break;
		default:

			if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_ticket'])) {
				$jkp = $_POST;
				    
				if (empty($jkp['subject'])) {
				    $errors['e'] = $jkl['e2'];
				}
				        
				if (empty($jkp['content'])) {
				    $errors['e1'] = $jkl['e1'];
				}		        
				        
				if (count($errors) == 0) {

					$usr = $jakdb1->get("users", ["id", "username"], ["AND" => ["locationid" => JAK_MAIN_LOC, "opid" => JAK_USERID]]);

					// Insert the news for user(s)
					$result = $jakdb1->insert($jaktable, ["userid" => $usr["id"],
						"opid" => JAK_USERID,
						"username" => $usr["username"],
						"subject" => trim($jkp['subject']),
						"content" => trim($jkp['content']),
						"isnews" => 0,
						"status" => 3,
						"sent" => time()]);
				    
				    if (!$result) {
				    	$_SESSION["infomsg"] = $jkl['i'];
				    	jak_redirect($_SESSION['LCRedirect']);
				    } else {

				    	// We start the email notifications
						$mail = new PHPMailer(); // defaults to using php "mail()" or optional SMTP

				    	// Get the email template
						$nlhtml = file_get_contents(APP_PATH.'email/index.html');

				    	// We inform the site owner
				    	if ($sett["smtp"] == 1) {

							$mail->IsSMTP(); // telling the class to use SMTP
							$mail->Host = $sett["smtphost"];
							$mail->SMTPAuth = ($sett["smtpauth"] ? true : false); // enable SMTP authentication
							$mail->SMTPSecure = $sett["smtpprefix"]; // sets the prefix to the server
							$mail->SMTPKeepAlive = ($sett["smtpalive"] ? true : false); // SMTP connection will not close after each email sent
							$mail->Port = $sett["smtpport"]; // set the SMTP port for the GMAIL server
							$mail->Username = $sett["smtpusername"]; // SMTP account username
							$mail->Password = $sett["smtppass"];        // SMTP account password
															
						}

						$mail->SetFrom($sett["emailaddress"]);

						// Send email to all admins
						if (isset($admins)) foreach ($admins as $ad) {
							# code...
							$mail->AddAddress($ad["email"]);
						}

						// Say Hello
						$webtext = '<h1>'.$sett["newtickettitle"].'</h1>';

						// Send the operator url
						$webtext .= sprintf($sett["newticketmsg"], $usr["username"]);

						// Get the link to the dashboard
						$webtext .= '<p><a href="'.jak_redirect(JAK_rewrite::jakParseurl('tickets')).'">'.$jkl['m31'].'</a></p>';
								
						// Change fake vars into real ones.
						$cssAtt = array('{emailcontent}', '{weburl}');
						$cssUrl   = array($webtext, $sett["webaddress"]);
						$nlcontent = str_replace($cssAtt, $cssUrl, $nlhtml);
							
						$body = str_ireplace("[\]", "", $nlcontent);
														
						$mail->Subject = $sett["newtickettitle"];
						$mail->MsgHTML($body);
						$mail->Send();
						
				    	$_SESSION["successmsg"] = $jkl['g14'];
				    	jak_redirect($_SESSION['LCRedirect']);
				    }
				    
				// Output the errors
				} else {
					$errors = $errors;
				}
		   
			}
				
			// Title and Description
			$SECTION_TITLE = $jkl["m31"];
			$SECTION_DESC = "";

			// Form title
			$FORM_TITLE = $jkl["g322"];
				
			// Include the javascript file for results
			$js_file_footer = 'js_pages.php';
				 
			// Call the template
			$template = 'tickets.php';

		}

} else {
	jak_redirect(BASE_URL);
}
?>