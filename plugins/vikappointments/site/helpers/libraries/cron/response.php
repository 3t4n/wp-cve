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
 * Class used to manage the response of a cronjob.
 *
 * @since 1.5
 * @since 1.7 Renamed from CronJobResponse.
 */
class VAPCronJobResponse
{
	/**
	 * The log of the response of the cron job execution.
	 *
	 * @var string
	 */
	private $content = '';

	/**
	 * The status of the response of the cron job execution.
	 * When the status is false, the content will be permanently stored.
	 *
	 * @var boolean
	 */
	private $status = true;

	/**
	 * The administrator will be notified only whether the content is not empty.
	 * When this parameter is true, the administrator will be notified via e-mail with the content.
	 *
	 * @var boolean
	 */
	private $notify = false;

	/**
	 * The last update of the response in millis.
	 *
	 * @var integer
	 */
	private $lastUpdate = null;

	/**
	 * The construct of the cron job response to initialize the required parameters of this object.
	 *
	 * @param 	string	 $content 	The response content.
	 * @param 	boolean  $status 	The response status.
	 * @param 	boolean  $notify 	If the response should be notified.
	 *
	 * @uses 	triggerUpdate()
	 */
	public function __construct($content = '', $status = true, $notify = false)
	{
		$this->content = $content;
		$this->status  = $status;
		$this->notify  = $notify;

		// last update done on the initialization of the object
		$this->triggerUpdate();
	}

	/**
	 * Check if the response contents are not empty.
	 *
	 * @return 	boolean	 True if the contents are set, otherwise false.
	 *
	 * @since 	1.6
	 */
	public function hasContent()
	{
		return !empty($this->content);
	}

	/**
	 * Overwrites the content of the response.
	 *
	 * @param 	string  $content	The content of the response.
	 *
	 * @return 	self	Returns this object to support chaining.
	 *
	 * @uses 	triggerUpdate()
	 */
	public function setContent($content)
	{
		$this->content = $content;

		return $this->triggerUpdate();
	}

	/**
	 * Appends the content of the response to the existing one.
	 *
	 * @param 	string  $content	The content of the response to append.
	 *
	 * @return 	self	Returns this object to support chaining.
	 *
	 * @uses 	triggerUpdate()
	 */
	public function appendContent($content)
	{
		$this->content .= $content;

		return $this->triggerUpdate();
	}

	/**
	 * Prepends the content of the response before the existing one.
	 *
	 * @param 	string  $content	The content of the response to prepend.
	 *
	 * @return 	self	Returns this object to support chaining.
	 *
	 * @uses 	triggerUpdate()
	 */
	public function prependContent($content)
	{
		$this->content = $content . $this->content;

		return $this->triggerUpdate();
	}


	/**
	 * Returns the content of the response.
	 *
	 * @return 	string 	The content of the response.
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * Sets the status of the response.
	 *
	 * @param 	boolean	 $status 	The status of the response (true or false).
	 *
	 * @return 	self	 Returns this object to support chaining.
	 *
	 * @uses 	triggerUpdate()
	 */
	public function setStatus($status)
	{
		$this->status = $status;

		return $this->triggerUpdate();
	}

	/**
	 * Returns true if the status of the response is equals to true.
	 *
	 * @return  boolean  The verified status of the response.
	 */
	public function isVerified()
	{
		return $this->status;
	}

	/**
	 * Returns true if the status of the response is equals to false.
	 *
	 * @return 	boolean  The error status of the response.
	 */
	public function isError()
	{
		return !$this->status;
	}

	/**
	 * Sets the notify setting of the response.
	 *
	 * @param 	boolean	 $notify 	The notify setting of the response (true or false).
	 *
	 * @return 	self	 Returns this object to support chaining.
	 *
	 * @uses 	triggerUpdate()
	 */
	public function setNotify($notify)
	{
		$this->notify = $notify;

		return $this->triggerUpdate();
	}

	/**
	 * Returns true if the administrator have to be notified.
	 *
	 * @return 	boolean	 The notify status of the response.
	 */
	public function isNotify()
	{
		return $this->notify;
	}

	/**
	 * Refreshes the last update time of the response.
	 *
	 * @return 	self 	Returns this object to support chaining.
	 */
	protected function triggerUpdate()
	{
		$this->lastUpdate = JFactory::getDate()->toSql();

		return $this;
	}

	/**
	 * Returns the last update of the response.
	 *
	 * @return 	string  The last update military date string.
	 */
	public function getLastUpdate()
	{
		return $this->lastUpdate;
	}
}

/**
 * Register a class alias for backward compatibility.
 *
 * @deprecated 1.8
 */
class_alias('VAPCronJobResponse', 'CronJobResponse');
