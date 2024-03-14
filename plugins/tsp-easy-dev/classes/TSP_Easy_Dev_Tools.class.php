<?php	
if ( !class_exists( 'TSP_Easy_Dev_Tools' ) )
{
	/**
	 * API implementations for LAPDI Easy Dev Pro's Tools class - Includes very handy functions
	 * @package 	TSP_Easy_Dev
	 * @author 		sharrondenice, letaprodoit
	 * @author 		Sharron Denice, Let A Pro Do IT!
	 * @copyright 	2021 Let A Pro Do IT!
	 * @license 	APACHE v2.0 (http://www.apache.org/licenses/LICENSE-2.0)
	 * @version 	1.2.9
	 */
	class TSP_Easy_Dev_Tools
	{
		/**
		 * Determines if a particular browser is currently being used by the current user
		 *
		 * @since 1.0.1
		 *
		 * @param string $key Required the browser key, (values: IE, Chrome, FireFox, Opera, Safari)
		 * @param integer $version Optional the version of the browser (will match versions equal to and less than)
		 *
		 * @return boolean $matches - If the current browser matches users specs
		 */
	 	public static function this_browser ( $key, $version = null )
	 	{
	 		$match = false;
	 		
	 		$this_browser = $_SERVER['HTTP_USER_AGENT'];
	 		
			if ( strpos( $this_browser, 'MSIE') !== FALSE && $key == 'IE' )
			{
				if ( $version ) 
				{
					preg_match("/MSIE\/(.*) /", $this_browser, $matches);
					
					if ( array_key_exists( '1', $matches ) )
					{
						$this_version = $matches['1'];
						
						if ( intval($version) <= intval( $this_version ))
						{
							$match = true;
						}
					}//endif
				}//end if
				else
				{
					$match = true;
				}//end else
			}//end if
			elseif ( strpos( $this_browser, 'Chrome') !== FALSE && $key == 'Chrome' ) 
			{
				if ( $version ) 
				{
					preg_match("/Chrome\/(.*) /", $this_browser, $matches);
					
					if ( array_key_exists( '1', $matches ) )
					{
						$this_version = $matches['1'];
						
						if ( intval($version) <= intval( $this_version ))
						{
							$match = true;
						}
					}//endif
				}//end if
				else
				{
					$match = true;
				}//end else
			}//end elseif
			elseif ( strpos( $this_browser, 'Firefox') !== FALSE && $key == 'Firefox' ) 
			{
				if ( $version ) 
				{
					preg_match("/Firefox\/(.*) /", $this_browser, $matches);
					
					if ( array_key_exists( '1', $matches ) )
					{
						$this_version = $matches['1'];
						
						if ( intval($version) <= intval( $this_version ))
						{
							$match = true;
						}
					}//endif
				}//end if
				else
				{
					$match = true;
				}//end else
			}//end elseif
			elseif ( strpos( $this_browser, 'Opera') !== FALSE && $key == 'Opera' ) 
			{
				if ( $version ) 
				{
					preg_match("/Opera\/(.*) /", $this_browser, $matches);
					
					if ( array_key_exists( '1', $matches ) )
					{
						$this_version = $matches['1'];
						
						if ( intval($version) <= intval( $this_version ))
						{
							$match = true;
						}
					}//endif
				}//end if
				else
				{
					$match = true;
				}//end else
			}//end elseif
			elseif ( strpos( $this_browser, 'Safari') !== FALSE && $key == 'Safari' ) 
			{
				if ( $version ) 
				{
					preg_match("/Safari\/(.*) /", $this_browser, $matches);
					
					if ( array_key_exists( '1', $matches ) )
					{
						$this_version = $matches['1'];
						
						if ( intval($version) <= intval( $this_version ))
						{
							$match = true;
						}
					}//endif
				}//end if
				else
				{
					$match = true;
				}//end else
			}//end elseif
	 		
	 		return $match;
		}//end this_browser

		/**
		 * Determines if a particular browser is currently being used by the current user
		 *
		 * @since 1.0.1
		 *
		 * @param string $key Required the browser key, (values: IE, Chrome, FireFox, Opera, Safari)
		 * @param integer $version Optional the version of the browser (will match versions equal to and less than)
		 *
		 * @return boolean $matches - If the current browser matches users specs
		 */
		public static function api_call ($method, $data, $api_url, $api_url_backup = "", $app_id, $api_key, $ssl_verify = false)
		{
			$post = array(
		        'body' => array(
		            "app_id" => $app_id, 
		            "api_key" => $api_key,
		            "data" => json_encode($data)
		        ), 
		        'timeout' => 500,
		    );
									
			if($ssl_verify) {
				$post["sslverify"] = 0;
			}
		
			$endpoint = sprintf($api_url.'/%s/%s/%s', "json", "pilotpress", $method);
			$response = wp_remote_post($endpoint, $post);
		
			if(is_object($response))
			{
				if ($response->errors['http_request_failed']){
					$endpoint = sprintf($api_url_backup.'/%s/%s/%s', "json", "pilotpress", $method);
					$response = wp_remote_post($endpoint, $post);
				}
			}
		
			if(is_wp_error($response) || $response['response']['code'] == 500) {
				return false;
			} else {
				$body = json_decode(trim($response['body']), true);
			}
		
			if(isset($body["type"]) && $body["type"] == "error") {
				return false;
			} else {
				return $body["pilotpress"];
			}
		}//endfunction
	}//end TSP_Easy_Dev_Tools
}//endif	