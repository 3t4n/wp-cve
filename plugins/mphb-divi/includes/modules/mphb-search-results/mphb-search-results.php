<?php
if(!class_exists('MPHB_Divi_Search_Results_Module') && class_exists('ET_Builder_Module')):

	class MPHB_Divi_Search_Results_Module extends ET_Builder_Module{

		public $slug       = 'mphb-divi-search-results';
		public $vb_support = 'on';

		function init() {

			$this->name = esc_html__( 'HB Search Results', 'mphb-divi' );

		}

		function get_fields(){

			return array(
				'title' => array(
					'label'           => esc_html__( 'Title', 'mphb-divi' ),
					'description'     => esc_html__( 'Whether to display title of the accommodation type.', 'mphb-divi' ),
					'type'              => 'yes_no_button',
					'options'           => array(
						'on'  => esc_html__( 'Yes', 'mphb-divi' ),
						'off' => esc_html__( 'No', 'mphb-divi' ),
					),
					'default_on_front'  => 'on',
					'computed_affects'   => array(
						'__search',
					),
				),
				'featured_image' => array(
					'label'           => esc_html__( 'Featured image', 'mphb-divi' ),
					'description'     => esc_html__( 'Whether to display featured image of the accommodation type.', 'mphb-divi' ),
					'type'              => 'yes_no_button',
					'options'           => array(
						'on'  => esc_html__( 'Yes', 'mphb-divi' ),
						'off' => esc_html__( 'No', 'mphb-divi' ),
					),
					'default_on_front'  => 'on',
					'computed_affects'   => array(
						'__search',
					),
				),
				'gallery' => array(
					'label'           => esc_html__( 'Gallery', 'mphb-divi' ),
					'description'     => esc_html__( 'Whether to display gallery of the accommodation type.', 'mphb-divi' ),
					'type'              => 'yes_no_button',
					'options'           => array(
						'on'  => esc_html__( 'Yes', 'mphb-divi' ),
						'off' => esc_html__( 'No', 'mphb-divi' ),
					),
					'default_on_front'  => 'on',
					'computed_affects'   => array(
						'__search',
					),
				),
				'excerpt' => array(
					'label'           => esc_html__( 'Excerpt', 'mphb-divi' ),
					'description'     => esc_html__( 'Whether to display excerpt (short description) of the accommodation type.', 'mphb-divi' ),
					'type'              => 'yes_no_button',
					'options'           => array(
						'on'  => esc_html__( 'Yes', 'mphb-divi' ),
						'off' => esc_html__( 'No', 'mphb-divi' ),
					),
					'default_on_front'  => 'on',
					'computed_affects'   => array(
						'__search',
					),
				),
				'details' => array(
					'label'           => esc_html__( 'Details', 'mphb-divi' ),
					'description'     => esc_html__( 'Whether to display details of the accommodation type.', 'mphb-divi' ),
					'type'              => 'yes_no_button',
					'options'           => array(
						'on'  => esc_html__( 'Yes', 'mphb-divi' ),
						'off' => esc_html__( 'No', 'mphb-divi' ),
					),
					'default_on_front'  => 'on',
					'computed_affects'   => array(
						'__search',
					),
				),
				'price' => array(
					'label'           => esc_html__( 'Price', 'mphb-divi' ),
					'description'     => esc_html__( 'Whether to display price of the accommodation type.', 'mphb-divi' ),
					'type'              => 'yes_no_button',
					'options'           => array(
						'on'  => esc_html__( 'Yes', 'mphb-divi' ),
						'off' => esc_html__( 'No', 'mphb-divi' ),
					),
					'default_on_front'  => 'on',
					'computed_affects'   => array(
						'__search',
					),
				),
				'view_button' => array(
					'label'           => esc_html__( 'View Button', 'mphb-divi' ),
					'description'     => esc_html__( 'Whether to display "View Details" button with the link to accommodation type.', 'mphb-divi' ),
					'type'              => 'yes_no_button',
					'options'           => array(
						'on'  => esc_html__( 'Yes', 'mphb-divi' ),
						'off' => esc_html__( 'No', 'mphb-divi' ),
					),
					'default_on_front'  => 'on',
					'computed_affects'   => array(
						'__search',
					),
				),
				'orderby' => array(
					'label'           => esc_html__( 'Sort by', 'mphb-divi' ),
					'description'     => esc_html__( 'ID, title, date, menu_order...', 'mphb-divi' ),
					'type'              => 'text',
					'default'   => '',
					'computed_affects'   => array(
						'__search',
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
						'__search',
					),
				),
				'meta_key' => array(
					'label'           => esc_html__( 'Custom field name', 'mphb-divi' ),
					'description'     => esc_html__( 'Required if "orderby" is one of the "meta_value", "meta_value_num" or "meta_value_*".', 'mphb-divi' ),
					'type'              => 'text',
					'default'   => '',
					'computed_affects'   => array(
						'__search',
					),
				),
				'meta_type' => array(
					'label'           => esc_html__( 'Custom field type', 'mphb-divi' ),
					'description'     => esc_html__( 'Specified type of the custom field. Can be used in conjunction with orderby="meta_value".', 'mphb-divi' ),
					'type'              => 'text',
					'default'   => '',
					'computed_affects'   => array(
						'__search',
					),
				),
				'class' => array(
					'label'           => esc_html__( 'Class', 'mphb-divi' ),
					'description'     => esc_html__( 'Custom CSS class for shortcode wrapper.', 'mphb-divi' ),
					'type'              => 'text',
					'default'   => '',
					'computed_affects'   => array(
						'__search',
					),
				),
				'__search' => array(
					'type'                => 'computed',
					'computed_callback'   => array( 'MPHB_Divi_Search_Results_Module', 'get_search_results' ),
					'computed_depends_on' => array(
						'class',
					),
				),
				'name' => array(
					'type'      => 'hidden',
					'default'   => $this->name,
				)

			);

		}

		function render($attrs, $content, $render_slug){

			return self::get_search_results($this->props);

		}


		static function get_search_results($args = array()){

			$defaults = array(
				'title' => '',
				'featured_image' => '',
				'gallery' => '',
				'excerpt' => '',
				'details' => '',
				'price' => '',
				'view_button' => '',
				'book_button' => '',
				'orderby' => '',
				'order'    => '',
				'meta_key' => '',
				'meta_type' => '',
				'default_sorting' => '',
				'class' => ''
			);

			$args = wp_parse_args($args, $defaults);

			return do_shortcode('[mphb_search_results title="'.$args['title'].'"
				featured_image="'.$args['featured_image'].'"
				gallery="'.$args['gallery'].'"
				excerpt="'.$args['excerpt'].'"
				details="'.$args['details'].'"
				price="'.$args['price'].'"
				view_button="'.$args['view_button'].'"
				book_button="'.$args['book_button'].'"
				orderby="'.$args['orderby'].'"
				order="'.$args['order'].'"
				meta_key="'.$args['meta_key'].'"
				meta_type="'.$args['meta_type'].'"
				default_sorting="'.$args['default_sorting'].'"
				class="'.$args['class'].'"]');

		}

	}

	new MPHB_Divi_Search_Results_Module;

endif;
