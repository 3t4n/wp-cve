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

namespace FPFramework\Helpers;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

class Video
{
	/**
	 * Returns the Video details.
	 * 
	 * Supported platforms:
	 * - YouTube
	 * - Vimeo
	 * 
	 * @param   string  $url
	 * 
	 * @return  array
	 */
	public static function getDetails($url)
	{
		$id = '';
		$provider = '';

		if (preg_match(self::getYouTubePatterns(), $url))
		{
			$id = self::getYouTubeID($url);
			$provider = 'youtube';
		}
		else if (preg_match(self::getVimeoPatterns(), $url))
		{
			$id = self::getVimeoID($url);
			$provider = 'vimeo';
		}
		
		return [
			'id' => $id,
			'provider' => $provider
		];
	}

	/**
	 * Get YouTube Patterns.
	 * 
	 * @return  string
	 */
	public static function getYouTubePatterns()
	{
		return '/^https?:\/\/((m|www)\.)?youtube\.com\/.+|^https?:\/\/youtu\.be\/.+/';
	}

	/**
	 * Get Vimeo Patterns.
	 * 
	 * @return  string
	 */
	public static function getVimeoPatterns()
	{
		return '/^https?:\/\/(www\.)?vimeo\.com\/.+/';
	}

	/**
	 * Get YouTube ID.
	 * 
	 * @param   string  $url
	 * 
	 * @return  string
	 */
	public static function getYouTubeID($url)
	{
		parse_str(wp_parse_url($url, PHP_URL_QUERY), $data);

		return isset($data['v']) ? $data['v'] : null;
	}

	/**
	 * Get Vimeo ID.
	 * 
	 * @param   string  $url
	 * 
	 * @return  string
	 */
	public static function getVimeoID($url)
	{
		return (int) substr(wp_parse_url($url, PHP_URL_PATH), 1);
	}
}