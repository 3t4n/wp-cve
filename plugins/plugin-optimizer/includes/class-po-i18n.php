<?php

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @package    PluginOptimizer
 * @subpackage PluginOptimizer/includes
 * @author     Simple Online Systems <admin@simpleonlinesystems.com>
 */
class SOSPO_i18n {
    
    public function __construct() {
        
		add_action( 'plugins_loaded', [ $this, 'load_plugin_textdomain' ] );

	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'plugin-optimizer',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
