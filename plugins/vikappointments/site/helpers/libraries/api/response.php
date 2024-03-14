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
 * The API response wrapper.
 *
 * @since 1.7
 */
class VAPApiResponse
{
	/**
	 * The status of the response. True for success, false on failure.
	 *
	 * @var boolean
	 */
	private $status = false;

	/**
	 * The text description of the response.
	 *
	 * @var string
	 */
	private $content = '';

	/**
	 * The initial timestamp in seconds of the creation of this object.
	 *
	 * @var integer
	 */
	private $startTime = 0;

	/**
	 * Used to allow the events to force a specific content type.
	 * If not specified, the default application/json will be used.
	 *
	 * @var null|string
	 */
	private $contentType = 'application/json';

	/**
	 * Keeps track of the request payload.
	 *
	 * @var null|string
	 */
	private $payload = null;

	/**
	 * Class constructor.
	 * 
	 * @param 	boolean  $status   True for success response, otherwise false.
	 * @param 	string   $content  The text description of the response.
	 *
	 * @uses 	setStatus()   Set the status of the response.
	 * @uses 	setContent()  Set the content of the response.
	 */
	public function __construct($status = false, $content = '')
	{
		$this->setStatus($status)->setContent($content);

		$this->startTime = microtime(true);
	}

	/**
	 * Set the status of the response.
	 *
	 * @param 	boolean  $status  True for success response, false otherwise.
	 *
	 * @return 	self     This object to support chaining.
	 */
	public function setStatus($status)
	{
		$this->status = $status;

		return $this;
	}

	/**
	 * Return true if the status of the response is success, false otherwise.
	 *
	 * @return 	boolean
	 */
	public function isVerified()
	{
		return $this->status == true;
	}

	/**
	 * Return true if the status of the response is failure, false otherwise.
	 *
	 * @return 	boolean
	 */
	public function isError()
	{
		return $this->status == false;
	}

	/**
	 * Set the text description of the response.
	 *
	 * @param 	mixed  $content  The content of the response.
	 *
	 * @return 	self   This object to support chaining.
	 */
	public function setContent($content)
	{
		if (!is_scalar($content))
		{
			// stringify non-scalar value
			$content = print_r($content, true);
		}

		$this->content = (string) $content;

		return $this;
	}

	/**
	 * Append some text to the existing description of the response.
	 *
	 * @param 	string  $content  The content of the response.
	 *
	 * @return 	self    This object to support chaining.
	 *
	 * @uses 	setContent()
	 */
	public function appendContent($content)
	{
		// keep current contents
		$tmp = $this->content;

		// set contents with default method
		$this->setContent($content);

		// prepend previous contents
		$this->content = $tmp . $this->content;

		return $this;
	}

	/**
	 * Prepend some text to the existing description of the response.
	 *
	 * @param 	string  $content  The content of the response.
	 *
	 * @return 	self    This object to support chaining.
	 *
	 * @uses 	setContent()
	 */
	public function prependContent($content)
	{
		// keep current contents
		$tmp = $this->content;

		// set contents with default method
		$this->setContent($content);

		// append previous contents
		$this->content .= $tmp;

		return $this;
	}

	/**
	 * Clear the text description of the response.
	 *
	 * @return 	self  This object to support chaining.
	 *
	 * @uses 	setContent()
	 */
	public function clearContent()
	{
		return $this->setContent('');
	}

	/**
	 * Get the text description of the response.
	 *
	 * @return 	string 	The text description of the response.
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * Sets the given content type.
	 *
	 * @param 	string|null  $type  The content type to set.
	 *
	 * @return 	self  This object to support chaining.
	 */
	public function setContentType($type = null)
	{
		$this->contentType = $type;

		return $this;
	}

	/**
	 * Returns the currently set content type.
	 *
	 * @return 	string|null
	 */
	public function getContentType()
	{
		return $this->contentType;
	}

	/**
	 * Sets the given payload.
	 *
	 * @param 	mixed  $type  The payload to set.
	 *
	 * @return 	self   This object to support chaining.
	 */
	public function setPayload($payload = null)
	{
		if ($payload !== null && !is_scalar($payload))
		{
			// JSON encode received payload
			$this->payload = json_encode($payload);
		}
		else
		{
			$this->payload = $payload;
		}

		return $this;
	}

	/**
	 * Returns the currently set payload.
	 *
	 * @return 	string|null
	 */
	public function getPayload()
	{
		return $this->payload;
	}

	/**
	 * Get the initial timestamp of the response.
	 * The initial time is recorded during the creation of the response.
	 *
	 * @return 	integer  The initial timestamp in seconds.
	 */
	public function createdOn()
	{
		return $this->startTime;
	}

	/**
	 * Get the elapsed time between the current time and the initial time.
	 *
	 * @return 	integer  The elapsed time in seconds.
	 */
	public function getElapsedTime()
	{
		return microtime() - $this->startTime;
	}
}
