<?php
if(!class_exists('MPHB_Divi_Booking_Form_Module') && class_exists('ET_Builder_Module')):

	class MPHB_Divi_Booking_Form_Module extends ET_Builder_Module{

		public $slug = 'mphb-divi-booking-form';
		public $vb_support = 'on';

		function init(){

			$this->name       = esc_html__( 'HB Booking Form', 'mphb-divi' );

		}

		function get_fields(){

			return array(
				'id' => array(
					'label'           => esc_html__( 'ID', 'mphb-divi' ),
					'description'     => esc_html__( 'ID of accommodation type.', 'mphb-divi' ),
					'type'              => 'text',
					'default'   => '',
					'computed_affects'   => array(
						'__form',
					),
				),
				'form_style' => array(
					'label'           => esc_html__( 'Style', 'mphb-divi' ),
					'type'            => 'select',
					'options'         => array(
						'default' => esc_html__( 'Default', 'mphb-divi' ),
						'horizontal'  => esc_html__( 'Horizontal', 'mphb-divi' ),
					),
					'default'         => 'default',
					'computed_affects'   => array(
						'__form',
					),
				),
				'class' => array(
					'label'           => esc_html__( 'Class', 'mphb-divi' ),
					'description'     => esc_html__( 'Custom CSS class for shortcode wrapper.', 'mphb-divi' ),
					'type'              => 'text',
					'default'   => '',
					'computed_affects'   => array(
						'__form',
					),
				),
				'__form' => array(
					'type' => 'computed',
					'computed_callback' => array( 'MPHB_Divi_Booking_Form_Module', 'get_booking_form' ),
					'computed_depends_on' => array(
						'class',
						'form_style',
						'id'
					),
				)

			);

		}

		function render($attrs, $content, $render_slug){

			return self::get_booking_form($this->props);

		}


		static function get_booking_form($args = array()){

			$defaults = array(
				'id' => '',
				'class' => '',
				'form_style' => 'default'
			);

			$args = wp_parse_args($args, $defaults);

			if($args['form_style'] !== 'default'){
				$args['class'] .= ' '.$args['form_style'];
			}

			if($args['id'] !== ''){
				return do_shortcode('[mphb_availability class="'.$args['class'].'" id="'.$args['id'].'"]');
			}

			return esc_html__('Please insert accommodation id.');

		}


	}

	new MPHB_Divi_Booking_Form_Module;

endif;
