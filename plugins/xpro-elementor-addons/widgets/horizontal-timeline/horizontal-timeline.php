<?php

namespace XproElementorAddons\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
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
class Horizontal_Timeline extends Widget_Base {

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
		return 'xpro-horizontal-timeline';
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
		return __( 'Horizontal Timeline', 'xpro-elementor-addons' );
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
		return 'xi-horizontal-timeline xpro-widget-label';
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
		return array( 'xpro', 'horizontal', 'timeline', 'carousel' );
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
		return array( 'owl-carousel' );
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
		return array( 'owl-carousel' );
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
			'section_horizontal_timeline',
			array(
				'label' => __( 'General', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'date_media_type',
			array(
				'label'       => __( 'Date Media', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'none'   => array(
						'title' => __( 'None', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-ban',
					),
					'image'  => array(
						'title' => __( 'Image', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-image',
					),
					'custom' => array(
						'title' => __( 'Custom', 'xpro-elementor-addons' ),
						'icon'  => ' eicon-font',
					),
				),
				'default'     => 'custom',
				'toggle'      => false,
			)
		);

		$repeater->add_control(
			'date_image',
			array(
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'date_media_type' => 'image',
				),
				'dynamic'   => array(
					'active' => true,
				),
			)
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'date_image_thumbnail',
				'default'   => 'large',
				'separator' => 'none',
				'condition' => array(
					'date_media_type' => 'image',
				),
			)
		);

		$repeater->add_control(
			'title',
			array(
				'label'       => __( 'Title', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __( 'Project Title', 'xpro-elementor-addons' ),
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					'date_media_type' => 'custom',
				),
			)
		);

		$repeater->add_control(
			'date_custom',
			array(
				'label'     => __( 'Date', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => gmdate( 'Y-m-d', strtotime( '+ 1 day' ) ),
				'condition' => array(
					'date_media_type' => 'custom',
				),
			)
		);

		$repeater->add_control(
			'content_media_type',
			array(
				'label'       => __( 'Content Media', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'separator'   => 'before',
				'options'     => array(
					'none'  => array(
						'title' => __( 'None', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-ban',
					),
					'image' => array(
						'title' => __( 'Image', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-image',
					),
				),
				'default'     => 'image',
				'toggle'      => false,
			)
		);

		$repeater->add_control(
			'content_image',
			array(
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'content_media_type' => 'image',
				),
				'dynamic'   => array(
					'active' => true,
				),
			)
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'content_image_thumbnail',
				'default'   => 'large',
				'separator' => 'none',
				'condition' => array(
					'content_media_type' => 'image',
				),
			)
		);

		$repeater->add_control(
			'sub_title',
			array(
				'label'       => __( 'Title', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __( 'Heading', 'xpro-elementor-addons' ),
				'dynamic'     => array(
					'active' => true,
				),

			)
		);

		$repeater->add_control(
			'description',
			array(
				'label'       => __( 'Description', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => __( 'Type your description here', 'xpro-elementor-addons' ),
				'default'     => __( 'It is a long established fact that a reader will be distracted by the readable content.', 'xpro-elementor-addons' ),
			)
		);

		$repeater->add_control(
			'bullet_media_type',
			array(
				'label'       => __( ' Bullet Media', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'separator'   => 'before',
				'options'     => array(
					'icon'   => array(
						'title' => __( 'Icon', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-star-o',
					),
					'image'  => array(
						'title' => __( 'Image', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-image',
					),
					'custom' => array(
						'title' => __( 'Custom', 'xpro-elementor-addons' ),
						'icon'  => ' eicon-font',
					),
				),
				'default'     => 'icon',
				'toggle'      => false,
			)
		);

		$repeater->add_control(
			'icon',
			array(
				'type'      => Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-calendar-alt',
					'library' => 'fa-solid',
				),
				'condition' => array(
					'bullet_media_type' => 'icon',
				),
			)
		);

		$repeater->add_control(
			'image',
			array(
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'bullet_media_type' => 'image',
				),
				'dynamic'   => array(
					'active' => true,
				),
			)
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'bullet_image_thumbnail',
				'default'   => 'large',
				'separator' => 'none',
				'condition' => array(
					'bullet_media_type' => 'image',
				),
			)
		);

		$repeater->add_control(
			'custom',
			array(
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => '1',
				'dynamic'     => array(
					'active' => true,
				),
				'condition'   => array(
					'bullet_media_type' => 'custom',
				),
			)
		);

		$repeater->add_control(
			'inline_style',
			array(
				'label'        => esc_html__( 'Inline Style', 'xpro-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'xpro-elementor-addons' ),
				'label_off'    => esc_html__( 'Hide', 'xpro-elementor-addons' ),
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$repeater->start_controls_tabs( 'inline_bullet_media_icon' );

		$repeater->start_controls_tab(
			'inline_bullet_media_normal',
			array(
				'label'     => __( 'Normal', 'xpro-elementor-addons' ),
				'condition' => array(
					'inline_style' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'inline_date_bg',
			array(
				'label'     => __( 'Date Background', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .xpro-horizontal-timeline-dates' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'inline_style' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'inline_bullet_media_normal_color',
			array(
				'label'     => __( 'Bullet Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .xpro-horizontal-timeline-media > i'                                      => 'color: {{VALUE}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} .xpro-horizontal-timeline-media > svg'                                      => 'fill: {{VALUE}};',
					'{{WRAPPER}} {{CURRENT_ITEM}} .xpro-horizontal-timeline-media > .xpro-horizontal-timeline-media-custom' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'inline_style' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'inline_bullet_media_normal_bg_color',
			array(
				'label'     => __( 'Bullet Background', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .xpro-horizontal-timeline-media' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'inline_style' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'inline_bullet_media_separator_color',
			array(
				'label'     => __( 'Separator Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .xpro-horizontal-timeline-dates:before,
					{{WRAPPER}} {{CURRENT_ITEM}} .xpro-horizontal-timeline-content-inner:after,
					{{WRAPPER}} {{CURRENT_ITEM}} .xpro-horizontal-timeline-media:before,
					{{WRAPPER}} {{CURRENT_ITEM}} .xpro-horizontal-timeline-media:after' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'inline_style' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'inline_content_bg',
			array(
				'label'     => __( 'Content Background', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .xpro-horizontal-timeline-content-inner' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'inline_style' => 'yes',
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'inline_bullet_media_hover',
			array(
				'label'     => __( 'Hover', 'xpro-elementor-addons' ),
				'condition' => array(
					'inline_style' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'inline_hdate_bg',
			array(
				'label'     => __( 'Date Background', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}:hover .xpro-horizontal-timeline-dates' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'inline_style' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'inline_bullet_media_hover_color',
			array(
				'label'     => __( 'Bullet Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}:hover .xpro-horizontal-timeline-media > i,
					{{WRAPPER}} {{CURRENT_ITEM}}:hover .xpro-horizontal-timeline-media > svg,
					{{WRAPPER}} {{CURRENT_ITEM}}:hover .xpro-horizontal-timeline-media > .xpro-horizontal-timeline-media-custom' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'inline_style' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'inline_bullet_media_hover_bg_color',
			array(
				'label'     => __( 'Bullet Background', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}:hover .xpro-horizontal-timeline-media' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'inline_style' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'inline_bullet_media_separator_hcolor',
			array(
				'label'     => __( 'Separator Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}:hover .xpro-horizontal-timeline-dates:before,
					{{WRAPPER}} {{CURRENT_ITEM}}:hover .xpro-horizontal-timeline-content-inner:after,
					{{WRAPPER}} {{CURRENT_ITEM}}:hover .xpro-horizontal-timeline-media:before,
					{{WRAPPER}} {{CURRENT_ITEM}}:hover .xpro-horizontal-timeline-media:after' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'inline_style' => 'yes',
				),
			)
		);

		$repeater->add_control(
			'inline_hcontent_bg',
			array(
				'label'     => __( 'Content Background', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}:hover .xpro-horizontal-timeline-content-inner' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'inline_style' => 'yes',
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'horizontal_timeline_item',
			array(
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ sub_title }}}',
				'separator'   => 'after',
				'default'     => array(
					array(
						'sub_title'   => __( 'Step 1', 'xpro-elementor-addons' ),
						'description' => __( 'It is a long established fact that a reader will be distracted by the readable content.', 'xpro-elementor-addons' ),
					),
					array(
						'sub_title'   => __( 'Step 2', 'xpro-elementor-addons' ),
						'description' => __( 'It is a long established fact that a reader will be distracted by the readable content.', 'xpro-elementor-addons' ),
					),
					array(
						'sub_title'   => __( 'Step 3', 'xpro-elementor-addons' ),
						'description' => __( 'It is a long established fact that a reader will be distracted by the readable content.', 'xpro-elementor-addons' ),
					),
					array(
						'sub_title'   => __( 'Step 4', 'xpro-elementor-addons' ),
						'description' => __( 'It is a long established fact that a reader will be distracted by the readable content.', 'xpro-elementor-addons' ),
					),
				),
			)
		);

		$this->add_control(
			'direction',
			array(
				'label'          => __( 'Direction', 'xpro-elementor-addons' ),
				'type'           => Controls_Manager::CHOOSE,
				'default'        => 'col',
				'options'        => array(
					'col'         => array(
						'title' => __( 'Top', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-v-align-top',
					),
					'col-reverse' => array(
						'title' => __( 'Bottom', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'style_transfer' => true,
				'toggle'         => false,
			)
		);

		$this->add_control(
			'reverse',
			array(
				'label'              => esc_html__( 'Reverse', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_on'           => esc_html__( 'Show', 'xpro-elementor-addons' ),
				'label_off'          => esc_html__( 'Hide', 'xpro-elementor-addons' ),
				'return_value'       => 'yes',
				'default'            => 'yes',
				'frontend_available' => true,
			)
		);

		$this->add_responsive_control(
			'direction_space_between',
			array(
				'label'      => __( 'Space Between', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 200,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 10,
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-horizontal-timeline-item .xpro-horizontal-timeline-date,
					{{WRAPPER}} .xpro-horizontal-timeline-item .xpro-horizontal-timeline-content' => 'padding: 0 {{SIZE}}{{UNIT}} ;',
				),
			)
		);

		$this->add_responsive_control(
			'direction_space_bottom',
			array(
				'label'      => __( 'Space Bottom', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 200,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 50,
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-horizontal-timeline-col .xpro-horizontal-timeline-item'                                                                                                                 => 'grid-gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-horizontal-timeline-col .xpro-horizontal-timeline-dates:before,{{WRAPPER}} .xpro-horizontal-timeline-col .xpro-horizontal-timeline-content-inner:after'                 => 'height: calc({{SIZE}}{{UNIT}} + 50px);',
					'{{WRAPPER}} .xpro-horizontal-timeline-col-reverse .xpro-horizontal-timeline-item'                                                                                                         => 'grid-gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-horizontal-timeline-col-reverse .xpro-horizontal-timeline-dates:before,{{WRAPPER}} .xpro-horizontal-timeline-col-reverse .xpro-horizontal-timeline-content-inner:after' => 'height: calc({{SIZE}}{{UNIT}} + 50px);',
				),
			)
		);

		$this->end_controls_section();

		//Carousel Settings Tab
		$this->start_controls_section(
			'section_horiz_timeline_carousel',
			array(
				'label' => __( 'Settings', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_responsive_control(
			'item_per_row',
			array(
				'label'              => __( 'Items To Show', 'xpro-elementor-addons' ),
				'description'        => __( 'Adjust items to show in a row.', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::NUMBER,
				'placeholder'        => 2,
				'desktop_default'    => 3,
				'tablet_default'     => 2,
				'mobile_default'     => 1,
				'min'                => 1,
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'loop',
			array(
				'label'              => __( 'Loop', 'xpro-elementor-addons' ),
				'description'        => __( 'Duplicate last and first items to get loop illusion.', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'return_value'       => 'yes',
				'frontend_available' => true,
				'render_type'        => 'template',
			)
		);

		$this->add_control(
			'mouse_drag',
			array(
				'label'              => __( 'Mouse Drag', 'xpro-elementor-addons' ),
				'description'        => __( 'Mouse drag enabled.', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'return_value'       => 'yes',
				'default'            => 'yes',
				'frontend_available' => true,
				'render_type'        => 'template',
			)
		);

		$this->add_control(
			'rtl',
			array(
				'label'              => __( 'RTL', 'xpro-elementor-addons' ),
				'description'        => __( 'Change direction from Right to left.', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'return_value'       => 'yes',
				'frontend_available' => true,
				'render_type'        => 'template',
				'selectors'          => array(
					'{{WRAPPER}} .xpro-owl-theme.owl-carousel' => 'direction: rtl;',
				),
			)
		);

		$this->add_control(
			'auto_height',
			array(
				'label'              => __( 'Auto Height', 'xpro-elementor-addons' ),
				'description'        => __( 'Adaptive its height of the currently active item.', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'return_value'       => 'yes',
				'default'            => 'yes',
				'frontend_available' => true,
				'render_type'        => 'template',
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'              => __( 'Autoplay', 'xpro-elementor-addons' ),
				'description'        => __( 'To enable autoplay behaviour.', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'return_value'       => 'yes',
				'frontend_available' => true,
				'render_type'        => 'template',
			)
		);

		$this->add_control(
			'autoplay_timeout',
			array(
				'label'              => __( 'Autoplay Timeout', 'xpro-elementor-addons' ),
				'description'        => __( 'Autoplay interval timeout in seconds(s).', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => array( 'px' ),
				'default'            => array(
					'size' => 3,
				),
				'range'              => array(
					'px' => array(
						'min' => 1,
						'max' => 10,
					),
				),
				'frontend_available' => true,
				'render_type'        => 'template',
				'condition'          => array(
					'autoplay' => 'yes',
				),
			)
		);

		$this->add_control(
			'nav',
			array(
				'label'              => __( 'Show Nav', 'xpro-elementor-addons' ),
				'description'        => __( 'Show next/prev buttons.', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'return_value'       => 'yes',
				'frontend_available' => true,
				'render_type'        => 'template',
				'default'            => 'yes',
			)
		);

		$this->add_control(
			'dots',
			array(
				'label'              => __( 'Show Dots', 'xpro-elementor-addons' ),
				'description'        => __( 'Show dots navigation.', 'xpro-elementor-addons' ),
				'type'               => Controls_Manager::SWITCHER,
				'return_value'       => 'yes',
				'frontend_available' => true,
				'render_type'        => 'template',
			)
		);

		$this->end_controls_section();

		//Date & Time
		$this->start_controls_section(
			'section_media_style',
			array(
				'label' => __( 'Date', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'date_styling_tabs' );

		$this->start_controls_tab(
			'date_normal',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'media_media_background',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .xpro-horizontal-timeline-dates',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'media_box_shadow',
				'label'    => __( 'Box Shadow', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-horizontal-timeline-dates',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'date_hover',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'media_media_background_hover',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .xpro-horizontal-timeline-item:hover .xpro-horizontal-timeline-dates',
			)
		);

		$this->add_control(
			'media_border_hover_color',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-horizontal-timeline-item:hover .xpro-horizontal-timeline-dates' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'media_border',
				'label'    => __( 'Border', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-horizontal-timeline-dates',
			)
		);

		$this->add_responsive_control(
			'media_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-horizontal-timeline-dates' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'media_padding',
			array(
				'label'      => __( 'Padding', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-horizontal-timeline-dates' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'media_title_options',
			array(
				'label'     => __( 'Title', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'media_title_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-horizontal-timeline-title',
			)
		);

		$this->add_control(
			'media_title_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,

				'selectors' => array(
					'{{WRAPPER}} .xpro-horizontal-timeline-title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'media_title_hcolor',
			array(
				'label'     => __( 'Hover', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,

				'selectors' => array(
					'{{WRAPPER}} .xpro-horizontal-timeline-item:hover .xpro-horizontal-timeline-title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'media_title_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-horizontal-timeline-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'media_date_options',
			array(
				'label'     => __( 'Date', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'media_date_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-horizontal-timeline-time',
			)
		);

		$this->add_control(
			'media_date_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,

				'selectors' => array(
					'{{WRAPPER}} .xpro-horizontal-timeline-time,{{WRAPPER}} .xpro-horizontal-timeline-content-time' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'media_date_hcolor',
			array(
				'label'     => __( 'Hover', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,

				'selectors' => array(
					'{{WRAPPER}} .xpro-horizontal-timeline-item:hover .xpro-horizontal-timeline-time' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'media_date_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-horizontal-timeline-time' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'media_image_options',
			array(
				'label'     => __( 'Image', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'media_image_width',
			array(
				'label'      => __( 'Width', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 1,
						'max' => 1000,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 100,
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-horizontal-timeline-inner .xpro-horizontal-timeline-dates > img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'media_image_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-horizontal-timeline-inner .xpro-horizontal-timeline-dates > img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Bullet Media
		$this->start_controls_section(
			'section_bullet_media_style',
			array(
				'label' => __( 'Bullet', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'bullet_media_size',
			array(
				'label'      => __( 'Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-horizontal-timeline-media > i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-horizontal-timeline-media > img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-horizontal-timeline-media > svg' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
				),
			)
		);

		$this->add_responsive_control(
			'bullet_media_bg_size',
			array(
				'label'      => __( 'Background Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 1,
						'max' => 1000,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-horizontal-timeline-media' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'bullet_media_icon' );

		$this->start_controls_tab(
			'bullet_media_normal',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'bullet_media_normal_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-horizontal-timeline-media > i'                                      => 'color: {{VALUE}};',
					'{{WRAPPER}} .xpro-horizontal-timeline-media > svg'                                      => 'fill: {{VALUE}};',
					'{{WRAPPER}} .xpro-horizontal-timeline-media > .xpro-horizontal-timeline-media-custom' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'bullet_media_normal_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-horizontal-timeline-media' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'bullet_media_separator_color',
			array(
				'label'     => __( 'Separator Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'selectors' => array(
					'{{WRAPPER}} .xpro-horizontal-timeline-dates:before,
					{{WRAPPER}} .xpro-horizontal-timeline-content-inner:after,
					{{WRAPPER}} .xpro-horizontal-timeline-media:before,
					{{WRAPPER}} .xpro-horizontal-timeline-media:after' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'divider_line_color',
			array(
				'label'     => __( 'Divider Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'selectors' => array(
					'{{WRAPPER}} .xpro-horizontal-timeline-bullet-line' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'bullet_media_hover',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'bullet_media_hover_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-horizontal-timeline-item:hover .xpro-horizontal-timeline-media > i,
					{{WRAPPER}} .xpro-horizontal-timeline-item:hover .xpro-horizontal-timeline-media > svg,
					{{WRAPPER}} .xpro-horizontal-timeline-item:hover .xpro-horizontal-timeline-media > .xpro-horizontal-timeline-media-custom' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'bullet_media_hover_bg_color',
			array(
				'label'     => __( 'Background Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-horizontal-timeline-item:hover .xpro-horizontal-timeline-media' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'bullet_media_separator_hcolor',
			array(
				'label'     => __( 'Separator Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'selectors' => array(
					'{{WRAPPER}} .xpro-horizontal-timeline-item:hover .xpro-horizontal-timeline-dates:before,
					{{WRAPPER}} .xpro-horizontal-timeline-item:hover .xpro-horizontal-timeline-content-inner:after,
					{{WRAPPER}} .xpro-horizontal-timeline-item:hover .xpro-horizontal-timeline-media:before,
					{{WRAPPER}} .xpro-horizontal-timeline-item:hover .xpro-horizontal-timeline-media:after' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'bullet_media_hover_border_color',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-horizontal-timeline-item:hover .xpro-horizontal-timeline-media' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'bullet_media_border',
				'separator' => 'before',
				'label'     => __( 'Border', 'xpro-elementor-addons' ),
				'selector'  => '{{WRAPPER}} .xpro-horizontal-timeline-media',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'bullet_media_box_shadow',
				'label'    => __( 'Box Shadow', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-horizontal-timeline-media',
			)
		);

		$this->add_responsive_control(
			'bullet_media_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-horizontal-timeline-media' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'bullet_media_custom_options',
			array(
				'label'     => __( 'Custom', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'bullet_media_custom_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-horizontal-timeline-media > .xpro-horizontal-timeline-media-custom',
			)
		);

		$this->end_controls_section();

		//Content Styling
		$this->start_controls_section(
			'section_general_style_content',
			array(
				'label' => __( 'Content', 'xpro-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'content_alignment',
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
				'selectors' => array(
					'{{WRAPPER}} .xpro-horizontal-timeline-content-inner' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'content_width',
			array(
				'label'      => esc_html__( 'Width', 'xpro-elementor-addons' ),
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
					'{{WRAPPER}} .xpro-horizontal-timeline-content-inner' => 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'content_styling_tabs' );

		$this->start_controls_tab(
			'content_normal',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'content_background',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .xpro-horizontal-timeline-content-inner',
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'content_box_shadow',
				'label'    => __( 'Box Shadow', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-horizontal-timeline-content-inner',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'content_hover',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'content_background_hover',
				'label'    => __( 'Background', 'xpro-elementor-addons' ),
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .xpro-horizontal-timeline-item:hover .xpro-horizontal-timeline-content-inner',
			)
		);

		$this->add_control(
			'content_border_hover_color',
			array(
				'label'     => __( 'Border Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-horizontal-timeline-item:hover .xpro-horizontal-timeline-content-inner' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'content_border',
				'separator' => 'before',
				'label'     => __( 'Border', 'xpro-elementor-addons' ),
				'selector'  => '{{WRAPPER}} .xpro-horizontal-timeline-content-inner',
			)
		);

		$this->add_responsive_control(
			'content_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-horizontal-timeline-content-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .xpro-horizontal-timeline-content-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'content_image_heading',
			array(
				'label'     => __( 'Image', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'inline',
			array(
				'label'       => __( 'Layout', 'xpro-elementor-addons' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default'     => 'inline-block',
				'options'     => array(
					'inline-flex'  => array(
						'title' => __( 'Inline', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-editor-list-ul',
					),
					'inline-block' => array(
						'title' => __( 'Block', 'xpro-elementor-addons' ),
						'icon'  => 'eicon-ellipsis-h',
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .xpro-horizontal-timeline-content-inner' => 'display: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'content_media_size',
			array(
				'label'      => __( 'Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 1,
						'max' => 1000,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-horizontal-timeline-content-inner .xpro-horizontal-timeline-content-media > img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
				),

			)
		);

		$this->add_responsive_control(
			'content_media_space_between',
			array(
				'label'      => __( 'Space Between', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 10,
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-horizontal-timeline-content-inner'                                               => 'grid-gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-horizontal-timeline-content-inner .xpro-horizontal-timeline-content-media > img' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'content_media_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-horizontal-timeline-content-inner .xpro-horizontal-timeline-content-media > img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'content_title_heading',
			array(
				'label'     => __( 'Title', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'content_title_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-horizontal-timeline-sub-title',
			)
		);

		$this->add_control(
			'content_title_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-horizontal-timeline-sub-title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'content_title_hover',
			array(
				'label'     => __( 'Hover', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-horizontal-timeline-item:hover .xpro-horizontal-timeline-sub-title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'content_title_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-horizontal-timeline-sub-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'content_description_heading',
			array(
				'label'     => __( 'Description', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'content_desc_typography',
				'label'    => __( 'Typography', 'xpro-elementor-addons' ),
				'selector' => '{{WRAPPER}} .xpro-horizontal-timeline-text',
			)
		);

		$this->add_control(
			'content_desc_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-horizontal-timeline-text' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'content_desc_hover',
			array(
				'label'     => __( 'Hover', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-horizontal-timeline-item:hover .xpro-horizontal-timeline-text' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'content_desc_margin',
			array(
				'label'      => __( 'Margin', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-horizontal-timeline-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		//Nav Style
		$this->start_controls_section(
			'section_horizontal_timeline_nav_style',
			array(
				'label'     => __( 'Nav', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'nav' => 'yes',
				),
			)
		);

		$this->add_control(
			'nav_layout',
			array(
				'label'   => __( 'Layout', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'style-1' => __( 'Style 1', 'xpro-elementor-addons' ),
					'style-2' => __( 'Style 2', 'xpro-elementor-addons' ),
					'style-3' => __( 'Style 3', 'xpro-elementor-addons' ),
					'style-4' => __( 'Style 4', 'xpro-elementor-addons' ),
					'style-5' => __( 'Style 5', 'xpro-elementor-addons' ),
					'style-6' => __( 'Style 6', 'xpro-elementor-addons' ),
					'style-7' => __( 'Style 7', 'xpro-elementor-addons' ),
				),
				'default' => 'style-1',
			)
		);

		$this->add_responsive_control(
			'nav_size',
			array(
				'label'      => __( 'Icon Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 16,
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-owl-theme.owl-carousel .owl-nav button.owl-prev,
					 {{WRAPPER}} .xpro-owl-theme.owl-carousel .owl-nav button.owl-next' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'nav_bg_size',
			array(
				'label'      => __( 'Background Size', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 50,
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-owl-theme.owl-carousel .owl-nav button.owl-prev,
					 {{WRAPPER}} .xpro-owl-theme.owl-carousel .owl-nav button.owl-next' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'nav_offset_y',
			array(
				'label'      => __( 'Offset Y', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-owl-theme.owl-carousel .owl-nav button.owl-prev,
					{{WRAPPER}} .xpro-owl-theme.owl-carousel .owl-nav button.owl-next' => 'top: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'reverse!' => 'yes',
				),
			)
		);

		$this->start_controls_tabs(
			'news_ticker_nav_style_tabs'
		);

		$this->start_controls_tab(
			'news_ticker_nav_normal_tab',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'nav_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-owl-theme.owl-carousel .owl-nav button.owl-prev,
					{{WRAPPER}} .xpro-owl-theme.owl-carousel .owl-nav button.owl-next' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'nav_bg_color',
			array(
				'label'     => __( 'Background', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'selectors' => array(
					'{{WRAPPER}} .xpro-owl-theme.owl-carousel .owl-nav button.owl-prev,
					{{WRAPPER}} .xpro-owl-theme.owl-carousel .owl-nav button.owl-next' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'news_ticker_nav_hover_tab_style',
			array(
				'label' => __( 'Hover', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'nav_hover_color',
			array(
				'label'     => __( 'Color', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-owl-theme.owl-carousel .owl-nav button.owl-prev:hover,
					{{WRAPPER}} .xpro-owl-theme.owl-carousel .owl-nav button.owl-next' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'nav_hover_bg',
			array(
				'label'     => __( 'Background', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => false,
				'selectors' => array(
					'{{WRAPPER}} .xpro-owl-theme.owl-carousel .owl-nav button.owl-prev:hover,
					 {{WRAPPER}} .xpro-owl-theme.owl-carousel .owl-nav button.owl-next:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'nav_hover_border',
			array(
				'label'     => __( 'Border', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-owl-theme.owl-carousel .owl-nav button.owl-prev:hover,
					{{WRAPPER}} .xpro-owl-theme.owl-carousel .owl-nav button.owl-next:hover' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'nav_border',
				'label'     => __( 'Border', 'xpro-elementor-addons' ),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .xpro-owl-theme.owl-carousel .owl-nav button.owl-prev,
				{{WRAPPER}} .xpro-owl-theme.owl-carousel .owl-nav button.owl-next',
			)
		);

		$this->add_responsive_control(
			'nav_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-owl-theme.owl-carousel .owl-nav button.owl-prev, 
					{{WRAPPER}} .xpro-owl-theme.owl-carousel .owl-nav button.owl-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		//Dots Styling
		$this->start_controls_section(
			'timeline_dots_styling',
			array(
				'label'     => __( 'Dots', 'xpro-elementor-addons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'dots' => 'yes',
				),
			)
		);

		$this->add_control(
			'dots_layout',
			array(
				'label'   => __( 'Layout', 'xpro-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'style-1' => __( 'Style 1', 'xpro-elementor-addons' ),
					'style-2' => __( 'Style 2', 'xpro-elementor-addons' ),
					'style-3' => __( 'Style 3', 'xpro-elementor-addons' ),
				),
				'default' => 'style-1',
			)
		);

		$this->add_responsive_control(
			'dots_bg_height',
			array(
				'label'      => __( 'Height', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => 12,
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-owl-theme.owl-carousel .owl-dot' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'dots_bg_width',
			array(
				'label'      => __( 'Width', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => 12,
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-owl-theme.owl-carousel .owl-dot'  => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .xpro-owl-dots-horizontal-style-2.owl-carousel .owl-dot.active' => 'width: calc({{SIZE}}{{UNIT}} * 2);',
				),
			)
		);

		$this->add_responsive_control(
			'dots_space_between',
			array(
				'label'      => __( 'Space Between', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => 5,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 20,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-owl-theme.owl-carousel .owl-dot' => 'margin: 0 {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'dots_space',
			array(
				'label'      => __( 'Spacing', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'default'    => array(
					'size' => 20,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-owl-theme.owl-carousel .owl-dots' => 'bottom: -{{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->start_controls_tabs(
			'horiz_timeline_dots_style_tabs'
		);

		$this->start_controls_tab(
			'horiz_timeline_dots_normal_tab',
			array(
				'label' => __( 'Normal', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'dots_bg_color',
			array(
				'label'     => __( 'Background', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-owl-theme.owl-carousel .owl-dot' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'horiz_timeline_dots_active_tab_style',
			array(
				'label' => __( 'Active', 'xpro-elementor-addons' ),
			)
		);

		$this->add_control(
			'dots_hover_bg',
			array(
				'label'     => __( 'Background', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-owl-theme.owl-carousel .owl-dot.active' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'dots_hover_border',
			array(
				'label'     => __( 'Border', 'xpro-elementor-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .xpro-owl-theme.owl-carousel .owl-dot.active' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'dots_border',
				'label'     => __( 'Border', 'xpro-elementor-addons' ),
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .xpro-owl-theme.owl-carousel .owl-dot',
			)
		);

		$this->add_responsive_control(
			'dots_border_radius',
			array(
				'label'      => __( 'Border Radius', 'xpro-elementor-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .xpro-owl-theme.owl-carousel .owl-dot' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		require XPRO_ELEMENTOR_ADDONS_WIDGET . 'horizontal-timeline/layout/frontend.php';

	}

}
