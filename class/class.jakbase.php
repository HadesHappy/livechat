<?php

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 5.0.3                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2022 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

include_once 'class.rewrite.php';

class JAK_base
{
	private $data = array();
	private $usraccesspl = array();
	private $case;
	private $lsvar;
	private $lsvar1;
	protected $table = '', $itemid = '', $select = '', $where = '', $dseo = '';
	
	// This constructor can be used for all classes:
	
	public function __construct(array $options){
			
			foreach($options as $k=>$v){
				if(isset($this->$k)){
					$this->$k = $v;
				}
			}
	}
	
	public static function pluralize($count, $text, $plural) 
	{ 
	    return $count . ( ( $count == 1 ) ? ( " $text" ) : ( " ${plural}" ) );
	}
	
	public static function jakTimesince($mysqlstamp, $date, $time)
	{
	
		$today = time(); /* Current unix time  */
		if (is_numeric($mysqlstamp)) {
			$unixtime = $mysqlstamp;
			//$mysqlstamp = date('Y-m-d H:i:s', $mysqlstamp);
		} else {
			$unixtime = strtotime($mysqlstamp);
		}
		
		// Return date time
		return date(($date && $time ? $date.' ' : $date).$time, $unixtime);
	
	}
	
	public static function jakCheckSession($convid, $restoreid)
	{
		
		global $jakdb;
		if ($jakdb->has("sessions", ["id" => $convid, "uniqueid" => $restoreid, "status" => 1])) {
			return true;
		}
	
	}
	
	public static function jakWriteinCache($file, $content, $extra)
	{
	
		if ($file && $content) {
		
			if (isset($extra)) {
				file_put_contents($file, $content, FILE_APPEND | LOCK_EX);
			} else {
				file_put_contents($file, $content, LOCK_EX);
			}

			return true;
		}
	
	}
	
	public static function jakAvailableHours($hours,$available) {
	
		$ohours = "";
		if (isset($hours) && !empty($hours)) $ohours = json_decode($hours, true);
		
		// get the php str
		$dtime = new DateTime($available);
		
		// Days of the week
		$daysaweekid = array(0 => "Mon", 1 => "Tue", 2 => "Wed", 3 => "Thu", 4 => "Fri", 5 => "Sat", 6 => "Sun");
		
		// Return the correct day
		$day = array_search($dtime->format('D'), $daysaweekid);
		
		$nobh = false;
		
		// Check if the day is active and proceed
		if (isset($ohours[$day]["isActive"]) && !empty($ohours[$day]["isActive"])) {
			
			// Now we need to check the time
			if (!empty($ohours[$day]["timeFrom"]) && !empty($ohours[$day]["timeTill"])) {
				
				if ($ohours[$day]["timeTill"] == "24:00") $ohours[$day]["timeTill"] = "23.59";
				
				if (($ohours[$day]["timeFrom"] <= $dtime->format('H:i')) && ($ohours[$day]["timeTill"] >= $dtime->format('H:i'))) $nobh = true;
			}
			
			if (!$nobh && !empty($ohours[$day]["timeFroma"]) && !empty($ohours[$day]["timeTilla"])) {
			
				if ($ohours[$day]["timeTilla"] == "24:00") $ohours[$day]["timeTilla"] = "23.59";
			
				if (($ohours[$day]["timeFroma"] <= $dtime->format('H:i')) && ($ohours[$day]["timeTilla"] >= $dtime->format('H:i'))) $nobh = true;
			}
			
			return $nobh;
			
		} else {
			return false;
		}
		
	}

	public static function jakWhatslog($guestid, $opid, $clientid, $whatsid, $itemid, $location, $email, $url, $ip, $agent) {
	
			global $jakdb;

			// We are calling the geo
			if (isset($location) && !empty($location)) {
				list($city, $country_name, $country_code, $country_lat, $country_lng, $storedtime) = explode('|', strip_tags(jak_string_encrypt_decrypt($location, false)));
			} else {
				// Country Stuff
				$country_name = 'Disabled';
				$country_code = 'xx';
				$city = 'Disabled';
				$country_lng = $country_lat = '';
			}

			if ($whatsid == 2 || $whatsid == 5) {
				$jakdb->update("whatslog", ["operatorid" => $opid, "clientid" => $clientid, "whatsid" => $whatsid], ["AND" => ["ip" => $ip, "name" => $email, "time" => $jakdb->raw("NOW()")]]);
			} else {
				$jakdb->insert("whatslog", ["guestid" => $guestid, "operatorid" => $opid, "clientid" => $clientid, "whatsid" => $whatsid, "itemid" => $itemid, "country" => $country_name, "city" => $city, "countrycode" => $country_code, "latitude" => $country_lat, "longitude" => $country_lng, "name" => $email, "fromwhere" => $url, "usragent" => $agent, "ip" => $ip, "time" => $jakdb->raw("NOW()")]);
			}

			return true;
			
	}

	public static function jakCookie($cookiename, $value, $expires, $path) {

		if (version_compare(PHP_VERSION, '7.3', '>=')) {

			setcookie($cookiename, $value, [
		    'expires' => time() + $expires,
		    'path' => $path,
		    'httponly' => true,
		    'samesite' => 'None',
		    'secure' => true]);

		} else {

			setcookie($cookiename, $value, time() + $expires, $path.'; SameSite=None; Secure');
		}
	}
}
?>