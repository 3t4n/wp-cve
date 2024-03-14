<?php
/**
 * Plugin Name: Library Viewer
 * Description: This is a File & Folder Viewer of FTP folder: yoursite.com/library. So using the shortcode [library-viewer], you can print the containing folders & files of your library on front-end 
 * Version: 2.0.6.3
 * Stable tag: 2.0.6.3
 * Plugin URI: https://www.pexlechris.dev/library-viewer
 * Author: Pexle Chris
 * Author URI: https://www.pexlechris.dev
 * Contributors: pexlechris
 * Domain Path: /languages
 * Tested up to: 6.3.1
 * Requires PHP: 5.6
 * License: GPLv2
 
 
Copyright 2022 Pexle Chris(email: info@pexlechris.dev)

Library Viewer is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

Library Viewer is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA02110-1301USA
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * The version of the plugin.
 *
 * @since 2.0.0
 * @var string
 */
define('LIBRARY_VIEWER_VERSION', '2.0.6.3');

/**
 * The http or https link of plugin with trailing slash.
 *
 * @since 2.0.0
 * @var string
 */
define('LIBRARY_VIEWER_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ));

/**
 * The absolute path of plugin file constructor.
 *
 * @since 2.0.0
 * @var string
 */
define('LIBRARY_VIEWER_FILE_ABSPATH', __FILE__ );

/**
 * The absolute path of plugin folder with trailing slash.
 *
 * @since 2.0.0
 * @var string
 */
define('LIBRARY_VIEWER_DIR_ABSPATH', __DIR__ . '/');

/**
 * The URL to buy Library Viewer Pro.
 *
 * @since 2.0.0
 * @var string
 */
define('LIBRARY_VIEWER_PRO_BUY_URL', 'https://www.pexlechris.dev/library-viewer/pro-user');

/**
 * The URL to buy Library Viewer File Manager Addon.
 *
 * @since 2.0.0
 * @var string
 */
define('LIBRARY_VIEWER_FILE_MANAGER_BUY_URL', 'https://www.pexlechris.dev/library-viewer/file-manager');

/**
 * The URL of Library Viewer's Documentation.
 *
 * @since 2.0.0
 * @var string
 */
define('LIBRARY_VIEWER_DOCUMENTATION_URL', 'https://www.pexlechris.dev/library-viewer/docs?library-viewer=yes');

/**
 * The version of Library Viewer Pro, that is required
 * in order to use the Library Viewer Pro shortcode parameters.
 *
 * @since 2.0.0
 * @var string
 */
define('LIBRARY_VIEWER_PRO_VERSION_REQUIRED_FOR_LV', '2.0.4');


/**
 * The version of Library Viewer File Manager, that is required
 * in order to use the Library Viewer File Manager shortcode parameters.
 *
 * @since 2.0.3
 * @var string
 */
define('LIBRARY_VIEWER_FILE_MANAGER_VERSION_REQUIRED_FOR_LV', '1.1.0');



/**
 * Class Library_Viewer_Init.
 *
 * Initialize Library_Viewer_Plugin_Page object and adds the frontend hooks.
 */
class Library_Viewer_Init {
	/**
	 * Library_Viewer_Init constructor.
	 *
	 * Register the shortcode and all actions of plugin.
	 *
	 * @since 2.0.0
	 */
	public function __construct()
	{
		add_action( 'init', array($this, 'load_plugin_textdomain'));

		if ( 'plugins.php' == $GLOBALS['pagenow'] ) {// in plugin's page

			require_once LIBRARY_VIEWER_DIR_ABSPATH . 'admin/class-library-viewer-plugin-page.php';
			new Library_Viewer_Plugin_Page();

			require_once LIBRARY_VIEWER_DIR_ABSPATH . 'admin/class-library-viewer-admin.php';
			new Library_Viewer_Admin();

		} elseif( !is_admin() ) { // in frontend

			add_action ('wp_loaded', array($this, 'custom_login_redirect_action'));

			add_action ('wp_loaded', array($this, 'file_viewer_action'));

			add_filter('lv_shortcode_class_names', array($this, 'filter_lv_shortcode_class_names'), 5);
			add_filter('lv_file_viewer_class_names', array($this, 'filter_lv_file_viewer_class_names'), 5);

			add_shortcode('library-viewer', array($this, 'register_library_viewer_shortcode'));

		} else { // is admin & not plugins page
			require_once LIBRARY_VIEWER_DIR_ABSPATH . 'admin/class-library-viewer-admin.php';
			new Library_Viewer_Admin();
		}
	}

	public function filter_lv_shortcode_class_names(){
		return array('Library_Viewer_Shortcode');
	}

	public function filter_lv_file_viewer_class_names(){
		return array('Library_Viewer_File');
	}


	/**
	 * Load plugin's textdomain.
	 *
	 * This hook loads the plugins' po & mo files.
	 * For backend, user's language is being loaded.
	 * For frontend, site's language is being loaded.
	 *
	 * @since 2.0.0
	 * @since 2.0.4 uses load_plugin_textdomain function
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'library-viewer',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}

	/**
	 * Custom login redirect.
	 *
	 * If the get parameter `redirect_to` exists in current URL, the user is being redirected.
	 *
	 * @since 1.0.0
	 */
	public function custom_login_redirect_action() {
		if( isset($_GET['action']) ) return;
		if( is_user_logged_in() && isset($_GET['redirect_to']) && !empty($_GET['redirect_to']) ){
			$redirect_url = $_GET['redirect_to'];
			if ( wp_safe_redirect( $redirect_url ) ) {
				exit;
			}
		}
	}

	/**
	 * Register library-viewer shortcode.
	 *
	 * @since 2.0.0
	 * @uses Library_Viewer_Shortcode
	 *
	 * @param array $attributes {
	 * 		Optional. The shortcode attributes.
	 *
	 * 		@type string have_file_access Who have access in the files.
	 * 									  Default 'all'. Accepts 'all', 'logged_in' &
	 * 									  capabilities separated with commas.
	 * 		@type string my_doc_viewer The file viewer.
	 * 								   If have_file_access=='all' default is 'default'.
	 * 								   If have_file_access!='all' default is 'library-viewer'.
	 * 								   Accepts 'default', 'library-viewer' or custom viewer.
	 * }
	 *
	 * @return string $shortcode_html_contents The shortcode html contents.
	 */
	public function register_library_viewer_shortcode( $attributes )
	{

		/**
		 * level-1 class: Library_Viewer_Shortcode
		 * 		registered in `frontend/class-library-viewer-shortcode.php` file
		 * level-2 classes: These classes extend the level-1 class or each other
		 * 		registered in `register_library_viewer_shortcode_child_class` action
		 */

		/**
		 * Registers the level-1 and level-2 classes.
		 */
		$this->register_library_viewer_shortcode_classes();

		$LV_shortcode_class_name = $this->get_shortcode_class_name();

		if ( class_exists($LV_shortcode_class_name) ) {

			$LV_shortcode_object = new $LV_shortcode_class_name($attributes);

			if ( method_exists($LV_shortcode_object, 'shortcode_html_contents') ) {
				$this->init_library_viewer_file_identifier();
				$shortcode_html_contents  = $LV_shortcode_object->shortcode_html_contents();
				return $shortcode_html_contents;
			} else {
				$shortcode_html_contents = library_viewer_error('shortcode_non_registered_method', $LV_shortcode_class_name);
				return $shortcode_html_contents;
			}

		} else {
			$shortcode_html_contents = library_viewer_error('shortcode_non_registered_class');
			return $shortcode_html_contents;
		}
	}

	/**
	 * File viewer action.
	 *
	 * Action that triggered, when we need to readfile.
	 * If REQUEST_URI contains the file_identifier, calls the object and prints errors if need it.
	 *
	 * @uses Library_Viewer_Init::init_file_viewer_object()
	 *
	 * @since 2.0.0
	 */
	public function file_viewer_action()
	{
		$this->init_library_viewer_file_identifier();
		$file_identifier = $GLOBALS['library_viewer_file_identifier'];

		if ( false !== strpos(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), "/$file_identifier/") ) {
			$file_status = $this->init_file_viewer_object();
			if ( empty($file_status['error_page_title']) ) {
				wp_die($file_status['error_message']);
			} else {
				wp_die($file_status['error_message'], $file_status['error_page_title']);
			}
		}
	}

	/**
	 * Init File Viewer object.
	 *
	 * @since 2.0.0
	 * @uses Library_Viewer_Shortcode, Library_Viewer_File
	 */
	public function init_file_viewer_object()
	{

		/**
		 * level-1 class: Library_Viewer_Shortcode
		 * 		registered in `frontend/class-library-viewer-shortcode.php` file
		 * level-2 classes: These classes extend the level-1 class or each other
		 * 		registered in `register_library_viewer_shortcode_child_class` action
		 * alias class: Library_Viewer_File_Alias
		 * 		alias of $lv_shortcode_class_names last item class
		 * level-3 class: Library_Viewer_File
		 * 		registered in `frontend/class-library-viewer-file.php` file
		 * level-4 classes: These classes extend the level-3 class or each other
		 * 		registered in `register_library_viewer_file_child_class` file
		 *
		 * level-4 classes extends the level-3 class extends the alias class extend the level-2 classes extends the level-1 class.
		 */

		/**
		 * Registers the level-1, level-2, alias class, level-3 and level-4 classes.
		 */
		$this->register_library_viewer_file_classes();

		$file_status = array();

		$LV_file_viewer_class_name = $this->get_file_viewer_class_name();

		if ( class_exists($LV_file_viewer_class_name) ) {

			$LV_file_object = new $LV_file_viewer_class_name();

			if (method_exists($LV_file_object, 'call_file_viewer')) {
				$file_status = $LV_file_object->call_file_viewer();
			} else {
				$file_status['error_message'] = library_viewer_error('non_registered_method_in_class', 'call_file_viewer', $LV_file_viewer_class_name);
			}

		}else {
			$file_status['error_message'] = library_viewer_error('file_non_registered_class');
		}

		return $file_status;
	}

	public function register_library_viewer_shortcode_classes()
	{
		require_once LIBRARY_VIEWER_DIR_ABSPATH . 'frontend/class-library-viewer-shortcode.php';// level-1 class

		/**
		 * Register library viewer shortcode child class filter.
		 *
		 * With this filter, we can register level-2 classes that
		 * extend the level-1 class or each other.
		 *
		 * For example,
		 * in action `register_library_viewer_shortcode_child_class` with priority 5,
		 * we can register the class with name Level_2_Class_1 that extends the Library_Viewer_Shortcode class.
		 * and also in action `register_library_viewer_shortcode_child_class` with priority 6,
		 * we can register the class with name Level_2_Class_2 that extends the Level_2_Class_1 class.
		 * So,
		 * The Level_2_Class_2 class extends the Level_2_Class_1 class that extends the Library_Viewer_Shortcode class.
		 * Now,
		 * we need to tell the code what class need to initialize,
		 * this we can do it with below filter lv_shortcode_class_name.
		 *
		 * @since 2.0.0
		 *
		 * @ignore
		 */
		do_action('register_library_viewer_shortcode_child_class');
	}

	public function register_library_viewer_file_classes()
	{
		/**
		 * Registers the level-1 and level-2 classes.
		 */
		$this->register_library_viewer_shortcode_classes();

		$LV_shortcode_class_name = $this->get_shortcode_class_name();

		/**
		 * Make an alias with name `Library_Viewer_File_Alias` from the
		 * shortcode class name that is used,
		 * so
		 * level-3 class to extend the level-2 class
		 * (if there is not level-2 classes, level-3 class extends level-1 class)
		 */
		class_alias($LV_shortcode_class_name, 'Library_Viewer_File_Alias');  // alias

		require_once LIBRARY_VIEWER_DIR_ABSPATH . 'frontend/class-library-viewer-file.php';// level-3 class

		/**
		 * Register library viewer file child class filter.
		 *
		 * With this filter, we can register level-4 classes that
		 * extend the level-3 class or each other.
		 *
		 * For example,
		 * in action `register_library_viewer_file_child_class` with priority 5,
		 * we can register the class with name Level_4_Class_1 that extends the Library_Viewer_File class.
		 * and also in action `register_library_viewer_shortcode_child_class` with priority 6,
		 * we can register the class with name Level_4_Class_2 that extends the Level_4_Class_1 class.
		 * So,
		 * The Level_4_Class_2 class extends the Level_4_Class_1 class that extends the Library_Viewer_File class.
		 * Now,
		 * we need to tell the code what class need to initialize,
		 * this we can do it with below filter register_library_viewer_file_child_class.
		 *
		 * @since 2.0.0
		 *
		 * @ignore
		 */
		do_action('register_library_viewer_file_child_class');// level-4 classes
	}

	public function get_shortcode_class_name()
	{
		/**
		 * Library Viewer shortcode object name.
		 *
		 * Depends on the $attributes the wp filters determine which object will called.
		 * Library Viewer Pro & Addons use this filter.
		 *
		 * @since 2.0.0
		 * @since 2.0.3 array structure was changed
		 *
		 * @ignore
		 *
		 * @param array $LV_shortcode_class_names The shortcode class names (parent & children).
		 * @param array $attributes see Library_Viewer_Init::register_library_viewer_shortcode()
		 */
		$LV_shortcode_class_names = apply_filters('lv_shortcode_class_names', array());

		return end($LV_shortcode_class_names);
	}

	public function get_file_viewer_class_name()
	{
		/**
		 * Library Viewer's file viewer class name.
		 *
		 * Depends on plugin's settings determine which class will used for object initialization.
		 * Library Viewer Pro & Addons use this filter.
		 *
		 * @since 2.0.0
		 * @since 2.0.2 Array structure was changed
		 *
		 * @ignore
		 *
		 * @param string $LV_file_viewer_class_names The file viewer class name.
		 */
		$LV_file_viewer_class_names = apply_filters('lv_file_viewer_class_names', array());

		return end($LV_file_viewer_class_names);
	}

	public function init_library_viewer_file_identifier()
	{
		$file_identifier = 'LV';

		/**
		 * Library Viewer file identifier filter.
		 *
		 * If file identifier is found in REQUEST_URI this plugins readfile and exits the code.
		 * So with this filter, we can change this string to this of our choice.
		 * BE CAREFUL!! If this string will be found in a URL of your website, the php execution will stops here.
		 *
		 * @param string $file_identifier Library Viewer file identifier keyword.
		 * @since 2.0.0
		 */
		$file_identifier = apply_filters('lv_file_identifier', $file_identifier);

		$GLOBALS['library_viewer_file_identifier'] = $file_identifier;
	}

}

new Library_Viewer_Init();

/**
 * Library viewer error function.
 *
 * This function return the appropriate error message according to the 1st parameter.
 *
 * @since 2.0.0
 *
 * @param string $case According to case determines which error message will return.
 * @param string $s The %1$s value.
 * @param string $s2 The %2$s value.
 * @return string $string The returned string.
 */
function library_viewer_error($case, $s = '', $s2 = '') {
	switch ($case) {
		case 'path_folder_created':
			$string = sprintf(
			// translators: %s is folder name
			__("The folder <strong>%s</strong> has been created. This page will be refreshed. Please wait.", 'library-viewer'),
				$s
			);
			break;
		case 'file_not_allowed':
			$string = __("You haven't access to files from this folder.", 'library-viewer');
			break;
		case 'file_not_exists':
			$string = __("File doesn't exists.", 'library-viewer');
			break;
		case 'no_appropriate_capabilities':
			$string = __("Sorry. You haven't the appropriate capabilities to view the files of our Library Viewer.", 'library-viewer');
			break;
		case 'redirect_to_login':
			$string = sprintf(
				// translators: %s is seconds
				__('You must login to view this file.<br>You will be redirected to the login page in <strong>%s seconds.</strong>', 'library-viewer'),
				$s
			);
			break;

		case 'php_forbidden':
			$string = __('Download is forbidden for php files', 'library-viewer');
			break;

		case 'shortcode_non_registered_class':
			$string = __('LV_shortcode_class_name filter has returned a non registered class.', 'library-viewer');
			break;
		case 'shortcode_non_registered_method':
			$string = sprintf(
				// translators: %s is the name of class
				__('shortcode_html_contents method is not registered in %s class.', 'library-viewer'),
				$s
			);
			break;
		case 'file_non_registered_class':
			$string = __('LV_file_viewer_class_name filter has returned a non registered class.', 'library-viewer');
			break;
		case 'non_registered_method_in_class':
			$string = sprintf(
				/* translators: %2$s is the name of class
				%1$s is the name of method */
				__('%1$s method is not registered in %2$s class.', 'library-viewer'),
				$s,
				$s2
			);
			break;
		case 'shortcode_more_than_1_times':
			$s2 = '<a href="' . LIBRARY_VIEWER_PRO_BUY_URL . '" target="_blank">Library Viewer Pro</a>';
			$string = sprintf(
				/* translators: %1$s is [library-viewer]
				%2$s is Library Viewer Pro Buy URL */
				__('You cannot use shortcode %1$s more than 1 times in the same page without the use of parameter <strong>url_suffix</strong>. You can use this parameter with the <strong>latest version</strong> of %2$s.<br>View documentation of <strong>url_suffix</strong> parameter for more.', 'library-viewer'),
				$s, $s2
			);
			break;
		case 'not_acceptable_parameter':
			$lv_pro_url = '<a target="_blank" href="' . LIBRARY_VIEWER_PRO_BUY_URL . '">Library Viewer Pro</a>';
			$lv_fm_url = '<a target="_blank" href="' . LIBRARY_VIEWER_FILE_MANAGER_BUY_URL . '">Library Viewer File Manager Addon</a>';
			$lv_pro_parameters = array('breadcrumb', 'shown_folders', 'hidden_folders', 'shown_files', 'hidden_files', 'waiting_seconds', 'url_suffix');
			$lv_fm_parameters = array('delete_folder', 'delete_file', 'rename_folder', 'rename_file', 'create_folder', 'upload_file', 'unzip_file', 'download_folder', 'download_file');

			if ( 'path' === $s ) {
				$string = sprintf(
					// translators: %s Library Viewer Pro URL
					__('You cannot use <b>path</b> parameter in shortcode.<br>If you want to display the containing files and the containing folders of <strong>a different folder</strong> (instead of library) of your (FTP) server to your users in the front-end, <br> consider buying %s', 'library-viewer'),
					$lv_pro_url
				);
			} elseif ( in_array($s, $lv_pro_parameters) ) {
				$string = sprintf(
					/* translators: %1$s is the parameter
					%2$s is the Library Viewer Pro URL */
					__('You cannot use <b>%1$s</b> parameter in shortcode.<br>If you want to use <b>%1$s</b> parameter,<br> consider buying %2$s or if you already have it installed, update it to the latest version.', 'library-viewer'),
					$s,
					$lv_pro_url
				);
			} elseif ( in_array($s, $lv_fm_parameters) ) {
				$string = sprintf(
				/* translators: %1$s is the parameter
				%2$s is the Library Viewer Pro URL */
					__('You cannot use <b>%1$s</b> parameter in shortcode.<br>If you want to use <b>%1$s</b> parameter,<br> consider buying %2$s or if you already have it installed, update it to the latest version.', 'library-viewer'),
					$s,
					$lv_fm_url
				);
			} else {
				$string = sprintf(
					// translators: %s shortcode parameter
					__('Probably, <b>%s</b> parameter is not supported yet.', 'library-viewer'),
					$s
				);
			}
			break;
		case 'folder_not_exists':
			$string = __("Error 404: This folder doesn't exists.", 'library-viewer');
			break;
		case 'no_access':
			$string = __("You haven't access to this folder.", 'library-viewer');
			break;
		case 'empty_folder':
			$string = __("This folder is empty.", 'library-viewer');
			break;
		case 'go_back':
			$string = __('Go Back', 'library-viewer');
			break;
		case 'redirection_page_title':
			$string = __('Redirection to login page', 'library-viewer');
			break;

		default:
			$string = '';
	}

	//sanitization
	$string = str_replace(
		array('<script>', '</script>'),
		array('', ''),
		$string
	);

	/**
	 * Error message filter.
	 *
	 * Filter the plugin's error messages according to $case.
	 *
	 * @since 2.0.3
	 *
	 * @param string $string The error message HTML.
	 * @param string $case The case. According to this,
	 *                     different messages will be displayed.
	 */
	$string = apply_filters('lv_error_message', $string, $case);
	return $string;
}


