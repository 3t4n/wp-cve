<?php

namespace XproElementorAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Xpro Elementor Addons
 *
 * Elementor widget.
 *
 * @since 1.0.0
 */
class Social_Icon extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve image widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'xpro-social-icon';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve image widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Social Icon', 'xpro-elementor-addons' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve image widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'xi-social xpro-widget-label';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the image widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @return array Widget categories.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_categories() {
		return array( 'xpro-widgets' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @return array Widget keywords.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_keywords() {
		return array( 'social', 'socials', 'icon', 'icons' );
	}

	/**
	 * Retrieve the list of style the widget depended on.
	 *
	 * Used to set style dependencies required to run the widget.
	 *
	 * @return array Widget style dependencies.
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */
	public function get_style_depends() {
		return array( 'hover' );
	}

	/**
	 * Register widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'section_social_icon',
			array(
				'label' => __( 'General', 'xpro-elementor-addons' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'icon',
			array(
				'show_label'  => false,
				'type'        => Controls_Manager::ICONS,
				'label_block' => true,
				'default'     => array(
					'value'   => 'fab fa-wordpress',
					'library' => 'fa-solid',
				),
				'recommended' => array(
					'fa-brands' => array(
						'android',
						'apple',
						'behance',
						'bitbucket',
						'codepen',
						'delicious',
						'deviantart',
						'digg',
						'dribbble',
						'facebook-f',
						'facebook',
						'flickr',
						'foursquare',
						'free-code-camp',
						'github',
						'gitlab',
						'globe',
						'google-plus',
						'houzz',
						'instagram',
						'jsfiddle',
						'linkedin',
						'medium',
						'meetup',
						'mixcloud',
						'odnoklassniki',
						'pinterest',
						'product-hunt',
						'reddit',
						'shopping-cart',
						'skype',
						'slideshare',
						'snapchat',
						'soundcloud',
						'spotify',
						'stack-overflow',
						'steam',
						'stumbleupon',
						'telegram',
						'thumb-tack',
						'tripadvisor',
						'tumblr',
						'twitch',
						'twitter',
						'viber',
						'vimeo',
						'vk',
						'weibo',
						'weixin',
						'whatsapp',
						'wordpress',
						'xing',
						'yelp',
						'youtube',
						'500px',
					),
				),
			)
		);

		$repeater->add_control(
			'social_enable_text',
			array(
				'label'        => __( 'Enable Title', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'    => __( 'Hide', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
			)
		);

		$repeater->add_control(
			'title',
			array(
				'label'       => __( 'Title', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Title Here', 'xpro-elementor-addons' ),
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					'social_enable_text' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'link',
			array(
				'label'       => __( 'Link', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => array(
					'active' => true,
				),
				'placeholder' => __( 'https://your-link.com', 'xpro-elementor-addons' ),
			)
		);

		$repeater->add_control(
			'social_inline_style',
			array(
				'label'        => __( 'Inline Style', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'    => __( 'Hide', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
			)
		);

		$repeater->start_controls_tabs(
			'tabs_item',
			array(
				'separator' => 'before',
				'condition' => array(
					'social_inline_style' => 'yes',
				),
			)
		);

		$repeater->start_controls_tab(
			'social_normal',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$repeater->add_control(
			'social_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-social-icon-wrapper {{CURRENT_ITEM}} .xpro-social-icon > i,
                     {{WRAPPER}} .xpro-social-icon-wrapper {{CURRENT_ITEM}} .xpro-social-icon .xpro-social-icon-title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .xpro-social-icon-wrapper {{CURRENT_ITEM}} .xpro-social-icon > svg' => 'fill: {{VALUE}};',
				),
				'condition' => array(
					'social_inline_style' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'social_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-social-icon-wrapper {{CURRENT_ITEM}} .xpro-social-icon' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'social_inline_style' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'social_border_color',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-social-icon-wrapper {{CURRENT_ITEM}} .xpro-social-icon ' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'social_inline_style' => 'yes',
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'social_list_active',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$repeater->add_control(
			'social_hover_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-social-icon-wrapper {{CURRENT_ITEM}} .xpro-social-icon:hover > i,
                     {{WRAPPER}} .xpro-social-icon-wrapper {{CURRENT_ITEM}} .xpro-social-icon:hover .xpro-social-icon-title ' => 'color: {{VALUE}};',
					'{{WRAPPER}} .xpro-social-icon-wrapper {{CURRENT_ITEM}} .xpro-social-icon:hover > svg' => 'fill: {{VALUE}};',
				),
				'condition' => array(
					'social_inline_style' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'social_hover_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-social-icon-wrapper {{CURRENT_ITEM}} .xpro-social-icon:hover' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'social_inline_style' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'social_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-social-icon-wrapper {{CURRENT_ITEM}} .xpro-social-icon:hover ' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'social_inline_style' => 'yes',
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'item',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'show_label'  => false,
				'title_field' => '<# print(elementor.helpers.getSocialNetworkNameFromIcon( icon ) || title); #>',
				'default'     => array(
					array(
						'icon'  => array(
							'value'   => 'fab fa-facebook-f',
							'library' => 'fa-solid',
						),
						'title' => __( 'Facebook', 'xpro-elementor-addons' ),
					),
					array(
						'icon'  => array(
							'value'   => 'fab fa-linkedin',
							'library' => 'fa-solid',
						),
						'title' => __( 'Linkedin', 'xpro-elementor-addons' ),
					),
					array(
						'icon'  => array(
							'value'   => 'fab fa-twitter',
							'library' => 'fa-solid',
						),
						'title' => __( 'Twitter', 'xpro-elementor-addons' ),
					),
				),
			)
		);

		$this->add_responsive_control(
			'social_align_horizontal',
			array(
				'label'     => __( 'Alignment', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'separator' => 'before',
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'toggle'    => false,
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-container' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'social_icon_column_grid',
			array(
				'label'     => __( 'Columns', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '3',
				'options'   => array(
					'1' => __( '1', 'xpro-elementor-addons' ),
					'2' => __( '2', 'xpro-elementor-addons' ),
					'3' => __( '3', 'xpro-elementor-addons' ),
					'4' => __( '4', 'xpro-elementor-addons' ),
					'5' => __( '5', 'xpro-elementor-addons' ),
					'6' => __( '6', 'xpro-elementor-addons' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-social-icon-wrapper' => 'grid-template-columns:repeat({{VALUE}}, 1fr);',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_social_icon_general_style',
			array(
				'label' => __( 'General', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'social_icon_item_space_vertical',
			array(
				'label'      => __( 'Column Gap', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => 15,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-social-icon-wrapper' => 'grid-column-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'social_item_space_between',
			array(
				'label'      => __( 'Row Gap', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => 15,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-social-icon-wrapper' => 'grid-row-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( '_social_icon' );

		$this->start_controls_tab(
			'social_icon_normal',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'social_icon_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-social-icon > i,
                     {{WRAPPER}} .xpro-social-icon .xpro-social-icon-title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .xpro-social-icon > svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'social_icon_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-social-icon,
                     {{WRAPPER}} .xpro-button-bg-animation:before' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'social_icon_list_active',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'social_icon_hover_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-social-icon:hover > i, {{WRAPPER}} .xpro-social-icon:hover .xpro-social-icon-title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .xpro-social-icon:hover > svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'social_icon_hover_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-button-animation-none:hover,
                    {{WRAPPER}} .xpro-social-icon-wrapper > li .xpro-social-icon:hover,
                    {{WRAPPER}} .xpro-button-2d-animation:hover,
                    {{WRAPPER}} .xpro-social-icon.xpro-unique-reverse-shape:hover,
                    {{WRAPPER}} .xpro-social-icon.xpro-unique-triangle-shape:hover,
                    {{WRAPPER}} .xpro-social-icon.xpro-unique-slide-shape:hover,
                    {{WRAPPER}} .xpro-social-icon.xpro-button-bg-animation:hover:before' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'social_icon_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-social-icon:hover ' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'social_icon_hover_animation',
			array(
				'label'   => __( 'Hover Animation', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => array(
					'none'                  => __( 'None', 'xpro-elementor-addons' ),
					'2d-transition'         => __( '2D', 'xpro-elementor-addons' ),
					'background-transition' => __( 'Background', 'xpro-elementor-addons' ),
					'hover-effect'          => __( 'Unique', 'xpro-elementor-addons' ),
				),
			)
		);

		$this->add_control(
			'social_icon_hover_2d_css_animation',
			array(
				'label'     => __( 'Animation', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'hvr-grow',
				'options'   => array(
					'hvr-grow'                   => __( 'Grow', 'xpro-elementor-addons' ),
					'hvr-shrink'                 => __( 'Shrink', 'xpro-elementor-addons' ),
					'hvr-pulse'                  => __( 'Pulse', 'xpro-elementor-addons' ),
					'hvr-pulse-grow'             => __( 'Pulse Grow', 'xpro-elementor-addons' ),
					'hvr-pulse-shrink'           => __( 'Pulse Shrink', 'xpro-elementor-addons' ),
					'hvr-push'                   => __( 'Push', 'xpro-elementor-addons' ),
					'hvr-pop'                    => __( 'Pop', 'xpro-elementor-addons' ),
					'hvr-bounce-in'              => __( 'Bounce In', 'xpro-elementor-addons' ),
					'hvr-bounce-out'             => __( 'Bounce Out', 'xpro-elementor-addons' ),
					'hvr-rotate'                 => __( 'Rotate', 'xpro-elementor-addons' ),
					'hvr-grow-rotate'            => __( 'Grow Rotate', 'xpro-elementor-addons' ),
					'hvr-float'                  => __( 'Float', 'xpro-elementor-addons' ),
					'hvr-sink'                   => __( 'Sink', 'xpro-elementor-addons' ),
					'hvr-bob'                    => __( 'Bob', 'xpro-elementor-addons' ),
					'hvr-hang'                   => __( 'Hang', 'xpro-elementor-addons' ),
					'hvr-wobble-vertical'        => __( 'Wobble Vertical', 'xpro-elementor-addons' ),
					'hvr-wobble-horizontal'      => __( 'Wobble Horizontal', 'xpro-elementor-addons' ),
					'hvr-wobble-to-bottom-right' => __( 'Wobble To Bottom Right', 'xpro-elementor-addons' ),
					'hvr-wobble-to-top-right'    => __( 'Wobble To Top Right', 'xpro-elementor-addons' ),
					'hvr-buzz'                   => __( 'Buzz', 'xpro-elementor-addons' ),
					'hvr-buzz-out'               => __( 'Buzz Out', 'xpro-elementor-addons' ),
				),
				'condition' => array(
					'social_icon_hover_animation' => '2d-transition',
				),
			)
		);

		$this->add_control(
			'social_icon_hover_background_css_animation',
			array(
				'label'     => __( 'Animation', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'hvr-fade',
				'options'   => array(
					'hvr-fade'                   => __( 'Fade', 'xpro-elementor-addons' ),
					'hvr-back-pulse'             => __( 'Back Pulse', 'xpro-elementor-addons' ),
					'hvr-sweep-to-right'         => __( 'Sweep To Right', 'xpro-elementor-addons' ),
					'hvr-sweep-to-left'          => __( 'Sweep To Left', 'xpro-elementor-addons' ),
					'hvr-sweep-to-bottom'        => __( 'Sweep To Bottom', 'xpro-elementor-addons' ),
					'hvr-sweep-to-top'           => __( 'Sweep To Top', 'xpro-elementor-addons' ),
					'hvr-bounce-to-right'        => __( 'Bounce To Right', 'xpro-elementor-addons' ),
					'hvr-bounce-to-left'         => __( 'Bounce To Left', 'xpro-elementor-addons' ),
					'hvr-bounce-to-bottom'       => __( 'Bounce To Bottom', 'xpro-elementor-addons' ),
					'hvr-bounce-to-top'          => __( 'Bounce To Top', 'xpro-elementor-addons' ),
					'hvr-radial-out'             => __( 'Radial Out', 'xpro-elementor-addons' ),
					'hvr-radial-in'              => __( 'Radial In', 'xpro-elementor-addons' ),
					'hvr-rectangle-in'           => __( 'Rectangle In', 'xpro-elementor-addons' ),
					'hvr-rectangle-out'          => __( 'Rectangle Out', 'xpro-elementor-addons' ),
					'hvr-shutter-in-horizontal'  => __( 'Shutter In Horizontal', 'xpro-elementor-addons' ),
					'hvr-shutter-out-horizontal' => __( 'Shutter Out Horizontal', 'xpro-elementor-addons' ),
					'hvr-shutter-in-vertical'    => __( 'Shutter In Vertical', 'xpro-elementor-addons' ),
					'hvr-shutter-out-vertical'   => __( 'Shutter Out Vertical', 'xpro-elementor-addons' ),
				),
				'condition' => array(
					'social_icon_hover_animation' => 'background-transition',
				),
			)
		);

		$this->add_control(
			'social_icon_hover_effect_animation',
			array(
				'label'     => __( 'Animation', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'slide-shape',
				'options'   => array(
					'slide-shape'    => __( 'Slide', 'xpro-elementor-addons' ),
					'triangle-shape' => __( 'Triangle', 'xpro-elementor-addons' ),
					'reverse-shape'  => __( 'Reverse', 'xpro-elementor-addons' ),
				),
				'condition' => array(
					'social_icon_hover_animation' => 'hover-effect',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'social_icon_border',
				'separator' => 'before',
				'label'     => __( 'Border', 'xpro-elementor-addons' ),
				'selector'  => '{{WRAPPER}} .xpro-social-icon',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'social_icon_box_shadow',
				'label'    => __( 'Box Shadow', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-social-icon',
			)
		);

		$this->add_control(
			'social_icon_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-social-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'social_icon_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-social-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_social_icon_style',
			array(
				'label' => __( 'Icon', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => __( 'Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => 30,
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-social-icon > i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-social-icon > svg' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_bg_size',
			array(
				'label'      => __( 'Background Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => 60,
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-social-icon' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_social_icon_title_style',
			array(
				'label' => __( 'Title', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-social-icon-title',
			)
		);

		$this->add_responsive_control(
			'social_title_space_left',
			array(
				'label'     => __( 'Space Between', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 5,
				),
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-social-icon-title' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}


	/**
	 * Render image widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		require XPRO_ELEMENTOR_ADDONS_WIDGET . 'social-icon/layout/frontend.php';
	}
}
