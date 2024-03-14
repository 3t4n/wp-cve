<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FPFramework\Base;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FPFramework\Helpers\Vendors\Mobile_Detect;

class WebClient
{
	/**
	 *  WordPress Application Client
	 *
	 *  @var  object
	 */
	public static $client;

	/**
	 *  Get visitor's Device Type
	 * 
	 *  @param	 string	   $ua User Agent string, if null use the implicit one from the server's enviroment
	 *
	 *  @return  string    The client's device type. Can be: tablet, mobile, desktop
	 */
	public static function getDeviceType($ua = null)
	{
        $detect = new Mobile_Detect(null, $ua);

        return ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'mobile') : 'desktop');
	}

	/**
	 *  Get visitor's Operating System
	 *
	 *  @param	 string	    $ua User Agent string, if null use the implicit one from the server's enviroment
	 * 
	 *  @return  string     Possible values: any of JApplicationWebClient's OS constants (except 'iphone' and 'ipad'), 
     *                                       'ios', 'chromeos'
	 */
	public static function getOS($ua = null)
	{
        // detect iOS and CromeOS (not handled by JApplicationWebClient)
        $ua = self::getClient($ua)->userAgent;

		if (!$ua)
		{
			return;
		}
		
        $ios_regex = '/iPhone|iPad|iPod/i';
        if (preg_match($ios_regex, $ua))
        {
            return 'ios';
        }

        $chromeos_regex = '/CrOS/i';
        if (preg_match($chromeos_regex, $ua))
        {
            return 'chromeos';
        }

        // use JApplicationWebClient for OS detection
		$platformInt = self::getClient($ua)->platform;
		$constants   = self::getClientConstants();
		
		if (isset($constants[$platformInt]))
		{
			return strtolower($constants[$platformInt]);
		}
	}

	/**
	 *  Get visitor's Browser name / version
	 * 
	 *  @param	 string	   $ua User Agent string, if null use the implicit one from the server's enviroment
	 *
	 *  @return  array
	 */
	public static function getBrowser($ua = null)
	{
		$client     = self::getClient($ua);
		$browserInt = $client->browser;
		$constants  = self::getClientConstants();

		if (isset($constants[$browserInt]))
		{
			return array(
				'name'    => strtolower($constants[$browserInt]),
				'version' => $client->browserVersion
			);
		}
	}

	/**
	 *  Get the constants from JApplicationWebClient as an array using the Reflection API
	 *
	 *  @return  array
	 */
	private static function getClientConstants()
	{
		$r = new \ReflectionClass('\\FPFramework\\Helpers\\Vendors\\WebClient');
		$constantsArray = $r->getConstants();

		// flip the associative array
		return array_flip($constantsArray);
	}

	/**
	 *  Get the Application Client helper
	 * 
	 *  @param	 string	   $ua User Agent string, if null use the implicit one from the server's enviroment
	 *
	 *  @return  object
	 */
	public static function getClient($ua = null)
	{
		if (is_object(self::$client) && $ua == null)
		{
			return self::$client;
		}

		return (self::$client = new \FPFramework\Helpers\Vendors\WebClient($ua));
	}
}