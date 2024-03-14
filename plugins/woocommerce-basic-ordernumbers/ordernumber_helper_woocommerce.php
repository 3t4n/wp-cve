<?php
/**
 * Advanced Ordernumbers generic helper class for WooCommerce
 * Reinhold Kainhofer, Open Tools, office@open-tools.net
 * @copyright (C) 2012-2015 - Reinhold Kainhofer
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

if ( !defined( 'ABSPATH' ) ) { 
	die( 'Direct Access to ' . basename( __FILE__ ) . ' is not allowed.' );
}
if (!class_exists( 'OrdernumberHelper' )) 
	require_once (dirname(__FILE__) . '/library/ordernumber_helper.php');

/**
 * Returns the timezone string for a site, even if it's set to a UTC offset
 *
 * Adapted from http://www.php.net/manual/en/function.timezone-name-from-abbr.php#89155
 *
 * @return string valid PHP timezone string
 */
function wp_get_timezone_string() {
    // if site timezone string exists, return it
    if ( $timezone = get_option( 'timezone_string' ) )
        return $timezone;

    // get UTC offset, if it isn't set then return UTC
    if ( 0 === ( $utc_offset = get_option( 'gmt_offset', 0 ) ) )
        return 'UTC';

    // adjust UTC offset from hours to seconds
    $utc_offset *= 3600;

    // attempt to guess the timezone string from the UTC offset
    if ( $timezone = timezone_name_from_abbr( '', $utc_offset, 0 ) ) {
        return $timezone;
    }

    // last try, guess timezone string manually
    $is_dst = date( 'I' );

    foreach ( timezone_abbreviations_list() as $abbr ) {
        foreach ( $abbr as $city ) {
            if ( $city['dst'] == $is_dst && $city['offset'] == $utc_offset )
                return $city['timezone_id'];
        }
    }

    // fallback to UTC
    return 'UTC';
}

class OrdernumberHelperWooCommerce extends OrdernumberHelper {
	public static $ordernumber_counter_prefix = '_ordernumber_counter_';

	function __construct() {
		parent::__construct();
		load_plugin_textdomain('opentools-ordernumbers', false, basename( dirname( __FILE__ ) ) . '/languages' );
		// WC-specific Defaults for the HTML tables
		$this->_styles['counter-table-class']  = "widefat";
		$this->_styles['variable-table-class'] = "widefat wc_input_table sortable";
		
	}

	static function getHelper() {
		static $helper = null;
		if (!$helper) {
			$helper = new OrdernumberHelperWooCommerce();
		}
		return $helper;
    }

	public function getDateTime($utime) {
		$time = new DateTime();
		$time->setTimestamp($utime);
		$time->setTimezone(new DateTimeZone(wp_get_timezone_string()));
		return $time;
	}

	
	/**
	 * HELPER FUNCTIONS, WooCommerce-specific
	 */
	public function __($string) {
		$string = $this->readableString($string);
		return __($string, 'opentools-advanced-ordernumbers');
	}
	function urlPath($type, $file) {
		return plugins_url('library/' . $type . '/' . $file, __FILE__);
    }
    
    public function print_admin_styles() {
		wp_register_style('ordernumber-styles',  $this->urlPath('css', 'ordernumber.css'));
		wp_enqueue_style('ordernumber-styles');
	}
	
	public function print_admin_scripts() {
		wp_register_script( 'ordernumber-script', $this->urlPath('js', 'ordernumber.js',  __FILE__), array('jquery') );
		wp_enqueue_script( 'ordernumber-script');
		
		// Handle the translations:
		// Check for MS dashboard
		if( is_network_admin() )
			$url = network_admin_url( 'admin-ajax.php' );
		else
			$url = admin_url( 'admin-ajax.php' );
		$localizations = array( 'ajax_url' => $url );
		
		$localizations['ORDERNUMBER_JS_JSONERROR'] = $this->__("Error reading response from server:");
		$localizations['ORDERNUMBER_JS_NOT_AUTHORIZED'] = $this->__("You are not authorized to modify order number counters.");
		$localizations['ORDERNUMBER_JS_NEWCOUNTER'] = $this->__("Please enter the format/name of the new counter:");
		$localizations['ORDERNUMBER_JS_ADD_FAILED'] = $this->__("Failed adding counter {0}");
		$localizations['ORDERNUMBER_JS_INVALID_COUNTERVALUE'] = $this->__("You entered an invalid value for the counter.\n\n");
		
		$localizations['ORDERNUMBER_JS_EDITCOUNTER'] = $this->__("{0}Please enter the new value for the counter '{1}' (current value: {2}):");
		$localizations['ORDERNUMBER_JS_MODIFY_FAILED'] = $this->__("Failed modifying counter {0}");
		$localizations['ORDERNUMBER_JS_DELETECOUNTER'] = $this->__("Really delete counter '{0}' with value '{1}'?");
		$localizations['ORDERNUMBER_JS_DELETE_FAILED'] = $this->__("Failed deleting counter {0}");

		// in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
		wp_localize_script( 'ordernumber-script', 'ajax_ordernumber', $localizations );
	}



	protected function get_all_options($prefix) {
		global $wpdb;
 
		$suppress = $wpdb->suppress_errors();
		$alloptions_db = $wpdb->get_results( "SELECT option_name, option_value FROM $wpdb->options WHERE `option_name` LIKE '$prefix%'" );
		$wpdb->suppress_errors($suppress);
		$alloptions = array();
		foreach ( (array) $alloptions_db as $o ) {
			$alloptions[$o->option_name] = $o->option_value;
		}
		return $alloptions;
	}

 	function getAllCounters($type) {
		$counters = array();
		$pfxlen = strlen(self::$ordernumber_counter_prefix );
		// BUG: wp_load_alloptions does NOT load non-autoload options.
		// However, we switched all counters to non-autoload, so they will not appear any more!
		// so we need to use our own function that directly accesses the database
		foreach ($this->get_all_options(self::$ordernumber_counter_prefix) as $name => $value) {
			if (substr($name, 0, $pfxlen) == self::$ordernumber_counter_prefix) {
				$parts = explode('-', substr($name, $pfxlen), 2);
				if (sizeof($parts)==1) {
					array_unshift($parts, 'ordernumber');
				}
				if ($parts[0]==$type) {
					$counters[] = array(
						'type'  => $parts[0],
						'name'  => $parts[1],
						'value' => $value,
					);
				}
			}
		}
		return $counters;
	}

    function getCounter($type, $format, $default=0) {
		// the option is cached, so two orders at approximately the same time 
		// (one submitted while the other one is still processed) will get the
		// same counter value from the cach, unless we explicitly erase the 
		// cache and force WP to look into the database for the current value
		wp_cache_delete ('alloptions', 'options');
		wp_cache_delete (self::$ordernumber_counter_prefix.$type.'-'.$format, 'options');
		return get_option (self::$ordernumber_counter_prefix.$type.'-'.$format, $default);
	}
    
	function addCounter($type, $format, $value) {
		return $this->setCounter($type, $format, $value);
	}

	function setCounter($type, $format, $value) {
		return update_option(self::$ordernumber_counter_prefix.$type.'-'.$format, $value, /*autoload=*/false);
	}

	function deleteCounter($type, $format) {
		return delete_option(self::$ordernumber_counter_prefix.$type.'-'.$format);
	}


}
