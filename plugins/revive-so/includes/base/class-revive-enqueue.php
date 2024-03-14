<?php 
/**
 * Enqueue all css & js.
 *
 */

defined( 'ABSPATH' ) || exit;

/**
 * Script class.
 */
class REVIVESO_Enqueue extends REVIVESO_BaseController
{
	use REVIVESO_Hooker;

	/**
	 * Register functions.
	 */
	public function register() {
		$this->action( 'admin_enqueue_scripts', 'load_assets' );
	}

	/**
	 * Load admin assets.
	 */
	public function load_assets( $hook ) {

		wp_register_style( 'reviveso-jquery-ui', REVIVESO_URL . 'assets/css/jquery-ui.min.css', array(), '1.13.1' );
		wp_register_style( 'reviveso-jquery-ui-timepicker', REVIVESO_URL . 'assets/css/jquery-ui-timepicker-addon.min.css', array(), '1.6.3' );
		wp_register_style( 'reviveso-select2', REVIVESO_URL . 'assets/css/select2.min.css',  array(), '4.0.13' );
		wp_register_style( 'reviveso-confirm', REVIVESO_URL . 'assets/css/jquery-confirm.min.css', array(),  '3.3.4' );
		wp_register_style( 'reviveso-styles', REVIVESO_URL . 'assets/css/admin.css', array( 'reviveso-jquery-ui', 'reviveso-jquery-ui-timepicker', 'reviveso-select2', 'reviveso-confirm' ), REVIVESO_VERSION );
		wp_register_style( 'reviveso-upsell-styles', REVIVESO_URL . 'assets/css/admin-upsells.css', array(), REVIVESO_VERSION );

		wp_register_script( 'reviveso-datetimepicker', REVIVESO_URL . 'assets/js/jquery-ui-timepicker-addon.min.js', array( 'jquery', 'jquery-ui-datepicker', 'jquery-ui-sortable' ), '1.6.3', true );
		wp_register_script( 'reviveso-select2', REVIVESO_URL . 'assets/js/select2.min.js', array( 'jquery' ), '4.0.13', true );
		wp_register_script( 'reviveso-confirm', REVIVESO_URL . 'assets/js/jquery-confirm.min.js', array( 'jquery' ), '3.3.4', true );
		wp_register_script( 'reviveso-admin', REVIVESO_URL . 'assets/js/admin.js', array( 'jquery', 'jquery-form', 'reviveso-datetimepicker', 'reviveso-select2', 'reviveso-confirm' ), REVIVESO_VERSION, true );
		wp_register_script( 'reviveso-admin-upsells', REVIVESO_URL . 'assets/js/admin-upsells.js', array( 'jquery' ), REVIVESO_VERSION, true );

		wp_register_style( 'reviveso-fa', REVIVESO_URL . 'assets/css/font-awesome.all.min.css', array(), '5.15.4' );

		wp_register_style( 'reviveso-extensions', REVIVESO_URL . 'assets/css/extensions.css', array(), REVIVESO_VERSION );
		wp_register_script( 'reviveso-extensions', REVIVESO_URL . 'assets/js/extensions.js', array( 'jquery', 'updates' ), REVIVESO_VERSION );

		if ( 'toplevel_page_reviveso' === $hook ) {
			wp_enqueue_style( 'reviveso-select2' );
			wp_enqueue_style( 'reviveso-jquery-ui' );
			wp_enqueue_style( 'reviveso-jquery-ui-timepicker' );
			wp_enqueue_style( 'reviveso-fa' );
			wp_enqueue_style( 'reviveso-styles' );

			wp_enqueue_script( 'jquery-form' );
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'jquery-ui-sortable' );
            wp_enqueue_script( 'reviveso-datetimepicker' );
			wp_enqueue_script( 'reviveso-select2' );
			wp_enqueue_script( 'reviveso-confirm' );
			wp_enqueue_script( 'reviveso-admin' );
			
			wp_localize_script( 'reviveso-admin', 'revsAdminL10n', apply_filters ( 'reviveso_admin_lang_strings', array(
				'ajaxurl'              => admin_url( 'admin-ajax.php' ),
				'nonce'                => wp_create_nonce( 'wp_reviveso_admin' ),
				'select_weekdays'      => __( 'Select weekdays (required)', 'revive-so' ),
				'select_post_types'    => __( 'Select post types (required)', 'revive-so' ),
				'select_post_statuses' => __( 'Select post statuses (required)', 'revive-so' ),
				'select_taxonomies'    => __( 'Select taxonomies', 'revive-so' ),
				'post_ids'             => __( 'Enter post or page or custom post ids (comma separated)', 'revive-so' ),
			) ) );
		}

		if ( 'revive-so_page_reviveso-extensions' === $hook ) {
			wp_enqueue_style( 'reviveso-extensions' );
			wp_enqueue_script( 'reviveso-extensions' );
			wp_localize_script( 'reviveso-extensions', 'revivesoAddons', array(
				// Add addon slug to verify addon's slug in wp-plugin-install-success action
				'installing_text'   => esc_html__( 'Installing & Activating addon...', 'modula-best-grid-gallery' ),
				'activating_text'   => esc_html__( 'Activating addon...', 'modula-best-grid-gallery' ),
				'deactivating_text' => esc_html__( 'Deactivating addon...', 'modula-best-grid-gallery' )
			) );
		}

		// Globaly load upsells css/js in admin env.
		$manage_options_cap = apply_filters( 'reviveso_manage_options_capability', 'manage_options' );
		if( is_admin() && current_user_can( $manage_options_cap ) ){
			wp_enqueue_style( 'reviveso-upsell-styles' );
			wp_enqueue_script( 'reviveso-admin-upsells' );
			wp_localize_script( 'reviveso-admin-upsells', 'revUps', array(
				'ajaxurl'              => admin_url( 'admin-ajax.php' ),
				'nonce'                => wp_create_nonce( 'wp_reviveso_admin' ),
			) );
		}
		do_action( 'reviveso_scripts_enqueue', $hook );
	}
}
