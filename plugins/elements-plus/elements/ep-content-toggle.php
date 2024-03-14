<?php
	namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Widget_EP_Content_Toggle extends Widget_Base {

	public function get_name() {
		return 'ep-content-toggle-plus';
	}

	public function get_title() {
		return __( 'Content Toggle Plus!', 'elements-plus' );
	}

	public function get_icon() {
		return 'ep-icon ep-icon-toggle';
	}

	public function get_categories() {
		return [ 'elements-plus' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_content_primary',
			[
				'label' => __( 'Primary Content', 'elements-plus' ),
			]
		);

		$this->add_control(
			'label_primary',
			[
				'label'   => __( 'Label', 'elements-plus' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Primary', 'elements-plus' ),
			]
		);

		$this->add_control(
			'content_type_primary',
			[
				'label'   => __( 'Content Type', 'elements-plus' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'content'  => __( 'Content', 'elements-plus' ),
					'template' => __( 'Elementor Template', 'elements-plus' ),
				],
				'default' => 'content',
			]
		);

		$this->add_control(
			'template_primary',
			[
				'label'     => __( 'Choose Template', 'elements-plus' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => ep_get_elementor_templates(),
				'condition' => [
					'content_type_primary' => 'template',
				],
			]
		);

		$this->add_control(
			'content_primary',
			[
				'label'     => __( 'Content', 'elements-plus' ),
				'type'      => Controls_Manager::WYSIWYG,
				'default'   => __( 'Primary Content', 'elements-plus' ),
				'condition' => [
					'content_type_primary' => 'content',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_content_secondary',
			[
				'label' => __( 'Secondary Content', 'elements-plus' ),
			]
		);

		$this->add_control(
			'label_secondary',
			[
				'label'   => __( 'Label', 'elements-plus' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Secondary', 'elements-plus' ),
			]
		);

		$this->add_control(
			'content_type_secondary',
			[
				'label'   => __( 'Content Type', 'elements-plus' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'content'  => __( 'Content', 'elements-plus' ),
					'template' => __( 'Elementor Template', 'elements-plus' ),
				],
				'default' => 'content',
			]
		);

		$this->add_control(
			'template_secondary',
			[
				'label'     => __( 'Choose Template', 'elements-plus' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => ep_get_elementor_templates(),
				'condition' => [
					'content_type_secondary' => 'template',
				],
			]
		);

		$this->add_control(
			'content_secondary',
			[
				'label'     => __( 'Content', 'elements-plus' ),
				'type'      => Controls_Manager::WYSIWYG,
				'default'   => __( 'Secondary Content', 'elements-plus' ),
				'condition' => [
					'content_type_secondary' => 'content',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_widget_title',
			[
				'label' => __( 'Toggle Styles', 'elements-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'shape',
			[
				'label'   => __( 'Shape', 'elements-plus' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'round',
				'options' => [
					'round'  => __( 'Round', 'elements-plus' ),
					'square' => __( 'Square', 'elements-plus' ),
				],
			]
		);

		$this->add_control(
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
				'default'   => 'center',
				'selectors' => [
					'{{WRAPPER}} .ep-ct-switch-wrapper' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'toggle_background_color_inactive',
			[
				'label'     => __( 'Background Color Inactive', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#CCC',
				'scheme'    => [
					'type'  => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} .ep-ct-switch .ep-ct-slider' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'toggle_background_color_active',
			[
				'label'     => __( 'Background Color Active', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#2196F3',
				'scheme'    => [
					'type'  => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} .ep-ct-switch input:checked + .ep-ct-slider' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .ep-ct-switch input:focus + .ep-ct-slider' => 'box-shadow: 0 0 1px {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'bottom_margin',
			[
				'label'     => __( 'Bottom Margin', 'plugin-domain' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 300,
				'step'      => 1,
				'default'   => 10,
				'selectors' => [
					'{{WRAPPER}} .ep-ct-switch-wrapper' => 'margin-bottom: {{VALUE}}px;',
				],
			]
		);

		$this->add_control(
			'toggle_label_color',
			[
				'label'     => __( 'Label Color', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'scheme'    => [
					'type'  => \Elementor\Core\Schemes\Color::get_type(),
					'value' => \Elementor\Core\Schemes\Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} .ep-ct-switch-label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label'    => __( 'Label Typography', 'elements-plus' ),
				'name'     => 'toggle_label_typography',
				'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .ep-ct-switch-label',
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings          = $this->get_settings();
		$shape             = 'round' === $settings['shape'] ? 'ep-ct-round' : '';
		$label_primary     = $settings['label_primary'];
		$label_secondary   = $settings['label_secondary'];
		$content_primary   = '';
		$content_secondary = '';

		if ( 'content' === $settings['content_type_primary'] ) {
			$content_primary = $this->parse_text_editor( $settings['content_primary'] );
		} elseif ( 'template' === $settings['content_type_primary'] ) {
			$el_frontend     = new Frontend();
			$content_primary = $el_frontend->get_builder_content( $settings['template_primary'], true );
		}

		if ( 'content' === $settings['content_type_secondary'] ) {
			$content_secondary = $this->parse_text_editor( $settings['content_secondary'] );
		} elseif ( 'template' === $settings['content_type_secondary'] ) {
			$el_frontend       = new Frontend();
			$content_secondary = $el_frontend->get_builder_content( $settings['template_secondary'], true );
		}

		?>

		<div class="ep-ct-outer-wrapper">
			<div class="ep-ct-switch-wrapper">
				<?php if ( $label_primary ) : ?>
					<span class="ep-ct-switch-label"><?php echo esc_html( $label_primary ); ?></span>
				<?php endif; ?>
				<label class="ep-ct-switch">
					<input type="checkbox" class="ep-ct-input" />
					<span class="ep-ct-slider <?php echo esc_attr( $shape ); ?>"></span>
				</label>
				<?php if ( $label_secondary ) : ?>
					<span class="ep-ct-switch-label"><?php echo esc_html( $label_secondary ); ?></span>
				<?php endif; ?>
			</div>
			<div class="ep-ct-content-wrapper">
				<div class="ep-ct-primary active ep-ct-content">
					<?php
					if ( $content_primary ) {
						echo $content_primary;
					}
					?>
				</div>
				<div class="ep-ct-secondary ep-ct-content">
					<?php
					if ( $content_secondary ) {
						echo $content_secondary;
					}
					?>
				</div>
			</div>
		</div>
		<?php
	}

	protected function content_template() {}

}

add_action(
	'elementor/widgets/register',
	function ( $widgets_manager ) {
		$widgets_manager->register( new Widget_EP_Content_Toggle() );
	}
);

function ep_get_elementor_templates() {
		$args = [
			'post_type'      => 'elementor_library',
			'posts_per_page' => -1,
		];

		$elementor_templates = get_posts( $args );
		$options             = wp_list_pluck( $elementor_templates, 'post_title', 'ID' );

		return $options;
}
