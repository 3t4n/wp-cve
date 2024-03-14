<?php
/**
 * Social Icons widget class
 *
 * @package Skt_Addons_Elementor
 */

namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Border;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

defined('ABSPATH') || die();

class Social_Icons extends Base {

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __('Social Icons', 'skt-addons-elementor');
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'skti skti-bond2';
	}

	public function get_keywords() {
		return ['social', 'icons', 'media', 'facebook', 'fb', 'twitter', 'linkedin'];
	}

	/**
     * Register widget content controls
     */
	protected function register_content_controls() {

		$this->start_controls_section(
			'_section_skt_addons_elementor_social_icons_contents',
			[
				'label' => __('Icon', 'skt-addons-elementor'),
				'tab'   => Controls_Manager::TAB_CONTENT
			]
		);
		$repeater = new Repeater();

		$repeater->add_control(
			'skt_addons_elementor_social_icon',
			[
				'label'       => __('Icon', 'skt-addons-elementor'),
				'type'        => Controls_Manager::ICONS,
				'label_block' => false,
				'default'     => [
					'value'   => 'fab fa-wordpress',
					'library' => 'fa-brands',
				],
				'skin' => 'inline',
				'exclude_inline_options' => ['svg'],
				'recommended' => [
					'fa-brands' => [
						'android',
						'apple',
						'behance',
						'bitbucket',
						'codepen',
						'delicious',
						'deviantart',
						'digg',
						'dribbble',
						'skt-addons-elementor',
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
					],
				],
			]
		);

		$repeater->add_control(
			'skt_addons_elementor_social_link',
			[
				'label'       => __('Link', 'skt-addons-elementor'),
				'type'        => Controls_Manager::URL,
				'label_block' => true,
				'dynamic'     => [
					'active' => true,
				],
				'default' => [
					'url' => '#'
				],
				'placeholder' => __('https://your-social-link.com', 'skt-addons-elementor'),
			]
		);

		// repeater icon text field
		$repeater->add_control(
			'skt_addons_elementor_enable_text',
			[
				'label'          => __('Enable Text', 'skt-addons-elementor'),
				'type'           => Controls_Manager::SWITCHER,
				'label_on'       => __('Yes', 'skt-addons-elementor'),
				'label_off'      => __('No', 'skt-addons-elementor'),
				'return_value'   => 'yes',
				'style_transfer' => true,
				'separator'      => 'before'
			]
		);

		$repeater->add_control(
			'skt_addons_elementor_social_icon_title',
			[
				'label'     => __('Social Name', 'skt-addons-elementor'),
				'type'      => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'condition' => [
					'skt_addons_elementor_enable_text' => 'yes'
				],
			]
		);

		$repeater->add_control(
			'customize',
			[
				'label'          => __('Want To Customize?', 'skt-addons-elementor'),
				'type'           => Controls_Manager::SWITCHER,
				'label_on'       => __('Yes', 'skt-addons-elementor'),
				'label_off'      => __('No', 'skt-addons-elementor'),
				'return_value'   => 'yes',
				'style_transfer' => true,
				'separator'      => 'before'
			]
		);

		$repeater->start_controls_tabs(
			'_tab_social_icon_colors',
			[
				'condition' => ['customize' => 'yes']
			]
		);
		$repeater->start_controls_tab(
			'_tab_skt_addons_elementor_social_icon_normal',
			[
				'label' => __('Normal', 'skt-addons-elementor'),
			]
		);

		$repeater->add_control(
			'skt_addons_elementor_social_icon_color',
			[
				'label' => __('Color', 'skt-addons-elementor'),
				'type'  => Controls_Manager::COLOR,

				'selectors'      => [
					'{{WRAPPER}} .skt-social-icons-wrapper > {{CURRENT_ITEM}}.skt-social-icon' => 'color: {{VALUE}};',
				],
				'condition'      => ['customize' => 'yes'],
				'style_transfer' => true,
			]
		);
		$repeater->add_control(
			'skt_addons_elementor_social_icon_bg_color',
			[
				'label' => __('Background Color', 'skt-addons-elementor'),
				'type'  => Controls_Manager::COLOR,

				'selectors'      => [
					'{{WRAPPER}} .skt-social-icons-wrapper > {{CURRENT_ITEM}}.skt-social-icon' => 'background-color: {{VALUE}};',
				],
				'condition'      => ['customize' => 'yes'],
				'style_transfer' => true,
			]
		);

		$repeater->add_control(
			'social_icon_border_color',
			[
				'label'          => __('Border Color', 'skt-addons-elementor'),
				'type'           => Controls_Manager::COLOR,
				'condition'      => ['customize' => 'yes'],
				'style_transfer' => true,
				'selectors'      => [
					'{{WRAPPER}} .skt-social-icons-wrapper {{CURRENT_ITEM}}' => 'border-color: {{VALUE}};',
				]
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'_tab_social_icon_hover',
			[
				'label' => __('Hover', 'skt-addons-elementor'),
			]
		);

		$repeater->add_control(
			'skt_addons_elementor_social_icon_hover_color',
			[
				'label'          => __('Color', 'skt-addons-elementor'),
				'type'           => Controls_Manager::COLOR,
				'selectors'      => [
					'{{WRAPPER}} .skt-social-icons-wrapper > {{CURRENT_ITEM}}.skt-social-icon:hover'     => 'color: {{VALUE}};',
				],
				'condition'      => ['customize' => 'yes'],
				'style_transfer' => true,
			]
		);
		$repeater->add_control(
			'skt_addons_elementor_social_icon_hover_bg_color',
			[
				'label'          => __('Background Color', 'skt-addons-elementor'),
				'type'           => Controls_Manager::COLOR,
				'selectors'      => [
					'{{WRAPPER}} .skt-social-icons-wrapper > {{CURRENT_ITEM}}.skt-social-icon:hover' => 'background-color: {{VALUE}};',
				],
				'condition'      => ['customize' => 'yes'],
				'style_transfer' => true,
			]
		);
		$repeater->add_control(
			'social_icon_hover_border_color',
			[
				'label'          => __('Border Color', 'skt-addons-elementor'),
				'type'           => Controls_Manager::COLOR,
				'condition'      => ['customize' => 'yes'],
				'style_transfer' => true,
				'selectors'      => [
					'{{WRAPPER}} .skt-social-icons-wrapper {{CURRENT_ITEM}}.skt-social-icon:hover' => 'border-color: {{VALUE}};',
				]
			]
		);

		$repeater->end_controls_tab();
		$repeater->end_controls_tabs();

		$this->add_control(
			'skt_addons_elementor_social_icon_list',
			[
				'label'       => __('Social Icons', 'skt-addons-elementor'),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'skt_addons_elementor_social_icon' => [
							'value'   => 'fab fa-facebook',
							'library' => 'fa-brands',
						],
						'skt_addons_elementor_social_link' => [
							'url' => '#'
						],
					],
					[
						'skt_addons_elementor_social_icon' => [
							'value'   => 'fab fa-twitter',
							'library' => 'fa-brands',
						],
						'skt_addons_elementor_social_link' => [
							'url' => '#'
						],
					],
					[
						'skt_addons_elementor_social_icon' => [
							'value'   => 'fab fa-linkedin',
							'library' => 'fa-brands',
						],
						'skt_addons_elementor_social_link' => [
							'url' => '#'
						],
					],
				],
				'title_field' => '<# print(elementor.helpers.getSocialNetworkNameFromIcon( skt_addons_elementor_social_icon ) || skt_addons_elementor_social_icon_title); #>',
			]
		);

		$this->add_control(
			'social_media_separator',
			[
				'label'        => __('Show Separator', 'skt-addons-elementor'),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'separator_type',
			[
				'label'        => __('Type', 'skt-addons-elementor'),
				'type'         => Controls_Manager::SELECT,
				'options'      => [
					'stroke' => __('Stroke', 'skt-addons-elementor'),
					'custom' => __('Custom', 'skt-addons-elementor'),
				],
				'default'      => 'stroke',
				'condition'    => [
					'social_media_separator' => 'yes'
				],
				'prefix_class' => 'skt-separator--',
				'render_type'  => 'template',
			]
		);

		$this->add_control(
			'default_separator',
			[
				'label'       => __('Stroke Size', 'skt-addons-elementor'),
				'type'        => Controls_Manager::SLIDER,
				'condition'   => [
					'social_media_separator' => 'yes',
					'separator_type'         => 'stroke'
				],
				'size_units'  => ['px', 'em'],
				'selectors'   => [
					'{{WRAPPER}}.skt-separator--stroke .skt-social-icon-separator' => 'width: {{SIZE}}{{UNIT}} !important;',
				],
				'render_type' => 'template'
			]
		);

		$this->add_control(
			'separator_color',
			[
				'label'     => __('Color', 'skt-addons-elementor'),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'social_media_separator' => 'yes',
					'separator_type'         => 'stroke'
				],
				'selectors' => [
					'{{WRAPPER}}.skt-separator--stroke .skt-social-icon-separator' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'custom_separator',
			[
				'label'       => __('Custom Character', 'skt-addons-elementor'),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'condition'   => [
					'social_media_separator' => 'yes',
					'separator_type'         => 'custom'
				],
				'render_type' => 'template'
			]
		);

		$this->add_responsive_control(
			'skt_addons_elementor_social_icon_align',
			[
				'label'       => __('Alignment', 'skt-addons-elementor'),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => [
					'left'   => [
						'title' => __('Left', 'skt-addons-elementor'),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __('Center', 'skt-addons-elementor'),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __('Right', 'skt-addons-elementor'),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'     => 'center',
				'selectors'   => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
				'separator'   => 'before',
				'render_type' => 'ui'
			]
		);

		$this->add_control(
			'sticky_options',
			[
				'label'        => __('Enable Sticky', 'skt-addons-elementor'),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator'    => 'before',
			]
		);

		$this->end_controls_section();
	}

	/**
     * Register widget style controls
     */
	protected function register_style_controls() {
		$this->__common_style_controls();
		$this->__icon_style_controls();
		$this->__social_name_style_controls();
		$this->__separator_style_controls();
	}

	protected function __common_style_controls() {

		$this->start_controls_section(
			'_section_common_style',
			[
				'label' => __('Common', 'skt-addons-elementor'),
				'tab'   => Controls_Manager::TAB_STYLE
			]
		);

		$this->start_controls_tabs('_tab_social_icons_colors');

		$this->start_controls_tab(
			'_tab_normal_social_color',
			[
				'label' => __('Normal', 'skt-addons-elementor'),
			]
		);

		$this->add_control(
			'social_icons_color',
			[
				'label' => __('Color', 'skt-addons-elementor'),
				'type'  => Controls_Manager::COLOR,

				'selectors'      => [
					'{{WRAPPER}} .skt-social-icons-wrapper > .skt-social-icon'       => 'color: {{VALUE}};',
					'{{WRAPPER}}.skt-separator--stroke .skt-social-icon-separator'   => 'background: {{VALUE}};',
					'{{WRAPPER}}.skt-separator--custom .skt-social-icon-separator'   => 'color: {{VALUE}};',
				],
				'style_transfer' => true,
			]
		);
		$this->add_control(
			'social_icons_bg_color',
			[
				'label' => __('Background Color', 'skt-addons-elementor'),
				'type'  => Controls_Manager::COLOR,

				'selectors'      => [
					'{{WRAPPER}} .skt-social-icons-wrapper .skt-social-icon' => 'background-color: {{VALUE}};',
				],
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'social_icon_common_border_color',
			[
				'label'     => __('Border Color', 'skt-addons-elementor'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-social-icons-wrapper .skt-social-icon' => 'border-color: {{VALUE}};',
				]
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'_tab_social_icons_hover',
			[
				'label' => __('Hover', 'skt-addons-elementor'),
			]
		);

		$this->add_control(
			'social_icons_hover_color',
			[
				'label'          => __('Color', 'skt-addons-elementor'),
				'type'           => Controls_Manager::COLOR,
				'selectors'      => [
					'{{WRAPPER}} .skt-social-icons-wrapper > .skt-social-icon:hover'     => 'color: {{VALUE}};',
					'{{WRAPPER}}.skt-separator--stroke .skt-social-icon-separator'       => 'background: {{VALUE}};',
					'{{WRAPPER}}.skt-separator--custom .skt-social-icon-separator'       => 'color: {{VALUE}};',
				],
				'style_transfer' => true,
			]
		);
		$this->add_control(
			'social_icons_hover_bg_color',
			[
				'label'          => __('Background Color', 'skt-addons-elementor'),
				'type'           => Controls_Manager::COLOR,
				'selectors'      => [
					'{{WRAPPER}} .skt-social-icon:hover' => 'background-color: {{VALUE}};',
				],
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'social_icon_common_hover_border_color',
			[
				'label'     => __('Border Color', 'skt-addons-elementor'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .skt-social-icons-wrapper .skt-social-icon:hover' => 'border-color: {{VALUE}};',
				]
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'skt_addons_elementor_social_icon_global_padding',
			[
				'label'          => __('Padding', 'skt-addons-elementor'),
				'type'           => Controls_Manager::DIMENSIONS,
				'selectors'      => [
					'{{WRAPPER}} .skt-social-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'size_units'     => ['px', 'em'],
				'default'        => [
					'unit' => 'px',
				],
				'tablet_default' => [
					'unit' => 'px',
				],
				'mobile_default' => [
					'unit' => 'px',
				],
				'range'          => [
					'px' => [
						'min' => 20,
						'max' => 300
					],
					'em' => [
						'min' => 0,
						'max' => 5,
					],
				],
			]
		);

		$icon_spacing = is_rtl() ? 'margin-left: {{SIZE}}{{UNIT}};' : 'margin-right: {{SIZE}}{{UNIT}};';

		$this->add_responsive_control(
			'skt_addons_elementor_social_icon_spacing',
			[
				'label'     => __('Social Spacing', 'skt-addons-elementor'),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-social-icon:not(:last-child)' => $icon_spacing,
					'{{WRAPPER}} .skt-social-icon-separator'        => $icon_spacing,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'image_border',
				'selector'  => '{{WRAPPER}} .skt-social-icon',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'skt_addons_elementor_social_icon_border_radius',
			[
				'label'      => __('Border Radius', 'skt-addons-elementor'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'{{WRAPPER}} .skt-social-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'icons_box_shadow',
				//'selector'      => '{{WRAPPER}} .skt-social-icon, {{WRAPPER}} .skt-social-icon-separator',
				'selector' => '{{WRAPPER}} .skt-social-icon',
			]
		);

		$this->end_controls_section();
	}

	protected function __icon_style_controls() {

		$this->start_controls_section(
			'_section_style_skt_addons_elementor_icon',
			[
				'label' => __('Icon', 'skt-addons-elementor'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'skt_addons_elementor_social_icon_size',
			[
				'label'     => __('Size', 'skt-addons-elementor'),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 20,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-social-icon.skt-social-icon--network i'   => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'skt_addons_elementor_social_icon_padding',
			[
				'label'          => __('Padding', 'skt-addons-elementor'),
				'type'           => Controls_Manager::SLIDER,
				'selectors'      => [
					'{{WRAPPER}} .skt-social-icon.skt-social-icon--network' => 'padding: {{SIZE}}{{UNIT}};',
				],
				'default'        => [
					'unit' => 'em',
				],
				'tablet_default' => [
					'unit' => 'em',
				],
				'mobile_default' => [
					'unit' => 'em',
				],
				'range'          => [
					'em' => [
						'min' => 0,
						'max' => 5,
					],
				],
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label'   => __('Hover Animation', 'skt-addons-elementor'),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'none'                   => __('None', 'skt-addons-elementor'),
					'2d-transition'          => __('2D Animation', 'skt-addons-elementor'),
					'background-transition'  => __('Background Animation', 'skt-addons-elementor'),
					'shadow-glow-transition' => __('Shadow and Glow Animation', 'skt-addons-elementor'),
				]
			]
		);

		$this->add_control(
			'hover_2d_css_animation',
			[
				'label'     => __('Animation', 'skt-addons-elementor'),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'hvr-grow'                   => __('Grow', 'skt-addons-elementor'),
					'hvr-shrink'                 => __('Shrink', 'skt-addons-elementor'),
					'hvr-pulse'                  => __('Pulse', 'skt-addons-elementor'),
					'hvr-pulse-grow'             => __('Pulse Grow', 'skt-addons-elementor'),
					'hvr-pulse-shrink'           => __('Pulse Shrink', 'skt-addons-elementor'),
					'hvr-push'                   => __('Push', 'skt-addons-elementor'),
					'hvr-pop'                    => __('Pop', 'skt-addons-elementor'),
					'hvr-bounce-in'              => __('Bounce In', 'skt-addons-elementor'),
					'hvr-bounce-out'             => __('Bounce Out', 'skt-addons-elementor'),
					'hvr-rotate'                 => __('Rotate', 'skt-addons-elementor'),
					'hvr-grow-rotate'            => __('Grow Rotate', 'skt-addons-elementor'),
					'hvr-float'                  => __('Float', 'skt-addons-elementor'),
					'hvr-sink'                   => __('Sink', 'skt-addons-elementor'),
					'hvr-bob'                    => __('Bob', 'skt-addons-elementor'),
					'hvr-hang'                   => __('Hang', 'skt-addons-elementor'),
					'hvr-wobble-vertical'        => __('Wobble Vertical', 'skt-addons-elementor'),
					'hvr-wobble-horizontal'      => __('Wobble Horizontal', 'skt-addons-elementor'),
					'hvr-wobble-to-bottom-right' => __('Wobble To Bottom Right', 'skt-addons-elementor'),
					'hvr-wobble-to-top-right'    => __('Wobble To Top Right', 'skt-addons-elementor'),
					'hvr-buzz'                   => __('Buzz', 'skt-addons-elementor'),
					'hvr-buzz-out'               => __('Buzz Out', 'skt-addons-elementor'),
				],
				'condition' => [
					'hover_animation' => '2d-transition'
				]
			]
		);

		$this->add_control(
			'hover_background_css_animation',
			[
				'label'     => __('Animation', 'skt-addons-elementor'),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'hvr-fade'                   => __('Fade', 'skt-addons-elementor'),
					'hvr-back-pulse'             => __('Back Pulse', 'skt-addons-elementor'),
					'hvr-sweep-to-right'         => __('Sweep To Right', 'skt-addons-elementor'),
					'hvr-sweep-to-left'          => __('Sweep To Left', 'skt-addons-elementor'),
					'hvr-sweep-to-bottom'        => __('Sweep To Bottom', 'skt-addons-elementor'),
					'hvr-sweep-to-top'           => __('Sweep To Top', 'skt-addons-elementor'),
					'hvr-bounce-to-right'        => __('Bounce To Right', 'skt-addons-elementor'),
					'hvr-bounce-to-left'         => __('Bounce To Left', 'skt-addons-elementor'),
					'hvr-bounce-to-bottom'       => __('Bounce To Bottom', 'skt-addons-elementor'),
					'hvr-bounce-to-top'          => __('Bounce To Top', 'skt-addons-elementor'),
					'hvr-radial-out'             => __('Radial Out', 'skt-addons-elementor'),
					'hvr-radial-in'              => __('Radial In', 'skt-addons-elementor'),
					'hvr-rectangle-in'           => __('Rectangle In', 'skt-addons-elementor'),
					'hvr-rectangle-out'          => __('Rectangle Out', 'skt-addons-elementor'),
					'hvr-shutter-in-horizontal'  => __('Shutter In Horizontal', 'skt-addons-elementor'),
					'hvr-shutter-out-horizontal' => __('Shutter Out Horizontal', 'skt-addons-elementor'),
					'hvr-shutter-in-vertical'    => __('Shutter In Vertical', 'skt-addons-elementor'),
					'hvr-shutter-out-vertical'   => __('Shutter Out Vertical', 'skt-addons-elementor'),
				],
				'condition' => [
					'hover_animation' => 'background-transition'
				]
			]

		);

		$this->add_control(
			'hover_shadow_glow_css_animation',
			[
				'label'     => __('Animation', 'skt-addons-elementor'),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'hvr-glow'              => __('Glow', 'skt-addons-elementor'),
					'hvr-shadow'            => __('Shadow', 'skt-addons-elementor'),
					'hvr-grow-shadow'       => __('Grow Shadow', 'skt-addons-elementor'),
					'hvr-box-shadow-outset' => __('Box Shadow Outset', 'skt-addons-elementor'),
					'hvr-box-shadow-inset'  => __('Box Shadow Inset', 'skt-addons-elementor'),
					'hvr-float-shadow'      => __('Float Shadow', 'skt-addons-elementor'),
					'hvr-shadow-radial'     => __('Shadow Radial', 'skt-addons-elementor'),
				],
				'condition' => [
					'hover_animation' => 'shadow-glow-transition'
				]
			]

		);

		$this->end_controls_section();
	}

	protected function __social_name_style_controls() {

		$this->start_controls_section(
			'_section_style_custom_label',
			[
				'label' => __('Social Name', 'skt-addons-elementor'),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'custom_label_typography',
				'label'    => __('Typography', 'skt-addons-elementor'),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .skt-social-icon-label'
			]

		);

		$this->add_control(
			'social_name_spacing',
			[
				'label'     => __('Spacing', 'skt-addons-elementor'),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .skt-social-icon:not(.elementor-social-icon-label) .skt-social-icon-label' => 'margin-left: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __separator_style_controls() {

		$this->start_controls_section(
			'_section_social_icon_separator',
			[
				'label' => __('Separator', 'skt-addons-elementor'),
				'tab'   => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'custom_separator_color',
			[
				'label'     => __('Color', 'skt-addons-elementor'),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'social_media_separator' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}}.skt-separator--stroke .skt-social-icon-separator' => 'background: {{VALUE}} !important;',
					'{{WRAPPER}}.skt-separator--custom .skt-social-icon-separator' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'skt_addons_elementor_icon_separator_typography',
				'label'    => __('Typography', 'skt-addons-elementor'),
				'selector' => '{{WRAPPER}} .skt-social-icon-separator',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings      = $this->get_settings_for_display();
		$social_list   = $settings['skt_addons_elementor_social_icon_list'];
		$sticky_option = $settings['sticky_options'];
		$sticky_class  = '';
		if ('yes' === $sticky_option) {
			$sticky_class = 'skt-social-icon-sticky';
		}

		$enable_separator  = $settings['social_media_separator'];
		$separator_type    = $settings['separator_type'];
		$custom_separators = $settings['custom_separator'];
		$separators        = $custom_separators ? $custom_separators : '';

		$hover_css_animation = '';

		if (!empty($settings['hover_animation'])) {

			if ($settings['hover_2d_css_animation']) {
				$hover_css_animation = $settings['hover_2d_css_animation'];
			}

			if ($settings['hover_background_css_animation']) {
				$hover_css_animation = $settings['hover_background_css_animation'];
			}

			if ($settings['hover_shadow_glow_css_animation']) {
				$hover_css_animation = $settings['hover_shadow_glow_css_animation'];
			}
		}
		?>
		<div class="skt-social-icons-wrapper <?php echo esc_attr($sticky_class); ?>">
			<?php
			foreach ($social_list as $key => $icons) {
				$icon         = $icons['skt_addons_elementor_social_icon']['value'];
				$social_title = esc_html($icons['skt_addons_elementor_social_icon_title']);
				$link_attr    = 'link_' . $key;

				if (!empty($icons['skt_addons_elementor_social_icon'])) {
					$social_name = str_replace(['fa fa-', 'fab fa-', 'far fa-'], '', $icon);
				}

				$this->add_link_attributes( $link_attr, $icons['skt_addons_elementor_social_link'] );

				$this->add_render_attribute($link_attr, 'class', [
					'skt-social-icon',
					'elementor-repeater-item-' . $icons['_id'] . ' ' . $hover_css_animation,
					'elementor-social-icon-' . ($icon ? $social_name : 'label'),
				]);

				if (!empty($icon)) {
					$this->add_render_attribute($link_attr, 'class', 'skt-social-icon--network');
				} else {
					$this->add_render_attribute($link_attr, 'class', 'skt-social-icon--custom-label');
				}
				?>
				<a <?php echo wp_kses_post($this->get_render_attribute_string($link_attr)); ?>>
					<?php
					Icons_Manager::render_icon($icons['skt_addons_elementor_social_icon']);
					if (!empty($social_title) && '' != $social_title) {
						echo wp_kses_post("<span class='skt-social-icon-label'>" . $social_title . "</span>");
					}

					?>
				</a>
				<?php
				if ('yes' === $enable_separator) {
					echo wp_kses_post("<span class='skt-social-icon-separator'> " . $separators . " </span>");
				}
			}
			?>
		</div>
		<?php
	}
}