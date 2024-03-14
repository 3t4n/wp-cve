<?php

/*
 * This class should be used to include ajax actions.
 */

class Daextlwcnf_Ajax {

	protected static $instance = null;
	private $shared = null;

	private function __construct() {

		//assign an instance of the plugin info
		$this->shared = Daextlwcnf_Shared::get_instance();

		//ajax requests for logged-in and not logged-in users
		add_action( 'wp_ajax_daextlwcnf_geolocate_user', array( $this, 'daextlwcnf_geolocate_user' ) );
		add_action( 'wp_ajax_nopriv_daextlwcnf_geolocate_user', array( $this, 'daextlwcnf_geolocate_user' ) );

	}

	/*
	 * Return an istance of this class.
	 */
	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;

	}

	/**
	 * Geolocate the user by using a database stored in the web server of the website.
	 *
	 * The following geolocation databases are currently supported:
	 *
	 * - MaxMind GeoLite2
	 */
	public function daextlwcnf_geolocate_user() {

		//Check the referer
		if ( ! check_ajax_referer( 'daextlwcnf', 'security', false ) ) {
			echo "Invalid AJAX Request";
			die();
		}

		$ip_address = $this->shared->get_ip_address();

		switch ( intval( get_option( $this->shared->get( 'slug' ) . '_geolocation_service' ), 10 ) ) {

			//MaxMind GeoLite2
			case 1:

				if ( $this->shared->is_valid_locale_maxmind_geolite2( $ip_address ) ) {
					$result = true;
				} else {
					$result = false;
				}

				break;

			//Default
			default:
				$result = true;
				break;

		}

		//Generate the json output
		echo $result ? '1' : '0';

		die();

	}

}