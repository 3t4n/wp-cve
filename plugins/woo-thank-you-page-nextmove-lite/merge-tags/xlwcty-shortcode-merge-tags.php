<?php
defined( 'ABSPATH' ) || exit;

class XLWCTY_ShortCode_Merge_Tags {

	public static $threshold_to_date = 30;

	protected static $_data_shortcode = array();

	/**
	 * Maybe try and parse content to found the xlwcty merge tags
	 * And converts them to the standard wp shortcode way
	 * So that it can be used as do_shortcode in future
	 *
	 * @param string $content
	 *
	 * @return mixed|string
	 */
	public static function maybe_parse_merge_tags( $content = '', $helper_data = false ) {
		$get_all = self::get_all_tags();
		//iterating over all the merge tags
		if ( $get_all && is_array( $get_all ) && count( $get_all ) > 0 ) {
			foreach ( $get_all as $tag ) {
				$matches = array();
				$re      = sprintf( '/\{{%s(.*?)\}}/', $tag );
				$str     = $content;

				//trying to find match w.r.t current tag
				preg_match_all( $re, $str, $matches );

				//if match found
				if ( $matches && is_array( $matches ) && count( $matches ) > 0 ) {

					if ( ! isset( $matches[0] ) ) {
						return;
					}

					//iterate over the found matches
					foreach ( $matches[0] as $exact_match ) {

						//preserve old match
						$old_match = $exact_match;

						$extra_attributes = '';
						if ( $helper_data !== false ) {
							$extra_attributes = " helper_data='" . serialize( $helper_data ) . "'";
						}
						//replace the current tag with the square brackets [shortcode compatible]
						$exact_match = str_replace( '{{' . $tag, '[xlwcty_' . $tag . $extra_attributes, $exact_match );

						$exact_match = str_replace( '}}', ']', $exact_match );

						$content = str_replace( $old_match, $exact_match, $content );
					}
				}
			}
		}

		return $content;
	}

	public static function get_all_tags() {
		$tags = array(
			'current_time',
			'current_date',
			'current_day',
			'today',
			'xlwcty_countdown_timer_admin',
			'order_meta',
			'jilt_post_registration_html',
		);

		return $tags;

	}

	public static function init() {
		add_shortcode( 'xlwcty_current_time', array( __CLASS__, 'process_time' ) );
		add_shortcode( 'xlwcty_current_date', array( __CLASS__, 'process_date' ) );
		add_shortcode( 'xlwcty_today', array( __CLASS__, 'process_today' ) );
		add_shortcode( 'xlwcty_current_day', array( __CLASS__, 'process_day' ) );
		add_shortcode( 'xlwcty_xlwcty_countdown_timer_admin', array( __CLASS__, 'countdown_timer_admin' ) );
		add_shortcode( 'xlwcty_order_meta', array( __CLASS__, 'xlwcty_order_meta' ) );

	}

	public static function process_date( $shortcode_attrs ) {
		$default_f = XLWCTY_Common::xlwcty_get_date_format();
		$atts      = shortcode_atts( array(
			'format'        => $default_f, //has to be user friendly , user will not understand 12:45 PM (g:i A) (https://codex.wordpress.org/Formatting_Date_and_Time)
			'adjustment'    => '',
			'cutoff'        => '',
			'exclude_days'  => '',
			'exclude_dates' => '',
		), $shortcode_attrs );

		$date_obj = new DateTime( 'now', new DateTimeZone( XLWCTY_Common::wc_timezone_string() ) );

		/** cutoff functionality starts */
		if ( $atts['cutoff'] !== '' ) {
			$date_obj_cutoff = new DateTime();
			$parsed_date     = date_parse( $atts['cutoff'] );
			$date_defaults   = array(
				'year'   => $date_obj_cutoff->format( 'Y' ),
				'month'  => $date_obj_cutoff->format( 'm' ),
				'day'    => $date_obj_cutoff->format( 'd' ),
				'hour'   => $date_obj_cutoff->format( 'H' ),
				'minute' => $date_obj_cutoff->format( 'i' ),
				'second' => '00',
			);
			foreach ( $parsed_date as $attrs => &$date_elements ) {
				if ( $date_elements === false && isset( $date_defaults[ $attrs ] ) ) {
					$parsed_date[ $attrs ] = $date_defaults[ $attrs ];
				}
			}
			$parsed_date = wp_parse_args( $parsed_date, $date_defaults );
			$date_obj_cutoff->setTimezone( new DateTimeZone( XLWCTY_Common::wc_timezone_string() ) );
			$date_obj_cutoff->setDate( $parsed_date['year'], $parsed_date['month'], $parsed_date['day'] );
			$date_obj_cutoff->setTime( $parsed_date['hour'], $parsed_date['minute'], $parsed_date['second'] );
			if ( $date_obj->getTimestamp() > $date_obj_cutoff->getTimestamp() ) {
				$date_obj->modify( '+1 days' );
			}
		}

		/**
		 * Pre check
		 */
		$itr = 0;
		while ( $itr < self::$threshold_to_date && ( ( ( $atts['exclude_dates'] !== '' ) && ( self::is_not_excluded_date( $date_obj, $atts['exclude_dates'] ) === false ) ) || ( ( $atts['exclude_days'] !== '' ) && ( self::is_not_excluded_day( $date_obj, $atts['exclude_days'] ) === false ) ) ) ) {
			$date_obj->modify( '+1 day' );
			$itr ++;
		}

		/** Cut-Off functionality Ends */
		if ( $atts['adjustment'] !== '' ) {
			$date_obj->modify( trim( $atts['adjustment'] ) );
		}

		/**
		 * After check
		 */
		$itr = 0;
		while ( $itr < self::$threshold_to_date && ( ( ( $atts['exclude_dates'] !== '' ) && ( self::is_not_excluded_date( $date_obj, $atts['exclude_dates'] ) === false ) ) || ( ( $atts['exclude_days'] !== '' ) && ( self::is_not_excluded_day( $date_obj, $atts['exclude_days'] ) === false ) ) ) ) {

			$date_obj->modify( '+1 day' );
			$itr ++;
		}

		return date_i18n( $atts['format'], $date_obj->getTimestamp() );
	}

	protected static function is_not_excluded_date( $date, $exclusions ) {
		$exclusions         = str_replace( ' ', '', $exclusions );
		$explode_exclusions = explode( ',', $exclusions );
		$explode_exclusions = apply_filters( 'xlwcty_merge_tags_date_exclude_dates', $explode_exclusions, $date );

		if ( in_array( strtolower( $date->format( 'Y-m-d' ) ), $explode_exclusions ) ) {
			return false;
		}

		return true;
	}

	protected static function is_not_excluded_day( $date, $exclusions ) {
		$exclusions         = str_replace( ' ', '', $exclusions );
		$explode_exclusions = explode( ',', $exclusions );
		$explode_exclusions = apply_filters( 'xlwcty_merge_tags_date_exclude_days', $explode_exclusions, $date );
		if ( in_array( strtolower( $date->format( 'l' ) ), $explode_exclusions ) ) {

			return false;
		}

		return true;
	}

	public static function process_day( $shortcode_attrs ) {
		$default_f = XLWCTY_Common::xlwcty_get_date_format();
		$atts      = shortcode_atts( array(
			'adjustment'    => '',
			'cutoff'        => '',
			'exclude_days'  => '',
			'exclude_dates' => '',
		), $shortcode_attrs );
		$date_obj  = new DateTime();
		$date_obj->setTimezone( new DateTimeZone( XLWCTY_Common::wc_timezone_string() ) );

		/** cutoff functionality starts */
		if ( $atts['cutoff'] !== '' ) {
			$date_obj_cutoff = new DateTime();
			$parsed_date     = date_parse( $atts['cutoff'] );
			$date_defaults   = array(
				'year'   => $date_obj_cutoff->format( 'Y' ),
				'month'  => $date_obj_cutoff->format( 'm' ),
				'day'    => $date_obj_cutoff->format( 'd' ),
				'hour'   => $date_obj_cutoff->format( 'H' ),
				'minute' => $date_obj_cutoff->format( 'i' ),
				'second' => '00',
			);
			foreach ( $parsed_date as $attrs => &$date_elements ) {
				if ( $date_elements === false && isset( $date_defaults[ $attrs ] ) ) {
					$parsed_date[ $attrs ] = $date_defaults[ $attrs ];
				}
			}
			$parsed_date = wp_parse_args( $parsed_date, $date_defaults );

			$date_obj_cutoff->setTimezone( new DateTimeZone( XLWCTY_Common::wc_timezone_string() ) );

			$date_obj_cutoff->setDate( $parsed_date['year'], $parsed_date['month'], $parsed_date['day'] );
			$date_obj_cutoff->setTime( $parsed_date['hour'], $parsed_date['minute'], $parsed_date['second'] );

			if ( $date_obj->getTimestamp() > $date_obj_cutoff->getTimestamp() ) {
				$date_obj->modify( '+1 days' );
			}
		}

		//pre check
		$itr = 0;
		/**
		 * iterating all over the recursive check for a valid date
		 */
		while ( $itr < self::$threshold_to_date && ( ( ( $atts['exclude_days'] !== '' ) && ( self::is_not_excluded_date( $date_obj, $atts['exclude_dates'] ) === false ) ) || ( ( $atts['exclude_days'] !== '' ) && ( self::is_not_excluded_day( $date_obj, $atts['exclude_days'] ) === false ) ) ) ) {
			$date_obj->modify( '+1 day' );
			$itr ++;
		}
		/** Cut-Off functionality Ends */
		if ( $atts['adjustment'] !== '' ) {
			$date_obj->modify( $atts['adjustment'] );
		}
		$itr = 0;
		/**
		 * iterating all over the recursive check for a valid date
		 */
		while ( $itr < self::$threshold_to_date && ( ( ( $atts['exclude_days'] !== '' ) && ( self::is_not_excluded_date( $date_obj, $atts['exclude_dates'] ) === false ) ) || ( ( $atts['exclude_days'] !== '' ) && ( self::is_not_excluded_day( $date_obj, $atts['exclude_days'] ) === false ) ) ) ) {
			$date_obj->modify( '+1 day' );
			$itr ++;
		}

		return date_i18n( 'l', $date_obj->getTimestamp() );
	}

	public static function process_today( $shortcode_attrs ) {
		$atts     = shortcode_atts( array(
			'cutoff'        => '',
			'exclude_days'  => '',
			'exclude_dates' => '',
		), $shortcode_attrs );
		$date_obj = new DateTime();
		$date_obj->setTimezone( new DateTimeZone( XLWCTY_Common::wc_timezone_string() ) );
		$date_obj_cutoff = new DateTime();
		/** cutoff functionlity starts */
		if ( $atts['cutoff'] !== '' ) {
			$parsed_date   = date_parse( $atts['cutoff'] );
			$date_defaults = array(
				'year'   => $date_obj_cutoff->format( 'Y' ),
				'month'  => $date_obj_cutoff->format( 'm' ),
				'day'    => $date_obj_cutoff->format( 'd' ),
				'hour'   => $date_obj_cutoff->format( 'H' ),
				'minute' => $date_obj_cutoff->format( 'i' ),
				'second' => '00',
			);
			foreach ( $parsed_date as $attrs => &$date_elements ) {
				if ( $date_elements === false && isset( $date_defaults[ $attrs ] ) ) {
					$parsed_date[ $attrs ] = $date_defaults[ $attrs ];
				}
			}
			$parsed_date = wp_parse_args( $parsed_date, $date_defaults );
			$date_obj_cutoff->setTimezone( new DateTimeZone( XLWCTY_Common::wc_timezone_string() ) );
			$date_obj_cutoff->setDate( $parsed_date['year'], $parsed_date['month'], $parsed_date['day'] );
			$date_obj_cutoff->setTime( $parsed_date['hour'], $parsed_date['minute'], $parsed_date['second'] );
		}

		if ( $date_obj->getTimestamp() > $date_obj_cutoff->getTimestamp() ) {

			$date_obj->modify( '+1 days' );
			$is_excluded = false;

			/**
			 * iterating all over the recursive check for a valid date
			 */
			$itr = 0;
			while ( $itr < self::$threshold_to_date && ( ( ( $atts['exclude_days'] !== '' ) && ( self::is_not_excluded_date( $date_obj, $atts['exclude_dates'] ) === false ) ) || ( ( $atts['exclude_days'] !== '' ) && ( self::is_not_excluded_day( $date_obj, $atts['exclude_days'] ) === false ) ) ) ) {
				;
				$date_obj->modify( '+1 day' );
				$itr ++;
				$is_excluded = true;
			}

			if ( $is_excluded ) {
				return date_i18n( 'l', $date_obj->getTimestamp() );
			} else {
				return __( 'tomorrow', 'woo-thank-you-page-nextmove-lite' );
			}
		} else {
			$is_excluded = false;
			/**
			 * iterating all over the recursive check for a valid date
			 */
			$itr = 0;
			while ( $itr < self::$threshold_to_date && ( ( ( $atts['exclude_days'] !== '' ) && ( self::is_not_excluded_date( $date_obj, $atts['exclude_dates'] ) === false ) ) || ( ( $atts['exclude_days'] !== '' ) && ( self::is_not_excluded_day( $date_obj, $atts['exclude_days'] ) === false ) ) ) ) {
				$date_obj->modify( '+1 day' );
				$is_excluded = true;
				$itr ++;
			}
			if ( $is_excluded ) {
				return date_i18n( 'l', $date_obj->getTimestamp() );
			} else {
				return __( 'today', 'woo-thank-you-page-nextmove-lite' );
			}
		}
	}

	public static function process_time( $shortcode_attrs ) {
		$default_f = XLWCTY_Common::xlwcty_get_time_format();
		$atts      = shortcode_atts( array(
			'format'     => $default_f, //has to be user friendly , user will not understand 12:45 PM (g:i A) (https://codex.wordpress.org/Formatting_Date_and_Time)
			'adjustment' => '',
		), $shortcode_attrs );

		$date_obj = new DateTime();
		$date_obj->setTimezone( new DateTimeZone( XLWCTY_Common::wc_timezone_string() ) );
		if ( $atts['adjustment'] !== '' ) {
			$date_obj->modify( $atts['adjustment'] );
		}

		return date_i18n( $atts['format'], $date_obj->getTimestamp() );
	}

	public static function countdown_timer_admin( $shortcode_attrs ) {
		return '<div class="xlwcty_countdown_timer_admin" data-timer="3600"></div>';
	}


	public static function xlwcty_order_meta( $shortcode_attrs ) {
		$atts = shortcode_atts( array(
			'key'   => '', //has to be user friendly , user will not understand 12:45 PM (g:i A) (https://codex.wordpress.org/Formatting_Date_and_Time)
			'label' => '',
		), $shortcode_attrs );

		if ( $atts['key'] === '' ) {
			return __return_empty_string();
		}

		$order = XLWCTY_Core()->data->get_order();
		if ( ! $order instanceof WC_Order ) {
			return __return_empty_string();
		}

		$get_key_value = XLWCTY_Compatibility::get_order_data( $order, $atts['key'] );

		if ( $get_key_value == '' || $get_key_value == false || $get_key_value == null ) {
			return __return_empty_string();
		}

		return sprintf( '%s%s', '<span class="xlwcty_order_meta_label">' . $atts['label'] . '</span>', $get_key_value );
	}


}

XLWCTY_ShortCode_Merge_Tags::init();
