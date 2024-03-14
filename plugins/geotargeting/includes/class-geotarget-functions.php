<?php
/**
 * Functions for geotargeting
 *
 * @link       https://timersys.com/geotargeting/
 * @since      1.0.0
 *
 * @package    GeoTarget
 * @subpackage GeoTarget/includes
 * @author     Your Name <email@example.com>
 */
use GeoIp2\Database\Reader;
use Jaybizzle\CrawlerDetect\CrawlerDetect;

class GeoTarget_Functions {
	/**
	 * Class to detect bots
	 * @var CrawlerDetect
	 */
	public $CrawlerDetect;

	/**
	 * Current user country used everywhere
	 * @var string
	 */
	protected  $userCountry;

	/**
	 * All data calculated for user
	 * @var array
	 */
	protected $calculated_data;
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct( ) {

			add_action('init' , array($this,'setUserCountry' ) );
			$this->CrawlerDetect = new CrawlerDetect;
	}

	function setUserCountry() {
		if( !is_admin()
		    && ! defined('DOING_CRON')
		    && ! defined('DOING_AJAX') )
			$this->userCountry = apply_filters('geot/user_country', $this->calculateUserCountry());
	}

	/**
	 * Main function that return is user target the given countries / regions or not
	 * @param  string $country         
	 * @param  string $region          
	 * @param  string $exclude_country 
	 * @param  string $exclude_region  
	 * @return bool      
	 */
	public function target( $country = '', $region = '', $exclude_country = '', $exclude_region  = '')
	{

		//Push country list into array
		$country 			= $this->toArray( $country );
		
		$exclude_country 	= $this->toArray( $exclude_country );
				
		$saved_regions 		= apply_filters('geot/get_regions', array());

		//Append any regions
		if ( !empty( $region ) && ! empty( $saved_regions ) ) {
			
			$region = $this->toArray( $region );	
				
			foreach ($region as $region_name) {
				
				foreach ($saved_regions as $key => $saved_region) {
				
					if ( strtolower( $region_name ) == strtolower( $saved_region['name'] ) ) {
					
						$country = array_merge( (array)$country, (array)$saved_region['countries']);
					
					}
				}
			}

		}	
		// append exlcluded regions to excluded countries		
		if (!empty( $exclude_region ) && ! empty( $saved_regions ) ) {

			$exclude_region = $this->toArray( $exclude_region );
			
			foreach ($exclude_region as $region_name ) {

				foreach ($saved_regions as $key => $saved_region) {
				
					if ( strtolower( $region_name ) == strtolower( $saved_region['name'] ) ) {

						$exclude_country = array_merge((array)$exclude_country, (array)$saved_region['countries']);

					}
				}	
			}
		}	
			
		//set target to false	
		$target = false;
			
		$user_country = $this->get_user_country();
		
		if ( count( $country ) > 0 ) {

			foreach ( $country as $c ) {

				if ( strtolower( $user_country->name ) == strtolower( $c )|| strtolower( $user_country->isoCode ) == strtolower( $c ) ) {
					$target = true;
				}

			}
		} else {
			// If we don't have countries to target return true
			$target = true;

		}
		
		if ( count( $exclude_country ) > 0 ) {

			foreach ( $exclude_country as $c ) {

				if ( strtolower( $user_country->name ) == strtolower( $c ) || strtolower( $user_country->isoCode ) == strtolower( $c ) ) {
					$target = false;
				}

			}
		}	
		

		return $target;
	}

	/**
	 * Check if "user" is a search engine
	 *
	 * @param string $user_agent
	 *
	 * @return bool
	 */
	public function isSearchEngine( $user_agent = null ) {
		// Check the user agent of the current 'visitor'
		if( $this->CrawlerDetect->isCrawler( $user_agent ) )
			return true;

		return false;
	}

	/**
	 * Helper function to conver to array
	 * @param  string $value comma separated countries, etc
	 * @return array  
	 */
	private function toArray( $value = "" )
	{
		if ( empty( $value ) )
			return array();

		if ( is_array( $value ) )
			return array_map('trim', $value );

		if ( stripos($value, ',') > 0)
			return array_map( 'trim', explode( ',', $value ) );

		return array( trim( $value ) );
	}


	/**
	 * Retrieve the current User country
	 * @return array Country array object
	 */
	public function get_user_country()
	{
		if( empty( $this->userCountry ) ) {
			$this->userCountry = $this->calculateUserCountry();
		}
		return $this->userCountry;
	}

	/**
	 * Get user Country
	 * @return array     country array
	 */
	public function calculateUserCountry() {
		
		global $wpdb;

		$opts = apply_filters('geot/settings_page/opts', get_option( 'geot_settings' ) );
		$bots_country = apply_filters('geot/bots_default_country', 'US' );
		if( !empty( $bots_country ) && $this->isSearchEngine() ) {

			$country = $this->getCountryByIsoCode( $bots_country );

			return $country;
			// If user set cookie use instead
		} elseif( ! defined('GEOT_DEBUG')  && empty( $_GET['geot_debug'] ) &&  ! empty( $_COOKIE['geot_country']) ) {

			$iso_code = ! empty( $_COOKIE['geot_country'] ) ?  $_COOKIE['geot_country'] : apply_filters('geot/fallback_iso_code', 'US');

			$country = $this->getCountryByIsoCode( $iso_code );

			return $country;
		}


		$country = $this->getCountryByIp();

		return $country;

	}

	/**
	 * Get Country by ip
	 * @param  string $ip 
	 * @return array     country array
	 */
	public function getCountryByIp( $ip = "" ) {

		// if we already calculated it on execution return
		if( !empty ( $this->calculated_data ) )
			return $this->calculated_data;

		if( empty( $ip) ) {
			$ip = $this->getUserIP();
		}
		if( !empty( $_GET['geot_debug'] ) )
			return $this->getCountryByIsoCode($_GET['geot_debug']);

		try {
			$file = plugin_dir_path( dirname( __FILE__ ) ) . 'includes/data/GeoLite2-Country.mmdb';
			$path_upload = wp_upload_dir();
			$geot_maxmind = apply_filters( 'geotmax/path_local', $path_upload['basedir'] . '/geot_plugin/GeoLite2-Country.mmdb' );
			if( file_exists( $geot_maxmind ) ) {
				$file = $geot_maxmind;
			}
			$reader = new Reader($file);
			$country= $reader->country($ip)->country;
		} catch ( Exception $e ) {
			return $this->getCountryByIsoCode( apply_filters('geot/fallback_iso_code', 'US') );
		}

		return $country;

	}

	/**
	 * Get country from database and return object like maxmind
	 * @param $iso_code
	 *
	 * @return StdClass
	 */
	private function getCountryByIsoCode( $iso_code ) {
		global $wpdb;
		$query 	 = "SELECT * FROM {$wpdb->base_prefix}geot_countries WHERE iso_code = %s";
		$result = $wpdb->get_row( $wpdb->prepare($query, array( $iso_code )), ARRAY_A );
		$country = new StdClass;

		$country->name      = $result['country'];
		$country->isoCode   = $result['iso_code'];

		return $country;
	}

	/**
	 * We get user IP but check with different services to see if they provided real user ip
	 * @return mixed|void
	 */
	public function getUserIP() {
		$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '1.1.1.1';
		// cloudflare
		$ip = isset( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $ip;
		// reblaze
		$ip = isset( $_SERVER['X-Real-IP'] ) ? $_SERVER['X-Real-IP'] : $ip;
		// Sucuri
		$ip = isset( $_SERVER['HTTP_X_SUCURI_CLIENTIP'] ) ? $_SERVER['HTTP_X_SUCURI_CLIENTIP'] : $ip;
		// Ezoic
		$ip = isset( $_SERVER['X-FORWARDED-FOR'] ) ? $_SERVER['X-FORWARDED-FOR'] : $ip;
		// akamai
		$ip = isset( $_SERVER['True-Client-IP'] ) ? $_SERVER['True-Client-IP'] : $ip;
		// Clouways
		$ip = isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $ip;
		// varnish trash ?
		$ip = str_replace( array( '::ffff:', ', 127.0.0.1'), '', $ip );
		// get varnish first ip
		$ip = strstr( $ip, ',') === false ? $ip : strstr( $ip, ',',1);
		return apply_filters( 'geot/user_ip', $ip );
	}

}	
