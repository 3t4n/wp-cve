<?php

namespace XproElementorAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;
use XproElementorAddons\Control\Xpro_Elementor_Group_Control_Foreground;
use XproElementorAddons\Control\Xpro_Elementor_Image_Selector;

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
class Team extends Widget_Base {

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
		return 'xpro-team';
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
		return __( 'Team', 'xpro-elementor-addons' );
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
		return 'xi-team-grid xpro-widget-label';
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
		return array( 'team', 'grid' );
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
			'section_general',
			array(
				'label' => __( 'General', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'              => esc_html__( 'Layout', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => '1',
				'options'            => array(
					'1'  => esc_html__( 'Style 1', 'xpro-elementor-addons' ),
					'2'  => esc_html__( 'Style 2', 'xpro-elementor-addons' ),
					'3'  => esc_html__( 'Style 3', 'xpro-elementor-addons' ),
					'4'  => esc_html__( 'Style 4', 'xpro-elementor-addons' ),
					'5'  => esc_html__( 'Style 5', 'xpro-elementor-addons' ),
					'6'  => esc_html__( 'Style 6', 'xpro-elementor-addons' ),
					'7'  => esc_html__( 'Style 7', 'xpro-elementor-addons' ),
					'8'  => esc_html__( 'Style 8', 'xpro-elementor-addons' ),
					'9'  => esc_html__( 'Style 9', 'xpro-elementor-addons' ),
					'10' => esc_html__( 'Style 10', 'xpro-elementor-addons' ),
					'11' => esc_html__( 'Style 11', 'xpro-elementor-addons' ),
					'12' => esc_html__( 'Style 12', 'xpro-elementor-addons' ),
					'13' => esc_html__( 'Style 13', 'xpro-elementor-addons' ),
					'14' => esc_html__( 'Style 14', 'xpro-elementor-addons' ),
					'15' => esc_html__( 'Style 15', 'xpro-elementor-addons' ),
				),
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'image',
			array(
				'label'   => __( 'Choose Image', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::MEDIA,
				'dynamic' => array(
					'active' => true,
				),
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
			)
		);

		$this->add_control(
			'mask_image',
			array(
				'label'        => __( 'Mask Image', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'Default', 'xpro-elementor-addons' ),
				'label_on'     => __( 'Custom', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
			)
		);

		$this->start_popover();

		$this->add_control(
			'mask_shape',
			array(
				'label'   => __( 'Mask Type', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'default',
				'options' => array(
					'default' => array(
						'title' => _x( 'Default Shapes', 'Mask Image', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-image-bold',
					),
					'custom'  => array(
						'title' => _x( 'Custom Shape', 'Mask Image', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-upload',
					),
				),
				'toggle'  => false,
			)
		);

		$this->add_control(
			'mask_shape_default',
			array(
				'label'                => _x( 'Default', 'Mask Image', 'xpro-elementor-addons' ),
				'label_block'          => true,
				'show_label'           => false,
				'type'                 => Xpro_Elementor_Image_Selector::TYPE,
				'default'              => 'shape1',
				'options'              => xpro_elementor_masking_shape_list( 'list' ),
				'selectors'            => array(
					'{{WRAPPER}} .xpro-team-image > img' => '-webkit-mask-image: url({{VALUE}}); mask-image: url({{VALUE}});',
				),
				'selectors_dictionary' => xpro_elementor_masking_shape_list( 'url' ),
				'condition'            => array(
					'mask_image' => 'yes',
					'mask_shape' => 'default',
				),
			)
		);

		$this->add_control(
			'mask_custom_shape',
			array(
				'label'       => _x( 'Custom Shape', 'Mask Image', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::MEDIA,
				'show_label'  => false,
				'description' => sprintf(
				/* translators: %s: Title */
					__( 'Note: Make sure svg support is enable to upload svg file. %1$sRead More%2$s', 'xpro-elementor-addons' ),
					'<a href="https://elementor.com/help/enable-svg-support-in-elementor/" target="_blank">',
					'</a>'
				),
				'selectors'   => array(
					'{{WRAPPER}} .xpro-team-image > img' => '-webkit-mask-image: url({{URL}}); mask-image: url({{URL}});',
				),
				'condition'   => array(
					'mask_image' => 'yes',
					'mask_shape' => 'custom',
				),
			)
		);

		$this->add_control(
			'mask_position',
			array(
				'label'                => _x( 'Position', 'Mask Image', 'xpro-elementor-addons' ),
				'type'                 => Controls_Manager::SELECT,
				'default'              => 'center-center',
				'options'              => array(
					'center-center' => _x( 'Center Center', 'Mask Image', 'xpro-elementor-addons' ),
					'center-left'   => _x( 'Center Left', 'Mask Image', 'xpro-elementor-addons' ),
					'center-right'  => _x( 'Center Right', 'Mask Image', 'xpro-elementor-addons' ),
					'top-center'    => _x( 'Top Center', 'Mask Image', 'xpro-elementor-addons' ),
					'top-left'      => _x( 'Top Left', 'Mask Image', 'xpro-elementor-addons' ),
					'top-right'     => _x( 'Top Right', 'Mask Image', 'xpro-elementor-addons' ),
					'bottom-center' => _x( 'Bottom Center', 'Mask Image', 'xpro-elementor-addons' ),
					'bottom-left'   => _x( 'Bottom Left', 'Mask Image', 'xpro-elementor-addons' ),
					'bottom-right'  => _x( 'Bottom Right', 'Mask Image', 'xpro-elementor-addons' ),
				),
				'selectors_dictionary' => array(
					'center-center' => 'center center',
					'center-left'   => 'center left',
					'center-right'  => 'center right',
					'top-center'    => 'top center',
					'top-left'      => 'top left',
					'top-right'     => 'top right',
					'bottom-center' => 'bottom center',
					'bottom-left'   => 'bottom left',
					'bottom-right'  => 'bottom right',
				),
				'selectors'            => array(
					'{{WRAPPER}} .xpro-team-image > img' => '-webkit-mask-position: {{VALUE}}; mask-position: {{VALUE}};',
				),
				'condition'            => array(
					'mask_image' => 'yes',
				),
			)
		);

		$this->add_control(
			'mask_size',
			array(
				'label'     => _x( 'Size', 'Mask Image', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'contain',
				'options'   => array(
					'auto'    => _x( 'Auto', 'Mask Image', 'xpro-elementor-addons' ),
					'cover'   => _x( 'Cover', 'Mask Image', 'xpro-elementor-addons' ),
					'contain' => _x( 'Contain', 'Mask Image', 'xpro-elementor-addons' ),
					'initial' => _x( 'Custom', 'Mask Image', 'xpro-elementor-addons' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-team-image > img' => '-webkit-mask-size: {{VALUE}}; mask-size: {{VALUE}};',
				),
				'condition' => array(
					'mask_image' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'mask_custom_size',
			array(
				'label'      => _x( 'Custom Size', 'Mask Image', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', '%', 'vw' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
					'em' => array(
						'min' => 0,
						'max' => 100,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
					'vw' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 100,
					'unit' => '%',
				),
				'required'   => true,
				'selectors'  => array(
					'{{WRAPPER}} .xpro-team-image > img' => '-webkit-mask-size: {{SIZE}}{{UNIT}}; mask-size: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'mask_image' => 'yes',
					'mask_size'  => 'initial',
				),
			)
		);

		$this->add_control(
			'mask_repeat',
			array(
				'label'                => _x( 'Repeat', 'Mask Image', 'xpro-elementor-addons' ),
				'type'                 => Controls_Manager::SELECT,
				'default'              => 'no-repeat',
				'options'              => array(
					'repeat'          => _x( 'Repeat', 'Mask Image', 'xpro-elementor-addons' ),
					'repeat-x'        => _x( 'Repeat-x', 'Mask Image', 'xpro-elementor-addons' ),
					'repeat-y'        => _x( 'Repeat-y', 'Mask Image', 'xpro-elementor-addons' ),
					'space'           => _x( 'Space', 'Mask Image', 'xpro-elementor-addons' ),
					'round'           => _x( 'Round', 'Mask Image', 'xpro-elementor-addons' ),
					'no-repeat'       => _x( 'No-repeat', 'Mask Image', 'xpro-elementor-addons' ),
					'repeat-space'    => _x( 'Repeat Space', 'Mask Image', 'xpro-elementor-addons' ),
					'round-space'     => _x( 'Round Space', 'Mask Image', 'xpro-elementor-addons' ),
					'no-repeat-round' => _x( 'No-repeat Round', 'Mask Image', 'xpro-elementor-addons' ),
				),
				'selectors_dictionary' => array(
					'repeat'          => 'repeat',
					'repeat-x'        => 'repeat-x',
					'repeat-y'        => 'repeat-y',
					'space'           => 'space',
					'round'           => 'round',
					'no-repeat'       => 'no-repeat',
					'repeat-space'    => 'repeat space',
					'round-space'     => 'round space',
					'no-repeat-round' => 'no-repeat round',
				),
				'selectors'            => array(
					'{{WRAPPER}} .xpro-team-image > img' => '-webkit-mask-repeat: {{VALUE}}; mask-repeat: {{VALUE}};',
				),
				'condition'            => array(
					'mask_image' => 'yes',
				),
			)
		);

		$this->end_popover();

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'thumbnail',
				'default'   => 'large',
				'separator' => 'none',
			)
		);

		$this->add_control(
			'title',
			array(
				'label'       => __( 'Name', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Jhon Walker', 'xpro-elementor-addons' ),
				'label_block' => true,
				'separator'   => 'before',
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'title_link',
			array(
				'label'       => __( 'Link', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://example.com',
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'designation',
			array(
				'label'       => __( 'Designation', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Managing Director', 'xpro-elementor-addons' ),
				'label_block' => true,
				'separator'   => 'before',
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'description',
			array(
				'label'       => __( 'Description', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => __( 'It is a long established fact that a reader will be distracted by the content.', 'xpro-elementor-addons' ),
				'placeholder' => __( 'Type your description here', 'xpro-elementor-addons' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$this->add_control(
			'align',
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
				'separator' => 'before',
				'selectors' => array(
					'{{WRAPPER}} .xpro-team-wrapper' => 'text-align: {{VALUE}};',
				),
				'condition' => array(
					'layout!' => array( '8', '9' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_social',
			array(
				'label' => __( 'Social', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'social_enable',
			array(
				'label'        => __( 'Enable', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'    => __( 'Hide', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'social_icon',
			array(
				'label'   => __( 'Icon', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::ICONS,
				'default' => array(
					'value'   => 'fab fa-wordpress',
					'library' => 'fa-brands',
				),
			)
		);

		$repeater->add_control(
			'icon_link',
			array(
				'label'       => __( 'Link', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::URL,
				'default'     => array(
					'is_external' => 'true',
				),
				'dynamic'     => array(
					'active' => true,
				),
				'placeholder' => __( 'https://your-link.com', 'xpro-elementor-addons' ),
			)
		);

		$repeater->add_control(
			'icon_inline_style',
			array(
				'label'        => __( 'Inline Style', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'xpro-elementor-addons' ),
				'label_off'    => __( 'Hide', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
			)
		);

		$repeater->start_controls_tabs( 'icon_inline_style_tab' );

		$repeater->start_controls_tab(
			'icon_inline_normal',
			array(
				'label'     => __( 'Normal', 'xpro-elementor-addons' ),
				'condition' => array(
					'icon_inline_style' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'icon_inline_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .xpro-team-social-list {{CURRENT_ITEM}} .xpro-team-social-icon > i'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .xpro-team-social-list {{CURRENT_ITEM}} .xpro-team-social-icon > svg' => 'fill: {{VALUE}};',
				),
				'condition' => array(
					'icon_inline_style' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'icon_inline_bg',
			array(
				'label'     => __( 'Background', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .xpro-team-social-list {{CURRENT_ITEM}} .xpro-team-social-icon' => 'background: {{VALUE}};',
				),
				'condition' => array(
					'icon_inline_style' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'icon_inline_border',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .xpro-team-social-list {{CURRENT_ITEM}} .xpro-team-social-icon' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'icon_inline_style' => 'yes',
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'icon_inline_hover',
			array(
				'label'     => __( 'Hover', 'xpro-elementor-addons' ),
				'condition' => array(
					'icon_inline_style' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'icon_inline_hover_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-team-social-list {{CURRENT_ITEM}} .xpro-team-social-icon:hover > i, {{WRAPPER}} .xpro-team-social-list {{CURRENT_ITEM}} .xpro-team-social-icon:focus > i'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .xpro-team-social-list {{CURRENT_ITEM}} .xpro-team-social-icon:hover > svg, {{WRAPPER}} .xpro-team-social-list {{CURRENT_ITEM}} .xpro-team-social-icon:focus > svg' => 'fill: {{VALUE}};',
				),
				'condition' => array(
					'icon_inline_style' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'icon_inline_hover_bg',
			array(
				'label'     => __( 'Background', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-team-social-list {{CURRENT_ITEM}} .xpro-team-social-icon:hover, {{WRAPPER}} .xpro-team-social-list {{CURRENT_ITEM}} .xpro-team-social-icon:focus' => 'background: {{VALUE}};',
				),
				'condition' => array(
					'icon_inline_style' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'icon_inline_border_hcolor',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-team-social-list {{CURRENT_ITEM}} .xpro-team-social-icon:hover, {{WRAPPER}} .xpro-team-social-list {{CURRENT_ITEM}} .xpro-team-social-icon:focus' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'icon_inline_style' => 'yes',
				),
			)
		);

		$repeater->end_controls_tab();
		$repeater->end_controls_tabs();

		$this->add_control(
			'social_icon_list',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'social_icon' => array(
							'value'   => 'fab fa-facebook-f',
							'library' => 'fa-brands',
						),
					),
					array(
						'social_icon' => array(
							'value'   => 'fab fa-twitter',
							'library' => 'fa-brands',
						),
					),
					array(
						'social_icon' => array(
							'value'   => 'fab fa-instagram',
							'library' => 'fa-brands',
						),
					),
				),
				'title_field' => '<# var migrated = "undefined" !== typeof __fa4_migrated, social = ( "undefined" === typeof social ) ? false : social; #>{{{ elementor.helpers.getSocialNetworkNameFromIcon( social_icon, social, true, migrated, true ) }}}',
				'condition'   => array(
					'social_enable' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		//Styling
		$this->start_controls_section(
			'section_image_style',
			array(
				'label' => __( 'Image', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'width',
			array(
				'label'      => __( 'Width', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'unit' => 'px',
				),
				'size_units' => array( 'px', '%', 'vw' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-team-wrapper .xpro-team-image > img' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'height',
			array(
				'label'          => __( 'Height', 'xpro-elementor-addons' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'unit' => 'px',
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'size_units'     => array( 'px', 'vh' ),
				'range'          => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}} .xpro-team-wrapper .xpro-team-image > img' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'object-fit',
			array(
				'label'     => __( 'Object Fit', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'condition' => array(
					'height[size]!' => '',
				),
				'options'   => array(
					''        => __( 'Default', 'xpro-elementor-addons' ),
					'fill'    => __( 'Fill', 'xpro-elementor-addons' ),
					'cover'   => __( 'Cover', 'xpro-elementor-addons' ),
					'contain' => __( 'Contain', 'xpro-elementor-addons' ),
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .xpro-team-wrapper .xpro-team-image > img' => 'object-fit: {{VALUE}};',
				),
			)
		);

		$this->start_controls_tabs( 'image_effects' );

		$this->start_controls_tab(
			'normal',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'shape_color',
			array(
				'label'     => __( 'Shape Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-team-layout-13::after' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'layout' => array( '13' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'css_filters',
				'selector' => '{{WRAPPER}} .xpro-team-wrapper .xpro-team-image img',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'hover',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'image_overlay',
			array(
				'label'     => __( 'Overlay Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-team-layout-5 .xpro-team-image::before, {{WRAPPER}} .xpro-team-layout-12 .xpro-team-image::after' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'layout' => array( '5', '12' ),
				),
			)
		);

		$this->add_control(
			'shape_hcolor',
			array(
				'label'     => __( 'Shape Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-team-layout-13:hover::after' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'layout' => array( '13' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'     => 'css_filters_hover',
				'selector' => '{{WRAPPER}} .xpro-team-wrapper:hover .xpro-team-image img',
			)
		);

		$this->add_control(
			'background_hover_transition',
			array(
				'label'     => __( 'Transition Duration', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'max'  => 3,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-team-wrapper .xpro-team-image img' => 'transition-duration: {{SIZE}}s',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'image_border',
				'selector'  => '{{WRAPPER}} .xpro-team-wrapper .xpro-team-image > img',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'image_box_shadow',
				'exclude'  => array(
					'box_shadow_position',
				),
				'selector' => '{{WRAPPER}} .xpro-team-wrapper .xpro-team-image > img',
			)
		);

		$this->add_responsive_control(
			'image_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-team-wrapper .xpro-team-image,{{WRAPPER}} .xpro-team-wrapper .xpro-team-image > img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'image_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-team-wrapper .xpro-team-image' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'layout' => '9',
				),
			)
		);

		$this->add_responsive_control(
			'image_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-team-wrapper .xpro-team-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		//Content
		$this->start_controls_section(
			'section_content_style',
			array(
				'label' => __( 'Content', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'content_height',
			array(
				'label'      => esc_html__( 'Height', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-team-layout-6 .xpro-team-content' => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'layout' => '6',
				),
			)
		);

		$this->add_control(
			'content_backdrop_blur',
			array(
				'label'     => esc_html__( 'Backdrop Blur', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => 1,
					),
				),
				'default'   => array(
					'size' => 3,
				),
				'selectors' => array(
					'{{WRAPPER}} .xpro-team-layout-6 .xpro-team-content:before' => 'backdrop-filter: blur({{SIZE}}{{UNIT}});',
				),
				'condition' => array(
					'layout' => '6',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'content_background',
				'label'     => __( 'Background', 'xpro-elementor-addons' ),
				'types'     => array( 'classic', 'gradient' ),
				'exclude'   => array( 'image' ),
				'selector'  => '{{WRAPPER}} .xpro-team-wrapper .xpro-team-content,{{WRAPPER}} .xpro-team-layout-9 .xpro-team-inner-content',
				'condition' => array(
					'layout!' => array( '15' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'content_border',
				'selector' => '{{WRAPPER}} .xpro-team-wrapper .xpro-team-content',
			)
		);

		$this->add_responsive_control(
			'content_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-team-wrapper .xpro-team-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'separator_color',
			array(
				'label'     => __( 'Separator Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-team-layout-9 .xpro-team-description::before' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'layout' => '9',
				),
			)
		);

		$this->add_responsive_control(
			'content_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-team-wrapper .xpro-team-content,{{WRAPPER}} .xpro-team-layout-9 .xpro-team-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'heading_title',
			array(
				'label'     => __( 'Title', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'title!' => '',
				),
			)
		);

		$this->add_group_control(
			Xpro_Elementor_Group_Control_Foreground::get_type(),
			array(
				'name'      => 'title_color',
				'label'     => __( 'Title Color', 'xpro-elementor-addons' ),
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .xpro-team-wrapper .xpro-team-title',
				'condition' => array(
					'title!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'title_typography',
				'label'     => __( 'Typography', 'xpro-elementor-addons' ),
				'selector'  => '{{WRAPPER}} .xpro-team-wrapper .xpro-team-title',
				'condition' => array(
					'title!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-team-wrapper .xpro-team-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'title!' => '',
				),
			)
		);

		$this->add_control(
			'heading_designation',
			array(
				'label'     => __( 'Designation', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'designation!' => '',
				),
			)
		);

		$this->add_control(
			'designation_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-team-wrapper .xpro-team-designation' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'designation!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'designation_typography',
				'label'     => __( 'Typography', 'xpro-elementor-addons' ),
				'selector'  => '{{WRAPPER}} .xpro-team-wrapper .xpro-team-designation',
				'condition' => array(
					'designation!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'designation_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-team-wrapper .xpro-team-designation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'designation!' => '',
				),
			)
		);

		$this->add_control(
			'heading_description',
			array(
				'label'     => __( 'Description', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'description!' => '',
				),
			)
		);

		$this->add_control(
			'description_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-team-wrapper .xpro-team-description' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'description!' => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'description_typography',
				'label'     => __( 'Typography', 'xpro-elementor-addons' ),
				'selector'  => '{{WRAPPER}} .xpro-team-wrapper .xpro-team-description',
				'condition' => array(
					'description!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'description_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-team-wrapper .xpro-team-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'description!' => '',
				),
			)
		);

		$this->end_controls_section();

		// Social Icon
		$this->start_controls_section(
			'section_social_icon_style',
			array(
				'label'     => __( 'Social', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'social_enable' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => __( 'Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-team-social-list .xpro-team-social-icon > i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-team-social-list .xpro-team-social-icon > svg' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_bg_size',
			array(
				'label'      => __( 'Background Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-team-social-list .xpro-team-social-icon' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_space',
			array(
				'label'      => __( 'Space Between', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => - 100,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-team-wrapper .xpro-team-social-list > li'                       => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-team-layout-9 .xpro-team-social-list > li,
					 {{WRAPPER}} .xpro-team-layout-13 .xpro-team-social-list > li,
					 {{WRAPPER}} .xpro-team-layout-15 .xpro-team-social-list > li' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'social_icon_style' );

		$this->start_controls_tab(
			'icon_normal',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .xpro-team-social-list .xpro-team-social-icon > i'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .xpro-team-social-list .xpro-team-social-icon > svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_bg',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .xpro-team-social-list .xpro-team-social-icon' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_wrapper_bg',
			array(
				'label'     => __( 'Wrapper Background', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .xpro-team-layout-15 .xpro-team-social-list' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'layout' => array( '15' ),
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_hover',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'icon_hover_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-team-social-list .xpro-team-social-icon:hover > i, {{WRAPPER}} .xpro-team-social-list .xpro-team-social-icon:focus > i'    => 'color: {{VALUE}};',
					'{{WRAPPER}} .xpro-team-social-list .xpro-team-social-icon:hover > svg, {{WRAPPER}} .xpro-team-social-list .xpro-team-social-icon:focus  svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_hbg',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .xpro-team-social-list .xpro-team-social-icon:hover,{{WRAPPER}} .xpro-team-social-list .xpro-team-social-icon:focus' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_border_hover_color',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-team-social-list .xpro-team-social-icon:hover, {{WRAPPER}} .xpro-team-social-list .xpro-team-social-icon:focus' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'icon_border',
				'selector'  => '{{WRAPPER}} .xpro-team-social-list .xpro-team-social-icon',
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'icon_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-team-social-list .xpro-team-social-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'layout!' => array( '13' ),
				),
			)
		);

		$this->add_control(
			'heading_social_wrapper',
			array(
				'label'     => __( 'Wrapper', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'layout' => array( '8', '9', '15' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'icon_wrapper_background',
				'label'     => __( 'Background', 'xpro-elementor-addons' ),
				'types'     => array( 'classic', 'gradient' ),
				'exclude'   => array( 'image' ),
				'selector'  => '{{WRAPPER}} .xpro-team-layout-8 .xpro-team-social-list,{{WRAPPER}} .xpro-team-layout-9 .xpro-team-social-list,{{WRAPPER}} .xpro-team-layout-15 .xpro-team-social-list',
				'condition' => array(
					'layout' => array( '8', '9', '15' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'icon_wrapper_border',
				'selector'  => '{{WRAPPER}} .xpro-team-layout-8 .xpro-team-social-list,{{WRAPPER}} .xpro-team-layout-9 .xpro-team-social-list,{{WRAPPER}} .xpro-team-layout-15 .xpro-team-social-list',
				'condition' => array(
					'layout' => array( '8', '9', '15' ),
				),
			)
		);

		$this->add_responsive_control(
			'icon_wrapper_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-team-layout-8 .xpro-team-social-list' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'layout' => array( '8' ),
				),
			)
		);

		$this->add_responsive_control(
			'icon_wrapper_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-team-layout-8 .xpro-team-social-list'  => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .xpro-team-layout-15 .xpro-team-social-list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .xpro-team-layout-9 .xpro-team-social-list'  => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'layout' => array( '8', '15', '9' ),
				),
			)
		);

		$this->add_responsive_control(
			'icon_wrapper_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-team-layout-8 .xpro-team-social-list' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'layout' => array( '8' ),
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

		require XPRO_ELEMENTOR_ADDONS_WIDGET . 'team/layout/frontend.php';
	}
}
