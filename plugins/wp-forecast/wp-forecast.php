<?php
/**
 * Plugin Name: wp-forecast
 * Plugin URI: http://www.tuxlog.de
 * Description: wp-forecast is a highly customizable plugin for WordPress, showing weather-data from Open-Meteo or OpenWeathermMap.
 * Version: 9.3
 * Author: Hans Matzen
 * Author URI: http://www.tuxlog.de
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path: lang
 * Text Domain: wp-forecast
 *
 * @package wp-forecast
 */

/**
 * Copyright 2006-2023  Hans Matzen
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */


//
// only use this in case of severe problems accessing the admin dialog
//
// preselected transport method for fetching the weather data
// valid values are
// curl      - uses libcurl
// fsockopen - uses fsockopen
// streams   - uses fopen with streams
// exthttp   - uses pecl http extension
// this will override every setting from the admin dialog
// you have to assure that the chosen transport is supported by the
// WordPress class WP_Http;
// .
static $wp_forecast_pre_transport = '';

//
// maximal number of widgets to use.
//
$wpf_maxwidgets = 8;

/*
 ---------- no parameters to change after this point --------------------
 */
// define path to wp-forecast plugin.
define( 'WPF_PATH', plugin_dir_path( __FILE__ ) );

// OpenWeathermap functions.
require_once 'func-openweathermap.php';
// Open-Meteo functions.
require_once 'func-openmeteo.php';

// OpenUV data functions.
require_once 'func-openuv.php';
// ipstack functions.
require_once 'func-ipstack.php';

// generic functions.
require_once 'funclib.php';
// include setup functions.
require_once 'wpf-setup.php';
// include admin options page.
require_once 'wp-forecast-admin.php';
// display functions.
require_once 'wp-forecast-show.php';
// shortcodes.
require_once 'shortcodes.php';
// support for WordPress autoupdate.
require_once 'wpf-autoupdate.php';
// virtual page class.
require_once 'class-wpf-virtualpage.php';


global $blog_id;

/**
 * Set cache with weather data for current parameters
 * a wrapper function called via the init hook.
 */
function wp_forecast_init() {
	// first of all check if we have to set a hard given
	// transport method.
	if ( isset( $wp_forecast_pre_transport ) && wpf_get_option( 'wp-forecast-pre-transport' ) != $wp_forecast_pre_transport ) {
			wpf_update_option( 'wp-forecast-pre-transport', $wp_forecast_pre_transport );
	}

	$count = (int) wpf_get_option( 'wp-forecast-count' );

	$weather = array();

	for ( $i = 0;$i < $count;$i++ ) {
		$wpfcid = get_widget_id( $i );

		$wpf_vars = get_wpf_opts( $wpfcid );

		// use visitors location for weather and UV.
		if ( isset( $wpf_vars['visitorlocation'] ) && $wpf_vars['visitorlocation'] ) {
			// get users ip address.
			$vip = ipstack_get_visitor_ip();

			// get location information about ip address.
			$vloc = ipstack_get_data( wpf_get_option( 'wp-forecast-ipstackapikey' ), $vip );

			// find matching locations from open-meteo.
			// search locations for openmeteo.
			$openmeteo_loc = get_loclist( $wpf_vars['OPENMETEO_LOC_URI'], ( ! isset( $vloc['city'] ) ? '' : $vloc['city'] ) );

			// guess the locations which might be correct matching the country.
			$locations = array();

			// prefilter list of found locations to reduce runtime.
			foreach ( $openmeteo_loc as $al ) {
				if ( strpos( $al['country'], $vloc['country_name'] ) !== false ) {
					$locations[] = $al;
				}
			}

			if ( count( $locations ) == 1 ) {
				// if there is only one location in the array take this one.
				$wpf_vars['location']     = $locations[0]['name'];
				$wpf_vars['locname']      = $locations[0]['name'];
				$wpf_vars['loclatitude']  = $locations[0]['latitude'];
				$wpf_vars['loclongitude'] = $locations[0]['longitude'];
			} else {
				// get the weather data of all the weather locations in the array and find the best match on lat and lon.
				$ldist = 99000.0;
				$lat1  = ( ! isset( $vloc['latitude'] ) ? '' : $vloc['latitude'] );
				$lon1  = ( ! isset( $vloc['longitude'] ) ? '' : $vloc['longitude'] );
				$tloc  = '';
				$tname = '';
				foreach ( $locations as $ll ) {
					$lat2  = $ll['latitude'];
					$lon2  = $ll['longitude'];
					$cdist = distanceCalculation( $lat1, $lon1, $lat2, $lon2 );

					if ( $cdist < $ldist ) {
						$ldist = $cdist;
						$tloc  = $ll['name'];
						$tname = $ll['name'];
						$tlat  = $ll['latitude'];
						$tlon  = $ll['longitude'];
					}
				}

				if ( '' != $tloc ) {
					$wpf_vars['locname']      = $tname;
					$wpf_vars['location']     = $tloc;
					$wpf_vars['loclatitude']  = $tlan;
					$wpf_vars['loclongitude'] = $tlon;
				} else {
					// nothing found set default values.
					$wpf_vars['location']     = 'Berlin Alexanderplatz';
					$wpf_vars['locname']      = 'Berlin Alexanderplatz';
					$wpf_vars['loclatitude']  = '52.521918';
					$wpf_vars['loclongitude'] = '13.413215';
				}
			}

			// make sure weather data is updated.
			$wpf_vars['expire'] = 0;

			// update wpf_vars in case we changed lat and lon.
			wpf_update_option( 'wp-forecast-opts' . $wpfcid, serialize( $wpf_vars ) );
		}

		// check if we have to fetch the weather data or if we can use the cache.
		if ( $wpf_vars['expire'] < time() ) {
			switch ( $wpf_vars['service'] ) {
				case 'openweathermap':
					$w       = openweathermap_get_weather( $wpf_vars['OPENWEATHERMAP_BASE_URI'], $wpf_vars['apikey1'], $wpf_vars['loclatitude'], $wpf_vars['loclongitude'], $wpf_vars['metric'] );
					$weather = openweathermap_get_data( $w, $wpf_vars );
					break;

				case 'openweathermap3':
					$w       = openweathermap_get_weather( $wpf_vars['OPENWEATHERMAP_BASE_URI_V3'], $wpf_vars['apikey1'], $wpf_vars['loclatitude'], $wpf_vars['loclongitude'], $wpf_vars['metric'] );
					$weather = openweathermap_get_data( $w, $wpf_vars );
					break;
				case 'openmeteo':
					$w       = openmeteo_get_weather( $wpf_vars['OPENMETEO_BASE_URI'], $wpf_vars['loclatitude'], $wpf_vars['loclongitude'], $wpf_vars['metric'] );
					$weather = openmeteo_get_data( $w, $wpf_vars );
					break;
			}

			// get OpenUV data if wanted.
			if ( isset( $wpf_vars['ouv_apikey'] ) && trim( $wpf_vars['ouv_apikey'] ) != '' ) {
				$ouv = openuv_get_data( $wpf_vars['ouv_apikey'], $weather['lat'], $weather['lon'] );
				if ( is_wp_error( $ouv ) ) {
					$weather['openuv']  = '';
					$weather['failure'] = $ouv->get_error_message();
				} else {
					$weather['openuv'] = $ouv;
				}
			}

			// store weather to database and set expire time.
			// if the current data wasnt available use old data.
			if ( count( $weather ) > 0 ) {
				wpf_update_option( 'wp-forecast-cache' . $wpfcid, serialize( $weather ) );
				if ( empty( $weather['failure'] ) || '' == $weather['failure'] ) {
					wpf_update_option( 'wp-forecast-expire' . $wpfcid, time() + $wpf_vars['refresh'] );
				} else {
					wpf_update_option( 'wp-forecast-expire' . $wpfcid, 0 );
				}
			}
		}
	}

	// javascript hinzufügen fuer ajax widget.
	if ( ! is_admin() && get_option( 'wpf_sem_ajaxload' ) > 0 ) {
		wp_enqueue_script( 'wpf_update', plugins_url( 'wpf_update.js', __FILE__ ), array( 'jquery' ), '9999' );
	}
	// javascript hinzufügen für suche im admin dialog.
	if ( is_admin() ) {
		wp_enqueue_script( 'wp-forecast-search', plugins_url( 'wp-forecast-admin.js', __FILE__ ), array( 'jquery' ), '9999' );
	}

	// add css attribute display to the list of safe css styles for pulldown widget.
	add_filter(
		'safe_style_css',
		function( $styles ) {
			$styles[] = 'display';
			return $styles;
		}
	);
}


/**
 * This function is called from your template
 * to insert your weather data at the place you want it to be
 * support to select language on a per call basis from Robert Lang.
 *
 * @param array  $args Default parameters.
 * @param string $wpfcid The Widget ID.
 * @param string $language_override ISO Code of the language.
 */
function wp_forecast_widget( $args = array(), $wpfcid = 'A', $language_override = null ) {
	if ( '?' == $wpfcid ) {
		$wpf_vars = get_wpf_opts( 'A' );
	} else {
		$wpf_vars = get_wpf_opts( $wpfcid );
	}

	if ( ! empty( $language_override ) ) {
		$wpf_vars['wpf_language'] = $language_override;
	}

	if ( '?' == $wpfcid ) {
		$weather = maybe_unserialize( wpf_get_option( 'wp-forecast-cacheA' ) );
	} else {
		$weather = maybe_unserialize( wpf_get_option( 'wp-forecast-cache' . $wpfcid ) );
	}

	show( $wpfcid, $args, $wpf_vars );
}

/**
 * This is the wrapper function for displaying from sidebar.php
 * and not as a widget. since the parameters are different we need this.
 *
 * @param string $wpfcid The Widget ID.
 * @param string $language_override ISO Code of the language.
 */
function wp_forecast( $wpfcid = 'A', $language_override = null ) {
	wp_forecast_widget( array(), $wpfcid, $language_override );
}

/**
 * A function to show a range of widgets at once.
 *
 * @param int    $from Lowest widget number to display.
 * @param int    $to Highest widget number to display.
 * @param int    $numpercol Number of widgets per column.
 * @param string $language_override ISO Code of the language.
 */
function wp_forecast_range( $from = 0, $to = 0, $numpercol = 1, $language_override = null ) {
	global $wpf_maxwidgets;
	$wcount = 1;

	// check min and max limit.
	if ( $from < 0 ) {
		$from = 0;
	}

	if ( $to > $wpf_maxwidgets ) {
		$to = $wpf_maxwidgets;
	}

	// output table header.
	echo '<table><tr>';

	// out put widgets in a table.
	for ( $i = $from;$i <= $to;$i++ ) {

		if ( 1 == $wcount % $numpercol ) {
			echo '<tr>';
		}

		echo '<td>';
		wp_forecast( get_widget_id( $i ), $language_override );
		echo '</td>';

		if ( ( 0 == $wcount % $numpercol ) && ( $i < $to ) ) {
			echo '</tr>';
		}

		++$wcount;
	}

	// output table footer.
	echo '</tr></table>';
}

/**
 * A function to show a set of widgets at once.
 *
 * @param array  $wset Array with numbers of widgets to display.
 * @param int    $numpercol Number of widgets per column.
 * @param string $language_override ISO Code of the language.
 */
function wp_forecast_set( $wset, $numpercol = 1, $language_override = null ) {
	global $wpf_maxwidgets;
	$wcount   = 1;
	$wset_max = count( $wset ) - 1;

	// output table header.
	echo '<table><tr>';

	// out put widgets in a table.
	for ( $i = 0;$i <= $wset_max;$i++ ) {

		if ( 1 == $wcount % $numpercol ) {
			echo '<tr>';
		}

		echo '<td>';
		wp_forecast( $wset[ $i ], $language_override );
		echo '</td>';

		if ( ( 0 == $wcount % $numpercol ) && ( $i < $wset_max ) ) {
			echo '</tr>';
		}

		++$wcount;
	}

	// output table footer.
	echo '</tr></table>';
}

/**
 * Returns the widget data as an array.
 *
 * @param string $wpfcid The Widget ID.
 * @param string $language_override ISO Code of the language.
 */
function wp_forecast_data( $wpfcid = 'A', $language_override = null ) {
	$wpf_vars = get_wpf_opts( $wpfcid );

	if ( ! empty( $language_override ) ) {
		$wpf_vars['wpf_language'] = $language_override;
	}

	$w = maybe_unserialize( wpf_get_option( 'wp-forecast-cache' . $wpfcid ) );

	$weather_arr = array();

	// read service dependent weather data.
	switch ( $wpf_vars['service'] ) {
		case 'openweathermap':
		case 'openweathermap3':
			$weather_arr = openweathermap_forecast_data( $wpfcid, $language_override );
			break;
		case 'openmeteo':
			$weather_arr = openmeteo_forecast_data( $wpfcid, $language_override );
			break;
	}

	// add openuv data to weather_arr.
	if ( is_array( $w ) && array_key_exists( 'openuv', $w ) ) {
		$weather_arr['openuv'] = $w['openuv'];
	} else {
		$weather_arr['openuv'] = array();
	}

	return $weather_arr;
}


/**
 * Register the wp-forecast widget
 */
function reg_wpf_widget() {
	return register_widget( 'wpf_widget' );
}
/**
 * Register the UV Widget
 */
function reg_wpf_uv_widget() {
	return register_widget( 'WpfUvWidget' );
}

/**
 * Init the wp-forecast widgets.
 */
function widget_wp_forecast_init() {
	global $wp_version,$wpf_maxwidgets, $wpf_lang_dict;

	// include widget class.
	require_once 'class-wpf-widget.php';
	require_once 'class-wpfuvwidget.php';

	$count = (int) wpf_get_option( 'wp-forecast-count' );

	// check for widget support.
	if ( ! function_exists( 'register_sidebar_widget' ) ) {
		return;
	}

	// add fetch weather data to init the cache before any headers are sent.
	add_action( 'init', 'wp_forecast_init' );
	add_action( 'admin_init', 'wp_forecast_admin_init' );

	// add css.
	add_action( 'wp_enqueue_scripts', 'wp_forecast_css' );

	for ( $i = 0;$i <= $wpf_maxwidgets;$i++ ) {
		$wpfcid = get_widget_id( $i );

		// register our widget and add a control.
		// translators: %s is for the Widget ID.
		$name = sprintf( __( 'wp-forecast %s' ), $wpfcid );
		$id   = "wp-forecast-$wpfcid";

		// translators: %s is for the widget ID.
		$uvname = sprintf( __( 'wp-forecast-uv %s' ), $wpfcid );
		$uvid   = "wp-forecast-uv-$wpfcid";

		// add widget.
		add_action( 'widgets_init', 'reg_wpf_widget' );
		add_action( 'widgets_init', 'reg_wpf_uv_widget' );

		wp_unregister_sidebar_widget( $i >= $count ? 'wp_forecast_widget' . $wpfcid : '' );

		wp_register_widget_control(
			$id,
			$name,
			$i < $count ? 'wpf_admin_hint' : '',
			array(
				'width'  => 300,
				'height' => 150,
			)
		);

		wp_unregister_widget_control( $i >= $count ? 'wpf_admin_hint' . $wpfcid : '' );
	}

	// add filters for transport method check.
	add_filter( 'use_fsockopen_transport', 'wpf_check_fsockopen' );
	add_filter( 'use_fopen_transport', 'wpf_check_fopen' );
	add_filter( 'use_streams_transport', 'wpf_check_streams' );
	add_filter( 'use_http_extension_transport', 'wpf_check_exthttp' );
	add_filter( 'use_curl_transport', 'wpf_check_curl' );
}

/**
 * Filters the REQUEST_URI for virtual page slug
 * to show weather in an iframe.
 */
function wpf_filter_url() {
	$url = ( isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '' );
	$url = trim( parse_url( $url, PHP_URL_PATH ), '/' );

	// determine if it is a wpf virtual page call.
	if ( strpos( $url, 'wp-forecast-direct' ) !== false ) {
		$wpfcid            = ( isset( $_GET['wpfcid'] ) ? sanitize_text_field( wp_unslash( $_GET['wpfcid'] ) ) : 'A' );
		$language_override = ( isset( $_GET['language_override'] ) ? sanitize_text_field( wp_unslash( $_GET['language_override'] ) ) : '' );
		$header            = ( isset( $_GET['header'] ) ? sanitize_text_field( wp_unslash( $_GET['header'] ) ) : '' );
		$selector          = ( isset( $_GET['selector'] ) ? sanitize_text_field( wp_unslash( $_GET['selector'] ) ) : '' );

		if ( '1' == $selector ) {
			$selector = '?';
		}
		$args     = array();
		$wpf_vars = get_wpf_opts( $wpfcid );

		if ( ! empty( $language_override ) ) {
			$wpf_vars['wpf_language'] = $language_override;
		}

		$weather = maybe_unserialize( wpf_get_option( 'wp-forecast-cache' . $wpfcid ) );

		// only show weather html withour page or header.
		if ( ! $header || 0 == $header ) {
			show( $selector . $wpfcid, $args, $wpf_vars );
			exit;
		}

		if ( 1 == $header ) {
			echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">' . "\n";
			echo '<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="' . esc_attr( str_replace( '_', '-', $wpf_vars['wpf_language'] ) ) . '">' . "\n";
			echo "<head><title>wp-forecast iframe</title>\n";
			echo '<meta http-equiv="content-type" content="text/html; charset=utf-8" />' . "\n";
			echo '<style>' . wp_kses( wp_forecast_get_nowp_css(), wpf_allowed_tags() ) . '</style>' . "\n";
			echo "</head>\n<body>\n";

			show( $selector . $wpfcid, $args, $wpf_vars );

			echo "</body></html>\n";
			exit;
		}

		if ( 2 == $header ) {
			// create weather html.
			// add css.
			add_action( 'wp_enqueue_scripts', 'wp_forecast_css_nowp' );
			ob_start();
			show( $selector . $wpfcid, $args, $wpf_vars );
			$out = ob_get_contents();
			ob_end_clean();

			// create virtual page and display it.
			$args = array(
				'slug'    => 'wp-forecast-direct',
				'title'   => 'wp-forecast weather',
				'content' => $out,
			);
			$pg4  = new Wpf_VirtualPage( $args );
		}
	}
}


// MAIN.

// activating deactivating the plugin.
register_activation_hook( __FILE__, 'wp_forecast_activate' );
register_deactivation_hook( __FILE__, 'wp_forecast_deactivate' );

// add option page.
add_action( 'admin_menu', 'wp_forecast_admin' );

// Run our code later in case this loads prior to any required plugins.
add_action( 'plugins_loaded', 'widget_wp_forecast_init' );

// add virtual page filter.
add_action( 'init', 'wpf_filter_url' );
