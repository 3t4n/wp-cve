<?php
if(!class_exists('MPHB_Divi_Booking_Confirmation_Module') && class_exists('ET_Builder_Module')):

	class MPHB_Divi_Booking_Confirmation_Module extends ET_Builder_Module{

		public $slug       = 'mphb-divi-booking-confirmation';
		public $vb_support = 'on';

		function init(){

			$this->name = esc_html__( 'HB Booking Confirmation', 'mphb-divi' );

		}

		function get_fields(){

			return array(
				'class' => array(
					'label'            => esc_html__( 'Class', 'mphb-divi' ),
					'description'      => esc_html__( 'Custom CSS class for shortcode wrapper.', 'mphb-divi' ),
					'type'             => 'text',
					'default'          => '',
				),
				'name' => array(
					'type'      => 'hidden',
					'default'   => $this->name,
				)
			);

		}

		function render($attrs, $content, $render_slug){

			return self::get_booking_confirmation($this->props);

		}


		static function get_booking_confirmation($args = array()){

			$defaults = array(
				'class' => ''
			);

			$args = wp_parse_args($args, $defaults);

			return do_shortcode('[mphb_booking_confirmation class="'.$args['class'].'"]');

		}

	}

	new MPHB_Divi_Booking_Confirmation_Module;

endif;
