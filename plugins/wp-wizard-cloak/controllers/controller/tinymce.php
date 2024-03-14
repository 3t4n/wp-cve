<?php

/**
 * Introduce special type for controllers which render pages inside admin area
 * 
 * @author Pavel Kulbakin <p.kulbakin@gmail.com>
 */
abstract class PMLC_Controller_Tinymce extends PMLC_Controller_Admin {
	/**
	 * @see Controller::render()
	 */
	protected function render($viewPath = NULL)
	{
		add_filter('admin_body_class', create_function('', 'return "' . PMLC_Plugin::PREFIX . 'plugin";'));
		// assume template file name depending on calling function
		if (is_null($viewPath)) {
			$trace = debug_backtrace();
			$viewPath = str_replace('_', '/', preg_replace('%^' . preg_quote(PMLC_Plugin::PREFIX, '%') . '%', '', strtolower($trace[1]['class']))) . '/' . $trace[1]['function'];
		}
		
		parent::render($viewPath);
		
		die(); // do not render wordpress layout
	}
}