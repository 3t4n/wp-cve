<?php

namespace XproElementorAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Utils;
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
class Social_Share extends Widget_Base {

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
		return 'xpro-social-share';
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
		return __( 'Social Share', 'xpro-elementor-addons' );
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
		return 'xi-share xpro-widget-label';
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
		return array( 'social', 'socials', 'share', 'shares' );
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
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @return array Widget scripts dependencies.
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 */

	public function get_script_depends() {
		return array( 'sharer' );
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
			'section_socialshare',
			array(
				'label' => __( 'General', 'xpro-elementor-addons' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'social_share_network',
			array(
				'label'       => __( 'Network', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'default'     => 'facebook',
				'options'     => array(
					'facebook'    => __( 'Facebook', 'xpro-elementor-addons' ),
					'twitter'     => __( 'Twitter', 'xpro-elementor-addons' ),
					'linkedin'    => __( 'Linkedin', 'xpro-elementor-addons' ),
					'email'       => __( 'Email', 'xpro-elementor-addons' ),
					'whatsapp'    => __( 'Whatsapp', 'xpro-elementor-addons' ),
					'telegram'    => __( 'Telegram', 'xpro-elementor-addons' ),
					'viber'       => __( 'Viber', 'xpro-elementor-addons' ),
					'pinterest'   => __( 'Pinterest', 'xpro-elementor-addons' ),
					'tumblr'      => __( 'Tumblr', 'xpro-elementor-addons' ),
					'reddit'      => __( 'Reddit', 'xpro-elementor-addons' ),
					'vk'          => __( 'VK', 'xpro-elementor-addons' ),
					'xing'        => __( 'Xing', 'xpro-elementor-addons' ),
					'get-pocket'  => __( 'Get Pocket', 'xpro-elementor-addons' ),
					'digg'        => __( 'Digg', 'xpro-elementor-addons' ),
					'skype'       => __( 'Skype', 'xpro-elementor-addons' ),
					'stumbleupon' => __( 'StumbleUpon', 'xpro-elementor-addons' ),
					'renren'      => __( 'Renren', 'xpro-elementor-addons' ),

				),
			)
		);

		$repeater->add_control(
			'social_share_title_enable',
			array(
				'label'        => __( 'Enable Title', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'    => __( 'Hide', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$repeater->add_control(
			'share_title',
			array(
				'label'       => __( 'Custom Title', 'xpro-elementor-addons' ),
				'placeholder' => __( 'Custom Title', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'condition'   => array(
					'social_share_title_enable' => 'yes',
				),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'link',
			array(
				'label'       => __( 'Custom Link', 'xpro-elementor-addons' ),
				'placeholder' => __( 'https://your-share-link.com', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::URL,
				'label_block' => true,
				'dynamic'     => array(
					'active' => true,
				),
			)
		);
		$repeater->add_control(
			'social_hashtags',
			array(
				'label'       => __( 'Hashtags', 'xpro-elementor-addons' ),
				'description' => __( 'Write hashtags without # sign and with comma separated value', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 2,
				'dynamic'     => array(
					'active' => true,
				),
				'conditions'  => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'social_share_network',
							'operator' => '!==',
							'value'    => 'facebook',
						),
						array(
							'name'     => 'social_share_network',
							'operator' => '!==',
							'value'    => 'linkedin',
						),
						array(
							'name'     => 'social_share_network',
							'operator' => '!==',
							'value'    => 'whatsapp',
						),
						array(
							'name'     => 'social_share_network',
							'operator' => '!==',
							'value'    => 'reddit',
						),
						array(
							'name'     => 'social_share_network',
							'operator' => '!==',
							'value'    => 'skype',
						),
						array(
							'name'     => 'social_share_network',
							'operator' => '!==',
							'value'    => 'pinterest',
						),
						array(
							'name'     => 'social_share_network',
							'operator' => '!==',
							'value'    => 'email',
						),
					),
				),
			)
		);

		$repeater->add_control(
			'share_custom_title',
			array(
				'label'      => __( 'Post Custom Title', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::TEXTAREA,
				'rows'       => 2,
				'dynamic'    => array(
					'active' => true,
				),
				'conditions' => array(
					'relation' => 'and',
					'terms'    => array(
						array(
							'name'     => 'social_share_network',
							'operator' => '!==',
							'value'    => 'facebook',
						),
						array(
							'name'     => 'social_share_network',
							'operator' => '!==',
							'value'    => 'linkedin',
						),
						array(
							'name'     => 'social_share_network',
							'operator' => '!==',
							'value'    => 'reddit',
						),
						array(
							'name'     => 'social_share_network',
							'operator' => '!==',
							'value'    => 'skype',
						),
						array(
							'name'     => 'social_share_network',
							'operator' => '!==',
							'value'    => 'pinterest',
						),
					),
				),
			)
		);

		$repeater->add_control(
			'social_email_to',
			array(
				'label'       => __( 'To', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'condition'   => array(
					'social_share_network' => 'email',
				),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'social_email_subject',
			array(
				'label'       => __( 'Subject', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'condition'   => array(
					'social_share_network' => 'email',
				),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'social_twitter_handle',
			array(
				'label'       => __( 'Twitter Handle', 'xpro-elementor-addons' ),
				'description' => __( 'Write without @ sign.', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'condition'   => array(
					'social_share_network' => 'twitter',
				),
			)
		);

		$repeater->add_control(
			'social_share_image',
			array(
				'type'      => Controls_Manager::MEDIA,
				'label'     => __( 'Custom Image', 'xpro-elementor-addons' ),
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'social_share_network' => 'pinterest',
				),
			)
		);

		$repeater->add_control(
			'social_share_inline_style',
			array(
				'label'        => __( 'Inline Style', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'    => __( 'Hide', 'xpro-elementor-addons' ),
				'render_type'  => 'ui',
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$repeater->start_controls_tabs(
			'tabs_item',
			array(
				'condition' => array(
					'social_share_inline_style' => 'yes',
				),
			)
		);

		$repeater->start_controls_tab(
			'social_share_normal',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$repeater->add_control(
			'social_share_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .xpro-social-share > i,
                     {{WRAPPER}} {{CURRENT_ITEM}} .xpro-social-share .xpro-social-share-title' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'social_share_inline_style' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'social_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .xpro-social-share ' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'social_share_inline_style' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'social_border_color',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .xpro-social-share ' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'social_share_inline_style' => 'yes',
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'social_share_hover',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$repeater->add_control(
			'social_active_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .xpro-social-share:hover > i,
                     {{WRAPPER}} {{CURRENT_ITEM}} .xpro-social-share:hover .xpro-social-share-title ' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'social_share_inline_style' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'social_active_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .xpro-social-share:hover ' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'social_share_inline_style' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'social_active_border_color',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .xpro-social-share:hover ' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'social_share_inline_style' => 'yes',
				),
			)
		);

		$repeater->end_controls_tab();
		$repeater->end_controls_tabs();

		$this->add_control(
			'social_share_item',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{ social_share_network }}',
				'show_label'  => false,
				'default'     => array(
					array(
						'social_share_network' => 'facebook',
					),
					array(
						'social_share_network' => 'twitter',
					),
					array(
						'social_share_network' => 'linkedin',
					),
				),
			)
		);

		$this->add_responsive_control(
			'social_share_align_horizontal',
			array(
				'label'     => __( 'Alignment', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::CHOOSE,
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
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .elementor-widget-container' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'social_share_column_grid',
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
					'{{WRAPPER}} .xpro-social-share-wrapper' => 'grid-template-columns: repeat({{VALUE}},1fr);',
				),
			)
		);

		$this->end_controls_section();

		//Styling
		$this->start_controls_section(
			'section_social_share_style',
			array(
				'label' => __( 'General', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'social_item_space_vertical',
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
					'{{WRAPPER}} .xpro-social-share-wrapper' => 'grid-column-gap: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .xpro-social-share-wrapper' => 'grid-row-gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( '_social_share' );

		$this->start_controls_tab(
			'social_share_normal',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'social_share_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-social-share > i,
                     {{WRAPPER}} .xpro-social-share .xpro-social-share-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'social_share_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-social-share, {{WRAPPER}} .xpro-social-share.xpro-button-bg-animation:before' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'social_share_border_color',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-social-share' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'social_share_list_active',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'social_share_hover_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-social-share:hover > i,
                     {{WRAPPER}} .xpro-social-share:hover .xpro-social-share-title ' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'social_share_hover_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-elementor-button-animation-none:hover,
                    {{WRAPPER}} .xpro-social-share-wrapper > li .xpro-social-share:hover,
                    {{WRAPPER}} .xpro-button-2d-animation:hover,
                    {{WRAPPER}} .xpro-social-share.xpro-unique-reverse-shape:hover,
                    {{WRAPPER}} .xpro-social-share.xpro-unique-triangle-shape:hover,
                    {{WRAPPER}} .xpro-social-share.xpro-unique-slide-shape:hover,
                    {{WRAPPER}} .xpro-social-share.xpro-button-bg-animation:hover:before' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'social_share_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-social-share:hover ' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'social_share_hover_animation',
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
			'social_share_hover_2d_css_animation',
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
					'social_share_hover_animation' => '2d-transition',
				),
			)
		);

		$this->add_control(
			'social_share_hover_background_css_animation',
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
					'social_share_hover_animation' => 'background-transition',
				),
			)
		);

		$this->add_control(
			'social_share_hover_effect_animation',
			array(
				'label'     => __( 'Animation', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'slide-shape',
				'options'   => array(
					'slide-shape'   => __( 'Slide', 'xpro-elementor-addons' ),
					'reverse-shape' => __( 'Reverse', 'xpro-elementor-addons' ),
				),
				'condition' => array(
					'social_share_hover_animation' => 'hover-effect',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'social_icon_border',
				'label'     => __( 'Border', 'xpro-elementor-addons' ),
				'selector'  => '{{WRAPPER}} .xpro-social-share',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'social_share_box_shadow',
				'label'    => __( 'Box Shadow', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-social-share',
			)
		);

		$this->add_control(
			'social_share_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-social-share' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'social_share_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-social-share' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			'share_icon_size',
			array(
				'label'      => __( 'Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => 20,
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-social-share > i' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'share_icon_bg_size',
			array(
				'label'      => __( 'Background Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => 25,
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-social-share > i' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_social_share_title_style',
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
				'selector' => '{{WRAPPER}} .xpro-social-share-title',
			)
		);

		$this->add_responsive_control(
			'social_title_space_left',
			array(
				'label'     => __( 'Title Space', 'xpro-elementor-addons' ),
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
					'{{WRAPPER}} .xpro-social-share-title' => 'margin-left: {{SIZE}}{{UNIT}};',
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

		require XPRO_ELEMENTOR_ADDONS_WIDGET . 'social-share/layout/frontend.php';
	}
}
