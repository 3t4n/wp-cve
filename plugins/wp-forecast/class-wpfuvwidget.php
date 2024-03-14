<?php
/**
 * Class for UV widget displaying the data from OpenUV.com
 *
 * @package wp-forecast
 */

if ( ! class_exists( 'WpfUvWidget' ) ) {
	/**
	 * Class for UV widget displaying the data from OpenUV.com
	 */
	class WpfUvWidget extends WP_Widget {
		/**
		 * Constructor
		 */
		public function __construct() {
			$widget_ops  = array(
				'classname'   => 'wp_forecast_uv_widget',
				'description' => 'WP Forecast UV-Widget',
			);
			$control_ops = array(
				'width'  => 300,
				'height' => 150,
			);
			parent::__construct( 'wp-forecast-uv', 'WP Forecast UV', $widget_ops, $control_ops );
		}
		/**
		 * Instantiate widget
		 *
		 * @param array $args Widgetparameters.
		 * @param array $instance Instance of the widget.
		 */
		public function widget( $args, $instance ) {
			// get widget params from instance.
			$title        = ( isset( $instance['title'] ) ? $instance['title'] : '' );
			$wpfcid       = ( isset( $instance['wpfcid'] ) ? $instance['wpfcid'] : '' );
			$safeexposure = ( isset( $instance['safeexposure'] ) ? esc_attr( $instance['safeexposure'] ) : '' );
			$showgraph    = ( isset( $instance['showgraph'] ) ? esc_attr( $instance['showgraph'] ) : '' );

			if ( trim( $wpfcid ) == '' ) {
				$wpfcid = 'A';
			}
			// pass title to show function.
			$args['title'] = $title;

			if ( '?' == $wpfcid ) {
				$wpf_vars = get_wpf_opts( 'A' );
			} else {
				$wpf_vars = get_wpf_opts( $wpfcid );
			}

			if ( ! empty( $language_override ) ) {
				$wpf_vars['wpf_language'] = $language_override;
			}
			// call display method.
			$this->show( $wpfcid, $args, $wpf_vars, $showgraph, $safeexposure );
		}

		/**
		 * Update the values from the form for the widget.
		 *
		 * @param array $new_instance New instance of th widget.
		 * @param array $old_instance Old instance of the widget.
		 */
		public function update( $new_instance, $old_instance ) {
			// update semaphor counter for loading wpf ajax script.

			if ( $old_instance['wpfcid'] != $new_instance['wpfcid'] ) {
				$semnow = get_option( 'wpf_sem_ajaxload' );

				if ( '?' == $new_instance['wpfcid'] ) {
					update_option( 'wpf_sem_ajaxload', $semnow + 1 );
				} else {
					update_option( 'wpf_sem_ajaxload', ( $semnow - 1 < 0 ? 0 : $semnow - 1 ) );
				}
			}
			return $new_instance;
		}
		/**
		 * Show the form.
		 *
		 * @param array $instance The instance of the widget.
		 */
		public function form( $instance ) {
			$count = wpf_get_option( 'wp-forecast-count' );
			// get translation.
			$locale = get_locale();

			if ( empty( $locale ) ) {
				$locale = 'en_US';
			}

			if ( function_exists( 'load_plugin_textdomain' ) ) {
				add_filter( 'plugin_locale', 'wpf_lplug', 10, 2 );
				load_plugin_textdomain( 'wp-forecast_' . $locale, false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
				remove_filter( 'plugin_locale', 'wpf_lplug', 10, 2 );
			}
			$title        = ( isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '' );
			$wpfcid       = ( isset( $instance['wpfcid'] ) ? esc_attr( $instance['wpfcid'] ) : '' );
			$safeexposure = ( isset( $instance['safeexposure'] ) ? esc_attr( $instance['safeexposure'] ) : '' );
			$showgraph    = ( isset( $instance['showgraph'] ) ? esc_attr( $instance['showgraph'] ) : '' );

			// code for widget title form.
			$out  = '';
			$out .= '<p><label for="' . $this->get_field_id( 'title' ) . '" >';
			$out .= wpf__( 'Title:', $locale );
			$out .= '<input class="widefat" id="' . $this->get_field_id( 'title' ) . '" name="' . $this->get_field_name( 'title' ) . '" type="text" value="' . $title . '" /></label></p>';
			// print out widget selector.
			$out .= '<p><label for ="' . $this->get_field_id( 'wpfcid' ) . '" >';
			$out .= wpf__( 'Available widgets', $locale );
			$out .= "<select name='" . $this->get_field_name( 'wpfcid' ) . "' id='" . $this->get_field_id( 'wpfcid' ) . "' size='1' >";
			// option for choose dialog.
			$out .= "<option value='?' ";

			if ( '?' == $wpfcid ) {
				$out .= " selected='selected' ";
			}
			$out .= '>?</option>';

			for ( $i = 0;$i < $count;$i++ ) {
				$id   = get_widget_id( $i );
				$out .= "<option value='" . $id . "' ";

				if ( $wpfcid == $id || ( '' == $wpfcid && 'A' == $id ) ) {
					$out .= " selected='selected' ";
				}
				$out .= '>' . $id . '</option>';
			}
			$out    .= '</select></label></p>';
			$out    .= '<p><label for ="' . $this->get_field_id( 'showgraph' ) . '" >';
			$out    .= wpf__( 'Show UV graph:', $locale );
			$checked = ( 1 == $showgraph ? "checked='checked'" : '' );
			$out    .= '<input type="checkbox" name="' . $this->get_field_name( 'showgraph' ) . '" id="' . $this->get_field_id( 'showgraph' ) . '" value="1" ' . $checked . '>';
			$out    .= '</label></p>';
			$out    .= '<p><label for ="' . $this->get_field_id( 'safeexposure' ) . '" >';
			$out    .= wpf__( 'Show safe exposure time:', $locale );
			$checked = ( 1 == $safeexposure ? "checked='checked'" : '' );
			$out    .= '<input type="checkbox" name="' . $this->get_field_name( 'safeexposure' ) . '" id="' . $this->get_field_id( 'sageexposure' ) . '" value="1" ' . $checked . '>';
			$out    .= '</label></p>';
			echo wp_kses( $out, wpf_allowed_tags() );
		}
		/**
		 * Show the widget.
		 *
		 * @param string $wpfcid       The Widget ID.
		 * @param array  $args         The Widget arguments.
		 * @param array  $wpf_vars     The wp-forecast parameters.
		 * @param bool   $showgraph    Show the graph.
		 * @param bool   $safeexposure Show the safe exposure time for the skin.
		 */
		public function show( $wpfcid, $args, $wpf_vars, $showgraph, $safeexposure ) {
			// check how we are called as a widget or from sidebar.

			if ( count( $args ) == 0 ) {
				$show_from_widget = 0;
			} else {
				$show_from_widget = 1;
			}

			// take title from widget args.
			$title = '';
			if ( isset( $args['title'] ) ) {
				// remember title from widget.
				$wpf_vars['title'] = $args['title'];
				// apply wp default filters to title for output.
				$title = apply_filters( 'widget_title', $args['title'] );
			}

			// get translations.
			if ( function_exists( 'load_plugin_textdomain' ) ) {
				add_filter( 'plugin_locale', 'wpf_lplug', 10, 2 );
				load_plugin_textdomain( 'wp-forecast_' . $wpf_vars['wpf_language'], false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
				remove_filter( 'plugin_locale', 'wpf_lplug', 10, 2 );
			}
			$plugin_path = plugins_url( '', __FILE__ );
			$w           = wp_forecast_data( $wpfcid, $wpf_vars['wpf_language'] );
			// output current conditions.
			$out  = '';
			$out .= "\n<div class=\"wp-forecast-curr\">\n";

			// if the provider sends us a failure notice print it and return.
			if ( '' != $w['failure'] ) {
				$out .= wpf_esc_attr__( 'Failure notice from provider', $wpf_vars['wpf_language'] ) . ':<br />';
				$out .= $w['failure'] . '</div>';

				// print it.
				if ( 1 == $show_from_widget ) {
					echo wp_kses( $before_widget . $before_title . $title . $after_title . $out . $after_widget, wpf_allowed_tags() );
				} else {
					echo wp_kses( $out, wpf_allowed_tags() );
				}
				return false;
			}
			// if error print an error message and return.

			if ( count( $w ) <= 0 ) {
				$out .= wpf_esc_attr__( 'Sorry, no valid weather data available.', $wpf_vars['wpf_language'] ) . '<br />';
				$out .= wpf_esc_attr__( 'Please try again later.', $wpf_vars['wpf_language'] ) . '</div>';
				// print it.

				if ( 1 == $show_from_widget ) {
					echo wp_kses( $before_widget . $before_title . $title . $after_title . $out . $after_widget, wpf_allowed_tags() );
				} else {
					echo wp_kses( $out, wpf_allowed_tags() );
				}
				return false;
			}
			// ortsnamen ausgeben parameter fuer open in new window berücksichtigen.
			$servicelink     = '';
			$servicelink_end = '';

			if ( substr( $wpf_vars['dispconfig'], 25, 1 ) == '1' ) {
				$servicelink     = '<a href="' . $w['servicelink'] . '"';
				$servicelink_end = '</a>';

				if ( substr( $wpf_vars['dispconfig'], 26, 1 ) == '1' ) {
					$servicelink = $servicelink . ' target="_blank" >';
				} else {
					$servicelink = $servicelink . ' >';
				}
			}
			$out .= '<div class="wp-forecast-curr-head">';

			if ( '' == $w['location'] || 1 == $wpf_vars['visitorlocation'] ) {
				$out .= '<div>' . $servicelink . $w['locname'] . $servicelink_end . "</div>\n";
			} elseif ( '' != trim( $w['location'] ) && '&nbsp;' != $w['location'] ) {
				$out .= '<div>' . $servicelink . $w['location'] . $servicelink_end . "</div>\n";
			}
			// show date / time.

			// if current time should be used.
			if ( '1' == $wpf_vars['currtime'] ) {
				$cd = $w['blogdate'];
				$ct = $w['blogtime'];
			} else {
				// else take given accuweather time.
				$cd = $w['accudate'];
				$ct = $w['accutime'];
			}

			if ( substr( $wpf_vars['dispconfig'], 18, 1 ) == '1' || substr( $wpf_vars['dispconfig'], 1, 1 ) == '1' ) {
				$out .= '<div>';

				if ( substr( $wpf_vars['dispconfig'], 18, 1 ) == '1' ) {
					$out .= $cd;
				} else {
					$out .= wpf__( 'time', $wpf_vars['wpf_language'] ) . ': ';
				}

				if ( substr( $wpf_vars['dispconfig'], 18, 1 ) == '1' && substr( $wpf_vars['dispconfig'], 1, 1 ) == '1' ) {
					$out .= ', ';
				}

				if ( substr( $wpf_vars['dispconfig'], 1, 1 ) == '1' ) {
					$out .= $ct;
				}
				$out .= "</div>\n";
			}
			$out .= "</div>\n";
			$out .= '<div class="wp-forecast-curr-block">';
			// show icon.
			$out .= "<div class='wp-forecast-curr-left'>";

			$uviconno = ( isset( $w['openuv']['uv'] ) ? round( $w['openuv']['uv'] ) : 0 );
			$uvicon   = 'UVIndex' . $uviconno . '.jpg';
			$breite   = 0;
			$hoehe    = 0;

			if ( file_exists( plugin_dir_path( __FILE__ ) . '/icons/' . $uvicon ) ) {
				$isize = getimagesize( plugin_dir_path( __FILE__ ) . '/icons/' . $uvicon );

				if ( false != $isize ) {
					$breite = $isize[0];
					$hoehe  = $isize[1];
				}
			}
			$out .= "<img class='awp-forecast-curr-left' src='" . $plugin_path . '/icons/' . $uvicon . "' alt='" . $w['shorttext'] . "' width='" . $breite . "' height='" . $hoehe . "' />\n";

			$out .= '<br />';
			$out .= '</div>';
			$out .= "</div>\n"; // end of block.
			$out .= '<div class="wp-forecast-curr-details">';
			// show data from OpenUV.io if applicable.

			if ( trim( $wpf_vars['ouv_apikey'] ) != '' ) {

				if ( $wpf_vars['ouv_uv'] ) {
					$out .= '<div>' . wpf__( 'Current UV-Index', $wpf_vars['wpf_language'] ) . ': ' . $w['openuv']['uv'] . "</div>\n";
				}

				if ( $wpf_vars['ouv_uvmax'] ) {
					$out .= '<div>' . wpf__( 'Max. UV-Index', $wpf_vars['wpf_language'] ) . ': ' . $w['openuv']['uv_max'] . "</div>\n";
				}

				if ( $wpf_vars['ouv_ozone'] ) {
					$out .= '<div>' . wpf__( 'Ozone', $wpf_vars['wpf_language'] ) . ': ' . $w['openuv']['ozone'] . " DU</div>\n";
				}
				$out .= '<br/>';

				if ( $wpf_vars['ouv_safetime'] || $safeexposure ) {
					$j = 1;

					foreach ( $w['openuv']['safe_exposure_time'] as $set ) {

						if ( trim( $set ) != '' ) {
							$out .= '<div>' . wpf__( 'Safe Exposure Time for Skin Type', $wpf_vars['wpf_language'] ) . " $j: " . $set . " Min.</div>\n";
						} else {
							$out .= '<div>' . wpf__( 'Safe Exposure Time for Skin Type', $wpf_vars['wpf_language'] ) . " $j: &infin; Min.</div>\n";
						}
						$j++;
					}
					$out .= '<br/>';
				}

				if ( $showgraph ) {
					$count_values = count( $w['openuv']['forecast'] );
					// determine min and max values.
					$uvmin = 99;
					$uvmax = 0;
					$data  = array();

					foreach ( $w['openuv']['forecast'] as $uvfc ) {

						if ( $uvfc['uv'] > $uvmax ) {
							$uvmax = $uvfc['uv'];
						}

						if ( $uvfc['uv'] < $uvmin ) {
							$uvmin = $uvfc['uv'];
						}
						$d                               = new DateTime( $uvfc['uv_time'] );
						$data[ (int) $d->format( 'H' ) ] = round( $uvfc['uv'], 1 ) * 10;
					}
					$scale_ymax = ( (int) $uvmax + 1 ) * 10;
					// print out diagram.
					$out .= '<style>';
					$out .= 'div.wpf-uv-bar-on    {float: left; width: 15px; height: 5px; background-color: #666666; margin: 1px;}';
					$out .= 'div.wpf-uv-bar-off   {float: left; width: 15px; height: 5px; background-color: rgb(255,255,255,0); margin: 1px;}';
					$out .= 'div.wpf-uv-row-clear {clear: both;}';
					$out .= 'div.wpf-uv-bar-left  {float:left; width: 20px; height: 5px; border-style: none none none none; border-color: #335588; border-width: 3px;}';
					$out .= 'div.wpf-uv-bar-right {float:left; width: 20px; height: 5px; border-style: none none none solid; border-color: #335588; border-width: 3px;}';
					$out .= 'div.wpf-uv-row-left  {float:left; width: 20px; height: 5px; border-style: solid none none none; border-color: #335588; border-width: 3px;margin-top:7px;}';
					$out .= '</style>';

					for ( $i = $scale_ymax;$i >= 0;$i-- ) {

						if ( 0 == $i % 10 ) {
							$out .= "<div class='wpf-uv-bar-left'>" . (int) ( $i / 10 ) . '</div>';
						} else {
							$out .= "<div class='wpf-uv-bar-left'>&nbsp;</div>";
						}

						foreach ( $data as $dk => $dv ) {

							if ( $dv >= $i ) {
								$out .= "<div class='wpf-uv-bar-on'>&nbsp;</div>";
							} else {
								$out .= "<div class='wpf-uv-bar-off'>&nbsp;</div>";
							}
						}
						$out .= "<div class='wpf-uv-row-clear'></div>";
					}
					// print x -axis.
					$out .= "<div class='wpf-uv-row-left'>&nbsp;</div>";

					foreach ( $data as $dk => $dv ) {
						$out .= "<div class='wpf-uv-row-left'>$dk</div>";
					}
					$out .= "<div class='wpf-uv-row-clear'></div><br/>";
					$d    = new DateTime( $w['openuv']['forecast'][0]['uv_time'] );
					$d1   = new DateTime( $w['openuv']['forecast'][ count( $w['openuv']['forecast'] ) - 1 ]['uv_time'] );
					$out .= '<div>Werte vom ' . $d->format( 'd.m.y' ) . ' von ' . $d->format( 'H:i' ) . ' bis ' . $d1->format( 'H:i' ) . ' Uhr.</div>';
				}
				// print copyright notice.
				$out .= "<div class='wpf-uv-row-clear'></div><br/>";
				$out .= '<div class="wp-forecast-copyright">' . wpf__( $w['openuv']['copyright'], $wpf_vars['wpf_language'] ) . '</div>';
			}
			// show copyright.

			if ( substr( $wpf_vars['dispconfig'], 21, 1 ) == '1' ) {
				$out .= '<div class="wp-forecast-copyright">' . $w['copyright'] . '</div>';
			}
			$out .= "</div>\n"; // end of details.
			$out .= "</div>\n"; // end of curr.

			// print it.
			if ( 1 == $show_from_widget ) {
				echo wp_kses( $args['before_widget'] . $args['before_title'] . $title . $args['after_title'], wpf_allowed_tags() );
			}
			echo '<div id="wp-forecast' . esc_attr( $wpfcid ) . '" class="wp-forecast">' . wp_kses( $out, wpf_allowed_tags() ) . '</div>' . "\n";
			// to come back to theme floating status.
			echo '<div style="clear:inherit;">&nbsp;</div>';

			if ( 1 == $show_from_widget ) {
				echo wp_kses( $args['after_widget'], wpf_allowed_tags() );
			}
		}
	}
}
