<?php
/**
 * Copyright 2006-2023 Hans Matzen  (email : webmaster at tuxlog dot de)
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
 * Display the weather-data.
 *
 * @param string $wpfcid   The widget ID.
 * @param array  $args     The widget aprameters.
 * @param array  $wpf_vars The Parameters of wth widget.
 */
function show( $wpfcid, $args, $wpf_vars ) {
	// check how we are called as a widget or from sidebar.

	if ( count( $args ) == 0 ) {
		$show_from_widget = 0;
	} else {
		$show_from_widget = 1;
	}

	// take title from widget args.
	if ( isset( $args['title'] ) ) {
		// remember title from widget.
		$wpf_vars['title'] = $args['title'];
		// apply wp default filters to title for output.
		$title = apply_filters( 'widget_title', $args['title'] );
	}

	// get translations.
	global $wpf_lang_dict;
	$wpf_lang = array();
	$langfile = WP_PLUGIN_DIR . '/wp-forecast/widgetlang/wp-forecast-' . strtolower( str_replace( '_', '-', $wpf_vars['wpf_language'] ) ) . '.php';
	if ( file_exists( $langfile ) ) {
		include $langfile;
	}
	$wpf_lang_dict[ $wpf_vars['wpf_language'] ] = $wpf_lang;
	$wpf_language                               = $wpf_vars['wpf_language'];

	$plugin_path = plugins_url( '', __FILE__ );
	// show location selection dialog and handle it ajax like.
	$fout     = '';
	$selector = substr( $wpfcid, 0, 1 );

	if ( '?' == $selector ) {
		$wpfcid = substr( $wpfcid, 1 );

		if ( '' == $wpfcid ) {
			$wpfcid = 'A';
		}
		$count = wpf_get_option( 'wp-forecast-count' );
		$fout .= "<div class='wpf-selector'><form action='#'>" . wpf__( 'Locations:', $wpf_vars['wpf_language'] );
		$fout .= "<select id='wpf_selector' size='1' onchange='wpf_update();' >";

		for ( $i = 0;$i < $count;$i++ ) {
			$id    = get_widget_id( $i );
			$v     = get_wpf_opts( $id );
			$fout .= "<option value='" . esc_attr( $id ) . "' ";

			if ( $id == $wpfcid ) {
				$fout .= "selected='selected' ";
			}
			$fout .= '>' . esc_attr( $v['locname'] ) . '</option>';
		}
		$fout .= '</select>';
		$fout .= "<input id='wpf_selector_site' type='hidden' value='" . esc_attr( $plugin_path ) . "' />";
		$fout .= "<input id='wpf_language' type='hidden' value='" . esc_attr( $wpf_language ) . "' />";
		$fout .= '</form>';
		$fout .= '<script type="text/javascript">window.onDomReady(wpf_load);</script>';
		$fout .= '</div>';
	}
	$w = wp_forecast_data( $wpfcid, $wpf_vars['wpf_language'] );
	// current conditions nur ausgeben, wenn mindestens ein feld aktiv ist.

	if ( strpos( substr( $wpf_vars['dispconfig'], 0, 9 ), '1' ) >= 0 || substr( $wpf_vars['dispconfig'], 18, 1 ) == '1' || substr( $wpf_vars['dispconfig'], 21, 1 ) == '1' || substr( $wpf_vars['dispconfig'], 22, 1 ) == '1' ) {
		// output current conditions.
		$out  = '';
		$out .= "\n<div class=\"wp-forecast-curr\">\n";
		// if the provider sends us a failure notice print it and return.

		if ( '' != $w['failure'] ) {
			$out .= wpf__( 'Failure notice from provider', $wpf_language ) . ':<br />';
			$out .= esc_attr( $w['failure'] ) . '</div>';
			// print it.

			if ( 1 == $show_from_widget ) {
				echo wp_kses( $args['before_widget'] . $args['before_title'] . $title . $args['after_title'] . $out . $args['after_widget'], wpf_allowed_tags() );
			} else {
				echo wp_kses( $out, wpf_allowed_tags() );
			}
			return false;
		}
		// if error print an error message and return.

		if ( count( $w ) <= 0 ) {
			$out .= wpf__( 'Sorry, no valid weather data available.', $wpf_language ) . '<br />';
			$out .= wpf__( 'Please try again later.', $wpf_language ) . '</div>';
			// print it.

			if ( 1 == $show_from_widget ) {
				echo wp_kses( $args['before_widget'] . $args['before_title'] . $title . $args['after_title'] . $out . $args['after_widget'], wpf_allowed_tags() );
			} else {
				echo wp_kses( $out, wpf_allowed_tags() );
			}
			return false;
		}
		// ortsnamen ausgeben parameter fuer open in new window berücksichtigen.
		$servicelink     = '';
		$servicelink_end = '';

		if ( substr( $wpf_vars['dispconfig'], 25, 1 ) == '1' ) {
			$servicelink     = '<a href="' . esc_url( $w['servicelink'] ) . '"';
			$servicelink_end = '</a>';

			if ( substr( $wpf_vars['dispconfig'], 26, 1 ) == '1' ) {
				$servicelink = $servicelink . ' target="_blank" >';
			} else {
				$servicelink = $servicelink . ' >';
			}
		}

		$out .= '<div class="wp-forecast-curr-head">';

		if ( '' == $w['location'] || ( isset( $wpf_vars['visitorlocation'] ) && 1 == $wpf_vars['visitorlocation'] ) ) {
			$out .= '<div>' . $servicelink . esc_attr( $w['locname'] ) . $servicelink_end . "</div>\n";
		} elseif ( '' != trim( $w['location'] ) && '&nbsp;' != $w['location'] ) {
			$out .= '<div>' . $servicelink . esc_attr( $w['location'] ) . $servicelink_end . "</div>\n";
		}

		// show date / time.
		// if current time should be used.
		if ( '1' == $wpf_vars['currtime'] ) {
			$cd = $w['blogdate'];
			$ct = $w['blogtime'];
		} else {
			// else take given weather time.
			$cd = $w['accudate'];
			$ct = $w['accutime'];
		}

		if ( substr( $wpf_vars['dispconfig'], 18, 1 ) == '1' || substr( $wpf_vars['dispconfig'], 1, 1 ) == '1' ) {
			$out .= '<div>';

			if ( substr( $wpf_vars['dispconfig'], 18, 1 ) == '1' ) {
				$out .= esc_attr( $cd );
			} else {
				$out .= wpf__( 'time', $wpf_language ) . ': ';
			}

			if ( substr( $wpf_vars['dispconfig'], 18, 1 ) == '1' && substr( $wpf_vars['dispconfig'], 1, 1 ) == '1' ) {
				$out .= ', ';
			}

			if ( substr( $wpf_vars['dispconfig'], 1, 1 ) == '1' ) {
				$out .= esc_attr( $ct );
			}
			$out .= "</div>\n";
		}
		$out .= "</div>\n";
		$out .= '<div class="wp-forecast-curr-block">';

		// show icon.
		$out .= "<div class='wp-forecast-curr-left'>";

		if ( substr( $wpf_vars['dispconfig'], 0, 1 ) == '1' ) {
			$breite = 0;
			$hoehe  = 0;

			if ( file_exists( plugin_dir_path( __FILE__ ) . '/' . $w['icon'] ) ) {
				$isize = getimagesize( plugin_dir_path( __FILE__ ) . '/' . $w['icon'] );

				if ( false != $isize ) {
					$breite = $isize[0];
					$hoehe  = $isize[1];
				}
			}

			if ( 1 == $wpf_vars['csssprites'] ) { // mit CSS Sprites.
				$cssid = substr( $w['icon'], strpos( $w['icon'], '/' ) + 1, strrpos( $w['icon'], '.' ) - strpos( $w['icon'], '/' ) - 1 );
				$out  .= "<div class='wp-forecast-curr-left wpfico" . esc_attr( $cssid ) . "'>&nbsp;</div>\n";
			} else { // ohne CSS-Sprites.
				$out .= "<img class='wp-forecast-curr-left' src='" . esc_attr( $plugin_path ) . '/' . esc_attr( $w['icon'] ) . "' alt='" . esc_attr( $w['shorttext'] ) . "' width='" . esc_attr( $breite ) . "' height='" . esc_attr( $hoehe ) . "' />\n";
			}
		}
		$out .= '<br />';

		// show windicon.
		if ( '1' == $wpf_vars['windicon'] ) {
			$breite        = 48;
			$hoehe         = 48;
			$wind_icon_url = $plugin_path . '/icons/wpf-' . $w['winddir_orig'] . '.png';
			$out          .= "<img src='" . esc_url( $wind_icon_url ) . "' alt='" . esc_attr( $w['winddir'] ) . "' width='" . esc_attr( $breite ) . "' height='" . esc_attr( $hoehe ) . "' />\n";
		}
		$out .= '</div>';
		$out .= "<div class='wp-forecast-curr-right'>";
		$out .= '<div>';

		// show short description.
		if ( substr( $wpf_vars['dispconfig'], 2, 1 ) == '1' ) {
			$out .= esc_attr( wpf__( $w['shorttext'], $wpf_vars['wpf_language'] ) ) . '<br/>';
		}

		// show temperatur.
		if ( substr( $wpf_vars['dispconfig'], 3, 1 ) == '1' ) {
			$out .= esc_attr( $w['temperature'] );
		}
		$out .= '</div>';
		// show wind on the right side if windicon is active.

		if ( '1' == $wpf_vars['windicon'] ) {
			$out .= '<div class="wp-forecast-wind-right">' . esc_attr( $w['windspeed'] ) . "</div>\n";
		}
		$out .= "</div>\n"; // end of right.
		$out .= "</div>\n"; // end of block.
		$out .= '<div class="wp-forecast-curr-details">';
		// show realfeel.

		if ( substr( $wpf_vars['dispconfig'], 4, 1 ) == '1' ) {
			$out .= '<div>' . wpf__( 'Apparent', $wpf_vars['wpf_language'] ) . ': ' . esc_attr( $w['realfeel'] ) . "</div>\n";
		}
		// show pressure.

		if ( substr( $wpf_vars['dispconfig'], 5, 1 ) == '1' ) {
			$out .= '<div>' . wpf__( 'Pressure', $wpf_vars['wpf_language'] ) . ': ' . esc_attr( $w['pressure'] ) . "</div>\n";
		}
		// show humiditiy.

		if ( substr( $wpf_vars['dispconfig'], 6, 1 ) == '1' ) {

			// you can change the decimals of humditiy by switching.

			// the 0 to whatever you need.

				$out .= '<div>' . wpf__( 'Humidity', $wpf_vars['wpf_language'] ) . ': ' . esc_attr( $w['humidity'] ) . "%</div>\n";
		}
		// show wind.

		if ( substr( $wpf_vars['dispconfig'], 7, 1 ) == '1' ) {
			$out .= '<div>' . wpf__( 'Winds', $wpf_vars['wpf_language'] ) . ': ' . esc_attr( $w['windspeed'] ) . ' ' . esc_attr( $w['winddir'] ) . "</div>\n";
		}
		// show windgusts.

		if ( substr( $wpf_vars['dispconfig'], 22, 1 ) == '1' ) {
			$out .= '<div>' . wpf__( 'Windgusts', $wpf_vars['wpf_language'] ) . ': ' . esc_attr( $w['windgusts'] ) . "</div>\n";
		}

		// show uvindex.
		if ( substr( $wpf_vars['dispconfig'], 27, 1 ) == '1' && trim( $wpf_vars['ouv_apikey'] ) == '' ) {
			$out .= '<div>' . wpf__( 'UV-Index', $wpf_vars['wpf_language'] ) . ': ' . esc_attr( $w['uvindex'] ) . "</div>\n";
		}

		// show precipitation.
		if ( substr( $wpf_vars['dispconfig'], 30, 1 ) == '1' ) {
			$intensestr = $w['precipIntensity'];
			if ( 'mm' == $w['un_prec'] ) {
				$intensestr = round( $w['precipIntensity'] * 2.54 * 10, 1 );
			}

			$precipstr  = '<span class="wp-forecast-precipIntense">' . esc_attr( $intensestr ) . esc_attr( $w['un_prec'] ) . '</span>';
			$precipstr .= ' /<span class="wp-forecast-precipProb">' . esc_attr( $w['precipProbability'] ) . '%</span>';
			if ( '' != $w['precipType'] ) {
				$precipstr .= ' / <span class="wp-forecast-precipType">' . wpf__( $w['precipType'], $wpf_language ) . '</span>';
			}
			$out .= '<div>' . wpf__( 'Precip.', $wpf_language ) . ': ' . $precipstr . "</div>\n";
		}

		// show sunrise.
		if ( substr( $wpf_vars['dispconfig'], 8, 1 ) == '1' ) {
			$out .= '<div>' . wpf__( 'Sunrise', $wpf_vars['wpf_language'] ) . ': ' . esc_attr( $w['sunrise'] ) . "</div>\n";
		}

		// show sunset.
		if ( substr( $wpf_vars['dispconfig'], 9, 1 ) == '1' ) {
			$out .= '<div>' . wpf__( 'Sunset', $wpf_vars['wpf_language'] ) . ': ' . esc_attr( $w['sunset'] ) . "</div>\n";
		}

		// show data from OpenUV.io if applicable.
		if ( isset( $wpf_vars['ouv_apikey'] ) && trim( $wpf_vars['ouv_apikey'] ) != '' ) {

			if ( $wpf_vars['ouv_uv'] ) {
				$out .= '<div>' . wpf__( 'Current UV index', $wpf_language ) . ': ' . esc_attr( $w['openuv']['uv'] ) . "</div>\n";
			}

			if ( $wpf_vars['ouv_uvmax'] ) {
				$out .= '<div>' . wpf__( 'Max. UV index', $wpf_language ) . ': ' . esc_attr( $w['openuv']['uv_max'] ) . "</div>\n";
			}

			if ( $wpf_vars['ouv_ozone'] ) {
				$out .= '<div>' . wpf__( 'Ozone', $wpf_language ) . ': ' . esc_attr( $w['openuv']['ozone'] ) . " DU</div>\n";
			}

			if ( $wpf_vars['ouv_safetime'] ) {
				$j = 1;

				foreach ( $w['openuv']['safe_exposure_time'] as $set ) {

					if ( trim( $set ) != '' ) {
						$out .= '<div>' . wpf_esc_attr__( 'Safe Exposure Time for Skin Type', $wpf_language ) . " $j: " . esc_attr( $set ) . " Min.</div>\n";
					} else {
						$out .= '<div>' . wpf_esc_attr__( 'Safe Exposure Time for Skin Type', $wpf_language ) . " $j: &infin; Min.</div>\n";
					}
					$j++;
				}
			}
			$out .= '<div class="wp-forecast-copyright">' . wpf_esc_attr__( $w['openuv']['copyright'], $wpf_language ) . '</div>';
		}
		// show copyright.

		if ( substr( $wpf_vars['dispconfig'], 21, 1 ) == '1' ) {
			$out .= '<div class="wp-forecast-copyright">' . $w['copyright'] . '</div>';
		}
		$out .= "</div>\n"; // end of details.
		$out .= "</div>\n"; // end of curr.

	}
	// ------------------

	// output forecast.

	// -------------------

	// calc max forecast days depending on provider.
	$maxdays = 7; // for openweathermap.
	if ( 'openmeteo' == $wpf_vars['service'] ) {
		$maxdays = 6;
	}

	$out1 = "<div class=\"wp-forecast-fc\">\n";
	$out2 = '';

	for ( $i = 1;$i <= $maxdays;$i++ ) {
		// check active forecast for day number i.

		if ( substr( $wpf_vars['daytime'], $i - 1, 1 ) == '1' || substr( $wpf_vars['nighttime'], $i - 1, 1 ) == '1' ) {
			$out1 .= "<div class=\"wp-forecast-fc-oneday\">\n";
			$out1 .= '<div class="wp-forecast-fc-head">';
			$out1 .= wpf__( 'Forecast', $wpf_vars['wpf_language'] ) . ' ';
			$out1 .= esc_attr( $w[ 'fc_obsdate_' . $i ] ) . "</div>\n";
		}
		// check for daytime information.

		if ( substr( $wpf_vars['daytime'], $i - 1, 1 ) == '1' ) {
			$out1 .= "<div class=\"wp-forecast-fc-block\">\n";
			$out1 .= "<div class=\"wp-forecast-fc-left\">\n";
			$out1 .= '<div>' . wpf__( 'Day', $wpf_vars['wpf_language'] ) . "</div>\n";
			// show icon.

			if ( substr( $wpf_vars['dispconfig'], 10, 1 ) == '1' ) {
				$breite = 0;
				$hoehe  = 0;

				if ( file_exists( plugin_dir_path( __FILE__ ) . '/' . $w[ 'fc_dt_icon_' . $i ] ) ) {
					$isize = getimagesize( plugin_dir_path( __FILE__ ) . '/' . $w[ 'fc_dt_icon_' . $i ] );

					if ( false != $isize ) {
						$breite = $isize[0];
						$hoehe  = $isize[1];
					}
				}

				if ( 1 == $wpf_vars['csssprites'] ) { // mit CSS Sprites.
					$cssid = substr( $w[ 'fc_dt_icon_' . $i ], strpos( $w[ 'fc_dt_icon_' . $i ], '/' ) + 1, strrpos( $w[ 'fc_dt_icon_' . $i ], '.' ) - strpos( $w[ 'fc_dt_icon_' . $i ], '/' ) - 1 );
					$out1 .= "<div class='wp-forecast-curr-left wpfico" . esc_attr( $cssid ) . "'>&nbsp;</div>\n";
				} else { // ohne CSS-Sprites.
					$out1 .= "<img class='wp-forecast-fc-left' src='" . esc_attr( $plugin_path ) . '/' . esc_attr( $w[ 'fc_dt_icon_' . $i ] ) . "' alt='" . wpf__( $w[ 'fc_dt_iconcode_' . $i ], $wpf_vars['wpf_language'] ) . "' width='$breite' height='$hoehe' />";
				}
			} else {
				$out1 .= '&nbsp;';
			}
			$out1 .= '<br />';
			// show windicon.

			if ( isset( $windicon ) && '1' == $windicon ) {
				$breite        = 48;
				$hoehe         = 48;
				$wind_icon_url = $plugin_path . '/icons/wpf-' . $w[ 'fc_dt_winddir_orig_' . $i ] . '.png';
				$out1         .= "<img src='" . esc_url( $wind_icon_url ) . "' alt='" . esc_attr( $w[ 'fc_dt_winddir_' . $i ] ) . "' width='" . esc_attr( $breite ) . "' height='" . esc_attr( $hoehe ) . "' />\n";
			}
			$out1 .= "\n</div>\n"; // end of wp-forecast-fc-left.
			$out1 .= "<div class='wp-forecast-fc-right'>";

			// show short description.
			if ( substr( $wpf_vars['dispconfig'], 11, 1 ) == '1' ) {
				$out1 .= '<div>' . esc_attr( wpf__( $w[ 'fc_dt_desc_' . $i ], $wpf_vars['wpf_language'] ) ) . '</div>';
			}
			// show temperature.

			if ( substr( $wpf_vars['dispconfig'], 12, 1 ) == '1' ) {
				$out1 .= '<div>';
				$out1 .= esc_attr( $w[ 'fc_dt_htemp_' . $i ] ) . '</div>';
			}
			// show wind.

			if ( substr( $wpf_vars['dispconfig'], 13, 1 ) == '1' ) {
				$out1 .= '<div>' . wpf__( 'Winds', $wpf_vars['wpf_language'] ) . ': ' . esc_attr( $w[ 'fc_dt_windspeed_' . $i ] ) . ' ' . esc_attr( $w[ 'fc_dt_winddir_' . $i ] ) . '</div>';
			}
			// show windgusts.

			// show precipitation.
			if ( substr( $wpf_vars['dispconfig'], 31, 1 ) == '1' ) {
				$intensestr = $w[ 'fc_dt_precipIntensity' . $i ];
				if ( 'mm' == $w['un_prec'] ) {
					$intensestr = round( $w[ 'fc_dt_precipIntensity' . $i ] * 2.54 * 10, 1 );
				}
				$precipstr  = '<span class="wp-forecast-precipIntense">' . esc_attr( $intensestr ) . esc_attr( $w['un_prec'] ) . '</span>';
				$precipstr .= ' / <span class="wp-forecast-precipProb">' . esc_attr( $w[ 'fc_dt_precipProbability' . $i ] ) . '%</span>';
				if ( '' != $w[ 'fc_dt_precipType' . $i ] ) {
					$precipstr .= ' / <span class="wp-forecast-precipType">' . wpf__( $w[ 'fc_dt_precipType' . $i ], $wpf_vars['wpf_language'] ) . '</span>';
				}
				$out1 .= '<div>' . wpf__( 'Precip.', $wpf_vars['wpf_language'] ) . ': ' . $precipstr . "</div>\n";
			}

			if ( substr( $wpf_vars['dispconfig'], 23, 1 ) == '1' ) {
				$out1 .= '<div>' . wpf__( 'Windgusts', $wpf_vars['wpf_language'] ) . ': ' . esc_attr( $w[ 'fc_dt_wgusts_' . $i ] ) . "</div>\n";
			}
			// show max uv index.

			if ( substr( $wpf_vars['dispconfig'], 28, 1 ) == '1' ) {
				$out1 .= '<div>' . wpf__( 'Max. UV index', $wpf_vars['wpf_language'] ) . ': ' . esc_attr( $w[ 'fc_dt_maxuv_' . $i ] ) . "</div>\n";
			}
			$out1 .= "</div></div>\n"; // end of wp-forecast-fc-right / block.

		}
		// check for nighttime information.

		if ( substr( $wpf_vars['nighttime'], $i - 1, 1 ) == '1' ) {
			$out1 .= "<div class=\"wp-forecast-fc-block\">\n";
			$out1 .= "<div class=\"wp-forecast-fc-left\">\n";
			$out1 .= '<div>' . wpf__( 'Night', $wpf_vars['wpf_language'] ) . "</div>\n";

			if ( substr( $wpf_vars['dispconfig'], 14, 1 ) == '1' ) {
				$iconfile = find_icon( $w[ 'fc_nt_icon_' . $i ] );
				$breite   = 64;
				$hoehe    = 40;

				if ( file_exists( plugin_dir_path( __FILE__ ) . '/' . $w[ 'fc_nt_icon_' . $i ] ) ) {
					$isize = getimagesize( plugin_dir_path( __FILE__ ) . '/' . $w[ 'fc_nt_icon_' . $i ] );

					if ( false != $isize ) {
						$breite = $isize[0];
						$hoehe  = $isize[1];
					}
				}

				if ( 1 == $wpf_vars['csssprites'] ) { // mit CSS Sprites.
					$cssid = substr( $w[ 'fc_nt_icon_' . $i ], strpos( $w[ 'fc_nt_icon_' . $i ], '/' ) + 1, strrpos( $w[ 'fc_nt_icon_' . $i ], '.' ) - strpos( $w[ 'fc_nt_icon_' . $i ], '/' ) - 1 );
					$out1 .= "<div class='wp-forecast-curr-left wpfico" . esc_attr( $cssid ) . "'>&nbsp;</div>\n";
				} else { // ohne CSS-Sprites.
					$out1 .= "<img class='wp-forecast-fc-left' src='" . esc_attr( $plugin_path ) . '/' . esc_attr( $w[ 'fc_nt_icon_' . $i ] ) . "' alt='" . wpf__( $w[ 'fc_nt_iconcode_' . $i ], $wpf_vars['wpf_language'] ) . "' width='$breite' height='$hoehe' />";
				}
			} else {
				$out1 .= '&nbsp;';
			}
			$out1 .= '<br />';

			// show windicon.
			if ( isset( $windicon ) && '1' == $windicon ) {
				$breite        = 48;
				$hoehe         = 48;
				$wind_icon_url = $plugin_path . '/icons/wpf-' . $w[ 'fc_nt_winddir_orig_' . $i ] . '.png';
				$out1         .= "<img src='" . esc_url( $wind_icon_url ) . "' alt='" . esc_attr( $w[ 'fc_nt_winddir_' . $i ] ) . "' width='" . esc_attr( $breite ) . "' height='" . esc_attr( $hoehe ) . "' />\n";
			}
			$out1 .= "\n</div>\n<div class='wp-forecast-fc-right'>";

			// show short description.
			if ( substr( $wpf_vars['dispconfig'], 15, 1 ) == '1' ) {
				$out1 .= '<div>' . esc_attr( wpf__( $w[ 'fc_nt_desc_' . $i ], $wpf_vars['wpf_language'] ) ) . '</div>';
			}

			// show temperature.
			if ( substr( $wpf_vars['dispconfig'], 16, 1 ) == '1' ) {
				$out1 .= '<div>' . esc_attr( $w[ 'fc_nt_ltemp_' . $i ] ) . '</div>';
			}

			// show wind.
			if ( substr( $wpf_vars['dispconfig'], 17, 1 ) == '1' ) {
				$out1 .= '<div>' . wpf__( 'Winds', $wpf_vars['wpf_language'] ) . ': ' . esc_attr( $w[ 'fc_nt_windspeed_' . $i ] ) . ' ' . esc_attr( $w[ 'fc_nt_winddir_' . $i ] ) . '</div>';
			}

			// show windgusts.
			if ( substr( $wpf_vars['dispconfig'], 24, 1 ) == '1' ) {
				$out1 .= '<div>' . wpf__( 'Windgusts', $wpf_vars['wpf_language'] ) . ': ' . esc_attr( $w[ 'fc_nt_wgusts_' . $i ] ) . "</div>\n";
			}

			// show max uv index.
			if ( substr( $wpf_vars['dispconfig'], 29, 1 ) == '1' ) {
				$out1 .= '<div>' . wpf__( 'max. UV-Index', $wpf_vars['wpf_language'] ) . ': ' . esc_attr( $w[ 'fc_nt_maxuv_' . $i ] ) . "</div>\n";
			}
			$out1 .= "</div></div>\n"; // end of wp-forecast-fc-right / block.
		}
		// close div block.

		if ( substr( $wpf_vars['daytime'], $i - 1, 1 ) == '1' || substr( $wpf_vars['nighttime'], $i - 1, 1 ) == '1' ) {
			$out1 .= "</div>\n";
		}
		// store first shown forecast in case pulldown is active.

		if ( isset( $wpf_vars['pdforecast'] ) && 1 == $wpf_vars['pdforecast'] && $wpf_vars['pdfirstday'] == $i ) {
			$out2 = $out1 . "</div>\n";
		}
	}
	$out1 .= "</div>\n"; // end of wp-forecast-fc.

	// wrap a div around for pulldown and switch off complete forecast.

	// mark ids: wpfbl wpfc1 wpfc2 wpfbm with widget id have disjunct ods when using more than one pulldown widget.
	if ( isset( $wpf_vars['pdforecast'] ) && 1 == $wpf_vars['pdforecast'] ) {
		$out1 .= "<div class='wpff_nav' id='wpfbl1" . esc_attr( $wpfcid ) . "' onclick=\"document.getElementById('wpfc1" . esc_attr( $wpfcid ) . "').style.display='none';document.getElementById('wpfc2" . $wpfcid . "').style.display='block';return false;\">" . wpf__( 'Less forecast...', $wpf_vars['wpf_language'] ) . "</div>\n";
		$out1  = "<div class='wpff_nav' id='wpfbl2" . esc_attr( $wpfcid ) . "' onclick=\"document.getElementById('wpfc1" . esc_attr( $wpfcid ) . "').style.display='none';document.getElementById('wpfc2" . $wpfcid . "').style.display='block';return false;\">" . wpf__( 'Less forecast...', $wpf_vars['wpf_language'] ) . "</div>\n" . $out1;
		$out2 .= "<div class='wpff_nav' id='wpfbm" . esc_attr( $wpfcid ) . "' onclick=\"document.getElementById('wpfc2" . esc_attr( $wpfcid ) . "').style.display='none';document.getElementById('wpfc1" . $wpfcid . "').style.display='block';return false;\">" . wpf__( 'More forecast...', $wpf_vars['wpf_language'] ) . "</div>\n";
		$out1  = '<div id="wpfc1' . esc_attr( $wpfcid ) . '"  style="display:none;">' . $out1 . "</div>\n";
		$out2  = '<div id="wpfc2' . esc_attr( $wpfcid ) . '"  style="display:block;">' . $out2 . "</div>\n";
	}

	// print it.
	if ( 1 == $show_from_widget ) {
		echo wp_kses( $args['before_widget'] . $args['before_title'] . $wpf_vars['title'] . $args['after_title'], wpf_allowed_tags() );
	}
	echo '<div id="wp-forecast' . esc_attr( $wpfcid ) . '" class="wp-forecast">' . wp_kses( $fout . $out . $out1 . $out2, wpf_allowed_tags() ) . '</div>' . "\n";
	// if called as iframe hide pulldown content.
	if ( 0 == $show_from_widget && 1 == $wpf_vars['pdforecast'] ) {
		echo "<script>document.getElementById('wpfc1" . esc_attr( $wpfcid ) . "').style.display='none';document.getElementById('wpfc2" . esc_attr( $wpfcid ) . "').style.display='block';</script>";
	}
	// to come back to theme floating status.
	echo '<div style="clear:inherit;">&nbsp;</div>';

	if ( 1 == $show_from_widget ) {
		echo wp_kses( $args['after_widget'], wpf_allowed_tags() );
	}
}
