<?php
/**
 * This file is part of the wp-forecast plugin for WordPress
 *
 * Copyright 2006-2023 Hans Matzen (email : webmaster at tuxlog dot de)
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

if ( ! function_exists( 'fetchURL' ) ) {
	/**
	 * This function fetches an url an returns it as one whole string.
	 *
	 * @param string $url the url to fetch.
	 */
	function fetch_url( $url ) {
		// get timeout parameter.
		$timeout = get_option( 'wp-forecast-timeout' );
		if ( '' == $timeout ) {
			$timeout = 10;
		}

		if ( function_exists( 'wp_remote_request' ) ) {
			// switch to wp-forecast transport.
			switch_wpf_transport( true );

			$wprr_args = array(
				'timeout'    => $timeout,
				'decompress' => true,
				'headers'    => array(
					'Connection' => 'Close',
					'Accept'     => '*/*',
				),
			);

			// use generic WordPress function to retrieve data.
			$s    = time();
			$resp = wp_remote_request( $url, $wprr_args );
			$e    = time();

			if ( is_wp_error( $resp ) ) {
				$blen = '-1';
			} else {
				$blen = strlen( $resp['body'] );
			}

			// switch to wp-forecast transport.
			switch_wpf_transport( false );

			if ( is_wp_error( $resp ) ) {
				$errcode = $resp->get_error_code();
				$errmesg = $resp->get_error_message( $errcode );

				$erg  = '<ADC_DATABASE><FAILURE>Connection Error:' . $errcode . '<br/>';
				$erg .= $errmesg . "</FAILURE></ADC_DATABASE>\n";
			} else {
				$erg = $resp['body'];
			}
		} else {
				  // fallback to old fsockopen variant.
				  $url_parsed = parse_url( $url );
				  $host       = $url_parsed['host'];
			if ( ! isset( $url_parsed['port'] ) ) {
				$port = 80;
			} else {
				$port = $url_parsed['port'];
			}

			$path = $url_parsed['path'];
			if ( '' != $url_parsed['query'] ) {
				$path .= '?' . $url_parsed['query'];
			}
			$out = "GET $path HTTP/1.0\r\nHost: $host\r\n\r\n";
			// open connection.
			$fp = @fsockopen( $host, $port, $errno, $errstr, $timeout );

			$erg = '';
			if ( $fp ) {
				// set timeout for reading.
				stream_set_timeout( $fp, $timeout );
				// send request.
				fwrite( $fp, $out );
				$body = false;
				// read answer.
				while ( ! feof( $fp ) ) {
					$s = fgets( $fp, 1024 );
					if ( $body ) {
							$erg .= $s;
					}
					if ( "\r\n" == $s ) {
							$body = true;
					}
				}
				// close connection.
				fclose( $fp );
			} else {
				// error handling.
				$erg = '<ADC_DATABASE><FAILURE>Connection Error:' . $errno . ' >> ' . $errstr . "</FAILURE></ADC_DATABASE>\n";
			}
		}

		// workaround for bug in decompress function, class wp_http in wp 2.9.
		$derg = @gzinflate( $erg );
		if ( false !== $derg ) {
			$erg = $derg;
		}

		return $erg;
	}


	/**
	 * Converts the given wind parameters into a suitable windstring.
	 *
	 * @param string $metric the units to use.
	 * @param string $wspeed the wind speed.
	 * @param string $windunit the unit of the wind to convert.
	 */
	function windstr( $metric, $wspeed, $windunit ) {
		// if its mph convert it to m/s.
		if ( 1 != $metric ) {
			$wspeed = round( $wspeed * 0.44704, 0 );
		}

		// convert it to selected unit.
		switch ( $windunit ) {
			case 'ms':
				$wunit = 'm/s';
				break;
			case 'kmh':
				$wspeed = round( $wspeed * 3.6, 0 );
				$wunit  = 'km/h';
				break;
			case 'mph':
				$wspeed = round( $wspeed * 2.23694, 0 );
				$wunit  = 'mph';
				break;
			case 'kts':
				$wspeed = round( $wspeed * 1.9438, 0 );
				$wunit  = 'kts';
				break;
			case 'bft':
				$wbft = 0;
				$bft  = array( 0.3, 1.6, 3.4, 5.5, 8.0, 10.8, 13.9, 17.2, 20.8, 24.5, 28.5, 32.7 );
				foreach ( $bft as $b ) {
					if ( $wspeed < $b ) {
						$wbft--;
						break;
					}
					$wbft++;
				}
				$wunit  = 'bft';
				$wspeed = $wbft;
				break;
		}
		return $wspeed . ' ' . $wunit;
	}


	/**
	 * Wrapper to make sure the correct option is used whether multisite or wp is used.
	 * only wraps main parameters and pass through all others.
	 *
	 * @param string $name the name of the option to get.
	 */
	function wpf_get_option( $name ) {
		global $blog_id;

		if ( ! function_exists( 'is_multisite' ) || ! is_multisite() || 1 == $blog_id ) {
			return get_option( $name );
		} else {
			// this is the multisite part.
			if ( 'wpf_sa_defaults' == $name || 'wpf_sa_allowed' == $name ) {
				return get_blog_option( 1, $name );
			} else {
				return get_blog_option( $blog_id, $name );
			}
		}
	}

	/**
	 * Wrapper for update option to hide differences between wp and wpmu.
	 *
	 * @param string $name the name of the option.
	 * @param string $value the value of the option.
	 */
	function wpf_update_option( $name, $value ) {
		global $blog_id;

		if ( ! function_exists( 'is_multisite' ) || ! is_multisite() || 1 == $blog_id ) {
			update_option( $name, $value );
		} else {
			update_blog_option( $blog_id, $name, $value );
		}
	}

	/**
	 * Wrapper for add option to hide differences between wp and wpmu.
	 *
	 * @param string $name the name of the option.
	 * @param string $value the value of the option.
	 */
	function wpf_add_option( $name, $value ) {
		global $blog_id;

		if ( ! function_exists( 'is_multisite' ) || ! is_multisite() || 1 == $blog_id ) {
			add_option( $name, $value );
		} else {
			add_blog_option( $blog_id, $name, $value );
		}
	}

	/**
	 * Reads all wp-forecast options and returns an array.
	 *
	 * @param $string $wpfcid the id of th widget.
	 */
	function get_wpf_opts( $wpfcid ) {
		global $blog_id;

		$av  = array();
		$opt = wpf_get_option( 'wp-forecast-opts' . $wpfcid );

		if ( ! empty( $opt ) ) {
			// unpack if necessary.
			$av = maybe_unserialize( $opt );
		} elseif ( get_option( 'wp-forecast-location' . $wpfcid ) != '' ) {
			// get old widget options from database.
			$av['service']      = get_option( 'wp-forecast-service' . $wpfcid );
			$av['apikey1']      = get_option( 'wp-forecast-apikey1' . $wpfcid );
			$av['apikey2']      = get_option( 'wp-forecast-apikey2' . $wpfcid );
			$av['location']     = get_option( 'wp-forecast-location' . $wpfcid );
			$av['locname']      = get_option( 'wp-forecast-locname' . $wpfcid );
			$av['refresh']      = get_option( 'wp-forecast-refresh' . $wpfcid );
			$av['metric']       = get_option( 'wp-forecast-metric' . $wpfcid );
			$av['wpf_language'] = get_option( 'wp-forecast-language' . $wpfcid );
			$av['daytime']      = get_option( 'wp-forecast-daytime' . $wpfcid );
			$av['nighttime']    = get_option( 'wp-forecast-nighttime' . $wpfcid );
			$av['dispconfig']   = get_option( 'wp-forecast-dispconfig' . $wpfcid );
			$av['windunit']     = get_option( 'wp-forecast-windunit' . $wpfcid );
			$av['currtime']     = get_option( 'wp-forecast-currtime' . $wpfcid );
			$av['timeoffset']   = get_option( 'wp-forecast-timeoffset' . $wpfcid );
			if ( '' == trim( $av['timeoffset'] ) ) {
				$av['timeoffset'] = 0;
			}
			$av['title'] = get_option( 'wp-forecast-title' . $wpfcid );
			// replace old options by new one row option.
			wpf_add_option( 'wp-forecast-opts' . $wpfcid, serialize( $av ) );
			// remove old options from database.
			delete_option( 'wp-forecast-location' . $wpfcid );
			delete_option( 'wp-forecast-locname' . $wpfcid );
			delete_option( 'wp-forecast-refresh' . $wpfcid );
			delete_option( 'wp-forecast-metric' . $wpfcid );
			delete_option( 'wp-forecast-language' . $wpfcid );
			delete_option( 'wp-forecast-daytime' . $wpfcid );
			delete_option( 'wp-forecast-nighttime' . $wpfcid );
			delete_option( 'wp-forecast-dispconfig' . $wpfcid );
			delete_option( 'wp-forecast-windunit' . $wpfcid );
			delete_option( 'wp-forecast-currtime' . $wpfcid );
			delete_option( 'wp-forecast-title' . $wpfcid );
			delete_option( 'wp-forecast-service' . $wpfcid );
			delete_option( 'wp-forecast-apikey1' . $wpfcid );
			delete_option( 'wp-forecast-apikey2' . $wpfcid );
		} else {
			$av = array();
		}

		// add expire options.
		$av['expire'] = get_option( 'wp-forecast-expire' . $wpfcid );

		// add generic options.
		$av['fc_date_format'] = get_option( 'date_format' );
		$av['fc_time_format'] = get_option( 'time_format' );
		$av['xmlerror']       = '';

		// set static uris for each provider.
		$av['OPENWEATHERMAP_BASE_URI']    = 'https://api.openweathermap.org/data/2.5/onecall?';
		$av['OPENWEATHERMAP_BASE_URI_V3'] = 'https://api.openweathermap.org/data/3.0/onecall?';

		$av['OPENMETEO_BASE_URI'] = 'https://api.open-meteo.com/v1/forecast?';
		$av['OPENMETEO_LOC_URI']  = 'https://geocoding-api.open-meteo.com/v1/search?';

		// if we use multisite then merge admin options.
		if ( function_exists( 'is_multisite' ) && is_multisite() && 1 != $blog_id ) {

			// read defaults and allowed fields.
			$defaults = maybe_unserialize( wpf_get_option( 'wpf_sa_defaults' ) );
			$allowed  = maybe_unserialize( wpf_get_option( 'wpf_sa_allowed' ) );
			// in case allowed is still empty.
			if ( ! $allowed ) {
				$allowed = array();
			}

			// set wpf_maxwidgets for users.
			global $blog_id, $wpf_maxwidgets;
			if ( $blog_id > '1' && isset( $defaults['wp-forecast-count'] ) ) {
				$wpf_maxwidgets = $defaults['wp-forecast-count'];
			}

			// map rest of fields.
			foreach ( $allowed as $f => $fswitch ) {
				$fname = substr( $f, 3 ); // strip ue_ prefix.

				if ( 1 != $fswitch || ! isset( $av[ $fname ] ) ) {
					// replace value in av with forced default.
					if ( array_key_exists( $fname, $defaults ) ) {
						$av[ $fname ] = $defaults[ $fname ];
					}
				}
			}
		}
		return $av;
	}


	/**
	 * Build the url from the parameters and fetch the weather-data.
	 * return it as one long string.
	 *
	 * @param string $uri    the url of the weather provider.
	 * @param string $loc    the location code of the  weather provider.
	 * @param string $metric the units to use.
	 */
	function get_weather( $uri, $loc, $metric ) {
		$url = $uri . 'location=' . urlencode( $loc ) . '&metric=' . $metric;

		$xml = fetchURL( $url );

		return $xml;
	}

	/**
	 * Just return the css link.
	 * this function is called via the wp_head hook.
	 *
	 * @param string $wpfcid the widget id.
	 */
	function wp_forecast_css( $wpfcid = 'A' ) {
		$wpf_loadcss = wpf_get_option( 'wp-forecast-loadcss' );
		if ( 1 == $wpf_loadcss ) {
			return;
		}

		$def  = 'wp-forecast-default.css';
		$user = 'wp-forecast.css';

		if ( file_exists( WP_PLUGIN_DIR . '/wp-forecast/' . $user ) ) {
			$def = $user;
		}

		wp_enqueue_style( 'wp-forecast', plugin_dir_url( __FILE__ ) . $def, array(), '9999' );
	}


	/**
	 * Just return the css link when not using WordPress
	 * this function is called when showing widget directly
	 *
	 * @param string $wpfcid the widget id.
	 */
	function wp_forecast_css_nowp( $wpfcid = 'A' ) {
		$wpf_loadcss = wpf_get_option( 'wp-forecast-loadcss' );
		if ( 1 == $wpf_loadcss ) {
			return;
		}

		$def  = 'wp-forecast-default-nowp.css';
		$user = 'wp-forecast-nowp.css';

		if ( file_exists( WP_PLUGIN_DIR . '/wp-forecast/' . $user ) ) {
			$def = $user;
		}

		wp_enqueue_style( 'wp-forecast-nowp', plugin_dir_url( __FILE__ ) . $def, array(), '9999' );
	}

	/**
	 * Just return the css link when not using WordPress
	 * this function is called when showing widget directly
	 */
	function wp_forecast_get_nowp_css() {
		$wpf_loadcss = wpf_get_option( 'wp-forecast-loadcss' );
		if ( 1 == $wpf_loadcss ) {
			return '';
		}

		$def  = 'wp-forecast-default-nowp.css';
		$user = 'wp-forecast-nowp.css';

		if ( file_exists( WP_PLUGIN_DIR . '/wp-forecast/' . $user ) ) {
			$def = $user;
		}

		$res  = '';
		$file = WP_PLUGIN_DIR . "/wp-forecast/$def";
		$f    = fopen( $file, 'r' );

		if ( $f ) {
			$res = fread( $f, filesize( $file ) );
			fclose( $f );
		}

		return $res;
	}

	/**
	 * Returns the number's widget id used with wp-forecast
	 * maximum is 999999 :-)
	 *
	 * @param int $number number of widgets to use.
	 */
	function get_widget_id( $number ) {
		// if negative take the first id.
		if ( $number < 0 ) {
			return 'A';
		}

		// the first widgets use chars above we go with 0 padded numbers.
		if ( $number <= 25 ) {
			return substr( 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', $number, 1 );
		} else {
			return str_pad( $number, 6, '0', STR_PAD_LEFT );
		}
	}

	/**
	 * Function tries to determine the icon path for icon number ino.
	 *
	 * @param string $ino icon number.
	 */
	function find_icon( $ino ) {
		$path = WPF_PATH . '/icons/' . $ino;
		$ext  = '.gif';

		if ( file_exists( $path . '.gif' ) ) {
			$ext = '.gif';
		} elseif ( file_exists( $path . '.png' ) ) {
			$ext = '.png';
		} elseif ( file_exists( $path . '.jpg' ) ) {
			$ext = '.jpg';
		} elseif ( file_exists( $path . '.GIF' ) ) {
			$ext = '.GIF';
		} elseif ( file_exists( $path . '.PNG' ) ) {
			$ext = '.PNG';
		} elseif ( file_exists( $path . '.JPG' ) ) {
			$ext = '.JPG';
		} elseif ( file_exists( $path . '.jpeg' ) ) {
			$ext = '.jpeg';
		} elseif ( file_exists( $path . '.JPEG' ) ) {
			$ext = '.JPEG';
		} elseif ( file_exists( $path . '.svg' ) ) {
			$ext = '.svg';
		} elseif ( file_exists( $path . '.SVG' ) ) {
			$ext = '.SVG';
		}

		return $ino . $ext;
	}

	/**
	 * Translates the wind direction into another language
	 *
	 * @param string $wdir direction in degrees.
	 * @param string $tdom textdomain.
	 */
	function translate_winddir( $wdir, $tdom ) {
		// translate winddir char by char.
		$winddir = '';
		$l       = strlen( $wdir );
		for ( $i = 0;$i < $l;$i++ ) {
			$winddir = $winddir . wpf__( $wdir[ $i ], $tdom );
		}
		return $winddir;
	}

	/**
	 * Translates the wind direction in degree to a string.
	 *
	 * @param string $wdir direction in degrees.
	 * @param string $tdom passhtrough.
	 */
	function translate_winddir_degree( $wdir, $tdom ) {
		$dir = '-';
		if ( 0 <= $wdir ) {
			$dir = 'N';
		}
		if ( 11.25 <= $wdir ) {
			$dir = 'NNE';
		}
		if ( 33.75 <= $wdir ) {
			$dir = 'NE';
		}
		if ( 56.25 <= $wdir ) {
			$dir = 'ENE';
		}
		if ( 78.75 <= $wdir ) {
			$dir = 'E';
		}
		if ( 101.25 <= $wdir ) {
			$dir = 'ESE';
		}
		if ( 123.75 <= $wdir ) {
			$dir = 'SE';
		}
		if ( 146.25 <= $wdir ) {
			$dir = 'SSE';
		}
		if ( 168.75 <= $wdir ) {
			$dir = 'S';
		}
		if ( 191.25 <= $wdir ) {
			$dir = 'SSW';
		}
		if ( 213.75 <= $wdir ) {
			$dir = 'SW';
		}
		if ( 236.25 <= $wdir ) {
			$dir = 'WSW';
		}
		if ( 258.75 <= $wdir ) {
			$dir = 'W';
		}
		if ( 281.25 <= $wdir ) {
			$dir = 'WNW';
		}
		if ( 303.75 <= $wdir ) {
			$dir = 'NW';
		}
		if ( 326.25 <= $wdir ) {
			$dir = 'NNW';
		}
		if ( 348.75 <= $wdir ) {
			$dir = 'N';
		}

		return translate_winddir( $dir, $tdom );
	}
}


/*
  Functions to check the WordPress transport methods

  if wpf selected transport option is set to empty,
  then we are in probing mode and do not change the WordPress result
  else we only keep alive what was selected via admin dialog

*/

/**
 * Function to check if fsockopen is usesd as the transport.
 *
 * @param string $use default transport.
 * @param array  $args parameters.
 */
function wpf_check_fsockopen( $use, $args = array() ) {
	$sel_transport = wpf_get_option( 'wp-forecast-wp-transport' );
	if ( '' == $sel_transport || 'default' == $sel_transport ) {
		return $use;
	} elseif ( 'fsockopen' == $sel_transport ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Function to check if fopen is usesd as the transport.
 *
 * @param string $use default transport.
 * @param array  $args parameters.
 */
function wpf_check_fopen( $use, $args = array() ) {
	$sel_transport = wpf_get_option( 'wp-forecast-wp-transport' );
	if ( '' == $sel_transport || 'default' == $sel_transport ) {
		return $use;
	} elseif ( 'fopen' == $sel_transport ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Function to check if streams is usesd as the transport.
 *
 * @param string $use default transport.
 * @param array  $args parameters.
 */
function wpf_check_streams( $use, $args = array() ) {
	$sel_transport = wpf_get_option( 'wp-forecast-wp-transport' );
	if ( '' == $sel_transport || 'default' == $sel_transport ) {
		return $use;
	} elseif ( 'streams' == $sel_transport ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Function to check if extHTTP is usesd as the transport.
 *
 * @param string $use default transport.
 * @param array  $args parameters.
 */
function wpf_check_exthttp( $use, $args = array() ) {
	$sel_transport = wpf_get_option( 'wp-forecast-wp-transport' );
	if ( '' == $sel_transport || 'default' == $sel_transport ) {
		return $use;
	} elseif ( 'exthttp' == $sel_transport ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Function to check if curl is usesd as the transport.
 *
 * @param string $use default transport.
 * @param array  $args parameters.
 */
function wpf_check_curl( $use, $args = array() ) {
	$sel_transport = wpf_get_option( 'wp-forecast-wp-transport' );
	if ( '' == $sel_transport || 'default' == $sel_transport ) {
		return $use;
	} elseif ( 'curl' == $sel_transport ) {
		return true;
	} else {
		return false;
	}
}


if ( ! function_exists( 'get_wp_transports' ) ) {
	/**
	 * Function to get the list of supported transports ignoring
	 * any preset method.
	 */
	function get_wp_transports() {
		$tlist = array();

		// remove but store selected transport.
		$wp_transport = wpf_get_option( 'wp-forecast-wp-transport' );
		wpf_update_option( 'wp-forecast-wp-transport', 'default' );

		// get WordPress default transports.
		$wplist = array();

		// if ( true === WP_Http_ExtHttp::test( array() ) ).
		// $tlist[] = "exthttp";.

		if ( true === WP_Http_Fsockopen::test( array() ) ) {
			$tlist[] = 'fsockopen';
		}

		if ( true === WP_Http_Streams::test( array() ) ) {
			$tlist[] = 'streams';
		}

		// disabled fopen, since this class sends no headers.
		// if ( true === WP_Http_Fopen::test( array() ) ).
		// $tlist[] = "fopen";.

		if ( true === WP_Http_Curl::test( array() ) ) {
			$tlist[] = 'curl';
		}

		// write back selected transport.
		wpf_update_option( 'wp-forecast-wp-transport', $wp_transport );

		return $tlist;
	}
}

/**
 * Function to turn on/off wp-forecast preselected transport.
 *
 * @param bool $sw switch the preselected transport.
 */
function switch_wpf_transport( $sw ) {
	$wptrans = 'default';

	if ( true == $sw ) {
		$wptrans = wpf_get_option( 'wp-forecast-pre-transport' );
	}

	update_option( 'wp-forecast-wp-transport', $wptrans );
}

/**
 * Function for plugin_locale filter hook.
 * only returns the first parameter.
 *
 * @param string $locale iso code of the locale.
 * @param string $domain complete domain of the .mo file.
 */
function wpf_lplug( $locale, $domain ) {
	// extract locale from domain.
	$wpf_locale = substr( $domain, 12, 5 );
	return $wpf_locale;
}

/**
 * Return an array containgin all allowed HTML tags and attributes
 */
function wpf_allowed_tags() {

	$allowed_atts = array(
		'align'      => array(),
		'class'      => array(),
		'type'       => array(),
		'id'         => array(),
		'dir'        => array(),
		'lang'       => array(),
		'style'      => array(),
		'xml:lang'   => array(),
		'src'        => array(),
		'alt'        => array(),
		'href'       => array(),
		'rel'        => array(),
		'rev'        => array(),
		'target'     => array(),
		'novalidate' => array(),
		'type'       => array(),
		'value'      => array(),
		'name'       => array(),
		'tabindex'   => array(),
		'action'     => array(),
		'method'     => array(),
		'for'        => array(),
		'width'      => array(),
		'height'     => array(),
		'data'       => array(),
		'title'      => array(),
		'maxlength'  => array(),
		'border'     => array(),
		'onclick'    => array(),
		'checked'    => array(),
		'selected'   => array(),
		'size'       => array(),
		'onchange'   => array(),
	);

	$allowedposttags['aside']    = $allowed_atts;
	$allowedposttags['section']  = $allowed_atts;
	$allowedposttags['nav']      = $allowed_atts;
	$allowedposttags['form']     = $allowed_atts;
	$allowedposttags['label']    = $allowed_atts;
	$allowedposttags['input']    = $allowed_atts;
	$allowedposttags['textarea'] = $allowed_atts;
	$allowedposttags['select']   = $allowed_atts;
	$allowedposttags['option']   = $allowed_atts;
	$allowedposttags['iframe']   = $allowed_atts;
	$allowedposttags['script']   = $allowed_atts;
	$allowedposttags['style']    = $allowed_atts;
	$allowedposttags['strong']   = $allowed_atts;
	$allowedposttags['small']    = $allowed_atts;
	$allowedposttags['table']    = $allowed_atts;
	$allowedposttags['span']     = $allowed_atts;
	$allowedposttags['abbr']     = $allowed_atts;
	$allowedposttags['code']     = $allowed_atts;
	$allowedposttags['pre']      = $allowed_atts;
	$allowedposttags['div']      = $allowed_atts;
	$allowedposttags['img']      = $allowed_atts;
	$allowedposttags['h1']       = $allowed_atts;
	$allowedposttags['h2']       = $allowed_atts;
	$allowedposttags['h3']       = $allowed_atts;
	$allowedposttags['h4']       = $allowed_atts;
	$allowedposttags['h5']       = $allowed_atts;
	$allowedposttags['h6']       = $allowed_atts;
	$allowedposttags['ol']       = $allowed_atts;
	$allowedposttags['ul']       = $allowed_atts;
	$allowedposttags['li']       = $allowed_atts;
	$allowedposttags['em']       = $allowed_atts;
	$allowedposttags['hr']       = $allowed_atts;
	$allowedposttags['br']       = $allowed_atts;
	$allowedposttags['tr']       = $allowed_atts;
	$allowedposttags['td']       = $allowed_atts;
	$allowedposttags['p']        = $allowed_atts;
	$allowedposttags['a']        = $allowed_atts;
	$allowedposttags['b']        = $allowed_atts;
	$allowedposttags['i']        = $allowed_atts;

	return $allowedposttags;
}

/**
 * Function to translate a string into another language
 * using the wpf own translation mechanism
 * this is necessary because WordPress does not allow
 * more than one language per plugin
 *
 * @param string $s string to translate.
 * @param string $d domain to translate to.
 */
function wpf__( $s, $d ) {
	global $wpf_lang_dict;

	if ( ! isset( $wpf_lang_dict ) ) {
		$wpf_lang_dict = array();
	}

	if ( array_key_exists( $d, $wpf_lang_dict ) && isset( $wpf_lang_dict[ $d ] ) ) {
		if ( array_key_exists( $s, $wpf_lang_dict[ $d ] ) ) {
			return $wpf_lang_dict[ $d ][ $s ];
		}
	}

	// nothing found return original.
	return $s;
}

/**
 * Function to translate a string into another language
 * using the wpf own translation mechanism
 * this is necessary because WordPress does not allow
 * more than one language per plugin
 * This one echos the translation.
 *
 * @param string $s string to translate.
 * @param string $d domain to translate to.
 */
function wpf_e( $s, $d ) {
	echo esc_attr( wpf__( $s, $d ) );
}

/**
 * Function to translate a string into another language
 * using the wpf own translation mechanism
 * this is necessary because WordPress does not allow
 * more than one language per plugin
 * This one escape it too
 *
 * @param string $s string to translate.
 * @param string $d domain to translate to.
 */
function wpf_esc_attr__( $s, $d ) {
	return esc_attr( wpf__( $s, $d ) );
}

/**
 * Function to translate a string into another language
 * using the wpf own translation mechanism
 * this is necessary because WordPress does not allow
 * more than one language per plugin
 * This one escapes and echos the translation.
 *
 * @param string $s string to translate.
 * @param string $d domain to translate to.
 */
function wpf_esc_attr_e( $s, $d ) {
	echo esc_attr( wpf__( $s, $d ) );
}

/**
 * Echos the admin notice concerning the accuweather probem.
 */
function wpf_accuweather_problem_notice() {
	$flag = get_option( 'wpf-show-admin-notice' );
	if ( false === $flag ) {
		$out  = '<div class="notice is-dismissible"><p>';
		$out .= __(
			'As of September 10, 2023 it seems Accuweather has discontinued the free api-service. 
			Please switch to either Openweathermap or Open-Meteo instead. 
			For use with OpenWeatherMap.org you will need a personal API-Key and have to register at their site.
			All your Accuweather Widgets have been set to Open-Meteo to avoid errors on your website. Please check if all settings are correct.',
			'wp-forecast'
		);
		$out .= '</p></div>';
		echo wp_kses( $out, wpf_allowed_tags() );
		update_option( 'wpf-show-admin-notice', 1 );
	}
}
