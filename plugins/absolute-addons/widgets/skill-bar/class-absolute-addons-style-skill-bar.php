<?php

namespace AbsoluteAddons\Widgets;

use AbsoluteAddons\Controls\Absp_Control_Styles;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use AbsoluteAddons\Absp_Widget;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @since 1.1.0
 */
class Absoluteaddons_Style_Skill_Bar extends Absp_Widget {


	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 * @since  1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_name() {
		return 'absolute-skill-bar';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 * @since  1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Skill Bar', 'absolute-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 * @since  1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'absp eicon-skill-bar';
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array(
			'absolute-addons-custom',
			'icofont',
			'font-awesome',
			'progressBar',
			'absolute-addons-core',
			'absp-skill-bar',
		);
	}

	/**
	 * Requires js files.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return [
			'waypoints',
			'progressBar',
			'absp-skill-bar',
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
	 * @since  1.1.0
	 *
	 * @access public
	 *
	 */
	public function get_categories() {
		return array( 'absp-widgets' );
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @param
	 *
	 * @since  1.1.0
	 *
	 * @access protected
	 */
	protected function register_controls() {
		/**
		 * Fires after controllers are registered.
		 *
		 * @param Absoluteaddons_Style_List $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.3
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/starts' ), [ &$this ] );

		$this->start_section( 'section_template', __( 'Template', 'absolute-addons' ) );

		$layouts = apply_filters( $this->get_prefixed_hook( 'styles' ), [
			'one'       => __( 'Style One', 'absolute-addons' ),
			'two'       => __( 'Style Two (Upcoming)', 'absolute-addons' ),
			'three-pro' => __( 'Style Three (Pro)', 'absolute-addons' ),
			'four-pro'  => __( 'Style Four (Upcoming – Pro)', 'absolute-addons' ),
			'five-pro'  => __( 'Style Five (Upcoming – Pro)', 'absolute-addons' ),
			'six-pro'   => __( 'Style Six (Upcoming – Pro)', 'absolute-addons' ),
			'seven-pro' => __( 'Style Seven (Upcoming – Pro)', 'absolute-addons' ),
			'eight-pro' => __( 'Style Eight (Upcoming – Pro)', 'absolute-addons' ),
		] );

		$this->add_control(
			'absolute_skill_bar',
			[
				'label'       => __( 'Layouts', 'absolute-addons' ),
				'label_block' => true,
				'type'        => Absp_Control_Styles::TYPE,
				'options'     => $layouts,
				'disabled'    => [
					'two',
					'four-pro',
					'five-pro',
					'six-pro',
					'seven-pro',
					'eight-pro',
				],
				'default'     => 'one',
			]
		);

		$this->init_pro_alert( [
			'three-pro',
			'four-pro',
			'five-pro',
			'six-pro',
			'seven-pro',
			'eight-pro',
		] );

		$this->end_controls_section();

		$this->content_section();

		$this->render_controller( 'template-style-control' );

		/**
		 * Fires after controllers are registered.
		 *
		 * @param Absoluteaddons_Style_list $this Current instance of WP_Network_Query (passed by reference).
		 *
		 * @since 1.0.0
		 *
		 */
		do_action_ref_array( $this->get_prefixed_hook( 'controllers/ends' ), [ &$this ] );

	}

	protected function content_section() {
		$this->start_controls_section(
			'skill_bar_content_section',
			[
				'label' => __( 'Content Section', 'absolute-addons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'title',
			[
				'label'       => __( 'Title', 'absolute-addons' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Title #1', 'absolute-addons' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'title_tag',
			[
				'label'     => __( 'Title HTML Tag', 'absolute-addons' ),
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
				'default'   => 'h4',
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'hide_title',
			[
				'label'      => __( 'Hide Title', 'absolute-addons' ),
				'type'       => Controls_Manager::SWITCHER,
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'title',
							'operator' => '!==',
							'value'    => '',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'icon',
			[
				'label'            => __( 'Icon', 'absolute-addons' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'absolute-addons',
				'recommended'      => [
					'fa-solid'  => [
						'angle-right',
						'camera',
						'cloud',
						'envelope',
						'magic',
						'paint-brush',
						'chart-pie',

						'code',
						'code-branch',
					],
					'fa-brands' => [
						'dribbble',
						'behance',
						'twitter',
						'500px',

						'html5',
						'css3',
						'js',
						'php',
						'wordpress',
						'wordpress-simple',
						'node-js',
						'react',
						'vuejs',
					],
				],
			]
		);

		$repeater->add_control(
			'value',
			[
				'label'   => __( 'Progress (%)', 'absolute-addons' ),
				'type'    => Controls_Manager::NUMBER,
				'max'     => 100,
				'min'     => 0,
				'default' => 45,
			]
		);

		$this->render_controller( 'progress-controller', compact( 'repeater' ) );

		$repeater->add_control(
			'delay',
			[
				'label'       => __( 'Delay', 'absolute-addons' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'description' => __( 'Animation start delay in milliseconds', 'absolute-addons' ),
			]
		);

		$this->render_controller( 'tooltip-controller', compact( 'repeater' ) );

		$repeater->add_responsive_control(
			'skill_bar_height',
			[
				'label'      => __( 'Skill Bar Height', 'absolute-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'step' => 1,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .absp-skill-bar {{CURRENT_ITEM}}' => '--progressbar-height: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$repeater->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'wrapper_background',
				'fields_options' => [
					'background' => [
						'label' => __( 'Progressbar Color', 'absolute-addons' ),
					],
				],
				'types'          => [ 'classic', 'gradient' ],
				'selector'       => '{{WRAPPER}} .absp-skill-bar {{CURRENT_ITEM}} .ab-progress-bar-wrap',
			]
		);

		$repeater->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'fill_background',
				'fields_options' => [
					'background' => [
						'label' => __( 'Progressbar Fill Color', 'absolute-addons' ),
					],
				],
				'types'          => [ 'classic', 'gradient' ],
				'selector'       => '{{WRAPPER}} .absp-skill-bar {{CURRENT_ITEM}} .ab-progress-bar',
			]
		);

		$repeater->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Title Color', 'absolute-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .absp-skill-bar {{CURRENT_ITEM}}' => '--progress-title-clr: {{VALUE}} !important;',
				],
			]
		);

		$repeater->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'           => 'number_background',
				'fields_options' => [
					'background' => [
						'label' => __( 'Number Background', 'absolute-addons' ),
					],
				],
				'types'          => [ 'classic', 'gradient' ],
				'selector'       => '{{WRAPPER}} .absp-skill-bar {{CURRENT_ITEM}} .ab-progress-indicator-inner',
			]
		);

		$repeater->add_control(
			'arrow_color',
			[
				'label'     => esc_html__( 'Tooltip Arrow Color', 'absolute-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .absp-skill-bar {{CURRENT_ITEM}}' => '--progress-indicator-arrow-background: {{VALUE}}',
				],
				'condition' => [
					'tooltip' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'number_color',
			[
				'label'     => esc_html__( 'Number Color', 'absolute-addons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .absp-skill-bar {{CURRENT_ITEM}}' => '--progress-indicator-color: {{VALUE}} !important;',
				],
			]
		);

		$repeater->add_responsive_control(
			'bar_radius',
			[
				'label'      => __( 'Skill Bar Radius', 'absolute-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'rem', 'em', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .absp-skill-bar {{CURRENT_ITEM}}' => '--progressbar-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		$this->render_controller( 'indicator-radius-controller', compact( 'repeater' ) );

		$this->add_control(
			'skills',
			[
				'label'       => __( 'Skill Bar', 'absolute-addons' ),
				'type'        => Controls_Manager::REPEATER,
				'show_label'  => true,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'title' => __( 'Title #1', 'absolute-addons' ),
						'value' => __( '50', 'absolute-addons' ),
					],
					[
						'title' => __( 'Title #2', 'absolute-addons' ),
						'value' => __( '60', 'absolute-addons' ),
					],
					[
						'title' => __( 'Title #3', 'absolute-addons' ),
						'value' => __( '75', 'absolute-addons' ),
					],
				],
				'title_field' => '{{{ elementor.helpers.renderIcon( this, icon, { "aria-hidden": "true" }, "i", "panel" ) }}} {{{ title }}}',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$style    = $settings['absolute_skill_bar'];
		?>
		<div class="absp-wrapper absp-widget">
			<div class="absp-wrapper-inside">
				<div class="absp-wrapper-content">
					<!-- absp-skill-bar -->
					<div class="absp-skill-bar element-<?php echo esc_attr( $style ); ?>">
						<div class="absp-skill-bar-<?php echo esc_attr( $style ); ?>">
							<?php
							foreach ( $settings['skills'] as $skill ) {

								// Add necessary classes here to avoid ui shift when js takes over.
								$has_tooltip = isset( $skill['tooltip'] ) && 'yes' === $skill['tooltip'];
								$progress   = isset( $skill['progress'] ) && 0 <= $skill['progress'] || 100 >= $skill['progress'] ? $skill['progress'] : 0;
								$this->add_render_attribute(
									'progress_bar',
									[
										'class'         => 'ab-progress ' . ( $has_tooltip ? 'ab-progress-tooltip' : 'ab-progress-inline' ) . ' elementor-repeater-item-' . $skill['_id'],
										'data-value'    => 0 > $skill['value'] || 100 < $skill['value'] ? 45 : $skill['value'],
										'data-progress' => $progress,
										'data-delay'    => 0 > $skill['delay'] ? $skill['delay'] : 0,
										'data-tooltip'  => $has_tooltip ? 'true' : 'false',
									],
									null,
									true
								);

								$this->add_render_attribute( 'title', 'class', 'ab-progress-title', true );

								$title_before = $title_after = '';

								if ( isset( $skill['hide_title'] ) && 'yes' === $skill['hide_title'] ) {
									$title_before = '<span class="sr-only">';
									$title_after  = '</span>';
								}
								?>
								<div <?php $this->print_attribute( 'progress_bar' ); ?>>
									<?php
									absp_tag_start( $skill['title_tag'], 'title', $this );

									$this->render_icon( 'icon', 'fas fa-angle-right', $skill );

									absp_render_title( $skill['title'], $title_before, $title_after );

									absp_tag_end( $skill['title_tag'] );
									?>
									<?php if ( 'one' === $style && ! $has_tooltip ) { ?>
									<div class="ab-progress-bar-wrap">
										<div class="ab-progress-bar" style="width: 0;"></div>
									</div>
									<div class="ab-progress-indicator">
										<div class="ab-progress-indicator-inner">
											<span class="ab-progress-percent">0%</span>
										</div>
									</div>
									<?php } ?>
								</div>
							<?php } ?>
						</div>
					</div>
					<!-- absp-skill-bar-->
				</div><!-- end .absp-wrapper-content -->
			</div><!-- end .absp-wrapper-inside -->
		</div><!-- end .absp-wrapper -->
		<?php
	}

	protected function content_template() {
		?>
		<# var style = settings.absolute_skill_bar; #>
		<div class="absp-wrapper">
			<div class="absp-wrapper-inside">
				<div class="absp-wrapper-content">
					<!-- absp-skill-bar -->
					<div class="absp-skill-bar element-{{ style }}">
						<div class="absp-skill-bar-{{ style }}">
							<# _.each( settings.skills, function( skill ) {
								var hasTooltip = skill.tooltip && 'yes' === skill.tooltip;
								var progress   = skill.progress && 0 <= skill.progress || 100 >= skill.progress ? skill.progress : 0;
								view.addRenderAttribute(
									'progress_bar',
									{
										'class'         : 'ab-progress ' + ( hasTooltip ? 'ab-progress-tooltip' : 'ab-progress-inline' ) + ' elementor-repeater-item-' + skill._id,
										'data-value'    : 0 > skill.value || 100 < skill.value ? 45 : skill.value,
										'data-progress' : progress,
										'data-delay'    : skill.delay,
										'data-tooltip'  : hasTooltip ? 'true' : 'false',
									},
									null,
									true
								);

								view.addRenderAttribute( 'title', 'class', 'ab-progress-title', true );

								var title, titleTag = elementor.helpers.validateHTMLTag( skill.title_tag );

								if ( skill.hide_title && 'yes' === skill.hide_title ) {
									title = '<span class="sr-only">' + skill.title + '</span>';
								} else {
									title = skill.title;
								}

								var icon = elementor.helpers.renderIcon( view, skill.icon, { 'aria-hidden': 'true' }, 'i', 'object' );

								if ( icon.rendered ) {
									title = icon.value + ' ' + title;
								}

								title = '<' + titleTag + ' ' + view.getRenderAttributeString( 'title' ) +'>' + title + '</' + titleTag + '>';
							#>
							<div {{{ view.getRenderAttributeString( 'progress_bar' ) }}}>
								{{{ title }}}
								<# if( 'one' === style && ! hasTooltip ) { #>
								<div class="ab-progress-bar-wrap">
									<div class="ab-progress-bar" style="width: 0;"></div>
								</div>
								<div class="ab-progress-indicator">
									<div class="ab-progress-indicator-inner">
										<span class="ab-progress-percent">0%</span>
									</div>
								</div>
								<# } #>
							</div>
							<# } ); #>
						</div>
					</div>
					<!-- absp-skill-bar-->
				</div><!-- end .absp-wrapper-content -->
			</div><!-- end .absp-wrapper-inside -->
		</div><!-- end .absp-wrapper -->
		<?php
	}
}
