<?php

namespace AbsoluteAddons\Widgets;

use Elementor\Controls_Manager;
use AbsoluteAddons\Absp_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * @since 1.1.0
 */
class Absoluteaddons_Style_Multi_Color_Heading extends Absp_Widget {

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
		return 'absolute-multi-color-heading';
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
		return __( 'Multi Color Heading', 'absolute-addons' );
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
		return 'absp eicon-site-title';
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array(
			'absolute-addons-core',
			'absp-multi-color-heading',
			'ico-font',
		);
	}

	/**
	 * Requires js files.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array(
			'absolute-addons-core',
		);
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

		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Content', 'absolute-addons' ),
			)
		);

		$this->add_control(
			'style_variation',
			array(
				'label'       => esc_html__( 'Multi Color Heading Style', 'absolute-addons' ),
				'label_block' => true,
				'type'        => Controls_Manager::SELECT2,
				'options'     => array(
					'one' => esc_html__( 'Style One', 'absolute-addons' ),
					'two' => esc_html__( 'Style Two', 'absolute-addons' ),
				),
				'default'     => 'one',
			)
		);
		$this->add_control(
			'multi_color_heading_sub_title_one',
			[
				'label'       => __( 'Sub Title', 'absolute-addons' ),
				'label_block' => false,
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'DESIGN<br>STYLE', 'absolute-addons' ),
				'condition'   => [
					'style_variation' => 'one',
				],
			]
		);
		$this->add_control(
			'multi_color_heading_sub_title_two',
			[
				'label'       => __( 'Sub Title', 'absolute-addons' ),
				'label_block' => false,
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'DesignStyle', 'absolute-addons' ),
				'condition'   => [
					'style_variation' => 'two',
				],
			]
		);
		$this->add_control(
			'multi_color_heading_number',
			[
				'label'       => __( 'Style Number', 'absolute-addons' ),
				'label_block' => false,
				'type'        => Controls_Manager::NUMBER,
				'default'     => __( '1', 'absolute-addons' ),
			]
		);
		$this->add_control(
			'multi_color_heading_title',
			[
				'label'       => __( 'Title', 'absolute-addons' ),
				'label_block' => false,
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'Default', 'absolute-addons' ),
			]
		);
		$this->add_control(
			'multi_color_heading_icon',
			[
				'label'     => __( 'Icon or SVG', 'absolute-addons' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => [
					'value'   => 'absp absp-simple-right',
					'library' => 'solid',
				],
				'condition' => [
					'style_variation' => 'one',
				],
			]
		);
		$this->add_responsive_control(
			'multi_color_heading_width',
			[
				'label'          => __( 'Width', 'absolute-addons' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => [ '%', 'px' ],
				'separator'      => 'before',
				'range'          => [
					'px' => [
						'max' => 1000,
					],
				],
				'default'        => [
					'size' => 100,
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'selectors'      => [
					'{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item .multi-color-heading-item' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'multi_color_heading_style_one_align',
			[
				'label'     => __( 'Alignment', 'absolute-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start' => [
						'title' => __( 'Left', 'absolute-addons' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'     => [
						'title' => __( 'Center', 'absolute-addons' ),
						'icon'  => 'eicon-text-align-center',
					],
					'flex-end'   => [
						'title' => __( 'Right', 'absolute-addons' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'center',
				'selectors' => [
					'{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item .multi-color-heading-item .multi-color-heading-flex-wrapper' => 'justify-content: {{VALUE}};',
				],
				'condition' => [
					'style_variation' => 'one',
				],
			]
		);
		$this->add_responsive_control(
			'multi_color_heading_align',
			[
				'label'     => __( 'Alignment', 'absolute-addons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'absolute-addons' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'absolute-addons' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'absolute-addons' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'center',
				'selectors' => [
					'{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item .multi-color-heading-item' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .absp-wrapper .absp-multi-color-heading-item .multi-color-heading-item' => 'margin: 0 auto; margin-{{VALUE}}: 0',
				],
				'condition' => [
					'style_variation' => 'two',
				],
			]
		);

		$this->end_controls_section();

		//Include style control Set files of multi color heading
		require_once( ABSOLUTE_ADDONS_PATH . '/widgets/multi-color-heading/controller/style-controller-multi-color-heading-settings.php' );
		require_once( ABSOLUTE_ADDONS_PATH . '/widgets/multi-color-heading/controller/style-controller-multi-color-heading-item-sub-title.php' );
		require_once( ABSOLUTE_ADDONS_PATH . '/widgets/multi-color-heading/controller/style-controller-multi-color-heading-item-number.php' );
		require_once( ABSOLUTE_ADDONS_PATH . '/widgets/multi-color-heading/controller/style-controller-multi-color-heading-item-icon.php' );
		require_once( ABSOLUTE_ADDONS_PATH . '/widgets/multi-color-heading/controller/style-controller-multi-color-heading-item-title.php' );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_inline_editing_attributes( 'multi_color_heading_sub_title_one' );
		$this->add_render_attribute( 'multi_color_heading_sub_title_one', 'class', 'multi-color-heading-sub-title' );

		$this->add_inline_editing_attributes( 'multi_color_heading_sub_title_two' );
		$this->add_render_attribute( 'multi_color_heading_sub_title_two', 'class', 'multi-color-heading-sub-title' );

		$this->add_inline_editing_attributes( 'multi_color_heading_number' );
		$this->add_render_attribute( 'multi_color_heading_number', 'class', 'style-number' );

		$this->add_inline_editing_attributes( 'multi_color_heading_title' );
		$this->add_render_attribute( 'multi_color_heading_title', 'class', 'multi-color-heading-title' );
		?>

		<div class="absp-wrapper absp-widget">
			<div class="absp-wrapper-inside">
				<div class="absp-wrapper-content">
					<!-- absp-multi-color-heading-item -->
					<div
						class="absp-multi-color-heading-item element-<?php echo esc_attr( $settings['style_variation'] ); ?>">
						<?php require __DIR__ . '/template/multi-color-heading-item-' . $settings['style_variation'] . '.php'; ?>
					</div>
					<!-- absp-multi-color-heading-item -->
				</div>
			</div>
		</div>
		<?php
	}
}
