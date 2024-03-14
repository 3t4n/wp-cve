<?php

abstract class PeepSoAjaxCallback
{
	protected static $_instances = array();
	protected $_input;
	protected $_request_method = 'get';

	protected function __construct()
	{
		if($_SERVER['REQUEST_METHOD'] === 'POST') {
			$this->_request_method = 'post';
		}
		$this->_input = new PeepSo3_Input();

		if (defined('WPML_PLUGIN_PATH')) {
			global $wpml_query_filter;
			remove_filter( 'posts_join', array( $wpml_query_filter, 'posts_join_filter' ), 10, 2 );
			remove_filter( 'posts_where', array( $wpml_query_filter, 'posts_where_filter' ), 10, 2 );
		}

		if (strpos($_SERVER['REQUEST_URI'], '/peepsoajax/') !== FALSE && !wp_verify_nonce($_SERVER['HTTP_X_PEEPSO_NONCE'], 'peepso-nonce')) {
			die(__('Invalid security challenge code.', 'peepso-core'));
		}
	}

	/*
	 * return singleton instance
	 */
	public static function get_instance()
	{
		$class = get_called_class();
		if (!isset(self::$_instances[$class])) {
			self::$_instances[$class] = new $class();
		}
		return (self::$_instances[$class]);
	}
}

// EOF