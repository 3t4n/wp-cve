<?php
/** This file is part of the wp-forecast plugin for WordPress
 *
 * Copyright 2006-2023  Hans Matzen  (email : webmaster at tuxlog dot de)
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
 *
 * @package wp-forecast
 */

/** Location array and counter init. */
$loc = array();
$i   = 0;

// generic functions.
require_once 'funclib.php';
require_once 'func-openweathermap.php';
require_once 'func-openmeteo.php';
require_once 'supp/supp.php';

/**
 * Delete cache if parameters are changed, to make sure
 * current data will be available with next call.
 */
function wp_forecast_admin_init() {
	 $count = wpf_get_option( 'wp-forecast-count' );

	// Query arguments.
	$wpfqs = '';
	if ( isset( $_SERVER['QUERY_STRING'] ) ) {
		$wpfqs = sanitize_text_field( wp_unslash( $_SERVER['QUERY_STRING'] ) );
	}

	if ( ( 'page=wp-forecast-admin.php' == $wpfqs ) && ( isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === $_SERVER['REQUEST_METHOD'] ) ) {
		for ( $i = 0;$i < $count;$i++ ) {
			$wpfcid = get_widget_id( $i );

			// delete cache for old location.
			wpf_update_option( 'wp-forecast-expire' . $wpfcid, '0' );
			wpf_update_option( 'wp-forecast-cache' . $wpfcid, '' );
		}
	}

	// add thickbox and jquery for checklist.
	wp_enqueue_script( 'thickbox' );
	wp_enqueue_style( 'thickbox' );

	wp_localize_script( 'wpf_search', 'wpf_search', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	add_action( 'wp_ajax_nopriv_wpf_search_ajax', 'wpf_search_ajax' );
	add_action( 'wp_ajax_wpf_search_ajax', 'wpf_search_ajax' );
	add_action( 'wp_ajax_nopriv_wpf_check_ajax', 'wpf_check_ajax' );
	add_action( 'wp_ajax_wpf_check_ajax', 'wpf_check_ajax' );

	// add admin notice concerning the problems with accuweather.
	add_action( 'admin_notices', 'wpf_accuweather_problem_notice' );
}

/**
 * Ajax funktion fuer die suche im admin dialog.
 */
function wpf_search_ajax() {
	// check nonce3.
	if ( ! isset( $_POST['wpf_nonce_3'] ) ) {
		die( "<br><br>Looks like you didn't send any credentials. Please reload the page. " );
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wpf_nonce_3'] ) ), 'wpf_nonce_3' ) ) {
		die( "<br><br>Looks like you didn't send any credentials. Please reload the page. " );
	}

	$wpf_vars = get_wpf_opts( ( isset( $_POST['wpfcid'] ) ? sanitize_text_field( wp_unslash( $_POST['wpfcid'] ) ) : 'A' ) );
	$av       = $wpf_vars;
	$locale   = $wpf_vars['wpf_language'];

	// get translations.
	if ( function_exists( 'load_plugin_textdomain' ) ) {
		add_filter( 'plugin_locale', 'wpf_lplug', 10, 2 );
		load_plugin_textdomain( 'wp-forecast', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
		remove_filter( 'plugin_locale', 'wpf_lplug', 10, 2 );
	}

	$res  = '';
	$res .= '<h2>' . esc_attr__( 'Searchresults', 'wp-forecast' ) . ':</h2>';

	if ( trim( ( isset( $_POST['searchterm'] ) ? sanitize_text_field( wp_unslash( $_POST['searchterm'] ) ) : '' ) ) == '' ) {
		$res .= '<p>' . esc_attr__( 'Please enter a non empty searchstring', 'wp-forecast' ) . '</p>';
	} else {
		$res .= '<p>' . esc_attr__( 'Please select a location', 'wp-forecast' ) . ':</p>';

		// search locations for openmeteo.
		$openmeteo_loc = get_loclist( $av['OPENMETEO_LOC_URI'], ( isset( $_POST['searchterm'] ) ? sanitize_text_field( wp_unslash( $_POST['searchterm'] ) ) : '' ) );

		$i = 0;
		// output searchresults.
		$res .= '<table border="1" ><thead><tr><th>';
		$res .= esc_attr__( 'Open-Meteo Hits', 'wp-forecast' );
		$res .= '</th></tr></thead><tbody>';

		$k = count( $openmeteo_loc );
		$i = 0;

		while ( $i < $k ) {
			$res .= '<tr>';

			$res .= '<td><a href="#" onclick="wpf_set_loc(\'' . addslashes( $openmeteo_loc[ $i ]['name'] ) . '\',' . $openmeteo_loc[ $i ]['latitude'] . ',' . $openmeteo_loc[ $i ]['longitude'] . ');" >';
			$res .= $openmeteo_loc[ $i ]['name'] . ', ' . ( isset( $openmeteo_loc[ $i ]['admin1'] ) ? $openmeteo_loc[ $i ]['admin1'] : '' ) . ' </a></td>';
			$res .= '</tr>';
			$i++;
		}
		$res .= '</tbody></table>';
	}

	echo wp_kses( $res, wpf_allowed_tags() );
	wp_die();
}

/**
 * Ajax funktion fuer den Verbindungstest im admin dialog.
 */
function wpf_check_ajax() {
	$res = '';

	// check nonce3.
	if ( ! isset( $_POST['wpf_nonce_3'] ) ) {
		die( "<br><br>Looks like you didn't send any credentials. Please reload the page. " );
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wpf_nonce_3'] ) ), 'wpf_nonce_3' ) ) {
		die( "<br><br>Looks like you didn't send any credentials. Please reload the page. " );
	}

	/**
	 *  Check the URL $url if it is a usable transport.
	 *
	 * @param string $url The URL to check.
	 */
	function check_url( $url ) {
		$erg = array();

		// switch to wp-forecast transport.
		switch_wpf_transport( true );

		$wprr_args = array(
			'timeout' => 30,
			'headers' => array(
				'Connection' => 'Close',
				'Accept'     => '*/*',
			),
		);

		// use generic WordPress function to retrieve data.
		$s    = time();
		$resp = wp_remote_request( $url, $wprr_args );
		$e    = time();

		// switch to WordPress transport.
		switch_wpf_transport( false );

		$erg['duration'] = (int) ( $e - $s );

		if ( is_wp_error( $resp ) ) {
			$errcode = $resp->get_error_code();
			$errmesg = $resp->get_error_message( $errcode );

			$erg['error'] = $errcode . ' ' . $errmesg;
			$erg['body']  = '';
			$erg['len']   = '-1';
		} else {
			$erg['error'] = '';
			$erg['body']  = $resp['body'];
			$erg['len']   = strlen( $resp['body'] );
		}
		return $erg;
	}

	/**
	 * Show the result of the check.
	 *
	 * @param string $erg Contains the result string.
	 */
	function show_check_result( $erg ) {
		$out   = '';
		$space = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		if ( '' == $erg['error'] ) {
			$out .= $space . 'Test was successfull.<br/>';
			$out .= $space . 'Fetched ' . $erg['len'] . ' Bytes in ';
			$out .= $erg['duration'] . ' seconds.<br/>';
			$out .= 'Content was: ' . $erg['body'] . '<br/>';
		} else {
			$out .= $space . 'Test ends in error.<br/>';
			$out .= $space . 'Error-Message was ' . $erg['error'] . '<br/>';
		}
		return $out;
	}

	$wpf_vars = get_wpf_opts( 'A' );

	if ( ! empty( $_POST['provider'] ) ) {
		$provider = sanitize_text_field( wp_unslash( $_POST['provider'] ) );
		$res     .= 'Checking for Weatherprovider ' . esc_attr( $provider ) . '<br />';

		if ( 'OpenWeatherMapV3' == $provider ) {
			if ( ! isset( $wpf_vars['apikey1'] ) ) {
				$out .= __( 'You have to set the API-Key to test OpenWeatherMap.', 'wp-forecast' );
				echo wp_kses( $out, wpf_allowed_tags() );
				die();
			}
			$url = 'https://api.openweathermap.org/data/3.0/onecall?lat=50.11&lon=8.68&appid=' . $wpf_vars['apikey1'] . '&exclude=minutely,hourly&units=1&lang=en';
		}

		if ( 'OpenWeatherMap' == $provider ) {
			if ( ! isset( $wpf_vars['apikey1'] ) ) {
				$out .= __( 'You have to set the API-Key to test OpenWeatherMap.', 'wp-forecast' );
				echo wp_kses( $out, wpf_allowed_tags() );
				die();
			}
			$url = 'https://api.openweathermap.org/data/2.5/onecall?lat=50.11&lon=8.68&appid=' . $wpf_vars['apikey1'] . '&exclude=minutely,hourly&units=1&lang=en';
		}

		if ( 'Open-Meteo' == $provider ) {
			$url = 'https://api.open-meteo.com/v1/forecast?latitude=50.77664&longitude=6.08342&current_weather=true&timeformat=unixtime&timezone=GMT';
		}

		// remember selected transport.
		$wp_transport = wpf_get_option( 'wp-forecast-wp-transport' );

		// checking for standard connection method.
		$res .= 'Checking default transport<br />';
		wpf_update_option( 'wp-forecast-wp-transport', 'default' );
		$erg  = check_url( $url );
		$res .= show_check_result( $erg );

		// checking fsockopen.
		$res .= 'Checking fsockopen transport<br />';
		wpf_update_option( 'wp-forecast-wp-transport', 'fsockopen' );
		$erg  = check_url( $url );
		$res .= show_check_result( $erg );

		// checking exthttp.
		$res .= 'Checking exthttp transport<br />';
		wpf_update_option( 'wp-forecast-wp-transport', 'exthttp' );
		$erg  = check_url( $url );
		$res .= show_check_result( $erg );

		// checking streams.
		$res .= 'Checking streams transport<br />';
		wpf_update_option( 'wp-forecast-wp-transport', 'streams' );
		$erg  = check_url( $url );
		$res .= show_check_result( $erg );

		// checking curl.
		$res .= 'Checking curl transport<br />';
		wpf_update_option( 'wp-forecast-wp-transport', 'curl' );
		$erg  = check_url( $url );
		$res .= show_check_result( $erg );

		// write back selected transport.
		wpf_update_option( 'wp-forecast-wp-transport', $wp_transport );

	}

	echo wp_kses( $res, wpf_allowed_tags() );
	wp_die();
}

/**
 * Add menuitem for options menu.
 */
function wp_forecast_admin() {
	$ap = add_menu_page( 'wp-Forecast', 'wp-Forecast', 'manage_options', basename( __FILE__ ), 'wpf_admin_form', plugins_url( '/wpf.png', __FILE__ ) );
	add_action( 'load-' . $ap, 'wp_forecast_contextual_help' );
}

/**
 * Print out hint for the widget control.
 *
 * @param array $args Parameters for amdin hint.
 */
function wpf_admin_hint( $args = null ) {
	$wpfcid = $args;

	// check nonce.
	if ( ! isset( $_POST['wpf_nonce_4'] ) ) {
		die( "<br><br>Looks like you didn't send any credentials. Please reload the page. " );
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wpf_nonce_4'] ) ), 'wpf_nonce_4' ) ) {
		die( "<br><br>Looks like you didn't send any credentials. Please reload the page. " );
	}

	load_plugin_textdomain( 'wp-forecast', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

	// code for widget title form.
	$av          = get_wpf_opts( $wpfcid );
	$av['title'] = $newtitle;

	if ( isset( $_POST[ 'wpf-submit-title' . $wpfcid ] ) ) {
		$newtitle = ( isset( $_POST[ 'wpf-title-' . $wpfcid ] ) ? sanitize_text_field( wp_unslash( $_POST[ 'wpf-title-' . $wpfcid ] ) ) : '' );
	}

	if ( $av['title'] != $newtitle ) {
		$av['title'] = $newtitle;
		wpf_update_option( 'wp-forecast-opts' . $wpfcid, serialize( $av ) );
	}

	echo esc_attr__( 'Title:', 'wp-forecast' );
	echo " <input style='width: 250px;' id='wpf-title-" . esc_attr( $wpfcid ) . "' name='wpf-title-" . esc_attr( $wpfcid ) . "' type='text' value='" . esc_attr( $av['title'] ) . "' />";
	echo "<input type='hidden' id='wpf-submit-title" . esc_attr( $wpfcid ) . "' name='wpf-submit-title" . esc_attr( $wpfcid ) . "' value='1' />";
	echo "<input name='wpf_nonce_4' type='hidden' value='<?php echo esc_attr( wp_create_nonce( 'wpf_nonce_4' ) ); ?>' />";
	echo '<p>' . esc_attr__( 'widget_hint', 'wp-forecast' ) . '</p>';
}

/**
 * Get the locationlist and return it in one long string.
 *
 * @param string $uri This contains the uri to fetch the list from.
 * @param string $loc This contains the location identifier.
 */
function get_loclist( $uri, $loc ) {
	$data = array();

	$url = $uri . 'name=' . urlencode( $loc ) . '&count=100&language=en&format=json';

	// fetch the URL and decode it.
	$json = wp_remote_get( $url );
	if ( ! is_wp_error( $json ) ) {
		$data = json_decode( $json['body'], true );
	}

	return $data['results'];
}


/**
 * Form handler for the widgets.
 *
 * @param string $wpfcid Widget-ID.
 * @param bool   $widgetcall Is it a widget call.
 */
function wpf_admin_form( $wpfcid = 'A', $widgetcall = 0 ) {
	global $wpf_maxwidgets, $blog_id;

	if ( function_exists( 'is_multisite' ) && is_multisite() && 1 != $blog_id && ! wpf_get_option( 'wpf_sa_allowed' ) ) {
		wp_forecast_activate();
	}

	$count       = wpf_get_option( 'wp-forecast-count' );
	$wpf_timeout = wpf_get_option( 'wp-forecast-timeout' );
	$wpf_delopt  = wpf_get_option( 'wp-forecast-delopt' );
	$wpf_ipstack = wpf_get_option( 'wp-forecast-ipstackapikey' );
	$wpf_loadcss = wpf_get_option( 'wp-forecast-loadcss' );

		// called via the options menu not from widgets.
	if ( 0 == $widgetcall ) {
		// load translation.
		load_plugin_textdomain( 'wp-forecast', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

		// if this is a post call to update global settings.
		if ( isset( $_POST['wpf_global_settings_update'] ) ) {
			// check nonce.
			if ( ! isset( $_POST['wpf_nonce_1'] ) ) {
				die( "<br><br>Looks like you didn't send any credentials. Please reload the page. " );
			}
			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wpf_nonce_1'] ) ), 'wpf_nonce_1' ) ) {
				die( "<br><br>Looks like you didn't send any credentials. Please reload the page. " );
			}

			// update max widgets.
			$number = ( isset( $_POST['wp-forecast-count'] ) ? (int) $_POST['wp-forecast-count'] : 1 );
			if ( $number > $wpf_maxwidgets ) {
				$number = $wpf_maxwidgets;
			}
			if ( $number < 1 ) {
				$number = 1;
			}
			$newcount = $number;

			if ( $count != $newcount ) {
				$count = $newcount;
				wpf_update_option( 'wp-forecast-count', $count );
				// add missing option to database.
				wp_forecast_activate();
				// init the new number of widgets.
				widget_wp_forecast_init( $count );
			}

			// update timeout.
			$timeout = ( isset( $_POST['wp-forecast-timeout'] ) ? (int) $_POST['wp-forecast-timeout'] : 1 );
			if ( $timeout < 0 ) {
				$timeout = 1;
			}
			wpf_update_option( 'wp-forecast-timeout', $timeout );

			// update delopt.
			$wpf_delopt = ( array_key_exists( 'wp-forecast-delopt', $_POST ) ? (int) ( 'on' == $_POST['wp-forecast-delopt'] ) : false );
			wpf_update_option( 'wp-forecast-delopt', $wpf_delopt );

			// update transport method.
			$wpf_pretrans = ( isset( $_POST['wp-forecast-pre-transport'] ) ? (int) $_POST['wp-forecast-pre-transport'] : false );
			wpf_update_option( 'wp-forecast-pre-transport', $wpf_pretrans );

			// update ipstack apikey.
			wpf_update_option( 'wp-forecast-ipstackapikey', ( isset( $_POST['wp-forecast-ipstackapikey'] ) ? sanitize_text_field( wp_unslash( $_POST['wp-forecast-ipstackapikey'] ) ) : '' ) );

			// update loadcss.
			$wpf_loadcss = ( array_key_exists( 'wp-forecast-loadcss', $_POST ) ? (int) ( 'on' == $_POST['wp-forecast-loadcss'] ) : false );
			wpf_update_option( 'wp-forecast-loadcss', $wpf_loadcss );

			$count       = wpf_get_option( 'wp-forecast-count' );
			$wpf_timeout = wpf_get_option( 'wp-forecast-timeout' );
			$wpf_delopt  = wpf_get_option( 'wp-forecast-delopt' );
			$wpf_ipstack = wpf_get_option( 'wp-forecast-ipstackapikey' );
			$wpf_loadcss = wpf_get_option( 'wp-forecast-loadcss' );

		} // end of update global settings.

		// start of form ---------------------------------------------------------------------------
		//
		// print out number of widgets selection.
		$out  = "<div class='wrap'>";
		$out .= '<h2>WP-Forecast Widgets</h2>';
		$out .= tl_add_supp();

		// begin of global settings.
		$out .= '<h2>' . esc_attr__( 'Global Settings', 'wp-forecast' ) . '</h2>';
		$out .= "<form name='options' id='options' method='post' action='#'>";
		$out .= '<input name="wpf_nonce_1" type="hidden" value="' . wp_create_nonce( 'wpf_nonce_1' ) . '" />';
		$out .= '<table style="width:80%;"><tr><td>' . esc_attr__( 'How many wp-forecast widgets would you like?', 'wp-forecast' ) . '</td>';
		$out .= '<td><select id="wp-forecast-count" name="wp-forecast-count">';

		for ( $i = 1; $i <= $wpf_maxwidgets; ++$i ) {
			$out .= "<option value='$i' ";
			if ( $count == $i ) {
				$out .= "selected='selected' ";
			}
			$out .= ">$i</option>";
		}
		$out .= '</select></td>';

		// print out timeout input field for transport.
		// (timeout for data connection).
		$out .= '<td>' . esc_attr__( 'Timeout for weatherprovider connections (secs.)?', 'wp-forecast' ) . ':</td>';
		$out .= "<td><input id='wp-forecast-timeout' name='wp-forecast-timeout' type='text' size='3' maxlength='3' value='" . $wpf_timeout . "' />";
		$out .= '</td></tr>';

		// show transport method selection.
		$out .= '<tr><td>' . esc_attr__( 'Preselect WordPress transfer method', 'wp-forecast' ) . ' :</td>';
		$out .= "<td><select name='wp-forecast-pre-transport' id='wp-forecast-pre-transport' size='1' >";
		$out .= "<option value='default'>" . esc_attr__( 'default', 'wp-forecast' ) . '</option>';

		// get WordPress default transports.
		$pre_trans = wpf_get_option( 'wp-forecast-pre-transport' );
		$tlist     = get_wp_transports();
		foreach ( $tlist as $t ) {
			$out .= "<option value='$t'" . ( $t == $pre_trans ? 'selected="selected"' : '' ) . ">$t</option>";
		}
		$out .= '</select></td>';

		// print out option deletion switch.
		$out .= '<td>' . esc_attr__( 'Delete options during plugin deactivation?', 'wp-forecast' ) . '</td>';
		$out .= "<td><input id='wp-forecast-delopt' name='wp-forecast-delopt' type='checkbox' ";
		if ( $wpf_delopt ) {
			$out .= 'checked="checked"';
		}
		$out .= ' />';
		$out .= "</td></tr>\n";

		// print out input field for ipstack api key.
		$out .= "<tr><td><a href='http://www.ipstack.com' target='_blank'>" . esc_attr__( 'API key to use location from ipstack.com ', 'wp-forecast' ) . ':</a></td>';
		$out .= "<td><input id='wp-forecast-ipstackapikey' name='wp-forecast-ipstackapikey' type='text' size='40' width='32' value='$wpf_ipstack'  />";
		$out .= "</td>\n";

		// print out option loadcss.
		$out .= '<td>' . esc_attr__( 'Do not load default CSS', 'wp-forecast' ) . '</td>';
		$out .= "<td><input id='wp-forecast-loadcss' name='wp-forecast-loadcss' type='checkbox' ";
		if ( $wpf_loadcss ) {
			$out .= 'checked="checked"';
		}
		$out .= ' />';
		$out .= "</td></tr>\n";

		$out .= "</table>\n";

		$out .= "<div class='submit'><input class='button-primary' type='submit' name='wpf_global_settings_update' value='" . esc_attr__( 'Update global options', 'wp-forecast' ) . " »' /></div>";
		$out .= "</form></div>\n";
		// end of global options.

		$out .= '<div style="text-align:right;padding-right:20px;">';
		$out .= '<a href="#TB_inline?height=1000&width=600&inlineId=wpf_check__&wpfcid=A" class="thickbox">';
		$out .= esc_attr__( 'Check connection to Weatherprovider', 'wp-forecast' ) . '</a></div>' . "\n";

		// print out widget selection form.
		if ( $count > 1 ) {
			$out .= "<form name='selwidget' id='selwidget' method='post' action='#'>";
			$out .= '<input name="wpf_nonce_2" type="hidden" value="' . wp_create_nonce( 'wpf_nonce_2' ) . '" />';
			$out .= '<div>' . esc_attr__( 'Available widgets', 'wp-forecast' ) . ': ';

			$out .= "<select name='widgetid' size='1' >";
			for ( $i = 0;$i < $count;$i++ ) {
				$id   = get_widget_id( $i );
				$out .= "<option value='" . $id . "' ";

				if (
					(
						array_key_exists( 'widgetid', $_POST ) &&
						sanitize_text_field( wp_unslash( $_POST['widgetid'] ) ) == $id &&
						isset( $_POST['set_widget'] )
					) || (
						array_key_exists( 'wid', $_POST ) && isset( $_POST['info_update'] ) && sanitize_text_field( $_POST['wid'] == $id )
					) || (
						array_key_exists( 'wid', $_POST ) && isset( $_POST['search_loc'] ) && sanitize_text_field( $_POST['wid'] == $id )
					) ) {
					$out .= "selected='selected'";
				}
				$out .= '>' . $id . '</option>';
			}
			$out .= '</select>';

			$out .= '<span class="submit"><input class="button" type="submit" name="set_widget" value="';
			$out .= esc_attr__( 'Select widget', 'wp-forecast' ) . " »\" /></span></div></form>\n";
		}

		echo wp_kses( $out, wpf_allowed_tags() );
	}

	// if this is a post call, select widget.
	if ( isset( $_POST['set_widget'] ) && 0 == $widgetcall ) {
		// check nonce.
		if ( ! isset( $_POST['wpf_nonce_2'] ) ) {
			die( "<br><br>Looks like you didn't send any credentials. Please reload the page. " );
		}
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wpf_nonce_2'] ) ), 'wpf_nonce_2' ) ) {
			die( "<br><br>Looks like you didn't send any credentials. Please reload the page. " );
		}

		$wpfcid = sanitize_text_field( wp_unslash( $_POST['widgetid'] ) );
	}

	// if this is any other post call.
	if ( ( isset( $_POST['info_update'] ) && 0 == $widgetcall ) ||
	   ( isset( $_POST['set_loc'] ) && 0 == $widgetcall ) ||
	   ( isset( $_POST['search_loc'] ) && 0 == $widgetcall ) ) {

		// check nonce.
		if ( ! isset( $_POST['wpf_nonce_3'] ) ) {
			die( "<br><br>Looks like you didn't send any credentials. Please reload the page. " );
		}
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wpf_nonce_3'] ) ), 'wpf_nonce_3' ) ) {
			die( "<br><br>Looks like you didn't send any credentials. Please reload the page. " );
		}

		$wpfcid = ( isset( $_POST['wid'] ) ? sanitize_text_field( wp_unslash( $_POST['wid'] ) ) : 'A' );
	}

	// default is the first widget.
	if ( '' == $wpfcid ) {
		$wpfcid = 'A';
	}

	// call sub form.
	wpf_sub_admin_form( $wpfcid, $widgetcall );
}


/**
 * Form to modify wp-forecast setup
 * the form also has a search function to search the wright location.
 *
 * @param string $wpfcid Widget ID.
 * @param bool   $widgetcall Is it a widgetcall.
 */
function wpf_sub_admin_form( $wpfcid, $widgetcall ) {
	global $blog_id;

	// check nonce3.
	if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' === $_SERVER['REQUEST_METHOD'] && isset( $_POST['info_update'] ) ) {
		if ( ! isset( $_POST['wpf_nonce_3'] ) ) {
			die( "<br><br>Looks like you didn't send any credentials. Please reload the page. " );
		}
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wpf_nonce_3'] ) ), 'wpf_nonce_3' ) ) {
			die( "<br><br>Looks like you didn't send any credentials. Please reload the page. " );
		}
	}

	// get parameters.
	$av      = get_wpf_opts( $wpfcid );
	$allowed = maybe_unserialize( wpf_get_option( 'wpf_sa_allowed' ) );

	$ismulti = false;
	if ( function_exists( 'is_multisite' ) && is_multisite() && get_current_blog_id() != 1 ) {
		$ismulti = true;
	}

	if ( function_exists( 'load_plugin_textdomain' ) ) {
		load_plugin_textdomain( 'wp-forecast', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}

	// if this is a POST call, save new values.
	if ( isset( $_POST['info_update'] ) ) {
		$upflag = false;

		$wpf_admin_fields = array(
			'service',
			'apikey1',
			'location',
			'loclongitude',
			'loclatitude',
			'visitorlocation',
			'locname',
			'location',
			'refresh',
			'provider',
			'csssprites',
			'windicon',
			'pdforecast',
			'pdfirstday',
			'timeoffset',
			'currtime',
			'wpf_language',
			'windunit',
			'metric',
			'ouv_safetime',
			'ouv_ozone',
			'ouv_uvmax',
			'ouv_uv',
			'ouv_apikey',
		);

		// fill up POST array with NULL vallues for unset keys.
		foreach ( $wpf_admin_fields as $k ) {
			if ( ! isset( $_POST[ $k ] ) ) {
				$_POST[ $k ] = null;
			}
		}

		// update fields if applicable.
		foreach ( $wpf_admin_fields as $k ) {
			$av[ $k ] = sanitize_text_field( wp_unslash( $_POST[ $k ] ) );
			$upflag   = true;
		}
		if ( '' == $av['metric'] ) {
			$av['metric'] = '0';
		}
		if ( '' == $av['currtime'] ) {
			$av['currtime'] = '0';
		}
		if ( '' == $av['windicon'] ) {
			$av['windicon'] = '0';
		}
		if ( '' == $av['ouv_uv'] ) {
			$av['ouv_uv'] = '0';
		}
		if ( '' == $av['ouv_uvmax'] ) {
			$av['ouv_uvmax'] = '0';
		}
		if ( '' == $av['ouv_ozone'] ) {
			$av['ouv_ozone'] = '0';
		}
		if ( '' == $av['ouv_safetime'] ) {
			$av['ouv_safetime'] = '0';
		}

		if ( '' == $av['visitorlocation'] ) {
			$av['visitorlocation'] = '0';
		}

		// set checkbox value to zero if not set.
		// for forecast options.
		$nd = array( 'day1', 'day2', 'day3', 'day4', 'day5', 'day6', 'day7', 'day8', 'day9', 'night1', 'night2', 'night3', 'night4', 'night5', 'night6', 'night7', 'night8', 'night9' );
		foreach ( $nd as $i ) {
			if ( ! isset( $_POST[ $i ] ) || '' == $_POST[ $i ] ) {
				$_POST[ "$i" ] = '0';
			}
		}

		// set empty checkboxes to 0.
		$do = array(
			'd_c_icon',
			'd_c_time',
			'd_c_short',
			'd_c_temp',
			'd_c_real',
			'd_c_press',
			'd_c_humid',
			'd_c_wind',
			'd_c_sunrise',
			'd_c_sunset',
			'd_d_icon',
			'd_d_short',
			'd_d_temp',
			'd_d_wind',
			'd_n_icon',
			'd_n_short',
			'd_n_temp',
			'd_n_wind',
			'd_c_date',
			'd_d_date',
			'd_n_date',
			'd_c_copyright',
			'd_c_wgusts',
			'd_d_wgusts',
			'd_n_wgusts',
			'd_c_accuweather',
			'd_c_aw_newwindow',
			'd_c_uvindex',
			'd_d_maxuv',
			'd_n_maxuv',
			'd_c_precipe',
			'd_d_precipe',
			'd_n_precipe',
		);
		foreach ( $do as $i ) {
			if ( ! isset( $_POST[ $i ] ) || '' == $_POST[ $i ] ) {
				$_POST[ "$i" ] = '0';
			}
		}

		// build config string for dispconfig and update if necessary.
		$newdispconfig = '';
		foreach ( $do as $i ) {
			$newdispconfig .= (int) $_POST[ $i ];
		}

		if ( ( ! $ismulti || isset( $allowed['ue_dispconfig'] ) ) && strcmp( $av['dispconfig'], $newdispconfig ) != 0 ) {
			$av['dispconfig'] = $newdispconfig;
			$upflag           = true;
		}

		// build config string for forecast and update if necessary.
		$newdaytime = ( isset( $_POST['day1'] ) ? (int) $_POST['day1'] : 0 )
					. ( isset( $_POST['day2'] ) ? (int) $_POST['day2'] : 0 )
					. ( isset( $_POST['day3'] ) ? (int) $_POST['day3'] : 0 )
					. ( isset( $_POST['day4'] ) ? (int) $_POST['day4'] : 0 )
					. ( isset( $_POST['day5'] ) ? (int) $_POST['day5'] : 0 )
					. ( isset( $_POST['day6'] ) ? (int) $_POST['day6'] : 0 )
					. ( isset( $_POST['day7'] ) ? (int) $_POST['day7'] : 0 )
					. ( isset( $_POST['day8'] ) ? (int) $_POST['day8'] : 0 )
					. ( isset( $_POST['day9'] ) ? (int) $_POST['day9'] : 0 );

		if ( ( ! $ismulti || isset( $allowed['ue_forecast'] ) ) && $av['daytime'] != $newdaytime ) {
			$av['daytime'] = $newdaytime;
			$upflag        = true;
		}

		$newnighttime = ( isset( $_POST['night1'] ) ? (int) $_POST['night1'] : 0 )
					. ( isset( $_POST['night2'] ) ? (int) $_POST['night2'] : 0 )
					. ( isset( $_POST['night3'] ) ? (int) $_POST['night3'] : 0 )
					. ( isset( $_POST['night4'] ) ? (int) $_POST['night4'] : 0 )
					. ( isset( $_POST['night5'] ) ? (int) $_POST['night5'] : 0 )
					. ( isset( $_POST['night6'] ) ? (int) $_POST['night6'] : 0 )
					. ( isset( $_POST['night7'] ) ? (int) $_POST['night7'] : 0 )
					. ( isset( $_POST['night8'] ) ? (int) $_POST['night8'] : 0 )
					. ( isset( $_POST['night9'] ) ? (int) $_POST['night9'] : 0 );

		if ( ( ! $ismulti || isset( $allowed['ue_forecast'] ) ) && $av['nighttime'] != $newnighttime ) {
			$av['nighttime'] = $newnighttime;
			$upflag          = true;
		}

		// put message after update.
		echo "<div class='updated'><p><strong>";
		if ( $upflag ) {
			wpf_update_option( 'wp-forecast-opts' . $wpfcid, serialize( $av ) );
			wpf_update_option( 'wp-forecast-expire' . $wpfcid, '0' );
			echo esc_attr__( 'Settings successfully updated', 'wp-forecast' );
		} else {
			echo esc_attr__( 'You have to change a field to update settings.', 'wp-forecast' );
		}
		echo '</strong></p></div>';
	}

	// if this is a POST call, set location.
	if ( isset( $_POST['set_loc'] ) ) {
		if ( isset( $_POST['new_loc'] ) ) {
			$av['location'] = sanitize_text_field( wp_unslash( $_POST['new_loc'] ) );
		}
		if ( isset( $_POST['provider'] ) ) {
			$av['service'] = sanitize_text_field( wp_unslash( $_POST['provider'] ) );
		}
	}
	?>

	 <?php
		if ( 0 == $widgetcall ) :
			?>
			<div class="wrap">
		 <!-- add javascript for field control -->
			<?php
			include 'wp-forecast-js.php';
			?>
	 <form method="post" name='woptions' action='#'>
	 <input name="wpf_nonce_3" type="hidden" value="<?php echo esc_attr( wp_create_nonce( 'wpf_nonce_3' ) ); ?>" />
			<?php endif; ?>
	 <input name='wid' type='hidden' value='<?php echo esc_attr( $wpfcid ); ?>'/>   
	 <h2><?php echo esc_attr__( 'WP-Forecast Setup', 'wp-forecast' ) . ' (Widget ' . esc_attr( $wpfcid ) . ') '; ?></h2>
	  <?php
		if ( 0 == $widgetcall ) :
			?>
			<fieldset id="set1"><?php endif; ?>

		
	 <div style="float: left; width: 49%">
		 <p><b><?php echo esc_attr__( 'Weatherservice', 'wp-forecast' ); ?>:</b>
		 <select name="service" id="service" size="1" onchange="apifields(document.woptions.service.value);">
		   <option value="openweathermap" 
		   <?php
			if ( 'openweathermap' === $av['service'] ) {
				echo 'selected="selected"';}
			?>
			><?php echo esc_attr__( 'OpenWeathermap', 'wp-forecast' ); ?></option>
			<option value="openweathermap3" 
		   <?php
			if ( 'openweathermap3' === $av['service'] ) {
				echo 'selected="selected"';}
			?>
			><?php echo esc_attr__( 'OpenWeatherMap v3', 'wp-forecast' ); ?></option>
			
			<option value="openmeteo" 
		   <?php
			if ( 'openmeteo' === $av['service'] ) {
				echo 'selected="selected"';}
			?>
			><?php echo esc_attr__( 'Open-Meteo', 'wp-forecast' ); ?></option>
	 </select>&nbsp;

		 <?php echo esc_attr__( 'API-Key', 'wp-forecast' ); ?>:
		 <input name="apikey1" id="apikey1" type="text" size="20" maxlength="80" value="<?php echo esc_attr( $av['apikey1'] ); ?>" /></p>
		 <script type="text/javascript">apifields(document.woptions.service.value);</script>

	 <p><b><?php echo esc_attr__( 'Location', 'wp-forecast' ); ?>:</b>
	 <input name="location" id="location" type="text" size="30" maxlength="80" value="<?php echo esc_attr( $av['location'] ); ?>"
		 <?php
			if ( 1 === $widgetcall ) {
				echo 'readonly';}
			?>
		 />

	<a href="#TB_inline?height=1000&width=600&inlineId=wpf_search__&wpfcid=<?php echo esc_attr( $wpfcid ); ?>" class="thickbox">
		<img alt="Search Icon" src="<?php echo esc_attr( plugin_dir_url( __FILE__ ) ); ?>/Searchicon16x16.png" /></a>
		&nbsp;&nbsp;&nbsp;<input type="checkbox" name="visitorlocation" id="visitorlocation" value="1" 
		<?php
		if ( isset( $av['visitorlocation'] ) && '1' === $av['visitorlocation'] ) {
			echo 'checked="checked"';}
		?>
		 onchange="vlocfields_update();" /><b><?php echo esc_attr__( 'Use visitors location', 'wp-forecast' ); ?></b>
	 </p>

	<p><b><?php echo esc_attr__( 'Latitude', 'wp-forecast' ); ?>:</b>
		 <input name="loclatitude" id="loclatitude" type="text" size="15" maxlength="10" 
		 value="<?php echo ( isset( $av['loclatitude'] ) ? esc_attr( $av['loclatitude'] ) : '' ); ?>" />
		 
	 <b><?php echo esc_attr__( 'Longitude', 'wp-forecast' ); ?>:</b>
		 <input name="loclongitude" id="loclongitude" type="text" size="15" maxlength="10" 
		 value="<?php echo ( isset( $av['loclongitude'] ) ? esc_attr( $av['loclongitude'] ) : '' ); ?>" /></p>
		  
		 <p><b><?php echo esc_attr__( 'Locationname', 'wp-forecast' ); ?>:</b>
		 <input name="locname" id="locname" type="text" size="30" maxlength="80" value="<?php echo esc_attr( $av['locname'] ); ?>" /></p>
	 <p><b><?php echo esc_attr__( 'Refresh cache after', 'wp-forecast' ); ?></b>
		 <input name="refresh" id="refresh" type="text" size="10" maxlength="6" value="<?php echo esc_attr( $av['refresh'] ); ?>"/>
		 <b><?php echo esc_attr__( 'secs.', 'wp-forecast' ); ?></b><br /></p>
	 <p><input type="checkbox" name="metric" id="metric" value="1" 
	 <?php
		if ( '1' === $av['metric'] ) {
			echo 'checked="checked"';}
		?>
		 /> <b><?php echo esc_attr__( 'Use metric units', 'wp-forecast' ); ?></b>
	 </p>

	 <p><input type="checkbox" name="currtime" id="currtime" value="1" 
	 <?php
		if ( '1' === $av['currtime'] ) {
			echo 'checked="checked"';
		}
		?>
		 /> <b><?php echo esc_attr__( 'Use current time', 'wp-forecast' ); ?></b> / <b>
			   <?php echo esc_attr__( 'Time-Offset', 'wp-forecast' ); ?> :</b> 
			   <input type="text" name="timeoffset" id="timeoffset" size="5" maxlength="5" value="<?php echo esc_attr( isset( $av['timeoffset'] ) ? $av['timeoffset'] : '' ); ?>" /> <b><?php echo esc_attr__( 'minutes', 'wp-forecast' ); ?></b> 
		 </p>

		 <p><b><?php echo esc_attr__( 'Windspeed-Unit', 'wp-forecast' ); ?>: </b><select name="windunit" id="windunit" size="1">
		  <option value="ms" 
		  <?php
			if ( 'ms' === $av['windunit'] ) {
				echo 'selected="selected"';}
			?>
			><?php echo esc_attr__( 'Meter/Second (m/s)', 'wp-forecast' ); ?></option>
			  <option value="kmh" 
			  <?php
				if ( 'kmh' === $av['windunit'] ) {
					echo 'selected="selected"';}
				?>
				><?php echo esc_attr__( 'Kilometer/Hour (km/h)', 'wp-forecast' ); ?></option>
			  <option value="mph" 
			  <?php
				if ( 'mph' === $av['windunit'] ) {
					echo 'selected="selected"';}
				?>
				><?php echo esc_attr__( 'Miles/Hour (mph)', 'wp-forecast' ); ?></option>
			  <option value="kts" 
			  <?php
				if ( 'kts' === $av['windunit'] ) {
					echo 'selected="selected"';}
				?>
				><?php echo esc_attr__( 'Knots (kts)', 'wp-forecast' ); ?></option>
			  <option value="bft" 
			  <?php
				if ( 'bft' === $av['windunit'] ) {
					echo 'selected="selected"';}
				?>
				><?php echo esc_attr__( 'Beaufort (bft)', 'wp-forecast' ); ?></option>
	 </select></p>


		

	 <p>
		 <b><?php echo esc_attr__( 'Language', 'wp-forecast' ); ?>: </b><select name="wpf_language" id="wpf_language" size="1">
			<?php
			$iso_arr  = array( 'en_US', 'de_DE', 'bg_BG', 'bs_BA', 'hr_HR', 'cs_CZ', 'da_DK', 'nl_NL', 'fi_FI', 'fr_FR', 'el_EL', 'he_IL', 'hu_HU', 'id_ID', 'it_IT', 'nb_NO', 'fa_IR', 'pl_PL', 'pt_PT', 'ro_RO', 'ru_RU', 'sr_SR', 'sk_SK', 'es_ES', 'sv_SE', 'uk_UA' );
			$lang_arr = array( 'english', 'deutsch', 'bulgarian', 'bosnian', 'croatian', 'czech', 'dansk', 'dutch', 'finnish', 'french', 'greek', 'hebrew', 'hungarian', 'indonesian', 'italian', 'norwegian', 'persian', 'polish', 'portugu&#234;s', 'romanian', 'russian', 'serbian', 'slovak', 'spanish', 'swedish', 'ukranian' );
			$lopt     = '';
			$num      = count( $iso_arr );
			for ( $i = 0; $i < $num; $i++ ) {
				$lopt .= '<option value="' . $iso_arr[ $i ] . '"';
				if ( $av['wpf_language'] == $iso_arr[ $i ] ) {
					// space as first characteer is important.
					$lopt .= ' selected="selected"';
				}
				$lopt .= '>' . $lang_arr[ $i ];
				$lopt .= '</option>';
			}
			echo wp_kses( $lopt, wpf_allowed_tags() );
			?>
		</select></p>
 
		 <p><input type="checkbox" name="pdforecast" id="pdforecast" value="1" 
		 <?php
			if ( '1' === $av['pdforecast'] ) {
				echo 'checked="checked"';}
			?>
			 onchange="pdfields_update();" /> <b><?php echo esc_attr__( 'Show forecast as ajax pull-down', 'wp-forecast' ); ?></b>
		 </p>

		 <p>
		 <b><?php echo esc_attr__( 'First day in pull-down', 'wp-forecast' ); ?>: </b><select name="pdfirstday" id="pdfirstday" size="1">
   <option value="0" 
	<?php
	if ( '0' === $av['pdfirstday'] ) {
		echo 'selected="selected"';}
	?>
	>0</option>
   <option value="1" 
	<?php
	if ( '1' === $av['pdfirstday'] ) {
		echo 'selected="selected"';}
	?>
	>1</option>
   <option value="2" 
	<?php
	if ( '2' === $av['pdfirstday'] ) {
		echo 'selected="selected"';}
	?>
	>2</option>
   <option value="3" 
	<?php
	if ( '3' === $av['pdfirstday'] ) {
		echo 'selected="selected"';}
	?>
	>3</option>
   <option value="4" 
	<?php
	if ( '4' === $av['pdfirstday'] ) {
		echo 'selected="selected"';}
	?>
	>4</option>
   <option value="5" 
	<?php
	if ( '5' === $av['pdfirstday'] ) {
		echo 'selected="selected"';}
	?>
	>5</option>
   <option value="6" 
	<?php
	if ( '6' === $av['pdfirstday'] ) {
		echo 'selected="selected"';}
	?>
	>6</option>
   <option value="7" 
	<?php
	if ( '7' === $av['pdfirstday'] ) {
		echo 'selected="selected"';}
	?>
	>7</option>
   <option value="8" 
	<?php
	if ( '8' === $av['pdfirstday'] ) {
		echo 'selected="selected"';}
	?>
	>8</option>
   <option value="9" 
	<?php
	if ( '9' == $av['pdfirstday'] ) {
		echo 'selected="selected"';}
	?>
	>9</option>
   </select></p>
   <script type="text/javascript">pdfields_update();</script>
	
   <p><input type="checkbox" name="windicon" id="windicon" value="1" 
	<?php
	if ( '1' === $av['windicon'] ) {
		echo 'checked="checked"';}
	?>
	 /> <b><?php echo esc_attr__( 'Show wind condition as icon', 'wp-forecast' ); ?></b>
   </p>
   <p><input type="checkbox" name="csssprites" id="csssprites" value="1" 
	<?php
	if ( '1' === $av['csssprites'] ) {
		echo 'checked="checked"';}
	?>
	 /> <b><?php echo esc_attr__( 'Use CSS-Sprites for showing icons', 'wp-forecast' ); ?></b>
   </p>
   </div>
	   <!-- start of right column -->
	   <div  style="padding-left: 2%; float: left; width: 49%;">
	   <b><?php echo esc_attr__( 'Display Configuration', 'wp-forecast' ); ?></b>
		<table>
	<tr>
		 <td>&nbsp;</td>
		 <td><?php echo esc_attr__( 'Current Conditions', 'wp-forecast' ); ?></td>
		 <td><?php echo esc_attr__( 'Forecast Day', 'wp-forecast' ); ?></td>
		 <td><?php echo esc_attr__( 'Forecast Night', 'wp-forecast' ); ?></td>
		</tr>
		<tr>
		<td><?php echo esc_attr__( 'Icon', 'wp-forecast' ); ?></td>
		<td class='td-center'><input type="checkbox" name="d_c_icon" id="d_c_icon" value="1" 
		 <?php
			if ( substr( $av['dispconfig'], 0, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td>
		<td class='td-center'><input type="checkbox" name="d_d_icon" id="d_d_icon" value="1" 
		 <?php
			if ( substr( $av['dispconfig'], 10, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td>
		<td class='td-center'><input type="checkbox" name="d_n_icon" id="d_n_icon" value="1" 
		 <?php
			if ( substr( $av['dispconfig'], 14, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td>
		 </tr>
		  <tr>
		 <td><?php echo esc_attr__( 'Date', 'wp-forecast' ); ?></td>
		<td class='td-center'><input type="checkbox" name="d_c_date" id="d_c_date" value="1" 
		 <?php
			if ( substr( $av['dispconfig'], 18, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td>
		 <td class='td-center'>&nbsp;</td>
		 <td class='td-center'>&nbsp;</td>
		 </tr>
	 <tr>
		 <td><?php echo esc_attr__( 'Time', 'wp-forecast' ); ?></td>
		<td class='td-center'><input type="checkbox" name="d_c_time" id="d_c_time" value="1" 
		 <?php
			if ( substr( $av['dispconfig'], 1, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td>
		<td class='td-center'>&nbsp;</td>
		<td class='td-center'>&nbsp;</td>
		</tr> 
		<tr>
		<td><?php echo esc_attr__( 'Short Description', 'wp-forecast' ); ?></td>
		<td class='td-center'><input type="checkbox" name="d_c_short" id="d_c_short" value="1" 
		 <?php
			if ( substr( $av['dispconfig'], 2, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td>
		<td class='td-center'><input type="checkbox" name="d_d_short" id="d_d_short" value="1" 
		 <?php
			if ( substr( $av['dispconfig'], 11, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td>
		<td class='td-center'><input type="checkbox" name="d_n_short" id="d_n_short" value="1" 
		 <?php
			if ( substr( $av['dispconfig'], 15, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td>
		</tr> 
		<tr>
		<td><?php echo esc_attr__( 'Temperature', 'wp-forecast' ); ?></td>
		<td class='td-center'><input type="checkbox" name="d_c_temp" id="d_c_temp" value="1" 
		 <?php
			if ( substr( $av['dispconfig'], 3, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td>
		<td class='td-center'><input type="checkbox" name="d_d_temp" id="d_d_temp" value="1" 
		 <?php
			if ( substr( $av['dispconfig'], 12, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td>
		<td class='td-center'><input type="checkbox" name="d_n_temp" id="d_n_temp" value="1" 
		 <?php
			if ( substr( $av['dispconfig'], 16, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td>
		</tr> 
		<tr>
		<td><?php echo esc_attr__( 'Realfeel', 'wp-forecast' ); ?></td>
		<td class='td-center'><input type="checkbox" name="d_c_real" id="d_c_real" value="1" 
		 <?php
			if ( substr( $av['dispconfig'], 4, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td>
		<td class='td-center'>&nbsp;</td>
		<td class='td-center'>&nbsp;</td>
		</tr> 
		<tr>
		<td><?php echo esc_attr__( 'Pressure', 'wp-forecast' ); ?></td>
		<td class='td-center'><input type="checkbox" name="d_c_press" id="d_c_press" value="1" 
		 <?php
			if ( substr( $av['dispconfig'], 5, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td>
		<td class='td-center'>&nbsp;</td>
		<td class='td-center'>&nbsp;</td>
		</tr> 
		<tr>
		<td><?php echo esc_attr__( 'Humidity', 'wp-forecast' ); ?></td>
		<td class='td-center'><input type="checkbox" name="d_c_humid" id="d_c_humid" value="1" 
		 <?php
			if ( substr( $av['dispconfig'], 6, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td>
		<td class='td-center'>&nbsp;</td>
		<td class='td-center'>&nbsp;</td>
		</tr> 
		<tr>
		<td><?php echo esc_attr__( 'Wind', 'wp-forecast' ); ?></td>
		<td class='td-center'><input type="checkbox" name="d_c_wind" id="d_c_wind" value="1" 
		 <?php
			if ( substr( $av['dispconfig'], 7, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td>
		<td class='td-center'><input type="checkbox" name="d_d_wind" id="d_d_wind" value="1" 
		 <?php
			if ( substr( $av['dispconfig'], 13, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td>
		<td class='td-center'><input type="checkbox" name="d_n_wind" id="d_n_wind" value="1" 
		<?php
		if ( substr( $av['dispconfig'], 17, 1 ) == '1' ) {
			echo 'checked="checked"';}
		?>
		 /></td>
		</tr> 
	<tr>
		<td><?php echo esc_attr__( 'Windgusts', 'wp-forecast' ); ?></td>
		<td class='td-center'><input type="checkbox" name="d_c_wgusts" id="d_c_wgusts" value="1" 
		 <?php
			if ( substr( $av['dispconfig'], 22, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td>
		<td class='td-center'><input type="checkbox" name="d_d_wgusts" id="d_d_wgusts" value="1" 
		 <?php
			if ( substr( $av['dispconfig'], 23, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td>
		<td class='td-center'><input type="checkbox" name="d_n_wgusts" id="d_n_wgusts" value="1" 
		 <?php
			if ( substr( $av['dispconfig'], 24, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td>
		</tr>      

		<tr>
		<td><?php echo esc_attr__( 'Precipitation', 'wp-forecast' ); ?></td>
		<td class='td-center'><input type="checkbox" name="d_c_precipe" id="d_c_precipe" value="1" 
		 <?php
			if ( substr( $av['dispconfig'], 30, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td>
		<td class='td-center'><input type="checkbox" name="d_d_precipe" id="d_d_precipe" value="1" 
		 <?php
			if ( substr( $av['dispconfig'], 31, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td>
		<td class='td-center'><input type="checkbox" name="d_n_precipe" id="d_n_precipe" value="1" 
		 <?php
			if ( substr( $av['dispconfig'], 32, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td>
		</tr>    
		
		<tr>
		<td><?php echo esc_attr__( 'UV-Index', 'wp-forecast' ); ?></td>
		<td class='td-center'><input type="checkbox" name="d_c_uvindex" id="d_c_uvindex" value="1" 
		 <?php
			if ( substr( $av['dispconfig'], 27, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td>
		<td class='td-center'><input type="checkbox" name="d_d_maxuv" id="d_d_maxuv" value="1" 
		 <?php
			if ( substr( $av['dispconfig'], 28, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td>
		<td class='td-center'><input type="checkbox" name="d_n_maxuv" id="d_n_maxuv" value="1" 
		 <?php
			if ( substr( $av['dispconfig'], 29, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td>
		</tr>    
		   
	<tr>
		<td><?php echo esc_attr__( 'Sunrise', 'wp-forecast' ); ?></td>
		<td class='td-center'><input type="checkbox" name="d_c_sunrise" id="d_c_sunrise" value="1" 
		 <?php
			if ( substr( $av['dispconfig'], 8, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td>
		<td class='td-center'>&nbsp;</td>
		<td class='td-center'>&nbsp;</td>
		</tr> 
		<tr>
		<td><?php echo esc_attr__( 'Sunset', 'wp-forecast' ); ?></td>
		<td class='td-center'><input type="checkbox" name="d_c_sunset" id="d_c_sunset" value="1" 
		 <?php
			if ( substr( $av['dispconfig'], 9, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td>
		<td class='td-center'>&nbsp;</td>
		<td class='td-center'>&nbsp;</td>
		</tr>
		<tr>
		<td><?php echo esc_attr__( 'Copyright', 'wp-forecast' ); ?></td>
		<td class='td-center'><input type="checkbox" name="d_c_copyright" id="d_c_copyright" value="1" 
		 <?php
			if ( substr( $av['dispconfig'], 21, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td>
		<td class='td-center'>&nbsp;</td>
		<td class='td-center'>&nbsp;</td>
		</tr> 
	<tr>
		<td><?php echo esc_attr__( 'Link to Weatherprovider', 'wp-forecast' ); ?></td>
		<td class='td-center'><input type="checkbox" name="d_c_accuweather" id="d_c_accuweather" value="1" 
		<?php
		if ( substr( $av['dispconfig'], 25, 1 ) == '1' ) {
			echo 'checked="checked"';}
		?>
		 onchange="nwfields_update();" /></td>
			<td colspan="2" >(<?php echo esc_attr__( 'Open in new Window', 'wp-forecast' ); ?>: 
			<input type="checkbox" name="d_c_aw_newwindow" id="d_c_aw_newwindow" value="1" 
		<?php
		if ( substr( $av['dispconfig'], 26, 1 ) == '1' ) {
			echo 'checked="checked"';}
		?>
		 />)</td>
		</tr> 
		</table>
	<br /> 
		<script type="text/javascript">nwfields_update();</script>

<b><?php echo esc_attr__( 'Forecast', 'wp-forecast' ); ?></b>
								  
		 <table>
		 <tr>
			 <td>&nbsp;</td>
			 <td><?php echo esc_attr__( 'All', 'wp-forecast' ); ?></td>
			 <td><?php echo esc_attr__( 'Day', 'wp-forecast' ); ?> 1</td>
			 <td><?php echo esc_attr__( 'Day', 'wp-forecast' ); ?> 2</td>
			 <td><?php echo esc_attr__( 'Day', 'wp-forecast' ); ?> 3</td>
			 <td><?php echo esc_attr__( 'Day', 'wp-forecast' ); ?> 4</td>
			 <td><?php echo esc_attr__( 'Day', 'wp-forecast' ); ?> 5</td>
			 <td><?php echo esc_attr__( 'Day', 'wp-forecast' ); ?> 6</td>
			 <td><?php echo esc_attr__( 'Day', 'wp-forecast' ); ?> 7</td>
			 <td><?php echo esc_attr__( 'Day', 'wp-forecast' ); ?> 8</td>
			 <td><?php echo esc_attr__( 'Day', 'wp-forecast' ); ?> 9</td>
		 </tr>
		 <tr><td><?php echo esc_attr__( 'Daytime', 'wp-forecast' ); ?></td>
			 <td><input type="checkbox" name="alldays" onclick="this.value=check('day')" /></td>
			 <td><input type="checkbox" name="day1" id="day1" value="1" 
		   <?php
			if ( substr( $av['daytime'], 0, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td>
			 <td><input type="checkbox" name="day2" id="day2"  value="1" 
		   <?php
			if ( substr( $av['daytime'], 1, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td> 
			 <td><input type="checkbox" name="day3" id="day3" value="1" 
		   <?php
			if ( substr( $av['daytime'], 2, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td> 
		 <td><input type="checkbox" name="day4" id="day4" value="1" 
		   <?php
			if ( substr( $av['daytime'], 3, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td> 
			 <td><input type="checkbox" name="day5" id="day5" value="1" 
		   <?php
			if ( substr( $av['daytime'], 4, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td> 
			 <td><input type="checkbox" name="day6" id="day6" value="1" 
		   <?php
			if ( substr( $av['daytime'], 5, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td> 
			 <td><input type="checkbox" name="day7" id="day7" value="1" 
		   <?php
			if ( substr( $av['daytime'], 6, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td> 
			 <td><input type="checkbox" name="day8" id="day8" value="1" 
		   <?php
			if ( substr( $av['daytime'], 7, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td> 
			 <td><input type="checkbox" name="day9" id="day9" value="1" 
		   <?php
			if ( substr( $av['daytime'], 8, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td>
		 </tr>
		 <tr><td><?php echo esc_attr__( 'Nighttime', 'wp-forecast' ); ?></td>
			 <td><input type="checkbox" name="allnight" onclick="this.value=check('night')" /></td>
			 <td><input type="checkbox" name="night1" id="night1" value="1" 
		 <?php
			if ( substr( $av['nighttime'], 0, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td>
			 <td><input type="checkbox" name="night2" id="night2" value="1" 
		 <?php
			if ( substr( $av['nighttime'], 1, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td> 
			 <td><input type="checkbox" name="night3" id="night3" value="1" 
		 <?php
			if ( substr( $av['nighttime'], 2, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td> 
		 <td><input type="checkbox" name="night4" id="night4" value="1" 
		 <?php
			if ( substr( $av['nighttime'], 3, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td> 
			 <td><input type="checkbox" name="night5" id="night5" value="1" 
		 <?php
			if ( substr( $av['nighttime'], 4, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td> 
			 <td><input type="checkbox" name="night6" id="night6" value="1" 
		 <?php
			if ( substr( $av['nighttime'], 5, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td> 
			 <td><input type="checkbox" name="night7" id="night7" value="1" 
		 <?php
			if ( substr( $av['nighttime'], 6, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td> 
			 <td><input type="checkbox" name="night8" id="night8" value="1" 
		 <?php
			if ( substr( $av['nighttime'], 7, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td> 
			 <td><input type="checkbox" name="night9" id="night9" value="1" 
		 <?php
			if ( substr( $av['nighttime'], 8, 1 ) == '1' ) {
				echo 'checked="checked"';}
			?>
			 /></td>
		 </tr>
	   </table>
	   </div>
	   
	   <?php
		if ( 0 === $widgetcall ) :
			?>
			</fieldset><?php endif; ?>
	   
	   <?php
			// OpenUV parameters.
			echo '<div>';
			echo "<b><a href='http://www.openuv.io' target='_blank'>" . esc_attr__( 'Use UV-Data from openuv.io', 'wp-forecast' ) . ':</a></b>&nbsp;&nbsp;&nbsp;';

			echo esc_attr__( 'OpenUV - API-Key', 'wp-forecast' );
			echo ':<input name="ouv_apikey" id="ouv_apikey" type="text" size="40" maxlength="80" value="' . ( isset( $av['ouv_apikey'] ) ? esc_attr( $av['ouv_apikey'] ) : '' ) . '" />&nbsp;&nbsp;&nbsp;';

			echo '<input type="checkbox" name="ouv_uv" id="oav_uv" value="1"';
		if ( isset( $av['ouv_uv'] ) && '1' === $av['ouv_uv'] ) {
			echo 'checked="checked"';
		}
			echo '/>';
			echo '<label for="oav_uv">' . esc_attr__( 'Show UV-Index', 'wp-forecast' ) . '&nbsp;&nbsp;&nbsp;</label>';

			echo '<input type="checkbox" name="ouv_uvmax" id="oav_uvmax" value="1"';
		if ( isset( $av['ouv_uvmax'] ) && '1' === $av['ouv_uvmax'] ) {
			echo 'checked="checked"';
		}
			echo '/>';
			echo '<label for="oav_uvmax">' . esc_attr__( 'Show max. UV-Index', 'wp-forecast' ) . '&nbsp;&nbsp;&nbsp;</label>';

			echo '<input type="checkbox" name="ouv_ozone" id="oav_ozone" value="1"';
		if ( isset( $av['ouv_ozone'] ) && '1' === $av['ouv_ozone'] ) {
			echo 'checked="checked"';
		}
			echo '/>';
			echo '<label for="oav_ozone">' . esc_attr__( 'Show Ozone', 'wp-forecast' ) . '&nbsp;&nbsp;&nbsp;</label>';

			echo '<input type="checkbox" name="ouv_safetime" id="oav_safetime" value="1"';
		if ( isset( $av['ouv_safetime'] ) && '1' === $av['ouv_safetime'] ) {
			echo 'checked="checked"';
		}
			echo '/>';
			echo '<label for="oav_safetime">' . esc_attr__( 'Show safe exposure time as tooltip', 'wp-forecast' ) . '&nbsp;&nbsp;&nbsp;</label>';

			echo '</div>';
		?>
	   
	   <?php
		// submit button.
		if ( 0 === $widgetcall ) {
			echo "<div class='submit'><input class='button-primary' type='submit' name='info_update' value='" . esc_attr__( 'Update options', 'wp-forecast' ) . " »' /></div>";
		} else {
			echo "<input type='hidden' name='info_update' value='1' />";
		}
		?>

	   </form>
	   
	<!-- -- ANFANG Suchdialog -->		
	<div id="wpf_search__" style="display:none">
		<div class='wpf-search'>
		<form action='#' onsubmit="wpf_search(); return false;">
			<input name="wpf_nonce_3" id="wpf_nonce_3" type="hidden" value="<?php echo esc_attr( wp_create_nonce( 'wpf_nonce_3' ) ); ?>" />
			<?php
			// get locale.
			$locale = get_locale();
			if ( empty( $locale ) ) {
				$locale = 'en_US';
			}
			?>
			<input id='wpf_search_language' type='hidden' value='<?php echo esc_attr( $locale ); ?>' />
			<input id='wpfcid' type='hidden' value='<?php echo esc_attr( $wpfcid ); ?>' />
	
			<h3><?php esc_attr_e( 'Search location', 'wp-forecast' ); ?></h3>
			<p><b><?php esc_attr_e( 'Searchterm', 'wp-forecast' ); ?>:</b>
			<input id="searchloc" type="text" size="30" maxlength="30" />
			<a href="#" class='button-primary' style="color:#ffffff;" onclick='javascript:wpf_search();' id='search_loc'><?php esc_attr_e( 'Search location', 'wp-forecast' ); ?> »</a>
			<br /></p>
			<p><?php esc_attr_e( 'The search will be performed using OpenMeteo location database.', 'wp-forecast' ); ?></p>
		</form>
		</div>
		<hr /> 
		<div id="search_results"></div>
	</div>
	<!-- -- ENDE Suchdialog-->	

	<!-- -- ANFANG Check Dialog -->
	<div id="wpf_check__" style="display:none">
	<style>#message {margin:20px; padding:20px; background:#cccccc; color:#cc0000;}</style>

	<div id="checkform" class="wrap" >
	<h2>
	<?php
	echo 'wp-forecast ';
	esc_attr_e( 'Connection-check', 'wp-forecast' );
	?>
	</h2>
	<p><?php esc_attr_e( 'For OpenWeatherMap please enter your API-Key for Widget A before starting the connection test.', 'wp-forecast' ); ?></p>
	<form action='#' onsubmit="wpf_check(); return false;">
	<input name="wpf_nonce_3" type="hidden" value="<?php echo esc_attr( wp_create_nonce( 'wpf_nonce_3' ) ); ?>" />
	<table class="editform" cellspacing="5" cellpadding="5">
	<tr>
	<th scope="row"><label for="wprovider"><?php esc_attr_e( 'Select weatherprovider', 'wp-forecast' ); ?>:</label></th>
	<td><select name="wprovider" id="wprovider">
		<option value='Open-Meteo'>Open-Meteo</option>
		<option value='OpenWeatherMap'>OpenWeatherMap</option>
		<option value='OpenWeatherMapV3'>OpenWeatherMapV3</option>
	</select></td>

	<tr><td><p class="submit">
	<input type="submit" name="startcheck" id="startcheck" value="<?php echo esc_attr_e( 'Start check', 'wp-forecast' ); ?> &raquo;" />
	<td><p class="submit">
	<input type="submit" name="cancel" id="cancel" value="<?php esc_attr_e( 'Close', 'wp-forecast' ); ?>" onclick="jQuery('div#check_log').html('');tb_remove();" /></p></td>
	</p></td></tr>
	</form></table>
	<hr />

	<div id="check_log">Check log</div></div>
	</div>
	<!-- -- ENDE Check Dialog -->
	   
	   
	   
	   </div>
	<?php
	echo '<script type="text/javascript">jQuery( document ).ready( function() { apifields(document.woptions.service.value); vlocfields_update();});';
	echo '</script>';
}

/**
 * Add contextual help to WordPress admin Help menu.
 */
function wp_forecast_contextual_help() {

	$locale = get_locale();
	if ( empty( $locale ) ) {
		$locale = 'en_US';
	}

	if ( function_exists( 'load_plugin_textdomain' ) ) {
		load_plugin_textdomain( 'wp-forecast', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}

	$contextual_help  = '<p>';
	$contextual_help .= esc_attr__(
		'If you are looking for instructions or help on wp-forecast, 
   please use the following ressources. If you are stuck you can 
   always write an email to',
		'wp-forecast'
	);
	$contextual_help .= ' <a href="mailto:support@tuxlog.de">support@tuxlog.de</a>.';
	$contextual_help .= '</p>';
	$contextual_help .= '<ul>';
	$contextual_help .= '<li><a href="http://www.tuxlog.de/wordpress/2008/wp-forecast-reference-v17-english/" target="_blank">';
	$contextual_help .= esc_attr__( 'English Manual', 'wp-forecast' );
	$contextual_help .= '</a></li>';
	$contextual_help .= '<li><a href=" http://www.tuxlog.de/wp-forecast-handbuch/" target="_blank">';
	$contextual_help .= esc_attr__( 'German Manual', 'wp-forecast' );
	$contextual_help .= '</a></li>';
	$contextual_help .= '<li><a href="http://www.tuxlog.de/wordpress/2009/checkliste-fur-wp-forecast-checklist-for-wp-worecast" target="_blank">';
	$contextual_help .= esc_attr__( 'Checklist for connection test', 'wp-forecast' );
	$contextual_help .= '</a></li>';
	$contextual_help .= '<li><a href="http://www.wordpress.org/extend/plugins/wp-forecast" target="_blank">';
	$contextual_help .= esc_attr__( 'wp-forecast on WordPress.org', 'wp-forecast' );
	$contextual_help .= '</a></li>';
	$contextual_help .= '<li><a href="http://www.tuxlog.de/wp-forecast/" target="_blank">';
	$contextual_help .= esc_attr__( 'German wp-forecast Site', 'wp-forecast' );
	$contextual_help .= '</a></li>';
	$contextual_help .= '<li><a href="http://www.tuxlog.de/wordpress/2012/wp-forecast-mit-wpml-nutzen" target="_blank">';
	$contextual_help .= esc_attr__( 'Using wp-forecast with WPML (german)', 'wp-forecast' );
	$contextual_help .= '</a></li>';
	$contextual_help .= '<li><a href="http://wordpress.org/plugins/wp-forecast/changelog/" target="_blank">';
	$contextual_help .= esc_attr__( 'Changelog', 'wp-forecast' );
	$contextual_help .= '</a></li></ul>';
	$contextual_help .= '<p>';
	$contextual_help .= esc_attr__( 'Links will open in new Window/Tab.', 'wp-forecast' );
	$contextual_help .= '</p>';

	$screen = get_current_screen();

	// Add my_help_tab if current screen is My Admin Page.
	$screen->add_help_tab(
		array(
			'id'      => 'wpf_help_tab',
			'title'   => esc_attr__( 'wp-forecast Help' ),
			'content' => $contextual_help,
		)
	);
}
