<?php
/**
 * The class defines all functionality for the dashboard of the plugin.
 *
 * @package BBPS
 * @since    1.0
 */

if ( ! class_exists( 'BBPS_Admin' ) ) {

	class BBPS_Admin {

		/**
		 * Stores plugin options.
		 */
		public $opt;

		/**
		 * Stores network activation status.
		 */
		private $networkactive;

		/**
		 * Core singleton class
		 * @var self
		 */
		private static $_instance;

		/**
		 * Initializes this class.
		 *
		 */
		public function __construct() {
			$bbps = BBPress_Shortcodes::getInstance();
			$this->opt = ( null !== $bbps ) ? $bbps->opt : get_option( 'bbpress_shortcodes' );
			$this->networkactive = ( is_multisite() && array_key_exists( plugin_basename( BBPS_PLUGIN_FILE ), (array) get_site_option( 'active_sitewide_plugins' ) ) );
		}

		/**
		 * Gets the instance of this class.
		 *
		 * @return self
		 */
		public static function getInstance() {
			if ( ! ( self::$_instance instanceof self ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * Loads plugin javascript and stylesheet files in the admin area.
		 */
		function admin_script_style(){
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			wp_register_script( 'bbpress-shortcodes-scripts', plugins_url( '/admin/js/bbps-admin' . $suffix . '.js', BBPS_PLUGIN_FILE ), array( 'jquery' ), BBPS_VERSION, true  );
			wp_localize_script( 'bbpress-shortcodes-scripts', 'bbpress_shortcodes', array(
				'ajax_url' => admin_url( 'admin-ajax.php' )
			) );
			wp_enqueue_script( 'bbpress-shortcodes-scripts' );
			wp_enqueue_style( 'bbpress-shortcodes-styles', plugins_url( '/admin/css/bbps-admin.css', BBPS_PLUGIN_FILE ), array(), BBPS_VERSION );
		}

		/**
		 * Adds a link to the settings page in the plugins list.
		 *
		 * @param array  $links array of links for the plugins, adapted when the current plugin is found.
		 * @param string $file  the filename for the current plugin, which the filter loops through.
		 *
		 * @return array $links
		 */
		function plugin_settings_link( $links, $file ) {
			if ( false !== strpos( $file, 'bbpress-shortcodes' ) ) {
				$mylinks = array(
					'<a href="https://wordpress.org/support/plugin/bbpress-shortcodes/">' . esc_html__( 'Get Support', 'bbpress-shortcodes' ) . '</a>',
					'<a href="options-general.php?page=bbpress_shortcodes">' . esc_html__( 'Settings', 'bbpress-shortcodes' ) . '</a>'
				);
				$links = array_merge( $mylinks, $links );
			}
			return $links;
		}

		/**
		 * Displays plugin configuration notice in admin area.
		 */
		function setup_notice(){
			if (  0 === strpos( get_current_screen()->id, 'settings_page_bbpress_shortcodes' ) ) {
				return;
			}

			$hascaps = $this->networkactive ? is_network_admin() && current_user_can( 'manage_network_plugins' ) : current_user_can( 'manage_options' );

			if ( $hascaps ) {
				$url = is_network_admin() ? network_site_url() : site_url( '/' );
				echo '<div class="notice notice-info is-dismissible bbpress-shortcodes"><p>' . sprintf( __( 'To configure <em>bbPress Shortcodes plugin</em> please visit its <a href="%1$s">configuration page</a> and to get plugin support contact us on <a href="%2$s" target="_blank">plugin support forum</a>.', 'bbpress-shortcodes'), $url . 'wp-admin/options-general.php?page=bbpress_shortcodes', 'https://wordpress.org/support/plugin/' ) . '</p></div>';
			}
		}

		/**
		 * Handles plugin notice dismiss functionality using AJAX.
		 */
		function dismiss_notice() {
			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
				$this->opt['dismiss_admin_notices'] = 1;
				update_option( 'bbpress_shortcodes', $this->opt );
			}
			die();
		}

		/**
		 * Displays bbPress missing admin notice.
		 */
		function bbp_missing_notice() {
			echo '<div class="error"><p>' . sprintf( __( 'bbPress Shortcodes depends on the %s to work so please activate it on your site.', 'bbpress-shortcodes' ), '<a href="https://wordpress.org/plugins/bbpress/" target="_blank">' . __( 'bbPress', 'bbpress-shortcodes' ) . '</a>' ) . '</p></div>';
		}

		/**
		 * Checks whether the bbPress is installed.
		 */
		function check_bbp_installed() {

			if ( ! class_exists( 'bbPress' ) ) {
				add_action( 'admin_notices', array( $this, 'bbp_missing_notice' ) );
			}
		}

		/**
		 * Adds a button for shortcodes to the WP editor.
		 */
		function add_shortcode_button() {
			if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
				return;
			}

			if ( 'true' == get_user_option( 'rich_editing' ) && isset( $this->opt['bbpress_shortcodes_posts'] ) && ! empty( $this->opt['bbpress_shortcodes_posts'] ) ) {
				global $current_screen;
				if ( ! empty( $current_screen->post_type ) && in_array( $current_screen->post_type, $this->opt['bbpress_shortcodes_posts'] ) ) {
					add_filter( 'mce_external_plugins', array( $this, 'add_shortcode_tinymce_plugin' ) );
					add_filter( 'mce_buttons', array( $this, 'register_shortcode_button' ) );
				}
			}
		}

		/**
		 * Registers the shortcode button.
		 */
		function register_shortcode_button( $buttons ) {
			array_push( $buttons, '|', 'bbpress_shortcodes' );

			return $buttons;
		}

		/**
		 * Adds the shortcode button to TinyMCE.
		 */
		function add_shortcode_tinymce_plugin( $plugins ) {
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			$plugins['bbpress_shortcodes'] = plugins_url( 'admin/js/bbps-admin-editor' . $suffix . '.js', BBPS_PLUGIN_FILE );

			return $plugins;
		}

		/**
		 * Force TinyMCE to refresh.
		 */
		function refresh_mce( $version ) {
			$version += 3;

			return $version;
		}

		/**
		 * Registers plugin admin menu item.
		 */
		function admin_menu_setup(){
			add_submenu_page( 'options-general.php', __( 'bbPress Shortcodes Settings', 'bbpress-shortcodes' ), __( 'bbPress Shortcodes', 'bbpress-shortcodes' ), 'manage_options', 'bbpress_shortcodes', array( $this, 'admin_page_screen' ) );
		}

		/**
		 * Renders the settings page for this plugin.
		 */
		function admin_page_screen() {
			include_once( 'partials/admin-page.php' );
		}

		/**
		 * Registers plugin settings.
		 */
		function settings_init(){
			add_settings_section( 'bbpress_shortcodes_section', __( 'bbPress Shortcodes Settings', 'bbpress-shortcodes' ),  array( $this, 'settings_section_desc'), 'bbpress_shortcodes' );

			add_settings_field( 'bbpress_shortcodes_posts', __( 'Use in Post Types : ', 'bbpress-shortcodes' ),  array( $this, 'list_post_types' ), 'bbpress_shortcodes', 'bbpress_shortcodes_section' );
			add_settings_field( 'bbpress_shortcodes_enable', __( 'Execute Shortcodes: ', 'bbpress-shortcodes' ),  array( $this, 'enable_shortcode' ), 'bbpress_shortcodes', 'bbpress_shortcodes_section' );

			register_setting( 'bbpress_shortcodes', 'bbpress_shortcodes' );
		}

		/**
		 * Displays plugin description text.
		 */
		function settings_section_desc(){
			echo '<p>' . esc_html__( 'Configure the bbPress Shortcodes plugin settings here.', 'bbpress-shortcodes' ) . '</p>';
		}

		/**
		 * Displays choose post types field.
		 */
		function list_post_types() {
			$html = '';
			$args = array( 'public' => true );

			$posts = get_post_types( $args );

			if ( ! empty( $posts ) ){

				foreach ( $posts as $key => $post ) {

					$check_value = isset( $this->opt['bbpress_shortcodes_posts'][$key] ) ? $this->opt['bbpress_shortcodes_posts'][ $key ] : 0;
					$html .= '<input type="checkbox" id="bbpress_shortcodes_posts' . esc_attr( $key ) . '" name="bbpress_shortcodes[bbpress_shortcodes_posts][' . esc_attr( $key ) . ']" value="' . esc_attr( $key ) . '" ' . checked( $key, $check_value, false ) . '/>';
					$html .= '<label for="bbpress_shortcodes_posts' . esc_attr( $key ) . '"> ' . esc_html( $post ) . '</label><br />';
				}
			} else {
				$html = __( 'No post types registered on your site.', 'bbpress-shortcodes' );
			}
			echo $html;

		}

		/**
		 * Displays do shortcode field.
		 */
		function enable_shortcode() {
			$check_value = isset( $this->opt['bbpress_shortcodes_enable'] ) ? $this->opt['bbpress_shortcodes_enable'] : 0;
			$html = '<input type="checkbox" id="bbpress_shortcodes_enable" name="bbpress_shortcodes[bbpress_shortcodes_enable]" value="bbpress_shortcodes_enable" ' . checked( 'bbpress_shortcodes_enable', $check_value, false ) . ' />';
			$html .= '<label for="bbpress_shortcodes_enable"> ' . esc_html__( 'Parse shortcodes placed in topic and reply content', 'bbpress-shortcodes' ) . '</label>';
			$html .= '<br /><label for="bbpress_shortcodes_enable" style="font-size: 10px;">' . esc_html__( "By default bbPress does not render short codes placed into forum posts, this option enables shortcodes.", 'bbpress-shortcodes' ) . '</label>';
			echo $html;
		}
	}
}
