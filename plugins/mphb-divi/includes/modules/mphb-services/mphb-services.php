<?php
if(!class_exists('MPHB_Divi_Services_Module') && class_exists('ET_Builder_Module')):

	class MPHB_Divi_Services_Module extends ET_Builder_Module{

		public $slug = 'mphb-divi-services';
		public $vb_support = 'on';

		function init(){

			$this->name = esc_html__( 'HB Accom. Services', 'mphb-divi' );

		}

		function get_fields(){

			return array(
				'ids' => array(
					'label'           => esc_html__( 'IDs', 'mphb-divi' ),
					'description'     => esc_html__( 'Comma-separated IDs of services that will be shown. All services by default.', 'mphb-divi' ),
					'type'              => 'text',
					'default'   => '',
					'computed_affects'   => array(
						'__services',
					),
				),
				'posts_per_page' => array(
					'label'           => esc_html__( 'Count per page', 'mphb-divi' ),
					'description'     => esc_html__( 'Integer, -1 to display all, default: "Blog pages show at most"', 'mphb-divi' ),
					'type'              => 'text',
					'default'   => '',
					'computed_affects'   => array(
						'__services',
					),
				),
				'orderby' => array(
					'label'           => esc_html__( 'Sort by', 'mphb-divi' ),
					'description'     => esc_html__( 'ID, title, date, menu_order...', 'mphb-divi' ),
					'type'              => 'text',
					'default'   => '',
					'computed_affects'   => array(
						'__services',
					),
				),
				'order' => array(
					'label'           => esc_html__( 'Order', 'mphb-divi' ),
					'description'     => esc_html__( 'Designates the ascending or descending order of sorting. ASC - from lowest to highest values (1, 2, 3). DESC - from highest to lowest values (3, 2, 1).', 'mphb-divi' ),
					'type'            => 'select',
					'options'         => array(
						'ASC' => esc_html__( 'ASC', 'mphb-divi' ),
						'DESC'  => esc_html__( 'DESC', 'mphb-divi' ),
					),
					'default' => 'DESC',
					'computed_affects'   => array(
						'__services',
					),
				),
				'meta_key' => array(
					'label'           => esc_html__( 'Custom field name', 'mphb-divi' ),
					'description'     => esc_html__( 'Required if "orderby" is one of the "meta_value", "meta_value_num" or "meta_value_*".', 'mphb-divi' ),
					'type'              => 'text',
					'default'   => '',
					'computed_affects'   => array(
						'__services',
					),
				),
				'meta_type' => array(
					'label'           => esc_html__( 'Custom field type', 'mphb-divi' ),
					'description'     => esc_html__( 'Specified type of the custom field. Can be used in conjunction with orderby="meta_value".', 'mphb-divi' ),
					'type'              => 'text',
					'default'   => '',
					'computed_affects'   => array(
						'__services',
					),
				),
				'class' => array(
					'label'           => esc_html__( 'Class', 'mphb-divi' ),
					'description'     => esc_html__( 'Custom CSS class for shortcode wrapper.', 'mphb-divi' ),
					'type'              => 'text',
					'default'   => '',
					'computed_affects'   => array(
						'__services',
					),
				),
				'__services' => array(
					'type' => 'computed',
					'computed_callback' => array( 'MPHB_Divi_Services_Module', 'get_services' ),
					'computed_depends_on' => array(
						'ids',
						'posts_per_page',
						'orderby',
						'order',
						'meta_key',
						'meta_type',
						'class'
					),
				)

			);

		}

		function render($attrs, $content, $render_slug){

			return self::get_services($this->props);

		}


		static function get_services($args = array()){

			$defaults = array(
				'ids' => '',
				'class' => '',
				'posts_per_page' => '',
				'orderby' => '',
				'order' => '',
				'meta_key' => '',
				'meta_type' => ''
			);

			$args = wp_parse_args($args, $defaults);

			return do_shortcode('[mphb_services class="'.$args['class'].'" ids="'.$args['ids'].'"
														posts_per_page="'.$args['posts_per_page'].'"
														orderby="'.$args['orderby'].'"
														order="'.$args['order'].'"
														meta_key="'.$args['meta_key'].'"
														meta_type="'.$args['meta_type'].'"
														]');

		}

	}

	new MPHB_Divi_Services_Module();

endif;
