<?php

namespace WP_VGWORT;

/**
 * Class for handling CSV Imports and Exports
 *
 * @package     vgw-metis
 * @copyright   Verwertungsgesellschaft Wort
 * @license     https://www.gnu.org/licenses/gpl-3.0.html
 * @author      Torben Gallob
 * @author      Michael Hillebrand
 *
 */
class Csv {
	/**
	 * @var bool is csv import and export activated?
	 */
	public bool $activated = false;

	/**
	 * @var array allowed mimetypes
	 */
	private array $allowed_mime_types = [ 'text/csv', 'text/plain' ];

	/**
	 * @var array allowed file extensions
	 */
	private array $allowed_file_extensions = [ 'csv' ];

	/**
	 * @var array allowed import_types
	 */
	private array $allowed_import_types = [ 'from_tom' ];

	/**
	 * @var string name of the first column of a t.o.m. csv export, used to check csv file
	 */
	const TOM_PUBLIC_COLUMN_NAME = 'Öffentlicher Identifikationscode';

	/**
	 * @var string name of the second column of a t.o.m. csv export, used to check csv file
	 */
	const TOM_PRIVATE_COLUMN_NAME = 'Privater Identifikationscode';

	/**
	 * @var object holds plugin reference
	 */
	private object $plugin;

	/**
	 * constructor
	 *
	 * @param object $plugin
	 */
	public function __construct( object &$plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * csv file upload & error handling
	 *
	 * @return void
	 */
	public function handle_csv_file_upload(): void {
		// check if we have an API key, if not, redirect and show warning
		//Services::has_api_key_or_redirect();

		if ( empty( $_REQUEST['action'] ) || sanitize_key( $_REQUEST['action'] ) != 'wp_metis_import_csv' ) {
			// no action given. call not allowed. go to dashboard and show error.
			Services::redirect_to_vgw_metis_page( 'metis-settings', 'wp_metis_import_csv_tom_error_call_not_allowed' );
		}

		if ( empty( $_REQUEST['import_type'] ) || ! in_array( sanitize_key( $_REQUEST['import_type'] ), $this->allowed_import_types ) ) {
			// no import type given. go to dashboard and show error.
			Services::redirect_to_vgw_metis_page( 'metis-settings', 'wp_metis_import_csv_tom_error_no_import_type' );
		}

		$import_type = sanitize_key( $_REQUEST['import_type'] );

		if ( $this->is_uploaded_file_check( $_FILES['wp_metis_csv_upload']['tmp_name'] ) ) {
			$mime_type = sanitize_mime_type( mime_content_type( $_FILES['wp_metis_csv_upload']['tmp_name'] ) );

			if ( ! in_array( $mime_type, $this->allowed_mime_types ) ) {
				// File type / MIME type is NOT allowed. Redirect.
				Services::redirect_to_vgw_metis_page( 'metis-settings', 'wp_metis_import_csv_tom_error_file_mime_type' );
			}

			$path_parts = pathinfo( sanitize_file_name( $_FILES['wp_metis_csv_upload']['name'] ) );
			if ( ! in_array( strtolower( $path_parts['extension'] ), $this->allowed_file_extensions ) ) {
				// File extension is NOT allowed. Redirect.
				Services::redirect_to_vgw_metis_page( 'metis-settings', 'wp_metis_import_csv_tom_error_file_extension' );
			}
		} else {
			// No uploaded file. Redirect.
			Services::redirect_to_vgw_metis_page( 'metis-settings', 'wp_metis_import_csv_tom_error_no_file' );
		}

		// so ... we have a valid action with valid import type and an uploaded file with valid extension
		// and a valid mime type and a valid redirect page ... all good
		// more import methods to come ...
		switch ( $import_type ) {
			case 'from_tom':
				$this->handle_import_from_tom( $_FILES['wp_metis_csv_upload']['tmp_name'] );
				break;
		}
	}

	/**
	 * Imports pixels from a tom exported csv file into the plugins db
	 *
	 * @param string $tmp_filename filename given by the check file/upload handler
	 *
	 * @return void
	 */
	private function handle_import_from_tom( string $tmp_filename ): void {
		$handle = fopen( $tmp_filename, 'r' );
		if ( $handle && $first_line = fgetcsv( $handle, 210, ';' ) ) {
			// Check if the header columns are correct, else redirect
			if ( $first_line[0] != self::TOM_PUBLIC_COLUMN_NAME || $first_line[1] != self::TOM_PRIVATE_COLUMN_NAME || count( $first_line ) != 2 ) {
				Services::redirect_to_vgw_metis_page( 'metis-settings', 'wp_metis_import_csv_tom_error_wrong_csv_format' );
			}

			// loop through csv data, line per line and add all pixels with the correct format
			$invalid_pixel_count = 0;
			$pixels              = [];
			while ( ( $line_data = fgetcsv( $handle, 210, ';' ) ) !== false ) {
				if ( Common::is_valid_pixel_id_format( sanitize_key( $line_data[0] ) ) ) {
					// gather public uids
					$pixels[] = sanitize_key( $line_data[0] );
				} else {
					$invalid_pixel_count ++;
				}
			}
			fclose( $handle );

			$validated_pixels = [];

			// if we have valid formatted pixels, check ownership
			if ( count( $pixels ) > 0 ) {
				$checked_pixels = Services::check_pixel_state( $pixels );

				if ( $checked_pixels === false || ! count( $checked_pixels ) ) {
					Services::redirect_to_vgw_metis_page( 'metis-settings', 'wp_metis_import_csv_tom_error_check_ownership_api_error' );
				}

				foreach ( $checked_pixels as $pixel ) {
					if ( $pixel->state != Common::API_STATE_VALID ) {
						$invalid_pixel_count ++;
					} else {
						$validated_pixels[] = new Pixel( $pixel );
					}
				}
			}

			$double_pixel_count   = 0;
			$imported_pixel_count = 0;

			// insert valid owned pixels to db
			if ( count( $validated_pixels ) > 0 ) {
				$import_result = DB_Pixels::upsert_pixels_from_csv( $validated_pixels );

				if ( $import_result->success !== false ) {
					$imported_pixel_count = $import_result->affected_rows;
					$double_pixel_count   = count( $validated_pixels ) - $imported_pixel_count;
				} else {
					Services::redirect_to_vgw_metis_page( 'metis-settings', 'wp_metis_import_csv_tom_error_db_error' );
				}
			} else {
				// no valid pixels :(
				Services::redirect_to_vgw_metis_page( 'metis-settings',
					'wp_metis_import_csv_tom_error_no_valid_pixels',
					urlencode( sprintf(
							esc_html__( '(%s Zählmarken waren fehlerhaft oder nicht im eigenen Besitz und wurden nicht importiert)', 'vgw-metis' ),
							$invalid_pixel_count )
					)
                );
			}

			// yippieh, we did it! redirect and show notification
			Services::redirect_to_vgw_metis_page( 'metis-settings',
				'wp_metis_import_csv_tom_success',
				urlencode( sprintf(
						esc_html__( ' Es wurden %s Zählmarken importiert.', 'vgw-metis' ) . esc_html__( ' (%s Zählmarken waren bereits vorhanden und wurden nicht importiert, %s Zählmarken waren fehlerhaft oder nicht im eigenen Besitz und wurden nicht importiert)', 'vgw-metis' ),
						$imported_pixel_count, $double_pixel_count, $invalid_pixel_count )
				)
            );
		}
	}

	/**
	 * Add the action to enable imports and add some csv related notices
	 *
	 * @return void
	 */
	public function activate_csv(): void {
		add_action( 'admin_post_wp_metis_import_csv', [ $this, 'handle_csv_file_upload' ] );
		$this->activated = true;
	}

	/**
	 * render the file upload form element for importing csv from T.O.M.
	 *
	 * @return void
	 */
	// ToDo Check if CSV Upload uses WP Nonce
	public function render_tom_csv_import_file_input(): void {
		?>
        <input type='file' id='wp-metis-field-csv-upload' name='wp_metis_csv_upload' accept=".csv"/>
        <input type="hidden" id="wp-metis-field-action" name="action" value="wp_metis_import_csv"/>
        <input type="hidden" id="wp-metis-import-type" name="import_type" value="from_tom"/>
		<?php
	}

	/**
	 * check if a file has really been uploaded
     *
     * comes in handy with unit tests
	 *
	 * @param string $file file to check (from files array)
	 *
	 * @return bool success or failure
	 */
	public function is_uploaded_file_check(
		string $file
	): bool {
		return is_uploaded_file( $file );
	}

	/**
     * register the csv import related notices
     *
	 * @param Notifications $notifications
	 *
	 * @return void
	 */
    public static function register_notifications(Notifications &$notifications): void {
	    $notifications->add_notice_by_key( 'wp_metis_import_csv_tom_success', esc_html__( 'CSV-Import aus T.O.M. wurde erfolgreich durchgeführt!', 'vgw-metis' ), 'success' );
	    $notifications->add_notice_by_key( 'wp_metis_import_csv_tom_error_file_extension', esc_html__( 'CSV-Import aus T.O.M. fehlgeschlagen, da der Dateityp nicht unterstützt wird. Bitte laden Sie eine aus T.O.M. exportierte CSV-Datei hoch!', 'vgw-metis' ) );
	    $notifications->add_notice_by_key( 'wp_metis_import_csv_tom_error_file_mime_type', esc_html__( 'CSV Import aus T.O.M. fehlgeschlagen. Datei-MIME-Typ nicht korrekt.', 'vgw-metis' ) );
	    $notifications->add_notice_by_key( 'wp_metis_import_csv_tom_error_no_redirect_page', esc_html__( 'CSV Import aus T.O.M. fehlgeschlagen. Invalide Weiterleitung.', 'vgw-metis' ) );
	    $notifications->add_notice_by_key( 'wp_metis_import_csv_tom_error_call_not_allowed', esc_html__( 'CSV Import aus T.O.M. fehlgeschlagen. Fehlerhafter Aufruf.', 'vgw-metis' ) );
	    $notifications->add_notice_by_key( 'wp_metis_import_csv_tom_error_no_file', esc_html__( 'CSV Import aus T.O.M. fehlgeschlagen. Keine CSV Datei hochgeladen.', 'vgw-metis' ) );
	    $notifications->add_notice_by_key( 'wp_metis_import_csv_tom_error_no_import_type', esc_html__( 'CSV Import aus T.O.M. fehlgeschlagen. Keine Import - Typ angegeben.', 'vgw-metis' ) );
	    $notifications->add_notice_by_key( 'wp_metis_import_csv_tom_error_wrong_csv_format', esc_html__( 'CSV-Import aus T.O.M. fehlgeschlagen, da das CSV-Format nicht den Anforderungen entspricht oder der Inhalt abgeändert wurde. Bitte überprüfen Sie die CSV-Datei oder führen Sie in T.O.M. einen neuen Export der Zählmarken durch.', 'vgw-metis' ) );
	    $notifications->add_notice_by_key( 'wp_metis_import_csv_tom_error_no_valid_pixels', esc_html__( 'CSV-Import aus T.O.M. fehlgeschlagen, da keine validen Zählmarken vorhanden waren.', 'vgw-metis' ) );
	    $notifications->add_notice_by_key( 'wp_metis_import_csv_tom_error_check_ownership_api_error', esc_html__( 'CSV Import aus T.O.M. fehlgeschlagen. API Aufruf zur Besitzprüfung fehlerhaft.', 'vgw-metis' ) );
	    $notifications->add_notice_by_key( 'wp_metis_import_csv_tom_error_db_error', esc_html__( 'CSV Import aus T.O.M. fehlgeschlagen. Fehler beim Schreiben in die Datenbank.', 'vgw-metis' ) );
    }

}
