<?php
/* 
Description: Generic HTTP Functions
 
Copyright 2020 Malcolm Shergold

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

if (!class_exists('StageShowLibHTTPIO')) 
{
	class StageShowLibHTTPIO // Define class
	{
		static function GetRequestedInt($paramId, $defaultVal = '')
		{
			$rtnVal = StageShowLibUtilsClass::GetHTTPInteger('request', $paramId, $defaultVal);
			
			return $rtnVal;
		}

		static function GetRequestedCurrency($paramId, $exitOnError = true)
		{
			$defaultVal = 0;
			$rtnVal = StageShowLibUtilsClass::GetHTTPNumber('request', $paramId, $defaultVal);
			if (preg_match('/([0-9\.]+)/', $rtnVal, $matches))
			{
				$rtnVal = $matches[1];
			}
			
			return $rtnVal;
		}
		
		static function GetRequestedString($paramId, $defaultVal = '')
		{
			return StageShowLibUtilsClass::GetHTTPTextElem('request', $paramId, $defaultVal);
		}
		
	    static function HTTPGet($url)
	    {	
			return self::HTTPRequest($url, '', 'GET');
		}
		
	    static function HTTPPost($url, $urlParams = '')
	    {	
			return self::HTTPRequest($url, $urlParams, 'POST');
		}
		
	    static function HTTPRequest($url, $urlParams = '', $method = '', $redirect = true)
	    {	
			if ($method == '')
			{
				$method = ($urlParams == '') ? 'GET' : 'POST';			
			}
			
			$HTTPResponse = self::HTTPAction($url, $urlParams, $method, $redirect);

			return $HTTPResponse; 
	    }
    
		static function HTTPAction($url, $urlParams = '', $method = 'POST', $redirect = true)
		{
			if( !class_exists( 'WP_Http' ) )
				include_once( ABSPATH . WPINC. '/class-http.php' );

			$args = array(
			'method' => $method,
			'body' => $urlParams,
			'sslverify' => false
			);
			
			if (!$redirect)
				$args['redirection'] = 0;
			
			$request = new WP_Http;
			$HTTPResult = $request->request( $url, $args );
			if ( is_wp_error($HTTPResult) )
			{
				$response['APIResponseText'] = '';
				$response['APIStatus'] = 'ERROR';
				$response['APIStatusMsg'] = $HTTPResult->get_error_message();
				$response['APIHeaders'] = '';
				$response['APICookies'] = array();
			}
			else
			{
				$response['APIResponseText'] = $HTTPResult['body'];
				$response['APIStatus'] = $HTTPResult['response']['code'];
				$response['APIStatusMsg'] = $HTTPResult['response']['message'];
				$response['APIHeaders'] = $HTTPResult['headers'];
				$response['APICookies'] = $HTTPResult['cookies'];
			}
/*			
			{
				StageShowLibEscapingClass::Safe_EchoHTML("HTTPRequest Called<br>");
				StageShowLibEscapingClass::Safe_EchoHTML("URL: $url<br>");
				StageShowLibEscapingClass::Safe_EchoHTML("METHOD: $method<br>");
				StageShowLibEscapingClass::Safe_EchoHTML("URL Params: <br>");
				print_r($urlParams);
				print_r($response, 'HTTPResponse:');
			}
*/
			return $response;			
		}

	}
}




