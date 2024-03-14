<?php
/**
 * Plugin Name:       LRW Widgets Bundle
 * Plugin URI:        https://github.com/luizrw
 * Description:       Extends the functions of the plugin SiteOrigin Widgets with new widgets options.
 * Version:           1.1.3
 * Author:            LRW
 * Author URI:        https://github.com/luizrw
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       lrw-so-widgets-bundle
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'LRW_BUNDLE_VERSION', '1.1.3' );
define( 'LRW_BASE_FILE', __FILE__ );

if ( ! class_exists( 'LRW_Widgets_Bundle' ) ) :

	/**
	 * Main class
	 */
	class LRW_Widgets_Bundle {
		/**
		 * Instance of this class.
		 *
		 * @var object
		 */
		private static $instance = null;

		// protected $photoswipe_settings;

		/**
		 * Return an instance of this class.
		 *
		 * @return object A single instance of this class.
		 */
		public static function get_instance() {

			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;

		}

		/**
		 * Initialize the plugin public actions.
		 */
		private function __construct() {
			add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
			add_filter( 'siteorigin_widgets_widget_folders', array( &$this, 'path_extras_widgets' ) );
			add_filter( 'siteorigin_panels_widget_dialog_tabs', array( &$this, 'add_widget_tabs' ), 20  );
			add_filter( 'siteorigin_panels_widgets', array( &$this, 'set_widget_icon_group' ) );
		}

		/**
		 * Load the plugin text domain for translation.
		 */
		public function load_plugin_textdomain() {
			$domain = 'lrw-so-widgets-bundle';
			$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

			load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . 'lrw-so-widgets-bundle/lrw-so-widgets-bundle-' . $locale . '.mo' );
			load_plugin_textdomain( 'lrw-so-widgets-bundle', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}

		/**
		 * Set path for extra widgets
		 *
		 * @param $folders
		 *
		 * @return void
		 * @since 1.0.0
		 */
		function path_extras_widgets( $folders ) {
			$folders[] = plugin_dir_path ( __FILE__ ) . 'widgets/';

			return $folders;
		}

		/**
		 * Add Widgets Tabs for theme
		 *
		 * @param $tabs
		 *
		 * @return void
		 * @since 1.0.0
		 */
		function add_widget_tabs( $tabs ) {
			$tabs[] = array(
				'title' => __( 'LRW Bundle', 'lrw-so-widgets-bundle' ),
				'filter' => array(
					'groups' => array( 'lrw_so_widgets_group' )
				)
			);

			return $tabs;
		}

		/**
		 * Add Widgets icons and groups for all extra Widgets
		 *
		 * @param $widgets
		 *
		 * @return void
		 * @since 1.0.0
		 */
		function set_widget_icon_group( $widgets ) {

			$lrw_widgets = array(
				'LRW_Widget_CTA',
				'LRW_Widget_Counter',
				'LRW_Widget_Empty_Space',
				'LRW_Widget_Feature',
				'LRW_Widget_Heading',
				'LRW_Widget_Icon',
				'LRW_Widget_Member',
				'LRW_Widget_Pie_Chart',
				'LRW_Widget_Progress_Bar',
				'LRW_Widget_Progress_Bar_Vertical',
				'LRW_Widget_Promote_Box',
				'LRW_Widget_Separator',
				'LRW_Widget_Slider',
				'LRW_Widget_Word_Rotator',
				'LRW_Widget_Word_Typed',
			);

			foreach ( $lrw_widgets as $lrw_widget ) :

				if ( isset( $widgets[$lrw_widget] ) ) :
					$widgets[$lrw_widget]['groups'] = array( 'lrw_so_widgets_group' );
					$widgets[$lrw_widget]['icon']   = 'dashicons dashicons-layout';
				endif;

			endforeach;

			return $widgets;
		}
	}

	add_action( 'plugins_loaded', array( 'LRW_Widgets_Bundle', 'get_instance' ), 0 );

endif;
