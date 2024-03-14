<?php
	namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class EP_Image_Accordion extends Widget_Base {

	public function get_name() {
		return 'ep-image-accordion';
	}

	public function get_title() {
		return __( 'Image Accordion Plus!', 'elements-plus' );
	}

	public function get_icon() {
		return 'ep-icon ep-icon-union';
	}

	public function get_categories() {
		return [ 'elements-plus' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_image_accordion',
			[
				'label' => __( 'Image Accordion Plus!', 'elements-plus' ),
			]
		);

		$this->add_control(
			'accordion',
			[
				'label'       => __( 'Accordion', 'elements-plus' ),
				'type'        => Controls_Manager::REPEATER,
				'title_field' => __( 'Accordion Item', 'elements-plus' ),
				'fields'      => [
					[
						'name'    => 'accordion_image',
						'label'   => __( 'Choose Image', 'plugin-domain' ),
						'type'    => Controls_Manager::MEDIA,
						'default' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
					[
						'name'         => 'accordion_item_is_active',
						'label'        => __( 'Active Item', 'elements-plus' ),
						'type'         => Controls_Manager::SWITCHER,
						'label_on'     => __( 'Yes', 'elements-plus' ),
						'label_off'    => __( 'No', 'elements-plus' ),
						'return_value' => 'yes',
						'default'      => '',
					],
					[
						'name'        => 'accordion_item_title',
						'label'       => __( 'Item Title', 'elements-plus' ),
						'type'        => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'Item Title', 'elements-plus' ),
					],
					[
						'name'        => 'accordion_item_text',
						'label'       => __( 'Item Text', 'elements-plus' ),
						'type'        => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'Text', 'elements-plus' ),
					],
					[
						'name'          => 'accordion_item_url',
						'label'         => __( 'Link', 'plugin-domain' ),
						'type'          => Controls_Manager::URL,
						'placeholder'   => __( 'https://your-link.com', 'plugin-domain' ),
						'show_external' => true,
						'default'       => [
							'url'         => '',
							'is_external' => true,
							'nofollow'    => true,
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'label'           => __( 'Accordion height', 'elements-plus' ),
				'type'            => Controls_Manager::SLIDER,
				'size_units'      => [ 'px' ],
				'range'           => [
					'px' => [
						'min'  => 50,
						'max'  => 1000,
						'step' => 1,
					],
				],
				'devices'         => [ 'desktop', 'tablet', 'mobile' ],
				'desktop_default' => [
					'size' => 250,
					'unit' => 'px',
				],
				'tablet_default'  => [
					'size' => 250,
					'unit' => 'px',
				],
				'mobile_default'  => [
					'size' => 150,
					'unit' => 'px',
				],
				'selectors'       => [
					'{{WRAPPER}} .ep-accordion-container, {{WRAPPER}} .ep-accordion-container ul li' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'expanded_height',
			[
				'label'          => __( 'Accordion hover height', 'elements-plus' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => [ 'px' ],
				'range'          => [
					'px' => [
						'min'  => 50,
						'max'  => 1000,
						'step' => 1,
					],
				],
				'devices'        => [ 'mobile' ],
				'mobile_default' => [
					'size' => 250,
					'unit' => 'px',
				],
				'selectors'      => [
					'{{WRAPPER}} .ep-accordion-container ul li:hover' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'show_text',
			[
				'label'        => __( 'Show title & caption', 'elements-plus' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'elements-plus' ),
				'label_off'    => __( 'Hide', 'elements-plus' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'text_align_horizontal',
			[
				'label'     => __( 'Horizontal text alignment', 'elements-plus' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'left',
				'options'   => [
					'left'    => __( 'Left', 'elements-plus' ),
					'center'  => __( 'Center', 'elements-plus' ),
					'right'   => __( 'Right', 'elements-plus' ),
					'justify' => __( 'Justify', 'elements-plus' ),
				],
				'selectors' => [
					'{{WRAPPER}} .ep-accordion-container ul li div span .ep-accordion-content' => 'text-align: {{OPTION}};',
				],
				'condition' => [
					'show_text' => 'yes',
				],
			]
		);

		$this->add_control(
			'text_align_vertical',
			[
				'label'     => __( 'Vertical text alignment', 'elements-plus' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'bottom',
				'options'   => [
					'top'    => __( 'Top', 'elements-plus' ),
					'middle' => __( 'Middle', 'elements-plus' ),
					'bottom' => __( 'Bottom', 'elements-plus' ),
				],
				'selectors' => [
					'{{WRAPPER}} .ep-accordion-container ul li div span .ep-accordion-content' => 'vertical-align: {{OPTION}};',
				],
				'condition' => [
					'show_text' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_image_accordion_styles',
			[
				'label' => __( 'Styles', 'elements-plus' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => __( 'Title Color', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .ep-accordion-container h2' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'label'    => __( 'Title Typography', 'elements-plus' ),
				'selector' => '{{WRAPPER}} .ep-accordion-container h2',
				'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
			]
		);

		$this->add_control(
			'caption_color',
			[
				'label'     => __( 'Caption Color', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .ep-accordion-container p' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'caption_typography',
				'label'    => __( 'Caption Typography', 'elements-plus' ),
				'selector' => '{{WRAPPER}} .ep-accordion-container p',
				'scheme'   => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_3,
			]
		);

		$this->add_control(
			'overlay_color',
			[
				'label'     => __( 'Image overlay hover color', 'elements-plus' ),
				'type'      => Controls_Manager::COLOR,
				'alpha'     => true,
				'default'   => 'rgba(0,0,0,.4)',
				'selectors' => [
					'{{WRAPPER}} .ep-accordion-container ul:hover li.active span' => 'background-color: transparent;',
					'{{WRAPPER}} .ep-accordion-container ul li.active span,{{WRAPPER}} .ep-accordion-container ul:hover li.active:hover span,{{WRAPPER}} .ep-accordion-container ul:focus li.active:focus span,{{WRAPPER}} .ep-accordion-container ul li:hover span,{{WRAPPER}} .ep-accordion-container ul li:focus span' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings  = $this->get_settings();
		$accordion = $settings['accordion'];
		$show_text = $settings['show_text'];

		if ( ! $accordion ) {
			return;
		}

		?>
			<div class="ep-accordion-container">
				<ul>
					<?php foreach ( $accordion as $accordion_item ) : ?>
						<?php
							$img          = wp_get_attachment_url( $accordion_item['accordion_image']['id'] );
							$title        = $accordion_item['accordion_item_title'];
							$text         = $accordion_item['accordion_item_text'];
							$active_class = 'yes' === $accordion_item['accordion_item_is_active'] ? 'active' : '';
							$url          = $accordion_item['accordion_item_url'];
							$target       = $url['is_external'] ? ' target="_blank"' : '';
							$nofollow     = $url['nofollow'] ? ' rel="nofollow"' : '';
						?>
						<li class="<?php echo esc_attr( $active_class ); ?>" style="background-image:url('<?php echo esc_url_raw( $img ); ?>');">
							<div>
								<span>
									<?php if ( 'yes' === $show_text && ( $title || $text ) ) : ?>
										<div class="ep-accordion-content">
											<?php if ( $url['url'] ) : ?>
												<a href="<?php echo esc_url_raw( $url['url'] ); ?>" <?php echo $target . ' ' . $nofollow; ?>>
											<?php endif; ?>
												<?php if ( $title ) : ?>
													<h2><?php echo esc_html( $title ); ?></h2>
												<?php endif; ?>
												<?php if ( $text ) : ?>
													<p><?php echo esc_html( $text ); ?></p>
												<?php endif; ?>
											<?php if ( $url['url'] ) : ?>
												</a>
											<?php endif; ?>
										</div>
									<?php endif; ?>
								</span>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php
	}

	protected function content_template() {}
}

	add_action(
		'elementor/widgets/register',
		function ( $widgets_manager ) {
			$widgets_manager->register( new EP_Image_Accordion() );
		}
	);

