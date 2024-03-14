<?php
/**
 * Load assets.
 *
 * @package     EverAccounting
 * @subpackage  Admin
 * @version     1.0.2
 */

namespace EverAccounting\Admin;

defined( 'ABSPATH' ) || exit();

/**
 * Class Assets
 *
 * @package EverAccounting\Admin
 * @since   1.0.2
 */
class Assets {
	/**
	 * Assets constructor
	 *
	 * @version 1.0.2
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}

	/**
	 * Enqueue styles.
	 *
	 * @version 1.0.2
	 */
	public function admin_styles() {
		$version   = eaccounting()->get_version();
		$screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';
		// Register admin styles.
		wp_register_style( 'ea-admin-styles', eaccounting()->plugin_url() . '/dist/css/admin.min.css', array(), $version );
		wp_register_style( 'ea-release-styles', eaccounting()->plugin_url() . '/dist/css/release.min.css', array(), $version );
		wp_register_style( 'jquery-ui-style', eaccounting()->plugin_url() . '/dist/css/jquery-ui.min.css', array(), $version );

		// Add RTL support for admin styles.
		wp_style_add_data( 'ea-admin-styles', 'rtl', 'replace' );

		// Admin styles for Accounting pages only.
		if ( in_array( $screen_id, eaccounting_get_screen_ids(), true ) ) {
			wp_enqueue_style( 'ea-admin-styles' );
			wp_enqueue_style( 'jquery-ui-style' );
		}
	}


	/**
	 * Enqueue scripts.
	 *
	 * @version 1.0.2
	 */
	public function admin_scripts() {
		$screen                = get_current_screen();
		$screen_id             = $screen ? $screen->id : '';
		$eaccounting_screen_id = sanitize_title( esc_html__( 'Accounting', 'wp-ever-accounting' ) );
		$suffix                = '';
		$version               = eaccounting()->get_version();

		// 3rd parties.
		wp_register_script( 'jquery-blockui', eaccounting()->plugin_url( '/dist/js/jquery.blockUI.min.js' ), array( 'jquery' ), '2.70', true );
		wp_register_script( 'jquery-select2', eaccounting()->plugin_url( '/dist/js/select2.full.js' ), array( 'jquery' ), $version, true );
		wp_register_script( 'jquery-inputmask', eaccounting()->plugin_url( '/dist/js/jquery.inputmask.js' ), array( 'jquery' ), '1.0.2', true );
		wp_register_script( 'jquery-chartjs', eaccounting()->plugin_url( '/dist/js/chart.bundle.js' ), array( 'jquery' ), '1.0.2', true );
		wp_register_script( 'jquery-chartjs-labels', eaccounting()->plugin_url( '/dist/js/chartjs-plugin-labels.js' ), array( 'jquery' ), '1.0.2', true );
		wp_register_script( 'ea-print', eaccounting()->plugin_url( '/dist/js/printThis.js' ), array( 'jquery' ), $version, true );

		// core plugins.
		wp_register_script( 'ea-select', eaccounting()->plugin_url( '/dist/js/ea-select2.js' ), array( 'jquery', 'jquery-select2' ), $version, true );
		wp_register_script( 'ea-creatable', eaccounting()->plugin_url( '/dist/js/ea-creatable.js' ), array( 'jquery', 'ea-select', 'wp-util', 'ea-modal', 'jquery-blockui' ), $version, true );
		wp_register_script( 'ea-modal', eaccounting()->plugin_url( '/dist/js/ea-modal.js' ), array( 'jquery' ), $version, true );
		wp_register_script( 'ea-form', eaccounting()->plugin_url( '/dist/js/ea-form.js' ), array( 'jquery' ), $version, true );
		wp_register_script( 'ea-exporter', eaccounting()->plugin_url( '/dist/js/ea-exporter.js' ), array( 'jquery' ), $version, true );
		wp_register_script( 'ea-importer', eaccounting()->plugin_url( '/dist/js/ea-importer.js' ), array( 'jquery' ), $version, true );

		// core script.
		wp_register_script( 'ea-helper', eaccounting()->plugin_url( '/dist/js/ea-helper.js' ), array( 'jquery', 'jquery-blockui' ), $version, true );
		wp_register_script( 'ea-overview', eaccounting()->plugin_url( '/dist/js/ea-overview.js' ), array( 'jquery', 'jquery-daterange', 'jquery-chartjs' ), $version, true );
		wp_register_script( 'ea-settings', eaccounting()->plugin_url( '/dist/js/ea-settings.js' ), array( 'jquery' ), $version, true );
		wp_register_script( 'ea-admin', eaccounting()->plugin_url( '/dist/js/ea-admin.js' ), array( 'jquery' ), $version, true );

		// Admin scripts for Accounting pages only.
		if ( in_array( $screen_id, eaccounting_get_screen_ids(), true ) ) {
			// globally needed scripts.
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'jquery-ui-tooltip' );
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_script( 'jquery-select2' );
			wp_enqueue_script( 'jquery-inputmask' );
			wp_enqueue_script( 'jquery-blockui' );
			wp_enqueue_script( 'ea-modal' );
			wp_enqueue_script( 'ea-select' );
			wp_enqueue_script( 'ea-creatable' );
			wp_enqueue_script( 'ea-helper' );
			wp_enqueue_script( 'ea-admin' );
			wp_enqueue_script( 'ea-form' );

			wp_localize_script(
				'ea-select',
				'eaccounting_select_i10n',
				array(
					'ajaxurl' => eaccounting()->ajax_url(),
				)
			);

			wp_localize_script(
				'ea-form',
				'eaccounting_form_i10n',
				array(
					'ajaxurl'           => eaccounting()->ajax_url(),
					'global_currencies' => eaccounting_get_global_currencies(),
					'nonce'             => array(
						'get_account'  => wp_create_nonce( 'ea_get_account' ),
						'get_currency' => wp_create_nonce( 'ea_get_currency' ),
					),
				)
			);

			// export page.
			$tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
			if ( eaccounting_is_admin_page( 'ea-tools' ) && $tab && 'export' === $tab ) {
				wp_enqueue_script( 'ea-exporter' );
			}

			// import page.
			if ( eaccounting_is_admin_page( 'ea-tools' ) ) {
				wp_localize_script(
					'ea-importer',
					'eaccounting_importer_i10n',
					array(
						'uploaded_file_not_found' => esc_html__( 'Could not find the uploaded file, please try again', 'wp-ever-accounting' ),
						'select_field_to_preview' => esc_html__( '  - Select field to preview data -', 'wp-ever-accounting' ),
						'required'                => esc_html__( '(Required)', 'wp-ever-accounting' ),
					)
				);
				wp_enqueue_script( 'ea-importer' );
			}

			// settings page.
			if ( eaccounting_is_admin_page( 'ea-settings' ) ) {
				wp_enqueue_media();
				wp_enqueue_script( 'ea-settings' );
			}

			// report page.
			if ( eaccounting_is_admin_page( 'ea-reports' ) ) {
				wp_enqueue_script( 'jquery-chartjs' );
			}

			$default_currency = eaccounting()->settings->get( 'default_currency', 'USD' );
			wp_localize_script(
				'ea-admin',
				'eaccountingi10n',
				array(
					'site_url'   => site_url(),
					'admin_url'  => admin_url(),
					'asset_url'  => eaccounting()->plugin_url( '/assets' ),
					'plugin_url' => eaccounting()->plugin_url(),
					'currency'   => eaccounting_get_currency( $default_currency )->get_data(),
					'currencies' => eaccounting_get_currencies(
						array(
							'return' => 'raw',
							'number' => -1,
						)
					),
				)
			);
			wp_enqueue_media();
		}
	}


	/**
	 * Registers a script according to `wp_register_script`, additionally loading the translations for the file.
	 *
	 * @since 1.0.0
	 *
	 * @param string $handle       Name of the script. Should be unique.
	 * @param string $src          Full URL of the script, or path of the script relative to the WordPress root directory.
	 * @param array  $dependencies Optional. An array of registered script handles this script depends on. Default empty array.
	 * @param bool   $has_i18n     Optional. Whether to add a script translation call to this file. Default 'true'.
	 */
	protected static function register_react_script( $handle, $src, $dependencies = array(), $has_i18n = true ) {
		$relative_src = str_replace( plugins_url( '/', EACCOUNTING_PLUGIN_FILE ), '', $src );
		$asset_path   = str_replace( '.js', '.asset.php', eaccounting()->plugin_path( $relative_src ) );

		$version = eaccounting()->get_version();
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$version = time();
		}
		if ( file_exists( $asset_path ) ) {
			$asset        = require $asset_path;
			$dependencies = isset( $asset['dependencies'] ) ? array_merge( $asset['dependencies'], $dependencies ) : $dependencies;
			$version      = ! empty( $asset['version'] ) ? $asset['version'] : $version;
		}

		wp_register_script( $handle, $src, $dependencies, $version, true );

		if ( $has_i18n && function_exists( 'wp_set_script_translations' ) ) {
			wp_set_script_translations( $handle, 'wp-ever-accounting', dirname( __DIR__ ) . '/languages' );
		}
	}

	/**
	 * Returns the appropriate asset url
	 *
	 * @param string $filename Filename for asset url (without extension).
	 * @param string $type     File type (.css or .js).
	 *
	 * @return  string The generated path.
	 */
	protected static function get_asset_dist_url( $filename, $type = 'js' ) {
		return eaccounting()->plugin_url( "assets/dist/$filename.$type" );
	}
}

return new \EverAccounting\Admin\Assets();
