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

if (!class_exists('UriUpdateHandler'))
{
	/**
	 * This class is used to handle the extra query field for the extension updates.
	 *
	 * USAGE:
	 *
	 * $update = new UriUpdateHandler(); // or new UriUpdateHandler('com_example')
	 *
	 * $update->addExtraField('order_number', $order_number);
	 * $update->addExtraField('domain', $domain);
	 *
	 * // OR
	 *
	 * $update->setExtraFields(array(
	 * 		'order_number' 	=> $order_number,
	 * 		'domain' 		=> $domain,
	 * ));
	 *
	 * $update->register();
	 *
	 * In case the component didn't own the <schemapath> XML tag, it suggested to launch
	 * also the function below to update correctly the schema version:
	 *
	 * $update->checkSchema($component_version);
	 * 
	 * @since 1.6
	 */
	class UriUpdateHandler
	{
		/**
		 * The component (or plugin/module) instance that need to be updated.
		 *
		 * @var mixed
		 */
		private $component = null;

		/**
		 * The query string containing the extra fields to append to the update URI.
		 *
		 * @var string
		 */
		private $extraFields = '';

		/**
		 * Class constructor.
		 * @param 	mixed 	$element 	The element to load. Null to load the current component.
		 *
		 * @uses 	getComponent()
		 */
		public function __construct($element = null)
		{
			$this->getComponent($element);
		}

		/**
		 * Load the specified/current component.
		 *
		 * @param 	mixed 	$element 	The element to load. Null to load the current component.
		 *
		 * @return 	mixed 	The loaded component.
		 */
		public function getComponent($element = null)
		{
			if ($element === null)
			{
				$element = JFactory::getApplication()->input->get('option');
			}

			JLoader::import('joomla.application.component.helper');
			$this->component = JComponentHelper::getComponent($element);

			return $this->component;
		}

		/**
		 * Returns the registered extra fields.
		 * 
		 * @param 	boolean       $array  True to return an array of vars, false to obtain
		 *                                the full query string.
		 * 
		 * @return  string|array  The query string or an associative array of vars.
		 * 
		 * @since 	1.7.3
		 */
		public function getExtraFields($array = false)
		{
			if (!$array)
			{
				// return query string
				return $this->extraFields;
			}

			// create a new URI instance and fetch registered vars
			$uri = new JUri($this->extraFields);
			return $uri->getQuery($array);
		}

		/**
		 * Set the parameters into the additional query string.
		 *
		 * @param 	array 	$params  The associative array to push.
		 *
		 * @return 	self 	This object to support chaining.
		 *
		 * @uses 	addExtraField()
		 */
		public function setExtraFields(array $params = array())
		{
			$this->extraFields = '';

			foreach ($params as $key => $val )
			{
				$this->addExtraField($key, $val);
			}

			return $this;
		}

		/**
		 * Push a single value into the additional query string.
		 *
		 * @param 	string 	$key 	The name of the query param.
		 * @param 	mixed 	$val 	The value of the query param. If an array is specified, 
		 * 							the values will be added recursively.
		 *
		 * @return 	self 	This object to support chaining.
		 */
		public function addExtraField($key, $val)
		{
			if (is_scalar($val))
			{
				$this->extraFields .= (empty($this->extraFields) ? '' : '&amp;') . $key . "=" . urlencode($val);
			}
			else
			{
				foreach ($val as $inner)
				{
					$this->addExtraField($key . '[]', $inner);
				}
			}

			return $this;
		}

		/**
		 * Commit the changes by updating the extra_fields column of the 
		 * #__update_sites_extensions database table.
		 *
		 * @return 	boolean  True on success, otherwise false.
		 */
		public function register()
		{
			if (!$this->component)
			{
				return false;
			}

			$dbo = JFactory::getDbo();

			// load the update site record, if it exists
			$q = $dbo->getQuery(true);

			$q->select($dbo->qn('update_site_id'))
				->from($dbo->qn('#__update_sites_extensions'))
				->where($dbo->qn('extension_id') . ' = ' . $dbo->q($this->component->id));

			$dbo->setQuery($q);
			$updateSite = $dbo->loadResult();

			$success = false;

			if ($updateSite)
			{
				// update the update site record
				$q = $dbo->getQuery(true);

				$q->update($dbo->qn('#__update_sites'))
					->set($dbo->qn('extra_query') . ' = ' . $dbo->q($this->extraFields))
					->set($dbo->qn('enabled') . ' = 1')
					->set($dbo->qn('last_check_timestamp') . ' = 0')
					->where($dbo->qn('update_site_id') . ' = ' . $dbo->q($updateSite));

				$dbo->setQuery($q);
				$dbo->execute();

				$success = (bool) $dbo->getAffectedRows();

				// Delete any existing updates (essentially flushes the updates cache for this update site)
				$q = $dbo->getQuery(true);

				$q->delete('#__updates')
					->where($dbo->qn('update_site_id') . ' = ' . $dbo->q($updateSite));
				
				$dbo->setQuery($q);
				$dbo->execute();
			}

			return $success;
		}

		/**
		 * Check the schema of the extension to make sure the system will use
		 * the current version.
		 *
		 * @param 	string 	 $version 	The current version of the component.
		 *
		 * @return 	boolean  True if the schema has been altered, otherwise false.
		 */
		public function checkSchema($version)
		{
			if (!$this->component)
			{
				return false;
			}

			$ok = false;

			$dbo = JFactory::getDbo();

			$q = $dbo->getQuery(true);

			$q->select($dbo->qn('version_id'))
				->from($dbo->qn('#__schemas'))
				->where($dbo->qn('extension_id') . ' = ' . (int) $this->component->id);
			
			$dbo->setQuery($q, 0, 1);
			$current = $dbo->loadResult();

			if ($current)
			{
				if ($current == $version)
				{
					$ok = true;
				}
				else
				{
					$q->clear()
						->delete($dbo->qn('#__schemas'))
						->where($dbo->qn('extension_id') . ' = ' . (int) $this->component->id);

					$dbo->setQuery($q);
					$dbo->execute();
				}
			}

			if (!$ok)
			{
				$q->clear()
					->insert($dbo->qn('#__schemas'))
					->columns(array($dbo->qn('extension_id'), $dbo->qn('version_id')))
					->values($this->component->id . ', ' . $dbo->q($version));

				$dbo->setQuery($q);
				$ok = (bool) $dbo->execute();
			}

			return $ok;
		}

	}
}
