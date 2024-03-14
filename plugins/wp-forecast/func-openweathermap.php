<?php
/** This file is part of the wp-forecast plugin for WordPress
 *
 * Copyright 2021  Hans Matzen  (email : webmaster at tuxlog dot de)
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

if ( ! function_exists( 'openweathermap_get_weather' ) ) {
	/**
	 * Get the data from OpenWeatherMap.
	 *
	 * @param strin  $baseuri  the Base URI for OWM.
	 * @param string $apikey   the apikey from OWM.
	 * @param string $lat      the latitude of the location.
	 * @param string $lon      the longitude of the location.
	 * @param string $metric   the units to us.
	 */
	function openweathermap_get_weather( $baseuri, $apikey, $lat, $lon, $metric ) {
		$data = array();

		if ( '1' == $metric ) {
			$metric = 'metric';
		} else {
			$metric = 'imperial';
		}

		// check parms.
		if ( trim( $apikey ) == '' || trim( $lat ) == '' || trim( $lon ) == '' ) {
			return array();
		}
		$url1 = $baseuri . 'lat=' . $lat . '&lon=' . $lon . '&appid=' . $apikey . '&exclude=minutely,hourly&units=' . $metric . '&lang=en';
		// Open the file and decode it.
		$file1 = wp_remote_get( $url1 );
		if ( ! is_wp_error( $file1 ) ) {
			$data = json_decode( $file1['body'], true );
		}

		return $data;
	}
}


if ( ! function_exists( 'openweathermap_get_data' ) ) {
	/**
	 * Extract the weather data from json object returned from OWM.
	 *
	 * @param array $weather_array the array to store the weather.
	 * @param array $wpf_vars       the parameters of the weather widget.
	 */
	function openweathermap_get_data( $weather_array, $wpf_vars ) {
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

		if ( ! isset( $weather_array['current'] ) || ! isset( $weather_array['daily'] ) ) {
			$w['failure'] = 'No OpenWeathermap data available. ';
			if ( isset( $weather_array['message'] ) ) {
				$w['failure'] .= $weather_array['message'];
			}
			return $w;
		}

		$w['lat']      = $weather_array['lat'];
		$w['lon']      = $weather_array['lon'];
		$w['time']     = $weather_array['current']['dt'];
		$w['timezone'] = $weather_array['timezone'];
		$mtz           = new DateTimeZone( $w['timezone'] );

		// current conditions.
		$w['pressure']      = $weather_array['current']['pressure'];
		$w['temperature']   = round( $weather_array['current']['temp'], 0 );
		$w['realfeel']      = round( $weather_array['current']['feels_like'], 0 );
		$w['humidity']      = $weather_array['current']['humidity'];
		$w['weathertext']   = $weather_array['current']['weather'][0]['description'];
		$w['weathericon']   = $weather_array['current']['weather'][0]['icon'];
		$w['weatherid']     = $weather_array['current']['weather'][0]['id'];
		$w['wgusts']        = $weather_array['daily'][0]['wind_gust'];
		$w['windspeed']     = $weather_array['current']['wind_speed'];
		$w['winddirection'] = $weather_array['current']['wind_deg'];
		$w['uvindex']       = $weather_array['current']['uvi'];

		// map precipitation values.
		// init vars.
		$w['precipProbability'] = 0;
		$w['precipIntensity']   = 0;
		$w['precipType']        = '';
		// if it rains add rain volume and set precipitation type to rain.
		if ( isset( $weather_array['current']['rain'] ) ) {
			$w['precipIntensity'] += $weather_array['current']['rain']['1h'];
			$w['precipType']       = 'Rain';
		}
		// if it snows add snow volume and set precipitation type to snow.
		if ( isset( $weather_array['current']['snow'] ) ) {
			$w['precipIntensity'] += $weather_array['current']['snow']['1h'];
			$w['precipType']       = 'Snow';
		}
		// convert mm to inches for compatibility reasons with accuweather.
		$w['precipIntensity'] = $w['precipIntensity'] / 2.54 / 10;

		if ( $w['precipIntensity'] > 0 ) {
			$w['precipProbability'] = 100;
		}

		// sunset sunrise.
		$sr = new DateTime();
		$sr->setTimezone( $mtz );
		$sr->setTimestamp( $weather_array['daily']['0']['sunrise'] );
		$w['sunrise'] = $sr->format( get_option( 'time_format' ) );

		$ss = new DateTime();
		$ss->setTimezone( $mtz );
		$ss->setTimestamp( $weather_array['daily']['0']['sunset'] );
		$w['sunset'] = $ss->format( get_option( 'time_format' ) );

		// forecast.
		for ( $i = 0;$i <= 7;$i++ ) {

			$j   = $i + 1;
			$odt = new DateTime();
			$odt->setTimezone( $mtz );

			$w[ 'fc_obsdate_' . $j ]      = $weather_array['daily'][ $i ]['dt'] + $odt->getOffset();
			$w[ 'fc_dt_short_' . $j ]     = $weather_array['daily'][ $i ]['weather'][0]['description'];
			$w[ 'fc_dt_icon_' . $j ]      = $weather_array['daily'][ $i ]['weather'][0]['icon'];
			$w[ 'fc_dt_id_' . $j ]        = $weather_array['daily'][ $i ]['weather'][0]['id'];
			$w[ 'fc_dt_htemp_' . $j ]     = round( $weather_array['daily'][ $i ]['temp']['day'], 0 );
			$w[ 'fc_dt_ltemp_' . $j ]     = round( $weather_array['daily'][ $i ]['temp']['day'], 0 );
			$w[ 'fc_dt_windspeed_' . $j ] = $weather_array['daily'][ $i ]['wind_speed'];
			$w[ 'fc_dt_winddir_' . $j ]   = $weather_array['daily'][ $i ]['wind_deg'];
			$w[ 'fc_dt_wgusts_' . $j ]    = $weather_array['daily'][ $i ]['wind_gust'];
			$w[ 'fc_dt_maxuv_' . $j ]     = $weather_array['daily'][ $i ]['uvi'];
			$w[ 'fc_nt_icon_' . $j ]      = $weather_array['daily'][ $i ]['weather'][0]['icon'];
			$w[ 'fc_nt_id_' . $j ]        = $weather_array['daily'][ $i ]['weather'][0]['id'];
			$w[ 'fc_nt_htemp_' . $j ]     = round( $weather_array['daily'][ $i ]['temp']['night'], 0 );
			$w[ 'fc_nt_ltemp_' . $j ]     = round( $weather_array['daily'][ $i ]['temp']['night'], 0 );
			$w[ 'fc_nt_windspeed_' . $j ] = $weather_array['daily'][ $i ]['wind_speed'];
			$w[ 'fc_nt_winddir_' . $j ]   = $weather_array['daily'][ $i ]['wind_deg'];
			$w[ 'fc_nt_wgusts_' . $j ]    = $weather_array['daily'][ $i ]['wind_gust'];
			$w[ 'fc_nt_maxuv_' . $j ]     = $weather_array['daily'][ $i ]['uvi'];

			// map precipitation values.
			// init vars.
			$w[ 'fc_dt_precipProbability' . $j ] = $weather_array['daily'][ $i ]['pop'] * 100;
			$w[ 'fc_dt_precipIntensity' . $j ]   = 0;
			$w[ 'fc_dt_precipType' . $j ]        = '';
			// if it rains add rain volume and set precipitation type to rain.
			if ( isset( $weather_array['daily'][ $i ]['rain'] ) ) {
				$w[ 'fc_dt_precipIntensity' . $j ] += $weather_array['daily'][ $i ]['rain'];
				$w[ 'fc_dt_precipType' . $j ]       = 'Rain';
			}
			// if it snows add snow volume and set precipitation type to snow.
			if ( isset( $weather_array['daily'][ $i ]['snow'] ) ) {
				$w[ 'fc_dt_precipIntensity' . $j ] += $weather_array['daily'][ $i ]['snow'];
				$w[ 'fc_dt_precipType' . $j ]       = 'Snow';
			}

			// convert mm to inches for compatibility reasons with accuweather.
			$w[ 'fc_dt_precipIntensity' . $j ] = $w[ 'fc_dt_precipIntensity' . $j ] / 2.54 / 10;
		}

		// fill failure anyway.
		$w['failure'] = ( isset( $w['failure'] ) ? $w['failure'] : '' );

		return $w;
	}
}

if ( ! function_exists( 'openweathermap_forecast_data' ) ) {
	/**
	 * Return the weather data for the cache from OWM
	 *
	 * @param string $wpfcid            the Widget ID.
	 * @param string $language_override the iso code of the language to use.
	 */
	function openweathermap_forecast_data( $wpfcid = 'A', $language_override = null ) {

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

		$w['servicelink'] = 'https://openweathermap.org/weathermap?basemap=map&cities=true&layer=temperature&lat=' . $w['lat'] . '&lon=' . $w['lon'] . '&zoom=5';
		$w['copyright']   = '<a href="https://openweathermap.org">&copy; ' . gmdate( 'Y' ) . ' Powered by OpenWeather</a>';

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

		$ct = current_time( 'U' );
		$ct = $ct + $wpf_vars['timeoffset'] * 60; // add or subtract time offset.

		$w['blogdate'] = date_i18n( $wpf_vars['fc_date_format'], $ct );
		$w['blogtime'] = date_i18n( $wpf_vars['fc_time_format'], $ct );

		// get date/time from openweathermap.
		$ct            = $w['time'] + $w['gmtdiff'];
		$w['accudate'] = date_i18n( $wpf_vars['fc_date_format'], $ct );
		$w['accutime'] = date_i18n( $wpf_vars['fc_time_format'], $ct );

		$ico            = openweathermap_map_icon( $w['weatherid'], false );
		$iconfile       = find_icon( $ico );
		$w['icon']      = 'icons/' . $iconfile;
		$w['iconcode']  = $ico;
		$w['shorttext'] = wpf__( openweathermap_wcode2text( $w['weatherid'] ), $wpf_vars['wpf_language'] );

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
		for ( $i = 1; $i < 8; $i++ ) {
			// daytime forecast.
			$w[ 'fc_obsdate_' . $i ] = date_i18n( $wpf_vars['fc_date_format'], $w[ 'fc_obsdate_' . $i ] );

			$ico                             = openweathermap_map_icon( $w[ 'fc_dt_id_' . $i ], false );
			$iconfile                        = find_icon( $ico );
			$w[ 'fc_dt_icon_' . $i ]         = 'icons/' . $iconfile;
			$w[ 'fc_dt_iconcode_' . $i ]     = $ico;
			$w[ 'fc_dt_desc_' . $i ]         = wpf__( openweathermap_wcode2text( $w[ 'fc_dt_id_' . $i ] ), $wpf_vars['wpf_language'] );
			$w[ 'fc_dt_htemp_' . $i ]        = $w[ 'fc_dt_htemp_' . $i ] . '&deg;' . $w['un_temp'];
			$wstr                            = windstr( $wpf_vars['metric'], $w[ 'fc_dt_windspeed_' . $i ], $wpf_vars['windunit'] );
			$w[ 'fc_dt_windspeed_' . $i ]    = $wstr;
			$w[ 'fc_dt_winddir_' . $i ]      = translate_winddir_degree( $w[ 'fc_dt_winddir_' . $i ], $wpf_vars['wpf_language'] );
			$w[ 'fc_dt_winddir_orig_' . $i ] = str_replace( 'O', 'E', $w[ 'fc_dt_winddir_' . $i ] );
			$w[ 'fc_dt_wgusts_' . $i ]       = windstr( $wpf_vars['metric'], $w[ 'fc_dt_wgusts_' . $i ], $wpf_vars['windunit'] );
			$w[ 'fc_dt_maxuv_' . $i ]        = $w[ 'fc_dt_maxuv_' . $i ];

			// nighttime forecast.
			$ico                             = openweathermap_map_icon( $w[ 'fc_nt_id_' . $i ], true );
			$iconfile                        = find_icon( $ico );
			$w[ 'fc_nt_icon_' . $i ]         = 'icons/' . $iconfile;
			$w[ 'fc_nt_iconcode_' . $i ]     = $ico;
			$w[ 'fc_nt_desc_' . $i ]         = wpf__( openweathermap_wcode2text( $w[ 'fc_nt_id_' . $i ] ), $wpf_vars['wpf_language'] );
			$w[ 'fc_nt_ltemp_' . $i ]        = $w[ 'fc_nt_ltemp_' . $i ] . '&deg;' . $w['un_temp'];
			$wstr                            = windstr( $wpf_vars['metric'], $w[ 'fc_nt_windspeed_' . $i ], $wpf_vars['windunit'] );
			$w[ 'fc_nt_windspeed_' . $i ]    = $wstr;
			$w[ 'fc_nt_winddir_' . $i ]      = translate_winddir_degree( $w[ 'fc_nt_winddir_' . $i ], $wpf_vars['wpf_language'] );
			$w[ 'fc_nt_winddir_orig_' . $i ] = str_replace( 'O', 'E', $w[ 'fc_nt_winddir_' . $i ] );
			$w[ 'fc_nt_wgusts_' . $i ]       = windstr( $wpf_vars['metric'], $w[ 'fc_nt_wgusts_' . $i ], $wpf_vars['windunit'] );
			$w[ 'fc_nt_maxuv_' . $i ]        = $w[ 'fc_nt_maxuv_' . $i ];
		}

		// add hook for possible individual changes.
		$w = apply_filters( 'wp-forecast-openweathermap-data', $w );

		return $w;
	}
}

if ( ! function_exists( 'openweathermap_map_icon' ) ) {
	/**
	 * Function to map the weather code to a weather icon
	 *
	 * @param string $weatherid the id of the weather condition.
	 * @param bool   $night     the parameter to say if it is night or not.
	 */
	function openweathermap_map_icon( $weatherid, $night = false ) {
		/*
		 Icon mapping from OWM
		 */

		/*
		 $ico_arr = array(
				'clear-day'             => '01',
				'clear-night'           => '33',
				'rain' 					=> '12',
				'snow'                  => '22',
				'sleet'                 => '29',
				'wind'                  => '32',
				'fog'                   => '11',
				'cloudy' 				=> '06',
				'partly-cloudy-day'     => '04',
				'partly-cloudy-night' 	=> '38',
				'hail'                  => '25',
				'thunderstorm'          => '15',
				'tornado'				=> '32',
		);
		*/

		$icon = '01';

		/* thunderstorm */
		if ( $weatherid >= 200 && $weatherid <= 232 ) {
			$icon = '15';
		}
		/* rain */
		if ( $weatherid >= 500 && $weatherid <= 531 ) {
			$icon = '12';
		}
		if ( $weatherid >= 300 && $weatherid <= 321 ) {
			$icon = '12';
		}
		/* snow , sleet*/
		if ( $weatherid >= 600 && $weatherid <= 622 ) {
			$icon = '22';
		}
		if ( 611 == $weatherid ) {
			$icon = '29';
		}
		/* tornado */
		if ( 781 == $weatherid ) {
			$icon = '32';
		}
		/* fog */
		if ( 741 == $weatherid ) {
			$icon = '11';
		}
		/* clear sky */
		if ( 800 == $weatherid && false === $night ) {
			$icon = '01';
		}
		if ( 800 == $weatherid && true === $night ) {
			$icon = '33';
		}

		/* partl cloudy */
		if ( $weatherid >= 801 && $weatherid <= 803 && false === $night ) {
			$icon = '04';
		}
		if ( $weatherid >= 801 && $weatherid <= 803 && true === $night ) {
			$icon = '38';
		}
		if ( 804 == $weatherid ) {
			$icon = '06';
		}

		return $icon;
	}
}

if ( ! function_exists( 'openweathermap_wcode2text' ) ) {
	/**
	 * Return the weather data for the cache from OWM
	 *
	 * @param string $wcode the OWM code for the weather condition.
	 */
	function openweathermap_wcode2text( $wcode ) {

		/**
		 * OpenWEatherMap condition codes
		 *
		 * Group 2xx: Thunderstorm
		 * ID   Main    Description Icon
		 * 200  Thunderstorm    thunderstorm with light rain     11d
		 * 201  Thunderstorm    thunderstorm with rain   11d
		 * 202  Thunderstorm    thunderstorm with heavy rain     11d
		 * 210  Thunderstorm    light thunderstorm   11d
		 * 211  Thunderstorm    thunderstorm     11d
		 * 212  Thunderstorm    heavy thunderstorm   11d
		 * 221  Thunderstorm    ragged thunderstorm  11d
		 * 230  Thunderstorm    thunderstorm with light drizzle  11d
		 * 231  Thunderstorm    thunderstorm with drizzle    11d
		 * 232  Thunderstorm    thunderstorm with heavy drizzle  11d
		 *
		 * Group 3xx: Drizzle
		 * ID   Main    Description Icon
		 * 300  Drizzle light intensity drizzle  09d
		 * 301  Drizzle drizzle  09d
		 * 302  Drizzle heavy intensity drizzle  09d
		 * 310  Drizzle light intensity drizzle rain     09d
		 * 311  Drizzle drizzle rain     09d
		 * 312  Drizzle heavy intensity drizzle rain     09d
		 * 313  Drizzle shower rain and drizzle  09d
		 * 314  Drizzle heavy shower rain and drizzle    09d
		 * 321  Drizzle shower drizzle   09d
		 *
		 * Group 5xx: Rain
		 * ID   Main    Description Icon
		 * 500  Rain    light rain   10d
		 * 501  Rain    moderate rain    10d
		 * 502  Rain    heavy intensity rain     10d
		 * 503  Rain    very heavy rain  10d
		 * 504  Rain    extreme rain     10d
		 * 511  Rain    freezing rain    13d
		 * 520  Rain    light intensity shower rain  09d
		 * 521  Rain    shower rain  09d
		 * 522  Rain    heavy intensity shower rain  09d
		 * 531  Rain    ragged shower rain   09d
		 *
		 * Group 6xx: Snow
		 * ID   Main    Description Icon
		 * 600  Snow    light snow   13d
		 * 601  Snow    snow     13d
		 * 602  Snow    heavy snow   13d
		 * 611  Snow    sleet    13d
		 * 612  Snow    light shower sleet   13d
		 * 613  Snow    shower sleet     13d
		 * 615  Snow    light rain and snow  13d
		 * 616  Snow    rain and snow    13d
		 * 620  Snow    light shower snow    13d
		 * 621  Snow    shower snow  13d
		 * 622  Snow    heavy shower snow    13d
		 *
		 * Group 7xx: Atmosphere
		 * ID   Main    Description Icon
		 * 701  Mist    mist     50d
		 * 711  Smoke   smoke    50d
		 * 721  Haze    haze     50d
		 * 731  Dust    sand/dust whirls     50d
		 * 741  Fog fog  50d
		 * 751  Sand    sand     50d
		 * 761  Dust    dust     50d
		 * 762  Ash volcanic ash     50d
		 * 771  Squall  squalls  50d
		 * 781  Tornado tornado  50d
		 *
		 * Group 800: Clear
		 * ID   Main    Description Icon
		 * 800  Clear   clear sky    01d / 01n
		 *
		 * Group 80x: Clouds
		 * ID   Main    Description Icon
		 * 801  Clouds  few clouds: 11-25%   02d / 02n
		 * 802  Clouds  scattered clouds: 25-50%     03d /  03n
		 * 803  Clouds  broken clouds: 51-84%    04d / 04n
		 * 804  Clouds  overcast clouds: 85-100%     04d / 04n
		 */

		$c = __( 'clear sky', 'wp-forecast' ); // the short description for the weather condition.

		if ( $wcode >= 200 && $wcode < 300 ) {
			switch ( $wcode ) {
				case 200:
					$c = __( 'Thunderstorm with light rain', 'xxxdummy' );
					break;
				case 201:
					$c = __( 'Thunderstorm with rain', 'xxxdummy' );
					break;
				case 202:
					$c = __( 'Thunderstorm with heavy rain', 'xxxdummy' );
					break;
				case 210:
					$c = __( 'Light thunderstorm', 'xxxdummy' );
					break;
				case 211:
					$c = __( 'Thunderstorm', 'xxxdummy' );
					break;
				case 212:
					$c = __( 'Heavy thunderstorm', 'xxxdummy' );
					break;
				case 221:
					$c = __( 'Ragged thunderstorm', 'xxxdummy' );
					break;
				case 230:
					$c = __( 'Thunderstorm with light drizzle', 'xxxdummy' );
					break;
				case 231:
					$c = __( 'Thunderstorm with drizzle', 'xxxdummy' );
					break;
				case 232:
					$c = __( 'Thunderstorm with heavy drizzle', 'xxxdummy' );
					break;
			}
		}

		if ( $wcode >= 300 && $wcode < 400 ) {
			switch ( $wcode ) {
				case 300:
					$c = __( 'Light drizzle', 'xxxdummy' );
					break;
				case 301:
					$c = __( 'Drizzle', 'xxxdummy' );
					break;
				case 302:
					$c = __( 'Heavy drizzle', 'xxxdummy' );
					break;
				case 310:
					$c = __( 'Light drizzle rain', 'xxxdummy' );
					break;
				case 311:
					$c = __( 'Drizzle rain', 'xxxdummy' );
					break;
				case 312:
					$c = __( 'Heavy drizzle rain', 'xxxdummy' );
					break;
				case 313:
					$c = __( 'Shower rain and drizzle', 'xxxdummy' );
					break;
				case 314:
					$c = __( 'Heavy shower rain and drizzle', 'xxxdummy' );
					break;
				case 321:
					$c = __( 'Shower drizzle', 'xxxdummy' );
					break;
			}
		}

		if ( $wcode >= 500 && $wcode < 600 ) {
			switch ( $wcode ) {
				case 500:
					$c = __( 'Light rain', 'xxxdummy' );
					break;
				case 501:
					$c = __( 'Moderate rain', 'xxxdummy' );
					break;
				case 502:
					$c = __( 'Heavy rain', 'xxxdummy' );
					break;
				case 503:
					$c = __( 'Very heavy rain', 'xxxdummy' );
					break;
				case 504:
					$c = __( 'Extreme rain', 'xxxdummy' );
					break;
				case 511:
					$c = __( 'Freezing rain', 'xxxdummy' );
					break;
				case 520:
					$c = __( 'Light shower rain', 'xxxdummy' );
					break;
				case 521:
					$c = __( 'Shower rain', 'xxxdummy' );
					break;
				case 522:
					$c = __( 'Heavy shower rain', 'xxxdummy' );
					break;
				case 531:
					$c = __( 'Ragged shower rain', 'xxxdummy' );
					break;
			}
		}

		if ( $wcode >= 600 && $wcode < 700 ) {
			switch ( $wcode ) {
				case 600:
					$c = __( 'Light snow', 'xxxdummy' );
					break;
				case 601:
					$c = __( 'Snow', 'xxxdummy' );
					break;
				case 602:
					$c = __( 'Heavy snow', 'xxxdummy' );
					break;
				case 611:
					$c = __( 'Sleet', 'xxxdummy' );
					break;
				case 612:
					$c = __( 'Light shower sleet', 'xxxdummy' );
					break;
				case 613:
					$c = __( 'Shower sleet', 'xxxdummy' );
					break;
				case 615:
					$c = __( 'Light rain and snow', 'xxxdummy' );
					break;
				case 616:
					$c = __( 'Rain and snow', 'xxxdummy' );
					break;
				case 620:
					$c = __( 'Light shower snow', 'xxxdummy' );
					break;
				case 621:
					$c = __( 'Shower snow', 'xxxdummy' );
					break;
				case 622:
					$c = __( 'Heavy shower snow', 'xxxdummy' );
					break;
			}
		}

		if ( $wcode >= 700 && $wcode < 800 ) {
			switch ( $wcode ) {
				case 701:
					$c = __( 'Mist', 'xxxdummy' );
					break;
				case 711:
					$c = __( 'Smoke', 'xxxdummy' );
					break;
				case 721:
					$c = __( 'Haze', 'xxxdummy' );
					break;
				case 731:
					$c = __( 'Dust', 'xxxdummy' );
					break;
				case 741:
					$c = __( 'Fog', 'xxxdummy' );
					break;
				case 751:
					$c = __( 'Sand', 'xxxdummy' );
					break;
				case 761:
					$c = __( 'Dust', 'xxxdummy' );
					break;
				case 762:
					$c = __( 'Ash', 'xxxdummy' );
					break;
				case 771:
					$c = __( 'Squalls', 'xxxdummy' );
					break;
				case 781:
					$c = __( 'Tornado', 'xxxdummy' );
					break;
			}
		}

		if ( $wcode >= 800 && $wcode < 900 ) {
			switch ( $wcode ) {
				case 800:
					$c = __( 'Clear sky', 'xxxdummy' );
					break;
				case 801:
					$c = __( 'Few clouds', 'xxxdummy' );
					break;
				case 802:
					$c = __( 'Scattered clouds', 'xxxdummy' );
					break;
				case 803:
					$c = __( 'Broken clouds', 'xxxdummy' );
					break;
				case 804:
					$c = __( 'Overcast clouds', 'xxxdummy' );
					break;
			}
		}

		return $c;
	} // end of function.
} // end of function wrapper.
