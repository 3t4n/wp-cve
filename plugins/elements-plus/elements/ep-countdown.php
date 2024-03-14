<?php
	namespace Elementor;

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	class Widget_CountDown extends Widget_Base {

		public function get_name() {
			return 'ep_countdown';
		}

		public function get_title() {
			return __( 'CountDown Plus!', 'elements-plus' );
		}

		public function get_icon() {
			return 'ep-icon ep-icon-countdown';
		}

		public function get_categories() {
			return [ 'elements-plus' ];
		}

		protected function register_controls() {
			$this->start_controls_section(
				'section_countdown',
				[
					'label' => __( 'CountDown Plus!', 'elements-plus' ),
				]
			);

			$this->add_control(
				'time_out',
				[
					'label'   => __( 'Time out', 'elements-plus' ),
					'type'    => Controls_Manager::DATE_TIME,
				]
			);

			$this->add_control(
				'end_text',
				[
					'label'       => __( 'Countdown over text', 'elements-plus' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => __( 'The countdown is over!', 'elements-plus' ),
					'placeholder' => __( 'Text displayed after countdown ends.', 'elements-plus' ),
				]
			);

			$this->add_control(
				'toggle_labels',
				[
					'label'        => __( 'Toggle Labels', 'elements-plus' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Show', 'elements-plus' ),
					'label_off'    => __( 'Hide', 'elements-plus' ),
					'return_value' => 'show',
					'default'      => 'show',
				]
			);

			$this->add_control(
				'toggle_days',
				[
					'label'        => __( 'Toggle Days', 'elements-plus' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Show', 'elements-plus' ),
					'label_off'    => __( 'Hide', 'elements-plus' ),
					'return_value' => 'show',
					'default'      => 'show',
				]
			);

			$this->add_control(
				'toggle_hours',
				[
					'label'        => __( 'Toggle Hours', 'elements-plus' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Show', 'elements-plus' ),
					'label_off'    => __( 'Hide', 'elements-plus' ),
					'return_value' => 'show',
					'default'      => 'show',
				]
			);

			$this->add_control(
				'toggle_minutes',
				[
					'label'        => __( 'Toggle Minutes', 'elements-plus' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Show', 'elements-plus' ),
					'label_off'    => __( 'Hide', 'elements-plus' ),
					'return_value' => 'show',
					'default'      => 'show',
				]
			);

			$this->add_control(
				'toggle_seconds',
				[
					'label'        => __( 'Toggle Seconds', 'elements-plus' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => __( 'Show', 'elements-plus' ),
					'label_off'    => __( 'Hide', 'elements-plus' ),
					'return_value' => 'show',
					'default'      => 'show',
				]
			);

			$this->add_control(
				'view',
				[
					'label'   => __( 'View', 'elements-plus' ),
					'type'    => Controls_Manager::HIDDEN,
					'default' => 'traditional',
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'module_style',
				[
					'label' => __( 'Module Styles', 'elements-plus' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_control(
				'background_color',
				[
					'label'     => __( 'Background Color', 'elements-plus' ),
					'type'      => Controls_Manager::COLOR,
					'scheme'    => [
						'type'  => \Elementor\Core\Schemes\Color::get_type(),
						'value' => \Elementor\Core\Schemes\Color::COLOR_4,
					],
					'default'   => '#000',
					'selectors' => [
						'{{WRAPPER}} div.elements-plus-countdown-item' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'        => 'border',
					'label'       => __( 'Border', 'elements-plus' ),
					'selector'    => '{{WRAPPER}} .elements-plus-countdown-item',
				]
			);

			$this->add_control(
				'border_radius',
				[
					'label'      => __( 'Border Radius', 'elements-plus' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors'  => [
						'{{WRAPPER}} div.elements-plus-countdown-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name'     => 'label_box_shadow',
					'selector' => '{{WRAPPER}} .elements-plus-countdown-item',
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'digit_style',
				[
					'label' => __( 'Digit Styles', 'elements-plus' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_control(
				'digit_color',
				[
					'label'     => __( 'Clock Digit Color', 'elements-plus' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#fff',
					'selectors' => [
						'{{WRAPPER}} .elements-plus-countdown-number' => 'color: {{VALUE}};',
					],
					'separator' => 'before',
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'digit_typography',
					'label'    => __( 'Typography', 'elements-plus' ),
					'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_4,
					'selector' => '{{WRAPPER}} p.elements-plus-countdown-number',
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'typography_style',
				[
					'label' => __( 'Label Styles', 'elements-plus' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'typography',
					'label'    => __( 'Typography', 'elements-plus' ),
					'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_4,
					'selector' => '{{WRAPPER}} p.elements-plus-countdown-label',
				]
			);

			$this->add_control(
				'label_text_color',
				[
					'label'     => __( 'Text Color', 'elements-plus' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#fff',
					'selectors' => [
						'{{WRAPPER}} p.elements-plus-countdown-label' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'text_padding',
				[
					'label'      => __( 'Text Padding', 'elements-plus' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} p.elements-plus-countdown-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->end_controls_section();

			$this->start_controls_section(
				'timeout_typography_style',
				[
					'label' => __( 'Timeout Text Styles', 'elements-plus' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'timeout_typography',
					'label'    => __( 'Typography', 'elements-plus' ),
					'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_4,
					'selectors' => [
						'{{WRAPPER}} div.message',
						'{{WRAPPER}} p.expired',
					]
				]
			);

			$this->add_control(
				'timeout_text_color',
				[
					'label'     => __( 'Text Color', 'elements-plus' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#000',
					'selectors' => [
						'{{WRAPPER}} div.message' => 'color: {{VALUE}};',
						'{{WRAPPER}} p.expired' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'timeout_text_padding',
				[
					'label'      => __( 'Text Padding', 'elements-plus' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} div.message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} p.expired' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->end_controls_section();
		}

		protected function render() {
			$settings = $this->get_settings();
			$time_out = str_replace( ' ', 'T', $settings['time_out'] );

			if ( empty( $time_out ) ) {
				return;
			}

			$diff         = strtotime( $settings['time_out'] ) - current_time( 'timestamp' );
			$expired_text = $settings['end_text'];
			$label        = $settings['toggle_labels'] ? true : false;
			$days         = $settings['toggle_days'] ? true : false;
			$hours        = $settings['toggle_hours'] ? true : false;
			$minutes      = $settings['toggle_minutes'] ? true : false;
			$seconds      = $settings['toggle_seconds'] ? true : false;

			?>
			<div class="elements-plus-countdown" data-date="<?php echo esc_attr( $time_out ); ?>">
				<div class="elements-plus-countdown-wrap">
					<?php if ( $diff > 0 ) { ?>
						<?php if ( $days ) : ?>
							<div class="elements-plus-countdown-item">
								<p class="elements-plus-countdown-number elements-plus-countdown-days"></p>
								<?php if ( true === $label ) : ?>
									<p class="elements-plus-countdown-label elements-plus-countdown-label-days"><?php esc_html_e( 'Days', 'elements-plus' ); ?></p>
								<?php endif; ?>
							</div>
						<?php endif; ?>
						<?php if ( $hours ) : ?>
							<div class="elements-plus-countdown-item">
								<p class="elements-plus-countdown-number elements-plus-countdown-hours"></p>
								<?php if ( true === $label ) : ?>
									<p class="elements-plus-countdown-label elements-plus-countdown-label-hours"><?php esc_html_e( 'Hours', 'elements-plus' ); ?></p>
								<?php endif; ?>
							</div>
						<?php endif; ?>
						<?php if ( $minutes ) : ?>
							<div class="elements-plus-countdown-item">
								<p class="elements-plus-countdown-number elements-plus-countdown-minutes"></p>
								<?php if ( true === $label ) : ?>
									<p class="elements-plus-countdown-label elements-plus-countdown-label-minutes"><?php esc_html_e( 'Minutes', 'elements-plus' ); ?></p>
								<?php endif; ?>
							</div>
						<?php endif; ?>
						<?php if ( $seconds ) : ?>
							<div class="elements-plus-countdown-item">
								<p class="elements-plus-countdown-number elements-plus-countdown-seconds"></p>
								<?php if ( true === $label ) : ?>
									<p class="elements-plus-countdown-label elements-plus-countdown-label-seconds"><?php esc_html_e( 'Seconds', 'elements-plus' ); ?></p>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					<?php } else { ?>
						<p class="expired"><?php echo esc_html( $expired_text ); ?></p>
					<?php } ?>
				</div>
			</div>
			<?php
		}

		protected function content_template() {}
	}

	add_action( 'elementor/widgets/register', function ( $widgets_manager ) {
		$widgets_manager->register( new Widget_CountDown() );
	} );
