<?php
/**
 * Plugin Name: Product Labels For WooCommerce
 * Description: Allows to create beautiful product labels for your WooCommerce store.
 * Version: 1.0.0
 * Author: Lion Plugins
 * Author URI: https://www.lionplugins.com
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define( 'LION_BADGES_VERSION', '1.0.0' );
define( 'LION_BADGES_FILE', __FILE__ );
define( 'LION_BADGES_PATH', untrailingslashit( plugin_dir_path( LION_BADGES_FILE ) ) );
define( 'LION_BADGES_URL', untrailingslashit( plugin_dir_url( LION_BADGES_FILE ) ) );

if ( ! class_exists( 'Lion_Badges' ) ) :

	class Lion_Badges {

		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
		}

		public function plugins_loaded() {
			$this->includes();
			
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_settings_link' ) );
		}

		/**
		 * Includes
		 */
		public function includes() {
			include_once LION_BADGES_PATH . '/inc/global-functions.php';

			if ( is_admin() ) {
				include_once LION_BADGES_PATH . '/admin/class-admin.php';
				include_once LION_BADGES_PATH . '/admin/inc/classes/class-ajax.php';
				include_once LION_BADGES_PATH . '/admin/inc/classes/class-option.php';
				include_once LION_BADGES_PATH . '/admin/inc/classes/class-options.php';
				include_once LION_BADGES_PATH . '/admin/inc/classes/class-meta-boxes.php';
				include_once LION_BADGES_PATH . '/admin/inc/classes/class-tabs.php';
				include_once LION_BADGES_PATH . '/admin/inc/classes/class-tabs-horizontal.php';
				include_once LION_BADGES_PATH . '/admin/inc/classes/class-settings-page.php';
			}

			include_once LION_BADGES_PATH . '/inc/classes/class-badge-cpt.php';
			include_once LION_BADGES_PATH . '/inc/classes/class-badge.php';
			include_once LION_BADGES_PATH . '/inc/classes/class-badge-style.php';
			include_once LION_BADGES_PATH . '/inc/classes/class-badge-compatibility.php';
		}

		/**
		 * Adds a settings link in plugins page
		 * 
		 * @param array $links
		 * @return array
		 */
		public function plugin_settings_link( $links ) {
			$links[] = '<a href="' . esc_url(
				add_query_arg(
					array(
						'post_type' => 'lion_badge'
					),
					admin_url( 'edit.php' )
				)
			) . '">' . __( 'Settings' ) . '</a>';

			return $links;
		}
	}

endif;

// Check if WooCommerce is activated
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	$Lion_Badges = new Lion_Badges();

	register_activation_hook( __FILE__, 'lionplugins_badges_activate' );
} else {
	/*
	 * No WooCommerce plugin message.
	 */
	function lionplugins_badges_no_woocommerce_message() {
		?>
		<div class="notice notice-error is-dismissible">
			<p><?php _e( 'Badges For WooCommerce plugin is activated, but inactive. It requires WooCommerce plugin to work.', 'lionplugins' ); ?></p>
		</div>
		<?php
	}

	add_action( 'admin_notices', 'lionplugins_badges_no_woocommerce_message' );
}

/*
 * Activate hook.
 */
function lionplugins_badges_activate() { 
	if ( ! get_option( 'lion_badges' ) ) {
		$settings = array(
			'hide_default_wc_badge' => 0
		);

		add_option( 'lion_badges', $settings );
	}
}
