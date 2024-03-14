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
 * VikAppointments custom field file handler.
 *
 * @since 1.7
 */
class VAPCustomFieldFile extends VAPCustomField
{
	/**
	 * Returns the name of the field type.
	 *
	 * @return 	string
	 */
	public function getType()
	{
		return JText::translate('VAPCUSTOMFTYPEOPTION7');
	}

	/**
	 * Extracts the value of the custom field and applies any
	 * sanitizing according to the settings of the field.
	 *
	 * @param 	array  &$args  The array data to fill-in in case of
	 *                         specific rules (name, e-mail, etc...).
	 *
	 * @return 	mixed  A scalar value of the custom field.
	 */
	protected function extract(&$args)
	{
		$input = JFactory::getApplication()->input;

		// get form field name
		$name = $this->getName();
		// get uploaded file from request
		$value = $input->files->get($this->getID(), null, 'array');

		// adjust structure to support multiple files
		$value = $this->getTmpFiles($value);

		// obtain old file from request
		$old = $input->getString('old_' . $this->getID(), '');

		// check if the file hasn't been uploaded
		if (!$value)
		{
			if (empty($old))
			{
				// attempt to fetch the old files by using the original custom field name
				$old = $input->getString($this->getID(), null);
			}

			// check if there is already an uploaded file for this field
			if (!empty($old))
			{
				// pushes the old file in the uploads list (might be an array)
				$args['uploads'][$name] = $old;
			}
		}
		else
		{
			// get file types filters
			$filters = $this->get('choose', '*');

			if ($this->get('multiple'))
			{
				// init array to support multiple files
				$args['uploads'][$name] = array();
			}

			foreach ($value as $file)
			{
				/**
				 * Prepend a random string to the file name,
				 * in order to make it as secret as possible.
				 *
				 * @since 1.7
				 */
				$secretName = md5(uniqid()) . '_' . $file['name'];
				// create full destination path
				$destination = VAPCUSTOMERS_UPLOADS . DIRECTORY_SEPARATOR . $secretName;

				// upload the file and get the response
				$result = VikAppointments::uploadFile($file, $destination, $filters);

				if (!$result->status)
				{
					// in case of multiple files, delete any other uploaded file
					// before throwing the exception
					if ($this->get('multiple'))
					{
						foreach ($args['uploads'][$name] as $tmp)
						{
							unlink(VAPCUSTOMERS_UPLOADS . DIRECTORY_SEPARATOR . $tmp);
						}
					}

					// unable to upload the file, throw error even if the field is optional
					throw new Exception(JText::translate($result->errno == 1 ? 'VAPFILEUPLOADERR2' : 'VAPFILEUPLOADERR1'));
				}

				// update the uploads map with the name of the file
				if ($this->get('multiple'))
				{
					// append file to the list
					$args['uploads'][$name][] = $result->name;
				}
				else
				{
					// set file within the map
					$args['uploads'][$name] = $result->name;
				}
			}
			
			if ($old)
			{
				// unlink the previous files, if exist
				foreach ((array) $old as $tmp)
				{
					unlink(VAPCUSTOMERS_UPLOADS . DIRECTORY_SEPARATOR . $tmp);
				}
			}
		}

		// in case of required field, make sure the file has been uploaded
		if ((int) $this->get('required') && empty($args['uploads'][$name]))
		{
			// missing file...
			throw new Exception(JText::translate('VAPERRINSUFFCUSTF'));
		}

		// do not return uploaded file, since it is included within
		// the uploads list of the received argument
		return '';
	}

	/**
	 * Validates the field value.
	 *
	 * @param 	mixed    $value  The field raw value.
	 *
	 * @return 	boolean  True if valid, false otherwise.
	 */
	protected function validate($value)
	{
		// always return true because the validation is made by the extractor method,
		// since the uploaded files are always located in a different var
		return true;
	}

	/**
	 * Returns an array of display data.
	 *
	 * @param 	array   $data  An array of display data.
	 *
	 * @return 	array
	 */
	protected function getDisplayData(array $data)
	{
		$data = parent::getDisplayData($data);

		$data['files'] = array();

		if ($data['value'])
		{
			// remove required class because there's already an uploaded file and,
			// since the input cannot hold a value, we cannot validate it
			$data['class'] = preg_replace("/\b\s*required\s*\b/", '', $data['class']);

			// then create a list of readable files
			foreach ((array) $data['value'] as $value)
			{
				$file = array();

				// strip secret prefix
				$file['name'] = preg_replace("/^[a-f0-9]+_/", '', $value);
				// create full path
				$file['path'] = VAPCUSTOMERS_UPLOADS . DIRECTORY_SEPARATOR . $value;
				// create URI
				$file['uri'] = VAPCUSTOMERS_UPLOADS_URI . $value;

				// register file details
				$data['files'][] = $file;
			}
		}

		return $data;
	}

	/**
	 * Adjusts the files stored in the temporary folder
	 * to be uploaded within the system.
	 *
	 * @param 	mixed 	$value  The temporary files.
	 *
	 * @return 	array   A multi-dimensional array of files.
	 */
	private function getTmpFiles($value)
	{
		if (!$value)
		{
			// no uploaded files
			return null;
		}

		if (!$this->get('multiple'))
		{
			// wrap uploaded file within an empty array,
			// so that we can handle single and multiple
			// uploads in the same way
			$value = array($value);
		}

		if (empty($value[0]['name']))
		{
			// invalid file
			return null;
		}

		return $value;
	}
}
