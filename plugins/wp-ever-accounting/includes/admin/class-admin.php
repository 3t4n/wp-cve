<?php
/**
 * EverAccounting Admin.
 *
 * @package     EverAccounting
 * @subpackage  Admin
 * @version     1.0.2
 */

namespace EverAccounting\Admin;

defined( 'ABSPATH' ) || exit();

/**
 * Class Admin
 *
 * @since   1.0.2
 */
class Admin {
	/**
	 * Admin constructor.
	 *
	 * @since   1.0.2
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'includes' ) );
		add_action( 'current_screen', array( $this, 'conditional_includes' ) );
		add_action( 'admin_init', array( $this, 'admin_redirects' ) );
		add_action( 'admin_init', array( $this, 'buffer' ), 1 );
		add_filter( 'admin_body_class', array( $this, 'admin_body_class' ) );
		add_action( 'admin_footer', 'eaccounting_print_js', 25 );
		add_action( 'admin_footer', array( $this, 'load_js_templates' ) );
		add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ), 1 );
	}

	/**
	 * Include any classes we need within admin.
	 *
	 * @since 1.0.2
	 * @return void
	 */
	public function includes() {
		require_once EACCOUNTING_ABSPATH . '/includes/admin/ea-admin-functions.php';
		require_once EACCOUNTING_ABSPATH . '/includes/admin/class-notices.php';
		require_once EACCOUNTING_ABSPATH . '/includes/admin/class-menu.php';
		require_once EACCOUNTING_ABSPATH . '/includes/admin/class-assets.php';
		require_once EACCOUNTING_ABSPATH . '/includes/admin/class-settings.php';
		require_once EACCOUNTING_ABSPATH . '/includes/admin/class-exporter.php';
		require_once EACCOUNTING_ABSPATH . '/includes/admin/class-importer.php';
		require_once EACCOUNTING_ABSPATH . '/includes/admin/class-dashboard.php';
		require_once EACCOUNTING_ABSPATH . '/includes/admin/class-invoice-actions.php';
		require_once EACCOUNTING_ABSPATH . '/includes/admin/class-bill-actions.php';
		require_once EACCOUNTING_ABSPATH . '/includes/admin/class-extensions.php';

		// Setup/welcome.
		$page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING );
		if ( ! empty( $page ) ) {
			switch ( $page ) {
				case 'ea-setup':
					include_once dirname( __FILE__ ) . '/class-setup.php';
					break;
				case 'ea-release':
					include_once dirname( __FILE__ ) . '/class-release.php';
					break;
			}
		}
	}

	/**
	 * Include admin files conditionally.
	 *
	 * @since 1.0.2
	 * @return void
	 */
	public function conditional_includes() {
		$screen = get_current_screen();

		if ( ! $screen ) {
			return;
		}

	}

	/**
	 * Output buffering allows admin screens to make redirects later on.
	 *
	 * @since 1.0.2
	 * @return void
	 */
	public function buffer() {
		ob_start();
	}

	/**
	 * Handle redirects to setup/welcome page after install and updates.
	 *
	 * For setup wizard, transient must be present, the user must have access rights, and we must ignore the network/bulk plugin updaters.
	 *
	 * @since 1.0.
	 */
	public function admin_redirects() {
		if ( get_option( 'ea_setup_wizard_complete' ) !== 'yes' && get_transient( '_eaccounting_activation_redirect' ) && apply_filters( 'eaccounting_enable_setup_wizard', true ) ) {
			$do_redirect = true;

			// On these pages, or during these events, postpone the redirect.
			if ( wp_doing_ajax() || is_network_admin() || ! current_user_can( 'manage_eaccounting' ) ) {
				$do_redirect = false;
			}

			if ( $do_redirect ) {
				delete_transient( '_eaccounting_activation_redirect' );
				wp_safe_redirect( admin_url( 'index.php?page=ea-setup' ) );
				exit;
			}
		}
	}

	/**
	 * Add custom class in admin body
	 *
	 * @since 1.0.2
	 *
	 * @param string $classes Admin body classes.
	 *
	 * @return string
	 */
	public function admin_body_class( $classes ) {
		if ( eaccounting_is_admin_page() ) {
			$classes .= ' eaccounting ';
		}

		return $classes;
	}

	/**
	 * Change the admin footer text on EverAccounting admin pages.
	 *
	 * @since  1.0.2
	 *
	 * @param string $footer_text text to be rendered in the footer.
	 *
	 * @return string
	 */
	public function admin_footer_text( $footer_text ) {
		if ( ! current_user_can( 'manage_options' ) || ! function_exists( 'eaccounting_get_screen_ids' ) ) {
			return $footer_text;
		}
		$current_screen = get_current_screen();
		$ea_pages       = eaccounting_get_screen_ids();

		// Set only EA pages.
		$ea_pages = array_diff( $ea_pages, array( 'profile', 'user-edit' ) );

		// Check to make sure we're on a EverAccounting admin page.
		if ( isset( $current_screen->id ) && apply_filters( 'eaccounting_display_admin_footer_text', in_array( $current_screen->id, $ea_pages, true ) ) ) {
			// Change the footer text.
			if ( ! get_option( 'eaccounting_admin_footer_text_rated' ) ) {
				$footer_text = sprintf(
				/* translators: %s page */
					__( 'If you like %1$s please leave us a %2$s rating. A huge thanks in advance!', 'wp-ever-accounting' ),
					sprintf( '<strong>%s</strong>', esc_html__( 'Ever Accounting', 'wp-ever-accounting' ) ),
					'<a href="https://wordpress.org/support/plugin/wp-ever-accounting/reviews?rate=5#new-post" target="_blank" class="ea-rating-link" aria-label="' . esc_attr__( 'five star', 'wp-ever-accounting' ) . '" data-rated="' . esc_attr__( 'Thanks :)', 'wp-ever-accounting' ) . '">&#9733;&#9733;&#9733;&#9733;&#9733;</a>'
				);
				eaccounting_enqueue_js(
					"jQuery( 'a.ea-rating-link' ).click( function() {
						jQuery.post( '" . eaccounting()->ajax_url() . "', { action: 'eaccounting_rated' } );
						jQuery( this ).parent().text( jQuery( this ).data( 'rated' ) );
					});"
				);
			} else {
				$footer_text = esc_html__( 'Thank you for using with Ever Accounting.', 'wp-ever-accounting' );
			}
		}

		return $footer_text;
	}

	/**
	 * Load js templates
	 *
	 * @since 1.0.2
	 */
	public function load_js_templates() {
		$screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';
		$action    = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
		if ( in_array( $screen_id, eaccounting_get_screen_ids(), true ) && in_array( $action, array( 'add', 'edit' ), true ) ) {
			eaccounting_get_admin_template( 'js/modal-add-account' );
			eaccounting_get_admin_template( 'js/modal-add-currency' );
			eaccounting_get_admin_template( 'js/modal-add-income-category' );
			eaccounting_get_admin_template( 'js/modal-add-expense-category' );
			eaccounting_get_admin_template( 'js/modal-add-item-category' );
			eaccounting_get_admin_template( 'js/modal-add-customer' );
			eaccounting_get_admin_template( 'js/modal-add-vendor' );
			eaccounting_get_admin_template( 'js/modal-add-invoice-item' );
			eaccounting_get_admin_template( 'js/modal-add-item' );
		}
	}
}

return new Admin();
