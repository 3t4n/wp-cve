<?php
/**
 * A block for registering widget areas within the WordPress 5 Gutenberg block editor.
 *
 * @link https://organicthemes.com
 * @since 1.0.0
 * @package Organic Widget Area Block
 *
 * @wordpress-plugin
 * Plugin Name: Organic Widget Area Block
 * Plugin URI: https://organicthemes.com/
 * Description: The Widget Area Block registers widget areas within the Gutenberg block editor. It's an excellent tool for adding traditional widgets within the block editor.
 * Author: Organic Themes
 * Author URI: https://organicthemes.com
 * Version: 1.2.3
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: owa
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

if ( ! class_exists( 'Organic_Widget_Area' ) ) {

	/**
	 * Class InitializePlugin
	 *
	 * @package organic_widget_area
	 */
	class Organic_Widget_Area {

		/**
		 * The plugin version number
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string
		 */
		private $plugin_version = '1.2.3';

		/**
		 * Allows the debugging scripts to initialize and log them in a file
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string
		 */
		private $log_debug_messages = false;

		/**
		 * The instance of the class
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      Object
		 */
		private static $instance;

		/**
		 * Load instance of the class
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      Object
		 */
		public static function get_instance() {

			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Class constructor
		 */
		public function __construct() {

			$this->define_constants();
			$this->loader();

			add_action( 'init', [ $this, 'register_widget_block' ] );
			add_action( 'init', [ $this, 'load_scripts' ] );

			add_action( 'widgets_init', [ 'OWA_Block', 'register_widget_sidebar' ], 0 );
			add_action( 'save_post', [ 'OWA_Block', 'update_widgets_log' ], 10, 3 );
			add_action( 'delete_post', [ 'OWA_Block', 'delete_widgets_log' ], 10 );

		}

		/**
		 * The full path and filename
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string
		 */
		public function define_constants() {
			define( 'OWA_PLUGIN_URL', plugins_url( 'organic-widget-area-block' ) );
			define( 'OWA_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
		}

		/**
		 * Load the class
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      Object
		 */
		public function loader() {
			require_once OWA_PLUGIN_DIR_PATH . 'src/class/class-widget-area.php';
		}

		/**
		 * Load block scripts and styles.
		 */
		public function load_scripts() {

			if ( ! function_exists( 'register_block_type' ) ) {
				// Gutenberg is not active.
				return;
			}

			// Block front end styles.
			wp_register_style(
				'owa-front-end-styles',
				OWA_PLUGIN_URL . '/src/css/style.css',
				array(),
				filemtime( OWA_PLUGIN_DIR_PATH . 'src/css/style.css' )
			);
			// Block editor styles.
			wp_register_style(
				'owa-editor-styles',
				OWA_PLUGIN_URL . '/src/css/editor.css',
				array( 'wp-edit-blocks' ),
				filemtime( OWA_PLUGIN_DIR_PATH . 'src/css/editor.css' )
			);
			// Widget Area Editor Script.
			wp_register_script(
				'owa-block-js',
				OWA_PLUGIN_URL . '/src/block/widget-area-block.js',
				array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n' ),
				filemtime( OWA_PLUGIN_DIR_PATH . 'src/block/widget-area-block.js' ),
				true
			);

		}

		/**
		 * Register widget block type.
		 */
		public function register_widget_block() {
			register_block_type(
				'organic/widget-area',
				array(
					'attributes'      => array(
						'widgetAreaTitle' => array(
							'type'    => 'string',
							'default' => '',
						),
					),
					'style'           => 'owa-front-end-styles',
					'editor_style'    => 'owa-editor-styles',
					'editor_script'   => 'owa-block-js',
					'render_callback' => function ( $block ) {
						$html = OWA_Block::render_block_html( $block );
						return $html;
					},
				)
			);
		}

	} // End of class.

	// Let's run it.
	Organic_Widget_Area::get_instance();
}

/*
-------------------------------------------------------------------------------------------------------
	Admin Notice
-------------------------------------------------------------------------------------------------------
*/

/** Function owa_admin_notice_upgrade */
function owa_admin_notice_upgrade() {
	if ( ! PAnD::is_admin_notice_active( 'notice-owa-upgrade-forever' ) ) {
		return;
	}
	?>

	<div data-dismissible="notice-owa-upgrade-forever" class="notice updated is-dismissible">

		<p><?php printf( wp_kses_post( '<a href="%1$s" target="_blank">Upgrade The Widget Area Block</a> and receive <a href="%2$s" target="_blank">11 Additional Premium Blocks</a> for the Gutenberg editor!', 'owa' ), 'https://organicthemes.com/block/widget-area-block/', 'https://organicthemes.com/blocks/' ); ?></p>
		<p><a class="button button-primary" href="https://organicthemes.com/blocks/" target="_blank"><?php esc_html_e( 'Get Blocks Bundle', 'owa' ); ?></a></p>

	</div>

	<?php
}

add_action( 'admin_init', array( 'PAnD', 'init' ) );
add_action( 'admin_notices', 'owa_admin_notice_upgrade' );

require OWA_PLUGIN_DIR_PATH . 'admin/persist-admin-notices-dismissal/persist-admin-notices-dismissal.php';
