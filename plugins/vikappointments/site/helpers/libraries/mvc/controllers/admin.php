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

jimport('joomla.application.component.controlleradmin');

/**
 * Extends the JControllerAdmin methods.
 *
 * @since 	1.7
 */
class VAPControllerAdmin extends JControllerAdmin
{
	/**
	 * Method to save the submitted ordering values for records via AJAX.
	 *
	 * @return  void
	 */
	public function saveOrderAjax()
	{
		$input = JFactory::getApplication()->input;

		/**
		 * Added token validation.
		 *
		 * @since 1.7
		 */
		if (!JSession::checkToken())
		{
			// missing CSRF-proof token
			UIErrorFactory::raiseError(403, JText::translate('JINVALID_TOKEN'));
		}

		// retrieve order from request
		$order   = $input->get('order', array(), 'array');
		$filters = $input->get('filters', array(), 'array');

		// init table
		$table = $this->getModel()->getTable();

		// iterate specified records
		foreach ($order as $id => $ordering)
		{
			$data = array(
				'id'       => (int) $id,
				'ordering' => (int) $ordering,
			);

			// update ordering
			$table->save($data);
		}

		$where = '';

		if ($filters)
		{
			// create ordering conditions
			$dbo = JFactory::getDbo();

			$where = array();

			foreach ($filters as $k => $v)
			{
				// insert condition only if the table owns the specified property
				if (property_exists($table, $k))
				{
					$where[] = $dbo->qn($k) . ' = ' . $dbo->q($v);
				}
			}

			// join the conditions with AND glue
			$where = implode(' AND ', $where);
		}

		// rearrange table global ordering
		$table->reorder($where);

		// stop the process
		exit;
	}

	/**
	 * Echoes the given JSON by using the right content type.
	 *
	 * @param 	mixed  $json  Either a JSON string or a non-scalar value.
	 *
	 * @return 	void
	 */
	public function sendJSON($json)
	{
		VAPHttpDocument::getInstance(JFactory::getApplication())->json($json);
	}

	/**
	 * Method to get a model object.
	 *
	 * @param   string  $name    The model name.
	 * @param   string  $prefix  The class prefix.
	 * @param   array   $config  Configuration array for model.
	 *
	 * @return  mixed   Model object on success; otherwise false on failure.
	 */
	public function getModel($name = '', $prefix = '', $config = array())
	{
		if (!$name)
		{
			// use the name of the controller
			$name = ucfirst($this->getControllerName());
		}

		if (!$prefix)
		{
			// always use the default model prefix
			$prefix = 'VikAppointmentsModel';
		}

		// invoke parent
		return parent::getModel($name, $prefix, $config);
	}

	/**
	 * Returns the name of the controller.
	 *
	 * NOTE: the native getName() method returns the string on the
	 * left of "Controller", which is always "VikAppointments".
	 *
	 * @return 	string
	 */
	public function getControllerName()
	{
		// fetches the string on the right of "Controller"
		if (preg_match("/Controller(.*?)$/i", get_class($this), $match))
		{
			return strtolower($match[1]);
		}

		return null;
	}
}
