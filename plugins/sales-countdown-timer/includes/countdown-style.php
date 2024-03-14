<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SALES_COUNTDOWN_TIMER_Countdown_Style {
	public static $settings;

	public function __construct() {
		self::$settings = new  SALES_COUNTDOWN_TIMER_Data();
	}

	public static function get_frontend_countdown_css() {
		self::$settings = new  SALES_COUNTDOWN_TIMER_Data();
		$shortcode_ids  = self::$settings->get_id();
		$css            = '';
		if ( $shortcode_ids && is_array( $shortcode_ids ) && $count_ids = count( $shortcode_ids ) ) {
			for ( $i = 0; $i < $count_ids; $i ++ ) {
				if ( empty( self::$settings->get_active()[ $i ] ) ) {
					continue;
				}
				$id           = $shortcode_ids[ $i ];
				$display_type = self::$settings->get_display_type()[ $i ];
				$countdown_timer_color              = self::$settings->get_countdown_timer_color()[ $i ] ?? '';
				$countdown_timer_bg_color              = self::$settings->get_countdown_timer_bg_color()[ $i ] ??'';
				$countdown_timer_padding              = self::$settings->get_countdown_timer_padding()[ $i ]??'';
				$countdown_timer_border_radius              = self::$settings->get_countdown_timer_border_radius()[ $i ]??'';
				$countdown_timer_border_color              = self::$settings->get_countdown_timer_border_color()[ $i ]??'';
				$datetime_value_color              = self::$settings->get_datetime_value_color()[ $i ]??'';
				$datetime_value_bg_color              = self::$settings->get_datetime_value_bg_color()[ $i ]??'';
				$datetime_value_font_size              = self::$settings->get_datetime_value_font_size()[ $i ]??'';
				$datetime_unit_color              = self::$settings->get_datetime_unit_color()[ $i ]??'';
				$datetime_unit_bg_color              = self::$settings->get_datetime_unit_bg_color()[ $i ]??'';
				$datetime_unit_font_size              = self::$settings->get_datetime_unit_font_size()[ $i ]??'';
				$circle_smooth_animation              = self::$settings->get_circle_smooth_animation()[ $i ]??'1';
				$countdown_timer_item_border_color              = self::$settings->get_countdown_timer_item_border_color()[ $i ]??'';
				if ( $display_type == 4 ) {
					if ( $circle_smooth_animation == 1 ) {
						$css .= '.woo-sctr-shortcode-wrap-wrap.woo-sctr-shortcode-wrap-wrap-' . $id . ' .woo-sctr-shortcode-countdown-1 .woo-sctr-progress-circle .woo-sctr-value-bar{transition: transform 1s linear;}';
					}
					$css .= '.woo-sctr-shortcode-wrap-wrap.woo-sctr-shortcode-wrap-wrap-' . $id . ' .woo-sctr-shortcode-countdown-1{';
					if ( $countdown_timer_color ) {
						$css .= esc_attr__( 'color:' ) . $countdown_timer_color . ';';
					}
					if ( $countdown_timer_bg_color ) {
						$css .= esc_attr__( 'background:' ) . $countdown_timer_bg_color . ';';
					}
					if ( $countdown_timer_padding ) {
						$css .= esc_attr__( 'padding:' ) . $countdown_timer_padding . 'px;';
					}
					if ( $countdown_timer_border_radius ) {
						$css .= esc_attr__( 'border-radius:' ) . $countdown_timer_border_radius . 'px;';
					}
					if ( $countdown_timer_bg_color ) {
						$css .= '.woo-sctr-shortcode-wrap-wrap.woo-sctr-shortcode-wrap-wrap-' . $id . '.woo-sctr-sticky-top{' . esc_attr__( 'background:' ) . $countdown_timer_bg_color . ';}';
					}

					$css .= '}';
					if ( $countdown_timer_item_border_color ) {
						$css .= '.woo-sctr-shortcode-wrap-wrap.woo-sctr-shortcode-wrap-wrap-' . $id . ' .woo-sctr-shortcode-countdown-1 .woo-sctr-progress-circle .woo-sctr-value-bar{' . esc_attr__( 'border-color: ' ) . $countdown_timer_item_border_color . ';}';
						$css .= '.woo-sctr-shortcode-wrap-wrap.woo-sctr-shortcode-wrap-wrap-' . $id . ' .woo-sctr-shortcode-countdown-1 .woo-sctr-progress-circle .woo-sctr-first50-bar{' . esc_attr__( 'background-color: ' ) . $countdown_timer_item_border_color . ';}';
					}
					$css .= '.woo-sctr-shortcode-wrap-wrap.woo-sctr-shortcode-wrap-wrap-' . $id . ' .woo-sctr-shortcode-countdown-1 .woo-sctr-shortcode-countdown-value-container,.woo-sctr-shortcode-wrap-wrap.woo-sctr-shortcode-wrap-wrap-' . $id . ' .woo-sctr-shortcode-countdown-1 .woo-sctr-shortcode-countdown-value{';
					if ( $datetime_value_color ) {
						$css .= esc_attr__( 'color:' ) . $datetime_value_color . ';';
					}
					$css .= '}';
					if ( $datetime_value_bg_color ) {
						$css .= '.woo-sctr-shortcode-wrap-wrap.woo-sctr-shortcode-wrap-wrap-' . $id . ' .woo-sctr-shortcode-countdown-1 .woo-sctr-progress-circle:after{' . esc_attr__( 'background:' ) . $datetime_value_bg_color . ';}';
					}
					if ( $datetime_value_font_size ) {
						$css .= '.woo-sctr-shortcode-wrap-wrap.woo-sctr-shortcode-wrap-wrap-' . $id . ' .woo-sctr-shortcode-countdown-1 .woo-sctr-progress-circle{';
						$css .= esc_attr__( 'font-size: ' ) . $datetime_value_font_size . 'px;';
						$css .= '}';
					}

					$css .= '.woo-sctr-shortcode-wrap-wrap.woo-sctr-shortcode-wrap-wrap-' . $id . ' .woo-sctr-shortcode-countdown-1 .woo-sctr-shortcode-countdown-text{';
					if ( $datetime_unit_color ) {
						$css .= esc_attr__( 'color:' ) . $datetime_unit_color . ';';
					}
					if ( $datetime_unit_bg_color ) {
						$css .= esc_attr__( 'background:' ) . $datetime_unit_bg_color . ';';
					}
					if ( $datetime_unit_font_size ) {
						$css .= esc_attr__( 'font-size:' ) . $datetime_unit_font_size . 'px;';
					}

					$css .= '}';
				} else {
					if ( $display_type == 1 || $display_type == 2 ) {
						$css .= '.woo-sctr-shortcode-wrap-wrap.woo-sctr-shortcode-wrap-wrap-' . $id . '{display:block;text-align:center;}';
					}
					if ( $display_type == 3 ) {
						$css .= '.woo-sctr-shortcode-wrap-wrap.woo-sctr-shortcode-wrap-wrap-' . $id . '.woo-sctr-shortcode-wrap-wrap-inline{';
						if ( $countdown_timer_color ) {
							$css .= esc_attr__( 'color:' ) . $countdown_timer_color . ';';
						}
						if ( $countdown_timer_bg_color ) {
							$css .= esc_attr__( 'background:' ) . $countdown_timer_bg_color . ';';
						}
						if ( $countdown_timer_padding ) {
							$css .= esc_attr__( 'padding:' ) . $countdown_timer_padding . 'px;';
						}
						if ( $countdown_timer_border_radius ) {
							$css .= esc_attr__( 'border-radius:' ) . $countdown_timer_border_radius . 'px;';
						}
						if ( $countdown_timer_border_color ) {
							$css .= esc_attr__( 'border: 1px solid ' ) . $countdown_timer_border_color . ';';
						}
						$css .= '}';
					} else {
						$css .= '.woo-sctr-shortcode-wrap-wrap.woo-sctr-shortcode-wrap-wrap-' . $id . ' .woo-sctr-shortcode-countdown-1{';
						if ( $countdown_timer_color ) {
							$css .= esc_attr__( 'color:' ) . $countdown_timer_color . ';';
						}
						if ( $countdown_timer_bg_color ) {
							$css .= esc_attr__( 'background:' ) . $countdown_timer_bg_color . ';';
						}
						if ( $countdown_timer_padding ) {
							$css .= esc_attr__( 'padding:' ) . $countdown_timer_padding . 'px;';
						}
						if ( $countdown_timer_border_radius ) {
							$css .= esc_attr__( 'border-radius:' ) . $countdown_timer_border_radius . 'px;';
						}
						if ( $countdown_timer_border_color ) {
							$css .= esc_attr__( 'border: 1px solid ' ) . $countdown_timer_border_color . ';';
						}
						$css .= '}';
					}
					if ( $countdown_timer_bg_color ) {
						$css .= '.woo-sctr-shortcode-wrap-wrap.woo-sctr-shortcode-wrap-wrap-' . $id . '.woo-sctr-sticky-top{' . esc_attr__( 'background:' ) . $countdown_timer_bg_color . ';}';
					}
					$css .= '.woo-sctr-shortcode-wrap-wrap.woo-sctr-shortcode-wrap-wrap-' . $id . ' .woo-sctr-shortcode-countdown-1 .woo-sctr-shortcode-countdown-value,.woo-sctr-shortcode-wrap-wrap.woo-sctr-shortcode-wrap-wrap-' . $id . ' .woo-sctr-shortcode-countdown-1 .woo-sctr-shortcode-countdown-value-container{';
					if ( $datetime_value_color ) {
						$css .= esc_attr__( 'color:' ) . $datetime_value_color . ';';
					}
					if ( $datetime_value_bg_color ) {
						$css .= esc_attr__( 'background:' ) . $datetime_value_bg_color . ';';
					}
					if ( $datetime_value_font_size ) {
						$css .= esc_attr__( 'font-size:' ) . $datetime_value_font_size . 'px;';
					}
					$css .= '}';
					$css .= '.woo-sctr-shortcode-wrap-wrap.woo-sctr-shortcode-wrap-wrap-' . $id . ' .woo-sctr-shortcode-countdown-1 .woo-sctr-shortcode-countdown-text{';
					if ( $datetime_unit_color ) {
						$css .= esc_attr__( 'color:' ) . $datetime_unit_color . ';';
					}
					if ( $datetime_unit_bg_color ) {
						$css .= esc_attr__( 'background:' ) . $datetime_unit_bg_color . ';';
					}
					if ( $datetime_unit_font_size ) {
						$css .= esc_attr__( 'font-size:' ) . $datetime_unit_font_size . 'px;';
					}
					$css  .= '}';
				}
			}
		}

		return $css;
	}


	private static function add_inline_style( $element, $i, $name, $style, $suffix = '' ) {
		$element = is_array( $element ) ? implode( ',', $element ) : $element;
		$return  = $element . '{';
		if ( is_array( $name ) && count( $name ) ) {
			foreach ( $name as $key => $value ) {
				$get_value  = self::$settings->get_current_countdown( $name[ $key ], $i );
				$get_suffix = isset( $suffix[ $key ] ) ? $suffix[ $key ] : '';
				if ( $get_value ) {
					$return .= $style[ $key ] . ':' . $get_value . $get_suffix . ';';
				}
			}
		}
		$return .= '}';

		return $return;
	}

	private static function add_inline_style_reduce( $element, $i, $name, $style, $suffix = '', $reduce = 0, $default = 0 ) {
		$element = is_array( $element ) ? implode( ',', $element ) : $element;
		$return  = $element . '{';
		if ( is_array( $name ) && count( $name ) ) {
			foreach ( $name as $key => $value ) {
				$get_value = self::$settings->get_current_countdown( $name[ $key ], $i );
				if ( $reduce > 0 && $get_value ) {
					if ( $default > 0 ) {
						$get_value = $get_value * $default / 100;
					}
					$get_value = $get_value * $reduce / 100;
				}
				$return .= $style[ $key ] . ':' . $get_value . $suffix[ $key ] . ';';
			}
		}
		$return .= '}';

		return $return;
	}
}