<?php

/*===============================================*\
|| ############################################# ||
|| # JAKWEB.CH / Version 3.8.3                 # ||
|| # ----------------------------------------- # ||
|| # Copyright 2019 JAKWEB All Rights Reserved # ||
|| ############################################# ||
\*===============================================*/

class JAK_rewrite {

	private $url_seg;
	private $data = array();
	
	// This constructor can be used for all classes:
	
	public function __construct($url) {
		$this->url = $url;
	}
	
	function jakGetseg($var) {

		if (isset($var)) {
	
			if (JAK_USE_APACHE) {
			
				$url = str_replace(_APP_MAIN_DIR, '', $this->url);
				$_tmp = explode('?', $url);
				$url = $_tmp[0];
				
				if ($url = explode('/', $url)) {
				    foreach ($url as $d) {
				        if ($d) {
				            $data[] = $d;
				        }
				    }
				}
				
				if (!empty($data[$var])) $url_seg = $data[$var];
			
			} else {
		
				// get the url and parse it
				$parseurl = parse_url($this->url);
				
				if (!empty($parseurl["query"])) {
					// get only the query
					$parameters = $parseurl["query"];
					parse_str($parameters, $data);
					
					// Now we have to set the array to basic keys
					if (!empty($data)) foreach($data as $d) {
						$data[] = $d;
					}
				
					if (!empty($data[$var])) $url_seg = $data[$var];

				}
			}

		
			if (!empty($url_seg)) return $url_seg;

		}
	}
	
	public static function jakParseurl($var, $var1 = '', $var2 = '', $var3 = '', $var4 = '', $var5 = '', $var6 = '', $var7 = '')
	{
	
		// Set v to zero
		$v = $v1 = $v2 = $v3 = $v4 = $v5 = $v6 = $v7 = $varname = '';
		
		// Check if is/not apache and create url
		if (!JAK_USE_APACHE) {
				
			if (!empty($var1)) {
				$v = '&amp;sp='.htmlspecialchars($var1);
			}
			if (!empty($var2)) {
				$v1 = '&amp;ssp='.htmlspecialchars($var2);
			}
			if (!empty($var3)) {
				$v2 = '&amp;sssp='.htmlspecialchars($var3);
			}
			
			if (!empty($var4)) {
				$v3 = '&amp;ssssp='.htmlspecialchars($var4);
			}
			
			if (!empty($var5)) {
				$v4 = '&amp;sssssp='.htmlspecialchars($var5);
			}

			if (!empty($var6)) {
				$v5 = '&amp;sssssp='.htmlspecialchars($var6);
			}

			if (!empty($var7)) {
				$v6 = '&amp;sssssp='.htmlspecialchars($var7);
			}
			
			// if not apache add some stuff to the url
			if ($var) {
				if ($var == JAK_OPERATOR_LOC) {
					$var = JAK_OPERATOR_LOC.'/index.php?p='.htmlspecialchars($var1);
					$varname = BASE_URL.html_entity_decode($var.$v1.$v2.$v3.$v4.$v5.$v6);
				} else {
					$var = 'index.php?p='.htmlspecialchars($var);
					$varname = BASE_URL.html_entity_decode($var.$v.$v1.$v2.$v3.$v4.$v5.$v6);
				}
			} else {
				$var = '/';
				$varname = BASE_URL.html_entity_decode($var.$v.$v1.$v2.$v3.$v4.$v5.$v6);
			}
		
		} else {
					
			if (!empty($var1)) {
				$v = '/'.htmlspecialchars($var1);
			}
			if (!empty($var2)) {
				$v1 = '/'.htmlspecialchars($var2);
			}
			if (!empty($var3)) {
				$v2 = '/'.htmlspecialchars($var3);
			}
			if (!empty($var4)) {
				$v3 = '/'.htmlspecialchars($var4);
			}
			if (!empty($var5)) {
				$v4 = '/'.htmlspecialchars($var5);
			}
			if (!empty($var6)) {
				$v5 = '/'.htmlspecialchars($var6);
			}
			if (!empty($var7)) {
				$v6 = '/'.htmlspecialchars($var7);
			}
			
			// page is always the same
			$var = htmlspecialchars($var);

			// Now se the var for apache
			$varname = BASE_URL.$var.$v.$v1.$v2.$v3.$v4.$v5.$v6;
				
		}
		
		if (!empty($varname)) return $varname;
		
	}
	
	public static function jakParseurlpaginate($var) {
	
		$varname = '';
		
		if ($var != 1) {
			// Check if is/not apache and create url
			if (!JAK_USE_APACHE && $var) {
				// Now se the var for none apache
				$varname = '&amp;page='.$var;
			} else {
				// Now se the var for seo apache
				$varname = '/'.$var;
			}
		}
		
		return $varname;
	
	}
	
	public function jakRealrequest()
	{
		return str_replace(_APP_MAIN_DIR, '', $this->url);
	}
}
?>