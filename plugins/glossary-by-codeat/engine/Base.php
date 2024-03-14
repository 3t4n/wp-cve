<?php

/**
 * Glossary
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2020
 * @license   GPL 3.0+
 * @link      https://codeat.co
 */

namespace Glossary\Engine;

/**
 * Base skeleton of the plugin
 */
class Base {

	/**
	 * The settings of the plugin
	 *
	 * @var array
	 */
	public $settings = array();

	/**
	 * Default parameters to avoid hardcoding
	 *
	 * @var array
	 */
	public $default_parameters = array();

	/**
	 * Initialize the class
	 *
	 * @return bool
	 */
	public function initialize() {
		$this->settings           = \gl_get_settings();
		$this->default_parameters = array(
			'post_type'        => 'glossary',
			'taxonomy'         => 'glossary-cat',
			'filter_prefix'    => 'glossary',
			'css_class_prefix' => 'glossary',
		);

		return true;
	}

}
