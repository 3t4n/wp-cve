<?php
if( !defined('ABSPATH') ) exit;
include_once(ABSPATH.'wp-admin/includes/plugin.php');

require_once('vendor/autoload.php');
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;
use PredictHQ\AddressFormatter\Formatter as AddressFormatter;
use Tamtamchik\NameCase\Formatter as NameFormatter;
use Coduo\PHPHumanizer\DateTimeHumanizer;

if( !class_exists('Stonehenge_Functions')) :
Class Stonehenge_Functions extends Stonehenge_Core {

	var $errors;

	#===============================================
	public function check_dependency($plugin) {
		$errors = array();
		if( method_exists($plugin['class'], 'dependency') ) {
			foreach( $plugin['class']::dependency() as $key => $name ) {
				if( !is_plugin_active($key) ) {
					$errors[] = sprintf( __('%s requires <strong>%s</strong> to be installed & activated.', $plugin['text']), $plugin['short'], $name );
				}
			}
		}
		$this->errors = $errors;
		return empty($errors) ? true : false;
	}


	#===============================================
	public function show_dependency($plugin) {
		if( !empty($this->errors) ) {
			foreach( $this->errors as $error ) {
				echo sprintf('<p><span class="stonehenge-error">%s</span</p>', $error);
			}
		}
		return;
	}


	#===============================================
	public function do_cleanup( $plugin ) {
		wp_clear_scheduled_hook('puc_cron_updater-'. $plugin['base']);
		wp_clear_scheduled_hook('stonehenge_creations_licenses');
	}


	#===============================================
	public function has_email_pro() {
		return function_exists('em_email_pro') ? true : false;
	}


	#===============================================
	public function set_nonce_life_span() {
		return 1800; 	// 30 minutes should be more than enough.
	}


	#===============================================
	public function slugify($string) {
		// Convert to lowercase and remove whitespace
		$string = strtolower( trim($string) );

		$string = str_replace(' - ', '-', $string);

		// Replace high ascii characters
		$chars = array( "ä", "ö", "ü", "ß" );
		$replacements = array( "ae", "oe", "ue", "ss" );
		$string = str_replace( $chars, $replacements, $string );
		$pattern = array( "/(é|è|ë|ê)/", "/(ó|ò|ö|ô)/", "/(ú|ù|ü|û)/" );
		$replacements = array( "e", "o", "u" );
		$string = preg_replace( $pattern, $replacements, $string );

		// Remove puncuation
		$pattern = array( ":", "!", "?", ".", "/", "'", "_", "%", "#" );
		$string = str_replace( $pattern, "", $string );

		// Hyphenate any non alphanumeric characters
		$pattern = array( "/[^a-z0-9-]/", "/-+/" );
		$string = preg_replace( $pattern, "-", $string );
		return $string;
	}


	#===============================================
	public function mask($input) {
		$masked =  str_repeat("*", strlen($input)-6) . substr($input, -6);
		return $masked;
	}


	#===============================================
	public function minify_js($input) {
	    if(trim($input) === "") return $input;
	    return preg_replace( array('#\s*("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')\s*|\s*\/\*(?!\!|@cc_on)(?>[\s\S]*?\*\/)\s*|\s*(?<![\:\=])\/\/.*(?=[\n\r]|$)|^\s*|\s*$#', '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/)|\/(?!\/)[^\n\r]*?\/(?=[\s.,;]|[gimuy]|$))|\s*([!%&*\(\)\-=+\[\]\{\}|;:,.<>?\/])\s*#s', '#;+\}#', '#([\{,])([\'])(\d+|[a-z_][a-z0-9_]*)\2(?=\:)#i', '#([a-z0-9_\)\]])\[([\'"])([a-z_][a-z0-9_]*)\2\]#i'), array( '$1', '$1$2', '}', '$1$3', '$1.$3'), $input);
	}


	#===============================================
	public function minify_css($input) {
		$output = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $input);
		$output = str_replace(': ', ':', $output);
		$output = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $output);
		$css 	= '<style>'. $output .'</style>';
		return $css;
	}


	#===============================================
	public function css_inline($html, $css) {
		$inline 	= new CssToInlineStyles();
		$result 	= $inline->convert($html, $css);
		return $result;
	}


	#===============================================
	public function locate_template( $template_name, $plugin, $template_path = '', $default_path = '' ) {
		$plugin = is_array($plugin) ? $plugin['base'] : $plugin;

	    if( !$template_path ) 	{ $template_path = 'stonehenge/'; }
	    if( !$default_path ) 	{ $default_path = WP_PLUGIN_DIR . "/{$plugin}/templates/"; }

	    $template = locate_template( array( $template_path . $template_name, $template_name ) );

	    if( !$template ) 		{ $template = $default_path . $template_name; }
	    return apply_filters('stonehenge_locate_template', $template, $plugin, $template_name, $template_path, $default_path);
	}


	#===============================================
	public function get_template( $template_name, $plugin, $args = array(), $tempate_path = '', $default_path = '' ) {
		$plugin = is_array($plugin) ? $plugin['base'] : $plugin;

		if( is_array($args) && isset($args) ) {
			extract($args);
		}

		$template_file = $this->locate_template( $template_name, $plugin, $tempate_path, $default_path );

		if( !file_exists($template_file) ) {
			_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $template_file ), '1.0.0' );
			return;
		}

		include $template_file;
	}


	#===============================================
	public function show_edit_template( $file_name ) {
		$plugin 	= $this->plugin;
		$text 		= $plugin['text'];
		$base 		= $plugin['base'];
		$theme_dir 	= get_stylesheet();

		return sprintf( __('To edit this template, first copy the original file, before making adjustments. <br>%s', $text),
			"<pre>From: 	<code>/wp-content/plugins/{$base}/templates/{$file_name}</code>
To: 	<code>/wp-content/themes/{$theme_dir}/stonehenge/{$file_name}</code></pre>");
	}


	#===============================================
	public function show_notice($notice, $class) {
		$notice  = wp_kses_allowed( $notice );
		return( '<p><span class="stonehenge-'.$class.'">'. $notice .'</span></p>' );
	}


	#===============================================
	public function show_admin_notice( $message, $class ) {
		if( current_user_can('manage_options') ) {
//			echo sprintf('<div class="notice notice-%1$s"><p>%2$s</p></div>', esc_html($class), $message);
		}
		return;
	}


	#===============================================
	public function show_settings_notice( $plugin, $class, $new = false ) {
		$short  = $plugin['short'];
		$text 	= $plugin['text'];
		$url 	= $plugin['url'];
		$new 	= !$new ? null : __('New options have been added.', $text);
		$check 	= sprintf(
			_x('Please check your <a href=%1$s>%2$s</a> and click on "%3$s".', 'Please check your settings and click on "Save Changes".', $text),
				$url, __wp('Settings'), __wp('Save Changes')
		);

		$message = wp_kses_allowed( sprintf('<strong>%1$s:</strong> %2$s %3$s', $short, $new, $check) );

		// Show as Admin Notice.
		$this->show_admin_notice( $message, $class );
	}


	#===============================================
	public function show_filter( $filter, $parameters = array() ) {
		$text = $this->plugin['text'];
		return wp_sprintf( __('You can use the filter <code>%s</code> with these variables: <code>%l</code>.', $text), $filter, $parameters );
	}


	#===============================================
	public function show_action_hook( $hook, $parameters = array() ) {
		$text = $this->plugin['text'];
		return wp_sprintf( __('You can hook into this by using the <code>%s</code> action hook and these variables: <code>%l</code>.', $text), $hook, $parameters );
	}


	#===============================================
	public function info_wp_cron_jobs() {
		$text = $this->plugin['text'];
		$info = wp_kses_allowed( sprintf('<div class="note"><strong class="red">%s</strong><ul><li>%s</li><li>%s</li><li>%s</li><li>%s</li></ul></div>', esc_html__('Important', $text), esc_html__('Enabling this section will schedule a WP Cron Job to run daily at the given time.', $text),
esc_html__('If you set this option to "No" or deactivate this plugin, the WP Cron Job will be cancelled immediately.', $text),
esc_html__('Reactivating this plugin will reschedule the WP Cron Job (or not) according to the last saved settings.', $text),
esc_html__('If you have a low-traffic website, it is best to not rely on the default WP Cron Jobs.', $text) .' '. sprintf( ('<a href=%s target="_blank">'. __('Tom McFarlin wrote a great read on how to solve that.', $text) .'</a>'), 'https://tommcfarlin.com/wordpress-cron-jobs/') ) );
		return $info;
	}


//------------------------------------------------------
// 	Front-End Localization.
//------------------------------------------------------

	#===============================================
	public function get_browser_locale() {
		$langs = array();
		if( isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ) {
			preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang_parse);

			if( count($lang_parse[1]) ) {
				$langs = array_combine($lang_parse[1], $lang_parse[4]);
				foreach( $langs as $lang => $val ) {
					if( $val === '' ) {
						$langs[$lang] = 1;
					}
				}
				arsort($langs, SORT_NUMERIC);
			}
		}
		reset($langs);
		$lang = key($langs);
		if( stristr($lang, '-') ) {
			list($lang) = explode('-', $lang);
		}
		return $lang;
	}


	#===============================================
	public function get_user_ip() {
		if( isset($_SERVER["HTTP_CF_CONNECTING_IP"]) ) {
			$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
			$_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
		}
		$client  = @$_SERVER['HTTP_CLIENT_IP'];
		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote  = $_SERVER['REMOTE_ADDR'];

		if( filter_var($client, FILTER_VALIDATE_IP) ) {
			$ip = $client;
		}
		elseif( filter_var($forward, FILTER_VALIDATE_IP) ) {
			$ip = $forward;
		}
		else {
			$ip = $remote;
		}
		return $ip;
	}


	#===============================================
	public function get_user_country_code( $ip = false ) {
		$ip = !$ip ? $this->get_user_ip() : $ip;
		if( $ip != '::1') {		// Ignore localhost.
			$response 	= wp_remote_get( 'https://www.iplocate.io/api/lookup/'.$ip);
			$result 	= json_decode( $response['body'] );
			return $result->country_code;
		}
		return;
	}


//------------------------------------------------------
// 	User Localization.
//------------------------------------------------------

	#===============================================
	public function get_countries() {
		include('arrays/countries.php');

		if( extension_loaded('intl') ) {
			$locale = get_locale();
				foreach( $countries as $code => $name ) {
				$name = Locale::getDisplayRegion('-' . $code, $locale);
				$countries[$code] = $name;
			}
		}
		return $countries;
	}


	#===============================================
	public function localize_address( $input, $country = false, $linebreak = false ) {
		$address = array(
			'country_code' 	=> $input['country'],
			'city' 			=> $input['city'],
			'road' 			=> $input['address'],
			'postcode' 		=> $input['postcode'],
			'state' 		=> $input['state'],
		);

		$formatter 		= new AddressFormatter();
		$localized 		= $formatter->formatArray( $address );

		if($country) {
			$countries 	= $this->get_countries();
			$localized	.= $countries[ $input['country'] ];
		}

		if($linebreak) {
			return nl2br($localized, false);
		}
		return $localized;
	}


	#===============================================
	public function localize_em_location( $EM_Location, $country = false, $linebreak = false ) {
		$address = array(
			'country'	=> $EM_Location->location_country,
			'city' 		=> $EM_Location->location_town,
			'address'	=> $EM_Location->location_address,
			'postcode'	=> $EM_Location->location_postcode,
			'state' 	=> $EM_Location->location_state
		);

		$localized	= $EM_Location->location_name .'<br>';
		$localized 	.= $this->localize_address($address, $country, $linebreak);
		return $localized;
	}


	#===============================================
	public function localize_name( $input ) {
		if( is_string($input) ) {
			$formatter = new NameFormatter( array(
				'lazy'        => false,
				'irish'       => true,
				'spanish'     => false,
				'roman'       => true,
				'hebrew'      => true,
				'postnominal' => true,
			) );
			return $formatter->nameCase( strtoupper($input) );
		}
		return $input;
	}


//------------------------------------------------------
// 	Financial Localization.
//------------------------------------------------------

	#===============================================
	public function get_currencies() {
		include('arrays/currencies.php');
		asort($currencies);

		return $currencies;
	}


	#===============================================
	public function get_currency_symbol( $currency = false ) {
		$currency	= !$currency ? get_option('dbem_bookings_currency') : $currency;

		if( extension_loaded('intl') ) {
			$locale 	= get_locale();
			$formatter 	= new NumberFormatter($locale, NumberFormatter::CURRENCY);

			$formatter->setPattern('¤');
			$formatter->setAttribute(NumberFormatter::MAX_SIGNIFICANT_DIGITS, 0);
			$formattedPrice = $formatter->formatCurrency(0, $currency);
			$currencySymbol = str_replace(0, '', $formattedPrice);
			return $currencySymbol;
		}
		return;
	}


	#===============================================
	public function localize_price( $price, $currency = false ) {
		$currency	= !$currency ? get_option('dbem_bookings_currency') : $currency;
		$locale 	= get_locale();
		$formatter 	= new NumberFormatter( $locale .'@currency=' . $currency, NumberFormatter::CURRENCY );
		$formatted	= $formatter->formatCurrency( (float) $price, $currency );
		return $formatted;
	}


	#===============================================
	public function format_raw_price( $price ) {
		return number_format_i18n( $price, 2 );
	}


	#===============================================
	public function float_price( $price ) {
	    $price 	= str_replace( ",", ".", $price );
	    $price 	= preg_replace( '/\.(?=.*\.)/', '', $price );
	    $price 	= floatval( $price );
	    return $price;
	}

	#===============================================
	public function price_excl_tax( $price, $tax_rate ) {
		// Remove included taxes from price. Retuns unformatted float with 5 decimals.
		$price 		= $this->float_price( $price );
		$tax_rate 	= (float) $tax_rate / 100;
		$tax_calc	= $tax_rate + 1;
		$result 	= number_format( ($price / $tax_calc), 5);
		return $result;
	}


	#===============================================
	public function price_incl_tax( $price, $tax_rate ) {
		// Add taxes to price. Retuns unformatted float with 5 decimals.
		$price 		= $this->float_price( $price );
		$tax_rate 	= (float) $tax_rate / 100;
		$tax_calc	= $tax_rate + 1;
		$result 	= number_format( ($price * $tax_calc), 5);
		return $result;
	}


	#===============================================
	public function price_added_tax( $included, $tax_rate ) {
		$price 		= $this->float_price( $included );
		$tax_rate 	= (float) $tax_rate / 100;
		$tax_calc	= $tax_rate + 1;
		$excluded 	= $price / $tax_calc;
		$tax_added 	= number_format( ($included - $excluded ), 5);
		return $tax_added;
	}





//------------------------------------------------------
// 	Date Functions.
//------------------------------------------------------

	#===============================================
	public function get_date_format() {
		$date_format = get_option('date_format');
		$date_format = apply_filters('stonehenge_date_format', $date_format);
		return $date_format;
	}


	#===============================================
	public function format_date( $date, $format = false ) {
		$date = is_numeric($date) ? $date : strtotime($date);

		if( $format === 'tech' ) {
			return date('Y-m-d', $date);
		}

		$format = !$format ? $this->get_date_format() : $format;
		return date($format, $date);
	}


	#===============================================
	public function locale_to_date_php() {
		include('arrays/locale-to-php.php');
		$locale 		= get_locale();
		$date_format 	= $array[$locale];
		$date_format 	= apply_filters('stonehenge_locale_to_date_php', $date_format);
		return array_key_exists($locale, $array) ? $date_format : get_option('date_format');
	}


	#===============================================
	public function locale_to_date_js() {
		include('arrays/locale-to-js.php');
		$locale 		= get_locale();
		$date_format 	= $array[$locale];
		$date_format 	= apply_filters('stonehenge_locale_to_date_js', $date_format);
		return $date_format;
	}


	#===============================================
	public function php_date_to_js() {
		include('arrays/php-to-js.php');
		$php = get_option('date_format');
		$php = apply_filters('stonehenge_php_date_to_js', $php);
		return array_key_exists($php, $array) ? $array[$php] : 'yy-mm-dd';
	}


	#===============================================
	public function localize_date( $date, $length = 'medium' ) {
		if( !is_numeric($date) ) {
			$date = strtotime($date);
		}

		if( extension_loaded('intl') ) {
			switch( $length ) {
				case 'tech':	return date('Y-m-d', $date); 			break;
				case 'full': 	$length = IntlDateFormatter::FULL; 		break;
				case 'long': 	$length = IntlDateFormatter::LONG;		break;
				case 'short':	$length = IntlDateFormatter::SHORT; 	break;
				case 'medium': 	$length = IntlDateFormatter::MEDIUM; 	break;
				default:		$length = IntlDateFormatter::SHORT; 	break;
			}
			$fmt = datefmt_create(
			    get_locale(),
			    $length,
			    IntlDateFormatter::NONE,
			    wp_timezone_string(),
			    IntlDateFormatter::GREGORIAN
			);
			return datefmt_format($fmt, $date);
		}

		$date_format = $this->get_date_format();
		return date($date_format, $date);
	}


	#===============================================
	public function localize_date_difference( $end, $precise = false ) {
		$today 		= current_time('mysql');
		$end 		= !is_numeric($end) ? strtotime($end) : $end;
		$end_date 	= date('Y-m-d H:i:s', $end);
		$locale 	= get_locale();

		if( $precise ) {
			return DateTimeHumanizer::preciseDifference(new \DateTime($today), new \DateTime($end_date), $locale);
		}
		return DateTimeHumanizer::difference(new \DateTime($today), new \DateTime($end_date), $locale);
	}


	#===============================================
	public function localize_datepicker( $type = false, $capitalize = false, $upper = false ) {
		$calendar = array(
			'months_full' 	=> array( __wp('January'), __wp('February'), __wp('March'), __wp('April'), __wp('May'), __wp('June'), __wp('July'), __wp('August'), __wp('September'), __wp('October'), __wp('November'), __wp('December') ),
			'months_short' 	=> array( __wp('Jan'), __wp('Feb'), __wp('Mar'), __wp('Apr'), __wp('May'), __wp('Jun'), __wp('Jul'), __wp('Aug'), __wp('Sep'), __wp('Oct'), __wp('Nov'), __wp('Dec') ),
			'weekdays_full'	=> array( __wp('Sunday'), __wp('Monday'), __wp('Tuesday'), __wp('Wednesday'), __wp('Thursday'), __wp('Friday'), __wp('Saturday') ),
			'weekdays_short' => array( __wp('Sun'), __wp('Mon'), __wp('Tue'), __wp('Wed'), __wp('Thu'), __wp('Fri'), __wp('Sat') ),
			'options' => array( __wp('Today'), __wp('Clear'), __wp('Close'), __wp('Next'), __wp('Previous') ),
			'other' => array( __wp('Day'), __wp('Week'), __wp('Month'), __wp('Year') ),
		);

		$result = $type ? $calendar[$type] : $calendar;

		if( $capitalize ) $result = array_map_recursive('ucfirst', $result);
		if( $upper ) $result = array_map_recursive('strtoupper', $result);
		return $result;
	}


//------------------------------------------------------
// 	Time Functions.
//------------------------------------------------------

	#===============================================
	public function get_current_time() {
		return current_time('timestamp');
	}


	#===============================================
	public function get_time_format() {
		$time_format = get_option('time_format');
		$time_format = apply_filters('stonehenge_time_format', $time_format);
		return $time_format;
	}


	#===============================================
	public function localize_time( $time ) {
		$time 		= !is_numeric($time) ? strtotime($time) : $time;
		$format 	= $this->get_time_format();
		$result 	= date( $format, $time );
		return $result;
	}


	#===============================================
	public function utc_to_local($input, $format = false) {
		$format		= !$format ? 'Y-m-d H:i:s' : $format;
		$input 		= is_numeric($input) ? $input : strtotime($input);
		$timezone 	= new DateTimeZone( wp_timezone_string() );
		$UTC 		= new DateTimeZone( 'UTC' );
		$Date		= new DateTime( date($format, $input), $UTC );
		$Date->setTimezone( $timezone );

		return $Date->format( $format );
	}


	#===============================================
	public function local_to_utc($input, $format = false) {
		$format		= !$format ? 'Y-m-d H:i:s' : $format;
		$input 		= is_numeric($input) ? $input : strtotime($input);
		$timezone 	= new DateTimeZone( wp_timezone_string() );
		$UTC 		= new DateTimeZone( 'UTC' );
		$Date		= new DateTime( date($format, $input), $timezone );
		$Date->setTimezone( $UTC );

		return $Date->format( $format );
	}


	#===============================================
	public function local_to_iso( $local ) {
		$utc 		= $this->local_to_utc( $local );
		$timezone 	= new DateTimeZone( wp_timezone_string() );
		$result 	= (new DateTime($utc, $timezone))->format('c');
		return $result;
	}


//------------------------------------------------------
// 	Events Manager Specific Functions.
//------------------------------------------------------

	#===============================================
	public function get_em_booking_status( $key = false ) {
		if( is_plugin_active('events-manager/events-manager.php') ) {
			$EM_Booking = new EM_Booking();
			if( is_object($EM_Booking) ) {
				$status = $EM_Booking->status_array;
				if( $key ) {
					return array_key_exists($key, $status) ? $status[$key] : 'Unknown';
				}
				else {
					return $status;
				}
			}
		}
		return false;
	}


} // End class.
endif;


#===============================================
if( !function_exists('__wp') ) {
	function __wp( $string ) {
		return __($string);
	}
}

#===============================================
if( !function_exists('__em') ) {
	function __em( $string ) {
		return __($string, 'events-manager');
	}
}

#===============================================
if( !function_exists('__emp') ) {
	function __emp( $string ) {
		return __($string, 'empro');
	}
}


#===============================================
# 	Like array_map, but can be used for recursive arrays.
#===============================================
if( !function_exists('array_map_recursive') ) {
	function array_map_recursive($callback, $array) {
		$func = function ($item) use (&$func, &$callback) {
			return is_array($item) ? array_map($func, $item) : call_user_func($callback, $item);
		};
		return array_map($func, $array);
	}
}


#===============================================
# 	Like wp_parse_args, but can be used for recursive arrays.
#===============================================
if( !function_exists('wp_parse_args_recursive') ) {
	function wp_parse_args_recursive( &$a, $b ) {
		$a = (array) $a;
		$b = (array) $b;
		$result = $b;
		foreach ( $a as $k => &$v ) {
			if ( is_array( $v ) && isset( $result[$k] ) ) {
				$result[$k] = wp_parse_args_recursive( $v, $result[$k] );
			} else {
				$result[$k] = $v;
			}
		}
		return $result;
	}
}


#===============================================
# 	Like wp_kses, but without the need to call $allowedposttags.
#===============================================
if( !function_exists('wp_kses_allowed') ) {
	function wp_kses_allowed( $context ) {
		global $allowedposttags;
		return wp_kses( $context, $allowedposttags, wp_allowed_protocols() );
	}
}


#===============================================
# 	Like wp_kses, but only allows <p>, <br>, <b>, <i> & <u>.
#===============================================
if( !function_exists('wp_kses_some') ) {
	function wp_kses_some( $context ) {
		$allowed = array( 'p' => [], 'br' => [], 'b' => [], 'i' => [], 'u' => [] );
		return wp_kses( $context, $allowed, array() );
	}
}


#===============================================
# 	Easy-to-use filter to make the wp_editor() required.
#	Usage: add_filter('the_editor', 'wp_editor_required', 10, 1);
#===============================================
if( !function_exists('wp_editor_required') ) {
	function wp_editor_required( $editor ) {
    	$editor = str_replace( '<textarea', '<textarea required="required"', $editor );
		return $editor;
	}
}


#===============================================
# 	Easy-to-use filter to make the wp_dropdown_pages() required.
#	Usage: add_filter('wp_dropdown_pages', 'wp_dropdown_pages_required', 10, 1);
#===============================================
if( !function_exists('wp_dropdown_pages_required') ) {
	function wp_dropdown_pages_required( $output ) {
    	$output = str_replace( '<select', '<select required="required"', $output );
		return $output;
	}
}



#===============================================
# 	Get URL args on the next page.
#===============================================
if( !function_exists('get_args_from_referer') ) {
	function get_args_from_referer( $parameter ) {
		if( isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], $parameter) !== false) {
			$url 	= parse_url($_SERVER['HTTP_REFERER']);
			$query	= explode('&', $url['query']);
			$args 	= array();
			foreach( $query as $q ) {
				$strpos = strpos($q, '=');
				$key 	= substr($q, 0, $strpos);
				$value	= substr($q, ($strpos +1) );
				$args[$key] = $value;
			}
			return $args;
		}
		return false;
	}
}


#===============================================
# 	Convert an Object to an Array.
#===============================================
if( !function_exists('convert_object_to_array') ) {
	function convert_object_to_array( $object ) {
		if( !is_object($object) ) {
			return $object;
		}

		$array = array();
		foreach( $object as $key => $value ) {
			$array[$key] = is_object($value) ? convert_object_to_array($value) : $value;
		}
		return $array;
	}
}


#===============================================
# 	Sort recursive array by single array key.
#===============================================
if( !function_exists('array_sort_by_key') ) {
	function array_sort_by_key( $needle, $haystack ) {
		usort( $haystack, function($a,$b) use($needle) {
			return strcmp( $a[$needle], $b[$needle] );
		});
		return $haystack;
	}
}


#===============================================
#	Get single array by value from multidirectional array.
#===============================================
if( !function_exists('array_get_by_value') ) {
	function array_get_by_value( $needle, $haystack ) {
		if( is_array($haystack) ) {
			foreach( $haystack as $haybale => $straw ) {
				if( $straw === $needle ) {
					return $haystack;
				}
				if( is_array($straw) ) {
					foreach( $straw as $helve => $gemma ) {
						if( $gemma === $needle ) {
							return $straw;
						}
						if( is_array($gemma) ) {
							return array_get_by_value( $straw, $needle );
						}
					}
				}
			}
		}
		return false;
	}
}


#===============================================
# 	Convert multidirectional arrays to a single array.
#===============================================
if( !function_exists('array_flatten') ) {
	function array_flatten($array) {
		if( !is_array($array) ) {
			return FALSE;
		}
		$result = array();
		foreach( $array as $key => $value ) {
			if( is_array($value) ) {
				$result = array_merge($result, array_flatten($value));
			}
			else {
				$result[$key] = $value;
			}
		}
		return $result;
	}
}


#===============================================
# 	Replace content between two tags.
#===============================================
if( !function_exists('replace_between') ) {
	function replace_between( $string, $needle_start, $needle_end, $replacement ) {
	    $pos 	= strpos( $string, $needle_start );
	    $start 	= $pos === false ? 0 : $pos + strlen( $needle_start );
	    $pos 	= strpos( $string, $needle_end, $start );
	    $end 	= $start === false ? strlen( $string ) : $pos;
	    return substr_replace( $string ,$replacement, $start, $end - $start );
	}
}

#===============================================
# 	Check if toggle is set to yes.
#===============================================
if( !function_exists('is_yes') ) {
	function is_yes( $parameter ) {
		$result = isset($parameter) && $parameter != 'no' ? true : false;
		return $result;
	}
}

#===============================================
# 	Check if a post meta key has value.
#===============================================
if( !function_exists('has_value') ) {
	function has_value( $meta_key, $post_id ) {
		$meta_value = get_post_meta($post_id, $meta_key, true);
		return isset($meta_value) && !empty($meta_value) ? $meta_value : false;
	}
}


#===============================================
# 	Check if the current page is in the backend.
#===============================================
if( !function_exists('is_backend') ) {
	function is_backend() {
		return is_blog_admin() ? true : false;
	}
}


#===============================================
# 	Check if the given string is a url.
#===============================================
if( !function_exists('is_url') ) {
	function is_url( $string ) {
		$result = filter_var($string, FILTER_VALIDATE_URL) === false ? false : $string;
		return $result;
	}
}


#===============================================
# 	Clean up email addresses. (PHP arrays are case sensitive.)
#===============================================
if( !function_exists('clean_email') ) {
	function clean_email( $string ) {
		$string = html_entity_decode( trim( strtolower( sanitize_email( $string ) ) ) );
		return $string;
	}
}


#===============================================
# 	Get version number based on the current week.
#===============================================
if( !function_exists('get_version_by_date') ) {
	function get_version_by_date() {
		$today  	= new DateTime( date('Y-m-d') );
		$version 	= date('Y') .'.'. $today->format("W");
		return $version;
	}
}

#===============================================
# 	EM Placeholder for Booking ID.
#===============================================
if( !function_exists('stonehenge_em_placeholder_booking_id') ) {
	function stonehenge_em_placeholder_booking_id( $replacement, $EM_Booking, $placeholder ) {
		if( $placeholder === "#_BOOKINGID") {
			$replacement = $EM_Booking->booking_id;
		}
		return $replacement;
	}
	add_filter('em_booking_output_placeholder', 'stonehenge_em_placeholder_booking_id', 11, 3);
}

