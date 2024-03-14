<?php
if(!class_exists('MPHB_Divi_Search_Availability_Module') && class_exists('ET_Builder_Module')):

	class MPHB_Divi_Search_Availability_Module extends ET_Builder_Module {

		public $slug = 'mphb-divi-availability-search';
		public $vb_support = 'on';

		function init(){

			$this->name = esc_html__( 'HB Search Availability', 'mphb-divi' );
		}

		function get_fields(){

			return array(
				'check_in_date' => array(
					'label'           => esc_html__( 'Check-in date', 'mphb-divi' ),
					'type'            => 'text',
					'default'           => '',
					'description'     => esc_html__( 'Check-in date presetted in the search form. Format: d/m/Y', 'mphb-divi' ),
					'computed_affects'   => array(
						'__form',
					),
				),
				'check_out_date' => array(
					'label'           => esc_html__( 'Check-out date', 'mphb-divi' ),
					'type'            => 'text',
					'default'           => '',
					'description'     => esc_html__( 'Check-out date presetted in the search form. Format: d/m/Y', 'mphb-divi' ),
					'computed_affects'   => array(
						'__form',
					),
				),
				'adults' => array(
					'label'           => esc_html__( 'Adults', 'mphb-divi' ),
					'type'            => 'text',
					'default'           => '',
					'description'     => esc_html__( 'The number of adults presetted in the search form.', 'mphb-divi' ),
					'computed_affects'   => array(
						'__form',
					),
				),
				'children' => array(
					'label'           => esc_html__( 'Children', 'mphb-divi' ),
					'type'            => 'text',
					'default'           => '',
					'description'     => esc_html__( 'The number of children presetted in the search form.', 'mphb-divi' ),
					'computed_affects'   => array(
						'__form',
					),
				),
				'attributes' => array(
					'label'           => esc_html__( 'Attributes', 'mphb-divi' ),
					'type'            => 'textarea',
					'description'     => esc_html__( 'Comma separated list of attributes.', 'mphb-divi' ),
					'computed_affects'   => array(
						'__form',
					),
				),
				'form_style' => array(
					'label'           => esc_html__( 'Style', 'mphb-divi' ),
					'type'            => 'select',
					'options'         => array(
						'default' => esc_html__( 'Default', 'mphb-divi' ),
						'horizontal-center'  => esc_html__( 'Horizontal Center', 'mphb-divi' ),
						'horizontal-left'  => esc_html__( 'Horizontal Left', 'mphb-divi' ),
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
					'computed_callback' => array( 'MPHB_Divi_Search_Availability_Module', 'get_form' ),
					'computed_depends_on' => array(
						'check_in_date',
						'check_out_date',
						'adults',
						'children',
						'attributes',
						'form_style',
						'class'
					),
				)
			);

		}

		function render($attrs, $content, $render_slug){

		   return self::get_form($this->props);

		}

		static function get_form($args = array()){

			$defaults = array(
				'check_in_date' => '',
				'check_out_date' => '',
				'adults' => '',
				'children' => '',
				'attributes' => '',
				'form_style' => 'default',
				'class' => ''
			);

			$args = wp_parse_args($args, $defaults);

			if($args['form_style'] !== 'default'){
				$args['class'] .= ' '.$args['form_style'];
			}

			return do_shortcode('[mphb_availability_search attributes="'.$args['attributes'].'"
																	check_in_date="'.$args['check_in_date'].'"
																	check_out_date="'.$args['check_out_date'].'"
																	adults="'.$args['adults'].'"
																	children="'.$args['children'].'"
																	class="'.$args['class'].'"
																	]');

		}

	}

	new MPHB_Divi_Search_Availability_Module;

endif;
