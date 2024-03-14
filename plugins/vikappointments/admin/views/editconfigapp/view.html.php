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

VAPLoader::import('libraries.backup.manager');

/**
 * VikAppointments applications configuration view.
 *
 * @since 1.7
 */
class VikAppointmentsVieweditconfigapp extends JViewVAP
{
	/**
	 * VikAppointments view display method.
	 *
	 * @return 	void
	 */
	function display($tpl = null)
	{
		$app = JFactory::getApplication();

		$root = JUri::root();

		$cookie = $app->input->cookie;

		$this->filters = [
			'preview_status' => $cookie->getBool('vikappointments_customizer_preview_status', true),
			'preview_page'   => $cookie->getString('vikappointments_customizer_preview_page', $root),
		];
		
		// set the toolbar
		$this->addToolBar();
		
		// get config
		$this->config = VAPFactory::getConfig();

		// fetch all the supported export types
		$this->backupExportTypes = VAPBackupManager::getExportTypes();

		// create customizer model
		$this->customizerModel = JModelVAP::getInstance('customizer');

		// fetch variables tree of CSS customizer
		$this->customizerTree = $this->customizerModel->getVarsTree();

		// fetch all the support pages
		$this->menuItems = [];

		if (VersionListener::isJoomla())
		{
			// get site menu
			$menu = JApplicationCms::getInstance('site')->getMenu()->getMenu();

			// check if we should use the SEF route or a plain link
			$is_sef = $app->get('sef') && $app->get('sef_rewrite');
			
			foreach ($menu as $item)
			{
				// build menu item name
				$text = str_repeat('- ', $item->level - 1) . $item->title;

				if ($item->language && $item->language !== '*')
				{
					// append language tag
					$text .= ' (' . $item->language . ')';
				}

				$value = $root;

				// exclude root in case of HOME
				if (!$item->home)
				{
					if ($is_sef)
					{
						// use SEF route
						$value .= $item->route;
					}
					else
					{
						// use plain link
						$value .= $item->link;
					}
				}

				// register menu item
				$this->menuItems[] = JHtml::fetch('select.option', $value, $text);
			}
		}
		else if (VersionListener::isWordpress())
		{
			// add empty option (HOME)
			$this->menuItems[] = JHtml::fetch('select.option', $root, JText::translate('JGLOBAL_SELECT_AN_OPTION'));

			// iterate all theme locations
			foreach (get_nav_menu_locations() as $l)
			{
				// get all menu items assigned to this location
				$items = wp_get_nav_menu_items($l);

				// check if we have some items because the function above might return false,
				// which could raise a warning since PHP 7 version
				if ($items)
				{
					// iterate all menu items assigned to this location
					foreach ($items as $item)
					{
						// register menu item
						$this->menuItems[] = JHtml::fetch('select.option', $item->url, $item->title);
					}
				}
			}
		}

		// display the template
		parent::display($tpl);
	}

	/**
	 * Setting the toolbar.
	 *
	 * @return 	void
	 */
	protected function addToolBar()
	{
		// add menu title and some buttons to the page
		JToolBarHelper::title(JText::translate('VAPMAINTITLECONFIG'), 'vikappointments');
		
		if (JFactory::getUser()->authorise('core.edit', 'com_vikappointments'))
		{
			JToolBarHelper::apply('configapp.save', JText::translate('VAPSAVE'));
			JToolBarHelper::divider();
		}
	
		JToolBarHelper::cancel('dashboard.cancel', 'JTOOLBAR_CLOSE');
	}
}
