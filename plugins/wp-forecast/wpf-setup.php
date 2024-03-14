<?php
/**
 * This file is part of the wp-forecast plugin for WordPress.
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

/**
 * Setting up the default options in table wp-options during
 * plugin activation from the plugins page.
 */
function wp_forecast_activate() {
	// add number of widgets, default: 1.
	$count = get_option( 'wp-forecast-count' );

	// migrating from accuweather to OpenMeteo.
	if ( $count > 0 ) {
		for ( $i = 0;$i < $count;$i++ ) {
			$wpfcid = get_widget_id( $i );

			// get all widget options.
			$av = get_wpf_opts( $wpfcid );

			if ( isset( $av['service'] ) && 'accu' == $av['service'] ) {
				$weather = get_option( 'wp-forecast-cache' . $wpfcid );
				$expire  = get_option( 'wp-forecast-expire' . $wpfcid );

				if ( $av['loclatitude'] > 0 && $av['loclongitude'] > 0 ) {  // we have got coords and can switch to openmeteo.
						$av['service']  = 'openmeteo';
						$av['location'] = $av['locname'];
				} else { // we do not have coords.
						$av['service'] = '';
				}
				wpf_update_option( 'wp-forecast-opts' . $wpfcid, $av );
			}

			// delete cache.
			wpf_update_option( 'wp-forecast-cache' . $wpfcid, '' );
			wpf_update_option( 'wp-forecast-expire' . $wpfcid, '0' );
		}
	}

	if ( '' == $count ) {
		$count = '1';
		wpf_add_option( 'wp-forecast-count', $count );
	};

	// add timeout for weather connections, default: 30.
	$timeout = get_option( 'wp-forecast-timeout' );

	if ( '' == $timeout ) {
		$timeout = '10';
		wpf_add_option( 'wp-forecast-timeout', $timeout );
	};

	// add switch to control option deletion during plugin deactivation.
	$delopt = get_option( 'wp-forecast-delopt' );

	if ( '' == $delopt ) {
		$delopt = '0';
		wpf_add_option( 'wp-forecast-delopt', $delopt );
	};

	// add preselected transport method for wp-forecast.
	$pre_trans = get_option( 'wp-forecast-pre-transport' );

	if ( '' == $pre_trans ) {
		$pre_trans = 'default';
		wpf_add_option( 'wp-forecast-pre-transport', $pre_trans );
	};

	// add transport to use by WordPress only for wp-forecast.
	$wp_trans = get_option( 'wp-forecast-wp-transport' );

	if ( '' == $wp_trans ) {
		$wp_trans = 'default';
		wpf_add_option( 'wp-forecast-wp-transport', $wp_trans );
	};

	// add ipstack apikey to use by WordPress only for wp-forecast.
	$wp_ipstack = get_option( 'wp-forecast-ipstackapikey' );

	if ( '' == $wp_ipstack ) {
		wpf_add_option( 'wp-forecast-ipstackapikey', $wp_ipstack );
	};

	for ( $i = 0;$i < $count;$i++ ) {
		$wpfcid = get_widget_id( $i );

		// get all widget options.
		$av      = get_wpf_opts( $wpfcid );
		$weather = get_option( 'wp-forecast-cache' . $wpfcid );
		$expire  = get_option( 'wp-forecast-expire' . $wpfcid );

		// if the options dont exists, add the defaults.
		if ( empty( $av['service'] ) || '' == $av['service'] ) {
			$av = array();

			$av['service']      = 'openmeteo'; // specify the weatherservice to use.
			$av['apikey1']      = ''; // Partner ID or API Code for openweathermap.
			$av['location']     = 'Frankfurt am Main'; // location code.
			$av['loclatitude']  = '50.11552'; // coord of location.
			$av['loclongitude'] = '8.68417'; // coord of location.
			$av['locname']      = 'Frankfurt am Main'; // user defined location name.
			$av['refresh']      = '1800'; // the intervall the local weather data is renewed.
			$av['metric']       = '1'; // 1 if you want to use metric scheme, else 0.
			$av['wpf_language'] = 'en_US'; // language code for this widget.
			$av['daytime']      = '000000000'; // Switches for Daytime forecast.
			$av['nighttime']    = '000000000'; // Switches for Nighttime forecast.
			$av['currtime']     = '1'; // 1 if you want to use current time, else 0.
			$av['timeoffset']   = '0'; // offset to correct wrong weather time e.g. half-timezones.
			$av['title']        = __( 'The Weather', 'wp-forecast' ); // the widget title.
			// Displayconfigurationmatrix.
			// CC    FC Day    FC Night.
			// Icon                0     10        14.
			// Datum              18     -         -.
			// Zeit                1     -         -.
			// Shorttext           2     11        15.
			// Temperatur          3     12        16.
			// gef. Temp           4     -         -.
			// Luftdruck           5     -         -.
			// Luftfeuchte         6     -         -.
			// Wind                7     13        17.
			// Windboen           22     23        24.
			// Sonnenaufgang       8     -         -.
			// Sonnenuntergang     9     -         -.
			// Copyright          21     -         -.
			// provider link      25     -         -.
			// open in new window 26     -         -.
			// uvindex            27	   28  		 29.
			// precipitation	  30       31		 32.
			//
			$av['dispconfig'] = str_repeat( '1', 32 );
			$av['windunit']   = 'ms'; // Choose between ms, kmh, mph or kts.
			$av['pdforecast'] = '0'; // pulldown forecast 0=No, 1=Yes.
			$av['pdfirstday'] = '0'; // day to start pulldown with.
			$av['windicon']   = '0'; // show windicon 0=No 1=Yes.
			$av['csssprites'] = '0'; // use csssprites to display icons 0=No 1=Yes.

			$av['ouv_apikey']   = ''; // APIkey to access openuv.io data.
			$av['ouv_uv']       = '0'; // show uv index from openuv.
			$av['ouv_uvmax']    = '0'; // show max uv index from openuv.
			$av['ouv_ozone']    = '0'; // show ozone from openuv.
			$av['ouv_safetime'] = '0'; // show safetimes as tooltip from openuv.

			wpf_add_option( 'wp-forecast-opts' . $wpfcid, serialize( $av ) );
		}

		if ( '' == $weather ) {
			$weather = '';
			wpf_add_option( 'wp-forecast-cache' . $wpfcid, $weather );
		};

		if ( '' == $expire ) {
			$expire = '0';
			wpf_add_option( 'wp-forecast-expire' . $wpfcid, $expire );
		};
	} // end of for

	global $blog_id;
	if ( function_exists( 'is_multisite' ) && is_multisite() && 1 != $blog_id ) {
		// add options for super admin on multisites.
		$wpf_sa_defaults = get_option( 'wpf_sa_defaults' );
		$wpf_sa_allowed  = get_option( 'wpf_sa_allowed' );

		$allallowed = array(
			'ue_wp-forecast-count'         => 1,
			'ue_wp-forecast-timeout'       => 1,
			'ue_wp-forecast-pre-transport' => 1,
			'ue_wp-forecast-delopt'        => 1,
			'ue_service'                   => 1,
			'ue_apikey1'                   => 1,
			'ue_location'                  => 1,
			'ue_locname'                   => 1,
			'ue_refresh'                   => 1,
			'ue_metric'                    => 1,
			'ue_currtime'                  => 1,
			'ue_timeoffset'                => 1,
			'ue_windunit'                  => 1,
			'ue_wpf_language'              => 1,
			'ue_pdforecast'                => 1,
			'ue_pdfirstday'                => 1,
			'ue_dispconfig'                => 1,
			'ue_forecast'                  => 1,
			'ue_daytime'                   => 1,
			'ue_nighttime'                 => 1,
			'ue_windicon'                  => 1,
			'ue_csssprites'                => 1,
		);

		if ( ! $wpf_sa_defaults ) {
			$wpf_sa_defaults = serialize( array() );
			add_blog_option( 1, 'wpf_sa_defaults', $wpf_sa_defaults );
		}

		if ( ! $wpf_sa_allowed ) {
			$wpf_sa_allowed = serialize( $allallowed );
			add_blog_option( 1, 'wpf_sa_allowed', $wpf_sa_allowed );
		}
	}
}

/**
 * Is called when plugin is deactivated and removes all.
 * the wp-forecast options from the database.
 *
 * @param string $wpfcid The Widget ID.
 */
function wp_forecast_deactivate( $wpfcid ) {
	global $wpf_maxwidgets;

	$delopt = get_option( 'wp-forecast-delopt' );

	// only delete options when switch is set.
	if ( 1 == $delopt ) {
		$count = $wpf_maxwidgets; // get_option('wp-forecast-count');.

		for ( $i = 0;$i < $count;$i++ ) {
			$wpfcid = get_widget_id( $i );

			delete_option( 'wp-forecast-opts' . $wpfcid );
			delete_option( 'wp-forecast-cache' . $wpfcid );
			delete_option( 'wp-forecast-expire' . $wpfcid );
		}
		delete_option( 'wp-forecast-timeout' );
		delete_option( 'wp-forecast-count' );
		delete_option( 'wp-forecast-delopt' );
		delete_option( 'wp-forecast-pre-transport' );
		delete_option( 'wp-forecast-wp-transport' );
		// delete options for superadmin on multisites.
		delete_option( 'wpf_sa_defaults' );
		delete_option( 'wpf_sa_allowed' );
	}
}

