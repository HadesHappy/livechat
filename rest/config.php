<?php

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 5.0.5                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2022 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

/*
Error Code for use in Xcode or Android SDK returned via errorcode
	1 = User is not logged in
	2 = File format is incorrect
	3 = File is too big for the server
	4 = Username/Email or Password is wrong
	5 = Please enter a valid email address
	6 = This email address does not exist
	7 = Something went wrong please try again
    8 = No permissions
    9 = No data available
    10 = Chat has been taken already
    11 = Please enter a message
    12 = There is no rest api installed on this URL.

Error Arrays for use in Xcode or Android SDK, errors will return an array with the same input field names = true
	$errors = array("name" => true, "new_password" => true);

Device and Token for login.php
    Important to register or update for Push Notifications
    $_REQUEST['device'] (ios or android), $_REQUEST['token'] (device token)
*/

// Absolute Path
$app_path = dirname(__file__) . DIRECTORY_SEPARATOR;
define('APP_PATH', str_replace("rest".DIRECTORY_SEPARATOR, "", $app_path));

if (isset($_SERVER['SCRIPT_NAME'])) {

    # on Windows _APP_MAIN_DIR becomes \ and abs url would look something like HTTP_HOST\/restOfUrl, so \ should be trimed too
    # @modified Chis Florinel <chis.florinel@candoo.ro>
    $app_main_dir = str_replace("rest/", "", $_SERVER['SCRIPT_NAME']);
    $app_main_dir = rtrim(dirname($app_main_dir), '/\\');
    define('_APP_MAIN_DIR', $app_main_dir);
} else {
    die('[rest/config.php] Cannot determine APP_MAIN_DIR, please set manual and comment this line');
}

//Import the PHPMailer class into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
// use PHPMailer\PHPMailer\OAuth;
//Alias the League Google OAuth2 provider class
// use League\OAuth2\Client\Provider\Google;

// The DB connections data
require_once APP_PATH.'include/db.php';

// Get the DB class
require_once APP_PATH.'class/class.db.php';

// All important files
include_once APP_PATH.'include/functions.php';
include_once APP_PATH.'class/class.jakbase.php';
include_once APP_PATH.'class/class.userlogin.php';
include_once APP_PATH.'class/class.user.php';

// Cache stuff
if (file_exists(APP_PATH.JAK_CACHE_DIRECTORY.'/define.php')) include_once APP_PATH.JAK_CACHE_DIRECTORY.'/define.php';
if (file_exists(APP_PATH.JAK_CACHE_DIRECTORY.'/stuff.php')) include_once APP_PATH.JAK_CACHE_DIRECTORY.'/stuff.php';

// Change for 3.0.3
use JAKWEB\JAKsql;

// Database connection
$jakdb = new JAKsql([
    // required
    'database_type' => JAKDB_DBTYPE,
    'database_name' => JAKDB_NAME,
    'server' => JAKDB_HOST,
    'username' => JAKDB_USER,
    'password' => JAKDB_PASS,
    'charset' => 'utf8',
    'port' => JAKDB_PORT,
    'prefix' => JAKDB_PREFIX,
 
    // [optional] driver_option for connection, read more from http://www.php.net/manual/en/pdo.setattribute.php
    'option' => [PDO::ATTR_CASE => PDO::CASE_NATURAL]
  	]);

// Launch the user login class
$jakuserlogin = new JAK_userlogin();

// timezone from server
date_default_timezone_set(JAK_TIMEZONESERVER);
$jakdb->query('SET time_zone = "'.date("P").'"');

// Check if https is activated
if (JAK_SITEHTTPS) {
    define('BASE_URL', 'https://' . FULL_SITE_DOMAIN . _APP_MAIN_DIR . '/');
} else {
    define('BASE_URL', 'http://' . FULL_SITE_DOMAIN . _APP_MAIN_DIR . '/');
}

// Get the users ip address
$ipa = get_ip_address();
?>