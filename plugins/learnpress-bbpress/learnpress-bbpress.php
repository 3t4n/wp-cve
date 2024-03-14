<?php
/**
 * Plugin Name: LearnPress - bbPress Integration
 * Plugin URI: http://thimpress.com/learnpress
 * Description: Using the forum for courses provided by bbPress.
 * Author: ThimPress
 * Version: 4.0.3
 * Author URI: http://thimpress.com
 * Tags: learnpress, lms, add-on, bbpress
 * Text Domain: learnpress-bbpress
 * Domain Path: /languages/
 * Require_LP_Version: 3.0.0
 * Require_BBpress_Version: 2.0.0
 *
 * @package LearnPress-bbPress-Integration
 */

defined( 'ABSPATH' ) || exit;

define( 'LP_ADDON_BBPRESS_FILE', __FILE__ );

/**
 * Class LP_Addon_bbPress_Preload
 */
class LP_Addon_bbPress_Preload {
	/**
	 * @var array|string[]
	 */
	public static $addon_info = array();

	/**
	 * LP_Addon_bbPress_Preload constructor.
	 */
	public function __construct() {
		// Set Base name plugin.
		define( 'LP_ADDON_BBPRESS_BASENAME', plugin_basename( LP_ADDON_BBPRESS_FILE ) );

		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		// Set version addon for LP check .
		self::$addon_info = get_file_data(
			LP_ADDON_BBPRESS_FILE,
			array(
				'Name'                    => 'Plugin Name',
				'Require_LP_Version'      => 'Require_LP_Version',
				'Version'                 => 'Version',
				'Require_BBpress_Version' => 'Require_BBpress_Version',
			)
		);

		define( 'LP_ADDON_BBPRESS_VER', self::$addon_info['Version'] );
		define( 'LP_ADDON_BBPRESS_REQUIRE_VER', self::$addon_info['Require_LP_Version'] );

		// Check LP activated .
		if ( ! is_plugin_active( 'learnpress/learnpress.php' ) ) {
			add_action( 'admin_notices', array( $this, 'show_note_errors_require_lp' ) );

			deactivate_plugins( LP_ADDON_BBPRESS_BASENAME );

			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}

			return;
		}

		// Check BBpress activated.
		$bbpress_valid = true;
		if ( ! is_plugin_active( 'bbpress/bbpress.php' ) ) {
			$bbpress_valid = false;
		} else {
			$bbpress_info = get_plugin_data( WP_PLUGIN_DIR . '/bbpress/bbpress.php' );

			if ( version_compare( self::$addon_info['Require_BBpress_Version'], $bbpress_info['Version'], '>' ) ) {
				$bbpress_valid = false;
			}
		}

		if ( ! $bbpress_valid ) {
			add_action( 'admin_notices', array( $this, 'show_notices_bbpress' ) );

			deactivate_plugins( LP_ADDON_BBPRESS_BASENAME );

			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}

			return;
		}

		// Sure LP loaded.
		add_action( 'learn-press/ready', array( $this, 'load' ) );
	}

	/**
	 * Load addon
	 */
	public function load() {
		LP_Addon::load( 'LP_Addon_bbPress', 'inc/load.php', __FILE__ );
	}

	public function show_note_errors_require_lp() {
		?>
		<div class="notice notice-error">
			<p><?php echo( 'Please active <strong>LearnPress version ' . LP_ADDON_BBPRESS_REQUIRE_VER . ' or later</strong> before active <strong>' . self::$addon_info['Name'] . '</strong>' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Show admin notice when inactive bbPress.
	 */
	public function show_notices_bbpress() {
		?>
		<div class="notice notice-error">
			<p>
				<?php echo wp_kses(
					sprintf(
						__( '<strong>BBPress</strong> addon for <strong>LearnPress</strong> requires %s version %s is <strong>activated</strong>.',
							'learnpress-bbpress' ),
						sprintf( '<a href="%s" target="_blank">bbPress</a>',
							admin_url( 'plugin-install.php?tab=search&type=term&s=bbpress' ) ),
						self::$addon_info['Require_BBpress_Version']
					), array(
						'a'      => array(
							'href'   => array(),
							'target' => array(),
						),
						'strong' => array()
					)
				); ?>
			</p>
		</div>
	<?php }
}

new LP_Addon_bbPress_Preload();
