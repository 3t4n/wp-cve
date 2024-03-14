<?php
/** 
 * @package     VikAppointments
 * @subpackage  core
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

/**
 * Implementor used to extract an iCalendar from remote URL.
 * 
 * @since 1.7.3 
 */
class VAPIcalReaderHttp implements VAPIcalReader
{
	/**
	 * The remote URL.
	 * 
	 * @var string
	 */
	protected $url;

	/**
	 * The request headers.
	 * 
	 * @var array
	 */
	protected $headers;

	/**
	 * Class constructor.
	 * 
	 * @param 	string  $url
	 * @param 	array   $headers
	 */
	public function __construct($url, array $headers = [])
	{
		$this->url     = $url;
		$this->headers = $headers;
	}

	/**
	 * Extracts the iCalendar buffer from a remote host.
	 * 
	 * @return  string  The iCalendar string.
	 */
	public function load()
	{
		$http = new JHttp();

		// replace WEBCAL scheme with HTTPS, because cURL may not support it
		$url = preg_replace("/^webcal:\/\//", 'https://', $this->url);

		// make GET request
		$response = $http->get($url, $this->headers);

		if ($response->code != 200)
		{
			// fetch error from body (ignore HTML tags)
			$error = strip_tags($response->body);

			throw new Exception($error ? $error : 'Error', $response->code);
		}

		return $response->body;
	}
}
