<?php

namespace QuadLayers\QuadMenu;

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

use QuadLayers\QuadMenu\Plugin as Plugin;
/**
 * Admin Class ex QuadMenu_Admin
 */
class Admin {

	private static $instance;

	public function __construct() {

		add_action( 'wp_ajax_quadmenu_dismiss_notice', array( $this, 'ajax_dismiss_notice' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'register' ), -1 );

		add_action( 'admin_notices', array( $this, 'notices' ) );

		add_action( 'admin_footer', array( $this, 'icons' ) );

	}

	public function register() {

		$admin = include QUADMENU_PLUGIN_DIR . 'build/backend/index.asset.php';

		wp_register_style( 'quadmenu-modal', QUADMENU_PLUGIN_URL . 'assets/backend/css/modal' . Plugin::isMin() . '.css', array(), QUADMENU_PLUGIN_VERSION, 'all' );

		wp_register_style( 'quadmenu-admin', QUADMENU_PLUGIN_URL . 'build/backend/style.css', array( 'quadmenu-modal', Plugin::instance()->selected_icons()->ID ), QUADMENU_PLUGIN_VERSION, 'all' );

		wp_register_script( 'quadmenu-admin', QUADMENU_PLUGIN_URL . 'build/backend/index.js', $admin['dependencies'], $admin['version'], false );
	}

	public function icons() {

		global $pagenow, $quadmenu_active_locations;

		if ( $pagenow != 'nav-menus.php' ) {
			return;
		}

		if ( ! is_array( $quadmenu_active_locations ) ) {
			return;
		}

		if ( ! count( $quadmenu_active_locations ) ) {
			return;
		}
		?>
		<script>
			jQuery(document).ready(function () {
				var $list = jQuery('.menu-theme-locations'),
					locations = <?php echo json_encode( array_keys( $quadmenu_active_locations ) ); ?>,
					icon = '<img src="<?php echo esc_url( QUADMENU_PLUGIN_URL . 'assets/backend/img/icond.png' ); ?>" style="width: 1em; height: auto; margin: 1px 0 -1px 0; "/>';

				jQuery.each(locations, function (index, item) {
					$list.find('input#locations-' + item).after(icon);
				});
			});
		</script>
		<?php
	}

	public function notices() {
		if ( $notices = get_option( 'quadmenu_admin_notices', false ) ) {
			foreach ( $notices as $notice ) {
				if ( empty( $notice['class'] ) || empty( $notice['notice'] ) ) {
					continue;
				}
				?>
					<div class="<?php echo esc_attr( $notice['class'] ); ?>">
						<p><?php esc_html_e( $notice['notice'] ); ?></p>
					</div>
				<?php
			}
			delete_option( 'quadmenu_admin_notices' );
		}
	}

	static function add_notification( $class = 'updated', $notice = false ) {

		if ( ! $notice ) {
			return;
		}

		$notices = get_option( 'quadmenu_admin_notices', array() );

		$notices[] = array(
			'class'  => $class,
			'notice' => $notice,
		);

		update_option( 'quadmenu_admin_notices', $notices );
	}

	function ajax_dismiss_notice() {

		if ( $notice_id = ( isset( $_POST['notice_id'] ) ) ? sanitize_key( $_POST['notice_id'] ) : '' ) {

			update_user_meta( get_current_user_id(), $notice_id, true );

			wp_send_json( $notice_id );
		}

		wp_die();
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
