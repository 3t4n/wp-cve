<?php
/**
 * Elementor test Widget.
 *
 * Elementor widget that inserts an embbedable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Control_Media;
class DirectoryPress_Elementor_Search_Widget extends \Elementor\Widget_Base {

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );
		
		add_action('wp_enqueue_scripts', array($this, 'scripts'));
		
	}
	public function scripts() {
		if ( \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
			wp_enqueue_style('directorypress-search', 1);
		}
	}
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
		return 'search';
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
		return __( 'Search Form', 'DIRECTORYPRESS' );
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
		return 'fab fa-searchengin';
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
		
		$this->setting_controls();
		$this->content_controls();
		$this->form_style_controls();
		$this->common_fields_style_controls();
		$this->keyword_field_style_controls();
		$this->category_field_style_controls();
		$this->address_field_style_controls();
		$this->location_field_style_controls();
		$this->button_style_controls();
		

	}
	
	protected function setting_controls() {
		// Setting Section
			$this->start_controls_section(
				'setting_section',
				[
					'label' => __( 'Setting', 'DIRECTORYPRESS' ),
					'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
				]
			);
			$this->add_control(
				'show_keywords_search',
				[
					'label' => __( 'Turn On Keyword Search', 'DIRECTORYPRESS' ), 
					'label_block' => true,
					'type' => \Elementor\Controls_Manager::SELECT2,
					'multiple' => false,
					'options' => [
						'0' => __( 'No', 'DIRECTORYPRESS' ),
						'1' => __( 'Yes', 'DIRECTORYPRESS' ),
					],
					'default' => 1,
					//'condition' => [
						//'scroll' => [ '1' ],
					//],
				]
			);
			$this->add_control(
				'keywords_ajax_search',
				[
					'label' => __( 'Turn On Ajax Keyword Search', 'DIRECTORYPRESS' ), 
					'label_block' => true,
					'type' => \Elementor\Controls_Manager::SELECT2,
					'multiple' => false,
					'options' => [
						'0' => __( 'No', 'DIRECTORYPRESS' ),
						'1' => __( 'Yes', 'DIRECTORYPRESS' ),
					],
					'default' => 1,
					'condition' => [
						'show_keywords_search' => [ '1' ],
					],
				]
			);
			$this->add_control(
				'keywords_search_examples',
				[
					'label' => __( 'Keywords Examples', 'DIRECTORYPRESS' ), 
					'label_block' => true,
					'description' => __( 'Comma-separated list of suggestions to try to search', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => '',
				]
			);
			$this->add_control(
				'what_search',
				[
					'label' => __( 'Default keywords', 'DIRECTORYPRESS' ), 
					'label_block' => true,
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => '',
				]
			);
			$this->add_control(
				'show_categories_search',
				[
					'label' => __( 'Turn On Category Search', 'DIRECTORYPRESS' ), 
					'label_block' => true,
					'type' => \Elementor\Controls_Manager::SELECT2,
					'multiple' => false,
					'options' => [
						'0' => __( 'No', 'DIRECTORYPRESS' ),
						'1' => __( 'Yes', 'DIRECTORYPRESS' ),
					],
					'default' => 1,
					//'condition' => [
						//'scroll' => [ '1' ],
					//],
				]
			);
			$this->add_control(
				'categories_search_depth',
				[
					'label' => __( 'Category Depth Level', 'DIRECTORYPRESS' ), 
					'label_block' => true,
					'type' => \Elementor\Controls_Manager::SELECT2,
					'multiple' => false,
					'options' => [
						'1' => __( 'Level 1', 'DIRECTORYPRESS' ),
						'2' => __( 'Level 2', 'DIRECTORYPRESS' ),
						//'3' => __( 'Level 3', 'DIRECTORYPRESS' ),
					],
					'default' => 1,
					'condition' => [
						'show_categories_search' => [ '1' ],
					],
				]
			);
			$this->add_control(
				'show_keywords_category_combo',
				[
					'label' => esc_html__('Show category and keyword combo ', 'DIRECTORYPRESS'),
					'type' => \Elementor\Controls_Manager::SWITCHER,
					'default' => 'yes',
					'label_on' =>esc_html__( 'Yes', 'DIRECTORYPRESS' ),
					'label_off' =>esc_html__( 'No', 'DIRECTORYPRESS' ),
				]
			);
			$this->add_control(
				'show_locations_search',
				[
					'label' => __( 'Turn On Location Search', 'DIRECTORYPRESS' ), 
					'label_block' => true,
					'type' => \Elementor\Controls_Manager::SELECT2,
					'multiple' => false,
					'options' => [
						'0' => __( 'No', 'DIRECTORYPRESS' ),
						'1' => __( 'Yes', 'DIRECTORYPRESS' ),
					],
					'default' => 1,
					//'condition' => [
						//'scroll' => [ '1' ],
					//],
				]
			);
			$this->add_control(
				'locations_search_depth',
				[
					'label' => __( 'Locations Depth Level', 'DIRECTORYPRESS' ), 
					'label_block' => true,
					'type' => \Elementor\Controls_Manager::SELECT2,
					'multiple' => false,
					'options' => [
						'1' => __( 'Level 1', 'DIRECTORYPRESS' ),
						'2' => __( 'Level 2', 'DIRECTORYPRESS' ),
						'3' => __( 'Level 3', 'DIRECTORYPRESS' ),
					],
					'default' => 1,
					'condition' => [
						'show_locations_search' => [ '1' ],
					],
				]
			);
			$this->add_control(
				'show_address_search',
				[
					'label' => __( 'Show Address Search?', 'DIRECTORYPRESS' ), 
					'label_block' => true,
					'type' => \Elementor\Controls_Manager::SELECT2,
					'multiple' => false,
					'options' => [
						'0' => __( 'No', 'DIRECTORYPRESS' ),
						'1' => __( 'Yes', 'DIRECTORYPRESS' ),
					],
					'default' => 1,
					//'condition' => [
						//'show_keywords_search' => [ '1' ],
					//],
				]
			);
			$this->add_control(
				'address',
				[
					'label' => __( 'Default address', 'DIRECTORYPRESS' ), 
					'label_block' => true,
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => '',
					'condition' => [
						'show_address_search' => [ '1' ],
					],
				]
			);
			$this->add_control(
				'show_radius_search',
				[
					'label' => __( 'Show Radius Search?', 'DIRECTORYPRESS' ), 
					'label_block' => true,
					'type' => \Elementor\Controls_Manager::SELECT2,
					'multiple' => false,
					'options' => [
						'0' => __( 'No', 'DIRECTORYPRESS' ),
						'1' => __( 'Yes', 'DIRECTORYPRESS' ),
					],
					'default' => 1,
					//'condition' => [
						//'show_keywords_search' => [ '1' ],
					//],
				]
			);
			$this->add_control(
				'radius',
				[
					'label' => __( 'Default Radius', 'DIRECTORYPRESS' ), 
					'label_block' => true,
					'type' => \Elementor\Controls_Manager::TEXT,
					'default' => '',
					'condition' => [
						'show_radius_search' => [ '1' ],
					],
				]
			);
			$this->add_control(
				'advanced_open',
				[
					'label' => __( 'Advanced Search Panel Always Open', 'DIRECTORYPRESS' ), 
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
			$this->add_control(
				'hide_search_button',
				[
					'label' => __( 'Hide Search Button', 'DIRECTORYPRESS' ), 
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
			$this->add_control(
				'show_default_field_label',
				[
					'label' => __( 'Turn On Default Field Labels', 'DIRECTORYPRESS' ), 
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
			$this->add_responsive_control(
				'fields_height',
				[
					'label' => __( 'Fields Height', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
							'step' => 1,
						]
					],
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-holder .form-control' => 'height: {{SIZE}}{{UNIT}};min-height: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .directorypress-search-holder .directorypress-autocomplete-dropmenubox-locations input' => 'height: {{SIZE}}{{UNIT}};min-height: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .directorypress-search-holder .select2-container--default .select2-selection--single .select2-selection__arrow' => 'height: {{SIZE}}{{UNIT}};min-height: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .directorypress-search-holder .select2-selection--single' => 'height: {{SIZE}}{{UNIT}};min-height: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .search-form-style1 .select2-selection--single' => 'height: {{SIZE}}{{UNIT}};min-height: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .directorypress-search-holder .select2-container--default .select2-selection--single .select2-selection__rendered' => 'height: {{SIZE}}{{UNIT}};min-height: {{SIZE}}{{UNIT}};'
					]
				]
			);
			$this->add_responsive_control(
				'gap_in_fields',
				[
					'label' => __( 'Fields Gap', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
							'step' => 1,
						]
					],
					'default' => [
						'unit' => 'px',
						'size' => 10,
					]
				]
			);
			$this->add_responsive_control(
				'fields_margin_top',
				[
					'label' => __( 'Fields Margin Top', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
							'step' => 1,
						]
					],
					'default' => [
						'unit' => 'px',
						'size' => 0,
					],
					'selectors' => [
						'{{WRAPPER}} .search-element-col' => 'margin-top: {{SIZE}}{{UNIT}};',
					]
				]
			);
			$this->add_responsive_control(
				'fields_margin_bottom',
				[
					'label' => __( 'Fields Margin Bottom', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
							'step' => 1,
						]
					],
					'default' => [
						'unit' => 'px',
						'size' => 15,
					],
					'selectors' => [
						'{{WRAPPER}} .search-element-col:not(.directorypress-search-submit-button-wrap)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					]
				]
			);
			$this->add_responsive_control(
				'keyword_field_width',
				[
					'label' => __( 'Keyword Field Width', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ '%' ],
					'range' => [
						'%' => [
							'min' => 0,
							'max' => 100,
							'step' => 1,
						]
					],
					'default' => [
						'unit' => '%',
						'size' => 25,
					],
					'selectors' => [
						'{{WRAPPER}} .keyword-search' => 'width: {{SIZE}}% !important;',
					],
				]
			);
			$this->add_responsive_control(
				'location_field_width',
				[
					'label' => __( 'Location Field Width', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ '%' ],
					'range' => [
						'%' => [
							'min' => 0,
							'max' => 100,
							'step' => 1,
						]
					],
					'default' => [
						'unit' => '%',
						'size' => 25,
					],
					'selectors' => [
						'{{WRAPPER}} .address-search' => 'width: {{SIZE}}% !important;',
					],
				]
			);
			$this->add_responsive_control(
				'custom_fields_width',
				[
					'label' => __( 'All Custom Fields Width', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ '%' ],
					'range' => [
						'%' => [
							'min' => 0,
							'max' => 100,
							'step' => 1,
						]
					],
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-holder .search-element-col:not(.keyword-search):not(.address-search):not(.directorypress-search-submit-button-wrap)' => 'width: {{SIZE}}% !important;',
					]
				]
			);
			$this->add_responsive_control(
				'radius_field_width',
				[
					'label' => __( 'Radius Field Width', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ '%' ],
					'range' => [
						'%' => [
							'min' => 0,
							'max' => 100,
							'step' => 1,
						]
					],
					'default' => [
						'unit' => '%',
						'size' => 25,
					],
					'selectors' => [
						'{{WRAPPER}} .radius-search-field' => 'width: {{SIZE}}% !important;',
					],
				]
			);
			$this->add_responsive_control(
				'button_field_width',
				[
					'label' => __( 'Search Button Width', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ '%' ],
					'range' => [
						'%' => [
							'min' => 0,
							'max' => 100,
							'step' => 1,
						]
					],
					'default' => [
						'unit' => '%',
						'size' => 25,
					],
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-submit-button-wrap' => 'width: {{SIZE}}% !important;',
					],
				]
			);
			$this->add_responsive_control(
				'button_margin_top',
				[
					'label' => __( 'Button Margin Top', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
							'step' => 1,
						]
					],
					'default' => [
						'unit' => 'px',
						'size' => 0,
					],
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-holder .directorypress-search-form-button button.btn' => 'margin-top: {{SIZE}}{{UNIT}};',
					]
				]
			);
			$this->end_controls_section(); 
	}

	protected function content_controls() {
		$directories = directorypress_directorytypes_array_options();
			$categories = directorypress_categories_array_options();
			$locations = directorypress_locations_array_options();
			$fields = directorypress_fields_array_options();
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
					'default' => [0],
				]
			);
			$this->add_control(
				'locations',
				[
					'label' => __( 'Select Specific Locations', 'DIRECTORYPRESS' ), 
					'label_block' => true,
					'type' => \Elementor\Controls_Manager::SELECT2,
					'multiple' => true,
					'options' => $locations,
					'default' => [0],
				]
			);
			$this->add_control(
				'search_fields',
				[
					'label' => __( 'Select Specific Fields', 'DIRECTORYPRESS' ), 
					'label_block' => true,
					'type' => \Elementor\Controls_Manager::SELECT2,
					'multiple' => true,
					'options' => $fields,
					'default' => [0],
				]
			);
			$this->add_control(
				'search_fields_advanced',
				[
					'label' => __( 'Select Specific Fields For Advanced Panel', 'DIRECTORYPRESS' ), 
					'label_block' => true,
					'type' => \Elementor\Controls_Manager::SELECT2,
					'multiple' => true,
					'options' => $fields,
					'default' => ['none'],
				]
			);
			$this->end_controls_section();
	}
	
	protected function form_style_controls() {
			// Style tab and section
			$this->start_controls_section(
				'style_section',
				[
					'label' => __( 'From Style', 'DIRECTORYPRESS' ),
					'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_responsive_control(
				'form_padding',
				[
					'label' => __( 'Search Form Padding', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-form .directorypress-search-holder' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'form_border',
					'label' => __( 'Search Form Border', 'plugin-domain' ),
					'selector' => '{{WRAPPER}} .directorypress-search-form',
				]
			);
			$this->add_responsive_control(
				'form_border_radius',
				[
					'label' => __( 'Search Form Border Radius', 'plugin-domain' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 50,
							'step' => 1,
						]
					],
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-form' => 'border-radius: {{SIZE}}{{UNIT}};',
					],
				]
			);
			
			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'form_background',
					'label' => __( 'Search Form Background', 'DIRECTORYPRESS' ),
					'description' => __( 'Search Form Background', 'DIRECTORYPRESS' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .directorypress-search-form',
				]
			);
			$this->end_controls_section();
	}
	
	
	protected function common_fields_style_controls() {
		// filed
			$this->start_controls_section(
				'field_section',
				[
					'label' => __( 'Common Fields', 'DIRECTORYPRESS' ),
					'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);
			// start tabs
			$this->start_controls_tabs(
				'field_style_tabs'
			);
			// start normal tab
			$this->start_controls_tab(
				'field_style_tab_normal',
				[
					'label' => esc_html__( 'Normal', 'DIRECTORYPRESS' ),
				]
			);
			$this->add_control(
				'field_text_color',
				[
					'label' => __( 'Text Color', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .form-control' => 'color: {{VALUE}}',
						'{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .select2-container--default .select2-selection--single:focus .select2-selection__rendered' => 'color: {{VALUE}}',
					],
				]
			);
			$this->add_control(
				'field_placeholder_color',
				[
					'label' => __( 'Placeholder Text Color', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .form-control::placeholder, .directorypress-search-form .directorypress-search-holder .form-control::-webkit-input-placeholder, .directorypress-search-form .directorypress-search-holder .form-control::-moz-placeholder, .directorypress-search-form .directorypress-search-holder .form-control:-moz-placeholder' => 'color: {{VALUE}}',
						'{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .select2-container--default .select2-selection--single .select2-selection__rendered' => 'color: {{VALUE}}',
					],
				]
			);
			$this->add_control(
				'field_label_color',
				[
					'label' => __( 'Label Color', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-form label' => 'color: {{VALUE}}',
					],
				]
			);
			$this->add_control(
				'field_icon_color',
				[
					'label' => __( 'Icon Color', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-form .directorypress-form-control-feedback' => 'color: {{VALUE}}',
						'{{WRAPPER}} .directorypress-search-holder .select2-selection--single .select2-selection__arrow' => 'color: {{VALUE}}',
						'{{WRAPPER}} .directorypress-search-holder .select2-container--default .select2-selection--single .select2-selection__arrow' => 'color: {{VALUE}}',
					],
				]
			);
			$this->add_control(
				'field_icon_bg_color',
				[
					'label' => __( 'Icon Background Color', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-form .directorypress-form-control-feedback' => 'background-color: {{VALUE}}',
						'{{WRAPPER}} .directorypress-search-holder .select2-selection--single .select2-selection__arrow' => 'background-color: {{VALUE}}',
						'{{WRAPPER}} .directorypress-search-holder .select2-container--default .select2-selection--single .select2-selection__arrow' => 'background-color: {{VALUE}}',
					],
				]
			);
			$this->add_control(
				'field_background_color',
				[
					'label' => __( 'Background Color', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .form-control' => 'background-color: {{VALUE}}',
						'{{WRAPPER}} .directorypress-search-holder .select2-selection--single' => 'background-color: {{VALUE}}',
						'{{WRAPPER}} .directorypress-search-holder .select2-container--default .select2-selection--single' => 'background-color: {{VALUE}}',
					],
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'field_box_shadow',
					'label' => __( 'Box Shadow', 'DIRECTORYPRESS' ),
					'selector' => '{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .form-control,
					{{WRAPPER}} .directorypress-search-holder .select2-selection--single,
					{{WRAPPER}} .directorypress-search-holder .select2-container--default .select2-selection--single',
				]
			);
			$this->end_controls_tab();
			
			// start hover tab
			$this->start_controls_tab(
				'field_style_tab_hover',
				[
					'label' => esc_html__( 'Hover', 'DIRECTORYPRESS' ),
				]
			);
			$this->add_control(
				'field_background_color_focus',
				[
					'label' => __( 'Background Color', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .form-control:focus' => 'background-color: {{VALUE}}',
						'{{WRAPPER}} .directorypress-search-holder .select2-selection--single:focus' => 'background-color: {{VALUE}}',
						'{{WRAPPER}} .directorypress-search-holder .select2-container--default .select2-selection--single:focus' => 'background-color: {{VALUE}}',
					],
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'field_box_shadow_hover',
					'label' => __( 'Box Shadow', 'DIRECTORYPRESS' ),
					'selector' => '{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .form-control:focus,
					{{WRAPPER}} .directorypress-search-holder .select2-selection--single:focus,
					{{WRAPPER}} .directorypress-search-holder .select2-container--default .select2-selection--single:focus',
				]
			);
			
			$this->end_controls_tab();
			$this->end_controls_tabs();
			$this->add_responsive_control(
				'field_padding',
				[
					'label' => __( 'Padding', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px'],
					'default' => [
						'top' => '',
						'bottom' => '',
						'left' => '',
						'right' => '',
					],
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .form-control,.directorypress-search-form .directorypress-search-holder .directorypress-tax-dropdowns-wrap .has-feedback input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .directorypress-search-holder .select2-selection--single' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .directorypress-search-holder .select2-container--default .select2-selection--single' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'field_icon_box_margin',
				[
					'label' => __( 'Icon Margin', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px'],
					'default' => [
						'top' => '',
						'bottom' => '',
						'left' => '',
						'right' => '',
					],
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-holder .directorypress-form-control-feedback' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'field_icon_box_width',
				[
					'label' => __( 'Icon box width', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
							'step' => 1,
						]
					],
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-holder .directorypress-form-control-feedback' => 'width: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'field_icon_box_height',
				[
					'label' => __( 'Icon box height', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
							'step' => 1,
						]
					],
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-holder .directorypress-form-control-feedback' => 'height: {{SIZE}}{{UNIT}};min-height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}',
					],
				]
			);
			$this->add_responsive_control(
				'field_icon_box_border_radius',
				[
					'label' => __( 'Icon Box Border Radius', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%'],
					'default' => [
						'top' => '',
						'bottom' => '',
						'left' => '',
						'right' => '',
					],
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-holder .directorypress-form-control-feedback' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'field_icon_size',
				[
					'label' => __( 'Field Icon Font Size', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 8,
							'max' => 36,
							'step' => 1,
						]
					],
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-form .directorypress-form-control-feedback' => 'font-size: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .directorypress-search-holder .select2-selection--single .select2-selection__arrow' => 'font-size: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .directorypress-search-holder .select2-container--default .select2-selection--single .select2-selection__arrow' => 'font-size: {{SIZE}}{{UNIT}};',
					],
				]
			);
			
			$this->add_responsive_control(
				'field_border-radius',
				[
					'label' => __( 'Field Border Radius', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%'],
					'default' => [
						'top' => 6,
						'bottom' => 6,
						'left' => 6,
						'right' => 6,
					],
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .form-control,.directorypress-search-form .directorypress-search-holder .directorypress-tax-dropdowns-wrap .has-feedback' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .directorypress-search-holder .select2-selection--single' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .directorypress-search-holder .select2-container--default .select2-selection--single' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'field_border_title',
				[
					'label' => __( 'Field Border', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'field_border',
					'label' => __( 'Border', 'DIRECTORYPRESS' ),
					'selector' => '{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .form-control,
					{{WRAPPER}} .directorypress-search-holder .select2-selection--single,
					{{WRAPPER}} .directorypress-search-holder .select2-container--default .select2-selection--single',
				]
			);
			
			$this->add_control(
				'field_placeholder_typo_control_title',
				[
					'label' => __( 'Field Typography', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'field_placehoder_typography',
					'label' => __( 'Typography', 'DIRECTORYPRESS' ),
					'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .form-control,
					{{WRAPPER}} .directorypress-search-holder .select2-selection--single,
					{{WRAPPER}} .directorypress-search-holder .select2-container--default .select2-selection--single',
				]
			);
			$this->add_control(
				'field_label_typo_control_title',
				[
					'label' => __( 'Field Label Typography', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'field_label_typography',
					'label' => __( 'Typography', 'DIRECTORYPRESS' ),
					'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .directorypress-search-form label',
				]
			);
			$this->end_controls_section();
	}
	
	
	
	protected function keyword_field_style_controls() {
		$this->start_controls_section(
			'keyword_field_section',
			[
				'label' => __( 'Keyword Field', 'DIRECTORYPRESS' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		// start tabs
			$this->start_controls_tabs(
				'keyword_field_style_tabs'
			);
			// start normal tab
			$this->start_controls_tab(
				'keyword_field_style_tab_normal',
				[
					'label' => esc_html__( 'Normal', 'DIRECTORYPRESS' ),
				]
			);
			$this->add_control(
				'keyword_field_text_color',
				[
					'label' => __( 'Text Color', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .keyword-search .form-control' => 'color: {{VALUE}}'
					],
				]
			);
			$this->add_control(
				'keyword_field_placeholder_color',
				[
					'label' => __( 'Placeholder Text Color', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-holder .keyword-search .form-control::placeholder' => 'color: {{VALUE}}',
						'{{WRAPPER}} .directorypress-search-holder .keyword-search .form-control::-webkit-input-placeholder' => 'color: {{VALUE}}',
						'{{WRAPPER}} .directorypress-search-holder .keyword-search .form-control::-moz-placeholder' => 'color: {{VALUE}}; opacity:1;',
						'{{WRAPPER}} .directorypress-search-holder .keyword-search .form-control::-moz-placeholder' => 'color: {{VALUE}}; opacity:1;',
					],
				]
			);
			$this->add_control(
				'keyword_field_label_color',
				[
					'label' => __( 'Label Color', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-form .keyword-search label' => 'color: {{VALUE}}',
					],
				]
			);
			$this->add_control(
				'keyword_field_icon_color',
				[
					'label' => __( 'Icon Color', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-form  .keyword-search .directorypress-form-control-feedback' => 'color: {{VALUE}}',
					],
				]
			);
			$this->add_control(
				'keyword_field_icon_bg_color',
				[
					'label' => __( 'Icon Background Color', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-form .keyword-search .directorypress-form-control-feedback' => 'background-color: {{VALUE}}'
					],
				]
			);
			$this->add_control(
				'keyword_field_background_color',
				[
					'label' => __( 'Background Color', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .keyword-search .form-control' => 'background-color: {{VALUE}}'
					],
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'keyword_field_box_shadow',
					'label' => __( 'Box Shadow', 'DIRECTORYPRESS' ),
					'selector' => '{{WRAPPER}} .directorypress-search-form .directorypress-search-holder  .keyword-search .form-control',
				]
			);
			$this->end_controls_tab();
			
			// start hover tab
			$this->start_controls_tab(
				'keyword_field_style_tab_hover',
				[
					'label' => esc_html__( 'Hover', 'DIRECTORYPRESS' ),
				]
			);
			$this->add_control(
				'keyword_field_background_color_focus',
				[
					'label' => __( 'Background Color', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-form .directorypress-search-holder  .keyword-search .form-control:focus' => 'background-color: {{VALUE}}'
					],
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'keyword_field_box_shadow_hover',
					'label' => __( 'Box Shadow', 'DIRECTORYPRESS' ),
					'selector' => '{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .keyword-search .form-control:focus',
				]
			);
			
			$this->end_controls_tab();
			$this->end_controls_tabs();
			$this->add_responsive_control(
				'keyword_field_padding',
				[
					'label' => __( 'Padding', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px'],
					'default' => [
						'top' => '',
						'bottom' => '',
						'left' => '',
						'right' => '',
					],
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .form-control,.directorypress-search-form .directorypress-search-holder .keyword-search .has-feedback input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'keyword_field_icon_box_margin',
				[
					'label' => __( 'Icon Margin', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px'],
					'default' => [
						'top' => '',
						'bottom' => '',
						'left' => '',
						'right' => '',
					],
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-holder .keyword-search .directorypress-form-control-feedback' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'keyword_field_icon_box_width',
				[
					'label' => __( 'Icon box width', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
							'step' => 1,
						]
					],
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-holder .keyword-search .directorypress-form-control-feedback' => 'width: {{SIZE}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'keyword_field_icon_box_height',
				[
					'label' => __( 'Icon box height', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
							'step' => 1,
						]
					],
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-holder .keyword-search .directorypress-form-control-feedback' => 'height: {{SIZE}}{{UNIT}};min-height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}',
					],
				]
			);
			$this->add_responsive_control(
				'keyword_field_icon_box_border_radius',
				[
					'label' => __( 'Icon Box Border Radius', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%'],
					'default' => [
						'top' => '',
						'bottom' => '',
						'left' => '',
						'right' => '',
					],
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-holder .keyword-search .directorypress-form-control-feedback' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'keyword_field_icon_size',
				[
					'label' => __( 'Field Icon Font Size', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 8,
							'max' => 36,
							'step' => 1,
						]
					],
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-form .keyword-search .directorypress-form-control-feedback' => 'font-size: {{SIZE}}{{UNIT}};',
					],
				]
			);
			
			$this->add_responsive_control(
				'keyword_field_border-radius',
				[
					'label' => __( 'Field Border Radius', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%'],
					'default' => [
						'top' => 6,
						'bottom' => 6,
						'left' => 6,
						'right' => 6,
					],
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .keyword-search .has-feedback' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_control(
				'keyword_field_border_title',
				[
					'label' => __( 'Field Border', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'keyword_field_border',
					'label' => __( 'Border', 'DIRECTORYPRESS' ),
					'selector' => '{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .keyword-search .form-control',
				]
			);
			
			$this->add_control(
				'keyword_field_placeholder_typo_control_title',
				[
					'label' => __( 'Field Typography', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'keyword_field_placehoder_typography',
					'label' => __( 'Typography', 'DIRECTORYPRESS' ),
					'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .keyword-search .form-control',
				]
			);
			$this->add_control(
				'keyword_field_label_typo_control_title',
				[
					'label' => __( 'Field Label Typography', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'keyword_field_label_typography',
					'label' => __( 'Typography', 'DIRECTORYPRESS' ),
					'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .directorypress-search-form .keyword-search label',
				]
			);
		$this->end_controls_section();
	}
	
	
	protected function category_field_style_controls() {
		$this->start_controls_section(
			'category_field_section',
			[
				'label' => __( 'Category Field', 'DIRECTORYPRESS' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		// start tabs
				$this->start_controls_tabs(
					'category_field_style_tabs'
				);
				// start normal tab
				$this->start_controls_tab(
					'category_field_style_tab_normal',
					[
						'label' => esc_html__( 'Normal', 'DIRECTORYPRESS' ),
					]
				);
				$this->add_control(
					'category_field_text_color',
					[
						'label' => __( 'Text Color', 'DIRECTORYPRESS' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .category-search .form-control' => 'color: {{VALUE}}'
						],
					]
				);
				$this->add_control(
					'category_field_placeholder_color',
					[
						'label' => __( 'Placeholder Text Color', 'DIRECTORYPRESS' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .directorypress-search-holder .category-search .form-control::placeholder' => 'color: {{VALUE}}',
							'{{WRAPPER}} .directorypress-search-holder .category-search .form-control::-webkit-input-placeholder' => 'color: {{VALUE}}',
							'{{WRAPPER}} .directorypress-search-holder .category-search .form-control::-moz-placeholder' => 'color: {{VALUE}}; opacity:1;',
							'{{WRAPPER}} .directorypress-search-holder .category-search .form-control::-moz-placeholder' => 'color: {{VALUE}}; opacity:1;',
						],
					]
				);
				$this->add_control(
					'category_field_label_color',
					[
						'label' => __( 'Label Color', 'DIRECTORYPRESS' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .directorypress-search-form .category-search label' => 'color: {{VALUE}}',
						],
					]
				);
				$this->add_control(
					'category_field_icon_color',
					[
						'label' => __( 'Icon Color', 'DIRECTORYPRESS' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .directorypress-search-form  .category-search .directorypress-form-control-feedback' => 'color: {{VALUE}}',
						],
					]
				);
				$this->add_control(
					'category_field_icon_bg_color',
					[
						'label' => __( 'Icon Background Color', 'DIRECTORYPRESS' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .directorypress-search-form .category-search .directorypress-form-control-feedback' => 'background-color: {{VALUE}}'
						],
					]
				);
				$this->add_control(
					'category_field_background_color',
					[
						'label' => __( 'Background Color', 'DIRECTORYPRESS' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .category-search .form-control' => 'background-color: {{VALUE}}'
						],
					]
				);
				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'category_field_box_shadow',
						'label' => __( 'Box Shadow', 'DIRECTORYPRESS' ),
						'selector' => '{{WRAPPER}} .directorypress-search-form .directorypress-search-holder  .category-search .form-control',
					]
				);
				$this->add_responsive_control(
					'category_field_border-radius',
					[
						'label' => __( 'Field Border Radius', 'DIRECTORYPRESS' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%'],
						'default' => [
							'top' => 6,
							'bottom' => 6,
							'left' => 6,
							'right' => 6,
						],
						'selectors' => [
							'{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .category-search .form-control' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->end_controls_tab();
				
				// start hover tab
				$this->start_controls_tab(
					'category_field_style_tab_hover',
					[
						'label' => esc_html__( 'Hover', 'DIRECTORYPRESS' ),
					]
				);
				$this->add_control(
					'category_field_background_color_focus',
					[
						'label' => __( 'Background Color', 'DIRECTORYPRESS' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .directorypress-search-form .directorypress-search-holder  .category-search .form-control:focus' => 'background-color: {{VALUE}}'
						],
					]
				);
				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'category_field_box_shadow_hover',
						'label' => __( 'Box Shadow', 'DIRECTORYPRESS' ),
						'selector' => '{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .category-search .form-control:focus',
					]
				);
				$this->add_responsive_control(
					'category_field_border-radius-focus',
					[
						'label' => __( 'Field Border Radius on focus', 'DIRECTORYPRESS' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%'],
						'default' => [
							'top' => '',
							'bottom' => '',
							'left' => '',
							'right' => '',
						],
						'selectors' => [
							'{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .category-search .form-control:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->end_controls_tab();
				$this->end_controls_tabs();
				$this->add_responsive_control(
					'category_field_padding',
					[
						'label' => __( 'Padding', 'DIRECTORYPRESS' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px'],
						'default' => [
							'top' => '',
							'bottom' => '',
							'left' => '',
							'right' => '',
						],
						'selectors' => [
							'{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .form-control,.directorypress-search-form .directorypress-search-holder .category-search .has-feedback input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_responsive_control(
					'category_field_icon_box_margin',
					[
						'label' => __( 'Icon Margin', 'DIRECTORYPRESS' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px'],
						'default' => [
							'top' => '',
							'bottom' => '',
							'left' => '',
							'right' => '',
						],
						'selectors' => [
							'{{WRAPPER}} .directorypress-search-holder .category-search .directorypress-form-control-feedback' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_responsive_control(
					'category_field_icon_box_width',
					[
						'label' => __( 'Icon box width', 'DIRECTORYPRESS' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 100,
								'step' => 1,
							]
						],
						'selectors' => [
							'{{WRAPPER}} .directorypress-search-holder .category-search .directorypress-form-control-feedback' => 'width: {{SIZE}}{{UNIT}};',
						],
					]
				);
				$this->add_responsive_control(
					'category_field_icon_box_height',
					[
						'label' => __( 'Icon box height', 'DIRECTORYPRESS' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 100,
								'step' => 1,
							]
						],
						'selectors' => [
							'{{WRAPPER}} .directorypress-search-holder .category-search .directorypress-form-control-feedback' => 'height: {{SIZE}}{{UNIT}};min-height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}',
						],
					]
				);
				$this->add_responsive_control(
					'category_field_icon_box_border_radius',
					[
						'label' => __( 'Icon Box Border Radius', 'DIRECTORYPRESS' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%'],
						'default' => [
							'top' => '',
							'bottom' => '',
							'left' => '',
							'right' => '',
						],
						'selectors' => [
							'{{WRAPPER}} .directorypress-search-holder .category-search .directorypress-form-control-feedback' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_responsive_control(
					'category_field_icon_size',
					[
						'label' => __( 'Field Icon Font Size', 'DIRECTORYPRESS' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => 8,
								'max' => 36,
								'step' => 1,
							]
						],
						'selectors' => [
							'{{WRAPPER}} .directorypress-search-form .category-search .directorypress-form-control-feedback' => 'font-size: {{SIZE}}{{UNIT}};',
						],
					]
				);
				
				
				$this->add_control(
					'category_field_border_title',
					[
						'label' => __( 'Field Border', 'DIRECTORYPRESS' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);
				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					[
						'name' => 'category_field_border',
						'label' => __( 'Border', 'DIRECTORYPRESS' ),
						'selector' => '{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .category-search .form-control',
					]
				);
				
				$this->add_control(
					'category_field_placeholder_typo_control_title',
					[
						'label' => __( 'Field Typography', 'DIRECTORYPRESS' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);
				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'category_field_placehoder_typography',
						'label' => __( 'Typography', 'DIRECTORYPRESS' ),
						'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
						'selector' => '{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .category-search .form-control',
					]
				);
				$this->add_control(
					'category_field_label_typo_control_title',
					[
						'label' => __( 'Field Label Typography', 'DIRECTORYPRESS' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);
				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'category_field_label_typography',
						'label' => __( 'Typography', 'DIRECTORYPRESS' ),
						'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
						'selector' => '{{WRAPPER}} .directorypress-search-form .category-search label',
					]
				);
		$this->end_controls_section();
	}
	
	
	protected function address_field_style_controls() {
		$this->start_controls_section(
			'address_field_section',
			[
				'label' => __( 'Address Field', 'DIRECTORYPRESS' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		// start tabs
				$this->start_controls_tabs(
					'address_field_style_tabs'
				);
				// start normal tab
				$this->start_controls_tab(
					'address_field_style_tab_normal',
					[
						'label' => esc_html__( 'Normal', 'DIRECTORYPRESS' ),
					]
				);
				$this->add_control(
					'address_field_text_color',
					[
						'label' => __( 'Text Color', 'DIRECTORYPRESS' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .address-search .form-control' => 'color: {{VALUE}}'
						],
					]
				);
				$this->add_control(
					'address_field_placeholder_color',
					[
						'label' => __( 'Placeholder Text Color', 'DIRECTORYPRESS' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .directorypress-search-holder .address-search .form-control::placeholder' => 'color: {{VALUE}}',
							'{{WRAPPER}} .directorypress-search-holder .address-search .form-control::-webkit-input-placeholder' => 'color: {{VALUE}}',
							'{{WRAPPER}} .directorypress-search-holder .address-search .form-control::-moz-placeholder' => 'color: {{VALUE}}; opacity:1;',
							'{{WRAPPER}} .directorypress-search-holder .address-search .form-control::-moz-placeholder' => 'color: {{VALUE}}; opacity:1;',
						],
					]
				);
				$this->add_control(
					'address_field_label_color',
					[
						'label' => __( 'Label Color', 'DIRECTORYPRESS' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .directorypress-search-form .address-search label' => 'color: {{VALUE}}',
						],
					]
				);
				$this->add_control(
					'address_field_icon_color',
					[
						'label' => __( 'Icon Color', 'DIRECTORYPRESS' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .directorypress-search-form  .address-search .directorypress-form-control-feedback' => 'color: {{VALUE}}',
						],
					]
				);
				$this->add_control(
					'address_field_icon_bg_color',
					[
						'label' => __( 'Icon Background Color', 'DIRECTORYPRESS' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .directorypress-search-form .address-search .directorypress-form-control-feedback' => 'background-color: {{VALUE}}'
						],
					]
				);
				$this->add_control(
					'address_field_background_color',
					[
						'label' => __( 'Background Color', 'DIRECTORYPRESS' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .address-search .form-control' => 'background-color: {{VALUE}}'
						],
					]
				);
				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'address_field_box_shadow',
						'label' => __( 'Box Shadow', 'DIRECTORYPRESS' ),
						'selector' => '{{WRAPPER}} .directorypress-search-form .directorypress-search-holder  .address-search .form-control',
					]
				);
				$this->end_controls_tab();
				
				// start hover tab
				$this->start_controls_tab(
					'address_field_style_tab_hover',
					[
						'label' => esc_html__( 'Hover', 'DIRECTORYPRESS' ),
					]
				);
				$this->add_control(
					'address_field_background_color_focus',
					[
						'label' => __( 'Background Color', 'DIRECTORYPRESS' ),
						'type' => \Elementor\Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .directorypress-search-form .directorypress-search-holder  .address-search .form-control:focus' => 'background-color: {{VALUE}}'
						],
					]
				);
				$this->add_group_control(
					\Elementor\Group_Control_Box_Shadow::get_type(),
					[
						'name' => 'address_field_box_shadow_hover',
						'label' => __( 'Box Shadow', 'DIRECTORYPRESS' ),
						'selector' => '{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .address-search .form-control:focus',
					]
				);
				
				$this->end_controls_tab();
				$this->end_controls_tabs();
				$this->add_responsive_control(
					'address_field_padding',
					[
						'label' => __( 'Padding', 'DIRECTORYPRESS' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px'],
						'default' => [
							'top' => '',
							'bottom' => '',
							'left' => '',
							'right' => '',
						],
						'selectors' => [
							'{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .form-control,.directorypress-search-form .directorypress-search-holder .address-search .has-feedback input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_responsive_control(
					'address_field_icon_box_margin',
					[
						'label' => __( 'Icon Margin', 'DIRECTORYPRESS' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px'],
						'default' => [
							'top' => '',
							'bottom' => '',
							'left' => '',
							'right' => '',
						],
						'selectors' => [
							'{{WRAPPER}} .directorypress-search-holder .address-search .directorypress-form-control-feedback' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_responsive_control(
					'address_field_icon_box_width',
					[
						'label' => __( 'Icon box width', 'DIRECTORYPRESS' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 100,
								'step' => 1,
							]
						],
						'selectors' => [
							'{{WRAPPER}} .directorypress-search-holder .address-search .directorypress-form-control-feedback' => 'width: {{SIZE}}{{UNIT}};',
						],
					]
				);
				$this->add_responsive_control(
					'address_field_icon_box_height',
					[
						'label' => __( 'Icon box height', 'DIRECTORYPRESS' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => 0,
								'max' => 100,
								'step' => 1,
							]
						],
						'selectors' => [
							'{{WRAPPER}} .directorypress-search-holder .address-search .directorypress-form-control-feedback' => 'height: {{SIZE}}{{UNIT}};min-height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}',
						],
					]
				);
				$this->add_responsive_control(
					'address_field_icon_box_border_radius',
					[
						'label' => __( 'Icon Box Border Radius', 'DIRECTORYPRESS' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%'],
						'default' => [
							'top' => '',
							'bottom' => '',
							'left' => '',
							'right' => '',
						],
						'selectors' => [
							'{{WRAPPER}} .directorypress-search-holder .address-search .directorypress-form-control-feedback' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_responsive_control(
					'address_field_icon_size',
					[
						'label' => __( 'Field Icon Font Size', 'DIRECTORYPRESS' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'size_units' => [ 'px' ],
						'range' => [
							'px' => [
								'min' => 8,
								'max' => 36,
								'step' => 1,
							]
						],
						'selectors' => [
							'{{WRAPPER}} .directorypress-search-form .address-search .directorypress-form-control-feedback' => 'font-size: {{SIZE}}{{UNIT}};',
						],
					]
				);
				
				$this->add_responsive_control(
					'address_field_border-radius',
					[
						'label' => __( 'Field Border Radius', 'DIRECTORYPRESS' ),
						'type' => \Elementor\Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', '%'],
						'default' => [
							'top' => 6,
							'bottom' => 6,
							'left' => 6,
							'right' => 6,
						],
						'selectors' => [
							'{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .address-search .has-feedback' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
				$this->add_control(
					'address_field_border_title',
					[
						'label' => __( 'Field Border', 'DIRECTORYPRESS' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);
				$this->add_group_control(
					\Elementor\Group_Control_Border::get_type(),
					[
						'name' => 'address_field_border',
						'label' => __( 'Border', 'DIRECTORYPRESS' ),
						'selector' => '{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .address-search .form-control',
					]
				);
				
				$this->add_control(
					'address_field_placeholder_typo_control_title',
					[
						'label' => __( 'Field Typography', 'DIRECTORYPRESS' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);
				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'address_field_placehoder_typography',
						'label' => __( 'Typography', 'DIRECTORYPRESS' ),
						'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
						'selector' => '{{WRAPPER}} .directorypress-search-form .directorypress-search-holder .address-search .form-control',
					]
				);
				$this->add_control(
					'address_field_label_typo_control_title',
					[
						'label' => __( 'Field Label Typography', 'DIRECTORYPRESS' ),
						'type' => \Elementor\Controls_Manager::HEADING,
						'separator' => 'before',
					]
				);
				$this->add_group_control(
					\Elementor\Group_Control_Typography::get_type(),
					[
						'name' => 'address_field_label_typography',
						'label' => __( 'Typography', 'DIRECTORYPRESS' ),
						'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
						'selector' => '{{WRAPPER}} .directorypress-search-form .address-search label',
					]
				);
		$this->end_controls_section();
	}
	
	protected function location_field_style_controls() {
		$this->start_controls_section(
			'location_field_section',
			[
				'label' => __( 'Location Field', 'DIRECTORYPRESS' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->end_controls_section();
	}
	
	protected function button_style_controls() {
		// button
			$this->start_controls_section(
				'button_section',
				[
					'label' => __( 'Button', 'DIRECTORYPRESS' ),
					'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				]
			);
			$this->add_control(
				'search_button_type',
				[
					'label' => __( 'Search Button Type', 'DIRECTORYPRESS' ), 
					'label_block' => true,
					'type' => \Elementor\Controls_Manager::SELECT2,
					'multiple' => false,
					'options' => [
						'1'  => __( 'Text + Icon Left', 'DIRECTORYPRESS' ),
						'2'  => __( 'Text + Icon Right', 'DIRECTORYPRESS' ),
						'3'  => __( 'Text Only', 'DIRECTORYPRESS' ),
						'4'  => __( 'Icon Only', 'DIRECTORYPRESS' ),
					],
					'default' => ['none'],
				]
			);
			$this->add_responsive_control(
				'button_align',
				[
					'label' => esc_html__( 'Alignment', 'DIRECTORYPRESS' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'left' => [
							'title' => esc_html__( 'Left', 'DIRECTORYPRESS' ),
							'icon' => 'eicon-text-align-left',
						],
						/* 'none' => [
							'title' => esc_html__( 'Center', 'DIRECTORYPRESS' ),
							'icon' => 'eicon-text-align-center',
						], */
						'right' => [
							'title' => esc_html__( 'Right', 'DIRECTORYPRESS' ),
							'icon' => 'eicon-text-align-right',
						],
					],
					'default' => '',
					'toggle' => true,
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-submit-button-wrap' => 'float: {{VALUE}} !important;',
					],
				]
			);
			$this->add_control(
				'search_button_icon',
				[
					'label' => __( 'Search Icon', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'skin' => 'inline',
					'exclude_inline_options' => 'svg',
					'default' => [
						'value' => 'fas fa-search',
						'library' => 'solid',
					],
				]
			);
			$this->add_control(
				'button_typo_control_title',
				[
					'label' => __( 'Button Typography', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Typography::get_type(),
				[
					'name' => 'button_typography',
					'label' => __( 'Typography', 'DIRECTORYPRESS' ),
					'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .directorypress-search-holder .directorypress-search-form-button button.btn',
				]
			);
			
			// start tabs
			$this->start_controls_tabs(
				'button_style_tabs'
			);
			// start normal tab
			$this->start_controls_tab(
				'button_style_tab_normal',
				[
					'label' => esc_html__( 'Normal', 'DIRECTORYPRESS' ),
				]
			);
			$this->add_control(
				'button_text_color',
				[
					'label' => __( 'Text Color', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-holder .directorypress-search-form-button button.btn' => 'color: {{VALUE}}',
					],
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'form_button_background',
					'label' => __( 'Button Background', 'DIRECTORYPRESS' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .directorypress-search-holder .directorypress-search-form-button button.btn',
				]
			);
			
			$this->add_group_control(
				\Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'box_shadow',
					'label' => __( 'Box Shadow', 'DIRECTORYPRESS' ),
					'selector' => '{{WRAPPER}} .directorypress-search-holder .directorypress-search-form-button button.btn',
				]
			);
			$this->end_controls_tab();
			
			// start hover tab
			$this->start_controls_tab(
				'button_style_tab_hover',
				[
					'label' => esc_html__( 'Hover', 'DIRECTORYPRESS' ),
				]
			);
			$this->add_control(
				'button_text_color_hover',
				[
					'label' => __( 'Text Hover Color', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-holder .directorypress-search-form-button button.btn:hover' => 'color: {{VALUE}}',
					],
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Background::get_type(),
				[
					'name' => 'form_button_background_hover',
					'label' => __( 'Button Background Hover', 'DIRECTORYPRESS' ),
					'types' => [ 'classic', 'gradient' ],
					'selector' => '{{WRAPPER}} .directorypress-search-holder .directorypress-search-form-button button.btn:hover',
				]
			);
			$this->add_control(
				'button_border_color_hover',
				[
					'label' => __( 'Border Hover Color', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-holder .directorypress-search-form-button button.btn:hover' => 'border-color: {{VALUE}}',
					],
				]
			);
			$this->add_group_control(
				\Elementor\Group_Control_Box_Shadow::get_type(),
				[
					'name' => 'box_shadow_hover',
					'label' => __( 'Box Shadow', 'DIRECTORYPRESS' ),
					'selector' => '{{WRAPPER}} .directorypress-search-holder .directorypress-search-form-button button.btn:hover',
				]
			);
			
			$this->end_controls_tab();
			$this->end_controls_tabs();
			
			$this->add_group_control(
				\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'button_border',
					'label' => __( 'Border', 'DIRECTORYPRESS' ),
					'selector' => '{{WRAPPER}} .directorypress-search-holder .directorypress-search-form-button button.btn',
				]
			);
			$this->add_responsive_control(
				'search_button_border',
				[
					'label' => __( 'Button Border Radius', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px'],
					'default' => [
						'top' => '',
						'bottom' => '',
						'left' => '',
						'right' => '',
					],
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-holder .directorypress-search-form-button button.btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'search_button_height',
				[
					'label' => __( 'Button Height', 'DIRECTORYPRESS' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 200,
							'step' => 1,
						]
					],
					'selectors' => [
						'{{WRAPPER}} .directorypress-search-holder .directorypress-search-form-button button.btn' => 'height: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}}',
					],
				]
			);
			
			$this->end_controls_section();
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
		if(in_array('0', $settings['search_fields'])){
			$fields = '';
		}elseif(in_array('none', $settings['search_fields'])){
			$fields = '-1';
		}else{
			$fields = implode(',', $settings['search_fields']);
		}
		if(in_array('0', $settings['search_fields_advanced'])){
			$advanced_fields = '';
		}elseif(in_array('none', $settings['search_fields_advanced'])){
			$advanced_fields = '-1';
		}else{
			$advanced_fields = implode(',', $settings['search_fields_advanced']);
		}
		//print_r($settings['categories']);
		$category = implode(',', $settings['categories']);
		$location = implode(',', $settings['locations']);
		$instance = array(
				'directorytype' => $settings['directorytype'],
				//'columns' => 2,
				'advanced_open' => $settings['advanced_open'],
				'uid' => $settings['uid'],
				'form_layout' => 'horizontal',
				'show_keywords_category_combo' => $settings['show_keywords_category_combo'],
				'show_categories_search' =>  $settings['show_categories_search'],
				'categories_search_depth' =>  $settings['categories_search_depth'],
				'category' => $category,
				//'exact_categories' => array(),
				'show_default_filed_label' => $settings['show_default_field_label'],
				'show_keywords_search' =>  $settings['show_keywords_search'],
				'keywords_ajax_search' =>  $settings['keywords_ajax_search'],
				'keywords_search_examples' => $settings['keywords_search_examples'],
				'what_search' => $settings['what_search'],
				'show_radius_search' =>  $settings['show_radius_search'],
				'radius' =>  $settings['radius'],
				'show_locations_search' =>  $settings['show_locations_search'],
				'locations_search_depth' =>  $settings['locations_search_depth'],
				'show_address_search' =>  $settings['show_address_search'],
				'address' => $settings['address'],
				'location' => $location,
				//'exact_locations' => array(),
				'search_fields' => $fields,
				'search_fields_advanced' => $advanced_fields,
				'hide_search_button' => $settings['hide_search_button'],
				'on_row_search_button' => 0,
				'has_sticky_scroll' => 0,
				'has_sticky_scroll_toppadding' => 0,
				'gap_in_fields' => $settings['gap_in_fields']['size'],
				'search_button_icon' => $settings['search_button_icon']['value'],
				'search_button_type' => $settings['search_button_type'],
				'keyword_field_width' => $settings['keyword_field_width']['size'],
				'location_field_width' => $settings['location_field_width']['size'],
				'radius_field_width' => $settings['radius_field_width']['size'],
				'button_field_width' => $settings['button_field_width']['size'],
				'scroll_to' => 'listings', // '', 'listings', 'map'
				
		);
		
		$directorypress_handler = new directorypress_search_handler();
		$directorypress_handler->init($instance);

		echo '<div class="directorypress-elementor-search-widget">';
			echo $directorypress_handler->display(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			//print_r($settings['search_button_icon']);
		echo '</div>';
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
		echo '<script>
			( function( $ ) {
				directorypress_select2_init();
				directorypress_process_main_search_fields();
			} )( jQuery );
		</script>';
		};
	}

}