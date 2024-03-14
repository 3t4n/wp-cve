<?php

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @package    PluginOptimizer
 * @subpackage PluginOptimizer/includes
 * @author     Simple Online Systems <admin@simpleonlinesystems.com>
 */
class SOSPO_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 */
	public static function deactivate() {
    if( file_exists(WPMU_PLUGIN_DIR . '/class-po-mu.php') ){
      unlink( WPMU_PLUGIN_DIR . '/class-po-mu.php' );
    }
	}

}
