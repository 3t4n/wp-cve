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

VAPLoader::import('libraries.tax.factory');

/**
 * VikAppointments tax management view.
 *
 * @since 1.0
 */
class VikAppointmentsViewmanagetax extends JViewVAP
{
	/**
	 * VikAppointments view display method.
	 *
	 * @return 	void
	 */
	function display($tpl = null)
	{
		$dbo   = JFactory::getDbo();
		$app   = JFactory::getApplication();
		$input = $app->input;

		$ids  = $input->getUint('cid', array());
		$type = $ids ? 'edit' : 'new';
		
		// set the toolbar
		$this->addToolBar($type);
		
		$tax = null;
		
		if ($type == 'edit')
		{
			$q = $dbo->getQuery(true)
				->select('*')
				->from($dbo->qn('#__vikappointments_tax'))
				->where($dbo->qn('id') . ' = ' . $ids[0]);

			$dbo->setQuery($q, 0, 1);
			$tax = $dbo->loadObject();
			
			if ($tax)
			{
				$tax->rules = [];

				$q = $dbo->getQuery(true);

				$q->select('*')
					->from($dbo->qn('#__vikappointments_tax_rule'))
					->where($dbo->qn('id_tax') . ' = ' . $tax->id)
					->order($dbo->qn('ordering') . ' ASC');

				$dbo->setQuery($q);
				
				foreach ($dbo->loadObjectList() as $rule)
				{
					// decode breakdown list
					$rule->breakdown = $rule->breakdown ? json_decode($rule->breakdown) : [];

					$tax->rules[] = $rule;
				}
			}
		}

		if (empty($tax))
		{
			$tax = (object) $this->getBlankItem();
		}

		// use tax data stored in user state
		$this->injectUserStateData($tax, 'vap.tax.data');
		
		$this->tax = $tax;

		// display the template
		parent::display($tpl);
	}

	/**
	 * Setting the toolbar.
	 *
	 * @param 	string 	$type 	The request type (new or edit).
	 *
	 * @return 	void
	 */
	protected function addToolBar($type)
	{
		// add menu title and some buttons to the page
		if ($type == 'edit')
		{
			JToolBarHelper::title(JText::translate('VAPMAINTITLEEDITTAX'), 'vikappointments');
		}
		else
		{
			JToolBarHelper::title(JText::translate('VAPMAINTITLENEWTAX'), 'vikappointments');
		}
		
		$user = JFactory::getUser();
		
		if ($user->authorise('core.edit', 'com_vikappointments')
			|| $user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::apply('tax.save', JText::translate('VAPSAVE'));
			JToolbarHelper::save('tax.saveclose', JText::translate('VAPSAVEANDCLOSE'));
		}

		if ($user->authorise('core.edit', 'com_vikappointments')
			&& $user->authorise('core.create', 'com_vikappointments'))
		{
			JToolbarHelper::save2new('tax.savenew', JText::translate('VAPSAVEANDNEW'));
		}
		
		JToolBarHelper::cancel('tax.cancel', $type == 'edit' ? 'JTOOLBAR_CLOSE' : 'JTOOLBAR_CANCEL');
	}

	/**
	 * Returns a blank item.
	 *
	 * @return 	array 	A blank item for new requests.
	 */
	protected function getBlankItem()
	{
		return array(
			'id'          => 0,
			'name'        => '',
			'description' => '',
			'rules'       => array(),
		);
	}
}
