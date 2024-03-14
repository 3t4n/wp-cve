<?php
/**
 * Team carousel
 */

namespace AbsoluteAddons\Widgets;

use AbsoluteAddons\Absp_Read_More_Button;
use AbsoluteAddons\Controls\Absp_Control_Styles;
use AbsoluteAddons\Absp_Slider_Controller;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use AbsoluteAddons\Absp_Widget;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @since 1.1.0
 */
class Absoluteaddons_Style_Team_Carousel extends Absp_Widget {

	use Absp_Slider_Controller;

	use Absp_Read_More_Button;

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_name() {
		return 'absolute-team-carousel';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Team Carousel', 'absolute-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'absp eicon-carousel';
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return [
			'absolute-addons-custom',
			'font-awesome',
			'absolute-addons-core',
			'absp-team-carousel',
			'absp-pro-team-carousel',
		];
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @return array Widget categories.
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_categories() {
		return [ 'absp-widgets' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function register_controls() {

		/**
		 * Fires after controllers are registered.
		 *
		 * @param Absoluteaddons_Style_Team $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */

		do_action_ref_array( $this->get_prefixed_hook( 'controllers/starts' ), [ & $this ] );
		$this->start_controls_section( 'section_content', [ 'label' => __( 'Template', 'absolute-addons' ) ] );

		$team = apply_filters( $this->get_prefixed_hook( 'styles' ), [
			'one'        => __( 'Style One', 'absolute-addons' ),
			'two-pro'    => __( 'Style Two Pro', 'absolute-addons' ),
			'three-pro'  => __( 'Style Three Pro', 'absolute-addons' ),
			'four-pro'   => __( 'Style Four Pro', 'absolute-addons' ),
			'five-pro'   => __( 'Style Five Pro', 'absolute-addons' ),
			'six'        => __( 'Style Six', 'absolute-addons' ),
			'seven'      => __( 'Style Seven', 'absolute-addons' ),
			'eight-pro'  => __( 'Style Eight Pro', 'absolute-addons' ),
			'nine'       => __( 'Style Nine ', 'absolute-addons' ),
			'ten-pro'    => __( 'Style Ten Pro', 'absolute-addons' ),
			'eleven-pro' => __( 'Style Eleven Pro', 'absolute-addons' ),
		] );

		$this->add_control(
			'absolute_team_carousel',
			array(
				'label'       => __( 'Team Carousel Style', 'absolute-addons' ),
				'label_block' => true,
				'type'        => Absp_Control_Styles::TYPE,
				'options'     => $team,
				'default'     => 'one',
			)
		);

		$this->init_pro_alert( [
			'four-pro',
			'five-pro',
			'ten-pro',
			'eleven-pro',
		] );

		$this->end_controls_section();

		$this->__information_content_controls();
		$this->render_slider_controller();

		$this->item_style_controls();
		$this->photo_style_controls();
		$this->name_title_bio_style_controls();
		$this->social_style_controls();
		if ( absp_has_pro() ) {
			$this->button();
		}

	}

	/**
	 * Register widget style controls
	 */
	protected function __information_content_controls() {

		$this->start_controls_section(
			'_section_info',
			[
				'label' => __( 'Information', 'absolute-addons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs( '_tab_members' );
		$repeater->start_controls_tab(
			'_tab_info',
			[
				'label' => __( 'Information', 'absolute-addons' ),
			]
		);

		$repeater->add_control(
			'image',
			[
				'label'   => __( 'Photo', 'absolute-addons' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'title',
			[
				'label'       => __( 'Name', 'absolute-addons' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Member Name', 'absolute-addons' ),
				'placeholder' => __( 'Type Member Name', 'absolute-addons' ),
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'job_title',
			[
				'label'       => __( 'designation', 'absolute-addons' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'CEO', 'absolute-addons' ),
				'placeholder' => __( 'Type Member designation', 'absolute-addons' ),
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'bio',
			[
				'label'       => __( 'Short Bio', 'absolute-addons' ),
				'type'        => Controls_Manager::TEXTAREA,
				'placeholder' => __( 'Write something amazing about the happy member', 'absolute-addons' ),
				'rows'        => 5,
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'show_contact',
			[
				'label'          => __( 'Show Contact', 'absolute-addons' ),
				'type'           => Controls_Manager::SWITCHER,
				'label_on'       => __( 'Show', 'absolute-addons' ),
				'label_off'      => __( 'Hide', 'absolute-addons' ),
				'return_value'   => 'yes',
				'default'        => 'yes',
				'style_transfer' => true,
			]
		);

		$contact_link_count = apply_filters( 'absp/widgets/team-carousel/contact_link_count', 2 );

		if ( $contact_link_count > 0 ) {
			for ( $i = 1; $i <= $contact_link_count; $i ++ ) {

				$repeater->add_control(
					'contact_' . $i . '_icon',
					[
						'label'     => sprintf(
						/* translators: %d Website Index (will display in this order) */
							__( 'Contact Icon #%d', 'absolute-addons' ), $i
						),
						'type'      => Controls_Manager::ICONS,
						'separator' => 'before',
						'condition' => [
							'show_contact' => 'yes',
						],
					]
				);

				$repeater->add_control(
					'contact_' . $i . '_label',
					[
						'label_block' => true,
						'type'        => Controls_Manager::TEXT,
						'label'       => sprintf(
						/* translators: %d Website Index (will display in this order) */
							__( 'Contact #%d', 'absolute-addons' ), $i
						),
						'placeholder' => __( 'Add your website name', 'absolute-addons' ),
						'condition'   => [
							'show_contact' => 'yes',
						],

					]
				);

				$repeater->add_control(
					'contact_' . $i . '_link_text',
					[
						'label_block' => true,
						'type'        => Controls_Manager::TEXT,
						'label'       => sprintf(
						/* translators: %d Website Index (will display in this order) */
							__( 'Contact Link Text #%d', 'absolute-addons' ), $i
						),
						'placeholder' => __( 'Add your website name', 'absolute-addons' ),
						'condition'   => [
							'show_contact' => 'yes',
						],
					]
				);
			}
		}

		$this->render_controller( 'read-more-controller', compact( 'repeater' ) );

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'_tab_social',
			[
				'label' => __( 'Links', 'absolute-addons' ),
			]
		);

		$repeater->add_control(
			'show_options',
			[
				'label'          => __( 'Show Links', 'absolute-addons' ),
				'type'           => Controls_Manager::SWITCHER,
				'label_on'       => __( 'Show', 'absolute-addons' ),
				'label_off'      => __( 'Hide', 'absolute-addons' ),
				'return_value'   => 'yes',
				'default'        => 'yes',
				'style_transfer' => true,
			]
		);

		$social_link_count = apply_filters( 'absp/widgets/team-carousel/website_link_count', 7 );

		if ( $social_link_count > 0 ) {
			for ( $i = 1; $i <= $social_link_count; $i ++ ) {

				$repeater->add_control(
					'website_' . $i . '_label',
					[
						'label_block' => true,
						'type'        => Controls_Manager::TEXT,
						'label'       => sprintf(
						/* translators: %d Website Index (will display in this order) */
							__( 'Website #%d', 'absolute-addons' ), $i
						),
						'placeholder' => __( 'Add your website name', 'absolute-addons' ),
						'condition'   => [
							'show_options' => 'yes',
						],
						'separator'   => 'before',
					]
				);

				$repeater->add_control(
					'website_' . $i . '_link',
					[
						'label_block' => true,
						'type'        => Controls_Manager::URL,
						'label'       => __( 'Link', 'absolute-addons' ),
						'placeholder' => __( 'Add your website link', 'absolute-addons' ),
						'condition'   => [ 'show_options' => 'yes' ],
					]
				);

				$repeater->add_control(
					'website_' . $i . '_icon',
					[
						'label'     => __( 'Icon', 'absolute-addons' ),
						'type'      => Controls_Manager::ICONS,
						'condition' => [ 'show_options' => 'yes' ],
					]
				);

				$repeater->add_control(
					'social_' . $i . '_icon_color',
					array(
						'label'     => esc_html__( 'Select Icon Color', 'absolute-addons' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .absp-team-carousel-links ul li.link-' . $i . ' > a' => 'color: {{VALUE}}',
						],
						'condition' => [
							'show_options' => 'yes',
						],
					)
				);

				$repeater->add_control(
					'social_' . $i . '_icon_bgcolor',
					array(
						'label'     => esc_html__( 'Background Color', 'absolute-addons' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .absp-team-carousel-links ul li.link-' . $i . ' > a' => 'background-color: {{VALUE}}',
						],
						'condition' => [
							'show_options' => 'yes',
						],
					)
				);

				$repeater->add_control(
					'social_icon_' . $i . '_color_hover',
					array(
						'label'     => esc_html__( 'Hover Color', 'absolute-addons' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .absp-team-carousel-links ul li.link-' . $i . ' > a:hover, {{WRAPPER}}.absp-team-carousel-links ul li.link-' . $i . ' > a:focus' => 'color: {{VALUE}}',
						],
						'condition' => [
							'show_options' => 'yes',
						],

					)
				);

				$repeater->add_control(
					'social_icon_' . $i . '_bgcolor_hover',
					array(
						'label'     => esc_html__( 'Hover Background Color', 'absolute-addons' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .absp-team-carousel-links ul .link-' . $i . ' > a:hover, {{WRAPPER}} .absp-team-carousel-links ul .link-' . $i . ' > a:focus' => 'background-color: {{VALUE}}',
						],
						'condition' => [
							'show_options' => 'yes',
						],

					)
				);


			}
		}

		$repeater->end_controls_tab();
		$repeater->end_controls_tabs();


		$labels = [
			[ __( 'Website', 'absolute-addons' ), 'link' ],
			[ __( 'Facebook', 'absolute-addons' ), 'facebook' ],
			[ __( 'Twitter', 'absolute-addons' ), 'twitter' ],
			[ __( 'Instagram', 'absolute-addons' ), 'instagram' ],
			[ __( 'GitHub', 'absolute-addons' ), 'github' ],
		];

		$link_defaults = [];

		foreach ( $labels as $idx => $data ) {
			$link_defaults[ "website_{$idx}_label" ] = $data[0];
			$link_defaults[ "website_{$idx}_link" ]  = [
				'url' => '#',
			];
			$link_defaults[ "website_{$idx}_icon" ]  = [
				'library' => 'solid',
				'value'   => 'fa fa-' . $data[1],
			];
		}

		// Names from https://goodbyejohndoe.com/
		$this->add_control(
			'team_members',
			[
				'show_label'  => false,
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}} - <sub>{{{job_title}}}</sub>',
				'default'     => [
					array_merge( [
						'title'     => __( 'Eleanor Fant', 'absolute-addons' ),
						'job_title' => __( 'Co Founder/CEO', 'absolute-addons' ),
					], $link_defaults ),
					array_merge( [
						'title'     => __( 'Brian Cumin', 'absolute-addons' ),
						'job_title' => __( 'System Engineer', 'absolute-addons' ),
					], $link_defaults ),
					array_merge( [
						'title'     => __( 'Parsley Montana', 'absolute-addons' ),
						'job_title' => __( 'UI/UX Designer', 'absolute-addons' ),
					], $link_defaults ),
					array_merge( [
						'title'     => __( 'Fig Nelson', 'absolute-addons' ),
						'job_title' => __( 'Web Developer', 'absolute-addons' ),
					], $link_defaults ),
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'thumbnail',
				'default'   => 'medium',
				'separator' => 'before',
			]
		);


		$this->add_responsive_control(
			'align',
			[
				'label'     => __( 'Alignment', 'absolute-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'    => [
						'title' => __( 'Left', 'absolute-addons' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'  => [
						'title' => __( 'Center', 'absolute-addons' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'   => [
						'title' => __( 'Right', 'absolute-addons' ),
						'icon'  => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justify', 'absolute-addons' ),
						'icon'  => 'fa fa-align-justify',
					],
				],
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}} !important',
				],
			]
		);

		$this->end_controls_section();


	}

	protected function item_style_controls() {
		$this->start_controls_section(
			'_section_style_item',
			[
				'label' => __( 'Carousel Item', 'absolute-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'item_padding',
			[
				'label'      => __( 'Padding', 'absolute-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .absp-team-carousel-item' => 'padding:  {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'item_border',
				'selector' => '{{WRAPPER}} .absp-team-carousel-item',
			]
		);

		$this->add_responsive_control(
			'item_border_radius',
			[
				'label'      => __( 'Border Radius', 'absolute-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .absp-team-carousel-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .absp-team-carousel-item img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'item_box_shadow',
				'selector' => '{{WRAPPER}} .absp-team-carousel-item',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'item_background',
				'types'    => [ 'classic', 'gradient' ],
				'exclude'  => [ 'classic' => 'image' ], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
				'selector' => '{{WRAPPER}} .absp-team-carousel-item',
			]
		);

		$this->end_controls_section();
	}

	protected function photo_style_controls() {

		$this->start_controls_section(
			'_section_style_image',
			[
				'label' => __( 'Photo', 'absolute-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
				'label'      => __( 'Width', 'absolute-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'%'  => [
						'min' => 20,
						'max' => 100,
					],
					'px' => [
						'min' => 100,
						'max' => 700,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .absp-team-carousel-item .absp-team-carousel-image' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_height',
			[
				'label'      => __( 'Height', 'absolute-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 100,
						'max' => 700,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .absp-team-carousel-item .absp-team-carousel-image' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_spacing',
			[
				'label'      => __( 'Bottom Spacing', 'absolute-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 80,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .absp-team-carousel-item .absp-team-carousel-image' => 'margin-bottom: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'image_padding',
			[
				'label'      => __( 'Padding', 'absolute-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .absp-team-carousel-item .absp-team-carousel-image > img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'image_border',
				'selector' => '{{WRAPPER}} .absp-team-carousel-item .absp-team-carousel-image > img',
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label'      => __( 'Border Radius', 'absolute-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .absp-team-carousel-item .absp-team-carousel-image > img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'image_box_shadow',
				'selector' => '{{WRAPPER}}.absp-team-carousel-item .absp-team-carousel-image > img',
			]
		);

		$this->end_controls_section();

	}

	protected function name_title_bio_style_controls() {
		$this->start_controls_section(
			'_section_style_content',
			[
				'label' => __( 'Name, Designation & Bio', 'absolute-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'_heading_title',
			[
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Name', 'absolute-addons' ),
				'separator' => 'before',
			]
		);


		$this->start_controls_tabs(
			'name_tabs'
		);

		$this->start_controls_tab(
			'name_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'absolute-addons' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'background_title',
				'label'    => esc_html__( 'Background', 'absolute-addons' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .absp-team-carousel-item .absp-team-carousel-title h2',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => __( 'Text Color', 'absolute-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .absp-team-carousel-item .absp-team-carousel-title h2' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .absp-team-carousel-item .absp-team-carousel-title h2',

			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'title_text_shadow',
				'selector' => '{{WRAPPER}} .absp-team-carousel-item .absp-team-carousel-title h2',
			]
		);

		$this->add_responsive_control(
			'title_border_radius',
			[
				'label'      => __( 'Border Radius', 'absolute-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .absp-team-carousel-item .absp-team-carousel-title h2' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_padding',
			[
				'label'      => __( 'Padding', 'absolute-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .absp-team-carousel-item .absp-team-carousel-title h2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label'      => __( 'Margin', 'absolute-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .absp-team-carousel-item .absp-team-carousel-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'name_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'absolute-addons' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'background_title_hover',
				'label'    => esc_html__( 'Background', 'absolute-addons' ),
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .absp-team-carousel-item:hover .absp-team-carousel-title h2',
			]
		);

		$this->add_control(
			'title_color_hover',
			[
				'label'     => __( 'Text Color', 'absolute-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .absp-team-carousel-item:hover .absp-team-carousel-title h2' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography_hover',
				'selector' => '{{WRAPPER}} .absp-team-carousel-item:hover .absp-team-carousel-title h2',

			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'title_text_shadow_hover',
				'selector' => '{{WRAPPER}} .absp-team-carousel-item:hover .absp-team-carousel-title h2',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'_heading_job_title',
			[
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Designation', 'absolute-addons' ),
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs(
			'designation_style_tabs'
		);

		$this->start_controls_tab(
			'designation_style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'absolute-addons' ),
			]
		);

		$this->add_control(
			'job_title_color',
			[
				'label'     => __( 'Text Color', 'absolute-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .absp-team-carousel .absp-team-carousel-designation' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'job_title_typography',
				'selector' => '{{WRAPPER}} .absp-team-carousel .absp-team-carousel-designation',

			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'job_title_text_shadow',
				'selector' => '{{WRAPPER}} .absp-team-carousel .absp-team-carousel-designation',
			]
		);

		$this->add_responsive_control(
			'job_title_padding',
			[
				'label'      => __( 'Content Padding', 'absolute-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .absp-team-carousel .absp-team-carousel-designation' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'job_title_margin',
			[
				'label'      => __( 'Content Margin', 'absolute-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .absp-team-carousel .absp-team-carousel-designation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_normal_tab',
			[
				'label' => esc_html__( 'Hover', 'absolute-addons' ),
			]
		);

		$this->add_control(
			'job_title_color_hover',
			[
				'label'     => __( 'Text Color', 'absolute-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .absp-team-carousel:hover .absp-team-carousel-designation' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'job_title_typography_hover',
				'selector' => '{{WRAPPER}} .absp-team-carousel:hover .absp-team-carousel-designation',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'job_title_text_shadow_hover',
				'selector' => '{{WRAPPER}} .absp-team-carousel:hover .absp-team-carousel-designation',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'_heading_content',
			[
				'type'      => Controls_Manager::HEADING,
				'label'     => __( 'Content', 'absolute-addons' ),
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs(
			'content_style_tabs'
		);

		$this->start_controls_tab(
			'content_style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'absolute-addons' ),
			]
		);

		$this->add_control(
			'content_color',
			[
				'label'     => __( 'Text Color', 'absolute-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .absp-team-carousel .absp-team-carousel-content' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography',
				'selector' => '{{WRAPPER}} .absp-team-carousel .absp-team-carousel-content',

			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'content_text_shadow',
				'selector' => '{{WRAPPER}} .absp-team-carousel .absp-team-carousel-content',
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label'      => __( 'Content Padding', 'absolute-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .absp-team-carousel .absp-team-carousel-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_margin',
			[
				'label'      => __( 'Content Margin', 'absolute-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .absp-team-carousel .absp-team-carousel-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'content_style_hover_tab',
			[
				'label' => esc_html__( 'hover', 'absolute-addons' ),
			]
		);

		$this->add_control(
			'content_color_hover',
			[
				'label'     => __( 'Text Color', 'absolute-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .absp-team-carousel:hover .absp-team-carousel-content' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typography_hover',
				'selector' => '{{WRAPPER}} .absp-team-carousel:hover .absp-team-carousel-content',

			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'content_text_shadow_hover',
				'selector' => '{{WRAPPER}} .absp-team-carousel:hover .absp-team-carousel-content',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	protected function social_style_controls() {

		$this->start_controls_section(
			'_section_style_social',
			[
				'label' => __( 'Social Icons', 'absolute-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'social_icon_rotate',
			[
				'label'      => esc_html__( 'Social Icon Rotation', 'absolute-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'range'      => [
					'deg' => [
						'min' => 0,
						'max' => 380,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .absp-team-carousel-links .list-inline li a'   => 'transform: rotate({{SIZE}}deg);',
					'{{WRAPPER}} .absp-team-carousel-links .list-inline li a i' => 'transform: rotate(-{{SIZE}}deg);',
				],
			]
		);

		$this->add_responsive_control(
			'links_spacing',
			[
				'label'      => __( 'Right Spacing', 'absolute-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .absp-team-carousel-links .list-inline li:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'links_padding',
			[
				'label'      => __( 'Padding', 'absolute-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .absp-team-carousel-links ul li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'links_paddingul',
			[
				'label'      => __( 'Area Padding', 'absolute-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .absp-team-carousel-links ul' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'links_icon_size',
			[
				'label'      => __( 'Icon Size', 'absolute-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .absp-team-carousel-links ul li > a' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'links_border',
				'selector' => '{{WRAPPER}} .absp-team-carousel-links ul li > a',
			]
		);

		$this->add_responsive_control(
			'links_border_radius',
			[
				'label'      => __( 'Border Radius', 'absolute-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .absp-team-carousel-links ul li > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();


	}

	protected function button() {

		$this->start_controls_section(
			'section_name_team_button',
			array(
				'label' => esc_html__( 'Button', 'absolute-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->start_controls_tabs(
			'section_name_team_button_tabs'
		);
		$this->start_controls_tab(
			'team_social_normal_tab',
			[
				'label' => __( 'Normal', 'absolute-addons' ),
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'button_box_shadow',
				'label'    => __( 'Box Shadow', 'absolute-addons' ),
				'selector' => '{{WRAPPER}} .absp-team-carousel .absp-team-carousel-button a',
			]
		);
		$this->add_group_control(
			Group_Control_Typography:: get_type(),
			array(
				'label'    => esc_html__( 'Button Typography', 'absolute-addons' ),
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .absp-team-carousel .absp-team-carousel-button a',
			)
		);
		$this->add_control(
			'section_style_team_button_text_color',
			array(
				'label'     => esc_html__( 'Button Text Color', 'absolute-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .absp-team-carousel .absp-team-carousel-button a' => 'color:{{VALUE}}',
				],
			)
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'section_style_team_button_background',
				'label'    => __( 'Background', 'absolute-addons' ),
				'types'    => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .absp-team-carousel .absp-team-carousel-button a',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'button_border',
				'label'    => __( 'Body Border', 'absolute-addons' ),
				'selector' => '{{WRAPPER}} .absp-team-carousel .absp-team-carousel-button a',
			]
		);
		$this->add_responsive_control(
			'button_section_padding',
			[
				'label'      => esc_html__( 'Padding', 'absolute-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .absp-team-carousel .absp-team-carousel-button a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'button_section_margin',
			[
				'label'      => esc_html__( 'Margin', 'absolute-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .absp-team-carousel .absp-team-carousel-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();

		//Hover Design
		$this->start_controls_tab(
			'team_social_hover_tab',
			[
				'label' => __( 'Hover', 'absolute-addons' ),
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'button_box_shadow_hover',
				'label'    => __( 'Box Shadow', 'absolute-addons' ),
				'selector' => '{{WRAPPER}} .absp-team-carousel .absp-team-carousel-button a:hover',
			]
		);
		$this->add_group_control(
			Group_Control_Typography:: get_type(),
			array(
				'label'    => esc_html__( 'Button  Typography', 'absolute-addons' ),
				'name'     => 'button_typography_hover',
				'selector' => '{{WRAPPER}} .absp-team-carousel .absp-team-carousel-button a:hover',
			)
		);
		$this->add_control(
			'button_text_color_hover',
			array(
				'label'     => esc_html__( 'Button Hover Color', 'absolute-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .absp-team-carousel .absp-team-carousel-button a:hover' => 'color:{{VALUE}}',
				],
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'section_style_team_button_background_hover',
				'label'    => __( 'Background', 'absolute-addons' ),
				'types'    => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .absp-team-carousel .absp-team-carousel-button a:hover',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'button_border_hover',
				'label'    => __( 'Body Border', 'absolute-addons' ),
				'selector' => '{{WRAPPER}} .absp-team-carousel .absp-team-carousel-button a:hover',
			]
		);
		$this->add_responsive_control(
			'button_section_padding_hover',
			[
				'label'      => esc_html__( 'Padding', 'absolute-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .absp-team-carousel .absp-team-carousel-button a:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'button_section_margin_hover',
			[
				'label'      => esc_html__( 'Margin', 'absolute-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .absp-team-carousel .absp-team-carousel-button:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();
//


		if ( ! is_array( $settings['team_members'] ) ) {
			return;
		}

		$this->add_render_attribute( [
			'absp_slider' => [
				'class' => 'absp-swiper-wrapper swiper-container',
			],
		] );
		$this->add_render_attribute( [ 'absp_slider' => $this->get_slider_attributes( $settings ) ] );

		$style = $settings['absolute_team_carousel'];
		?>

		<div class="absp-wrapper absp-widget">
			<div class="absp-wrapper-inside">
				<div class="absp-wrapper-content">
					<!-- absp-team-carousel -->
					<div class="absp-team-carousel element-<?php echo esc_attr( $style ); ?>">
						<div class="absp-team-slider absp-team-slider-<?php echo esc_attr( $style ); ?>">
							<div <?php $this->print_render_attribute_string( 'absp_slider' ); ?>>
								<div class="swiper-wrapper">
									<?php
									foreach ( $settings['team_members'] as $index => $member ) {
										$title = $this->get_repeater_setting_key( 'title', 'members', $index );
										$this->add_render_attribute( $title, 'class', 'absp-team-carousel-title' );

										$job_title = $this->get_repeater_setting_key( 'job_title', 'members', $index );
										$this->add_render_attribute( $job_title, 'class', 'absp-team-carousel-designation' );

										$bio = $this->get_repeater_setting_key( 'bio', 'members', $index );
										$this->add_render_attribute( $bio, 'class', 'absp-team-carousel-content' );

										$image = wp_get_attachment_image_url( $member['image']['id'], $settings['thumbnail_size'] );
										if ( ! $image ) {
											$image = $member['image']['url'];
										}
										$this->render_template( $settings['absolute_team_carousel'], compact( 'title', 'image', 'job_title', 'bio', 'member', 'index' ) );
									}
									?>
								</div>
							</div>
							<?php $this->slider_nav( $settings ); ?>
						</div>
					</div>
					<!-- absp-team-carousel -->
				</div>
			</div>
		</div>
		<?php
	}

	protected function render_links( $member ) {
		if ( 'yes' === $member['show_options'] ) { ?>
			<div class="absp-team-carousel-links">
				<ul class="list-unstyled list-inline">
					<?php
					$social_link_count = apply_filters( 'absp/widgets/team-carousel/website_link_count', 7 );
					if ( $social_link_count > 0 ) {
						for ( $i = 1; $i <= $social_link_count; $i ++ ) {
							if ( ! empty( $member[ 'website_' . $i . '_label' ] ) && ! empty ( $member[ 'website_' . $i . '_link' ] ) ) {
								$icon         = isset( $member[ 'website_' . $i . '_icon' ]['value'] ) && ! empty( $member[ 'website_' . $i . '_icon' ] ['value'] );
								$title_before = '';
								$title_after  = '';
								$this->add_link_attributes( 'team-link-' . $i, $member[ 'website_' . $i . '_link' ], true );
								?>
								<li class="link-<?php echo esc_attr( $i ); ?>">
									<a <?php $this->print_attribute( 'team-link-' . $i ); ?> >
										<?php
										if ( $icon ) {
											$title_before = '<span class="sr-only">';
											$title_after  = '</span>';
											$this->render_icon( 'website_' . $i . '_icon', '', $member );
										}
										absp_render_title( $member[ 'website_' . $i . '_label' ], $title_before, $title_after ); ?>
									</a>
								</li>
								<?php
							}
						}
					}
					?>
				</ul>
			</div>
		<?php }
	}

	protected function render_short_bio( $member ) {
		if ( ! empty( $member['bio'] ) ) { ?>
			<div class="absp-team-carousel-content"><?php absp_render_content( $member['bio'] ); ?></div>
			<?php
		}
	}

	protected function render_member_contact( $member ) {
		if ( 'yes' === $member['show_contact'] ) { ?>
			<div class="absp-team-contact-links mt-2">
				<ul class="list-unstyled list-block">
					<?php
					$contact_link_count = apply_filters( 'absp/widgets/team-carousel/contact_link_count', 2 );
					if ( $contact_link_count > 0 ) {
						for ( $i = 1; $i <= $contact_link_count; $i ++ ) {
							if ( ! empty( $member[ 'contact_' . $i . '_label' ] ) || ! empty ( $member[ 'contact_' . $i . '_link_text' ] ) ) {
								$icon         = isset( $member[ 'contact_' . $i . '_icon' ]['value'] ) && ! empty( $member[ 'contact_' . $i . '_icon' ] ['value'] );
								$title_before = '';
								$title_after  = '';
								$this->add_attribute( 'team-contact-link-' . $i, $member[ 'contact_' . $i . '_link_text' ], '', true );
								?>
								<li class="contact-link mb-1 contact-<?php echo esc_attr( $i ); ?>">
									<span><?php
										if ( $icon ) {
											$title_before = '<span class="sr-only">';
											$title_after  = '</span>';
											$this->render_icon( 'contact_' . $i . '_icon', '', $member );
										}
										absp_render_title( $member[ 'contact_' . $i . '_label' ], $title_before, $title_after );
										?></span>
									<span class="separator">:</span>
									<span><?php absp_render_title( $member[ 'contact_' . $i . '_link_text' ] ); ?></span>
								</li>
								<?php
							}
						}
					}
					?>
				</ul>
			</div>
		<?php }
	}

	protected function render_button( $member ) {
		if ( $this->maybe_render_read_more( $member ) ) {
			echo '<div class="absp-team-carousel-button">';
			$this->render_read_more( '', $member, [], true );
			echo '</div>';
		}
	}

}
