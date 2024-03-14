<?php
/**
 * SURLs Import - Hook.
 *
 * @package Pages
 */

namespace LassoLite\Pages\Import_Urls;

use LassoLite\Classes\Processes\Import_All;

/**
 * Hook.
 */
class Hook {
	/**
	 * Register hooks
	 */
	public function register_hooks() {
		add_action( 'lasso_import_all_process', array( $this, 'stop_import_all_process_from_lite' ) );
	}

	/**
	 * Stop Import all process from Lite if Pro is running import all
	 *
	 * @return $this
	 */
	public function stop_import_all_process_from_lite() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$import_all_process = new Import_All();
		$import_all_process->remove_process();
		update_option( Import_All::OPTION, '0' );
		delete_option( Import_All::FILTER_PLUGIN );

		return $this;
	}
}
