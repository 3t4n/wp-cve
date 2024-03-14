<?php
	namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Widget_SVG extends Widget_Base {

	public function get_name() {
		return 'ep-inline-svg';
	}

	public function get_title() {
		return __( 'Inline SVG Plus!', 'elements-plus' );
	}

	public function get_icon() {
		return 'ep-icon ep-icon-inline';
	}

	public function get_categories() {
		return [ 'elements-plus' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_inline_svg',
			[
				'label' => __( 'Inline SVG Plus!', 'elements-plus' ),
			]
		);

		$this->add_control(
			'svg',
			[
				'label'              => __( 'Select SVG file', 'elements-plus' ),
				'type'               => Controls_Manager::MEDIA,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'link',
			[
				'label'              => __( 'Link', 'elements-plus' ),
				'type'               => Controls_Manager::URL,
				'placeholder'        => 'https://your-site.com',
				'default'            => [
					'url' => '',
				],
				'separator'          => 'after',
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Styles', 'elements-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'     => __( 'Alignment', 'elements-plus' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'elements-plus' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elements-plus' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'elements-plus' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'label'       => __( 'Width', 'elements-plus' ),
				'description' => __( 'Set the maximum width', 'elements-plus' ),
				'type'        => Controls_Manager::SLIDER,
				'default'     => [
					'size' => '',
				],
				'range'       => [
					'px' => [
						'min'  => 0,
						'max'  => 1920,
						'step' => 10,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units'  => [ 'px', '%' ],
				'selectors'   => [
					'{{WRAPPER}} .ep-inline-svg > svg' => 'width: {{SIZE}}{{UNIT}}; height: auto; display: inline-block !important;',
				],
			]
		);

		$this->add_control(
			'override_colors',
			[
				'label' 		=> __( 'Customize Color', 'elements-plus' ),
				'description'	=> __( 'Modify the color of svg elements that have a fill or stroke color set.', 'elements-plus' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> '',
				'label_on' 		=> __( 'Yes', 'elements-plus' ),
				'label_off' 	=> __( 'No', 'elements-plus' ),
				'return_value' 	=> 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'color',
			[
				'label'     => __( 'Color', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_1,
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .ep-inline-svg' => 'color: {{VALUE}} !important',
				],
				'condition'	=> [
					'override_colors!' => '',
				],
			]
		);

		$this->add_control(
			'color_hover',
			[
				'label'     => __( 'Hover Color', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => [
					'type'  => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_1,
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .ep-inline-svg:hover' => 'color: {{VALUE}} !important',
				],
				'condition'	=> [
					'override_colors!' => '',
				],
			]
		);

		$this->add_control(
			'remove_inline_css',
			[
				'label'              => __( 'Remove Inline CSS', 'elements-plus' ),
				'description'        => __( 'An SVG might include inline styles. If custom color options do not work, try this option to remove these styles. Please note, this will also remove non-color related styling.', 'elements-plus' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => '',
				'label_on'           => __( 'Yes', 'elements-plus' ),
				'label_off'          => __( 'No', 'elements-plus' ),
				'return_value'       => 'yes',
				'frontend_available' => true,
				'condition'	=> [
					'override_colors!' => '',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings();
		$tag      = $settings['link']['url'] ? 'a' : 'div';

		// Add main class to wrapper.
		$this->add_render_attribute( 'svg', 'class', 'ep-inline-svg' );

		if ( ! empty( $settings['link']['url'] ) ) {

			$this->add_render_attribute( 'svg', 'href', $settings['link']['url'] );

			if ( $settings['link']['is_external'] ) {
				$this->add_render_attribute( 'svg', 'target', '_blank' );
			}

			if ( ! empty( $settings['link']['nofollow'] ) ) {
				$this->add_render_attribute( 'svg', 'rel', 'nofollow' );
			}
		}

		?>

		<div class="ep-inline-svg-wrapper">
			<?php if ( ! empty( $settings['svg']['url'] ) ) { ?>
				<<?php echo esc_html( $tag ); ?>
					<?php echo $this->get_render_attribute_string( 'svg' ); ?>>
				</<?php echo esc_html( $tag ); ?>>
			<?php } ?>
		</div>

		<?php
	}

	protected function content_template() {}

}

	add_action(
		'elementor/widgets/register',
		function ( $widgets_manager ) {
			$widgets_manager->register( new Widget_SVG() );
		}
	);

	add_action(
		'elementor/frontend/after_enqueue_scripts',
		function () {
			wp_enqueue_script( 'ep-inline-svg-scripts', ELEMENTS_PLUS_URL . 'assets/js/ep-inline-svg.js', array(), ELEMENTS_PLUS_VERSION, true );
		}
	);

	add_action(
		'elementor/frontend/after_enqueue_styles',
		function () {
			wp_enqueue_style( 'ep-inline-svg-styles', ELEMENTS_PLUS_URL . 'assets/css/ep-inline-svg.css', array(), ELEMENTS_PLUS_VERSION );
		}
	);
