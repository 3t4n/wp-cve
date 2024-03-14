<?php
/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://codexin.com
 * @since      1.0.0
 *
 * @package    ImageMetadataSettings
 * @subpackage ImageMetadataSettings/admin
 */

namespace Codexin\ImageMetadataSettings;

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    ImageMetadataSettings
 * @subpackage ImageMetadataSettings/admin
 * @author     Your Name <email@codexin.com>
 */
class Admin {

	/**
	 * The plugin's instance.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    Plugin $plugin This plugin's instance.
	 */
	private $plugin;
	/**
	 * The plugin's script.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    Plugin $suffix This plugin's script.
	 */
	private $suffix;
	/**
	 * WordPress version.
	 *
	 * @since  1.0.4
	 * @access private
	 * @var    Plugin $wpress_version represents WordPress version
	 */
	private $wpress_version;
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 *
	 * @param Plugin $plugin This plugin's instance.
	 */
	public function __construct( Plugin $plugin ) {
		$this->suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		$this->plugin = $plugin;
		$this->wpress_version = ( version_compare(get_bloginfo('version'),'6.0', '>=') ) ? '6 or higher' : 'below 6';
	}

	/**
	 * Register the stylesheets for the Dashboard. dependency
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		$my_current_screen = get_current_screen();
		if ( 'upload' !== $my_current_screen->base ) {
			return;
		}

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in PluginBoilerplate_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The PluginBoilerplate_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		\wp_enqueue_style(
			CDXN_MLH_PREFIX . '_' . $this->plugin->get_plugin_name(),
			\plugin_dir_url( dirname( __FILE__ ) ) . 'assets/styles/admin' . $this->suffix . '.css',
			array(),
			$this->plugin->get_version(),
			'all'
		);

	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		$my_current_screen = get_current_screen();
		if ( 'upload' !== $my_current_screen->base ) {
			return;
		}

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		\wp_enqueue_script(
			CDXN_MLH_PREFIX . '_' . $this->plugin->get_plugin_name(),
			\plugin_dir_url( dirname( __FILE__ ) ) . 'assets/scripts/admin' . $this->suffix . '.js',
			array( 'jquery', 'inline-edit-post' ),
			$this->plugin->get_version(),
			true
		);

		wp_localize_script(
			CDXN_MLH_PREFIX . '_' . $this->plugin->get_plugin_name(),
			'cdxn_mlh_script',
			array(
				'admin_ajax'     => admin_url( 'admin-ajax.php' ),
				'ajx_nonce'      => wp_create_nonce( 'ajax-nonce' ),
				'text_no_change' => __( '--- No Change ---', 'media-library-helper' ),
				'plugin_prefix'  => CDXN_MLH_PREFIX,
				'wordpress_version' => $this->wpress_version
			)
		);

	}
}