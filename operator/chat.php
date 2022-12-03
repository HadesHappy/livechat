<?php

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 3.2                   # ||
|| # ----------------------------------------- # ||
|| # Copyright 2018 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

// Check if the file is accessed only via index.php if not stop the script from running
if (!defined('JAK_ADMIN_PREVENT_ACCESS')) die('You cannot access this file directly.');

// Check if the user has access to this file
if (!JAK_USERID && ($jakuser->getVar("operatorchatpublic") != 1 || $jakuser->getVar("operatorchat") != 1)) jak_redirect(BASE_URL);

// Call the template
$template = 'chat.php';

?>