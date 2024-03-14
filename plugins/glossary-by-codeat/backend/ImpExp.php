<?php

/**
 * Glossary
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2020
 * @license   GPL 3.0+
 * @link      https://codeat.co
 */

namespace Glossary\Backend;

use Glossary\Engine;

/**
 * Provide Import and Export of the settings of the plugin
 */
class ImpExp extends Engine\Base {

	/**
	 * Initialize the class.
	 *
	 * @return bool
	 */
	public function initialize() {
		if ( !parent::initialize() ) {
			return false;
		}

		if ( \current_user_can( 'manage_options' ) ) {
			// Add the export settings method
			\add_action( 'admin_init', array( $this, 'settings_export' ) );
			// Add the import settings method
			\add_action( 'admin_init', array( $this, 'settings_import' ) );
		}

		return true;
	}

	/**
	 * Process a settings export from config
	 *
	 * @since 2.0
	 * @return void
	 */
	public function settings_export() {
		if ( empty( $_POST[ 'g_action' ] ) ||
			'export_settings' !== \sanitize_text_field(
				\wp_unslash( $_POST[ 'g_action' ] ) //phpcs:ignore WordPress.Security.NonceVerification
		) ) {
			return;
		}

		if ( !\wp_verify_nonce(
			\sanitize_text_field(
				\wp_unslash( $_POST[ 'g_export_nonce' ] )//phpcs:ignore WordPress.Security.ValidatedSanitizedInput
			),
			'g_export_nonce'
		) ) {
			return;
		}

		$settings      = array();
		$settings[ 0 ] = \get_option( GT_SETTINGS . '-settings' );

		\ignore_user_abort( true );

		\nocache_headers();
		\header( 'Content-Type: application/json; charset=utf-8' );
		\header( 'Content-Disposition: attachment; filename=gt-settings-export-' . \gmdate( 'm-d-Y' ) . '.json' );
		\header( 'Expires: 0' );

		echo \wp_json_encode( $settings, JSON_PRETTY_PRINT );

		exit;
	}

	/**
	 * Process a settings import from a json file
	 *
	 * @since 2.0
	 * @return void
	 */
	public function settings_import() {
		if (
			empty( $_POST[ 'g_action' ] )
			|| 'import_settings' !== \sanitize_text_field( \wp_unslash( $_POST[ 'g_action' ] ) )
		) {
			return;
		}

		if ( ! \wp_verify_nonce( \sanitize_text_field( \wp_unslash( $_POST[ 'g_import_nonce' ] ) ), 'g_import_nonce' ) ) { //phpcs:ignore WordPress.Security.ValidatedSanitizedInput
			return;
		}

		if ( !isset( $_FILES[ 'g_import_file' ][ 'name' ] ) ) {
			return;
		}

		$exploded  = \explode( '.', $_FILES[ 'g_import_file' ][ 'name' ] ); //phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$extension = \end( $exploded );

		if ( 'json' !== $extension ) {
			\wp_die( \esc_html__( 'Please upload a valid .json file', GT_SETTINGS ) );
		}

		$import_file = $_FILES[ 'g_import_file' ][ 'tmp_name' ]; //phpcs:ignore WordPress.Security.ValidatedSanitizedInput

		if ( empty( $import_file ) ) {
			\wp_die( \esc_html__( 'Please upload a file to import', GT_SETTINGS ) );
		}

		// Retrieve the settings from the file and convert the json object to an array.
		$settings_file = file_get_contents( $import_file );// phpcs:ignore

		if ( $settings_file !== false ) {
			$settings = \json_decode( (string) $settings_file );

			if ( \is_array( $settings ) ) {
				\update_option( GT_SETTINGS . '-settings', \get_object_vars( $settings[ 0 ] ) );

				\wp_safe_redirect( \admin_url( 'edit.php?post_type=glossary&page=glossary#tabs-impexp' ) );
				exit;
			}
		}

		new \WP_Error(
				'glossary_import_settings_failed',
				\__( 'Failed to import the settings.', GT_SETTINGS )
			);
	}

}
