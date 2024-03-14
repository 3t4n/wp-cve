<?php
/**
 * Modal Popup widget class
 *
 * @package Skt_Addons_Elementor
 */
namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Control_Media;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Icons_Manager;

defined( 'ABSPATH' ) || die();

class Modal_Popup extends Base {
	/**
	 * Get widget title.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Modal Popup', 'skt-addons-elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'skti skti-popup';
	}

	public function get_keywords() {
		return [ 'modal', 'popup' ];
	}

	/**
     * Register widget content controls
     */
	protected function register_content_controls() {
		$this->__trigger_content_controls();
		$this->__modal_popup_content_controls();
		$this->__settings_content_controls();
	}

	protected function __trigger_content_controls() {

		$this->start_controls_section(
			'_section_modal_popup_trigger',
			[
				'label' => __( 'Trigger', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'trigger_type',
			[
				'label' => __( 'Trigger', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'button' => __( 'Button', 'skt-addons-elementor' ),
					'image' => __( 'Image', 'skt-addons-elementor' ),
					'pageload' => __( 'On Page Load', 'skt-addons-elementor' ),
				],
				'default' => 'button',
			]
		);

		$this->add_control(
			'image',
			[
				'label' => __( 'Image', 'skt-addons-elementor' ),
				'show_label' => false,
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'trigger_type' => 'image'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => '_image',
				'default' => 'thumbnail',
				'separator' => 'none',
				'condition' => [
					'trigger_type' => 'image'
				],
			]
		);

		$this->add_control(
			'button',
			[
				'label' => __( 'Button', 'skt-addons-elementor' ),
				'label_block' => true,
				'show_label' => false,
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Button Text', 'skt-addons-elementor' ),
				'default' => __( 'SKT Addons', 'skt-addons-elementor' ),
				'condition' => [
					'trigger_type' => 'button',
				],
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$this->add_control(
			'modal_box_popup_delay',
			[
				'label'       => __( 'Display Delay', 'skt-addons-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => __( 'Display Popup Box Delay time in seconds. Default 3 seconds', 'skt-addons-elementor' ),
				'default'     => 3,
				'step'        => 0.1,
				'min'         => 0,
				'condition'   => [
					'trigger_type' => 'pageload',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __modal_popup_content_controls() {

		$this->start_controls_section(
			'_section_modal_popup',
			[
				'label' => __( 'Modal Popup', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'popup_image_show',
			[
				'label' => __( 'Show Image', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'skt-addons-elementor' ),
				'label_off' => __( 'No', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_control(
			'modal_image',
			[
				'label' => __( 'Image', 'skt-addons-elementor' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'popup_image_show' => 'yes'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail',
				'default' => 'medium',
				'separator' => 'none',
				'condition' => [
					'popup_image_show' => 'yes'
				],
			]
		);

		$this->add_control(
			'modal_title',
			[
				'label' => __( 'Title', 'skt-addons-elementor' ),
				'label_block' => true,
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Title', 'skt-addons-elementor' ),
				'default' => __( 'SKT Elementor Addons', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$this->add_control(
			'modal_description',
			[
				'label' => __( 'Description', 'skt-addons-elementor' ),
				'label_block' => true,
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => __( 'Description', 'skt-addons-elementor' ),
				'default' => __( 'SKT Addons is the best Elementor Addons Plugin.', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$this->add_control(
			'close_icon',
			[
				'label' => __( 'Close Icon', 'skt-addons-elementor' ),
				'type' => Controls_Manager::ICONS,
				'label_block' => false,
				'skin' => 'inline',
				'default' => [
					'value' => 'skti skti-cross',
					'library' => 'skt-icons',
				]
			]
		);

		$this->add_control(
			'popup_shortcode_show',
			[
				'label' => __( 'Show ShortCode', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'skt-addons-elementor' ),
				'label_off' => __( 'No', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_control(
			'modal_shortcode',
			[
				'label' => __( 'ShortCode', 'skt-addons-elementor' ),
				'label_block' => true,
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => __( 'ShortCode', 'skt-addons-elementor' ),
				'condition' => [
					'popup_shortcode_show' => 'yes'
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __settings_content_controls() {

		$this->start_controls_section(
			'_section_modal_popup_settings',
			[
				'label' => __( 'Settings', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'popup_preview',
			[
				'label' => __( 'Popup Preview', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'skt-addons-elementor' ),
				'label_off' => __( 'No', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);

		$this->add_control(
			'popup_full_screen',
			[
				'label' => __( 'Full Screen Popup', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'skt-addons-elementor' ),
				'label_off' => __( 'No', 'skt-addons-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'popup_animation',
			[
				'label' => __( 'Popup Animation', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'animate__fadeIn' => __( 'Fade In', 'skt-addons-elementor' ),
					'animate__fadeInDown' => __( 'Fade In Down', 'skt-addons-elementor' ),
					'animate__fadeInUp' => __( 'Fade In Up', 'skt-addons-elementor' ),
					'animate__fadeInLeft' => __( 'Fade In Left', 'skt-addons-elementor' ),
					'animate__fadeInRight' => __( 'Fade In Right', 'skt-addons-elementor' ),
					'animate__slideInUp' => __( 'Slide In Up', 'skt-addons-elementor' ),
					'animate__slideInDown' => __( 'Slide In Down', 'skt-addons-elementor' ),
					'animate__slideInLeft' => __( 'Slide In Left', 'skt-addons-elementor' ),
					'animate__slideInRight' => __( 'Slide In Right', 'skt-addons-elementor' ),
					'animate__zoomIn' => __( 'Zoom In', 'skt-addons-elementor' ),
					'animate__zoomInUp' => __( 'Zoom In Up', 'skt-addons-elementor' ),
					'animate__zoomInDown' => __( 'Zoom In Down', 'skt-addons-elementor' ),
					'animate__zoomInLeft' => __( 'Zoom In Left', 'skt-addons-elementor' ),
					'animate__zoomInRight' => __( 'Zoom In Right', 'skt-addons-elementor' ),
				],
				'default' => 'animate__zoomIn',
			]
		);

		$this->add_control(
			'popup_position',
			[
				'label' => __( 'Popup Position', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'center' => __( 'Center', 'skt-addons-elementor' ),
					'top-left' => __( 'Top Left', 'skt-addons-elementor' ),
					'top-right' => __( 'Top Right', 'skt-addons-elementor' ),
					'bottom-left' => __( 'Bottom Left', 'skt-addons-elementor' ),
					'bottom-right' => __( 'Bottom Right', 'skt-addons-elementor' )
				],
				'default' => 'center',
				'condition' => [
					'popup_full_screen!' => 'yes'
				],
			]
		);

		$this->add_control(
			'close_position',
			[
				'label' => __( 'Close Icon Position', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'top-left' => __( 'Top Left', 'skt-addons-elementor' ),
					'top-right' => __( 'Top Right', 'skt-addons-elementor' ),
					'bottom-left' => __( 'Bottom Left', 'skt-addons-elementor' ),
					'bottom-right' => __( 'Bottom Right', 'skt-addons-elementor' )
				],
				'default' => 'top-right'
			]
		);

		$this->add_control(
			'saved_content',
			[
				'label' => __( 'Saved Content', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_saved_content_list()
			]
		);

		$this->add_control(
			'global_widget_list',
			[
				'label' => __( 'Global Widget', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_saved_content( 'widget' ),
				'default' => 'select',
				'condition' => [
					'saved_content' => 'global_widget'
				],
			]
		);

		$this->add_control(
			'saved_template_list',
			[
				'label' => __( 'Saved Template', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_saved_content( 'section' ),
				'default' => 'select',
				'condition' => [
					'saved_content' => 'saved_template'
				],
			]
		);

		$this->add_control(
			'text_align',
			[
				'label' => __( 'Button Alignment', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-right',
					]
				],
				'selectors' => [
					'{{WRAPPER}} .skt-modal-button-wrapper' => 'text-align: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'modal_text_align',
			[
				'label' => __( 'Modal Text Alignment', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-right',
					]
				],
				'selectors' => [
					'{{WRAPPER}} .skt-modal-inner-content' => 'text-align: {{VALUE}};'
				]
			]
		);

		$this->end_controls_section();
	}

	/**
     * Register widget style controls
     */
	protected function register_style_controls() {
		$this->__trigger_btn_style_controls();
		$this->__trigger_image_style_controls();
		$this->__popup_style_controls();
	}

	protected function __trigger_btn_style_controls() {

		$this->start_controls_section(
			'_section_button_style',
			[
				'label' => __( 'Trigger Button', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'trigger_type' => 'button'
				],
			]
		);

		$this->add_control(
			'button_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-modal-popup-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .skt-modal-popup-btn',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-modal-popup-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .skt-modal-popup-btn',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-modal-popup-btn',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
			]
		);

		$this->start_controls_tabs( '_tabs_button' );
		$this->start_controls_tab(
			'_tab_button_normal',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' )
			]
		);

		$this->add_control(
			'button_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-modal-popup-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_background_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-modal-popup-btn' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'_tab_button_hover',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'button_hover_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-modal-popup-btn:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_hover_background_color',
			[
				'label' => __( 'Background Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-modal-popup-btn:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
            'button_hover_border',
            [
                'label' => __( 'Border Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                     'button_border_border!' => ''
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-modal-popup-btn:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function __trigger_image_style_controls() {

		$this->start_controls_section(
			'_section_trigger_image_style',
			[
				'label' => __( 'Trigger Image', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'trigger_type' => 'image'
				],
			]
		);

		$this->add_responsive_control(
			'trigger_image_width',
			[
				'label' => __( 'Width', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px','%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-modal-popup-image' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->add_responsive_control(
			'trigger_image_height',
			[
				'label' => __( 'Height', 'skt-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px','%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-modal-popup-image' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'trigger_image_shadow',
				'label' => __( 'Box Shadow', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-modal-popup-image',
			]
		);


		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'trigger_image_border',
				'label' => __( 'Border', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-modal-popup-image',
			]
		);



		$this->add_responsive_control(
			'trigger_image_border_radius',
			[
				'label' => __( 'Border Radius', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .skt-modal-popup-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->start_controls_tabs( 'trigger_image_tabs');
		$this->start_controls_tab(
			'trigger_image_normal_tab',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' ),
			]
		);

		$this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'trigger_image_css_filters',
                'selector' => '{{WRAPPER}} .skt-modal-popup-image',
            ]
        );

		$this->end_controls_tab();

		$this->start_controls_tab(
			'trigger_image_hover_tab',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
                'name' => 'trigger_image_hover_css_filters',
                'selector' => '{{WRAPPER}} .skt-modal-popup-image:hover',
            ]
        );

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function __popup_style_controls() {

		$this->start_controls_section(
			'_section_popup_style',
			[
				'label' => __( 'Popup', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
            'popup_width',
            [
                'label' => __( 'Width', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vw' ],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 2000,
                    ]
				],
				'default' => [
                    'unit' => 'vw',
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-modal-animation' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
		);

		$this->add_responsive_control(
            'popup_height',
            [
                'label' => __( 'Height', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh' ],
                'range' => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                    ]
				],
				'default' => [
                    'unit' => 'vh',
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-modal-animation' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

		$this->add_control(
            'modal_content',
            [
                'label' => __( 'Content', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
            ]
		);

		$this->add_control(
            'content_padding',
            [
                'label' => __( 'Padding', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-modal-animation' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
		);

		$this->add_control(
            'content_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-modal-animation' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
		);

		$this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'modal_content_box_shadow',
				'selector' => '{{WRAPPER}} .skt-modal-animation',
				'condition' => [
					'popup_full_screen!' => 'yes'
				]
            ]
		);

		$this->add_control(
            'overlay_color',
            [
                'label' => __( 'Overlay Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'popup_full_screen' => 'yes'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-modal-popup-overlay' => 'background-color: {{VALUE}}',
                ],
            ]
		);

		$this->add_control(
            'background_color',
            [
                'label' => __( 'Background Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-modal-animation' => 'background-color: {{VALUE}}',
                ],
            ]
		);

		$this->add_control(
            'modal_image_heading',
            [
                'label' => __( 'Image', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
            ]
		);

		$this->add_responsive_control(
            'modal_image_spacing',
            [
                'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-modal-content__image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
		);

		$this->add_responsive_control(
            'image_width',
            [
                'label' => __( 'Width', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 2000,
                    ]
				],
                'selectors' => [
                    '{{WRAPPER}} .skt-modal-content__image img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
		);

		$this->add_responsive_control(
            'image_height',
            [
                'label' => __( 'Height', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 1000,
                    ]
				],
                'selectors' => [
                    '{{WRAPPER}} .skt-modal-content__image img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

		$this->add_control(
            'image_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-modal-content__image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
		);

		$this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_box_shadow',
				'selector' => '{{WRAPPER}} .skt-modal-content__image img',
				'condition' => [
					'popup_image_show' => 'yes'
				]
            ]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'selector' => '{{WRAPPER}} .skt-modal-content__image img',
			]
		);

		$this->add_control(
            'modal_title_heading',
            [
                'label' => __( 'Title', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
            ]
		);

		$this->add_responsive_control(
            'modal_title_spacing',
            [
                'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-modal-content__title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'modal_title_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-modal-content__title',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
			],
			]
		);

		$this->add_control(
            'modal_title_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-modal-content__title' => 'color: {{VALUE}}',
                ],
            ]
		);

		$this->add_control(
            'modal_description_heading',
            [
                'label' => __( 'Description', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
            ]
		);

		$this->add_responsive_control(
            'modal_description_spacing',
            [
                'label' => __( 'Bottom Spacing', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-modal-content__description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'modal_description_typography',
				'label' => __( 'Typography', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-modal-content__description',
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
			]
		);

		$this->add_control(
            'modal_description_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-modal-content__description' => 'color: {{VALUE}}',
                ],
            ]
		);

		$this->add_control(
            'modal_close',
            [
                'label' => __( 'Close Button', 'skt-addons-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
		);

		$this->add_responsive_control(
            'close_icon_size',
            [
                'label' => __( 'Size', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 2,
                        'max' => 200,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-modal-popup-content-close' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
		);

		$this->start_controls_tabs( '_tabs_close_button' );
		$this->start_controls_tab(
			'_tab_close_button_normal',
			[
				'label' => __( 'Normal', 'skt-addons-elementor' )
			]
		);

		$this->add_control(
			'close_button_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-modal-popup-content-close' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'_tab_close_button_hover',
			[
				'label' => __( 'Hover', 'skt-addons-elementor' ),
			]
		);

		$this->add_control(
			'close_button_hover_color',
			[
				'label' => __( 'Color', 'skt-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-modal-popup-content-close:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function get_saved_content_list() {
		$content_list = [
			''   => __( 'Select Saved Content', 'skt-addons-elementor' ),
			'saved_template' => __( 'Template', 'skt-addons-elementor' ),
		];

		if ( defined( 'ELEMENTOR_PRO_VERSION' ) ) {
			$content_list['global_widget'] = __( 'Global Widget', 'skt-addons-elementor' );
		}
		return $content_list;
	}

	protected function get_post_template( $term = 'page' ) {
		$posts = get_posts(
			[
				'post_type'      => 'elementor_library',
				'orderby'        => 'title',
				'order'          => 'ASC',
				'posts_per_page' => '-1',
				'tax_query'      => [
					[
						'taxonomy' => 'elementor_library_type',
						'field'    => 'slug',
						'terms'    => $term,
					],
				],
			]
		);

		$templates = [];
		foreach ( $posts as $post ) {
			$templates[] = [
				'id'   => $post->ID,
				'name' => $post->post_title,
			];
		}
		return $templates;
	}

	protected function get_saved_content( $term = 'section' ) {
		$saved_contents = $this->get_post_template( $term );

		if ( count( $saved_contents ) > 0 ) {
			foreach ( $saved_contents as $saved_content ) {
				$content_id             = $saved_content['id'];
				$options[ $content_id ] = $saved_content['name'];
			}
		} else {
			$options['no_template'] = __( 'Nothing Found', 'skt-addons-elementor' );
		}
		return $options;
	}

	protected function modal_popup_content($id) {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute(
			'modal-content',
			[
				'class' => 'skt-modal-popup-content-wrapper',
				'data-id' => $id,
				'data-animation' => $settings['popup_animation']
			]
		);

		if( 'pageload' == $settings['trigger_type'] ){
			$delay = $settings['modal_box_popup_delay'];
			$delay = $delay ? ( $delay * 1000 ) : 0;

			$this->add_render_attribute( 'modal-content', 'data-display-delay', $delay );
		}

		if ( $settings['popup_preview'] == 'yes' && is_admin() ) {
			$this->add_render_attribute( 'modal-content', 'class', 'skt-modal-show' );
		}
		if ( $settings['popup_full_screen'] == 'yes' ) {
			$this->add_render_attribute( 'modal-content', 'class', 'skt-modal-fullscreen' );
		} else {
			$this->add_render_attribute( 'modal-content', 'class', 'skt-modal-box' );
		}

		if ( $settings['popup_full_screen'] != 'yes' ) {
			if ($settings['popup_position'] == 'center') {
				$this->add_render_attribute('modal-content', 'class', 'skt-modal-position-center');
			} elseif ($settings['popup_position'] == 'top-left') {
				$this->add_render_attribute('modal-content', 'class', 'skt-modal-position-top-left');
			} elseif ($settings['popup_position'] == 'top-right') {
				$this->add_render_attribute('modal-content', 'class', 'skt-modal-position-top-right');
			} elseif ($settings['popup_position'] == 'bottom-left') {
				$this->add_render_attribute('modal-content', 'class', 'skt-modal-position-bottom-left');
			} elseif ($settings['popup_position'] == 'bottom-right') {
				$this->add_render_attribute('modal-content', 'class', 'skt-modal-position-bottom-right');
			}
		}

		$this->add_render_attribute( 'close-icon', 'class', 'skt-modal-popup-content-close' );
		if ( $settings['close_position'] == 'top-left' ) {
			$this->add_render_attribute( 'close-icon', 'class', 'skt-modal-close-top-left' );
		} elseif ( $settings['close_position'] == 'top-right' ) {
			$this->add_render_attribute( 'close-icon', 'class', 'skt-modal-close-top-right' );
		} elseif ( $settings['close_position'] == 'bottom-left' ) {
			$this->add_render_attribute( 'close-icon', 'class', 'skt-modal-close-bottom-left' );
		} elseif ( $settings['close_position'] == 'bottom-right' ) {
			$this->add_render_attribute( 'close-icon', 'class', 'skt-modal-close-bottom-right' );
		}
		?>
		<div <?php $this->print_render_attribute_string( 'modal-content' ); ?>>

			<?php if ( $settings['close_icon']['value'] ) : ?>
				<div <?php $this->print_render_attribute_string( 'close-icon' ); ?>>
					<?php Icons_Manager::render_icon( $settings['close_icon'], [ 'aria-hidden' => 'true' ] ); ?>
				</div>
			<?php endif; ?>

			<?php if ( $settings['popup_full_screen'] == 'yes' ) : ?>
				<div class="skt-modal-popup-overlay"></div>
			<?php endif; ?>

			<div class="skt-modal-animation animate__animated">
				<div class="skt-modal-inner">
					<div class="skt-modal-inner-content">
						<?php if ( isset($settings['modal_image']) && !empty( $settings['modal_image']['url'] || $settings['modal_image']['id'] ) ) :
							$this->add_render_attribute( 'modal_image', 'src', $settings['modal_image']['url'] );
							$this->add_render_attribute( 'modal_image', 'alt', Control_Media::get_image_alt( $settings['modal_image'] ) );
							$this->add_render_attribute( 'modal_image', 'title', Control_Media::get_image_title( $settings['modal_image'] ) );
							?>
							<div class="skt-modal-content__image">
								<?php echo wp_kses_post(Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'modal_image' )); ?>
							</div>
						<?php endif; ?>

						<?php if ( !empty( $settings['modal_title'] ) ) : ?>
							<div class="skt-modal-content__title">
								<?php echo esc_html($settings['modal_title']); ?>
							</div>
						<?php endif; ?>

						<?php if ( !empty( $settings['modal_description'] ) ) : ?>
							<div class="skt-modal-content__description">
								<?php echo wp_kses_post($settings['modal_description']); ?>
							</div>
						<?php endif; ?>
					</div>

					<?php if ( $settings['popup_shortcode_show'] == 'yes' && !empty( $settings['modal_shortcode'] ) ) : ?>
						<div class="skt-modal-content__shortcode">
							<?php echo wp_kses_post(do_shortcode( $settings['modal_shortcode'] )); ?>
						</div>
					<?php endif; ?>

					<?php
					if ( isset( $settings['saved_template_list'] ) ) :
						echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $settings['saved_template_list'] );
					elseif ( isset( $settings['global_widget_list'] ) ) :
						echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $settings['global_widget_list'] );
					endif;
					?>
				</div>
			</div>
		</div>
	<?php
	}

	protected function trigger_type_render() {
		$settings = $this->get_settings_for_display();
		$widget_id = $this->get_id();

		if( 'image' == $settings['trigger_type'] ){
			$this->add_render_attribute(
				'modal-button',
				[
					'class' => 'skt-modal-popup-trigger  skt-modal-popup-image',
					'data-id' => $widget_id,
					'src' => Group_Control_Image_Size::get_attachment_image_src( $settings['image']['id'], '_image', $settings ) ? esc_url(Group_Control_Image_Size::get_attachment_image_src( $settings['image']['id'], '_image', $settings )) : esc_url($settings['image']['url']),
					'title' => esc_attr(Control_Media::get_image_title( $settings['image'] )),
					'alt' => esc_attr(Control_Media::get_image_alt( $settings['image'] ))
				]
			);
		} elseif( 'button' == $settings['trigger_type'] ) {
			$this->add_render_attribute(
				'modal-button',
				[
					'class' => 'skt-modal-popup-trigger skt-modal-popup-btn',
					'data-id' => $widget_id,
					'href' => '#'
				]
			);
		}

		$html = '';
		if( 'button' == $settings['trigger_type'] ){
			$html = sprintf('<a %s>%s</a>',
					$this->get_render_attribute_string( 'modal-button' ),
					esc_html( $settings['button'] )
				);
		} elseif( 'image' == $settings['trigger_type'] ) {
			$html = sprintf( '<img %s />',
				$this->get_render_attribute_string( 'modal-button' )
			);
		}

		return $html;
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$widget_id = $this->get_id();

		$this->add_render_attribute(
			'box-wrapper',
			[
				'class' => 'skt-modal-popup-box-wrapper',
				'data-trigger-type' => $settings['trigger_type']
			]
		);

		?>
		<div <?php $this->print_render_attribute_string( 'box-wrapper' );?>>
			<?php if( 'pageload' != $settings['trigger_type'] ):?>
				<div class="skt-modal-button-wrapper">
					<?php echo wp_kses_post($this->trigger_type_render());?>
				</div>
			<?php
				elseif ( skt_addons_elementor()->editor->is_edit_mode() ):
					printf( '<p>%s<p>', esc_html__( 'Modal Popup:- You set "On Page Load" for Trigger. This is just a placeholder &amp; will not be shown on the live page.', 'skt-addons-elementor' ) );
				endif;
			?>

		<?php $this->modal_popup_content( $widget_id );?>
		</div>
		<?php
	}
}