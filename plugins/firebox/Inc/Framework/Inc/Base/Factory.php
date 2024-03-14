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

class Factory
{
	/**
	 * Returns Site Data
	 * 
	 * @return  object
	 */
	public function getSiteData()
	{
		return new Factory\SiteData();
	}

	/**
	 * Returns the condition.
	 * 
	 * @param   string  $name
	 * 
	 * @return  Object
	 */
	public static function getCondition($name)
    {
        return \FPFramework\Base\Conditions\ConditionsHelper::getInstance()->getCondition($name);
    }
	
	/**
	 * WordPress Database Object
	 * 
	 * @return  object
	 */
	public function getDbo()
	{
		global $wpdb;
		return $wpdb;
	}

	/**
	 * Get Query Strings
	 * 
	 * @return  array
	 */
	public function getQueryStrings()
	{
		return isset($_GET) ? wp_unslash($_GET) : []; //phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}

	/**
	 * Retrieves the language code
	 * 
	 * @return  string
	 */
	public function getLanguage()
	{
		return new Factory\Language();
	}

	/**
	 * Retrieves public query variables that are recognized by WP_Query
	 * See https://core.trac.wordpress.org/browser/tags/5.3/src/wp-includes/class-wp-query.php#L530
	 * 
	 * @param   string  $var
	 * @param   string  $default
	 * 
	 * @return  string
	 */
    public function getQueryVar($var, $default = '')
    {
        return get_query_var($var, $default = '');
    }
	
	/**
	 * Retrives the date
	 * 
	 * @param   string  $date
	 * @param   object  $tz
	 */
    public function getDate($date = 'now', $tz = null)
    {
		return date_create($date ?? '', $tz);
	}
	
	/**
	 * Get the user by a given field
	 * 
	 * @param   string  $field
	 * @param   string  $value
	 * 
	 * @return  mixed
	 */
    public function getUser($field = '', $value = '')
    {
        return \FPFramework\Base\User::get($value, $field);
	}
	
	/**
	 * Get Config
	 * 
	 * @var  Object
	 */
	public static function getConfig()
	{
		return new Factory\Config();
	}
	
	/**
	 * Get referrer
	 * 
	 * @return  string
	 */
    public function getReferrer()
    {
        return wp_get_referer();
    }
	
	/**
	 * Returns the current page url
	 * 
	 * @return  string
	 */
	public function getURL()
	{
		return home_url(add_query_arg(NULL, NULL));
	}

	/**
	 * Get Device Type
	 * 
	 * @return  string
	 */
    public function getDevice()
    {
        return \FPFramework\Base\WebClient::getDeviceType();
    }

	/**
	 * Get Browser
	 * 
	 * @return  string
	 */
    public function getBrowser()
    {
        return \FPFramework\Base\WebClient::getBrowser();
	}

	/**
	 * Get OS
	 * 
	 * @return  string
	 */
    public function getOS()
    {
        return \FPFramework\Base\WebClient::getOS();
	}

	/**
	 * Get User Agent
	 * 
	 * @return  string
	 */
    public function getUserAgent()
    {
        return \FPFramework\Base\WebClient::getClient()->userAgent;
	}

	/**
	 * Get a GeoIP Instance
	 * 
	 * @param   string  $ip
	 * 
	 * @return  GeoIP
	 */
	public function getGeoIP($ip = null)
	{
		return new \FPFramework\Libs\Vendors\GeoIP\GeoIP($ip);
	}
	
	/**
	 * Get Session
	 * 
	 * @return  array
	 */
    public function getSession()
    {
		return new Factory\Session();
    }
	
	/**
	 * Get Cookie
	 * 
	 * @param   string  $cookie_name
	 * 
	 * @return  array
	 */
    public function getCookie($cookie_name = null)
    {
		return (new Factory\Cookie($cookie_name))->get();
    }
	
	/**
	 * Get Visitor ID
	 * 
	 * @return  array
	 */
    public function getVisitorID()
    {
        return \FPFramework\Helpers\VisitorToken::getInstance()->get();
    }

	/**
	 * Executes PHP code
	 * 
	 * @return  mixed
	 */
    public function getExecuter($php_code)
    {
        return new \FPFramework\Base\Executer($php_code);
	}
	
	/**
	 * Return Custom Post Type of post
	 * 
	 * @param   object  $post
	 * 
	 * @return  string
	 */
	public function getCustomPostType($post = null)
	{
		return get_post_type($post);
	}
}