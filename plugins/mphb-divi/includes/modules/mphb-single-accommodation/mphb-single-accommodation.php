<?php
if(!class_exists('MPHB_Divi_Single_Accommodation_Module') && class_exists('ET_Builder_Module')):

	class MPHB_Divi_Single_Accommodation_Module extends ET_Builder_Module{

		public $slug = 'mphb-divi-single-accommodation';
		public $vb_support = 'on';

		function init(){

			$this->name = esc_html__( 'HB Single Accom.', 'mphb-divi' );

		}

		function get_fields(){

			return array(
				'id' => array(
					'label'           => esc_html__( 'ID', 'mphb-divi' ),
					'description'     => esc_html__( 'ID of accommodation type to display.', 'mphb-divi' ),
					'type'              => 'text',
					'default'   => '',
					'computed_affects'   => array(
						'__room',
					),
				),
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
						'__room',
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
						'__room',
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
						'__room',
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
						'__room',
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
						'__room',
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
						'__room',
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
						'__room',
					),
				),
				'book_button' => array(
					'label'           => esc_html__( 'Book Button', 'mphb-divi' ),
					'description'     => esc_html__( 'Whether to display "Book" button.', 'mphb-divi' ),
					'type'              => 'yes_no_button',
					'options'           => array(
						'on'  => esc_html__( 'Yes', 'mphb-divi' ),
						'off' => esc_html__( 'No', 'mphb-divi' ),
					),
					'default_on_front'  => 'on',
					'computed_affects'   => array(
						'__room',
					),
				),
				'class' => array(
					'label'           => esc_html__( 'Class', 'mphb-divi' ),
					'description'     => esc_html__( 'Custom CSS class for shortcode wrapper.', 'mphb-divi' ),
					'type'              => 'text',
					'default'   => '',
					'computed_affects'   => array(
						'__room',
					),
				),
				'__room' => array(
					'type' => 'computed',
					'computed_callback' => array( 'MPHB_Divi_Single_Accommodation_Module', 'get_room' ),
					'computed_depends_on' => array(
						'title',
						'featured_image',
						'gallery',
						'excerpt',
						'details',
						'price',
						'view_button',
						'book_button',
						'id',
						'class'
					),
				)

			);

		}

		function render($attrs, $content, $render_slug) {

			return self::get_room($this->props);

		}


		static function get_room($args = array()){

			$defaults = array(
				'title' => '',
				'featured_image' => '',
				'gallery' => '',
				'excerpt' => '',
				'details' => '',
				'price' => '',
				'view_button' => '',
				'book_button' => '',
				'id' => '',
				'class' => ''
			);

			$args = wp_parse_args($args, $defaults);

			if($args['id'] !== ''){
				return do_shortcode('[mphb_room title="'.$args['title'].'"
													featured_image="'.$args['featured_image'].'"
													gallery="'.$args['gallery'].'"
													excerpt="'.$args['excerpt'].'"
													details="'.$args['details'].'"
													price="'.$args['price'].'"
													view_button="'.$args['view_button'].'"
													book_button="'.$args['book_button'].'"
													id="'.$args['id'].'"
													class="'.$args['class'].'"]');
			}

			return esc_html__('Please insert accommodation id.');

		}


	}

	new MPHB_Divi_Single_Accommodation_Module;

endif;
