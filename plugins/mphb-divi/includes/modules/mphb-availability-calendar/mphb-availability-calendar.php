<?php
if ( ! class_exists( 'MPHB_Divi_Availability_Calendar_Module' ) && class_exists( 'ET_Builder_Module' ) ) :

	class MPHB_Divi_Availability_Calendar_Module extends ET_Builder_Module {

		public $slug       = 'mphb-divi-availability-calendar';
		public $vb_support = 'on';

		function init() {

			$this->name = esc_html__( 'HB Availability Calendar', 'mphb-divi' );

		}

		function get_fields() {

			return array(
				'id'               => array(
					'label'            => esc_html__( 'ID', 'mphb-divi' ),
					'description'      => esc_html__( 'ID of accommodation type.', 'mphb-divi' ),
					'type'             => 'text',
					'default'          => '',
					'computed_affects' => array(
						'__calendar',
					),
				),
				'monthstoshow'     => array(
					'label'             => esc_html__( 'Months to Show', 'mphb-divi' ),
					'description'       => esc_html__( 'How many months to show.', 'mphb-divi' ),
					'type'              => 'text',
					'default'           => '2,3',
					'value_type'        => 'float',
					'number_validation' => true,
					'value_min'         => 0,
					'value_type'        => 100,
					'computed_affects'  => array(
						'__calendar',
					),
				),
				'display_price'    => array(
					'label'            => esc_html__( 'Display prices', 'mphb-divi' ),
					'description'      => esc_html__( 'Display per-night prices in the availability calendar.', 'mphb-divi' ),
					'type'             => 'yes_no_button',
					'options'          => array(
						'on'  => esc_html__( 'Yes', 'mphb-divi' ),
						'off' => esc_html__( 'No', 'mphb-divi' ),
					),
					'default_on_front' => 'off',
					'computed_affects' => array(
						'__calendar',
					),
				),
				'truncate_price'   => array(
					'label'            => esc_html__( 'Truncate prices', 'mphb-divi' ),
					'description'      => esc_html__( 'Truncate per-night prices in the availability calendar.', 'mphb-divi' ),
					'type'             => 'yes_no_button',
					'options'          => array(
						'on'  => esc_html__( 'Yes', 'mphb-divi' ),
						'off' => esc_html__( 'No', 'mphb-divi' ),
					),
					'default_on_front' => 'on',
					'computed_affects' => array(
						'__calendar',
					),
				),
				'display_currency' => array(
					'label'            => esc_html__( 'Display currency', 'mphb-divi' ),
					'description'      => esc_html__( 'Display the currency sign in the availability calendar.', 'mphb-divi' ),
					'type'             => 'yes_no_button',
					'options'          => array(
						'on'  => esc_html__( 'Yes', 'mphb-divi' ),
						'off' => esc_html__( 'No', 'mphb-divi' ),
					),
					'default_on_front' => 'off',
					'computed_affects' => array(
						'__calendar',
					),
				),
				'class'            => array(
					'label'            => esc_html__( 'Class', 'mphb-divi' ),
					'description'      => esc_html__( 'Custom CSS class for shortcode wrapper.', 'mphb-divi' ),
					'type'             => 'text',
					'default'          => '',
					'computed_affects' => array(
						'__calendar',
					),
				),
				'__calendar'       => array(
					'type'                => 'computed',
					'computed_callback'   => array( 'MPHB_Divi_Availability_Calendar_Module', 'get_availability_calendar' ),
					'computed_depends_on' => array(
						'class',
						'monthstoshow',
						'display_price',
						'truncate_price',
						'display_currency',
						'id',
					),
				),

			);

		}

		function render( $attrs, $content, $render_slug ) {
			return self::get_availability_calendar( $this->props );
		}


		static function get_availability_calendar( $args = array() ) {

			$defaults = array(
				'id'               => '',
				'monthstoshow'     => '2,3',
				'display_price'    => false,
				'truncate_price'   => true,
				'display_currency' => false,
				'class'            => '',
			);

			$args = wp_parse_args( $args, $defaults );

			if ( $args['id'] !== '' ) {

				return do_shortcode(
					'[mphb_availability_calendar class="' . $args['class'] .
					'" id="' . $args['id'] .
					'" monthstoshow="' . $args['monthstoshow'] .
					'" display_price="' . ( 'on' == $args['display_price'] ? 1 : 0 ) .
					'" truncate_price="' . ( 'on' == $args['truncate_price'] ? 1 : 0 ) .
					'" display_currency="' . ( 'on' == $args['display_currency'] ? 1 : 0 ) . '"]'
				);
			}

			return esc_html__( 'Please insert accommodation id.' );
		}
	}

	new MPHB_Divi_Availability_Calendar_Module();

endif;
