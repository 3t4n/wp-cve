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

VAPLoader::import('libraries.mvc.model');

/**
 * VikAppointments file model.
 *
 * @since 1.7
 */
class VikAppointmentsModelFile extends JModelVAP
{
	/**
	 * Entirely rewrite save method because the files
	 * do not use database tables.
	 *
	 * @param 	mixed  $data  Either an array or an object of data to save.
	 *
	 * @return 	mixed  The ID of the record on success, false otherwise.
	 */
	public function save($data)
	{
		$dispatcher = VAPFactory::getEventDispatcher();

		try
		{
			/**
			 * Trigger event to allow the plugins to bind the object that
			 * is going to be saved.
			 *
			 * @param 	mixed 	   &$data  The array/object to bind.
			 * @param 	JModelVAP  $model  The model instance.
			 *
			 * @return 	boolean    False to abort saving.
			 *
			 * @throws 	Exception  It is possible to throw an exception to abort
			 *                     the saving process and return a readable message.
			 *
			 * @since 	1.7
			 */
			if ($dispatcher->false('onBeforeSaveFile', array(&$data, $this)))
			{
				return false;
			}
		}
		catch (Exception $e)
		{
			// register the error thrown by the plugin and abort 
			$this->setError($e);

			return false;
		}

		// attempt to bind the source to the instance
		if (!$this->bind($data))
		{
			return false;
		}

		// run any sanity checks on the instance and verify that it is ready for storage
		if (!$this->check())
		{
			return false;
		}

		// attempt to store the file
		if (!$this->store())
		{
			return false;
		}

		/**
		 * Trigger event to allow the plugins to make something after
		 * saving a file.
		 *
		 * @param 	array 	   $args   The saved record.
		 * @param 	JModelVAP  $model  The model instance.
		 *
		 * @return 	void
		 *
		 * @since 	1.7
		 */
		$dispatcher->trigger('onAfterSaveFile', array($this->getData(), $this));

		return $this->id;
	}

	/**
	 * Method to bind an associative array or object to the Table instance. This
	 * method only binds properties that are publicly accessible and optionally
	 * takes an array of properties to ignore when binding.
	 *
	 * @param   array|object  $src  An associative array or object to bind.
	 *
	 * @return  boolean  True on success.
	 */
	protected function bind($src)
	{
		$src = (array) $src;

		// make sure the file path has been specified
		if (empty($src['id']))
		{
			$this->setError('Missing file path');

			return false;
		}

		// check if the file was encoded
		if (strpos($src['id'], VAPBASE) !== 0 && strpos($src['id'], VAPADMIN) !== 0)
		{
			// decode file from base64
			$src['id'] = base64_decode($src['id']);
		}

		// register file
		$this->id = $src['id'];

		// register content to save
		$this->content = isset($src['content']) ? $src['content'] : '';

		return true;
	}

	/**
	 * Method to perform sanity checks to ensure they are safe to store.
	 *
	 * @return  boolean  True if the instance is sane and able to be stored.
	 */
	public function check()
	{
		// DO NOT INVOKE PARENT

		// make sure the file is located within a VikAppointments folder
		if (strpos($this->id, VAPBASE) !== 0 && strpos($this->id, VAPADMIN) !== 0)
		{
			// register error message
			$this->setError('Only files within VikAppointments can be created or updated.');

			return false;
		}

		return true;
	}

	/**
	 * Method to create/update a file.
	 *
	 * @return  boolean  True on success.
	 */
	public function store()
	{
		// open file resource
		$handle = fopen($this->id, 'wb');

		if (!$handle)
		{
			$this->setError('Unable to open file resource');

			return false;
		}

		// iterate until the number of bytes written is equals to the content length
		for ($bytes = 0; $bytes < strlen($this->content); $bytes += $chunk)
		{
			// write bytes starting from the last seek (0 initially)
			// and get the number of written bytes, which will now
			// be the new seek to use to catch the remaining substring
	        $chunk = fwrite($handle, substr($this->content, $bytes));

	        if ($chunk === false)
	        {
	        	// unable to write file
	            return false;
	        }
	    }

		// close file resource
		fclose($handle);

		// check whether the file exists
		return is_file($this->id);
	}

	/**
	 * Returns the table properties, useful to retrieve the information
	 * that have been registered while saving a record.
	 *
	 * @return 	array
	 */
	public function getData()
	{
		// return all public class properties
		return $this->getProperties();
	}

	/**
	 * Method to delete one or more records.
	 *
	 * @param   mixed    $ids   Either the record ID or a list of records.
	 *
	 * @return  boolean  True on success.
	 */
	public function delete($ids = null)
	{
		/**
		 * For security reasons, files cannot be deleted here.
		 */

		return false;
	}

	/**
	 * Basic item loading implementation.
	 *
	 * @param   mixed    $file  The file path  (plain or base64).
	 * @param   boolean  $new   For inheritance.
	 *
	 * @return 	mixed    The record object on success, null otherwise.
	 */
	public function getItem($file, $new = false)
	{
		// make sure the file exists
		if (!$file || !is_file($file))
		{
			// file not found, try to decode from base64
			if ($file)
			{
				$file = base64_decode($file);
			}

			if (!is_file($file))
			{
				// file not found
				$this->setError(sprintf('File [%s] not found', $file));
				
				return null;
			}
		}

		$data = new stdClass;
		$data->id      = $file;
		$data->content = '';

		// read file using a buffer
		$handle = fopen($file, 'r');

		while (!feof($handle))
		{
			$data->content .= fread($handle, 8192);
		}

		fclose($handle);

		return $data;
	}
}
