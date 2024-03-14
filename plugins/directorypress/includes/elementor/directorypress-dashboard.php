<?php
/**
 * Elementor test Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
use Elementor\Plugin;
class DirectoryPress_Elementor_Dashboard_Widget extends \Elementor\Widget_Base {

	/* public function __construct($data = [], $args = null) {
		 parent::__construct($data, $args);

		//wp_register_script('slick-carousel-triger-js2', DIRECTORYPRESS_RESOURCES_URL . 'lib/slick-carousel/js/slick-triger.min.js', array('jquery'), false, true);
	}
	  public function get_script_depends() {
		 // wp_enqueue_script('slick-carousel-triger-js');
		// return [ 'slick-carousel-triger-js2' ];
	  } */
	/**
	 * Get widget name.
	 *
	 * Retrieve oEmbed widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'directorypress-dashboard';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve oEmbed widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'User Dashboard', 'DIRECTORYPRESS' );
	}


	/**
	 * Get widget icon.
	 *
	 * Retrieve oEmbed widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'fas fa-tachometer-alt';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the oEmbed widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'directorypress' ];
	}

	/**
	 * Register oEmbed widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		//$ordering = directorypress_sorting_options();
		$directories = directorypress_directorytypes_array_options();
		$categories = directorypress_categories_array_options();
		//$locations = directorypress_locations_array_options();
		$packages = directorypress_packages_array_options();
		
		// Setting Section
		$this->start_controls_section(
			'setting_section',
			[
				'label' => __( 'Setting', 'DIRECTORYPRESS' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'cat_style',
			[
				'label' => __('category styles', 'DIRECTORYPRESS'),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
				'1' => __('Style 1 ( Elca and Max )', 'DIRECTORYPRESS'),
				'2' => __('Style 2 Echo', 'DIRECTORYPRESS'),
				'3' => __('Style 3 Zee', 'DIRECTORYPRESS'),
				'4' => __('Style 4 Wox', 'DIRECTORYPRESS'),
				'5' => __('Style 5 Ultra', 'DIRECTORYPRESS'),
				'6' => __('Style 6 Mintox', 'DIRECTORYPRESS'),
				'7' => __('Style 7 Zoco', 'DIRECTORYPRESS'),
				'8' => __('Style 8 Fantro (List)', 'DIRECTORYPRESS'),
				'9' => __('Style 9 ', 'DIRECTORYPRESS'),
				'10' => __('Style 10 ', 'DIRECTORYPRESS'),
				'11' => __('Style 11 ', 'DIRECTORYPRESS'),
				],
				'default' => '4',
			]
		);
		$this->add_control(
			'parent',
			[
				'label' => __('Parent category', 'DIRECTORYPRESS'),
				'description' => __('ID of parent category (default 0 – this will build whole categories tree starting from the root).', 'DIRECTORYPRESS'),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'Inset Id', 'DIRECTORYPRESS' ),
			]
		);
		$this->add_control(
			'depth',
			[
				'label' => __('Categories sub level', 'DIRECTORYPRESS'), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'description' => __('The max depth of categories tree. When set to 1 – only root categories will be listed.', 'DIRECTORYPRESS'),
				'multiple' => false,
				'options' => [
					'1' => __( 'Level 1', 'DIRECTORYPRESS' ),
					'2' => __( 'Level 2', 'DIRECTORYPRESS' ),
				],
				'condition' => [
					'cat_style' => [ '3', '6', '7', '10' ],
				],
				'default' => 1,
			]
		);
		$this->add_control(
			'sublocations',
			[
				'label' => __('Show sublocations items number', 'DIRECTORYPRESS'),
				'description' => __('This is the number of sublocations those will be displayed in the table, when Location item includes more than this number "View all" link appears at the bottom.', 'DIRECTORYPRESS'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'condition' => [
					'depth' => [ '2' ],
				],
				'min' => 1,
				'max' => 100,
				'step' => 1,
				'default' => 5,
			]
		);
		
		$this->add_control(
			'columns_set1',
			[
				'label' =>__('Categories columns number', 'DIRECTORYPRESS'),
				'description' => __('Categories list is divided by columns.', 'DIRECTORYPRESS'),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'1' => __( '1 Column', 'DIRECTORYPRESS' ),
					'2' => __( '2 Column', 'DIRECTORYPRESS' ),
					'3' => __( '3 Column', 'DIRECTORYPRESS' ),
					'4' => __( '4 Column', 'DIRECTORYPRESS' ),
					//'5' => __( '5 Column', 'DIRECTORYPRESS' ),
					//'6' => __( '6 Column', 'DIRECTORYPRESS' ),
					//'inline' => __( 'Inline', 'DIRECTORYPRESS' ),
				],
				'default' => 4,
				'condition' => [
					'cat_style' => [ '3', '6', '7', '10' ],
				],
			]
		);
		$this->add_control(
			'columns_set2',
			[
				'label' =>__('Categories columns number', 'DIRECTORYPRESS'),
				'description' => __('Categories list is divided by columns.', 'DIRECTORYPRESS'),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'1' => __( '1 Column', 'DIRECTORYPRESS' ),
					'2' => __( '2 Column', 'DIRECTORYPRESS' ),
					'3' => __( '3 Column', 'DIRECTORYPRESS' ),
					'4' => __( '4 Column', 'DIRECTORYPRESS' ),
					'5' => __( '5 Column', 'DIRECTORYPRESS' ),
					'6' => __( '6 Column', 'DIRECTORYPRESS' ),
					'inline' => __( 'Inline', 'DIRECTORYPRESS' ),
				],
				'default' => 4,
				'condition' => [
					'cat_style' => [ '1', '2', '5', '8', '9', '11' ],
				],
			]
		);
		$this->add_control(
			'cat_icon_type',
			[
				'label' => __('Select Categories icon type', 'DIRECTORYPRESS'),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'1' => __( 'Font Icons', 'DIRECTORYPRESS' ),
					'2' => __( 'Image Icons', 'DIRECTORYPRESS' ),
				],
				'default' => 1,
			]
		);
		$this->add_control(
			'count',
			[
				'label' =>  __('Show category listings count?', 'DIRECTORYPRESS'),
				'description' => __('Whether to show number of listings assigned with current category in brackets.', 'DIRECTORYPRESS'), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'0' => __( 'No', 'DIRECTORYPRESS' ),
					'1' => __( 'Yes', 'DIRECTORYPRESS' ),
				],
				'default' => 1,
			]
		);
		$this->add_control(
			'hide_empty',
			[
				'label' =>  __('Hide Empty Ctegories?', 'DIRECTORYPRESS'), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'0' => __( 'No', 'DIRECTORYPRESS' ),
					'1' => __( 'Yes', 'DIRECTORYPRESS' ),
				],
				'default' => 0,
			]
		);
		
		$this->end_controls_section(); 
		
		// content section
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'DIRECTORYPRESS' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'directorytype',
			[
				'label' => __( 'Select Directory', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => $directories,
				'default' => 0,
			]
		);
		$this->add_control(
			'uid',
			[
				'label' => __( 'Unique ID', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'description' => __( 'Insert unique id if you like to connect this module to a specific module like map or search(optional)', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
			]
		);
		$this->add_control(
			'categories',
			[
				'label' => __( 'Select Specific Categories', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $categories,
				'default' => 0,
			]
		);
		$this->add_control(
			'packages',
			[
				'label' => __( 'Select Packages', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $packages,
				'default' => 0,
			]
		);

		$this->end_controls_section();
		
		// Slider
		$this->start_controls_section(
			'slider_section',
			[
				'label' => __( 'Slider', 'DIRECTORYPRESS' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'scroll',
			[
				'label' => __( 'Turn On Slider', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'0' => __( 'No', 'DIRECTORYPRESS' ),
					'1' => __( 'Yes', 'DIRECTORYPRESS' ),
				],
				'default' => 0,
				'condition' => [
					'cat_style' => [ '1', '2', '4', '5', '8', '9', '11' ],
				],
			]
		);
		$this->add_control(
			'desktop_items',
			[
				'label' => __( 'Items Per Slide', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				//'label_block' => true,
				'min' => 1,
				'max' => 10,
				'step' => 1,
				'default' => 3,
				'condition' => [
					'scroll' => [ '1' ],
				],
			]
		);
		$this->add_control(
			'gutter',
			[
				'label' => __( 'Space Between Slides', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				//'label_block' => true,
				'min' => 0,
				'max' => 100,
				'step' => 1,
				'default' => 30,
				'condition' => [
					'scroll' => [ '1' ],
				],
			]
		);
		$this->add_control(
			'autoplay',
			[
				'label' => __( 'Turn On Autoplay', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'0' => __( 'No', 'DIRECTORYPRESS' ),
					'1' => __( 'Yes', 'DIRECTORYPRESS' ),
				],
				'default' => 0,
				'condition' => [
					'scroll' => [ '1' ],
				],
			]
		);
		$this->add_control(
			'autoplay_speed',
			[
				'label' => __( 'Autoplay Speed', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				//'label_block' => true,
				'min' => 100,
				'max' => 10000,
				'step' => 100,
				'default' => 1000,
				'condition' => [
					'scroll' => [ '1' ],
				],
			]
		);
		$this->add_control(
			'loop',
			[
				'label' => __( 'Turn On Loop', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'0' => __( 'No', 'DIRECTORYPRESS' ),
					'1' => __( 'Yes', 'DIRECTORYPRESS' ),
				],
				'default' => 0,
				'condition' => [
					'scroll' => [ '1' ],
				],
			]
		);
		$this->add_control(
			'owl_nav',
			[
				'label' => __( 'Turn On Navigation', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'0' => __( 'No', 'DIRECTORYPRESS' ),
					'1' => __( 'Yes', 'DIRECTORYPRESS' ),
				],
				'default' => 0,
				'condition' => [
					'scroll' => [ '1' ],
				],
			]
		);
		$this->add_control(
			'scroller_nav_style',
			[
				'label' => __( 'Navigation Style', 'DIRECTORYPRESS' ), 
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'1' => __( 'Style 1', 'DIRECTORYPRESS' ),
					'2' => __( 'Style 2', 'DIRECTORYPRESS' ),
				],
				'default' => 2,
				'condition' => [
					'scroll' => [ '1' ],
				],
			]
		);
		$this->add_control(
			'delay',
			[
				'label' => __( 'Delay', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				//'label_block' => true,
				'min' => 100,
				'max' => 10000,
				'step' => 100,
				'default' => 1000,
				'condition' => [
					'scroll' => [ '1' ],
				],
			]
		);
		
		$this->end_controls_section();
		
		// Style tab and section
		/* $this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Style', 'DIRECTORYPRESS' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'grid_thumb_dimension',
			[
				'label' => __( 'Grid Thumbnail Dimension', 'DIRECTORYPRESS' ),
				'label_block' => true,
				'type' => \Elementor\Controls_Manager::IMAGE_DIMENSIONS,
				'description' => __( 'Crop the original image size to any custom size. Set custom width or height to keep the original size ratio.', 'DIRECTORYPRESS' ),
				'default' => [
					'width' => '',
					'height' => '',
				],
			]
		);
		$this->add_control(
			'grid_padding',
			[
				'label' => __( 'Grid Column Gap', 'DIRECTORYPRESS' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				//'label_block' => true,
				'description' => __( 'Padding would effect grid item left and right, a 15px value means 30px gap between items', 'DIRECTORYPRESS' ),
				'min' => 0,
				'max' => 50,
				'step' => 1,
				'default' => 15,
			]
		);
		$this->end_controls_section(); */

	}

	/**
	 * Render oEmbed widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		if( $settings['cat_style'] == 3 || $settings['cat_style'] == 6 || $settings['cat_style'] == 7 || $settings['cat_style'] == 10){
			$columns = $settings['columns_set1'];
		}else{
			$columns = $settings['columns_set2'];
		}
		$instance = array(
				//'custom_home' => 0,
				'directorytype' => $settings['parent'],
				'parent' => $settings['parent'],
				'depth' => $settings['depth'],
				'columns' => $columns,
				'count' => $settings['count'],
				'hide_empty' => $settings['hide_empty'],
				'sublocations' => $settings['sublocations'], //
				'locations' => $settings['locations'], //
				'location_style' => $settings['location_style'], //
				'location_bg' => $settings['location_bg'],
				'location_bg_image' => $settings['location_bg_image'],
				'gradientbg1' => $settings['gradientbg1'],
				'gradientbg2' => $settings['gradientbg2'],
				'opacity1' => $settings['opacity1'],
				'opacity2' => $settings['opacity2'],
				'gradient_angle' => $settings['gradient_angle'],
				'location_width' => $settings['location_width'],
				'location_height' => $settings['location_height'],
				'location_padding' => $settings['location_padding'],
				
		);
		
		$directorypress_handler = new directorypress_locations_handler();
		$directorypress_handler->init($instance);

		echo '<div class="directorypress-elementor-listing-widget">';
			echo $directorypress_handler->display(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '</div>';
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
		echo '<script>
			( function( $ ) {
				directorypress_slik_init();	
			} )( jQuery );
		</script>';
		};
	}

}