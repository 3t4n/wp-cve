<?php  if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Handle import and export of Help Dialog configuration
 *
 * @copyright   Copyright (C) 2019, Echo Plugins
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class EPHD_Export_Import {
	
	private $message = array(); // error/warning/success messages

	// Exclude config fields from import/export
	const IGNORED_FIELDS = array(
		'logo_image_url',
		'contact_submission_email',
		'location_page_filtering',
		'location_pages_list',
		'location_posts_list',
		'location_cpts_list',
		'faqs_sequence'
	);

	/**
	 * Run export
	 * return text message about error or stop script and show export file
	 * @return String|array
	 */
	public function download_export_file() {

		if ( ! current_user_can( 'manage_options' ) ) {
			EPHD_Utilities::ajax_show_error_die(__( 'You do not have permission to edit Help Dialog.', 'help-dialog' ));
		}

		// export data and report error if an issue found
		$exported_data = $this->export_hd_config();
		if ( empty( $exported_data ) ) {
			return $this->message;
		}

		ignore_user_abort( true );
		
		if ( ! $this->is_function_disabled( 'set_time_limit' ) && ! ini_get( 'safe_mode' ) ) {
			set_time_limit( 0 );
		}

		nocache_headers();
		header( 'Content-Type: application/json; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=help_dialog_config_export_' . date('Y_m_d_H_i_s') . '.json' );
		header( "Expires: 0" );

		echo wp_json_encode($exported_data);

		return '';
	}

	/**
	 * Export HD configuration.
	 *
	 * @return null
	 */
	private function export_hd_config() {
		global $wp_widget_factory;

		$export_data = array();

		$export_data['plugin_version'] = Echo_Help_Dialog::$version;

		// retrieve Help Dialog all configs; no error is returned
		$all_hd_configs = $this->get_all_hd_configs();

		// check and filter config
		foreach ( $all_hd_configs as $config_name => $config ) {
			if ( empty( $config ) || ! is_array( $config ) ) {
				$this->message['error'] = 'E40';
				return null;
			}
			$export_data[$config_name] = $this->filter_config( $config );
		}

		// export WordPress widgets if it is available
		if ( empty( $wp_widget_factory ) || empty( $wp_widget_factory->widgets ) ) {
			return $export_data;
		}

		return $export_data;
	}

	/**
	 * Import HD configuration from a file.
	 *
	 * @return array|null
	 */
	public function import_hd_config() {

		if ( ! current_user_can( 'manage_options' ) ) {
			EPHD_Utilities::ajax_show_error_die( __( 'You do not have permission to edit Help Dialog.', 'help-dialog' ) );
		}

		$import_file_name = $_FILES['import_file']['tmp_name'];
		if ( empty( $import_file_name ) ) {
			$this->message['error'] = __( 'Import file format is not correct.', 'help-dialog' ) . ' (0)';
			return $this->message;
		}

		// check the file
		if ( empty( is_uploaded_file( $import_file_name ) ) ) {
			$this->message['error'] = __( 'Import file format is not correct.', 'help-dialog' ) . ' (3)';
			return $this->message;
		}

		// retrieve content of the imported file
		$import_data_file = file_get_contents( $import_file_name );
		if ( empty( $import_data_file ) ) {
			$this->message['error'] = __( 'Import file format is not correct.', 'help-dialog' ) . ' (1)';
			return $this->message;
		}

		// validate imported data
		$imported_configs = json_decode( $import_data_file, true );
		if ( empty( $imported_configs ) || ! is_array( $imported_configs ) ) {
			$this->message['error'] = __( 'Import file format is not correct.', 'help-dialog' ) . ' (2)';
			return $this->message;
		}

		// if imported version is too new, then tell user to first upgrade the plugin
		if ( self::is_imported_version_too_new( $imported_configs ) ) {
			$this->message['error'] = __( 'The importing configuration version is too new for this plugin. Please update the plugin first.', 'help-dialog' ) . ' (4)';
			return $this->message;
		}

		// check if we need to upgrade data
		$this->upgrade_imported_data( $imported_configs );

		// remove unnecessary fields
		unset( $imported_configs['plugin_version'] );

		// filter imported configs
		foreach ( $imported_configs as $key => $config ) {
			$imported_configs[$key] = self::filter_config( $config );
		}

		// retrieve plugin instance
		/** @var $plugin_instance Echo_Help_Dialog */

		$orig_configs = $this->get_all_hd_configs();
		foreach ( $orig_configs as $orig_config ) {
			if ( is_wp_error( $orig_config ) ) {
				$this->message['error'] =  'E31 ' . $orig_config->get_error_message();  // do not translate
				return $this->message;
			}
		}

		// merge origin and imported configs
		$configs = array_replace_recursive( $orig_configs, $imported_configs );

		// update configuration
		$this->update_all_hd_configs( $configs );

		return $this->message;
	}

	/**
	 * Get all Help Dialog configs
	 *
	 * @return array
	 */
	private function get_all_hd_configs() {

		$configs = array();

		$configs[EPHD_Config_DB::EPHD_GLOBAL_CONFIG_NAME] = ephd_get_instance()->global_config_obj->get_config();
		$configs[EPHD_Widgets_DB::EPHD_WIDGETS_CONFIG_NAME] = ephd_get_instance()->widgets_config_obj->get_config();

		return $configs;
	}

	/**
	 * Update all Help Dialog configs
	 *
	 * @param $new_configs
	 */
	private function update_all_hd_configs( $new_configs ) {

		$current_configs = self::get_all_hd_configs();

		// update Global config
		$global_config = isset( $new_configs[ EPHD_Config_DB::EPHD_GLOBAL_CONFIG_NAME] ) ? $new_configs[ EPHD_Config_DB::EPHD_GLOBAL_CONFIG_NAME] : $current_configs[ EPHD_Config_DB::EPHD_GLOBAL_CONFIG_NAME];
		$updated_config = ephd_get_instance()->global_config_obj->update_config( $global_config );
		if ( is_wp_error( $updated_config ) ) {
			$this->message['error'] =  'E31 ' . $updated_config->get_error_message();  // do not translate
			return;
		}

		// update Widgets config
		$widgets_config = isset( $new_configs[ EPHD_Widgets_DB::EPHD_WIDGETS_CONFIG_NAME] ) ? $new_configs[ EPHD_Widgets_DB::EPHD_WIDGETS_CONFIG_NAME] : $current_configs[ EPHD_Widgets_DB::EPHD_WIDGETS_CONFIG_NAME];
		$updated_config = ephd_get_instance()->widgets_config_obj->update_config( $widgets_config );
		if ( is_wp_error( $updated_config ) ) {
			$this->message['error'] =  'E34 ' . $updated_config->get_error_message();  // do not translate
			return;
		}

		$this->message['success'] =  __( 'Import finished successfully', 'help-dialog' );
	}

	/**
	 * Filter config
	 *
	 * @param $config
	 * @return array
	 */
	private function filter_config( $config ) {

		// check and delete ignored fields
		foreach( $config as $key_1 => $config_1 ) {
			// is two-dimensional config array ( widgets, designs, contact_forms )
			if ( is_numeric( $key_1 ) && is_array( $config_1 ) ) {
				foreach( $config_1 as $key_2 => $config_2 ) {
					if ( ! empty( in_array( $key_2, self::IGNORED_FIELDS ) ) ) {
						unset( $config[$key_1][$key_2] );
					}
				}
			// is simple config array ( global )
			} else {
				if ( ! empty( in_array( $key_1, self::IGNORED_FIELDS ) ) ) {
					unset( $config[$key_1] );
				}
			}
		}

		return $config;
	}

	/**
	 * Run upgrade for imported configurations data
	 *
	 * @param $imported_configs
	 */
	private function upgrade_imported_data( &$imported_configs ) {

		$import_plugin_version = empty( $imported_configs['plugin_version'] ) ? '' : $imported_configs['plugin_version'];
		$import_plugin_version = empty( $import_plugin_version ) ? '1.0.0' : $import_plugin_version;

		// upgrade imported config if it has version less than current plugin version
		if ( version_compare( $import_plugin_version, Echo_Help_Dialog::$version, '<' ) ) {

			$imported_global_config = $imported_configs['ephd_global_config'];
			$imported_widgets_config = $imported_configs['ephd_widgets_config'];

			// deprecated since version 2.0.0, keep it to import old configs
			$imported_designs_config = isset( $imported_configs['ephd_designs_config'] ) ? $imported_configs['ephd_designs_config'] : [];
			$imported_contact_forms_config = isset( $imported_configs['ephd_contact_forms_config'] ) ? $imported_configs['ephd_contact_forms_config'] : [];

			// run upgrades
			EPHD_Upgrades::run_upgrade( $imported_global_config, $imported_widgets_config, $imported_designs_config, $imported_contact_forms_config, $import_plugin_version );

			// apply changes for imported configs
			$imported_configs['ephd_global_config'] = $imported_global_config;
			$imported_configs['ephd_widgets_config'] = $imported_widgets_config;
		}
	}

	/**
	 * Check if the imported configuration is higher than current plugin version
	 *
	 * @param $imported_configs
	 * @return bool
	 */
	private static function is_imported_version_too_new( $imported_configs ) {

		$import_plugin_version = empty( $imported_configs['plugin_version'] ) ? '' : $imported_configs['plugin_version'];

		if ( empty( $import_plugin_version ) ) {
			return false;
		}

		if ( version_compare( $import_plugin_version, Echo_Help_Dialog::$version, '>' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Checks whether function is disabled.
	 * @param $function
	 * @return bool
	 */
	private function is_function_disabled( $function ) {
		$disabled = explode( ',',  ini_get( 'disable_functions' ) );
		return in_array( $function, $disabled );
	}
}