<?php
	namespace Elementor;

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Widget_Preloader extends Widget_Base {

		public function get_name() {
			return 'preloader';
		}

		public function get_title() {
			return __( 'Preloader Plus!', 'elements-plus' );
		}

		public function get_icon() {
			return 'ep-icon ep-icon-preloader';
		}

		public function get_categories() {
			return [ 'elements-plus' ];
		}

		protected function register_controls() {
			$this->start_controls_section(
				'section_preloader',
				[
					'label' => __( 'Preloader Plus!', 'elements-plus' ),
				]
			);

			$this->add_control(
				'preloader_icon',
				[
					'label'   => __( 'Preloader Icon', 'elements-plus' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'eicon-spinner',
					'options' => [
						'eicon-spinner'  => __( 'Spinner', 'elements-plus' ),
						'eicon-redo'     => __( 'Circle', 'elements-plus' ),
						'eicon-sync'     => __( 'Refresh', 'elements-plus' ),
						'eicon-cog'      => __( 'Cog', 'elements-plus' ),
					],
				]
			);

			$this->add_control(
				'custom_icon',
				[
					'label'       => __( 'Custom Elementor icon', 'elements-plus' ),
					'label_block' => true,
					'type'        => Controls_Manager::TEXT,
					'description' => __( 'Elementor Icon i.e. eicon-star', 'elements-plus' ),
				]
			);

			$this->add_control(
				'preloader_preview',
				[
					'label'     => __( 'Toggle Preloader Preview', 'elements-plus' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'none',
					'options'   => [
						'block' => __( 'On', 'elements-plus' ),
						'none'  => __( 'Off', 'elements-plus' ),
					],
					'selectors' => [
						'.elementor-editor-active {{WRAPPER}} #preloader' => 'display: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'preloader_size',
				[
					'label' => __( 'Preloader Size', 'elements-plus' ),
					'type' => Controls_Manager::SLIDER,
					'default' => [
						'size' => 40,
					],
					'range' => [
						'px' => [
							'min' => 10,
							'max' => 300,
							'step' => 1,
						],
					],
					'size_units' => [ 'px' ],
					'selectors' => [
						'{{WRAPPER}} #preloader i' => 'font-size: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} #preloader #status' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}; margin: calc(-{{SIZE}}{{UNIT}} / 2) 0 0 calc(-{{SIZE}}{{UNIT}} / 2);',
					],
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'section_style',
				[
					'label' => __( 'Preloader', 'elements-plus' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_control(
				'icon_color',
				[
					'label' => __( 'Icon Color', 'elements-plus' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_4,
					],
					'default' => '#000',
					'selectors' => [
						'{{WRAPPER}} div#status' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'background_color',
				[
					'label' => __( 'Background Color', 'elements-plus' ),
					'type' => Controls_Manager::COLOR,
					'scheme' => [
						'type' => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_4,
					],
					'default' => '#fff',
					'selectors' => [
						'{{WRAPPER}} div#preloader' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->end_controls_section();
		}

		protected function render() {
			$settings = $this->get_settings();
			$icon     = ! empty( $settings['custom_icon'] ) ? $settings['custom_icon'] : $settings['preloader_icon'];

			?>
				<div id="ep-pr-<?php echo esc_attr( $this->get_id() ); ?>">
					<div id="preloader">
					  <div id="status"><i class="<?php echo esc_attr( $icon ); ?> eicon-animation-spin" aria-hidden="true"></i></div>
					</div>
				</div>
				<script>
					jQuery(document).ready(function () {
						jQuery(document).trigger('elementor/render/ep_preloader', '#ep-pr-<?php echo esc_attr( $this->get_id() ); ?>');
					});
				</script>
			<?php
		}

		protected function content_template() {}

	}

	add_action( 'elementor/widgets/register', function ( $widgets_manager ) {
		$widgets_manager->register( new Widget_Preloader() );
	} );
