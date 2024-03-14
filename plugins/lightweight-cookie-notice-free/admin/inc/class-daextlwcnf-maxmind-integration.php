<?php

/*
 * Handles the integration with the databases provided by MaxMind.
 */

class Daextlwcnf_MaxMind_Integration {

	//PROPERTIES -------------------------------------------------------------------------------------------------------

	//METHODS ----------------------------------------------------------------------------------------------------------
	/**
	 * Daextlwcnf_MaxMind_Integration constructor.
	 *
	 * @param $shared The shared class.
	 * @param bool $cron Set true if the class is instantiated by a cron task, otherwise set false.
	 */
	public function __construct( $shared, $cron = false ) {

		//Assign an instance of the plugin info
		$this->shared = $shared;

		//Property used to detect if the class has been instantiate by a cron task
		$this->cron = $cron;

	}

	/**
	 * Updates the GeoLite2 database.
	 *
	 */
	public function update_maxmind_geolite2() {

		//Proceed only if there are the necessary data provided through the plugin option
		if ( ! $this->validate_options() ) {
			return;
		}

		//Create the .htaccess file to prevent access to the plugin upload directory
		$this->create_htaccess();

		//Initializes and connects the WordPress Filesystem Abstraction classes
		require_once ABSPATH . 'wp-admin/includes/file.php';
		WP_Filesystem();
		global $wp_filesystem;

		//Remove the existing archive
		$target_database_path = get_option( $this->shared->get( 'slug' ) . '_maxmind_database_file_path' );
		if ( $wp_filesystem->exists( $target_database_path ) ) {
			$wp_filesystem->delete( $target_database_path );
		}

		//Download the MaxMind Geolite2 database
		$tmp_database_path = $this->download_maxmind_geolite2();

		//Move the downloaded database in the correct position
		$wp_filesystem->move( $tmp_database_path, $target_database_path, true );
		$wp_filesystem->delete( dirname( $tmp_database_path ) );

	}

	/**
	 * Downloads the GeoLite2 database.
	 *
	 * @return string|WP_Error
	 */
	public function download_maxmind_geolite2() {

		//The name of the MaxMind database to download.
		$database = 'GeoLite2-Country';

		//The extension of the MaxMind database
		$database_extension = '.mmdb';
		$license_key        = urlencode( get_option( $this->shared->get( 'slug' ) . '_maxmind_license_key' ) );
		$download_uri       = 'https://download.maxmind.com/app/geoip_download?edition_id=GeoLite2-Country&license_key=' . $license_key . '&suffix=tar.gz';

		//Required for the download_url() function
		require_once ABSPATH . 'wp-admin/includes/file.php';

		$tmp_archive_path = download_url( esc_url_raw( $download_uri ) );
		if ( is_wp_error( $tmp_archive_path ) ) {
			$error_data = $tmp_archive_path->get_error_data();
			if ( isset( $error_data['code'] ) ) {
				switch ( $error_data['code'] ) {
					case 401:
						return new WP_Error(
							'daextlwcnf_maxmind_geolite2_invalid_license_key',
							__( 'Invalid license key.', 'daextlwcnf' )
						);
				}
			}

			return new WP_Error( 'daextlwcnf_maxmind_geolite2_generic_error',
				__( 'An error occurred during the download of the MaxMind GeoLite2 database.', 'daextlwcnf' ) );
		}

		//Extract the downloaded archive
		try {
			$file              = new PharData( $tmp_archive_path );
			$tmp_database_path = trailingslashit( dirname( $tmp_archive_path ) ) . trailingslashit( $file->current()->getFilename() ) . $database . $database_extension;
			$file->extractTo(
				dirname( $tmp_archive_path ),
				trailingslashit( $file->current()->getFilename() ) . $database . $database_extension,
				true
			);
		} catch ( Exception $exception ) {
			return new WP_Error( 'daextlwcnf_maxmind_geolite2_extract_archive', $exception->getMessage() );
		} finally {
			//Delete the temporary archive
			unlink( $tmp_archive_path );
		}

		return $tmp_database_path;

	}


	/**
	 * Create the .htaccess file to prevent access to the plugin upload directory.
	 */
	public function create_htaccess() {

		$files = array(
			array(
				'base'    => WP_CONTENT_DIR . '/uploads/daextlwcnf_uploads/',
				'file'    => '.htaccess',
				'content' => 'deny from all',
			),
		);

		foreach ( $files as $file ) {
			if ( wp_mkdir_p( $file['base'] ) && ! file_exists( trailingslashit( $file['base'] ) . $file['file'] ) ) {
				$file_handle = @fopen( trailingslashit( $file['base'] ) . $file['file'], 'wb' );
				if ( $file_handle ) {
					fwrite( $file_handle, $file['content'] );
					fclose( $file_handle );
				}
			}
		}

	}

	/**
	 * Validate the options associated with the use of the MaxMind GeoLite2 database. The return value of this method
	 * is used to determine if the procedure used to download the MaxMind GeoLite2 database should be executed.
	 *
	 * @return bool
	 */
	public function validate_options() {

		$enable_geolocation         = intval( get_option( $this->shared->get( 'slug' ) . '_enable_geolocation' ), 10 );
		$geolocation_service        = intval( get_option( $this->shared->get( 'slug' ) . '_geolocation_service' ), 10 );
		$maxmind_license_key        = get_option( $this->shared->get( 'slug' ) . '_maxmind_license_key' );
		$maxmind_database_file_path = get_option( $this->shared->get( 'slug' ) . '_maxmind_database_file_path' );

		if ( $enable_geolocation === 1 and
		     $geolocation_service === 1 and
		     strlen( $maxmind_license_key ) > 0 and
		     strlen( $maxmind_database_file_path ) > 0 and
		     ( $this->cron or ! $this->database_exists( $maxmind_database_file_path ) ) ) {

			return true;

		} else {

			return false;

		}

	}

	/**
	 * Verifies if the MaxMind database at the specified path exists.
	 *
	 * @param $maxmind_database_file_path
	 *
	 * @return bool
	 */
	public function database_exists( $maxmind_database_file_path ) {

		if ( file_exists( $maxmind_database_file_path ) ) {
			return true;
		} else {
			return false;
		}

	}

}