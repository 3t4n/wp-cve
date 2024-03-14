<?php

class Library_Viewer_Admin {

	public $deprecated_hooks = array(
		'lv_file_was_viewed'		  => 'LV__file_was_viewed',
		'lv_mime_types' 			  => 'LV__mime_types',
		'lv_folder_fake_path_symbols' => 'LV__array_replace_to__in_foldernames',
		'lv_file_fake_path_symbols'	  => 'LV__array_replace_to__in_filenames',
		'lv_folder_real_path_symbols' => 'LV__array_replace_from__in_foldernames',
		'lv_file_real_path_symbols'	  => 'LV__array_replace_from__in_filenames',
		'lv_folder_html'			  => 'LV__folder_html',
		'lv_file_html'				  => 'LV__file_html',
	);

	/**
	 * Library_Viewer_Admin constructor.
	 *
	 * This function calls the hooks.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {
		add_action('admin_notices', array($this, 'lv_pro_need_update') );
		add_action('admin_notices', array($this, 'deprecated_hooks_notices') );
	}

	/**
	 * Add admin notice that say need to update the Library Viewer Pro in order to work
	 *
	 * @since 2.0.0
	 */
	public function lv_pro_need_update()
	{
		if ( defined('LIBRARY_VIEWER_PRO_PLUGIN_BASENAME') && defined('LIBRARY_VIEWER_PRO_VERSION_REQUIRED_FOR_LV') ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			$plugin_data = get_plugin_data( WP_PLUGIN_DIR  . '/' . LIBRARY_VIEWER_PRO_PLUGIN_BASENAME );
			$lv_pro_version = $plugin_data['Version'];

			if ( -1 === version_compare($lv_pro_version, LIBRARY_VIEWER_PRO_VERSION_REQUIRED_FOR_LV) ) {
				echo '<div class="notice notice-error"><p>Library Viewer Pro cannot work. You need to update Library Viewer Pro in order to work in addition to Library Viewer.<br>
					 If you can\'t update Library Viewer Pro, contact me via <a href="info@pexlechris.dev">email</a></p></div>';
			}
		}

		if ( defined('LIBRARY_VIEWER_FILE_MANAGER_FILE_ABSPATH') && defined('LIBRARY_VIEWER_FILE_MANAGER_VERSION_REQUIRED_FOR_LV') ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			$plugin_data = get_plugin_data( LIBRARY_VIEWER_FILE_MANAGER_FILE_ABSPATH );
			$lv_fm_version = $plugin_data['Version'];

			if ( -1 === version_compare($lv_fm_version, LIBRARY_VIEWER_FILE_MANAGER_VERSION_REQUIRED_FOR_LV) ) {
				echo '<div class="notice notice-error"><p>Library Viewer File Manager cannot work. You need to update Library Viewer File Manager in order to work in addition to Library Viewer.<br>
					 If you can\'t update Library Viewer File Manager, contact me via <a href="info@pexlechris.dev">email</a></p></div>';
			}
		}
	}

	/**
	 * This method is a callback for admin_notices action,
	 * that display an admin notice if a deprecated action/filter is used
	 */
	public function deprecated_hooks_notices()
	{
		$LIBRARY_VIEWER_DOCUMENTATION_URL = LIBRARY_VIEWER_DOCUMENTATION_URL;
		foreach ($this->deprecated_hooks as $new_hook => $deprecated_hook) {
			if( has_filter($deprecated_hook) || has_action($deprecated_hook) ){
				echo "<div class='notice notice-error'><p>The <b>$deprecated_hook</b> hook is deprecated from 2.0.0 . Please replace it with <b>$new_hook</b>. View <a target='_blank' href='$LIBRARY_VIEWER_DOCUMENTATION_URL'>Documentation</a> for more details.</p></div>";
			}
		}
	}


}
