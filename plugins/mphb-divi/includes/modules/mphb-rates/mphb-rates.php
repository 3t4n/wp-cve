<?php
if(!class_exists('MPHB_Divi_Rates_Module') && class_exists('ET_Builder_Module')):

	class MPHB_Divi_Rates_Module extends ET_Builder_Module{

		public $slug = 'mphb-divi-rates';
		public $vb_support = 'on';

		function init(){

			$this->name = esc_html__( 'HB Accom. Rates', 'mphb-divi' );

		}

		function get_fields(){

			return array(
				'id' => array(
					'label'           => esc_html__( 'ID', 'mphb-divi' ),
					'description'     => esc_html__( 'ID of accommodation type.', 'mphb-divi' ),
					'type'              => 'text',
					'default'   => '',
					'computed_affects'   => array(
						'__rates',
					),
				),
				'class' => array(
					'label'           => esc_html__( 'Class', 'mphb-divi' ),
					'description'     => esc_html__( 'Custom CSS class for shortcode wrapper.', 'mphb-divi' ),
					'type'              => 'text',
					'default'   => '',
					'computed_affects'   => array(
						'__rates',
					),
				),
				'__rates' => array(
					'type' => 'computed',
					'computed_callback' => array( 'MPHB_Divi_Rates_Module', 'get_rates' ),
					'computed_depends_on' => array(
						'class',
						'id'
					),
				)

			);

		}

		function render($attrs, $content, $render_slug){

			return self::get_rates($this->props);

		}


		static function get_rates($args = array()){

			$defaults = array(
				'id' => '',
				'class' => ''
			);

			$args = wp_parse_args($args, $defaults);

			if($args['id'] !== ''){
				return do_shortcode('[mphb_rates class="'.$args['class'].'" id="'.$args['id'].'"]');
			}

			return esc_html__('Please insert accommodation id.');

		}

	}

	new MPHB_Divi_Rates_Module;

endif;
