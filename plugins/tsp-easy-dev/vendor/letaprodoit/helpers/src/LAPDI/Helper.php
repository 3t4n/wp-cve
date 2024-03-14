<?php
/**
 * Helper Classes
 *
 * @package		LetAProDoIT.Helpers
 * @filename	Helper.php
 * @version		2.0.0
 * @author		Sharron Denice, Let A Pro Do IT! (www.letaprodoit.com)
 * @copyright	Copyright 2016 Let A Pro Do IT! (www.letaprodoit.com). All rights reserved
 * @license		APACHE v2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 * @brief		Global functions used by various services
 *
 */	
class LAPDI_Helper
{	
	/**
	 * Function to return safe value from an array
	 *
	 * @since 1.0.0
	 *
	 * @param string $array  - The array to query
	 * @param string $key  - The key to look for
	 * @param string $default  - The default value to return
	 *
	 * @return string - $array[$key] 
	 */
	public static function array_safeGetValue( $array, $key, $default = '' )
	{
		return self::arrGetVal($array, $key, $default);
	}
    
	/**
	 * Function to return safe value from an array (shorter name)
	 *
	 * @since 1.0.5
	 *
	 * @param string $array  - The array to query
	 * @param string $key  - The key to look for
	 * @param string $default  - The default value to return
	 *
	 * @return string - $array[$key] 
	 */
	public static function arrGetVal( $array, $key, $default = '' )
	{
        if (empty($key))
            return $array;
        else if (!isset( $array, $array[$key] ) || empty($array[$key]) || $array[$key] == 'null')
            return $default;
        return $array[$key];
	}

    /**
     * Function to generate salt key
     *
     * @since 1.0.0
     *
     * @param int $num - The number of the key
     * @param bool special_chars - Use special chars in password
     *
     * @return string $rand_value - The random value
     *
     */
    public static function assignRandValue($num, $special_chars = true) 
    {
        $rand_value = "";

		if ($special_chars)
		{
			switch($num) 
			{
				case "1":
					$rand_value = "a";
						break;
				case "2":
					$rand_value = "b";
						break;
				case "3":
					$rand_value = "c";
						break;
				case "4":
					$rand_value = "d";
						break;
				case "5":
					$rand_value = "e";
						break;
				case "6":
					$rand_value = "f";
					break;
				case "7":
					$rand_value = "g";
					break;
				case "8":
					$rand_value = "h";
					break;
				case "9":
					$rand_value = "i";
					break;
				case "10":
					$rand_value = "j";
					break;
				case "11":
					$rand_value = "k";
					break;
				case "12":
					$rand_value = "l";
					break;
				case "13":
					$rand_value = "m";
					break;
				case "14":
					$rand_value = "n";
					break;
				case "15":
					$rand_value = "o";
					break;
				case "16":
					$rand_value = "p";
					break;
				case "17":
					$rand_value = "q";
					break;
				case "18":
					$rand_value = "r";
					break;
				case "19":
					$rand_value = "s";
					break;
				case "20":
					$rand_value = "t";
					break;
				case "21":
					$rand_value = "u";
					break;
				case "22":
					$rand_value = "v";
					break;
				case "23":
					$rand_value = "w";
					break;
				case "24":
					$rand_value = "x";
					break;
				case "25":
					$rand_value = "y";
					break;
				case "26":
					$rand_value = "z";
					break;
				case "27":
					$rand_value = "0";
					break;
				case "28":
					$rand_value = "1";
					break;
				case "29":
					$rand_value = "2";
					break;
				case "30":
					$rand_value = "3";
					break;
				case "31":
					$rand_value = "4";
					break;
				case "32":
					$rand_value = "5";
					break;
				case "33":
					$rand_value = "6";
					break;
				case "34":
					$rand_value = "7";
					break;
				case "35":
					$rand_value = "8";
					break;
				case "36":
					$rand_value = "9";
					break;
				case "37":
					$rand_value = "*";
					break;
				case "38":
					$rand_value = "~";
					break;
				case "39":
					$rand_value = "-";
					break;
				case "40":
					$rand_value = "|";
					break;
				case "41":
					$rand_value = "^";
					break;
				case "42":
					$rand_value = "%";
					break;
				/* Dont use spaces 
				case "43":
					$rand_value = " ";
					break;
				*/
				case "44":
					$rand_value = "_";
					break;
				case "45":
					$rand_value = "+";
					break;
				case "46":
					$rand_value = "=";
					break;
				case "47":
					$rand_value = "A";
					break;
				case "48":
					$rand_value = "B";
					break;
				case "49":
					$rand_value = "C";
					break;
				case "50":
					$rand_value = "D";
					break;
				case "51":
					$rand_value = "E";
					break;
				case "52":
					$rand_value = "F";
					break;
				case "53":
					$rand_value = "G";
					break;
				case "54":
					$rand_value = "H";
					break;
				case "55":
					$rand_value = "I";
					break;
				case "56":
					$rand_value = "J";
					break;
				case "57":
					$rand_value = "K";
					break;
				case "58":
					$rand_value = "L";
					break;
				case "59":
					$rand_value = "M";
					break;
				case "60":
					$rand_value = "N";
					break;
				case "61":
					$rand_value = "O";
					break;
				case "62":
					$rand_value = "P";
					break;
				case "63":
					$rand_value = "Q";
					break;
				case "64":
					$rand_value = "R";
					break;
				case "65":
					$rand_value = "S";
					break;
				case "66":
					$rand_value = "T";
					break;
				case "67":
					$rand_value = "U";
					break;
				case "68":
					$rand_value = "V";
					break;
				case "69":
					$rand_value = "W";
					break;
				case "70":
					$rand_value = "X";
					break;
				case "71":
					$rand_value = "Y";
					break;
				case "72":
					$rand_value = "Z";
					break;
				default:
					$rand_value = 0;
					break;
			}
		}
		else
		{
			switch($num) 
			{
				case "1":
					$rand_value = "a";
						break;
				case "2":
					$rand_value = "b";
						break;
				case "3":
					$rand_value = "c";
						break;
				case "4":
					$rand_value = "d";
						break;
				case "5":
					$rand_value = "e";
						break;
				case "6":
					$rand_value = "f";
					break;
				case "7":
					$rand_value = "g";
					break;
				case "8":
					$rand_value = "h";
					break;
				case "9":
					$rand_value = "i";
					break;
				case "10":
					$rand_value = "j";
					break;
				case "11":
					$rand_value = "k";
					break;
				case "12":
					$rand_value = "l";
					break;
				case "13":
					$rand_value = "m";
					break;
				case "14":
					$rand_value = "n";
					break;
				case "15":
					$rand_value = "o";
					break;
				case "16":
					$rand_value = "p";
					break;
				case "17":
					$rand_value = "q";
					break;
				case "18":
					$rand_value = "r";
					break;
				case "19":
					$rand_value = "s";
					break;
				case "20":
					$rand_value = "t";
					break;
				case "21":
					$rand_value = "u";
					break;
				case "22":
					$rand_value = "v";
					break;
				case "23":
					$rand_value = "w";
					break;
				case "24":
					$rand_value = "x";
					break;
				case "25":
					$rand_value = "y";
					break;
				case "26":
					$rand_value = "z";
					break;
				case "27":
					$rand_value = "0";
					break;
				case "28":
					$rand_value = "1";
					break;
				case "29":
					$rand_value = "2";
					break;
				case "30":
					$rand_value = "3";
					break;
				case "31":
					$rand_value = "4";
					break;
				case "32":
					$rand_value = "5";
					break;
				case "33":
					$rand_value = "6";
					break;
				case "34":
					$rand_value = "7";
					break;
				case "35":
					$rand_value = "8";
					break;
				case "36":
					$rand_value = "9";
					break;
				case "47":
					$rand_value = "A";
					break;
				case "48":
					$rand_value = "B";
					break;
				case "49":
					$rand_value = "C";
					break;
				case "50":
					$rand_value = "D";
					break;
				case "51":
					$rand_value = "E";
					break;
				case "52":
					$rand_value = "F";
					break;
				case "53":
					$rand_value = "G";
					break;
				case "54":
					$rand_value = "H";
					break;
				case "55":
					$rand_value = "I";
					break;
				case "56":
					$rand_value = "J";
					break;
				case "57":
					$rand_value = "K";
					break;
				case "58":
					$rand_value = "L";
					break;
				case "59":
					$rand_value = "M";
					break;
				case "60":
					$rand_value = "N";
					break;
				case "61":
					$rand_value = "O";
					break;
				case "62":
					$rand_value = "P";
					break;
				case "63":
					$rand_value = "Q";
					break;
				case "64":
					$rand_value = "R";
					break;
				case "65":
					$rand_value = "S";
					break;
				case "66":
					$rand_value = "T";
					break;
				case "67":
					$rand_value = "U";
					break;
				case "68":
					$rand_value = "V";
					break;
				case "69":
					$rand_value = "W";
					break;
				case "70":
					$rand_value = "X";
					break;
				case "71":
					$rand_value = "Y";
					break;
				case "72":
					$rand_value = "Z";
					break;
				default:
					$rand_value = 0;
					break;
			}
		}

        return $rand_value;
    }

    /**
     * Function to read user input from command line
     *
     * @since 1.0.0
     *
     * @param string $text - The information inputted by the user
     * @param string $color - A name of a color
     * @param boolean $return - Return the response or echo the response
     *
     * @return string
     */
	public static function colorize($text, $color = "NORMAL", $return = true)
	{
		$_colors = array(
	        LIGHT_RED   => "[1;31m",
	        LIGHT_GREEN => "[1;32m",
	        YELLOW      => "[1;33m",
	        LIGHT_BLUE  => "[1;34m",
	        MAGENTA     => "[1;35m",
	        LIGHT_CYAN  => "[1;36m",
	        WHITE       => "[1;37m",
	        NORMAL      => "[0m",
	        BLACK      	=> "[0;30m",
	        RED         => "[0;31m",
	        GREEN       => "[0;32m",
	        BROWN       => "[0;33m",
	        BLUE        => "[0;34m",
	        CYAN        => "[0;36m",
	        BOLD        => "[1m",
		);
		
		$color = strtoupper($color);
	    $out = $_colors["$color"];
	    
	    if(empty($out))
	    { 
		    $out = "[0m"; 
		}//endif
	    
	    if($return)
	    {
	        return chr(27)."$out$text".chr(27)."[0m";#.chr(27);
	    }//endif
	    else
	    {
	        echo chr(27)."$out$text".chr(27).chr(27)."[0m";#.chr(27);
	    }//endelse
	}// end function
	
	/**
	 * Function to delete cookies given a key
	 *
	 * @since 1.0.0
	 *
	 * @param string $find  - The cookie name to find
	 * @param boolean $delete_session  - Whether or not to delete the entire session
     * @param boolean $secure  - Whether or not the cookie is secure
	 *
	 * @return void
	 */
	public static function cookieDelete($find, $delete_session = false, $secure = true)
	{
		foreach ($_COOKIE as $key => $value)
		{
			if (preg_match("/{$find}/", $key))
			{
				if (LAPDI_Config::get('app.debug'))
					LAPDI_Log::info("Deleting cookie [$key]");
					
				$_COOKIE[$key] = "";
				unset($_COOKIE[$key]);
    			$response = setcookie($key, "", -1, '/', false, $secure, false);
    		}
		}
		
		if ($delete_session)
		{
			$_COOKIE['PHPSESSID'] = "";
			unset($_COOKIE['PHPSESSID']);
			$response = setcookie('PHPSESSID', "", -1, '/', false, $secure, false);
		}
	}

    /**
     * Function to set a cookie
     *
     * @since 1.0.0
     *
     * @param string $key  - The cookie name
     * @param string $value  - The cookie value
     * @param string $prefix  - The cookie prefix
     * @param boolean $secure (OPTIONAL) - Will the cookie be set securely
     * @param int $expires (OPTIONAL) - When will the cookie expire
     * @param string $domain (OPTIONAL) - On which domain will the cookie be stored
     *
     * @return null
     */
    public static function cookieSet($key, $value, $prefix = "", $secure = true, $expires = 0, $domain = "")
    {
        $key = $prefix.$key;

        if(is_array($value))
        {
            if (LAPDI_Config::get('app.debug'))
                LAPDI_Log::info("Set Encrypted cookie [$key] = " . @json_encode($value));

            $value = LAPDI_Settings::$cookie_prefix_encoded . @json_encode($value);
        }
        else
        {
            if (LAPDI_Config::get('app.debug'))
                LAPDI_Log::info("Set cookie [$key] = " . $value);
        }

        $response = setcookie($key, $value, $expires, '/', $domain, $secure, false);

        if (LAPDI_Config::get('app.debug'))
            LAPDI_Log::info("Setting Cookie Response: " . $response);

        $_COOKIE[$key] = $value;
    }

	/**
	 * Function to get a cookie
	 *
	 * @since 1.0.0
	 *
	 * @param string $key  - The cookie name
	 * @param string $prefix (OPTIONAL) - The cookie prefix
	 *
	 * @return object - The cookie
	 */
    public static function cookieGet($key, $prefix = "")
    {
		$key = $prefix.$key;
		$value = null;
		
		if (isset($_COOKIE[$key]))
		{
			$value = $_COOKIE[$key];
			
			if (preg_match("/".LAPDI_Settings::$cookie_prefix_encoded."/", $value))
			{
				$value = preg_replace("/".LAPDI_Settings::$cookie_prefix_encoded."/", "", $value);

		        if (LAPDI_Config::get('app.debug'))
		            LAPDI_Log::info("Get encoded cookie [$key] = $value");

				$value = stripslashes($value);
				$value = json_decode($value, true);

		        if (LAPDI_Config::get('app.debug'))
		            LAPDI_Log::info("Get decoded cookie [$key] = " . @json_encode($value));
			}
			else
			{
		        if (LAPDI_Config::get('app.debug'))
		            LAPDI_Log::info("Get cookie [$key] = $value");
			}
		}			

		
		return $value;
	}

	/**
	 * Function to decrypt data
	 *
	 * @since 1.0.0
     * @deprecated 1.1.1 No longer used by internal code and not used for PHP versions 7.0 and higher. Use openDecrypt instead.
	 *
	 * @param string $salt  - The decryption salt
	 * @param string $encrypted  - The data to decrypt
	 *
	 * @return string - encrypted data
	 */
	public static function decrypt($salt, $encrypted)
    {
		$data = base64_decode($encrypted);
		$iv = substr($data, 0, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC));

        if(empty($iv))
        {
            return false;
        }

        if (!defined('MCRYPT_MODE_CBC'))
        {
            return false;
        }

		return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, hash('sha256', $salt, true), substr($data, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC)), MCRYPT_MODE_CBC, $iv), "\0");
	}

	/**
	 * Function to encrypt data
	 *
	 * @since 1.0.0
     * @deprecated 1.1.1 No longer used by internal code and not used for PHP versions 7.0 and higher. Use openEncrypt instead.
	 *
	 * @param string $salt  - The encryption salt
	 * @param string $data  - The data to encrypt
	 *
	 * @return string - encrypted data
	 */
	public static function encrypt($salt, $data)
    {
		$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM);
		return base64_encode($iv . mcrypt_encrypt(MCRYPT_RIJNDAEL_256, hash('sha256', $salt, true), $data, MCRYPT_MODE_CBC, $iv));
	}

    /**
     * Function to decrypt data
     *
     * @since 1.1.1
     *
     * @param string $salt  - The decryption salt
     * @param string $encrypted  - The data to decrypt
     * @param string $iv  - 16-byte string (highly recommended)
     *
     * @return string - encrypted data
     */
    public static function openDecrypt($salt, $encrypted, $iv)
    {
        $decrypted = openssl_decrypt($encrypted, 'AES-128-CBC', $salt, true, $iv);

        return base64_decode($decrypted);
    }

    /**
     * Function to encrypt data
     *
     * @since 1.1.1
     *
     * @param string $salt  - The encryption salt
     * @param string $data  - The data to encrypt
     * @param string $iv  - 16-byte string (highly recommended)
     *
     * @return string - encrypted data
     */
    public static function openEncrypt($salt, $data, $iv)
    {
        return openssl_encrypt(base64_encode($data), 'AES-128-CBC', $salt, true, $iv);
    }

	/**
	 * Function to fill an array with a string x times
	 * 
	 * @since 1.0.0
	 * 
	 * @param string $string - The string to pad the array with
	 * @param int $count - the number of times to fill the array
	 * 
	 * @return array of results
	 */
	public static function fillArray($string, $count)
	{
		$arr = array();
		
		for ($i = 0; $i < $count; $i++)
		{
			$arr[] = $string;
		}//endfor
		
		return $arr;		
	}// end function

	/**
	 * Function to convert a date string to another date format
	 *
	 * @since 1.0.0
	 *
	 * @param string $date  - The date string
	 * @param string $format (OPTIONAL)  - The date format
	 *
	 * @return string - The converted date
	 */
	public static function formatDate($date, $format = 'Y-m-d H:i:s')
	{
		if (!empty($date))
		{
			$dateObj = new DateTime($date);
			return $dateObj->format($format);
		}
		
		return date($format);
	}

    /**
     * Function to generate salt key
     *
     * @since 1.0.0
     *
     * @param int $length - The size of the salt
     * @param bool $special_chars - Include special chars (true or false)
     * @param bool $numbers - Include numbers (true or false)
     * @param bool $letters - Include letters (true or false)
     *
     * @return string $salt - encrypted Data
     *
     */
    public static function genKey($length = 4, $special_chars = true, $numbers = true, $letters = true)
    {
        $salt = "";

        if($length > 0) 
        { 
            for($i = 1; $i <= $length; $i++) 
            {
                mt_srand((double)microtime() * 1000000);

                if ($numbers && $letters)
                    $num = mt_rand(1,72);
                else if ($numbers && !$letters)
                    $num = mt_rand(27,36);
                else if (!$numbers && !$letters)
                    $num = mt_rand(37,42);

                $salt .= self::assignRandValue($num, $special_chars);
            }
        }
        return $salt;
    }

	/**
	 * Function to display AgentCubed error string
	 *
	 * @since 1.0.0
	 *
	 * @param object $acResponse  - The agent cubed response
	 *
	 * @return string - The error string
	 */
    public static function getAcErrorString($acResponse)
    {
        $msg = "Error - AgentCubed returned an error: \n";
        if (isset($acResponse['AddLeadsUsingXMLStringResult']['PortalServiceReturnDataList']))
        {
            $ref = $acResponse['AddLeadsUsingXMLStringResult']['PortalServiceReturnDataList'];
            if (isset($ref['StatusCode']))
                $msg .= " : StatusCode = " . $ref['StatusCode'];
            if (isset($ref['StatusDescription']))
                $msg .= " : StatusDescription = " . $ref['StatusDescription'];
            if (isset($ref['StatusDetails']))
                $msg .= " : StatusDetails = " . $ref['StatusDetails'];
            $msg .= "\n";
        }
        $msg .= "Please report this to your agent.";
        return $msg;
    }

	/**
	 * Function to get the real name of a cookie minus the prefix
	 *
	 * @since 1.0.0
	 *
	 * @param string $key  - The cookie name
	 * @param string $prefix  - The cookie prefix to remove
	 *
	 * @return string - The cookie name minus the prefix
	 */
	public static function getCookieName($key, $prefix)
	{
		return preg_replace("/{$prefix}/", "", $key);
	}

	/**
	 * Function to return the results of a curl call to a website
	 *
	 * @since 1.0.0
	 *
	 * @param string $url  - The URL to call
	 * @param string $host Optional - The host that will server the results
	 * @param array $params Optional - The arguments passed to the URL
	 * @param bool $post Optional - Is this a post request
	 * @param array $headers Optional - headers array
	 * @param bool $debug Optional - Debug messages
	 *
	 * @return string $results - The processed contents
	 */
	public static function getCurlResults($url, $host = "", $params = array(), $post = false, $headers = array(), $debug = false)
	{
        $ch = curl_init();

		// if there are GET params available append them to the URL
		if (!$post && !empty($params) && is_array($params))
		{
			// Update URL to container Query String of Paramaters */
			$url .= '?' . http_build_query($params);
		}//endif

		curl_setopt($ch, CURLOPT_VERBOSE, $debug);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		//curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . "/cacert.pem");
	
		if ($post)
		{
			curl_setopt($ch, CURLOPT_POST, true);

			if (is_array($params))
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
			else
				curl_setopt($ch, CURLOPT_POSTFIELDS, $params);				
		}//endif
	
		if (!empty($host))
		{
			$headers[] = "Host: $host";
		}//endif

		if (!empty($headers))
		{
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}
	
		$results = curl_exec($ch);

		curl_close ($ch);
	
		return $results;
	}// end function

	/**
	 * Function to get the current domain being accessed
	 *
	 * @since 1.0.0
	 *
	 * @return string - the current domain name
	 */
	public static function getCurrentDomain() 
	{        		
		$domain = $_SERVER['SERVER_NAME']; 
        $domain = preg_replace("/www\./", "", $domain);

        return $domain;
	}
	
	/**
	 * Function to return the cookie object
	 *
	 * @since 1.0.0
	 *
	 * @param void
	 *
	 * @return array - The cookies
	 */
	public static function getDomainCookie()
	{
		return $_COOKIE;
	}

    /**
     * Get a image's true path based on known file locations
     *
     * @since 1.0.0
     *
     * @param string $file_name - The file name
     * @param string $base_path - The base search path
     * @param $include_base - Optional: Return the base path with the path or just the child path
     * @param $include_leading_slash - Optional: Include the trailing slash
     * @param $include_trailing_slash - Optional: Include the trailing slash
     *
     * @return string $found_path
     *
     */
    public static function getFilePath($file_name, $base_path, $include_base = true, $include_leading_slash = false, $include_trailing_slash = false)
    {
        $found_path = "";
        
        $file_search_results = `find {$base_path} -name '{$file_name}'`;
        $file_search_results = explode("\n", $file_search_results);
    
        if (!empty($file_search_results))
        {
            foreach ($file_search_results as $file)
            {
                $file = trim($file);
                
                if (file_exists($file))
                {
                    $reg_ex = preg_quote($base_path, "/");
                    
                    $found_path = preg_replace("/$reg_ex/", "", dirname($file));
                    
                    if ($include_leading_slash && !preg_match("/^\//",$found_path))
                        $found_path = '/'. $found_path;
                    else
                        $found_path = preg_replace("/^\//", "", $found_path);
                    
                    if ($include_trailing_slash && !preg_match("/\/$/",$found_path))
                        $found_path .= '/';
                    else
                        $found_path = preg_replace("/\/$/", "", $found_path);

                    break;
                }
            }
        }
       
        return $found_path;
    }

	/**
	 * Function to determine if a name contains image extensions
	 *
	 * @since 1.0.0
	 *
	 * @param int $num - The number to get the ordinal for
	 *
	 * @return string $suffix - The ordinal
	 */
     public static function getOrdinalSuffix($num) 
     {
        $suffixes = array("st", "nd", "rd");

        $lastDigit = $num % 10;
    
        if(($num < 20 && $num > 9) || $lastDigit == 0 || $lastDigit > 3) 
            return "th";
    
        return $suffixes[$lastDigit - 1];
    }

	/**
	 * Function to determine if a name contains image extensions
	 *
	 * @since 1.0.0
	 *
	 * @param string $file_name - File name
	 *
	 * @return boolean $valid - The status of the code check
	 */
	public static function isImage($file_name)
	{
		$file_name = strtolower($file_name);
	
		return preg_match( "/\.(jpg|jpeg|png|gif)/", $file_name);	
	}
	
	/**
	 * Function to check the validity of an email
	 *
	 * @since 1.0.0
	 *
	 * @param string $email  - The email address
	 *
	 * @return boolean - The status of whether its valid or not
	 */
	public static function isValidEmail($email)
	{
		// checks proper syntax
		if (preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9._-]+)+$/", $email))
		{
			// gets domain name
			list($username,$domain)=preg_split("/\@/", $email);
			
			// Code finds current server bindings false took out on 10/5/2015
			// checks for if MX records in the DNS
			//if(!checkdnsrr($domain, 'MX')) {
			//	return false;
			//}
			
			// Code taken out because it fails every time
			// attempts a socket connection to mail server
			//if(!@fsockopen($domain, 25, $errno, $errstr, 30)) {
			//	return false;
			//}
			
			return true;
		}
		return false;
 	}
	
	/**
	 * Function to get the last value of an array
	 * 
	 * @since 1.0.0
	 * 
	 * @param array $array - The array
	 * 
	 * @return the last array value
	 */
	public static function lastVal($array)
	{ 
	    return $array{count($array) - 1}; 
	}//endpublic static function 

	/**
	 * Function to notify slack of incoming messages
	 *
	 * @since 1.0.0
	 *
	 * @param string $username  - The title of the message
	 * @param string $text  - The text to display
	 * @param string $icon_key  - The type of message (see configuraiton file on the slack service)
	 * @param string $channel  - The slack channel to post to
	 *
	 * @return void
	 */
    public static function notifySlack($username, $text, $icon_key, $channel = '#server-activity')
    {
		// On external servers other than VPS, create a folder /_scripts/slack and place config.inc.php inside
		// And change the line below to require_once(SCRIPTS_ROOT . "/slack/config.inc.php"); 
		$url = "https://services.hpaexchange.com/slack/";
		
		$params = array(
                'channel' => $channel,
				'text' => $text,
				'icon_key' => $icon_key,
				'username' => $username,
		);

		$url .= '?' . http_build_query($params);
		
		file_get_contents($url);				
    }

	/**
	 * Function to convert an object into an array
	 *
	 * @since 1.0.0
	 *
	 * @param object $object  - The object
	 *
	 * @return array - The converted object
	 */
    public static function objectToArray( $object )
    {
        if( !is_object( $object ) && !is_array( $object ) )
            return $object;
        if( is_object( $object ) )
            $object = get_object_vars( $object );
        return array_map( 'objectToArray', $object );
    }

	/**
	 * Function to parse a URL to make it valid for manipulation
	 *
	 * @since 1.0.0
	 *
	 * @param string $url (Reference) - The URL to be parsed
	 *
	 * @return void
	 */
    public static function parseUrl($url)
    {
    	$url = trim($url);
    	$url = strtolower($url);
    	$url = preg_replace("/^https?\:?/", "", $url);
    	$url = preg_replace("/\//m", "", $url);
    	$url = preg_replace("/^(www\.)/", "", $url);

        return $url;
    }

    /**
     * Function to parse content
     *
     * @since 1.0.7
     *
     * @param array $params - Array of key/values to parse
     * @param string $content - content to parse
     *
     * @return string
     */
    public static function parseContent(&$params, $content)
    {
        foreach ($params as $key => $value)
        {
            if (!is_array($key) && !is_array($value))
                $content = preg_replace("/(\{\{|\[\[)$key(\}\}|\]\])/", $value, $content);
        }

        return $content;
    }

	/**
	 * Function to pretty print an array
	 *
	 * @since 1.0.0
	 *
	 * @param array $array  - The array to print
	 * @param bool $exit  - if the program should exit after printing
	 *
	 * @return void
	 */
	public static function print_pr( $array, $exit = false )
	{
    	echo "<pre>";
    	print_r($array);
    	echo "</pre>";
    	
    	if ($exit)
    	    exit;
	}

	/**
	 * Function to generate a random passowrd
	 *
	 * @param void
	 *
	 * @since 1.0.0
	 *
	 * @return string - Returns the generated password
	 */
	public static function randomPassword() 
    {
	    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
	    $pass = array(); //remember to declare $pass as an array
	    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache

	    for ($i = 0; $i < 8; $i++) {
	        $n = rand(0, $alphaLength);
	        $pass[] = $alphabet[$n];
	    }

	    return implode($pass); //turn the array into a string
	}

	/**
	 * Function to read user input from command line
	 *
	 * @since 1.0.0
	 *
	 * @return string $input - The information inputted by the user
	 */
	public static function readSTDIN()
	{
		$fr = fopen("php://stdin","r"); // open our file pointer to read from stdin
	    $input = fgets($fr,128);        // read a maximum of 128 characters
	    $input = rtrim($input);         // trim any trailing spaces.
	    fclose ($fr);                   // close the file handle
	    
		return $input;                  // return the text entered
	}// end func
	
	/**
	 * Function to delete session given a key
	 *
	 * @since 1.0.0
	 *
	 * @param string $find  - The cookie name to find
	 *
	 * @return void
	 */
	public static function sessionDelete($find)
	{
		foreach ($_SESSION as $key => $value)
		{
			if (preg_match("/{$find}/", $key))
			{
				if (LAPDI_Config::get('app.debug'))
					LAPDI_Log::info("Deleting session var [$key]");
					
				unset($_SESSION[$key]);
    		}
		}
	}

	/**
	 * Function to set a session variable
	 *
	 * @since 1.0.0
	 *
	 * @param string $key  - The session var name
	 * @param string $value  - The session var value
	 * @param string $prefix  - The session var prefix
	 *
	 * @return void
	 */
    public static function sessionSet($key, $value, $prefix = "")
    {
		$key = $prefix.$key;
		
		if(is_array($value))
		{
	        if (LAPDI_Config::get('app.debug'))
	            LAPDI_Log::info("Set session var [$key] = " . @json_encode($value));
	            
			$value = LAPDI_Settings::$cookie_prefix_encoded . @json_encode($value);
		}
		else
		{
	        if (LAPDI_Config::get('app.debug'))
	            LAPDI_Log::info("Set session var [$key] = " . @json_encode($value));
		}
		
		$_SESSION[$key] = $value;
	
        if (LAPDI_Config::get('app.debug'))
            LAPDI_Log::info("Set session var [$key] = $value");
	}

	/**
	 * Function to get a session var
	 *
	 * @since 1.0.0
	 *
	 * @param string $key  - The session var name
	 * @param string $prefix (OPTIONAL) - The session var prefix
	 *
	 * @return object - The cookie
	 */
    public static function sessionGet($key, $prefix = "")
    {
		$key = $prefix.$key;
		$value = null;
		
		if (isset($_SESSION[$key]))
		{
			$value = $_SESSION[$key];
			
			if (preg_match("/".LAPDI_Settings::$cookie_prefix_encoded."/", $value))
			{
				$value = preg_replace("/".LAPDI_Settings::$cookie_prefix_encoded."/", "", $value);

		        if (LAPDI_Config::get('app.debug'))
		            LAPDI_Log::info("Get encoded session var [$key] = $value");

				$value = stripslashes($value);
				$value = json_decode($value, true);

		        if (LAPDI_Config::get('app.debug'))
		            LAPDI_Log::info("Get decoded session var [$key] = " . @json_encode($value));
			}
			else
			{
		        if (LAPDI_Config::get('app.debug'))
		            LAPDI_Log::info("Get session var [$key] = $value");
			}
		}			

		
		return $value;
	}

	/**
	 * Function to display an html status
	 *
	 * @since 1.0.0
	 *
	 * @param string $msg  - The message
	 * @param string $color  - The color
	 * @param string $size  - The font size
	 *
	 * @return string - The status message 
	 */
	public static function status($msg, $color, $size = "inherit")
	{
		return "&nbsp;<span style='font-size: {$size}; color: {$color};'>{$msg}</span>";
	}

	/**
	 * Function to read user input from command line
	 *
	 * @param string $var - The variable to test
	 *
	 * @since 1.0.0
	 *
	 * @return boolean - Returns the boolean equivalent
	 */
	public static function toBool($var) 
	{        		
		if (empty($var)) 
			return false;
			
        $var = (string)$var;
		$var = trim($var);

		switch (strtolower($var)) 
		{
			case '1':
			case 'true':
			case 'on':
			case 'yes':
			case 'y':
				return true;
			default:
				return false;
		}
	}


    /**
     * Function to determine if the data is empty
     *
     * @param string $var - The variable to test
     *
     * @since 1.1.2
     *
     * @return boolean - Returns the boolean equivalent
     */
    public static function isEmpty($var)
    {
        if (empty($var) || (is_string($var) && strtolower($var) == 'null'))
            return true;

        return false;
    }

    /**
     * Remove special characters from a string - Taken from CMS 1.0 code
     *
     * @since 1.0.0
     *
     * @param string $str - The string to be converted
     *
     * @return string $str
     *
     */
    public static function utils_msword_conversion($str)
    {
    	$str = str_replace(chr(130), ',', $str);    // baseline single quote
    	$str = str_replace(chr(131), 'NLG', $str);  // florin
    	$str = str_replace(chr(132), '"', $str);    // baseline double quote
    	$str = str_replace(chr(133), '...', $str);  // ellipsis
    	$str = str_replace(chr(134), '**', $str);   // dagger (a second footnote)
    	$str = str_replace(chr(135), '***', $str);  // double dagger (a third footnote)
    	$str = str_replace(chr(136), '^', $str);    // circumflex accent
    	$str = str_replace(chr(137), 'o/oo', $str); // permile
    	$str = str_replace(chr(138), 'Sh', $str);   // S Hacek
    	$str = str_replace(chr(139), '<', $str);    // left single guillemet
    	// $str = str_replace(chr(140), 'OE', $str);   // OE ligature
    	$str = str_replace(chr(145), "'", $str);    // left single quote
    	$str = str_replace(chr(146), "'", $str);    // right single quote
    	// $str = str_replace(chr(147), '"', $str);    // left double quote
    	// $str = str_replace(chr(148), '"', $str);    // right double quote
    	$str = str_replace(chr(149), '-', $str);    // bullet
    	$str = str_replace(chr(150), '-–', $str);    // endash
    	$str = str_replace(chr(151), '--', $str);   // emdash
    	// $str = str_replace(chr(152), '~', $str);    // tilde accent
    	// $str = str_replace(chr(153), '(TM)', $str); // trademark ligature
    	$str = str_replace(chr(154), 'sh', $str);   // s Hacek
    	$str = str_replace(chr(155), '>', $str);    // right single guillemet
    	// $str = str_replace(chr(156), 'oe', $str);   // oe ligature
    	$str = str_replace(chr(159), 'Y', $str);    // Y Dieresis
    	$str = str_replace('°C', '&deg;C', $str);    // Celcius is used quite a lot so it makes sense to add this in
    	$str = str_replace('£', '&pound;', $str);
    	$str = str_replace("'", "'", $str);
    	$str = str_replace('"', '"', $str);
    	$str = str_replace('–', '&ndash;', $str);
    
    	return $str;
    }
}



/**
 * TSP_Helper
 *
 * @since 1.0.0
 *
 * @deprecated 2.0.0 Please use LAPDI_Helper instead
 *
 * @return void
 *
 */
class TSP_Helper extends LAPDI_Helper
{

}// end class