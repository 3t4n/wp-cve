<?php

/**
 * WPPFM Product Feed Form Control Class.
 *
 * @package WP Product Feed Manager/User Interface/Classes
 * @version 1.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPPFM_Feed_Form_Control' ) ) :

	/**
	 * WPPFM Feed Form Control Class
	 */
	class WPPFM_Feed_Form_Control {

		public static function source_selector() {
			$data_class = new WPPFM_Data();
			$sources    = $data_class->get_sources();
			$html_code  = '';

			if ( ! empty( $sources ) ) {
				$html_code = '<select id="sources">';

				$html_code .= '<option value="0">' . esc_html__( 'Select your product source', 'wp-product-feed-manager' ) . '</option>';

				if ( count( $sources ) > 1 ) {
					foreach ( $sources as $source ) {
						$html_code .= '<option value="' . $source['source_id'] . '">' . $source['name'] . '</option>';
					}
				} else {
					$html_code .= '<option value="' . $sources[0]['source_id'] . '" selected>' . $sources[0]['name'] . '</option>';
				}

				$html_code .= '</select>';
			}

			return $html_code;
		}

		public static function channel_selector() {
			$data_class = new WPPFM_Data();
			$channels   = $data_class->get_channels();

			if ( ! empty( $channels ) ) {
				$html_code  = '<div id="selected-merchant"></div>';
				$html_code .= '<select class="wppfm-main-input-selector" id="wppfm-merchants-selector" style="display:initial;">';

				$html_code .= '<option value="0">' . esc_html__( '-- Select your merchant --', 'wp-product-feed-manager' ) . '</option>';

				foreach ( $channels as $channel ) {
					$html_code .= '<option value="' . $channel['channel_id'] . '">' . $channel['name'] . '</option>';
				}

				$html_code .= '</select>';
			} else {
				$html_code = esc_html__( 'You first need to install a channel before you can add a feed. Open the Manage Channels page and install at least one channel.', 'wp-product-feed-manager' );
			}

			return $html_code;
		}

		/**
		 * Returns the html code that makes up the feed type selector.
		 *
		 * @since 2.38.0.
		 * @return string
		 */
		public static function feed_type_selector( $preselected ) {
			$data_class = new WPPFM_Data();
			$feed_types = $data_class->get_google_support_feed_types();
			$html_code  = '';

			if ( ! empty( $feed_types ) ) {
				$html_code  = '<div id="wppfm-selected-google-feed-type"></div>';
				$html_code .= '<select class="wppfm-main-input-selector wppfm-feed-types-selector" id="wppfm-feed-types-selector">';

				$html_code .= '<option value="' . $feed_types[0]['feed_type_id'] . '">' . $feed_types[0]['name'] . '</option>';
				$html_code .= '<optgroup label="Supplemental Feeds">';

				foreach ( $feed_types as $feed_type ) {
					$disabled = $feed_type['disabled'] ? ' disabled="disabled"' : '';
					if ( 'supplemental' === $feed_type['group'] ) {
						if ( $feed_type['feed_type_id'] === $preselected ) {
							$html_code .= '<option value="' . $feed_type['feed_type_id'] . '" selected>' . $feed_type['name'] . '</option>';
						} else {
							$html_code .= '<option value="' . $feed_type['feed_type_id'] . '"' . $disabled . '>' . $feed_type['name'] . '</option>';
						}
					}
				}

				$html_code .= '</optgroup>';

				$html_code .= '</select>';
			}

			return $html_code;
		}

		public static function feed_business_type_selector() {
			$business_types = array( 'Education', 'Flights', 'Hotels and rentals', 'Jobs', 'Local deals', 'Real estate', 'Travel', 'Custom' );

			$html_code  = '<select class="wppfm-main-input-selector wppfm-feed-business-types-selector" id="wppfm-feed-drm-types-selector">';
			$html_code .= '<option value="0">' . esc_html__( '-- Select your business type --', 'wp-product-feed-manager' ) . '</option>';

			foreach ( $business_types as $business_type ) {
				$html_code .= '<option value="' . $business_type . '">' . $business_type . '</option>';
			}

			$html_code .= '</select>';

			return $html_code;
		}

		public static function country_selector() {
			$data_class = new WPPFM_Data();
			$countries  = $data_class->get_countries();
			$html_code  = '';

			if ( ! empty( $countries ) ) {
				$html_code  = '<select class="wppfm-main-input-selector wppfm-countries-selector" id="wppfm-countries-selector" disabled>';
				$html_code .= '<option value="0">' . esc_html__( '-- Select your target country --', 'wp-product-feed-manager' ) . '</option>';

				foreach ( $countries as $country ) {
					$html_code .= '<option value="' . $country['name_short'] . '">' . $country['name'] . '</option>';
				}

				$html_code .= '</select>';
			}

			return $html_code;
		}

		public static function schedule_selector() {
			$html_code  = '<span id="wppfm-update-day-wrapper" style="display:initial">' . esc_html__( 'Every', 'wp-product-feed-manager' ) . ' ';
			$html_code .= '<input type="text" class="small-text" name="days-interval" id="days-interval" value="1" style="width:30px;" /> ' . esc_html__( 'day(s) at', 'wp-product-feed-manager' ) . '</span>';
			$html_code .= '<span id="wppfm-update-every-day-wrapper" style="display:none">' . esc_html__( 'Every day at', 'wp-product-feed-manager' ) . '</span>';
			$html_code .= ' <select id="update-schedule-hours" style="width:52px;height:35px;">' . self::hour_list() . '</select>';
			$html_code .= '<select id="update-schedule-minutes" style="width:52px;height:35px;">' . self::minutes_list() . '</select>';
			$html_code .= '<span id="wppfm-update-frequency-wrapper" style="display:initial">';
			$html_code .= ' ' . esc_html__( 'for', 'wp-product-feed-manager' ) . ' ';
			$html_code .= '<select id="update-schedule-frequency" style="width:50px;height:35px;">' . self::frequency_list() . '</select>';
			$html_code .= ' ' . esc_html__( 'time(s) a day', 'wp-product-feed-manager' );
			$html_code .= '</span>';

			return $html_code;
		}

		public static function aggregation_selector() {
			return '<input type="checkbox" name="aggregator-selector" id="aggregator">';
		}

		public static function product_variation_selector() {
			return '<input type="checkbox" name="product-variations-selector" id="variations">';
		}

		public static function google_feed_title_selector() {
			return '<input type="text" name="google-feed-title-selector" id="google-feed-title-selector" placeholder="uses File Name if left empty..." />';
		}

		public static function google_feed_description_selector() {
			return '<input type="text" name="google-feed-description-selector" id="google-feed-description-selector" placeholder="uses a standard description if left empty..." />';
		}

		private static function hour_list() {
			$html_code = self::get_time_list();

			for ( $i = 10; $i < 24; $i ++ ) {
				$html_code .= '<option value="' . $i . '">' . $i . '</option>';
			}

			return $html_code;
		}

		private static function minutes_list() {
			$html_code = self::get_time_list();

			for ( $i = 10; $i < MINUTE_IN_SECONDS; $i ++ ) {
				$html_code .= '<option value="' . $i . '">' . $i . '</option>';
			}

			return $html_code;
		}

		private static function frequency_list() {
			$html_code  = '<option value="1">1</option>';
			$html_code .= '<option value="2">2</option>';
			$html_code .= '<option value="4">4</option>';
			$html_code .= '<option value="6">6</option>';
			$html_code .= '<option value="8">8</option>';
			$html_code .= '<option value="12">12</option>';
			$html_code .= '<option value="24">24</option>';

			return $html_code;
		}

		/**
		 * @return string
		 */
		private static function get_time_list(): string {
			$html_code  = '<option value="00">00</option>';
			$html_code .= '<option value="01">01</option>';
			$html_code .= '<option value="02">02</option>';
			$html_code .= '<option value="03">03</option>';
			$html_code .= '<option value="04">04</option>';
			$html_code .= '<option value="05">05</option>';
			$html_code .= '<option value="06">06</option>';
			$html_code .= '<option value="07">07</option>';
			$html_code .= '<option value="08">08</option>';
			$html_code .= '<option value="09">09</option>';

			return $html_code;
		}
	}


	// end of WPPFM_Feed_Form_Control class

endif;
