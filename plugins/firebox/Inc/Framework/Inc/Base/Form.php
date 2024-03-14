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

namespace FPFramework\Base;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

use FPFramework\Libs\Registry;
use FPFramework\Base\FieldsParser;
use FPFramework\Helpers\HTML;

class Form
{
	/**
	 * All the form settings.
	 * 
	 * @var  array  $form
	 */
	protected $form_settings;

	/**
	 * Form options.
	 * 
	 * @var  array  $options
	 */
	protected $options;

	public function __construct($form_settings = [], $options = [])
	{
		$this->form_settings = $form_settings;
		$defaultOptions = method_exists($this, 'getDefaultOptions') ? $this->getDefaultOptions() : [];
		$this->options = wp_parse_args($options, $defaultOptions);
	}

	/**
	 * Renders the form
	 * 
	 * @return  void
	 */
	public function render()
	{
		$fieldsParser = new FieldsParser($this->options);

		$formOutput = $this->form_settings;

		// check if were given a form that contains section of fields
		if (is_array($this->form_settings) && isset($this->form_settings['data']))
		{
			// render all fields
			ob_start();
			foreach ($this->form_settings['data'] as $name => $section)
			{
				$fieldsParser->renderContentFields($section);
			}
			$formOutput = ob_get_contents();
			ob_end_clean();
		}

		// render content without form
		$render_form = isset($this->options['render_form']) ? (bool) $this->options['render_form'] : false;
		if (!$render_form)
		{
			return $formOutput;
		}

		// render content with form
		$this->options['content'] = $formOutput;

		return fpframework()->renderer->form->render('form', $this->options, true);
	}

	/**
	 * Returns the default form options
	 * 
	 * @return  array
	 */
	protected function getDefaultOptions()
	{
		return [
			'method' => 'post',
			'action' => 'options.php',
			'render_form' => true,
			'tabs_menu_sticky' => false,
			'mobile_menu' => false,
			'vertical' => false,
			'button_label' => 'FPF_SAVE',
			'button_classes' => 'fpf-button large primary',
		];
	}

	/**
	 * Set form data
	 * 
	 * @return  void
	 */
	public function setForm($form)
	{
		$this->form_settings = $form;
	}

	/**
	 * Sets whether to render the form open/close tags or not.
	 * 
	 * @return  void
	 */
	public function setRenderFormGroup($render)
	{
		$this->options['render_form'] = $render;
	}

	/**
	 * Sets the form action attribute
	 * 
	 * @param   string
	 * 
	 * @return  void
	 */
	public function setFormAction($action)
	{
		$this->options['action'] = $action;
	}

	/**
	 * Sets the form method attribute
	 * 
	 * @param   string
	 * 
	 * @return  void
	 */
	public function setFormMethod($method)
	{
		$this->options['method'] = $method;
	}

	/**
	 * Sets whether the tabs menu item will be sticky
	 * 
	 * @param   string
	 * 
	 * @return  void
	 */
	public function setTabsMenuSticky($tabs_menu_sticky)
	{
		$this->options['tabs_menu_sticky'] = $tabs_menu_sticky;
	}

	/**
	 * Sets whether a mobile menu will appear to navigate between tabs
	 * 
	 * @param   string
	 * 
	 * @return  void
	 */
	public function setMobileMenu($mobile_menu)
	{
		$this->options['mobile_menu'] = $mobile_menu;
	}
}