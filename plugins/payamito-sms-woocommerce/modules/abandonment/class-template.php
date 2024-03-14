<?php

namespace Payamito\Woocommerce\Modules\Abandoned;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( "Template" ) ) {
	class Template
	{
		public $tepmlates = [];

		public $obj_tepmlates;
		/**
		 * Member Variable
		 *
		 * @var object instance
		 */
		private static $instance;

		/**
		 *  Initiator
		 */
		public static function get_instance()
		{
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function get_templates()
		{
			$options = get_option( "payamito_wc_abandonment" );

			if ( $options === false || ! is_array( $options ) || count( $options ) == 0 ) {
				return [];
			}
			$this->tepmlates = $options;

			return $this->tepmlates;
		}

		public function prepare( array $tepmlates )
		{
			if ( count( $tepmlates ) != 0 ) {
				if ( isset( $tepmlates['active'] ) ) {
					unset( $tepmlates['active'] );
				}
				$tepmlate_prepared = [];

				foreach ( $tepmlates as $key => $tepmlate ) {
					if ( count( $tepmlate['patterns'] ) == 0 && count( $tepmlate['messeges'] ) == 0 ) {
						continue;
					}
					$method                                   = "";
					$tepmlate_prepared[ $key ]['id']          = $tepmlate['template_id'];
					$tepmlate_prepared[ $key ]['guest_user']  = $tepmlate['guest_user'];
					$tepmlate_prepared[ $key ]['meta_key']    = $tepmlate['meta_key'];
					$tepmlate_prepared[ $key ]['field_id']    = $tepmlate['field_id'];
					$tepmlate_prepared[ $key ]['coupon_data'] = $tepmlate['coupon_data'];

					if ( $tepmlate['pattern_active'] == '1' ) {
						$method = 'pattern';
					} else {
						$method = 'messege';
					}

					$tepmlate_prepared[ $key ]['method'] = $method;
					$units                               = Helper::units( true );

					if ( $method == 'pattern' ) {
						$prepared_pattern = [];

						foreach ( $tepmlate['patterns'] as $index => $pattern ) {
							if ( empty( $pattern['pattern_id'] ) ) {
								continue;
							}
							if ( ! isset( $pattern['abandonment_pattern_variable'] ) || ! is_array( $pattern['abandonment_pattern_variable'] ) ) {
								continue;
							}
							#frequency
							$frequency = $pattern['frequency'];
							if ( empty( $frequency['width'] ) ) {
								continue;
							}

							$seconds              = $units[ strtoupper( $frequency['unit'] ) ];
							$frequency            = ( $frequency['width'] * $seconds );
							$pattern['frequency'] = $frequency;

							array_push( $prepared_pattern, $pattern );
						}

						$frequency = array_column( $prepared_pattern, 'frequency' );
						sort( $frequency, SORT_NUMERIC );
						array_multisort( $frequency, $prepared_pattern );
						$tepmlate_prepared[ $key ]['patterns'] = $prepared_pattern;
					} elseif ( $method == 'messege' ) {
						$prepared_messege = [];
						foreach ( $tepmlate['messeges'] as $messege ) {
							if ( trim( empty( $messege['text'] ) ) ) {
								continue;
							}
							#frequency
							$frequency = $messege['frequency'];
							if ( empty( $frequency['width'] ) ) {
								continue;
							}
							$seconds              = $units[ strtoupper( $frequency['unit'] ) ];
							$frequency            = ( $frequency['width'] * $seconds );
							$messege['frequency'] = $frequency;

							array_push( $prepared_messege, $messege );
						}

						$frequency = array_column( $prepared_messege, 'frequency' );
						sort( $frequency, SORT_NUMERIC );
						array_multisort( $frequency, SORT_DESC, $prepared_messege );

						$tepmlate_prepared[ $key ]['messeges'] = $prepared_messege;
					}
				}

				return $tepmlate_prepared;
			}

			return [];
		}
	}
}
