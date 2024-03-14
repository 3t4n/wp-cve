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

namespace FPFramework\Base\Ui;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FPFramework\Libs\Translation;

class Tabs
{
	/**
	 * All tab data
	 * 
	 * @var  array
	 */
	private $settings;

	/**
	 * Contains an array of tab name,title
	 * 
	 * @var  array
	 */
	private $tab_settings;

	public function __construct($settings = [])
	{
		$this->settings = $settings;
		$this->tab_settings = $this->getTabsSettings();
	}

	/**
	 * Render Tabs
	 * 
	 * @return  void
	 */
	public function render()
	{
		if (!$this->canRun())
		{
			return;
		}

		$content = $this->getTabsContent();

		return fpframework()->renderer->admin->render('ui/tabs', [
			'tabs' => $this->tab_settings,
			'config' => $this->settings,
			'content' => $content
		], true);
	}

	/**
	 * Check whether we have any tabs item to render
	 * 
	 * @return  boolean
	 */
	protected function canRun()
	{
		return count($this->tab_settings);
	}

	/**
	 * Get tabs content
	 * 
	 * @return  void
	 */
	public function getTabsContent()
	{
		$content = [];
		
		$show_tab_title = isset($this->settings['show_tab_title']) ? $this->settings['show_tab_title'] : false;

		foreach ($this->settings['data'] as $tab_name => $section)
		{
			$output = '';

			$class = isset($section['class']) ? $section['class'] : '';
			
			// If we are showing the tab title and a title is set, show the tab title
			if ($show_tab_title && isset($section['title']))
			{
				$output .= \FPFramework\Helpers\HTML::renderHeading([
					'input_class' => ['fpf-tab-name-wrapper'],
					'heading_type' => 'h6',
					'title' => fpframework()->_($section['title'])
				]);
			}

			$output .= $section['content'];

			$content[$tab_name] = [
				'class' => $class,
				'content' => $output
			];
		}

		return $content;
	}

	/**
	 * Gets the Tabs settings
	 * 
	 * @return  array
	 */
	private function getTabsSettings()
	{
		if (!empty($this->tab_settings))
		{
			return $this->tab_settings;
		}

		if (!isset($this->settings['data']))
		{
			return [];
		}
		
		$tabs = [];

		foreach ($this->settings['data'] as $name => $setting)
		{
			$tabs[$name] = fpframework()->_($setting['title']);
		}
		
		return $tabs;
	}
}