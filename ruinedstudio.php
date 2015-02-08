<?php
	session_start();
	include(dirname(__FILE__).DIRECTORY_SEPARATOR."config.php");
	function _loged_in_studio()
			{ 
				global $set, $DB;
				$username = $_SESSION['RS_username'];
				$password_sh = $_SESSION['RS_password'];
				$SQL = $DB -> prepare( "SELECT * FROM studio_users WHERE username=? AND password=? LIMIT 1");
				$SQL -> execute (array($username, $password_sh));
				$usere = $SQL -> fetch();
				if ($usere['user_id']) { return $usere; } else { return false; }
			}
	function _loged()
			{ 
				global $set, $DB;
				$username = $_SESSION['RSe_username'];
				$password_sh = $_SESSION['RSe_password'];
				$SQL = $DB -> prepare( "SELECT * FROM studio_commerce_clients WHERE email=? AND password=? LIMIT 1");
				$SQL -> execute (array($username, $password_sh));
				$user = $SQL -> fetch();
				if ($user['commerce_client'])
					{
						return $user;
					}
				else
					{
						return false;
					}
			}
	function perm($permision, $list)
		{
			$array = json_decode($list);
			return in_array($permision, $array);
		}
	function _error_page( $title = 'Error', $html = 'Error.' )
		{
			die ( str_replace ( array ( '{ERROR_TITLE}', '{ERROR_MESSAGE}' ), array( $title, $html ), file_get_contents ( dirname ( __FILE__ ).DIRECTORY_SEPARATOR."data".DIRECTORY_SEPARATOR."html".DIRECTORY_SEPARATOR."error.html" ) ) );			
		}
	function _database_connect ( $host = 'localhost', $database = '', $username = 'root', $password = '' )
		{
			try
				{
					$dsn = 'mysql:host='.$host.';dbname='.$database;
					$options = array(
						PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
					);
					$ms_connected = 'pdo';
					return new PDO($dsn, $username, $password, $options);
				}
			catch( PDOException $e )
				{
					return _error_page('Connection Error','<b>Connection Error:</b><br/><br/>Cant connect to database, please try again in few minutes.');
				}
		}
	function ob_language($html)
	 {
		 global $lang,$config;
		 $key = array_keys($lang);
		 for($i=0; $i<count($lang); $i++)
			{
			  $html = str_replace("{LANG:".$key[$i]."}",$lang[$key[$i]],$html);
			}
		return $html;
		unset($lang,$config,$i,$key,$html);
	 }
	function _check_town($address)
		{
					$search_code = urlencode($address);
					$address_url = 'http://maps.googleapis.com/maps/api/geocode/json?address=' . $search_code . '&sensor=false';
					$address_json = json_decode(file_get_contents($address_url));
					$address_data = $address_json->results[0]->address_components;
					for ($i=0; $i<count($address_data); $i++)
						{
							switch($address_data[$i]->types[0])
								{
									case locality;
									$town = $address_data[$i]->long_name;
									break;
								}
						}
					return $town;
		}
	function ob_language_from_mysql($html)
	 {
		global $DB, $set;
		$SQL = $DB->prepare("SELECT lang_key, translation, language FROM studio_language WHERE type = ?");
		$SQL->execute(array("web"));
		$data = $SQL->fetchAll();
		$key = array();
		if ($data)
			{
				foreach ($data as $lang)
					{
						$key[$lang['lang_key']][$lang['language']] = $lang['translation'];
					}
			}
		$key_name = array_keys($key);
		for($i=0; $i<count($key_name); $i++)
			{
				if ($key[$key_name[$i]][$set['lang']])
					{
						$translation = $key[$key_name[$i]][$set['lang']];
					}
				else
					{
						$key_lang = array_keys($key[$key_name[$i]]);
						$translation = $key[$key_name[$i]][$key_lang[0]];
					}
			  $html = str_replace("{LANG:".$key_name[$i]."}",$translation,$html);
			}
		return $html;
		unset($lang,$config,$i,$key,$html);
	 }
	 
	function _explode_lang_url($url)
		{
			list($tag, $lang, $link) = explode("/",$url, 3);
			//list($tag, $lang, $link, $link_not_real) = explode("/",$url, 4);
			return $link;
			//return $link_not_real;
		}
	function getLnt($zip)
		{
			$url = "http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($zip)."&sensor=false";
			$result_string = file_get_contents($url);
			$result = json_decode($result_string, true);
			$result1[]=$result['results'][0];
			$result2[]=$result1[0]['geometry'];
			$result3[]=$result2[0]['location'];
			return $result3[0];
		}
	function _getDistanceAndTime($from, $to, $mode="driving",$units="metric")
		{
			$from = urlencode($from);
			$to = urlencode($to);
			$key = "AIzaSyDx9-LLtoKeEEbTWvVomu1inN_-4ksKKaI";
			$file = file_get_contents("https://maps.googleapis.com/maps/api/directions/json?units=metric&origin=".$from."&destination=".$to."&key=".$key."&mode=".$mode);
			$result = json_decode($file, true);
			$distance = $result['routes'][0]['legs'][0]['distance']['value'];
			$time = $result['routes'][0]['legs'][0]['duration']['text'];
			if ($units == "imperial")
				{
					$dist = number_format($distance * 0.000621371192,2,'.',' '); // meters div mile
					$return = array('distance'=>$dist." mi",'distance_value'=>$dist,'time'=>$time);
				}
			else
				{
					$dist = number_format($distance / 1000,2,'.',' '); // meter div kilometer
					$return = array('distance'=>$dist." km",'distance_value'=>$dist,'time'=>$time);
				}
			return $return;
		}
	function getDistance($zip1, $zip2, $unit="K")
		{
			$first_lat = getLnt($zip1);
			$next_lat = getLnt($zip2);
			$lat1 = $first_lat['lat'];
			$lon1 = $first_lat['lng'];
			$lat2 = $next_lat['lat'];
			$lon2 = $next_lat['lng']; 
			$theta=$lon1-$lon2;
			$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +
			cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
			cos(deg2rad($theta));
			$dist = acos($dist);
			$dist = rad2deg($dist);
			$miles = $dist * 60 * 1.1515;
			$unit = strtoupper($unit);

			if ($unit == "K"){
			return ($miles * 1.609344)." ".$unit;
			}
			else if ($unit =="N"){
			return ($miles * 0.8684)." ".$unit;
			}
			else{
			return $miles." ".$unit;
			}
		}
	function ob_links($html)
		{
			global $config_page, $config_seo, $config_facebook, $header, $lang;
			$html = str_replace("{URL}",_page_link(),$html);
			$html = str_replace("{ROOT}",$config_page['url'].($config_page['url_folder'] ? '/'.$config_page['url_folder']:''),$html);
			$html = str_replace("{URL_THIS}",$config_page['url'].$_SERVER['REQUEST_URI'],$html);
			$html = str_replace("{ROOT_STUDIO}",$config_page['url'].($config_page['url_studio'] ? '/'.$config_page['url_studio']:''),$html);
			$html = str_replace("{DATA}",$config_page['url'].($config_page['url_folder'] ? '/'.$config_page['url_folder']:'').'/data',$html);
			$html = str_replace("{THEME}",$config_page['url'].($config_page['url_folder'] ? '/'.$config_page['url_folder']:'').'/'.$config_page['theme'],$html);
			$html = str_replace("{DISPLAY_STUDIO}",$config_page['url'].($config_page['url_studio'] ? '/'.$config_page['url_studio']:'').'/display',$html);
			$html = str_replace("{TITLE}",$lang['title'],$html);
			
			$html = str_replace("{KEYWORDS}",$lang['keywords'],$html);
			$html = str_replace("{AUTHOR}",$lang['author'],$html);
			$html = str_replace("{CHARSET}",$lang['charset'],$html);
			
			$header['title'] = empty($header['title']) ? $lang['INDEX:Title'] : $header['title'];
			$header['robots'] = empty($header['robots']) ? $config_seo['robots'] : $header['robots'];
			$header['description'] = empty($header['description']) ? $lang['description'] : $header['description'];
			
			$header['title'] = str_replace(array('"',"'"),array('&#34;','&#39;'),$header['title']);
			$header['description'] = str_replace(array('"',"'"),array('&#34;','&#39;'),$header['description']);
			
			$html = str_replace("{PAGE_TITLE}",$header['title'],$html);
			$html = str_replace("{ROBOTS}",$header['robots'],$html);
			$html = str_replace("{DESCRIPTION}",$header['description'],$html);
			
			$header['fb_image'] = empty($header['fb_image']) ? $config_facebook['image'] : $header['fb_image'];
			$header['fb_type'] = empty($header['fb_type']) ? $config_facebook['type'] : $header['fb_type'];
			
			$html = str_replace("{FB:IMAGE}",$header['fb_image'],$html);
			$html = str_replace("{FB:TYPE}",$header['fb_type'],$html);
			
			return $html;
			unset($config_page,$config_seo,$config_facebook,$html,$header);
		}
	function ober($html)
	 {
			//return ob_language(ob_links($html));
			return ob_language_from_mysql(ob_links($html));
	 }
	function _page_link()
		{
			global $config_page, $instudio;
			if ($instudio == true)
				{
					return $config_page['url'].($config_page['url_studio'] ? '/'.$config_page['url_studio']:'')."/"._get_lang();
				}
			else
				{
					return $config_page['url'].($config_page['url_folder'] ? '/'.$config_page['url_folder']:'')."/"._get_lang();
				}
		}
	function _get_lang()
		{
			global $config_lang, $language_id;
			if ( !$language_id AND !$_COOKIE['language'])
				{
					$detect = _detect_lang();
					SetCookie('language', $detect, time() + 31536000);
					$_SESSION['language'] = $detect;
					return $detect;
				}
			else if (!$language_id AND $_COOKIE['language'])
				{
					if (in_array($_COOKIE['language'], $config_lang['available']))
						{
							return $_COOKIE['language'];
						}
					else
						{
							$detect = _detect_lang();
							$_COOKIE['language'] = $detect;
							$_SESSION['language'] = $detect;
							return $detect;
						}
				}
			else
				{
					if (in_array($language_id, $config_lang['available']))
						{
							return $language_id;
						}
					else
						{
							$detect = _detect_lang();
							$_COOKIE['language'] = $detect;
							$_SESSION['language'] = $detect;
							return $detect;
						}
				}
		}
	function _get ( $string )
		{
			return htmlspecialchars ( $_REQUEST [ $string ] );
		}
	function _gethtml ( $string )
		{
			return $_REQUEST [ $string ];
		}
	function _check_email($email)
		{
			//$regex1 = '/\A[a-z0-9]+([-._][a-z0-9]+)*@([a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{2,4}\z/'; 
			//$regex2 = '/^(?=.{1,64}@.{4,64}$)(?=.{6,100}$).*/'; 
			//if ( !preg_match ( $regex1, $email ) || !preg_match ( $regex2, $email ))
			//	{
			//		return false;
			//	}
			if ( !filter_var($email, FILTER_VALIDATE_EMAIL))
				{
					return false;
				}
			return true;
		}
	function _check_phone_number($phone)
		{
			$pattern = "/^(\+44\s?7\d{3}|\(?07\d{3}\)?)\s?\d{3}\s?\d{3}$/";
			$match = preg_match($pattern,$phone);
			if ($match == false)
				{ return false; }
			return true;
		}
	function _random_string()
			{
				$character_set_array = array();
				$character_set_array[] = array('count' => 3, 'characters' => 'abcdefghijklmnpqrstuvwxyz');
				$character_set_array[] = array('count' => 2, 'characters' => '23456789');
				$temp_array = array();
				foreach ($character_set_array as $character_set) {
					for ($i = 0; $i < $character_set['count']; $i++) {
						$temp_array[] = $character_set['characters'][rand(0, strlen($character_set['characters']) - 1)];
					}
				}
				shuffle($temp_array);
				return implode('', $temp_array);
			}
	function _seo_url($string)
		{
			$string = strtolower($string);
			$string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
			$string = preg_replace("/[\s-]+/", " ", $string);
			$string = preg_replace("/[\s_]/", "-", $string);
			return $string;
		}
	function lixlpixel_get_env_var($Var)
		{
			 if(empty($GLOBALS[$Var]))
			 {
				 $GLOBALS[$Var]=(!empty($GLOBALS['_SERVER'][$Var]))?
				 $GLOBALS['_SERVER'][$Var] : (!empty($GLOBALS['HTTP_SERVER_VARS'][$Var])) ? $GLOBALS['HTTP_SERVER_VARS'][$Var]:'';
			 }
		}
	function _femname($name="")
		{
			global $set;
			if ($set['lang'] == 'lt')
				{
					$word = explode(" ", $name);
					$name="";
					for ($i=0; $i<count($word); $i++)
						{
							$cnt = strlen($word[$i]);
							
							if (strtolower(substr($word[$i], -5)) == "iasis")
								{
									$name.=substr($word[$i], 0, $cnt-5)."ioji ";
								}
							elseif (strtolower(substr($word[$i], -4)) == "ysis")
								{
									$name.=substr($word[$i], 0, $cnt-4)."ioji ";
								}
							elseif (strtolower(substr($word[$i], -3)) == "ius")
								{
									$name.=substr($word[$i], 0, $cnt-3)."ė ";
								}
							elseif (strtolower(substr($word[$i], -3)) == "ias")
								{
									$name.=substr($word[$i], 0, $cnt-3)."ia ";
								}
							elseif (strtolower(substr($word[$i], -2)) == "is")
								{
									$name.=substr($word[$i], 0, $cnt-2)."ė ";
								}
							elseif (strtolower(substr($word[$i], -3)) == "jas")
								{
									$name.=substr($word[$i], 0, $cnt-3)."ja ";
								}
							elseif (strtolower(substr($word[$i], -2)) == "as")
								{
									$name.=substr($word[$i], 0, $cnt-2)."ė ";
								}
							else
								{
									$name.=$word[$i]." ";
								}
							
						}
					return $name;
				}
		}
	function _money($number = 0)
			{
				return number_format($number,2,'.',' ');
			}
	function _langByKey($key, $lang="en")
		{
			global $DB;
			if (empty($key) OR empty($lang)) { return "none"; exit; }
			$SQL = $DB->prepare("SELECT * FROM studio_language WHERE lang_key = ? AND language = ? LIMIT 1");
			$SQL->execute(array($key, $lang));
			$text = $SQL->fetch();
			if ($text)
				{
					
					return $text['translation'];
				}
			else
				{
					return "";
				}
		}
	function _KeyLang($key, $lang="en")
		{
			global $DB;
			if (empty($key) OR empty($lang)) { return "none"; exit; }
			$SQL = $DB->prepare("SELECT * FROM studio_language WHERE lang_key = ? AND language = ? LIMIT 1");
			$SQL->execute(array($key, $lang));
			$text = $SQL->fetch();
			if ($text)
				{
					
					return $text['translation'];
				}
			else
				{
					return $key;
				}
		}
	function _MultiKey($key="", $lang="en")
		{
			global $DB;
			$SQL = $DB->prepare("SELECT * FROM studio_language WHERE lang_key = ? AND language = ? LIMIT 1");
			$SQL->execute(array($key, $lang));
			$text = $SQL->fetch();
			if ($text)
				{
					
					return $text['translation'];
				}
			else
				{
					$SQL = $DB->prepare("SELECT * FROM studio_language WHERE lang_key = ? LIMIT 1");
					$SQL->execute(array($key));
					$text = $SQL->fetch();
					if ($text)
						{
							
							return $text['translation'];
						}
					else
						{
							return $key;
						}
				}
		}
	function _detect_lang()
	{
		 // Detect HTTP_ACCEPT_LANGUAGE & HTTP_USER_AGENT.
		 //lixlpixel_get_env_var('HTTP_ACCEPT_LANGUAGE');
		 //lixlpixel_get_env_var('HTTP_USER_AGENT');
		global $config_lang;
		 $_AL=strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']);
		 $_UA=strtolower($_SERVER['HTTP_USER_AGENT']);

		 // Try to detect Primary language if several languages are accepted.
		 foreach($config_lang['available'] as $K)
		 {
			 if(strpos($_AL, $K)===0)
			 return $K;
		 }

		 // Try to detect any language if not yet detected.
		 foreach($config_lang['available'] as $K)
		 {
			 if(strpos($_AL, $K)!==false)
			 return $K;
		 }
		 foreach($config_lang['available'] as $K)
		 {
			 //if(preg_match("/[[( ]{$K}[;,_-)]/",$_UA)) // matching other letters (create an error for seo spyder)
			 return $K;
		 }

		 // Return default language if language is not yet detected.
		 return $config_lang['default'];
	}
	$language_id = _get ( 'RS_language' );
	$page_id = _get ( 'RS_page' );
	$set_id = explode('/', _get ( 'RS_set' ));
	if ( !$page_id )
		{
			$page_id = "index";
		}
	if ( !file_exists("pages/".$page_id.".php") )
		{
			$page_id = "404";
		}
		
	$set = array('lang' => _get_lang(), 'time' => time());
	if (!$_COOKIE[$cookie_name])
		{
			$array = json_encode(array());
			setcookie($cookie_name, $array, $set['time']+604800, $config_page['url_folder']);
			$_COOKIE[$cookie_name] = $array;
		}
?>