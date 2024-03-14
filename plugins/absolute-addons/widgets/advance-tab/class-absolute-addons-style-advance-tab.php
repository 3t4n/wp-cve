<?php

namespace AbsoluteAddons\Widgets;

use AbsoluteAddons\Absp_Read_More_Button;
use AbsoluteAddons\Absp_Widget;
use AbsoluteAddons\Controls\Absp_Control_Styles;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @since 1.1.0
 */
class Absoluteaddons_Style_Advance_Tab extends Absp_Widget {

	use Absp_Read_More_Button;

	protected $current_style;

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
		return 'absolute-advance-tab';
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
		return __( 'Advance Tab', 'absolute-addons' );
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
		return 'absp eicon-tabs';
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
			'ico-font',
			'absp-advance-tab',
			'absp-pro-advance-tab',
		];
	}

	/**
	 * Requires js files.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return [
			'absolute-addons-advance-tab',
			'jquery.beefup',
			'responsive-menu',
			'absp-advance-tab',
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
		 * @param Absoluteaddons_Style_Advance_Tab $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/starts' ), [ & $this ] );

		$this->start_controls_section(
			'advance_tab_section_content',
			[
				'label' => __( 'Template Style', 'absolute-addons' ),
			]
		);

		$advance_tab = apply_filters( $this->get_prefixed_hook( 'styles' ), [
			'one'       => __( 'Style One', 'absolute-addons' ),
			'two-pro'   => __( 'Style Two (Pro)', 'absolute-addons' ),
			'three-pro' => __( 'Style Three(Pro)', 'absolute-addons' ),
			'four-pro'  => __( 'Style Four (Pro)', 'absolute-addons' ),
			'five-pro'  => __( 'Style Five (Pro)', 'absolute-addons' ),
			'six-pro'   => __( 'Style Six (Pro)', 'absolute-addons' ),
			'seven-pro' => __( 'Style Seven (Pro)', 'absolute-addons' ),
			'eight'     => __( 'Style Eight', 'absolute-addons' ),
			'nine'      => __( 'Style Nine', 'absolute-addons' ),
			'ten-pro'   => __( 'Style Ten (Pro)', 'absolute-addons' ),
		] );

		$pro_styles = [
			'two-pro',
			'three-pro',
			'four-pro',
			'five-pro',
			'six-pro',
			'seven-pro',
			'ten-pro',
		];

		$this->add_control(
			'advance_tab',
			[
				'label'       => __( 'Advance Tab Style', 'absolute-addons' ),
				'label_block' => true,
				'type'        => Absp_Control_Styles::TYPE,
				'options'     => $advance_tab,
				'default'     => 'one',
			]
		);

		$this->init_pro_alert( $pro_styles );

		$this->add_control(
			'view',
			[
				'label'   => __( 'View', 'absolute-addons' ),
				'type'    => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this->add_control(
			'absp_tab_position',
			[
				'label'        => __( 'Position', 'absolute-addons' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'horizontal',
				'options'      => [
					'horizontal' => __( 'Horizontal', 'absolute-addons' ),
					'vertical'   => __( 'Vertical', 'absolute-addons' ),
				],
				'prefix_class' => 'absp-tabs-view-',
				'separator'    => 'before',
			]
		);

		$this->add_control(
			'tabs_align_horizontal',
			[
				'label'        => __( 'Alignment', 'absolute-addons' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => [
					''        => [
						'title' => __( 'Start', 'absolute-addons' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center'  => [
						'title' => __( 'Center', 'absolute-addons' ),
						'icon'  => 'eicon-h-align-center',
					],
					'end'     => [
						'title' => __( 'End', 'absolute-addons' ),
						'icon'  => 'eicon-h-align-right',
					],
					'stretch' => [
						'title' => __( 'Justified', 'absolute-addons' ),
						'icon'  => 'eicon-h-align-stretch',
					],
				],
				'prefix_class' => 'absp-tabs-alignment-',
				'condition'    => [
					'absp_tab_position' => 'horizontal',
				],
			]
		);

		$this->add_control(
			'tabs_align_vertical',
			[
				'label'        => __( 'Alignment', 'absolute-addons' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => [
					''        => [
						'title' => __( 'Start', 'absolute-addons' ),
						'icon'  => 'eicon-v-align-top',
					],
					'center'  => [
						'title' => __( 'Center', 'absolute-addons' ),
						'icon'  => 'eicon-v-align-middle',
					],
					'end'     => [
						'title' => __( 'End', 'absolute-addons' ),
						'icon'  => 'eicon-v-align-bottom',
					],
					'stretch' => [
						'title' => __( 'Justified', 'absolute-addons' ),
						'icon'  => 'eicon-v-align-stretch',
					],
				],
				'prefix_class' => 'absp-tabs-alignment-',
				'condition'    => [
					'absp_tab_position' => 'vertical',
				],
			]
		);

		$this->add_control(
			'title_html_tag',
			[
				'label'     => __( 'Content Title HTML Tag', 'absolute-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'h1'  => 'H1',
					'h2'  => 'H2',
					'h3'  => 'H3',
					'h4'  => 'H4',
					'h5'  => 'H5',
					'h6'  => 'H6',
					'div' => 'div',
				],
				'default'   => 'h3',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->content_section( 'one' );
		$this->content_section( 'two' );
		$this->content_section( 'three' );
		$this->content_section( 'four' );
		$this->content_section( 'five' );
		$this->content_section( 'six' );
		$this->content_section( 'seven' );
		$this->content_section( 'eight' );
		$this->content_section( 'nine' );
		$this->content_section( 'ten' );

		$this->render_controller( 'template-advance-tab-style' );

		/**
		 * Fires after controllers are registered.
		 *
		 * @param Absoluteaddons_Style_Advance_Tab $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/ends' ), [ & $this ] );
	}

	/**
	 * Render content section controllers.
	 *
	 * @param string $style
	 *
	 * @todo merge from other repeater, load from pro.
	 *
	 */
	private function content_section( $style = '' ) {
		$this->start_controls_section(
			'advance_tab_section_' . $style,
			[
				'label'     => __( 'Content Section', 'absolute-addons' ),
				'condition' => [
					'advance_tab' => $style,
				],
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'absp_tab_title',
			[
				'label'       => __( 'Tab Title', 'absolute-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Tab Title', 'absolute-addons' ),
				'placeholder' => __( 'Tab Title', 'absolute-addons' ),
				'label_block' => true,
				'dynamic'     => [ 'active' => true ],
			]
		);

		if ( in_array( $style, [ 'five', 'six', 'seven', 'eight', 'ten' ] ) ) {
			$repeater->add_control(
				'tab_icon_select',
				[
					'label'     => __( 'Show Tab Icon', 'absolute-addons' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => [
						'true'  => __( 'Yes', 'absolute-addons' ),
						'false' => __( 'No', 'absolute-addons' ),
					],
					'default'   => 'true',
					'separator' => 'before',
				]
			);

			$repeater->add_control(
				'tab_icon',
				[
					'label'            => __( 'Tab Icon', 'absolute-addons' ),
					'type'             => Controls_Manager::ICONS,
					'fa4compatibility' => 'absolute-addons',
					'default'          => [
						'value'   => 'fas fa-camera',
						'library' => 'solid',
					],
					'condition'        => [
						'tab_icon_select' => 'true',
					],
				]
			);
		}

		if ( in_array( $style, [ 'one', 'two', 'five', 'eight' ] ) ) {
			$repeater->add_group_control(
				Group_Control_Image_Size::get_type(),
				[
					'name'      => 'thumbnail',
					'exclude'   => [ 'custom' ], // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
					'separator' => 'none',
				]
			);
		}

		if ( in_array( $style, [ 'one', 'five' ] ) ) {
			$repeater->add_control(
				'advance_tab_gallery',
				[
					'label'      => __( 'Add Gallery', 'absolute-addons' ),
					'type'       => Controls_Manager::GALLERY,
					'show_label' => false,
					'dynamic'    => [
						'active' => true,
					],
				]
			);

			$repeater->add_control(
				'caption_source',
				[
					'label'   => __( 'Caption', 'absolute-addons' ),
					'type'    => Controls_Manager::SELECT,
					'options' => [
						'none'       => __( 'None', 'absolute-addons' ),
						'attachment' => __( 'Attachment Caption', 'absolute-addons' ),
						'custom'     => __( 'Custom Caption', 'absolute-addons' ),
					],
					'default' => 'none',
				]
			);

			$repeater->add_control(
				'caption',
				[
					'label'       => __( 'Custom Caption', 'absolute-addons' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'placeholder' => __( 'Enter your image caption', 'absolute-addons' ),
					'condition'   => [
						'caption_source' => 'custom',
					],
					'dynamic'     => [
						'active' => true,
					],
				]
			);

			$gallery_columns = range( 1, 4 );

			$repeater->add_control(
				'gallery_columns',
				[
					'label'   => __( 'Columns', 'absolute-addons' ),
					'type'    => Controls_Manager::SELECT,
					'options' => array_combine( $gallery_columns, $gallery_columns ),
					'default' => 2,
				]
			);
		}

		if ( in_array( $style, [ 'two', 'four', 'five', 'six', 'eight', 'nine' ] ) ) {
			$repeater->add_control(
				'tab_image',
				[
					'label'   => __( 'Add Image', 'absolute-addons' ),
					'type'    => Controls_Manager::MEDIA,
					'default' => [
						'url' => Utils::get_placeholder_image_src(),
					],
				]
			);

		}

		if ( in_array( $style, [ 'two', 'three', 'five', 'six', 'seven', 'ten' ] ) ) {
			$repeater->add_control(
				'absp_content_sub_title',
				[
					'label'       => __( 'Content Sub Title', 'absolute-addons' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => __( 'Content Sub Title', 'absolute-addons' ),
					'placeholder' => __( 'Content Sub Title', 'absolute-addons' ),
					'label_block' => true,
					'dynamic'     => [
						'active' => true,
					],
				]
			);
		}

		$repeater->add_control(
			'absp_content_title',
			[
				'label'       => __( 'Content Title', 'absolute-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Content Title', 'absolute-addons' ),
				'placeholder' => __( 'Content Title', 'absolute-addons' ),
				'label_block' => true,
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'absp_tab_content',
			[
				'label'       => __( 'Content', 'absolute-addons' ),
				'default'     => __( 'Tab Content', 'absolute-addons' ),
				'placeholder' => __( 'Tab Content', 'absolute-addons' ),
				'type'        => Controls_Manager::WYSIWYG,
			]
		);

		if ( in_array( $style, [ 'one', 'four', 'seven', 'eight', 'nine' ] ) ) {
			$this->render_read_more_control( $repeater );
		}

		if ( $style === 'nine' ){

			$repeater->start_controls_tabs( 'tab_normal_style' );

			$repeater->start_controls_tab(
				'advance_tab_normal',
				[
					'label' => esc_html__( 'Normal', 'absolute-addons' ),
				]
			);

			$repeater->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name'           => 'advance_tab_title_background',
					'fields_options' => [
						'background' => [
							'label' => esc_html__( 'Title Background', 'absolute-addons' ),
						],
					],
					'types'          => [ 'classic', 'gradient' ],
					'selector'       => '{{WRAPPER}}  .absp-advance-tab .absp-tab-container .absp-nav-tab li{{CURRENT_ITEM}} a',
				]
			);

			$repeater->add_control(
				'advance_tab_title_color',
				[
					'label'     => esc_html__( 'Title Color', 'absolute-addons' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li{{CURRENT_ITEM}} a' => 'color: {{VALUE}}',
					],
				]
			);

			$repeater->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name'     => 'advance_tab_title_box_shadow',
					'label'    => esc_html__( 'Box Shadow', 'absolute-addons' ),
					'selector' => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li{{CURRENT_ITEM}} a',
				]
			);

			$repeater->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'     => 'advance_tab_title_border',
					'label'    => esc_html__( 'Border', 'absolute-addons' ),
					'selector' => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li{{CURRENT_ITEM}} a',
				]
			);

			$repeater->add_responsive_control(
				'advance_tab_title_border_radius',
				[
					'label'      => esc_html__( 'Advance Tab Border Radius', 'absolute-addons' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li{{CURRENT_ITEM}} a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$repeater->end_controls_tab();

			$repeater->start_controls_tab(
				'advance_tab_hover',
				[
					'label' => esc_html__( 'Hover', 'absolute-addons' ),
				]
			);

			$repeater->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name'           => 'advance_tab_title_background_hover',
					'fields_options' => [
						'background' => [
							'label' => esc_html__( 'Background', 'absolute-addons' ),
						],
					],
					'types'          => [ 'classic', 'gradient' ],
					'selector'       => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li{{CURRENT_ITEM}} a:hover',
				]
			);

			$repeater->add_control(
				'advance_tab_title_color_hover',
				[
					'label'     => esc_html__( 'Title Color', 'absolute-addons' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li{{CURRENT_ITEM}} a:hover' => 'color: {{VALUE}}',
					],
				]
			);

			$repeater->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name'     => 'advance_tab_title_box_shadow_hover',
					'label'    => esc_html__( 'Box Shadow', 'absolute-addons' ),
					'selector' => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li{{CURRENT_ITEM}} a:hover',
				]
			);

			$repeater->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'     => 'advance_tab_title_border_hover_hover',
					'label'    => esc_html__( 'Border', 'absolute-addons' ),
					'selector' => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li{{CURRENT_ITEM}} a:hover',
				]
			);

			$repeater->end_controls_tab();

			$repeater->start_controls_tab(
				'advance_tab_active',
				[
					'label' => esc_html__( 'Active', 'absolute-addons' ),
				]
			);

			$repeater->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name'           => 'advance_tab_title_background_active',
					'fields_options' => [
						'background' => [
							'label' => esc_html__( 'Background', 'absolute-addons' ),
						],
					],
					'types'          => [ 'classic', 'gradient' ],
					'selector'       => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li{{CURRENT_ITEM}}.is-open a, {{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li-{{CURRENT_ITEM}}.is-open a::before, {{WRAPPER}} .advance-tab-item-five .absp-nav-tab li-{{CURRENT_ITEM}}.is-open::before',
				]
			);

			$repeater->add_control(
				'advance_tab_title_color_active',
				[
					'label'     => esc_html__( 'Title Color', 'absolute-addons' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li{{CURRENT_ITEM}}.is-open a' => 'color: {{VALUE}}',
					],
				]
			);



			$repeater->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name'     => 'advance_tab_title_box_shadow_active',
					'label'    => esc_html__( 'Box Shadow', 'absolute-addons' ),
					'selector' => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li{{CURRENT_ITEM}}.is-open a',
				]
			);

			$repeater->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'     => 'advance_tab_title_border_active',
					'label'    => esc_html__( 'Border', 'absolute-addons' ),
					'selector' => '{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li{{CURRENT_ITEM}}.is-open a',
				]
			);

			$repeater->add_responsive_control(
				'advance_tab_title_border_radius_active',
				[
					'label'      => esc_html__( 'Advance Tab Border Radius', 'absolute-addons' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .absp-advance-tab .absp-tab-container .absp-nav-tab li{{CURRENT_ITEM}}.is-open a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$repeater->end_controls_tab();

			$repeater->end_controls_tabs();


		}

		$this->add_control(
			'absp_tabs_' . $style,
			[
				'label'       => __( 'Tabs Items', 'absolute-addons' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'absp_tab_title'     => __( 'Tab #1', 'absolute-addons' ),
						'absp_content_title' => __( 'Tab #1 Content Title', 'absolute-addons' ),
						'absp_tab_content'   => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'absolute-addons' ),
					],
					[
						'absp_tab_title'     => __( 'Tab #2', 'absolute-addons' ),
						'absp_content_title' => __( 'Tab #2 Content Title', 'absolute-addons' ),
						'absp_tab_content'   => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'absolute-addons' ),
					],
					[
						'absp_tab_title'     => __( 'Tab #3', 'absolute-addons' ),
						'absp_content_title' => __( 'Tab #3 Content Title', 'absolute-addons' ),
						'absp_tab_content'   => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'absolute-addons' ),
					],
					[
						'absp_tab_title'     => __( 'Tab #4', 'absolute-addons' ),
						'absp_content_title' => __( 'Tab #4 Content Title', 'absolute-addons' ),
						'absp_tab_content'   => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'absolute-addons' ),
					],
				],
				'title_field' => '{{{ absp_tab_title }}}',
				'condition'   => [
					'advance_tab' => $style,
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$style = $settings['advance_tab'];

		$this->current_style = $style;

		$id_int = substr( $this->get_id_int(), 0, 3 );

		$this->add_render_attribute( 'tab-content-title', 'class', 'content-title' );



		?>
		<div class="absp-wrapper absp-widget">
			<div class="absp-wrapper-inside">
				<div class="absp-wrapper-content">
					<!-- absp-advance-tab -->
					<div class="absp-advance-tab element-<?php echo esc_attr( $style ); ?>">
						<div class="advance-tab-item-<?php echo esc_attr( $style ); ?> absp-tab-container">
							<ul class="absp-nav-tab">
								<?php
								if ( isset( $settings[ 'absp_tabs_' . $style ] ) ) {
									foreach ( $settings[ 'absp_tabs_' . $style ] as $index => $tab ) {
										$is_open   = 0 == $index ? 'is-open' : '';
										$this->render_tab_title( $tab, $is_open, $id_int . ( $index + 1 ), $tab['_id'] );
									}
								}


								?>
							</ul>
							<div class="absp-tab-content">
								<?php if ( isset( $settings[ 'absp_tabs_' . $style ] ) ) {
									foreach ( $settings[ 'absp_tabs_' . $style ] as $index => $tab ) {
										$nav_body_style = '';
										if ( 'four' === $style ) {
											$nav_body_style = 'background-image: url(' . esc_url( $tab['tab_image']['url'] ) . ');';
										}
										$args = [
											'index'     => $index,
											'tab_count' => ( $index + 1 ),
											'tab'       => $tab,
											'style'     => $style,
										];
									?>
									<div class="absp-tab-item <?php echo 0 == $index ? 'is-open' : ''; ?>" id="absp-tab-<?php echo esc_attr( $id_int . ( $index + 1 ) ); ?>">
										<?php
										if ( 'six' === $style ) {
											$this->render_tab_content_title( $tab, $settings );
										}
										?>
										<div class="absp-nav-body" style="<?php echo esc_attr( $nav_body_style ); ?>">
											<?php $this->render_template( $style, $args ); ?>
										</div>
									</div>
								<?php }
								} ?>
							</div>
						</div>
					</div>
					<!-- absp-advance-tab -->
				</div>
			</div>
		</div>
		<?php
	}

	protected function render_tab_title( $tab, $is_open, $tab_id, $current_element_id ) {
		if ( in_array( $this->current_style, [ 'five', 'six', 'seven', 'eight', 'ten' ] ) ) {
			?>
			<li class="<?php echo esc_attr( $is_open ); ?>">
				<a href="#absp-tab-<?php echo esc_attr( $tab_id ); ?>">
					<?php
					if ( ! empty( $tab['tab_icon']['value'] ) ) {
						Icons_Manager::render_icon( $tab['tab_icon'], [ 'aria-hidden' => 'true' ] );
					}
					?>
					<span><?php absp_render_title( $tab['absp_tab_title'] ); ?></span>
				</a>
			</li>
			<?php
		} else {
			?>
			<li class="<?php echo esc_attr( $is_open ); ?> elementor-repeater-item-<?php echo $current_element_id ?> ">
				<a href="#absp-tab-<?php echo esc_attr( $tab_id ); ?>">
					<span><?php absp_render_title( $tab['absp_tab_title'] ); ?></span>
				</a>
			</li>
			<?php
		}
	}

	protected function render_tab_content_title( $tab, $settings ) {
		if ( ! empty( $tab['absp_content_title'] ) ) {
			absp_tag_start( $settings['title_html_tag'], 'tab-content-title', $this );
			absp_render_title( $tab['absp_content_title'] );
			absp_tag_end( $settings['title_html_tag'] );
		}
	}

	protected function render_tab_content_sub_title( $tab, $settings ) {
		if ( ! empty( $tab['absp_content_sub_title'] ) ) {
			?><span class="content-sub-title"><?php absp_render_title( $tab['absp_content_sub_title'] ); ?></span><?php
		}
	}

	protected function render_tab_content( $tab ) {
		if ( ! empty( $tab['absp_tab_content'] ) ) {
			?>
			<div class="tab-body-content">
				<?php absp_render_content( $tab['absp_tab_content'] ); ?>
			</div>
			<?php
		}
	}
}
