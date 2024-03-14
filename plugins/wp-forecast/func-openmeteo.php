<?php
/** This file is part of the wp-forecast plugin for WordPress
 *
 * Copyright 2023  Hans Matzen  (email : webmaster at tuxlog dot de)
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

if ( ! function_exists( 'openmeteo_get_weather' ) ) {
	/**
	 * Get the data from OpenMeteo.
	 *
	 * @param strin  $baseuri  the Base URI for OM.
	 * @param string $lat      the latitude of the location.
	 * @param string $lon      the longitude of the location.
	 * @param string $metric   flag for getting metric values from OpenMeteo.
	 */
	function openmeteo_get_weather( $baseuri, $lat, $lon, $metric ) {
		$data = array();

		$urlparms = '&hourly=relativehumidity_2m,apparent_temperature,pressure_msl,uv_index&daily=weathercode,temperature_2m_max,temperature_2m_min,apparent_temperature_max,apparent_temperature_min,sunrise,sunset,uv_index_max,uv_index_clear_sky_max,precipitation_sum,rain_sum,showers_sum,snowfall_sum,precipitation_hours,precipitation_probability_max,windspeed_10m_max,windgusts_10m_max,winddirection_10m_dominant&current_weather=true&timeformat=unixtime&timezone=auto';

		// add non metric parms to url.
		if ( 1 != $metric ) {
			$urlparms .= '&temperature_unit=fahrenheit&windspeed_unit=mph&precipitation_unit=inch';
		}

		// check parms.
		if ( trim( $lat ) == '' || trim( $lon ) == '' ) {
			return array();
		}
		$url = $baseuri . 'latitude=' . $lat . '&longitude=' . $lon . $urlparms;

		// Open the file and decode it.
		try {
			$response = wp_remote_get( $url );
			if ( ! is_wp_error( $response ) && ( 400 > wp_remote_retrieve_response_code( $response ) ) ) {
				if ( json_last_error() === JSON_ERROR_NONE ) {
					$data = json_decode( $response['body'], true );
				}
			}
		} catch ( Exception $ex ) {
			error_log( print_r( $ex, true ) );
		}

		return $data;
	}
}


if ( ! function_exists( 'openmeteo_get_data' ) ) {
	/**
	 * Extract the weather data from json object returned from OM.
	 *
	 * @param array $weather_array the array to store the weather.
	 * @param array $wpf_vars       the parameters of the weather widget.
	 */
	function openmeteo_get_data( $weather_array, $wpf_vars ) {
		$w = array();

		if ( '1' == $wpf_vars['metric'] ) {
			$w['un_temp']  = 'C';
			$w['un_dist']  = 'km';
			$w['un_speed'] = 'm/s';
			$w['un_pres']  = 'mb';
			$w['un_prec']  = 'mm';
		} else {
			$w['un_temp']  = 'F';
			$w['un_dist']  = 'mi';
			$w['un_speed'] = 'mph';
			$w['un_pres']  = 'mb';
			$w['un_prec']  = 'in';
		}

		if ( ! isset( $weather_array['current_weather'] ) || ! isset( $weather_array['daily'] ) ) {
			$w['failure'] = 'No OpenMeteo data available. ';
			if ( isset( $weather_array['message'] ) ) {
				$w['failure'] .= $weather_array['message'];
			}
			return $w;
		}

		$w['lat']      = $weather_array['latitude'];
		$w['lon']      = $weather_array['longitude'];
		$w['time']     = $weather_array['current_weather']['time'];
		$w['timezone'] = $weather_array['timezone'];
		$mtz           = new DateTimeZone( $w['timezone'] );

		// values from hourly forecast.
		$hourlyindex = 0;
		for ( $i = 0; $i <= 165; $i++ ) {
			if ( $weather_array['current_weather']['time'] == $weather_array['hourly']['time'][ $i ] ) {
				$hourlyindex = $i;
				break;
			}
		}

		$w['pressure'] = $weather_array['hourly']['pressure_msl'][ $hourlyindex ];
		$w['humidity'] = $weather_array['hourly']['relativehumidity_2m'][ $hourlyindex ];
		$w['realfeel'] = round( $weather_array['hourly']['apparent_temperature'][ $hourlyindex ], 0 );
		$w['uvindex']  = $weather_array['hourly']['uv_index'][ $hourlyindex ];

		// values from current_weather and daily.
		$w['temperature']   = round( $weather_array['current_weather']['temperature'], 0 );
		$w['weathertext']   = openmeteo_wmocode2text( $weather_array['current_weather']['weathercode'] );
		$w['weathericon']   = openmeteo_map_icon( $weather_array['current_weather']['weathercode'] );
		$w['weatherid']     = $weather_array['current_weather']['weathercode'];
		$w['wgusts']        = round( $weather_array['daily']['windgusts_10m_max'][0] / 3.6, 1 );  // convert from kmh to ms.
		$w['windspeed']     = round( $weather_array['current_weather']['windspeed'] / 3.6, 1 );   // convert from kmh to ms.
		$w['winddirection'] = $weather_array['current_weather']['winddirection'];

		// map precipitation values.
		$w['precipProbability'] = $weather_array['daily']['precipitation_probability_max'][0];
		$w['precipIntensity']   = $weather_array['daily']['precipitation_sum'][0];
		$w['precipType']        = 'Rain';

		// if it snows set precipitation type to snow.
		if ( $weather_array['daily']['snowfall_sum'][0] > 0 ) {
			$w['precipType'] = 'Snow';
		}

		// sunset sunrise.
		$sr = new DateTime();
		$sr->setTimezone( $mtz );
		$sr->setTimestamp( $weather_array['daily']['sunrise'][0] );
		$w['sunrise'] = $sr->format( get_option( 'time_format' ) );

		$ss = new DateTime();
		$ss->setTimezone( $mtz );
		$ss->setTimestamp( $weather_array['daily']['sunset'][0] );
		$w['sunset'] = $ss->format( get_option( 'time_format' ) );

		// get forecast data.
		for ( $i = 0;$i <= 6;$i++ ) {

			$j   = $i + 1;
			$odt = new DateTime();
			$odt->setTimezone( $mtz );

			$w[ 'fc_obsdate_' . $j ]      = $weather_array['daily']['time'][ $i ] + $odt->getOffset();
			$w[ 'fc_dt_short_' . $j ]     = openmeteo_wmocode2text( $weather_array['daily']['weathercode'][ $i ] );
			$w[ 'fc_dt_icon_' . $j ]      = openmeteo_map_icon( $weather_array['daily']['weathercode'][ $i ] );
			$w[ 'fc_dt_id_' . $j ]        = $weather_array['daily']['weathercode'][ $i ];
			$w[ 'fc_dt_htemp_' . $j ]     = round( $weather_array['daily']['temperature_2m_max'][ $i ], 0 );
			$w[ 'fc_dt_ltemp_' . $j ]     = round( $weather_array['daily']['temperature_2m_min'][ $i ], 0 );
			$w[ 'fc_dt_windspeed_' . $j ] = round( $weather_array['daily']['windspeed_10m_max'][ $i ] / 3.6, 1 ); // convert from kmh to ms.
			$w[ 'fc_dt_winddir_' . $j ]   = $weather_array['daily']['winddirection_10m_dominant'][ $i ];
			$w[ 'fc_dt_wgusts_' . $j ]    = round( $weather_array['daily']['windgusts_10m_max'][ $i ] / 3.6, 1 ); // convert from kmh to ms.
			$w[ 'fc_dt_maxuv_' . $j ]     = $weather_array['daily']['uv_index_max'][ $i ];
			$w[ 'fc_nt_icon_' . $j ]      = openmeteo_map_icon( $weather_array['daily']['weathercode'][ $i ] );
			$w[ 'fc_nt_id_' . $j ]        = $weather_array['daily']['weathercode'][ $i ];
			$w[ 'fc_nt_htemp_' . $j ]     = round( $weather_array['daily']['temperature_2m_max'][ $i ], 0 );
			$w[ 'fc_nt_ltemp_' . $j ]     = round( $weather_array['daily']['temperature_2m_min'][ $i ], 0 );
			$w[ 'fc_nt_windspeed_' . $j ] = round( $weather_array['daily']['windspeed_10m_max'][ $i ] / 3.6, 1 ); // convert from kmh to ms.
			$w[ 'fc_nt_winddir_' . $j ]   = $weather_array['daily']['winddirection_10m_dominant'][ $i ];
			$w[ 'fc_nt_wgusts_' . $j ]    = round( $weather_array['daily']['windgusts_10m_max'][ $i ] / 3.6, 1 ); // convert from kmh to ms.
			$w[ 'fc_nt_maxuv_' . $j ]     = $weather_array['daily']['uv_index_max'][ $i ];

			// map precipitation values.
			// init vars.
			$w[ 'fc_dt_precipProbability' . $j ] = $weather_array['daily']['precipitation_probability_max'][ $i ];
			$w[ 'fc_dt_precipIntensity' . $j ]   = $weather_array['daily']['precipitation_sum'][ $i ];
			$w[ 'fc_dt_precipType' . $j ]        = __( 'Rain', 'xxxdummy' );

			// if it snows set precipitation type to snow.
			if ( $weather_array['daily']['snowfall_sum'][ $i ] > 0 ) {
				$w[ 'fc_dt_precipType' . $j ] = __( 'Snow', 'xxxdummy' );
			}

			// convert mm to inches for compatibility reasons with accuweather.
			$w[ 'fc_dt_precipIntensity' . $j ] = $w[ 'fc_dt_precipIntensity' . $j ] / 2.54 / 10;
		}

		// fill failure anyway.
		$w['failure'] = ( isset( $w['failure'] ) ? $w['failure'] : '' );

		return $w;
	}
}

if ( ! function_exists( 'openmeteo_forecast_data' ) ) {
	/**
	 * Return the weather data for the cache from OM
	 *
	 * @param string $wpfcid            the Widget ID.
	 * @param string $language_override the iso code of the language to use.
	 */
	function openmeteo_forecast_data( $wpfcid = 'A', $language_override = null ) {

		$wpf_vars = get_wpf_opts( $wpfcid );
		if ( ! empty( $language_override ) ) {
			$wpf_vars['wpf_language'] = $language_override;
		}

		$w = maybe_unserialize( wpf_get_option( 'wp-forecast-cache' . $wpfcid ) );

		// get translations.
		global $wpf_lang_dict;
		$wpf_lang = array();
		$langfile = WP_PLUGIN_DIR . '/wp-forecast/widgetlang/wp-forecast-' . strtolower( str_replace( '_', '-', $wpf_vars['wpf_language'] ) ) . '.php';
		if ( file_exists( $langfile ) ) {
			include $langfile;
		}
		$wpf_lang_dict[ $wpf_vars['wpf_language'] ] = $wpf_lang;

		// --------------------------------------------------------------
		// calc values for current conditions.
		if ( isset( $w['failure'] ) && '' != $w['failure'] ) {
			return array( 'failure' => $w['failure'] );
		}

		$w['servicelink'] = 'https://open-meteo.com';
		$w['copyright']   = '<a href="https://open-meteo.com">&copy; ' . gmdate( 'Y' ) . ' Powered by Open-Meteo</a>';

		// next line is for compatibility.
		$w['acculink'] = $w['servicelink'];
		$w['location'] = $wpf_vars['locname'];
		$w['locname']  = $w['location'];

		// handle empty timezone setting.
		if ( ! isset( $w['timezone'] ) ) {
			$w['timezone'] = get_option( 'timezone_string' );
		}

		$tz           = new DateTimeZone( $w['timezone'] );
		$w['gmtdiff'] = $tz->getOffset( new DateTime() );

		// calculate blog date and time.
		$ct            = current_time( 'U' );
		$ct            = $ct + $wpf_vars['timeoffset'] * 60; // add or subtract time offset.
		$w['blogdate'] = date_i18n( $wpf_vars['fc_date_format'], $ct );
		$w['blogtime'] = date_i18n( $wpf_vars['fc_time_format'], $ct );

		// calculate date/time from openmeteo.
		$ct            = $w['time'] + $w['gmtdiff'];
		$w['accudate'] = date_i18n( $wpf_vars['fc_date_format'], $ct );
		$w['accutime'] = date_i18n( $wpf_vars['fc_time_format'], $ct );

		$ico            = openmeteo_map_icon( $w['weatherid'], false );
		$iconfile       = find_icon( $ico );
		$w['icon']      = 'icons/' . $iconfile;
		$w['iconcode']  = $ico;
		$w['shorttext'] = openmeteo_wmocode2text( $w['weatherid'] );

		$w['temperature'] = $w['temperature'] . '&deg;' . $w['un_temp'];
		$w['realfeel']    = $w['realfeel'] . '&deg;' . $w['un_temp'];
		$w['humidity']    = round( $w['humidity'], 0 );

		// workaround different pressure values returned by accuweather.
		$press = round( $w['pressure'], 0 );
		if ( strlen( $press ) == 3 && substr( $press, 0, 1 ) == '1' ) {
			$press = $press * 10;
		}
		$w['pressure']     = $press . ' ' . $w['un_pres'];
		$w['humidity']     = round( $w['humidity'], 0 );
		$w['windspeed']    = windstr( $wpf_vars['metric'], $w['windspeed'], $wpf_vars['windunit'] );
		$w['winddir']      = translate_winddir_degree( $w['winddirection'], $wpf_vars['wpf_language'] );
		$w['winddir_orig'] = str_replace( 'O', 'E', $w['winddir'] );
		$w['windgusts']    = windstr( $wpf_vars['metric'], $w['wgusts'], $wpf_vars['windunit'] );

		// calc values for forecast.
		for ( $i = 1; $i < 7; $i++ ) {
			// daytime forecast.
			$w[ 'fc_obsdate_' . $i ] = date_i18n( $wpf_vars['fc_date_format'], $w[ 'fc_obsdate_' . $i ] );

			$ico                             = openmeteo_map_icon( $w[ 'fc_dt_id_' . $i ], false );
			$iconfile                        = find_icon( $ico );
			$w[ 'fc_dt_icon_' . $i ]         = 'icons/' . $iconfile;
			$w[ 'fc_dt_iconcode_' . $i ]     = $ico;
			$w[ 'fc_dt_desc_' . $i ]         = openmeteo_wmocode2text( $w[ 'fc_dt_id_' . $i ] );
			$w[ 'fc_dt_htemp_' . $i ]        = $w[ 'fc_dt_htemp_' . $i ] . '&deg;' . $w['un_temp'];
			$wstr                            = windstr( $wpf_vars['metric'], $w[ 'fc_dt_windspeed_' . $i ], $wpf_vars['windunit'] );
			$w[ 'fc_dt_windspeed_' . $i ]    = $wstr;
			$w[ 'fc_dt_winddir_' . $i ]      = translate_winddir_degree( $w[ 'fc_dt_winddir_' . $i ], $wpf_vars['wpf_language'] );
			$w[ 'fc_dt_winddir_orig_' . $i ] = str_replace( 'O', 'E', $w[ 'fc_dt_winddir_' . $i ] );
			$w[ 'fc_dt_wgusts_' . $i ]       = windstr( $wpf_vars['metric'], $w[ 'fc_dt_wgusts_' . $i ], $wpf_vars['windunit'] );
			$w[ 'fc_dt_maxuv_' . $i ]        = $w[ 'fc_dt_maxuv_' . $i ];

			// nighttime forecast.
			$ico                             = openmeteo_map_icon( $w[ 'fc_nt_id_' . $i ], true );
			$iconfile                        = find_icon( $ico );
			$w[ 'fc_nt_icon_' . $i ]         = 'icons/' . $iconfile;
			$w[ 'fc_nt_iconcode_' . $i ]     = $ico;
			$w[ 'fc_nt_desc_' . $i ]         = openmeteo_wmocode2text( $w[ 'fc_nt_id_' . $i ] );
			$w[ 'fc_nt_ltemp_' . $i ]        = $w[ 'fc_nt_ltemp_' . $i ] . '&deg;' . $w['un_temp'];
			$wstr                            = windstr( $wpf_vars['metric'], $w[ 'fc_nt_windspeed_' . $i ], $wpf_vars['windunit'] );
			$w[ 'fc_nt_windspeed_' . $i ]    = $wstr;
			$w[ 'fc_nt_winddir_' . $i ]      = translate_winddir_degree( $w[ 'fc_nt_winddir_' . $i ], $wpf_vars['wpf_language'] );
			$w[ 'fc_nt_winddir_orig_' . $i ] = str_replace( 'O', 'E', $w[ 'fc_nt_winddir_' . $i ] );
			$w[ 'fc_nt_wgusts_' . $i ]       = windstr( $wpf_vars['metric'], $w[ 'fc_nt_wgusts_' . $i ], $wpf_vars['windunit'] );
			$w[ 'fc_nt_maxuv_' . $i ]        = $w[ 'fc_nt_maxuv_' . $i ];
		}

		// add hook for possible individual changes.
		$w = apply_filters( 'wp-forecast-open-meteo-data', $w );

		return $w;
	}
}

if ( ! function_exists( 'openmeteo_map_icon' ) ) {
	/**
	 * Function to map the weather code to a weather icon
	 *
	 * @param string $weatherid the id of the weather condition.
	 * @param bool   $night     the parameter to say if it is night or not.
	 */
	function openmeteo_map_icon( $weatherid, $night = false ) {
		/*
		 Icon mapping from OpenMeteo
		 */

		/*
		WMO Weather interpretation codes (WW)
		Code		Description
		0			Clear sky
		1, 2, 3		Mainly clear, partly cloudy, and overcast
		45, 48		Fog and depositing rime fog
		51, 53, 55	Drizzle: Light, moderate, and dense intensity
		56, 57		Freezing Drizzle: Light and dense intensity
		61, 63, 65	Rain: Slight, moderate and heavy intensity
		66, 67		Freezing Rain: Light and heavy intensity
		71, 73, 75	Snow fall: Slight, moderate, and heavy intensity
		77			Snow grains
		80, 81, 82	Rain showers: Slight, moderate, and violent
		85, 86		Snow showers slight and heavy
		95 *		Thunderstorm: Slight or moderate
		96, 99 *	Thunderstorm with slight and heavy hail
		(*) Thunderstorm forecast with hail is only available in Central Europe
		*/

		/* defaul clear sky */
		$icon = '01';

		if ( $night ) {
			// night icon mapping.
			$ico_arr = array(
				0  => '33',
				1  => '34',
				2  => '35',
				3  => '38',
				45 => '11',
				48 => '11',
				51 => '25',
				53 => '24',
				55 => '26',
				56 => '44',
				57 => '44',
				61 => '12',
				63 => '40',
				65 => '40',
				66 => '44',
				67 => '26',
				71 => '19',
				73 => '22',
				75 => '22',
				77 => '22',
				80 => '12',
				81 => '40',
				82 => '40',
				85 => '19',
				86 => '22',
				95 => '41',
				96 => '42',
				99 => '15',
			);
		} else {
			// day icon mapping.
			$ico_arr = array(
				0  => '01',
				1  => '03',
				2  => '04',
				3  => '07',
				45 => '11',
				48 => '11',
				51 => '25',
				53 => '24',
				55 => '26',
				56 => '29',
				57 => '29',
				61 => '12',
				63 => '18',
				65 => '18',
				66 => '29',
				67 => '26',
				71 => '19',
				73 => '22',
				75 => '22',
				77 => '22',
				80 => '12',
				81 => '18',
				82 => '18',
				85 => '19',
				86 => '22',
				95 => '17',
				96 => '16',
				99 => '15',
			);
		}

		return $ico_arr[ $weatherid ];
	}
}

if ( ! function_exists( 'openmeteo_wmocode2text' ) ) {
	/**
	 * Return the weather data for the cache from OM
	 *
	 * @param string $wmocode the WMO code for the weather condition.
	 */
	function openmeteo_wmocode2text( $wmocode ) {
		/*
		WMO Weather interpretation codes (WW)
		Code		Description
		0			Clear sky
		1, 2, 3		Mainly clear, partly cloudy, and overcast
		45, 48		Fog and depositing rime fog
		51, 53, 55	Drizzle: Light, moderate, and dense intensity
		56, 57		Freezing Drizzle: Light and dense intensity
		61, 63, 65	Rain: Slight, moderate and heavy intensity
		66, 67		Freezing Rain: Light and heavy intensity
		71, 73, 75	Snow fall: Slight, moderate, and heavy intensity
		77			Snow grains
		80, 81, 82	Rain showers: Slight, moderate, and violent
		85, 86		Snow showers slight and heavy
		95 *		Thunderstorm: Slight or moderate
		96, 99 *	Thunderstorm with slight and heavy hail
		(*) Thunderstorm forecast with hail is only available in Central Europe
		*
		* We use an textdomain dummy here, to avoid translation but make the strings fetchable for xgettext.
		*/

		$c = ''; // the short description for the weather condition.

		switch ( $wmocode ) {
			case 0:
				$c = __( 'Clear sky', 'xxxdummy' );
				break;
			case 1:
				$c = __( 'Mainly clear', 'xxxdummy' );
				break;
			case 2:
				$c = __( 'Partly cloudy', 'xxxdummy' );
				break;
			case 3:
				$c = __( 'Overcast', 'xxxdummy' );
				break;
			case 45:
				$c = __( 'Fog', 'xxxdummya' );
				break;
			case 48:
				$c = __( 'Rime fog', 'xxxdummy' );
				break;
			case 51:
				$c = __( 'Light drizzle', 'xxxdummy' );
				break;
			case 53:
				$c = __( 'Moderate drizzle', 'xxxdummy' );
				break;
			case 55:
				$c = __( 'Dense drizzle', 'xxxdummy' );
				break;
			case 56:
				$c = __( 'Light freezing drizzle', 'xxxdummy' );
				break;
			case 57:
				$c = __( 'Dense freezing drizzle', 'xxxdummy' );
				break;
			case 61:
				$c = __( 'Slight rain', 'xxxdummy' );
				break;
			case 63:
				$c = __( 'Moderate rain', 'xxxdummy' );
				break;
			case 65:
				$c = __( 'Heavy rain', 'xxxdummy' );
				break;
			case 66:
				$c = __( 'Light freezing rain', 'xxxdummy' );
				break;
			case 67:
				$c = __( 'Heavy freezing rain', 'xxxdummy' );
				break;
			case 71:
				$c = __( 'Slight snow fall', 'xxxdummy' );
				break;
			case 73:
				$c = __( 'Moderate snow fall', 'xxxdummy' );
				break;
			case 75:
				$c = __( 'Heavy snow fall', 'xxxdummy' );
				break;
			case 77:
				$c = __( 'Snow grains', 'xxxdummy' );
				break;
			case 80:
				$c = __( 'Slight rain showers', 'xxxdummy' );
				break;
			case 81:
				$c = __( 'Moderate rain showers', 'xxxdummy' );
				break;
			case 82:
				$c = __( 'Violent rain showers', 'xxxdummy' );
				break;
			case 85:
				$c = __( 'Slight snow showers', 'xxxdummy' );
				break;
			case 86:
				$c = __( 'Heavy snow showers', 'xxxdummy' );
				break;
			case 95:
				$c = __( 'Thunderstorm', 'xxxdummy' );
				break;
			case 96:
				$c = __( 'Thunderstorm with light hail', 'xxxdummy' );
				break;
			case 99:
				$c = __( 'Thunderstorm with heavy hail', 'xxxdummy' );
				break;

			default:
				$c = __( 'Clear sky', 'xxxdummy' );
				break;
		}

		return $c;
	}
}
