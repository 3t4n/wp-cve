<?php
/**
 * Classic WPTD Video Popup Widget
 * @since 1.0.0
 */
class Elementor_Video_Popup_Widget extends \Elementor\Widget_Base {
		
	/**
	 * Get widget name.
	 *
	 * Retrieve Video Popup name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return "wptd_video_popup";
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve Blog widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( "Video Popup WPTD", "wptd-video-popup" );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve Blog widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return "eicon-youtube";
	}


	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the Popup widget belongs to.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ "wptd" ];
	}
	
	/**
	 * Retrieve the list of scripts the counter widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'magnific-popup', 'wptd-video-popup' ];
	}
	
	public function get_style_depends() {
		return [ 'magnific-popup', 'wptd-video-popup' ];
	}
	
	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'video popup', 'magnific', 'popup', 'modal', 'youtube', 'vimeo', 'iframe' ];
	}
	
	/**
	 * Register Popup widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		//General Section
		$this->start_controls_section(
			"general_section",
			[
				"label"	=> esc_html__( "Popup", "wptd-video-popup" ),
				"tab"	=> \Elementor\Controls_Manager::TAB_CONTENT,
				"description"	=> esc_html__( "Default popup options.", "wptd-video-popup" ),
			]
		);
		$this->add_control(
			'popup_type',
			[
				'label' => __( 'Popup Type', 'elementor' ),
				"description"	=> esc_html__( "This is option for popup type.", "wptd-video-popup" ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'default' => 'txt',
				'options' => [
					'icon' => [
						'title' => __( 'Icon', 'elementor' ),
						'icon' => 'eicon-favorite',
					],
					'btn' => [
						'title' => __( 'Button', 'elementor' ),
						'icon' => 'eicon-button',
					],
					'img' => [
						'title' => __( 'Image', 'elementor' ),
						'icon' => 'eicon-image',
					],
					'txt' => [
						'title' => __( 'Text', 'elementor' ),
						'icon' => 'eicon-t-letter',
					]
				],
				'toggle' => false,
			]
		);	
		$this->add_control(
			"popup_url",
			[
				"type"			=> \Elementor\Controls_Manager::TEXT,
				"label" 		=> esc_html__( "Video URL", "wptd-video-popup" ),
				"description"	=> esc_html__( "Enter popup video url. Example https://www.youtube.com/watch?v=LXb3EKWsInQ", "wptd-video-popup" ),
				"default"		=> "https://www.youtube.com/watch?v=LXb3EKWsInQ"
			]
		);
		$this->add_responsive_control(
			'text_align',
			[
				'label' => __( 'Alignment', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'wptd-video-popup' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'wptd-video-popup' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'wptd-video-popup' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'wptd-video-popup' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wptd-video-popup-wrapper' => 'text-align: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();
		
		//Icon Section
		$this->start_controls_section(
			'section_icon',
			[
				'label' => esc_html__( 'Icon', 'wptd-video-popup' ),
				'condition' => [
					'popup_type' => 'icon',
				],
			]
		);		
		$this->add_control(
			'selected_icon',
			[
				'label' => esc_html__( 'Icon', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default' => [
					'value' => 'fa fa-heart',
					'library' => 'themify',
				],
			]
		);
		$this->add_control(
			'icon_view',
			[
				'label' => esc_html__( 'View', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'default' => esc_html__( 'Default', 'wptd-video-popup' ),
					'stacked' => esc_html__( 'Stacked', 'wptd-video-popup' ),
					'framed' => esc_html__( 'Framed', 'wptd-video-popup' ),
				],
				'default' => 'default',
				'prefix_class' => 'wptd-video-popup-view-',
			]
		);
		$this->add_control(
			'icon_shape',
			[
				'label' => esc_html__( 'Shape', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'circle' => esc_html__( 'Circle', 'wptd-video-popup' ),
					'square' => esc_html__( 'Square', 'wptd-video-popup' ),
				],
				'default' => 'circle',
				'condition' => [
					'icon_view!' => 'default',
				],
				'prefix_class' => 'wptd-video-popup-shape-',
			]
		);
		$this->end_controls_section();	
		
		// Image Section
		$this->start_controls_section(
			"image_section",
			[
				"label"			=> esc_html__( "Image", "wptd-video-popup" ),
				"tab"			=> \Elementor\Controls_Manager::TAB_CONTENT,
				"description"	=> esc_html__( "Popup trigger image options available here.", "wptd-video-popup" ),
				'condition' => [
					'popup_type' => 'img',
				],
			]
		);
		$this->add_control(
			"image",
			[
				"type" => \Elementor\Controls_Manager::MEDIA,
				"label" => esc_html__( "Image", "wptd-video-popup" ),
				"description"	=> esc_html__( "Choose popup trigger image.", "wptd-video-popup" ),
				"dynamic" => [
					"active" => true,
				],
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);		
		$this->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
				'default' => 'full',
				'separator' => 'none',
			]
		);				
		$this->end_controls_section();	
		
		// Button
		$this->start_controls_section(
			"button_section",
			[
				"label"			=> esc_html__( "Button", "wptd-video-popup" ),
				"tab"			=> \Elementor\Controls_Manager::TAB_CONTENT,
				"description"	=> esc_html__( "Button options available here.", "wptd-video-popup" ),
				'condition' => [
					'popup_type' => 'btn',
				],
			]
		);
		$this->add_control(
			'button_type',
			[
				'label' => esc_html__( 'Type', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( 'Default', 'wptd-video-popup' ),
					'info' => esc_html__( 'Info', 'wptd-video-popup' ),
					'success' => esc_html__( 'Success', 'wptd-video-popup' ),
					'warning' => esc_html__( 'Warning', 'wptd-video-popup' ),
					'danger' => esc_html__( 'Danger', 'wptd-video-popup' ),
				],
				'prefix_class' => 'elementor-button-',
			]
		);
		$this->add_control(
			'button_text',
			[
				'label' => esc_html__( 'Text', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Click here', 'wptd-video-popup' ),
				'placeholder' => esc_html__( 'Click here', 'wptd-video-popup' ),
			]
		);
		$this->add_responsive_control(
			'button_align',
			[
				'label' => esc_html__( 'Alignment', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
						'title' => esc_html__( 'Left', 'wptd-video-popup' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wptd-video-popup' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wptd-video-popup' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'wptd-video-popup' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'prefix_class' => 'wptd-video-popup-btn%s-align-',
				'default' => '',
			]
		);
		$this->add_control(
			'button_size',
			[
				'label' => esc_html__( 'Size', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'sm',
				'options' => [
			'xs' => __( 'Extra Small', 'elementor' ),
			'sm' => __( 'Small', 'elementor' ),
			'md' => __( 'Medium', 'elementor' ),
			'lg' => __( 'Large', 'elementor' ),
			'xl' => __( 'Extra Large', 'elementor' ),
		],//self::get_button_sizes(),
				'style_transfer' => true,
			]
		);
		$this->add_control(
			'button_icon',
			[
				'label' => esc_html__( 'Icon', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
			]
		);
		$this->add_control(
			'button_icon_align',
			[
				'label' => esc_html__( 'Icon Position', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__( 'Before', 'wptd-video-popup' ),
					'right' => esc_html__( 'After', 'wptd-video-popup' ),
				],
				'condition' => [
					'button_icon[value]!' => '',
				],
			]
		);
		$this->add_control(
			'button_icon_indent',
			[
				'label' => esc_html__( 'Icon Spacing', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wptd-video-popup-button .wptd-video-popup-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wptd-video-popup-button .wptd-video-popup-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'button_view',
			[
				'label' => esc_html__( 'View', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);
		$this->add_control(
			'button_css_id',
			[
				'label' => esc_html__( 'Button ID', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
				'title' => esc_html__( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'wptd-video-popup' ),
				'description' => esc_html__( 'Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'wptd-video-popup' ),
				'separator' => 'before',

			]
		);
		$this->end_controls_section();
		
		// Text
		$this->start_controls_section(
			"text_section",
			[
				"label"			=> esc_html__( "Text", "wptd-video-popup" ),
				"tab"			=> \Elementor\Controls_Manager::TAB_CONTENT,
				"description"	=> esc_html__( "Popup trigger text.", "wptd-video-popup" ),
				'condition' => [
					'popup_type' => 'txt',
				]
			]
		);
		$this->add_control(
			"popup_text",
			[
				"type"			=> \Elementor\Controls_Manager::TEXT,
				"label" 		=> esc_html__( "Popup Trigger Text", "wptd-video-popup" ),
				"description"	=> esc_html__( "Example: Click here", "wptd-video-popup" ),
				"default"		=> esc_html__( "Click here", "wptd-video-popup" ),
			]
		);
		$this->end_controls_section();
			
		// Style General Section
		$this->start_controls_section(
			'section_style_general',
			[
				'label' => __( 'General', 'wptd-video-popup' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'popup_padding',
			[
				'label' => esc_html__( 'Padding', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .wptd-video-popup-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);
		$this->add_control(
			'popup_margin',
			[
				'label' => esc_html__( 'Margin', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .wptd-video-popup-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);
		$this->start_controls_tabs( 'general_styles' );
			$this->start_controls_tab(
				'general_normal',
				[
					'label' => esc_html__( 'Normal', 'wptd-video-popup' ),
				]
			);
			$this->add_control(
				'font_color',
				[
					'label' => esc_html__( 'Font Color', 'wptd-video-popup' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .wptd-video-popup-wrapper' => 'color: {{VALUE}};'
					]
				]
			);
			$this->add_control(
				'bg_color',
				[
					'label' => esc_html__( 'Background Color', 'wptd-video-popup' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .wptd-video-popup-wrapper' => 'background-color: {{VALUE}};'
					]
				]
			);
			$this->end_controls_tab();
			$this->start_controls_tab(
				'general_hover',
				[
					'label' => esc_html__( 'Hover', 'wptd-video-popup' ),
				]
			);
			$this->add_control(
				'font_hcolor',
				[
					'label' => esc_html__( 'Font Color', 'wptd-video-popup' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .wptd-video-popup-wrapper:hover' => 'color: {{VALUE}};'
					]
				]
			);
			$this->add_control(
				'bg_hcolor',
				[
					'label' => esc_html__( 'Background Color', 'wptd-video-popup' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .wptd-video-popup-wrapper:hover' => 'background-color: {{VALUE}};'
					]
				]
			);
			$this->end_controls_tab();	
		$this->end_controls_tabs();	
		$this->end_controls_section();	
		
		// Style Icon Section
		$this->start_controls_section(
			'section_style_icon',
			[
				'label' => esc_html__( 'Icon', 'wptd-video-popup' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'popup_type' => 'icon',
				]
			]
		);
		$this->start_controls_tabs( 'icon_colors' );
		$this->start_controls_tab(
			'icon_colors_normal',
			[
				'label' => esc_html__( 'Normal', 'wptd-video-popup' ),
			]
		);
		$this->add_control(
			'icon_primary_color',
			[
				'label' => esc_html__( 'Primary Color', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}}.wptd-video-popup-view-stacked .popup-trigger-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.wptd-video-popup-view-framed .popup-trigger-icon, {{WRAPPER}}.wptd-video-popup-view-default .popup-trigger-icon' => 'color: {{VALUE}}; border-color: {{VALUE}};',
					'{{WRAPPER}}.wptd-video-popup-view-framed .popup-trigger-icon, {{WRAPPER}}.wptd-video-popup-view-default .popup-trigger-icon svg' => 'fill: {{VALUE}};',
				]
			]
		);
		$this->add_control(
			'icon_secondary_color',
			[
				'label' => esc_html__( 'Secondary Color', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#888888',
				'condition' => [
					'icon_view!' => 'default',
				],
				'selectors' => [
					'{{WRAPPER}}.wptd-video-popup-view-framed .popup-trigger-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.wptd-video-popup-view-stacked .popup-trigger-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}}.wptd-video-popup-view-stacked .popup-trigger-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'icon_colors_hover',
			[
				'label' => esc_html__( 'Hover', 'wptd-video-popup' ),
			]
		);
		$this->add_control(
			'icon_primary_hcolor',
			[
				'label' => esc_html__( 'Primary Hover Color', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}.wptd-video-popup-view-stacked:hover .popup-trigger-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.wptd-video-popup-view-framed:hover .popup-trigger-icon, {{WRAPPER}}.wptd-video-popup-view-default:hover .popup-trigger-icon' => 'color: {{VALUE}}; border-color: {{VALUE}};',
					'{{WRAPPER}}.wptd-video-popup-view-framed:hover .popup-trigger-icon, {{WRAPPER}}.wptd-video-popup-view-default:hover .popup-trigger-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'icon_secondary_hcolor',
			[
				'label' => esc_html__( 'Secondary Hover Color', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'condition' => [
					'icon_view!' => 'default',
				],
				'selectors' => [
					'{{WRAPPER}}.wptd-video-popup-view-framed:hover .popup-trigger-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}}.wptd-video-popup-view-stacked:hover .popup-trigger-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}}.wptd-video-popup-view-stacked:hover .popup-trigger-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);		

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Size', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .popup-trigger-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
		$this->add_control(
			'icon_padding',
			[
				'label' => esc_html__( 'Padding', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}}.wptd-video-popup-view-stacked .popup-trigger-icon' => 'padding: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wptd-video-popup-view-framed .popup-trigger-icon' => 'padding: {{SIZE}}{{UNIT}};'
				],
				'defailt' => [
					'unit' => 'px',
					'size' => 30,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'condition' => [
					'icon_view!' => 'default',
				],
			]
		);
		$this->add_responsive_control(
			'icon_rotate',
			[
				'label' => esc_html__( 'Rotate', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'default' => [
					'size' => 0,
					'unit' => 'deg',
				],
				'tablet_default' => [
					'unit' => 'deg',
				],
				'mobile_default' => [
					'unit' => 'deg',
				],
				'selectors' => [
					'{{WRAPPER}} .popup-trigger-icon i, {{WRAPPER}} .popup-trigger-icon svg' => 'transform: rotate({{SIZE}}{{UNIT}});',
				],
			]
		);
		$this->add_responsive_control(
			'icon_spacing',
			[
				'label' => esc_html__( 'Spacing', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [
					'size' => 5,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .popup-trigger-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'(mobile){{WRAPPER}} .popup-trigger-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'icon_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .popup-trigger-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'icon_view' => 'framed',
				],
			]
		);
		$this->add_control(
			'icon_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .popup-trigger-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'icon_view!' => 'default',
				],
			]
		);
		$this->add_control(
			'icon_animation',
			[
				'label' => esc_html__( 'Icon Animation', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::ANIMATION,
				'selectors' => [
					'{{WRAPPER}} .wptd-video-popup-wrapper:hover .popup-trigger-icon.wptd-video-popup-elementor-animation' => 'animation-name: {{VALUE}};'
				]
			]
		);		
		$this->end_controls_section();
		
		// Style Image Section		
		$this->start_controls_section(
			'section_style_image',
			[
				'label' => __( 'Image', 'wptd-video-popup' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'popup_type' => 'img',
				]
			]
		);
		
		$this->start_controls_tabs( 'counter_image_styles' );
		$this->start_controls_tab(
			'counter_img_normal',
			[
				'label' => esc_html__( 'Normal', 'wptd-video-popup' ),
			]
		);
		$this->add_control(
			'counter_img_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wptd-video-popup-wrapper .popup-trigger-img > img' => 'background-color: {{VALUE}};'
				]
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'counter_img_hover',
			[
				'label' => esc_html__( 'Hover', 'wptd-video-popup' ),
			]
		);
		$this->add_control(
			'counter_img_bg_hcolor',
			[
				'label' => esc_html__( 'Hover Background Color', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wptd-video-popup-wrapper:hover .popup-trigger-img > img' => 'background-color: {{VALUE}};'
				],
			]
		);
		$this->add_control(
			'hover_animation',
			[
				'label' => __( 'Hover Animation', 'elementor' ),
				'type' => \Elementor\Controls_Manager::HOVER_ANIMATION,
			]
		);	
		$this->end_controls_tab();
		$this->end_controls_tabs();			
			
		$this->add_control(
			"img_style",
			[
				"label"			=> esc_html__( "Image Style", "wptd-video-popup" ),
				"type"			=> \Elementor\Controls_Manager::SELECT,
				"description"	=> esc_html__( "Choose image style.", "wptd-video-popup" ),
				"default"		=> "squared",
				"options"		=> [
					"squared"			=> esc_html__( "Squared", "wptd-video-popup" ),
					"rounded"			=> esc_html__( "Rounded", "wptd-video-popup" ),
					"rounded-circle"	=> esc_html__( "Circled", "wptd-video-popup" )
				]
			]
		);
		$this->add_control(
			"resize_opt",
			[
				"label" 		=> esc_html__( "Resize Option", "wptd-video-popup" ),
				"description"	=> esc_html__( "Enable resize option.", "wptd-video-popup" ),
				"type" 			=> \Elementor\Controls_Manager::SWITCHER,
				"default" 		=> "no"
			]
		);
		$this->add_responsive_control(
			'image_size',
			[
				'label' => esc_html__( 'Image Size', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'condition' => [
					'resize_opt' => 'yes',	
				],
				'selectors' => [
					'{{WRAPPER}} .popup-trigger-img > img' => 'width: {{SIZE}}%; max-width: {{SIZE}}%;',
					'{{WRAPPER}} .popup-trigger-img' => 'width: {{SIZE}}%; max-width: {{SIZE}}%;'
				],
			]
		);
		$this->add_responsive_control(
			'image_spacing',
			[
				'label' => esc_html__( 'Image Spacing', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'default' => [
					'size' => 5,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .popup-trigger-img' => 'margin-bottom: {{SIZE}}{{UNIT}};'
				],
			]
		);		
		$this->add_control(
			'counter_img_padding',
			[
				'label' => esc_html__( 'Padding', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .popup-trigger-img > img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
				[
					'name' => 'counter_img_border',
					'label' => esc_html__( 'Border', 'wptd-video-popup' ),
					'selector' => '{{WRAPPER}} .popup-trigger-img > img'
				]
		);
		$this->end_controls_section();
		
		// Style Button Section
		$this->start_controls_section(
			'button_section_style',
			[
				'label' => esc_html__( 'Button', 'wptd-video-popup' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'popup_type' => 'btn',
				]
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'text_shadow',
				'selector' => '{{WRAPPER}} .wptd-video-popup-button',
			]
		);
		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'wptd-video-popup' ),
			]
		);
		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wptd-video-popup-button' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'button_background_color',
			[
				'label' => esc_html__( 'Background Color', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wptd-video-popup-button' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'wptd-video-popup' ),
			]
		);
		$this->add_control(
			'button_hover_color',
			[
				'label' => esc_html__( 'Text Color', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wptd-video-popup-button:hover, {{WRAPPER}} .wptd-video-popup-button:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wptd-video-popup-button:hover svg, {{WRAPPER}} .wptd-video-popup-button:focus svg' => 'fill: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'button_background_hover_color',
			[
				'label' => esc_html__( 'Background Color', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wptd-video-popup-button:hover, {{WRAPPER}} .wptd-video-popup-button:focus' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition' => [
					'border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .wptd-video-popup-button:hover, {{WRAPPER}} .wptd-video-popup-button:focus' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'button_hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::HOVER_ANIMATION,
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'selector' => '{{WRAPPER}} .wptd-video-popup-button',
				'separator' => 'before',
			]
		);
		$this->add_control(
			'button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wptd-video-popup-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .wptd-video-popup-button',
			]
		);
		$this->add_responsive_control(
			'button_text_padding',
			[
				'label' => esc_html__( 'Padding', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wptd-video-popup-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
		$this->end_controls_section();	
		
		// Style Text Section
		$this->start_controls_section(
			'section_style_text',
			[
				'label' => __( 'Text', 'wptd-video-popup' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'popup_type' => 'txt',
				]
			]
		);
		$this->add_control(
			'popup_text_padding',
			[
				'label' => esc_html__( 'Padding', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .wptd-video-popup-wrapper .popup-trigger-txt' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);
		$this->add_control(
			'popup_text_margin',
			[
				'label' => esc_html__( 'Margin', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .wptd-video-popup-wrapper .popup-trigger-txt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);
		$this->start_controls_tabs( 'popup_text_styles' );
			$this->start_controls_tab(
				'popup_text_normal',
				[
					'label' => esc_html__( 'Normal', 'wptd-video-popup' ),
				]
			);
			$this->add_control(
				'popup_textfont_color',
				[
					'label' => esc_html__( 'Font Color', 'wptd-video-popup' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .wptd-video-popup-wrapper .popup-trigger-txt' => 'color: {{VALUE}};'
					]
				]
			);
			$this->add_control(
				'popup_textbg_color',
				[
					'label' => esc_html__( 'Background Color', 'wptd-video-popup' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .wptd-video-popup-wrapper .popup-trigger-txt' => 'background-color: {{VALUE}};'
					]
				]
			);
			$this->end_controls_tab();
			$this->start_controls_tab(
				'popup_text_hover',
				[
					'label' => esc_html__( 'Hover', 'wptd-video-popup' ),
				]
			);
			$this->add_control(
				'popup_textfont_hcolor',
				[
					'label' => esc_html__( 'Font Color', 'wptd-video-popup' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .wptd-video-popup-wrapper:hover .popup-trigger-txt' => 'color: {{VALUE}};'
					]
				]
			);
			$this->add_control(
				'popup_textbg_hcolor',
				[
					'label' => esc_html__( 'Background Color', 'wptd-video-popup' ),
					'type' => \Elementor\Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .wptd-video-popup-wrapper:hover .popup-trigger-txt' => 'background-color: {{VALUE}};'
					]
				]
			);
			$this->end_controls_tab();	
		$this->end_controls_tabs();	
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Trigger Text Typo', 'wptd-video-popup' ),
				'name' 			=> 'popup_text_typography',
				'selector' 		=> '{{WRAPPER}} .wptd-video-popup-wrapper .popup-trigger-txt'
			]
		);	
		$this->end_controls_section();
		
		// Style Frame Section
		$this->start_controls_section(
			'section_style_frame',
			[
				'label' => esc_html__( 'Frame', 'wptd-video-popup' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'frame_bg_color',
			[
				'label' => esc_html__( 'Overlay Background Color', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => ''
			]
		);
		$this->add_control(
			'frame_width',
			[
				'label' => esc_html__( 'Frame Width', 'wptd-video-popup' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 1400,
						'step' => 1,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 900,
				]
			]
		);
		$this->end_controls_section();

	}
	
	/**
	 * Render Popup widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	 
	public function render_content() {
		/**
		 * Before widget render content.
		 *
		 * Fires before Elementor widget is being rendered.
		 *
		 * @since 1.0.0
		 *
		 * @param Widget_Base $this The current widget.
		 */
		do_action( 'elementor/widget/before_render_content', $this );
	
		ob_start();
	
		$skin = $this->get_current_skin();
		if ( $skin ) {
			$skin->set_parent( $this );
			$skin->render();
		} else {
			$this->render();
		}
	
		$widget_content = ob_get_clean();
		
		$settings = $this->get_settings_for_display();
		extract( $settings );
		?>
		
		<div class="elementor-widget-container wptd-video-popup-wrapper">
		
			<?php
			/**
			 * Render widget content.
			 *
			 * Filters the widget content before it's rendered.
			 *
			 * @since 1.0.0
			 *
			 * @param string      $widget_content The content of the widget.
			 * @param Widget_Base $this           The widget.
			 */
			$widget_content = apply_filters( 'elementor/widget/render_content', $widget_content, $this );
	
			echo $widget_content; // XSS ok.
			?>
			
		</div>
		<?php
	}

	/**
	 * Render Popup widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();
		extract( $settings );
		
		//Define Variables
		$popup_url = isset( $popup_url ) && !empty( $popup_url ) ? $popup_url : '';
		
		$frame_bg = isset( $frame_bg_color ) ? $frame_bg_color : ''; 
		$frame_width = isset( $frame_width['size'] ) ? $frame_width['size'] : 900;
		$shortcode_css = '';
		$rand_class = 'wptd-video-popup-'. $this->get_id();
		$shortcode_css .= $frame_bg ? '.' . esc_attr( $rand_class ) . ' .mfp-bg { background-color: '. esc_attr( $frame_bg ) .'; }' : '';
		$shortcode_css .= $frame_width ? '.' . esc_attr( $rand_class ) . ' .mfp-iframe-holder .mfp-content { max-width: '. absint( $frame_width ) .'px; }' : '';
		
		if( $shortcode_css ){ ?>
			<span class="wptd-video-popup-inline-css" data-css="<?php echo htmlspecialchars( json_encode( $shortcode_css ), ENT_QUOTES, 'UTF-8' ); ?>"></span>
		<?php
		}
				
		switch( $popup_type ){
			
			case "img":						
				//Image Section
				if ( ! empty( $settings['image']['url'] ) ) {
					$this->image_class = 'image_class';
					$this->add_render_attribute( 'image', 'src', $settings['image']['url'] );
					$this->add_render_attribute( 'image', 'alt', \Elementor\Control_Media::get_image_alt( $settings['image'] ) );
					$this->add_render_attribute( 'image', 'title', \Elementor\Control_Media::get_image_title( $settings['image'] ) );
					$this->add_render_attribute( 'image_class', 'class', 'img-fluid' );
					$this->add_render_attribute( 'image_class', 'class', $settings['img_style'] );
					if ( $settings['hover_animation'] ) {
						$this->add_render_attribute( 'image_class', 'class', 'elementor-animation-' . $settings['hover_animation'] );						
					}
					$counter_image = self::elementor_video_popup_get_attachment_image_html( $settings, 'thumbnail', 'image', $this );
					echo '<a href="'. esc_html( $popup_url ) .'" class="wptd-video-popup-trigger popup-trigger-img">' . $counter_image . '</a>';
				}														
			break;
			
			case "btn":
				$this->add_render_attribute( 'button-wrapper', 'class', 'wptd-video-popup-button-wrapper' );
				$this->add_render_attribute( 'button', 'class', 'elementor-button wptd-video-popup-button' );
				if ( ! empty( $settings['button_css_id'] ) ) {
					$this->add_render_attribute( 'button', 'id', $settings['button_css_id'] );
				}
				if ( ! empty( $settings['button_size'] ) ) {
					$this->add_render_attribute( 'button', 'class', 'elementor-size-' . $settings['button_size'] );
				}
				if ( $settings['button_hover_animation'] ) {
					$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['button_hover_animation'] );
				}
				$this->add_render_attribute( 'button', 'class', 'wptd-video-popup-trigger' );
				$this->add_render_attribute( 'button', 'href', esc_url( $popup_url ) );
				?>
				<div <?php echo $this->get_render_attribute_string( 'button-wrapper' ); ?>>
					<a <?php echo $this->get_render_attribute_string( 'button' ); ?>>
						<?php $this->button_render_text(); ?>
					</a>
				</div>
				<?php
			break;
			
			case "txt":
				$popup_text = isset( $popup_text ) && $popup_text != '' ? $popup_text : esc_html__( 'Popup', 'wptd-video-popup' );
				echo '<a class="wptd-video-popup-trigger popup-trigger-txt" href="'. esc_html( $popup_url ) .'">'. esc_html( $popup_text ) .'</a>';
			break;
			
			case "icon":						
				//Icon Section
				$this->add_render_attribute( 'icon-wrapper', 'class', 'wptd-video-popup-trigger' );
				$this->add_render_attribute( 'icon-wrapper', 'class', 'popup-trigger-icon' );
				$this->add_render_attribute( 'icon-wrapper', 'href', esc_url( $popup_url ) );
				if ( ! empty( $settings['icon_animation'] ) ) {
					$this->add_render_attribute( 'icon-wrapper', 'class', 'wptd-video-popup-elementor-animation' );
				}
				if ( empty( $settings['icon'] ) && ! \Elementor\Icons_Manager::is_migration_allowed() ) {
					// add old default
					$settings['icon'] = 'fa fa-heart';
				}
				if ( ! empty( $settings['icon'] ) ) {
					$this->add_render_attribute( 'icon', 'class', $settings['icon'] );
					$this->add_render_attribute( 'icon', 'aria-hidden', 'true' );
				}		
				$migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
				$is_new = empty( $settings['icon'] ) && \Elementor\Icons_Manager::is_migration_allowed();
				if( $settings['selected_icon'] ){
					echo '<a '. $this->get_render_attribute_string( 'icon-wrapper' ) .'>';
						if ( $is_new || $migrated ) :
							\Elementor\Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
						else : ?>
							<i <?php echo $this->get_render_attribute_string( 'icon' ); ?>></i>
						<?php endif; 
					echo '</a>';
				}
			break;
			
		}
		
	}
	
	/**
	 * Render button text.
	 *
	 * Render button widget text.
	 *
	 * @since 1.5.0
	 * @access protected
	 */
	protected function button_render_text() {
		$settings = $this->get_settings_for_display();

		$migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
		$is_new = empty( $settings['icon'] ) && \Elementor\Icons_Manager::is_migration_allowed();

		if ( ! $is_new && empty( $settings['icon_align'] ) ) {
			// @todo: remove when deprecated
			// added as bc in 2.6
			//old default
			$settings['icon_align'] = $this->get_settings( 'icon_align' );
		}

		$this->add_render_attribute( [
			'content-wrapper' => [
				'class' => 'wptd-video-popup-button-content-wrapper',
			],
			'icon-align' => [
				'class' => [
					'wptd-video-popup-button-icon',
					'wptd-video-popup-align-icon-' . $settings['button_icon_align'],
				],
			],
			'text' => [
				'class' => 'wptd-video-popup-button-text',
			],
		] );

		$this->add_inline_editing_attributes( 'text', 'none' );
		?>
		<span <?php echo $this->get_render_attribute_string( 'content-wrapper' ); ?>>
			<?php if ( ! empty( $settings['button_icon'] ) || ! empty( $settings['button_icon']['value'] ) ) : ?>
			<span <?php echo $this->get_render_attribute_string( 'icon-align' ); ?>>
				<?php if ( $is_new || $migrated ) :
					\Elementor\Icons_Manager::render_icon( $settings['button_icon'], [ 'aria-hidden' => 'true' ] );
				else : ?>
					<i class="<?php echo esc_attr( $settings['button_icon'] ); ?>" aria-hidden="true"></i>
				<?php endif; ?>
			</span>
			<?php endif; ?>
			<span <?php echo $this->get_render_attribute_string( 'text' ); ?>><?php echo $settings['button_text']; ?></span>
		</span>
		<?php
	}
	
	public static function elementor_video_popup_get_attachment_image_html( $settings, $image_size_key = 'image', $image_key = null, $cur_class = '' ) {
		if ( ! $image_key ) {
			$image_key = $image_size_key;
		}
		
		$image_class = $cur_class->image_class;
		
		$image = $settings[ $image_key ];
		// Old version of image settings.
		if ( ! isset( $settings[ $image_size_key . '_size' ] ) ) {
			$settings[ $image_size_key . '_size' ] = '';
		}
		$size = $settings[ $image_size_key . '_size' ];
		$html = '';
		// If is the new version - with image size.
		$image_sizes = get_intermediate_image_sizes();
		$image_sizes[] = 'full';
		if ( ! empty( $image['id'] ) && ! wp_attachment_is_image( $image['id'] ) ) {
			$image['id'] = '';
		}
		if( ! empty( $image['id'] ) && in_array( $size, $image_sizes ) ){
			$cur_class->add_render_attribute( 'image_class', 'class', "attachment-$size size-$size" );
			$img_attr = $cur_class->get_render_attributes( $image_class );
			$img_attr['class'] = implode( " ", $img_attr['class'] );
			$html .= wp_get_attachment_image( $image['id'], $size, false, $img_attr );
		}else{
			$image_src = \Elementor\Group_Control_Image_Size::get_attachment_image_src( $image['id'], $image_size_key, $settings );
			if ( ! $image_src && isset( $image['url'] ) ) {
				$image_src = $image['url'];
			}
			if ( ! empty( $image_src ) ) {
				$html .= sprintf( '<img src="%s" title="%s" alt="%s" %s />', esc_attr( $image_src ), \Elementor\Control_Media::get_image_title( $image ), \Elementor\Control_Media::get_image_alt( $image ), $cur_class->get_render_attribute_string( $image_class ) );
			}
		}
		return $html;
	}
	
}