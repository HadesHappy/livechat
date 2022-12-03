<?php if(count(get_included_files()) == 1) exit("No direct script access allowed");

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 4.0.5                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2021 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

define("LB_API_DEBUG", false);
define("LB_SHOW_UPDATE_PROGRESS", true);

define("LB_TEXT_CONNECTION_FAILED", 'License Server is unavailable at the moment, please try again.');
define("LB_TEXT_INVALID_RESPONSE", 'Invalid license, please register bellow or contact <a href="https://www.jakweb.ch">support</a>.');
define("LB_TEXT_VERIFIED_RESPONSE", 'Verified! Thanks for purchasing Live Chat 3.');
define("LB_TEXT_PREPARING_MAIN_DOWNLOAD", 'Preparing to download main update...');
define("LB_TEXT_MAIN_UPDATE_SIZE", 'Main Update size:');
define("LB_TEXT_DONT_REFRESH", '(Please do not refresh the page).');
define("LB_TEXT_DOWNLOADING_MAIN", 'Downloading main update...');
define("LB_TEXT_UPDATE_PERIOD_EXPIRED", 'Your update period has ended or your license is invalid, please contact support.');
define("LB_TEXT_UPDATE_PATH_ERROR", 'Folder does not have write permission or the update file path could not be resolved, please contact support.');
define("LB_TEXT_MAIN_UPDATE_DONE", 'Main update files downloaded, extracted and installed.');
define("LB_TEXT_UPDATE_EXTRACTION_ERROR", 'Update zip extraction failed.');
define("LB_TEXT_PREPARING_SQL_DOWNLOAD", 'Preparing to download database update...');
define("LB_TEXT_SQL_UPDATE_SIZE", 'Database update size:');
define("LB_TEXT_DOWNLOADING_SQL", 'Downloading database update...');
define("LB_TEXT_SQL_UPDATE_DONE", 'Database update file downloaded.');
define("LB_TEXT_UPDATE_WITH_SQL_DONE", 'Update successful, files and database have been updated.');
define("LB_TEXT_UPDATE_WITHOUT_SQL_DONE", 'Update successful, files have been updated there were no database updates.');

if(!LB_API_DEBUG){
	@ini_set('display_errors', 0);
}

if((@ini_get('max_execution_time')!=='0')&&(@ini_get('max_execution_time'))<600){
	@ini_set('max_execution_time', 600);
}
@ini_set('memory_limit', '256M');

class JAKLicenseAPI{

	private $product_id;
	private $api_url;
	private $api_key;
	private $api_language;
	private $current_version;
	private $verify_type;
	private $verification_period;
	private $root_path;
	private $license_file;

	public function __construct(){ 
		$this->product_id = 'LC3_165D659B';
		$this->api_url = 'https://license.jakweb.ch/';
		$this->api_key = 'B505125128CDA848466F';
		$this->api_language = 'english';
		$this->current_version = 'v'.(defined('JAK_VERSION') ? JAK_VERSION : '');
		$this->verify_type = 'envato';
		$this->verification_period = 30;
		$this->root_path = APP_PATH.JAK_FILES_DIRECTORY.'/updates/';
		$this->license_file = APP_PATH.JAK_FILES_DIRECTORY.'/updates/.lic';
	}

	public function check_local_license_exist(){
		return is_file($this->license_file);
	}

	public function get_current_version(){
		return $this->current_version;
	}

	private function call_api($method, $url, $data){
		$curl = curl_init();
		switch ($method){
			case "POST":
				curl_setopt($curl, CURLOPT_POST, 1);
				if($data)
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				break;
			case "PUT":
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
				if($data)
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);                         
				break;
		  	default:
		  		if($data)
					$url = sprintf("%s?%s", $url, http_build_query($data));
		}
		$this_server_name = getenv('SERVER_NAME')?:
			$_SERVER['SERVER_NAME']?:
			getenv('HTTP_HOST')?:
			$_SERVER['HTTP_HOST'];
		$this_http_or_https = ((
			(isset($_SERVER['HTTPS'])&&($_SERVER['HTTPS']=="on"))or
			(isset($_SERVER['HTTP_X_FORWARDED_PROTO'])and
				$_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
		)?'https://':'http://');
		$this_url = $this_http_or_https.$this_server_name.$_SERVER['REQUEST_URI'];
		$this_ip = getenv('SERVER_ADDR')?:
			$_SERVER['SERVER_ADDR']?:
			$this->get_ip_from_third_party()?:
			gethostbyname(gethostname());
		curl_setopt($curl, CURLOPT_HTTPHEADER, 
			array('Content-Type: application/json', 
				'LB-API-KEY: '.$this->api_key, 
				'LB-URL: '.$this_url, 
				'LB-IP: '.$this_ip, 
				'LB-LANG: '.$this->api_language)
		);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30); 
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		$result = curl_exec($curl);
		if(!$result&&!LB_API_DEBUG){
			$rs = array(
				'status' => FALSE, 
				'message' => LB_TEXT_CONNECTION_FAILED
			);
			return json_encode($rs);
		}
		$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if($http_status != 200){
			if(LB_API_DEBUG){
				$temp_decode = json_decode($result, true);
				$rs = array(
					'status' => FALSE, 
					'message' => ((!empty($temp_decode['error']))?
						$temp_decode['error']:
						$temp_decode['message'])
				);
				return json_encode($rs);
			}else{
				$rs = array(
					'status' => FALSE, 
					'message' => LB_TEXT_INVALID_RESPONSE
				);
				return json_encode($rs);
			}
		}
		curl_close($curl);
		return $result;
	}

	public function check_connection(){
		$data_array =  array();
		$get_data = $this->call_api(
			'POST',
			$this->api_url.'api/check_connection_ext', 
			json_encode($data_array)
		);
		$response = json_decode($get_data, true);
		return $response;
	}

	public function get_latest_version(){
		$data_array =  array(
			"product_id"  => $this->product_id
		);
		$get_data = $this->call_api(
			'POST',
			$this->api_url.'api/latest_version', 
			json_encode($data_array)
		);
		$response = json_decode($get_data, true);
		return $response;
	}

	public function activate_license($license, $client, $create_lic = true){
		$data_array =  array(
			"product_id"  => $this->product_id,
			"license_code" => $license,
			"client_name" => $client,
			"verify_type" => $this->verify_type
		);
		$get_data = $this->call_api(
			'POST',
			$this->api_url.'api/activate_license', 
			json_encode($data_array)
		);
		$response = json_decode($get_data, true);
		if(!empty($create_lic)){
			if($response['status']){
				$licfile = trim($response['lic_response']);
				file_put_contents($this->license_file, $licfile, LOCK_EX);
			}else{
				@chmod($this->license_file, 0777);
				if(is_writeable($this->license_file)){
					unlink($this->license_file);
				}
			}
		}
		return $response;
	}

	public function verify_license($time_based_check = false, $license = false, $client = false){
		if(!empty($license)&&!empty($client)){
			$data_array =  array(
				"product_id"  => $this->product_id,
				"license_file" => null,
				"license_code" => $license,
				"client_name" => $client
			);
		}else{
			if(is_file($this->license_file)){
				$data_array =  array(
					"product_id"  => $this->product_id,
					"license_file" => file_get_contents($this->license_file),
					"license_code" => null,
					"client_name" => null
				);
			}else{
				$data_array =  array();
			}
		} 
		$res = array('status' => TRUE, 'message' => LB_TEXT_VERIFIED_RESPONSE);
		if($time_based_check && $this->verification_period > 0){
			ob_start();
			if(session_status() == PHP_SESSION_NONE){
				session_start();
			}
			$type = (int) $this->verification_period;
			$today = date('d-m-Y');
			if(empty($_SESSION["f0518747ef7c4b1"])){
				$_SESSION["f0518747ef7c4b1"] = '00-00-0000';
			}
			if($type == 1){
				$type_text = '1 day';
			}elseif($type == 3){
				$type_text = '3 days';
			}elseif($type == 7){
				$type_text = '1 week';
			}elseif($type == 30){
				$type_text = '1 month';
			}elseif($type == 90){
				$type_text = '3 months';
			}elseif($type == 365) {
				$type_text = '1 year';
			}else{
				$type_text = $type.' days';
			}
			if(strtotime($today) >= strtotime($_SESSION["f0518747ef7c4b1"])){
				$get_data = $this->call_api(
					'POST',
					$this->api_url.'api/verify_license', 
					json_encode($data_array)
				);
				$res = json_decode($get_data, true);
				if($res['status']==true){
					$tomo = date('d-m-Y', strtotime($today. ' + '.$type_text));
					$_SESSION["f0518747ef7c4b1"] = $tomo;
				}
			}
			ob_end_clean();
		}else{
			$get_data = $this->call_api(
				'POST',
				$this->api_url.'api/verify_license', 
				json_encode($data_array)
			);
			$res = json_decode($get_data, true);
		}
		return $res;
	}

	public function deactivate_license($license = false, $client = false){
		if(!empty($license)&&!empty($client)){
			$data_array =  array(
				"product_id"  => $this->product_id,
				"license_file" => null,
				"license_code" => $license,
				"client_name" => $client
			);
		}else{
			if(is_file($this->license_file)){
				$data_array =  array(
					"product_id"  => $this->product_id,
					"license_file" => file_get_contents($this->license_file),
					"license_code" => null,
					"client_name" => null
				);
			}else{
				$data_array =  array();
			}
		}
		$get_data = $this->call_api(
			'POST',
			$this->api_url.'api/deactivate_license', 
			json_encode($data_array)
		);
		$response = json_decode($get_data, true);
		if($response['status']){
			@chmod($this->license_file, 0777);
			if(is_writeable($this->license_file)){
				unlink($this->license_file);
			}
		}
		return $response;
	}

	public function check_update(){
		$data_array =  array(
			"product_id"  => $this->product_id,
			"current_version" => $this->current_version
		);
		$get_data = $this->call_api(
			'POST',
			$this->api_url.'api/check_update', 
			json_encode($data_array)
		);
		$response = json_decode($get_data, true);
		return $response;
	}

	public function download_update($update_id, $type, $version, $license = false, $client = false){ 
		if(!empty($license)&&!empty($client)){
			$data_array =  array(
				"license_file" => null,
				"license_code" => $license,
				"client_name" => $client
			);
		}else{
			if(is_file($this->license_file)){
				$data_array =  array(
					"license_file" => file_get_contents($this->license_file),
					"license_code" => null,
					"client_name" => null
				);
			}else{
				$data_array =  array();
			}
		}
		ob_end_flush(); 
		ob_implicit_flush(true);  
		$version = str_replace(".", "_", $version);
		ob_start();
		$source_size = $this->api_url."api/get_update_size/main/".$update_id; 
		echo LB_TEXT_PREPARING_MAIN_DOWNLOAD."<br>";
		if(LB_SHOW_UPDATE_PROGRESS){echo '<script>document.getElementById("updprog").style.width = "1%";document.getElementById("updprogval").textContent="1%";</script>';}
		ob_flush();
		echo LB_TEXT_MAIN_UPDATE_SIZE." ".$this->get_remote_filesize($source_size)." ".LB_TEXT_DONT_REFRESH."<br>";
		if(LB_SHOW_UPDATE_PROGRESS){echo '<script>document.getElementById("updprog").style.width = "5%";document.getElementById("updprogval").textContent="5%";</script>';}
		ob_flush();
		$temp_progress = '';
		$ch = curl_init();
		$source = $this->api_url."api/download_update/main/".$update_id; 
		curl_setopt($ch, CURLOPT_URL, $source);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_array);
		$this_server_name = getenv('SERVER_NAME')?:
			$_SERVER['SERVER_NAME']?:
			getenv('HTTP_HOST')?:
			$_SERVER['HTTP_HOST'];
		$this_http_or_https = ((
			(isset($_SERVER['HTTPS'])&&($_SERVER['HTTPS']=="on"))or
			(isset($_SERVER['HTTP_X_FORWARDED_PROTO'])and
				$_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
		)?'https://':'http://');
		$this_url = $this_http_or_https.$this_server_name.$_SERVER['REQUEST_URI'];
		$this_ip = getenv('SERVER_ADDR')?:
			$_SERVER['SERVER_ADDR']?:
			$this->get_ip_from_third_party()?:
			gethostbyname(gethostname());
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'LB-API-KEY: '.$this->api_key, 
			'LB-URL: '.$this_url, 
			'LB-IP: '.$this_ip, 
			'LB-LANG: '.$this->api_language)
		);
		if(LB_SHOW_UPDATE_PROGRESS){curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, array($this, 'progress'));}
		if(LB_SHOW_UPDATE_PROGRESS){curl_setopt($ch, CURLOPT_NOPROGRESS, false);}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); 
		echo LB_TEXT_DOWNLOADING_MAIN."<br>";
		if(LB_SHOW_UPDATE_PROGRESS){echo '<script>document.getElementById("updprog").style.width = "10%";document.getElementById("updprogval").textContent="10%";</script>';}
		ob_flush();
		$data = curl_exec($ch);
		$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if($http_status != 200){
			if($http_status == 401){
				curl_close($ch);
				exit("<br>".LB_TEXT_UPDATE_PERIOD_EXPIRED);
			}else{
				curl_close($ch);
				exit("<br>".LB_TEXT_INVALID_RESPONSE);
			}
		}
		curl_close($ch);
		$destination = $this->root_path."/update_main_".$version.".zip"; 
		$file = fopen($destination, "w+");
		if(!$file){
			exit("<br>".LB_TEXT_UPDATE_PATH_ERROR);
		}
		fputs($file, $data);
		fclose($file);
		if(LB_SHOW_UPDATE_PROGRESS){echo '<script>document.getElementById("updprog").style.width = "65%";document.getElementById("updprogval").textContent="65%";</script>';}
		ob_flush();
		$zip = new ZipArchive;
		$res = $zip->open($destination);
		if($res === TRUE){
			$zip->extractTo(APP_PATH); 
			$zip->close();
			unlink($destination);
			echo LB_TEXT_MAIN_UPDATE_DONE."<br><br>";
			if(LB_SHOW_UPDATE_PROGRESS){echo '<script>document.getElementById("updprog").style.width = "75%";document.getElementById("updprogval").textContent="75%";</script>';}
			ob_flush();
		}else{
			echo LB_TEXT_UPDATE_EXTRACTION_ERROR."<br><br>";
			ob_flush();
		}
		if($type == true){
			$source_size = $this->api_url."api/get_update_size/sql/".$update_id; 
			echo LB_TEXT_PREPARING_SQL_DOWNLOAD."<br>";
			ob_flush();
			echo LB_TEXT_SQL_UPDATE_SIZE." ".$this->get_remote_filesize($source_size)." ".LB_TEXT_DONT_REFRESH."<br>";
			if(LB_SHOW_UPDATE_PROGRESS){echo '<script>document.getElementById("updprog").style.width = "85%";document.getElementById("updprogval").textContent="85%";</script>';}
			ob_flush();
			$temp_progress = '';
			$ch = curl_init();
			$source = $this->api_url."api/download_update/sql/".$update_id;
			curl_setopt($ch, CURLOPT_URL, $source);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_array);
			$this_server_name = getenv('SERVER_NAME')?:
				$_SERVER['SERVER_NAME']?:
				getenv('HTTP_HOST')?:
				$_SERVER['HTTP_HOST'];
			$this_http_or_https = ((
				(isset($_SERVER['HTTPS'])&&($_SERVER['HTTPS']=="on"))or
				(isset($_SERVER['HTTP_X_FORWARDED_PROTO'])and
					$_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
			)?'https://':'http://');
			$this_url = $this_http_or_https.$this_server_name.$_SERVER['REQUEST_URI'];
			$this_ip = getenv('SERVER_ADDR')?:
				$_SERVER['SERVER_ADDR']?:
				$this->get_ip_from_third_party()?:
				gethostbyname(gethostname());
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'LB-API-KEY: '.$this->api_key, 
				'LB-URL: '.$this_url, 
				'LB-IP: '.$this_ip, 
				'LB-LANG: '.$this->api_language)
			); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
			echo LB_TEXT_DOWNLOADING_SQL."<br>";
			if(LB_SHOW_UPDATE_PROGRESS){echo '<script>document.getElementById("updprog").style.width = "90%";document.getElementById("updprogval").textContent="90%";</script>';}
			ob_flush();
			$data = curl_exec($ch);
			$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if($http_status!=200){
				curl_close($ch);
				exit(LB_TEXT_INVALID_RESPONSE);
			}
			curl_close($ch);
			$destination = $this->root_path."/update_sql_".$version.".sql";
			$file = fopen($destination, "w+");
			if(!$file){
				exit(LB_TEXT_UPDATE_PATH_ERROR);
			}
			fputs($file, $data);
			fclose($file);
			// We run the real database update file
			$dbupdatefile = APP_PATH.'update.php';
			if (file_exists($dbupdatefile)) {
				global $jakdb;
				include($dbupdatefile);
				unlink($dbupdatefile);
			}
			echo LB_TEXT_SQL_UPDATE_DONE."<br><br>";
			if(LB_SHOW_UPDATE_PROGRESS){echo '<script>document.getElementById("updprog").style.width = "100%";document.getElementById("updprogval").textContent="100%";</script>';}
			echo LB_TEXT_UPDATE_WITH_SQL_DONE;
			ob_flush();
		}else{
			if(LB_SHOW_UPDATE_PROGRESS){echo '<script>document.getElementById("updprog").style.width = "100%";document.getElementById("updprogval").textContent="100%";</script>';}
			echo LB_TEXT_UPDATE_WITHOUT_SQL_DONE;
			ob_flush();
		}
		ob_end_flush(); 
	}

	private function progress($resource, $download_size, $downloaded, $upload_size, $uploaded){
		static $prev = 0;
		if($download_size == 0){
			$progress = 0;
		}else{
			$progress = round( $downloaded * 100 / $download_size );
		}
		if(($progress!=$prev) && ($progress == 25)){
			$prev = $progress;
			echo '<script>document.getElementById("updprog").style.width = "25%";document.getElementById("updprogval").textContent="25%";</script>';
			ob_flush();
		}
		if(($progress!=$prev) && ($progress == 50)){
			$prev=$progress;
			echo '<script>document.getElementById("updprog").style.width = "50%";document.getElementById("updprogval").textContent="50%";</script>';
			ob_flush();
		}
		if(($progress!=$prev) && ($progress == 75)){
			$prev=$progress;
			echo '<script>document.getElementById("updprog").style.width = "75%";document.getElementById("updprogval").textContent="75%";</script>';
			ob_flush();
		}
		if(($progress!=$prev) && ($progress == 100)){
			$prev=$progress;
			echo '<script>document.getElementById("updprog").style.width = "100%";document.getElementById("updprogval").textContent="100%";</script>';
			ob_flush();
		}
	}

	private function get_ip_from_third_party(){
		$curl = curl_init ();
		curl_setopt($curl, CURLOPT_URL, "http://ipecho.net/plain");
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10); 
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		$response = curl_exec($curl);
		curl_close($curl);
		return $response;
	}

	private function get_remote_filesize($url){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_HEADER, TRUE);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_NOBODY, TRUE);
		$this_server_name = getenv('SERVER_NAME')?:
			$_SERVER['SERVER_NAME']?:
			getenv('HTTP_HOST')?:
			$_SERVER['HTTP_HOST'];
		$this_http_or_https = ((
			(isset($_SERVER['HTTPS'])&&($_SERVER['HTTPS']=="on"))or
			(isset($_SERVER['HTTP_X_FORWARDED_PROTO'])and
				$_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
		)?'https://':'http://');
		$this_url = $this_http_or_https.$this_server_name.$_SERVER['REQUEST_URI'];
		$this_ip = getenv('SERVER_ADDR')?:
			$_SERVER['SERVER_ADDR']?:
			$this->get_ip_from_third_party()?:
			gethostbyname(gethostname());
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'LB-API-KEY: '.$this->api_key, 
			'LB-URL: '.$this_url, 
			'LB-IP: '.$this_ip, 
			'LB-LANG: '.$this->api_language)
		);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30); 
		$result = curl_exec($curl);
		$filesize = curl_getinfo($curl, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
		if ($filesize){
			switch ($filesize){
				case $filesize < 1024:
					$size = $filesize .' B'; break;
				case $filesize < 1048576:
					$size = round($filesize / 1024, 2) .' KB'; break;
				case $filesize < 1073741824:
					$size = round($filesize / 1048576, 2) . ' MB'; break;
				case $filesize < 1099511627776:
					$size = round($filesize / 1073741824, 2) . ' GB'; break;
			}
			return $size; 
		}
	}
}
?>